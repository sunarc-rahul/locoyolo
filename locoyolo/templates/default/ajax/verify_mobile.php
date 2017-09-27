<?php

include_once(ROOTPATH."/config.php");
include_once(INC."/commonfunction.php");
include_once(INC.'/dbfilter.php');
include_once(INC.'/dbqueries.php');
include_once(INC.'/dbhelper.php');
require ROOTPATH. '/lib/twilio-php-master/Twilio/autoload.php';
use Twilio\Rest\Client;

if(isset($_POST['verify_number']))
{
    $insertData['mobile_number']=$number = $_POST['number'];
    $insertData['user_id']= $user_id = $userData->id;
    $insertData['is_verified']=  $is_verified = 'N';
    $insertData['verification_code']=$verification_code =  mt_rand(100000, 999999);
// Your Account SID and Auth Token from twilio.com/console
    $client = new Client(TWLIO_SID, TWLIO_TOKEN);

// Use the client to do fun stuff like send text messages!
    $client->messages->create(
// the number you'd like to send the message to
        "$number",
        array(
            // A Twilio phone number you purchased at twilio.com/console
            'from' => TWLIO_FROM_NUMBER,
            // the body of the text message you'd like to send
            'body' => "Your Locoyolo Mobile number varification code is ".$verification_code.". Thank you Locoyolo Team"
        )
    );

    $DB->InsertRecord('user_contact_varification_status',$insertData);
    echo 'Y';
}
elseif(isset($_POST['verify_code']))
{
    $verification_code = $_POST['verification_code'];
    $number = $_POST['number'];

     $user_id = $userData->id;
    $updateData['is_verified']=  $is_verified = 'N';
    $result = $DB->SelectRecord('user_contact_varification_status', "user_id=$user_id and mobile_number='".$number."' and verification_code='".$verification_code."'");// and verification_code_sent_time > date_sub(now(), interval 5 minute)

    if($result)
    {
        //$updatedata['id'] = $result->id;
        $updatedata['is_verified'] = 'Y';
        $DB->UpdateRecord('user_contact_varification_status',$updatedata,'id='.$result->id);
        echo 'Y';
    }else
    {
        echo 'N';
    }
}
else
{
    echo 'N';
}


?>