<?php

namespace App\Core;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as MailException;

/**
 * PHPMailer + SMTP sarmalayici. HTML mailler View emails/ sablonlarindan render edilir.
 */
class Mailer
{
    public static function send(string $to, string $subject, string $template, array $data = [], ?string $toName = null): bool
    {
        $cfg = App::get()->config['mail'];

        $view = new View();
        $body = $view->render('emails/' . $template, array_merge($data, ['subject' => $subject]), 'emails/layout');

        if (!class_exists(PHPMailer::class)) {
            // composer install yapilmamissa logla (gelistirme ortami)
            Logger::warning('PHPMailer yok; mail gonderilemedi', ['to' => $to, 'subject' => $subject]);
            return false;
        }

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = $cfg['host'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $cfg['username'];
            $mail->Password   = $cfg['password'];
            $mail->SMTPSecure = $cfg['encryption'];
            $mail->Port       = $cfg['port'];
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom($cfg['from_addr'], $cfg['from_name']);
            $mail->addAddress($to, $toName ?? '');
            $mail->addReplyTo($cfg['admin_addr'], $cfg['from_name']);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->AltBody = trim(strip_tags($body));

            $mail->send();
            return true;
        } catch (MailException $e) {
            Logger::error('Mail gonderim hatasi: ' . $mail->ErrorInfo, ['to' => $to]);
            return false;
        }
    }

    public static function toAdmin(string $subject, string $template, array $data = []): bool
    {
        $cfg = App::get()->config['mail'];
        return self::send($cfg['admin_addr'], $subject, $template, $data);
    }
}
