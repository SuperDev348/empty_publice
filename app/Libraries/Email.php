<?php

namespace App\Libraries;


use App\Models\Setting;
use DOMDocument;
use function GuzzleHttp\Promise\exception_for;
use Illuminate\Support\Facades\Mail;
use Config;
use Illuminate\Validation\ValidationException;
use Mockery\Exception;

class Email
{
    public function sendEmail($mailText, $email, $subject = null, $fileNameToStore = null)
    {
        $fromName = Setting::select('setting_value')->where('setting_name', 'email_from_name')->first()->setting_value;
        $fromAddress = Setting::select('setting_value')->where('setting_name', 'email_from_address')->first()->setting_value;
        $fromPass = Setting::select('setting_value')->where('setting_name', 'email_smtp_password')->first()->setting_value;
        $fromDriver = Setting::select('setting_value')->where('setting_name', 'email_driver')->first()->setting_value;
        $fromHost = Setting::select('setting_value')->where('setting_name', 'email_smtp_host')->first()->setting_value;
        $fromPort = Setting::select('setting_value')->where('setting_name', 'email_port')->first()->setting_value;
        $fromType = Setting::select('setting_value')->where('setting_name', 'email_encryption_type')->first()->setting_value;
        $mandrillApi = Setting::select('setting_value')->where('setting_name', 'mandrill_api')->first()->setting_value;
        $sparkpostApi = Setting::select('setting_value')->where('setting_name', 'sparkpost_api')->first()->setting_value;
        $mailgunDomain = Setting::select('setting_value')->where('setting_name', 'mailgun_domain')->first()->setting_value;
        $mailgunApi = Setting::select('setting_value')->where('setting_name', 'mailgun_api')->first()->setting_value;

        if ($fromDriver && $fromAddress) {
            Config::set('mail.username', $fromAddress);
            Config::set('mail.password', $fromPass);
            Config::set('mail.host', $fromHost);
            Config::set('mail.driver', $fromDriver);
            Config::set('mail.port', $fromPort);
            Config::set('mail.encryption', $fromType);
            Config::set('mail.from.address', $fromAddress);
            Config::set('mail.from.name', $fromName);
            Config::set('services.mandrill.secret', $mandrillApi);
            Config::set('services.sparkpost.secret', $sparkpostApi);
            Config::set('services.mailgun.domain', $mailgunDomain);
            Config::set('services.mailgun.secret', $mailgunApi);

            if ($fileNameToStore != null) {
                $pdf = 'uploads/pdf/' . $fileNameToStore;
                Mail::send([], [], function ($message) use ($email, $subject, $mailText, $pdf) {
                    $message->to($email)->subject($subject)->setBody($mailText, 'text/html')->Attach(\Swift_Attachment::fromPath($pdf));
                });
            } else {
                Mail::send([], [], function ($message) use ($email, $subject, $mailText) {
                    $message->to($email)->subject($subject)->setBody($mailText, 'text/html');
                });
            }

            return true;

        } else {

            return false;
        }
    }

}