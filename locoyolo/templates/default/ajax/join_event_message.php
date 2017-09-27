<?php
include_once("../../config.php");
include_once(INC."/commonfunction.php");
include_once(INC.'/dbfilter.php');
include_once(INC.'/dbqueries.php');
include_once(INC.'/dbhelper.php');

if($_POST)
{

    $errorcheck = false;

    $emailto = $_POST['emailto'];
    $touser = $DB->SelectRecord('public_users', "email = '$emailto'");
    $userImage= (array)$touser;
    $emailfrom = $_POST['emailfrom'];
    $event_name = $_POST['event_name'];
    $organiser_name = $_POST['organiser_name'];
    $participant_name = $_POST['participant_name'];
    $event_id = $_POST['event_id'];
    $booking_message = $_POST['booking_message'];
    $user_id = $_POST["user_id"];
    $event_price = $_POST["event_price"];
    $start_date = $_POST["start_date"];

    if ($booking_message == ""){
        $errorcheck = true;
    }

    if ($errorcheck == false){
        $message = "Your request to join <strong>".$event_name."</strong> has been sent to ".$organiser_name.".";

        $emailmessage='<html>
            <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            </head>
            <body>
            
            <div>
            
                    <p>Hello '.$organiser_name.'!</p>
                    <p>'.$participant_name.' would like to join your event <b>'.$event_name.'</b></a> :</p>
                    <p><table width="300"><tr><td width="40" align="left" valign="top">
                    <img style="border:#CCC 2px solid; border-radius:15px" width="30" src='. ROOTURL.'/' .$userImage["profile_pic"].' />
                    </td><td><strong>'.$participant_name.'</strong><br><br>"'.$booking_message.'"</td></tr></table></p>
                    <p>You can correspond further with him/her over emails.</p>
                    <p><strong>LocoYolo Team</strong></p>
            
            </div>
            </body>
            </html>';
        include_once(ROOTPATH.'/lib/mailer.php');
        $mail = new mailer();
        $subject = 'LocoYolo: Join Request from '.$participant_name.' for '.$event_name;
        $mail->addTo($emailto, ucwords($touser->firstname.' '.$touser->lastname));
        $mail->setSubject($subject);
        $mail->setMessage($emailmessage);
        $mail->send();



        $booking_date = date("Y-m-d");
        $booking_status = "Pending";
        $bookings_info = $DB->SelectRecord('event_bookings', "user_id = '$user_id' and event_id =$event_id");

        $eventUserdata = $DB->SelectRecord('events', " event_id =$event_id");
        $event_data=(array)$eventUserdata;

        if(!is_array($bookings_info))
        {
            $bookings_info = array();
        }
        if (count($bookings_info) < 1){

            $data['user_id'] = $user_id;
            $data['event_id'] = $event_id;
            $data['booking_date'] = $booking_date;
            $data['booking_status'] = $booking_status;
            $data['payment'] = $event_price;
            $data['start_date'] = $start_date;


            $dataNotification['other_user_id'] = $event_data['user_id'];
            $dataNotification['user_id'] = $user_id;
            $dataNotification['event_id'] = $event_id;
            $dataNotification['status'] = "Pending";
            $dataNotification["notification_date"] = date("Y-m-d H:i:s");
            $dataNotification['notification_type'] = "Booking Request";

         $id = $DB->InsertRecord('event_bookings', $data);
         $id = $DB->InsertRecord('notifications', $dataNotification);
        }

        ?>
        <table>
            <tr>
                <td>
                    <font class="ArialVeryDarkGrey15"><? echo $message ?></font><br />
                    <br /><input type="hidden" id="messagestatus" value="Sent" />
                </td>
            </tr>
        </table>
        <?
    }else{
        ?>
        <table>
        <tr>
        <td><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
            <td><font class="ArialOrange18">Join event</font><br />
                <br />
                <font class="ArialVeryDarkGrey15">Send <?=$organiser_name ?> a message to join this event. Replies will come to your email.</font><br />
                <br /></td>
        </tr>
        <tr>
            <td class="ArialVeryDarkGrey15"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td colspan="4">
                            <div class="ArialOrange18" style="font-size:15px">Please enter a message for the organiser.</div>
                            <div style="height:10px"></div>
                            <textarea name="booking_message" style="width:100%" rows="10" class="textboxbottomborder" id="booking_message" placeholder="Send a request to join this event..."></textarea>
                            <input type="hidden" id="messagestatus" value="" />
                            <br />
                            <br /></td>
                    </tr>
                </table>
        <?
    }
}
// Check name
?>