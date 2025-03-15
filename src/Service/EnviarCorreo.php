<?php

namespace App\Service;

use App\Entity\Correo;
use Doctrine\ORM\EntityManagerInterface;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Psr\Log\LoggerInterface;

class EnviarCorreo {
    public function __construct(private EntityManagerInterface $em, private LoggerInterface $logger) {}

    public function enviar(Subscriptores $subscriptor, $email_cliente, $plantilla_id, $nombre_campana, $asunto, $cuerpo): bool{
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = getenv("MAIL_HOST");
            $mail->SMTPAuth = filter_var(getenv("SMTPAUTH"), FILTER_VALIDATE_BOOLEAN);
            $mail->Username = getenv("MAIL_USERNAME");
            $mail->Password = getenv("MAIL_PASSWORD");
            $mail->SMTPSecure = getenv("MAIL_SMTP_TYPE");

            $mail->From = getenv("MAIL_EMAIL");
            $mail->FromName = $nombre_campana;
            $mail->addAddress($subscriptor->getEmail());
            $mail->addReplyTo($email_cliente);

            $mail->WordWrap = 50;
            $mail->isHTML(true);

            $mail->Subject = $asunto;
            $mail->Body    = $cuerpo;
            $mail->AltBody = new \Html2Text\Html2Text($cuerpo);

            $mail->send();
            $this->nuevo_registro_correo($plantilla_id, $subscriptor->getId(), true);
            return true;
        } catch (Exception $e) {
            $this->nuevo_registro_correo($plantilla_id, $subscriptor->getId(), false);
            $this->logger->error(sprintf(
                'Error sending mail: Mailer ErrorInfo: "%s" | Exception message: "%s"',
                $mail->ErrorInfo,
                $e->getMessage()));
                return false;
        };
    }

    public function nuevo_registro_correo($plantilla_id, $subscriptor_id, $enviado){
        $correo = new Correo();
        $correo->setPlantillaId($plantilla_id);
        $correo->setSubscriptorId($subscriptor_id);
        $correo->setEnviado($enviado);
        
        $this->em->persist($correo);
        $this->em->flush();
    }
}
?>
