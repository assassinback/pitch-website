<?php include('config.php');

function sendTestMsg($data = array()) {
    
    //return false;
    $to = null;
    $from_name = null;
    $from_email = null;
    $subject = null;
    $message = null;
    
    foreach($data as $key => $value) {
        ${$key} = $value;
    }
    
    if(!is_array($to)) {
        $to = explode(',', $to);
    }
    
    if (!$from_name) {
        $from_name = SMTP_FROM_NAME;
    }
    
    if (!$from_email) {
        $from_email = SMTP_FROM_EMAIL;
    }
    
    $search_replace = array(
                    '[SITE_TITLE]' => SITE_TITLE,
                    '[SITE_URL]' => SITE_URL,
                    //'[SITE_LOGO]' => SITE_URL . 'images/logo.png',
                    '[SITE_LOGO]' => SITE_URL . 'images/logo_white.png',
                    '[FOOTER_TEXT]' => sprintf(COPYRIGHT_TEXT, date('Y')),
                    '[SUBJECT]' => $subject,
                    '[MESSAGE]' => $message,
    );
    
    $template = file_get_contents(SITE_PATH . 'common/mail.html');
    $message = str_replace(
                array_keys($search_replace),
                array_values($search_replace),
                $template
            );
    
    $mail = new PHPMailer;
    $mail->SMTPDebug = 3;
    
    echo "Host: " . SMTP_HOST . "<br>";
    echo "USERNAME: " . SMTP_USERNAME . "<br>";
    echo "PASSWORD: " . SMTP_PASSWORD . "<br>";
    $mail->isSMTP();
    $mail->Host = "auth.smtp.1and1.co.uk";
    $mail->SMTPAuth = true;
    $mail->Username = "mail@mgtcloud.co.uk";
    $mail->Password = "mgtm4il789";
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    
    $mail->setFrom($from_email, $from_name);
    foreach($to as $address) {
        $mail->addAddress($address);
    }
    
    $mail->isHTML(true);
    $mail->Subject = SITE_TITLE . ' - ' . $subject;
    $mail->Body = $message;
    if(!$mail->send()) {
        echo "SMTP fail";
    } else {
        echo "SMTP succcess";
    }
}

$message = '<p>Hi </p>';
$message .= '<p>This is a test message</p>';

$msgdata = array(
    'to' => array("Mayank@webtechsystem.com", "harnish@mgtdesign.co.uk"),
    'subject' => "invite",
    'message' => $message
);
sendTestMsg($msgdata);