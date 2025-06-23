<?php
declare(strict_types=1);

namespace App\Mailer;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Cake\Log\Log;

class AppMailer
{
    public static function sendEmail($to, $subject, $body, $fromEmail = 'noreply@example.com', $fromName = 'My App')
    {
        require_once ROOT . '/vendor/autoload.php';

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();                                  
            $mail->Host       = 'mail.compavop.com';  // Update if needed
            $mail->SMTPAuth   = true;                           
            $mail->Username   = 'ssingh@compavop.com';  
            $mail->Password   = 'qHrJp{?E+&Mv';  
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // Use TLS for port 587
            $mail->Port       = 587; 

            // If SSL is needed, use this instead:
            // $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            // $mail->Port       = 465;

            // Bypass SSL verification if needed (for testing)
            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer'       => false,
                    'verify_peer_name'  => false,
                    'allow_self_signed' => true,
                ],
            ];

            // Sender and recipient
            $mail->setFrom('ssingh@compavop.com', 'Sender Name'); 
            $mail->addAddress($to, '');
            $mail->addReplyTo($fromEmail, $fromName);
            
            // Email content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;

            // Send email
            if ($mail->send()) {
                return true;
            } else {
                Log::error('Email Error: ' . $mail->ErrorInfo);
                return false;
            }
        } catch (Exception $e) {
            Log::error('PHPMailer Error: ' . $e->getMessage());
            return false;
        }
    }
}