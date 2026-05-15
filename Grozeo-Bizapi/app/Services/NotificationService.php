<?php

namespace App\Services;

use App\Contracts\NotificationServiceInterface;
use App\Sms\SmsProviderInterFace;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService implements NotificationServiceInterface
{
    private SmsProviderInterFace $smsProvider;

    public function __construct(SmsProviderInterFace $smsProvider)
    {
        $this->smsProvider = $smsProvider;
    }

    public function sendSms(string $phone, string $message, int $storegroupId): bool
    {
        try {
            $this->smsProvider->send($phone, $message);
            return true;
        } catch (\Throwable $e) {
            Log::error('SMS send failed', ['phone' => $phone, 'error' => $e->getMessage()]);
            return false;
        }
    }

    public function sendEmail(string $to, string $subject, string $body, int $storegroupId): bool
    {
        try {
            Mail::raw($body, function ($message) use ($to, $subject) {
                $message->to($to)->subject($subject);
            });
            return true;
        } catch (\Throwable $e) {
            Log::error('Email send failed', ['to' => $to, 'error' => $e->getMessage()]);
            return false;
        }
    }

    public function sendPushNotification(string $deviceToken, string $title, string $body, array $data = []): bool
    {
        try {
            $fcm = app(\App\CloudFcmNotification::class);
            $fcm->sendNotification($deviceToken, $title, $body, $data);
            return true;
        } catch (\Throwable $e) {
            Log::error('Push notification failed', ['token' => substr($deviceToken, 0, 10) . '...', 'error' => $e->getMessage()]);
            return false;
        }
    }
}
