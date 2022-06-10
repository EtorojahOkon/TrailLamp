<?php
/**
    * @Mail class
    *
    * Editing this file may cause breakage in your application
*/

require("./app/utilities/PHPMailer/src/PHPMailer.php");
require("./app/utilities/PHPMailer/src/SMTP.php");
require("./app/utilities/PHPMailer/src/Exception.php");

class Mail{
    /**
        *@private properties
    */
    private $host;
    private $port;
    private $username;
    private $password;
    private $from;

    /**
        *@construct method
    */
    public function __construct() {
        $env = new Env();
        $this->host = $env->MAIL_HOST;
        $this->port = $env->MAIL_PORT;
        $this->username = $env->MAIL_USERNAME;
        $this->password = $env->MAIL_PASSWORD;
        $this->from = $env->MAIL_SENTFROM;
    }
    
    /**
        *@send mail class method
    */
    public function sendMail($to, $subject, $message) {
        $h = $this->host;
        $u = $this->username;
        $p = $this->password;
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = "ssl";
        $mail->CharSet = 'UTF-8';
        $mail->Host = $h;
        $mail->Port = $this->port;
        $mail->IsHTML(true);
        $mail->Username = $u;
        $mail->Password = $p;
        $mail->SetFrom($u, $this->from);
        $mail->Subject = $subject;
        $contenthtml = file_get_contents("./mail.html");
        $mail->Body = str_replace("**********", $message, $contenthtml);
        $mail->AddAddress($to);
        if (!$mail->Send()) {
            return false;
        }
        else {
            return true;
        }
    }

   
    
   
}
