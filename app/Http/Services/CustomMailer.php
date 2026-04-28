<?php
namespace App\Http\Services;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class CustomMailer
{
    protected $data;

    public function __construct()
    {

    }

    public function sendMail($subject, $body, array $to, $extra = [])
    {
        // require base_path("vendor/autoload.php");
        $mail = new PHPMailer(true); // Passing `true` enables exceptions
        try {
            // Email server settings
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = env("MAIL_HOST");
            $mail->SMTPAuth = true;
            $mail->Username = env("MAIL_USERNAME");
            $mail->Password = env("MAIL_PASSWORD");
            $mail->SMTPSecure = env("MAIL_ENCRYPTION");
            $mail->Port = env("MAIL_PORT");

            $mail->setFrom(env("MAIL_FROM_ADDRESS"), env("MAIL_FROM_NAME"));
            foreach ($to as $value) {
                $mail->addAddress($value);
            }
            if (isset($extra["cc"]) and isset($extra["cc"])) {
                foreach ($extra["cc"] as $value) {
                    $mail->addCC($value);
                }
            }
            if (isset($extra["attachments"]) and isset($extra["attachments"])) {
                foreach ($extra["attachments"] as $value) {
                    $mail->addAttachment($value);
                }
            }
            if (isset($extra["string_attachments"]) and isset($extra["string_attachments"])) {
                foreach ($extra["string_attachments"] as $value) {
                    if (isset($value["base64_decode"]) and $value["base64_decode"] === true) {
                        $mail->addStringAttachment(base64_decode($value["text"]), $value["file_name"], "base64", $value["file_type"], "attachment");
                    }
                    else {
                        $mail->addStringAttachment($value["text"], $value["file_name"], "base64", $value["file_type"], "attachment");
                    }
                }
            }
            $mail->isHTML(true);

            $mail->Subject = $subject;
            $mail->Body = $body;

            if (!$mail->send()) {
                $error = $mail->ErrorInfo;
                \Illuminate\Support\Facades\Log::error("Email send failed", [
                    'error' => $error,
                    'subject' => $subject
                ]);
                return ["status" => "failed", "msg" => $error];
            }
        }
        catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error("Email exception", [
                'message' => $e->getMessage(),
                'subject' => $subject
            ]);
            return ["status" => "failed", "msg" => $e->getMessage()];
        }
        return ["status" => "success", "msg" => null];
    }
}