<?php

namespace App\Contracts;

interface NotificationServiceInterface
{
    public function sendSms(string $phone, string $message, int $storegroupId): bool;

    public function sendEmail(string $to, string $subject, string $body, int $storegroupId): bool;

    public function sendPushNotification(string $deviceToken, string $title, string $body, array $data = []): bool;
}
