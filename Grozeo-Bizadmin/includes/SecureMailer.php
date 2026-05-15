<?php
/**
 * SecureMailer - Safe email wrapper using PHP's built-in mail capabilities.
 * Replaces the vulnerable PHPMailer 5.1 (CVE-2016-10033).
 */
class SecureMailer
{
    private string $host;
    private int $port;
    private string $username;
    private string $password;
    private string $fromEmail;
    private string $fromName;
    private string $encryption;
    private array $to = [];
    private array $cc = [];
    private array $bcc = [];
    private string $subject = '';
    private string $body = '';
    private string $altBody = '';
    private bool $isHtml = false;
    private array $attachments = [];
    private array $customHeaders = [];

    public function __construct(array $config = [])
    {
        $this->host = $config['host'] ?? '';
        $this->port = (int)($config['port'] ?? 587);
        $this->username = $config['username'] ?? '';
        $this->password = $config['password'] ?? '';
        $this->fromEmail = $config['from_email'] ?? '';
        $this->fromName = $config['from_name'] ?? '';
        $this->encryption = $config['encryption'] ?? 'tls';
    }

    public static function fromGlobals(): self
    {
        global $db;
        $config = [];

        if (isset($db)) {
            $result = $db->query("SELECT mail_host, mail_port, mail_username, mail_password, mail_from, mail_from_name, mail_encryption FROM site_settings LIMIT 1");
            if ($result && $row = $result->fetch_assoc()) {
                $config = [
                    'host' => $row['mail_host'] ?? '',
                    'port' => $row['mail_port'] ?? 587,
                    'username' => $row['mail_username'] ?? '',
                    'password' => $row['mail_password'] ?? '',
                    'from_email' => $row['mail_from'] ?? '',
                    'from_name' => $row['mail_from_name'] ?? '',
                    'encryption' => $row['mail_encryption'] ?? 'tls',
                ];
            }
        }

        return new self($config);
    }

    public function setFrom(string $email, string $name = ''): self
    {
        $this->fromEmail = filter_var($email, FILTER_VALIDATE_EMAIL) ?: $this->fromEmail;
        $this->fromName = $this->sanitizeHeader($name);
        return $this;
    }

    public function addTo(string $email, string $name = ''): self
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->to[] = ['email' => $email, 'name' => $this->sanitizeHeader($name)];
        }
        return $this;
    }

    public function addCc(string $email, string $name = ''): self
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->cc[] = ['email' => $email, 'name' => $this->sanitizeHeader($name)];
        }
        return $this;
    }

    public function addBcc(string $email, string $name = ''): self
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->bcc[] = ['email' => $email, 'name' => $this->sanitizeHeader($name)];
        }
        return $this;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $this->sanitizeHeader($subject);
        return $this;
    }

    public function setBody(string $body, bool $isHtml = true): self
    {
        $this->body = $body;
        $this->isHtml = $isHtml;
        return $this;
    }

    public function setAltBody(string $altBody): self
    {
        $this->altBody = $altBody;
        return $this;
    }

    public function addAttachment(string $path, string $name = ''): self
    {
        if (file_exists($path) && is_readable($path)) {
            $this->attachments[] = [
                'path' => realpath($path),
                'name' => $name ?: basename($path),
            ];
        }
        return $this;
    }

    public function addHeader(string $name, string $value): self
    {
        $this->customHeaders[$this->sanitizeHeader($name)] = $this->sanitizeHeader($value);
        return $this;
    }

    public function send(): bool
    {
        if (empty($this->to)) {
            throw new \RuntimeException('No recipients specified');
        }

        if (!filter_var($this->fromEmail, FILTER_VALIDATE_EMAIL)) {
            throw new \RuntimeException('Invalid sender email');
        }

        if (!empty($this->host)) {
            return $this->sendViaSMTP();
        }

        return $this->sendViaMail();
    }

    private function sendViaSMTP(): bool
    {
        $errno = 0;
        $errstr = '';
        $protocol = $this->encryption === 'ssl' ? 'ssl://' : '';
        $socket = @fsockopen($protocol . $this->host, $this->port, $errno, $errstr, 30);

        if (!$socket) {
            throw new \RuntimeException("SMTP connection failed: $errstr ($errno)");
        }

        stream_set_timeout($socket, 30);

        $this->smtpRead($socket);
        $this->smtpCommand($socket, "EHLO " . gethostname());

        if ($this->encryption === 'tls') {
            $this->smtpCommand($socket, "STARTTLS");
            if (!stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT | STREAM_CRYPTO_METHOD_TLSv1_3_CLIENT)) {
                fclose($socket);
                throw new \RuntimeException('TLS negotiation failed');
            }
            $this->smtpCommand($socket, "EHLO " . gethostname());
        }

        if (!empty($this->username)) {
            $this->smtpCommand($socket, "AUTH LOGIN");
            $this->smtpCommand($socket, base64_encode($this->username));
            $this->smtpCommand($socket, base64_encode($this->password));
        }

        $this->smtpCommand($socket, "MAIL FROM:<{$this->fromEmail}>");

        foreach ($this->to as $recipient) {
            $this->smtpCommand($socket, "RCPT TO:<{$recipient['email']}>");
        }
        foreach ($this->cc as $recipient) {
            $this->smtpCommand($socket, "RCPT TO:<{$recipient['email']}>");
        }
        foreach ($this->bcc as $recipient) {
            $this->smtpCommand($socket, "RCPT TO:<{$recipient['email']}>");
        }

        $this->smtpCommand($socket, "DATA");
        $message = $this->buildMessage();
        fwrite($socket, $message . "\r\n.\r\n");
        $this->smtpRead($socket);

        $this->smtpCommand($socket, "QUIT");
        fclose($socket);

        return true;
    }

    private function sendViaMail(): bool
    {
        $to = $this->formatRecipients($this->to);
        $headers = $this->buildHeaders();
        $params = "-f" . escapeshellarg($this->fromEmail);
        return mail($to, $this->subject, $this->body, $headers, $params);
    }

    private function buildMessage(): string
    {
        $boundary = md5(uniqid(time()));
        $headers = [];

        $headers[] = "From: " . $this->formatAddress($this->fromEmail, $this->fromName);
        $headers[] = "To: " . $this->formatRecipients($this->to);

        if (!empty($this->cc)) {
            $headers[] = "Cc: " . $this->formatRecipients($this->cc);
        }

        $headers[] = "Subject: " . $this->subject;
        $headers[] = "Date: " . date('r');
        $headers[] = "MIME-Version: 1.0";
        $headers[] = "X-Mailer: SecureMailer/1.0";

        foreach ($this->customHeaders as $name => $value) {
            $headers[] = "$name: $value";
        }

        if (!empty($this->attachments)) {
            $headers[] = "Content-Type: multipart/mixed; boundary=\"$boundary\"";
            $body = "--$boundary\r\n";
            $body .= "Content-Type: " . ($this->isHtml ? "text/html" : "text/plain") . "; charset=UTF-8\r\n";
            $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
            $body .= chunk_split(base64_encode($this->body)) . "\r\n";

            foreach ($this->attachments as $att) {
                $body .= "--$boundary\r\n";
                $body .= "Content-Type: application/octet-stream; name=\"{$att['name']}\"\r\n";
                $body .= "Content-Disposition: attachment; filename=\"{$att['name']}\"\r\n";
                $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
                $body .= chunk_split(base64_encode(file_get_contents($att['path']))) . "\r\n";
            }

            $body .= "--$boundary--\r\n";
        } else {
            $headers[] = "Content-Type: " . ($this->isHtml ? "text/html" : "text/plain") . "; charset=UTF-8";
            $headers[] = "Content-Transfer-Encoding: base64";
            $body = chunk_split(base64_encode($this->body));
        }

        return implode("\r\n", $headers) . "\r\n\r\n" . $body;
    }

    private function buildHeaders(): string
    {
        $headers = [];
        $headers[] = "From: " . $this->formatAddress($this->fromEmail, $this->fromName);

        if (!empty($this->cc)) {
            $headers[] = "Cc: " . $this->formatRecipients($this->cc);
        }
        if (!empty($this->bcc)) {
            $headers[] = "Bcc: " . $this->formatRecipients($this->bcc);
        }

        $headers[] = "MIME-Version: 1.0";
        $headers[] = "Content-Type: " . ($this->isHtml ? "text/html" : "text/plain") . "; charset=UTF-8";
        $headers[] = "X-Mailer: SecureMailer/1.0";

        foreach ($this->customHeaders as $name => $value) {
            $headers[] = "$name: $value";
        }

        return implode("\r\n", $headers);
    }

    private function formatAddress(string $email, string $name): string
    {
        if (empty($name)) {
            return $email;
        }
        return "\"" . str_replace('"', '\\"', $name) . "\" <$email>";
    }

    private function formatRecipients(array $recipients): string
    {
        return implode(', ', array_map(function ($r) {
            return $this->formatAddress($r['email'], $r['name']);
        }, $recipients));
    }

    private function sanitizeHeader(string $value): string
    {
        return str_replace(["\r", "\n", "\0"], '', $value);
    }

    private function smtpCommand($socket, string $command): string
    {
        fwrite($socket, $command . "\r\n");
        return $this->smtpRead($socket);
    }

    private function smtpRead($socket): string
    {
        $response = '';
        while ($line = fgets($socket, 515)) {
            $response .= $line;
            if (isset($line[3]) && $line[3] === ' ') {
                break;
            }
        }
        $code = (int)substr($response, 0, 3);
        if ($code >= 400) {
            throw new \RuntimeException("SMTP error ($code): " . trim($response));
        }
        return $response;
    }

    public function clearRecipients(): self
    {
        $this->to = [];
        $this->cc = [];
        $this->bcc = [];
        return $this;
    }

    public function clearAttachments(): self
    {
        $this->attachments = [];
        return $this;
    }
}
