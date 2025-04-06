<?php

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

    function sendEmailAccountRegistration($emailType, $recipientEmail, $recipientName, $subject, $extraData = []) {

        try {

            $emailVerificationCode = $_SESSION['verification_code'] ?? null;

            $smtp_debug = true;

            $email = new PHPMailer(true);

            $email->IsSMTP();

            $email->SMTPAuth = true;

            $email->SMTPSecure = 'ssl';

            $email->Host = "mail.NexureSolutionsservices.com";

            $email->Port = 465;

            $envNoReplyEmail = $_ENV['NO_REPLY_EMAIL'];

            $envNoReplyPassword = $_ENV['NO_REPLY_PASSWORD'];

            $email->Username = $envNoReplyEmail;

            $email->Password = $envNoReplyPassword;

            // Determine the email template based on the email type

            $scriptType = $emailType;

            $submittedsubject = $subject;

            // Include the appropriate email template

            include($_SERVER["DOCUMENT_ROOT"]."/modules/emailIntegrations/index.php");

            $fromName = 'Nexure Solutions LLP';

            $email->SetFrom("noreply@NexureSolutionsservices.com", $fromName);

            $email->AddAddress($recipientEmail, $recipientName);

            $email->isHTML(true);

            $email->Subject = $submittedsubject;

            $email->Body = $HTMLCONTENT; // This variable is set in the included email template

            if(!$email->Send()) {

                echo '<p>System Error: '. $email->ErrorInfo .'</p>';

            }

        } catch (\Throwable $exception) {

            \Sentry\captureException($exception);

        }

    }

?>