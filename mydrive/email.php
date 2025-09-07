<?php
$to = "ramanujnishad@gmail.com";
$subject = "Test Mail";
$message = "Hello! This is a test email message.";
$headers = "From: Genius <g6676829@gmail.com> \r\n";
$headers .= "Reply-To: g6676829@gmail.com \r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; @charset=UTF-8\r\n";

if (mail($to, $subject, $message, $headers)) {
    echo "Email successfully sent to $to...";
} else {
    echo "Email sending failed...";
}
?>
