<?php

// $to_email = "alvarollvv@gmail.com";
// $subject = "Simple Email Test via PHP";
// $body = "Hi,nn This is test email send by PHP Script";
// $headers = "From: alvarollvv@gmail.com";

// if (mail($to_email, $subject, $body, $headers)) {
// echo "Email successfully sent to $to_email...";
// } else {
// echo "Email sending failed...";
// }



$to = 'alvarollvv@gmail.com';
$subject = 'Heloo to xamp';
$message = 'This is a test';
$headers = 'From: alvarollvv@gmail.com';
if (mail($to, $subject, $message, $message)){
    echo "the mesagge to sending ";
} else{
    echo "ERROR";
}
