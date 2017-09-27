<?php

if($_POST)
{

    $errorcheck = false;
    $emailto = $_POST['emailto'];
    $emailfrom = $_POST['emailfrom'];
    $event_name = $_POST['event_name'];
    $organiser_name = $_POST['organiser_name'];
    $participant_name = $_POST['participant_name'];
    $event_id = $_POST['event_id'];
    $user_id = $_POST["user_id"];
    $event_price = $_POST["event_price"];
    $start_date = $_POST["start_date"];
    $touser = $DB->SelectRecord('public_users', "email = '$emailto'");
    $fromuser = $DB->SelectRecord('public_users', "email = '$emailfrom'");

    if ($errorcheck == false){
        $event_info = $DB->SelectRecord('events', "event_id = '$event_id'");
        foreach( $event_info as $data){
            $result = (array)$data;
            $event_code = $result["event_code"];
            $request_code = $result["request_code"];
        }
//        $message = "Booking code sent";
//
//        $emailmessage='<html>
//			<head>
//			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
//			</head>
//			<body>
//
//			<div>
//					<p>Hello '.$participant_name.'!</p>
//					<p>'.$organiser_name.' would like you to confirm your booking for the event <a href ="http://locoyolo.com/eventdetails.php?eventid='.$event_id.'">'.$event_name.'</a></p>
//					 <p>Please go to this <a href ="'.CreateURL('index.php',"mod=booking&do=booking_confirmation?event_id=".$event_id."&event_code=".$event_code).'">link</a> and enter the boooking confirmation code <strong>'.$request_code.'</strong></p>
//					<p><strong>LocoYolo Team</strong></p>
//
//			</div>
//			</body>
//			</html>';
//        //$to      = $emailto;
//        $to = $emailto;
//        $subject = 'LocoYolo: Confirm your booking for '.$event_name;
//        $headers  = 'MIME-Version: 1.0' . "\r\n";
//        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
//        $headers .= 'From: '.$emailfrom."\r\n";
//
////        mail($to, $subject, $emailmessage, $headers);
//        include_once(ROOTPATH.'/lib/mailer.php');
//        $mail = new mailer();
//        $mail->addTo($emailto, ucwords($touser->firstname.' '.$touser->lastname));
//        $mail->setFrom($emailfrom, ucwords($fromuser->firstname.' '.$fromuser->lastname));
//        $mail->setSubject($subject);
//        $mail->setMessage($emailmessage);
//        $mail->send();
      $booking_status = "Confirmed";

        $dataToUpdate['booking_status']= $booking_status;

        $query=  $DB->UpdateRecord('event_bookings',$dataToUpdate,"event_id=$event_id AND user_id=$user_id");


        echo "Y";
    }
}
// Check name
?>