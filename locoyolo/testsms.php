<?php
// Require the bundled autoload file - the path may need to change
// based on where you downloaded and unzipped the SDK
require __DIR__ . '/lib/twilio-php-master/Twilio/autoload.php';

// Use the REST API Client to make requests to the Twilio REST API
use Twilio\Rest\Client;

// Your Account SID and Auth Token from twilio.com/console
$sid = 'ACbd1be4877b8ef44951b37e609fc6dff1';
$token = '0ec5e4f67ee86d48278d1c38cebddef4';
$client = new Client($sid, $token);

// Use the client to do fun stuff like send text messages!
$client->messages->create(
// the number you'd like to send the message to
    '+918955511553',
    array(
        // A Twilio phone number you purchased at twilio.com/console
        'from' => '+19597775126',
        // the body of the text message you'd like to send
        'body' => "Hey Jenny! Good luck on the bar exam!"
    )
);
?>

