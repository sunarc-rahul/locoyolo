<?php

include_once("../../config.php");
include_once(INC."/commonfunction.php");
include_once(INC.'/dbfilter.php');
include_once(INC.'/dbqueries.php');
include_once(INC.'/dbhelper.php');


error_reporting (E_ALL ^ E_NOTICE);

$post = (!empty($_POST)) ? true : false;

if($post)
{

    $errorcheck = false;

    echo $current_user_id = $_POST['current_user_id'];
   echo  $buddy_id = $_POST['buddy_id'];

    $status = "Confirmed buddy";
    $message = "Confirmed buddy";
    $notification_date = date("Y-m-d H:i:s");
    $notification_type = "Confirmed Buddy";



//    $updateData = array('event_status'=>$event_status);
//    $DB->updateRecord('events', $updateData, "event_id='".$event_id."'");
    $insertToBuddy['status']= $status;
    $insertToBuddy['user_id']= $current_user_id;
    $insertToBuddy['buddy_id']= $buddy_id;
    $insertQuery=$DB->InsertRecord('buddies',$insertToBuddy);

    $updateData = array('status'=>$status,);
    $query = $DB->UpdateRecord('buddies',$updateData,"buddy_id='".$buddy_id."'"AND "user_id='".$current_user_id."'");
//    $updateData = array('status'=>$status,
//
//       );
//    $query = $DB->UpdateRecord('buddies',$updateData,"buddy_id='".$current_user_id."'"AND "user_id='".$buddy_id."'");

    $dataToInsert['status']="Pending";
    $dataToInsert['user_id']= $current_user_id;
    $dataToInsert['other_user_id']= $buddy_id;
    $dataToInsert['notification_type']=$notification_type;
    $dataToInsert['notification_date']=$notification_date;

        $status = "Pending";
        $insertQuery=$DB->InsertRecord('notifications',$dataToInsert);

    ?>
    <img src="images/green_tick.gif" style="vertical-align:middle" width="15" />&nbsp;<h4 class="ArialVeryDarkGreyBold15">Accepted</h4>
    <?

    echo'OK';
}

// Check name
?>