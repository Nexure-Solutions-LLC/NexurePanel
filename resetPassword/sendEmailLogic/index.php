<?php

    session_start();

    require($_SERVER["DOCUMENT_ROOT"].'/configuration/index.php');
    require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

    use Dotenv\Dotenv;
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    \Sentry\init([
        'dsn' => $_ENV['SENTRY_DSN'],
        'traces_sample_rate' => 1.0,
        'profiles_sample_rate' => 1.0,
    ]);

    try {

        // Set the type of script so that the panel knows what email template
        // to use. Installers of this panel can change the email templates
        // in the /modules/emailIntegrations/templates.

        $scriptType = "Reset Password";

        $emailVerificationCode = $_SESSION['verification_code'];

        $smtp_debug = true;

        $email = new PHPMailer(true);

        $email->IsSMTP();
        $email->SMTPAuth = true;
        $email->SMTPSecure = 'ssl';
        $email->Host = "mail.caliwebdesignservices.com";
        $email->Port = 465;

        $envNoReplyEmail = $_ENV['NO_REPLY_EMAIL'];
        $envNoReplyPassword = $_ENV['NO_REPLY_PASSWORD'];

        $email->Username = $envNoReplyEmail;
        $email->Password = $envNoReplyPassword;

        $submittedsubject = "Here is your requested Cali Web Design verification code"; 

        include($_SERVER["DOCUMENT_ROOT"]."/modules/emailIntegrations/index.php"); 

        $fromName = 'Nexure Solutions LLC';
        $email->SetFrom("noreply@caliwebdesignservices.com", $fromName);
        $email->AddAddress($_SESSION['resetPassswordEmail']);
        $email->isHTML(true);
        $email->Subject = $submittedsubject;
        $email->Body = $HTMLCONTENT;

        if(!$email->Send()) {

            echo '<p>System Error: '. $email->ErrorInfo .'</p>';
        
        }

    } catch (\Throwable $exception) {
            
        \Sentry\captureException($exception);
        
    }

?>