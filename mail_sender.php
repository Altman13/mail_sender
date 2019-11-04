<?php

require 'lib/class.phpmailer.php';
require 'lib/class.smtp.php';
require 'messagebuilder/message.php';
require 'messagebuilder/contact.php';

$mail = new PHPMailer(true);
$mail->Host       = "ssl://smtp.mail.ru";
$mail->IsSMTP();
$mail->Port       = 465;
$mail->SMTPAuth   = true;
$mail->Username   = "";
$mail->Password   = "";
$mail->SMTPSecure = "tls";
$mail->SetFrom('', '');
//$mail->addAttachment( 'file.PDF');
$mail->SMTPDebug  = 2;
$mail->IsHTML(true);
$mail->CharSet = 'UTF-8';
$mail->Subject = '';

$contacts = contacts_show();
foreach ($contacts as $key => $contact) {
    if ($contact['email']) {
        try {
            $mail->addAddress($contact['email']);
            $mail->Body = createMessage(
                $contact['emp_name'],
                $contact['fio_lpr'],
                $contact['opf'],
                $contact['mr_or_ms'],
                $contact['position_lpr'],
                $contact['fio']
            );
            echo $mail->Body;
            if ($mail->send()) {
                echo '<div style=\"padding: 10px\">Письмо отправлено</div>';
            } else {
                echo '<div style=\"padding: 10px\"><strong>Ошибка при отправке анкеты по электронной почте!</strong></div>';
            }
        } catch (Exception $ex) {
            echo 'Произошла ошибка при отправке почты: ' . $ex->show();
        }
    }
}
