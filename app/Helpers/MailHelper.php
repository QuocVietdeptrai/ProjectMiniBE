<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Mail;

class MailHelper
{
    public static function sendMail($to, $subject, $htmlContent)
    {
        try {
            Mail::html($htmlContent, function ($message) use ($to, $subject) {
                $message->to($to)
                        ->subject($subject);
            });
            return true;
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gửi mail thất bại: ' . $e->getMessage()], 500);
        }
    }
}
