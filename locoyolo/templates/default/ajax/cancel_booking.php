<?php

if($_POST)
{

    $errorcheck = false;
    $status = "Pending";
    $notification_type = "Cancel Booking";
    $event_id = $_POST["event_id"];
    $user_id = $_POST["user_id"];

    $DB->DeleteRecord('event_bookings','event_id="'.$event_id.'" and user_id='.$user_id);

    $query = $DB->SelectRecords('events',"event_id='" . $event_id . "'",'user_id');
    $otherUserId = $query[0]->user_id;
    $frmdata['user_id'] = $user_id;
    $frmdata['other_user_id'] = $otherUserId;
    $frmdata['event_id'] = $event_id;
    $frmdata['notification_type'] = $notification_type;
    $frmdata['status'] = $status;
    $frmdata['notification_date'] = $notification_date;
    $insertDataIntoEvent = $DB->InsertRecord('notifications', $frmdata);
   echo 'Y';
}
else
{
    echo 'N';
}

// Check name
?>