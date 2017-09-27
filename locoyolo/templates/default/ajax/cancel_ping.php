<?php
if($_REQUEST['event_id'])
{

    $errorcheck = false;

    $event_id = $_POST["event_id"];
    $entry_type = "Ping";
    $event_status = "C";
    $updateData = array('event_status'=>$event_status);
    $DB->updateRecord('events', $updateData, "event_id='".$event_id."'");

    $notification_type = "Cancel Ping";
    $status = "Pending";
    $notification_date = date("Y-m-d H:i:s");
    $sql = "Select * from event_bookings where event_id =  $event_id";
    $eventsData = $DB->RunSelectQuery($sql);

    foreach($eventsData as $result){
        $result = (array) $result;
        $frmdata['user_id'] = $result["user_id"];
        $frmdata['event_id'] = $event_id;
        $frmdata['notification_type'] = $notification_type;
        $frmdata['status'] = $status;
        $frmdata['notification_date'] = $notification_date;
    }
    $insertDataIntoEvent = $DB->InsertRecord('notifications', $frmdata);
    if($insertDataIntoEvent){echo 'Y' ;}else{echo 'N';}
}
else
{
    echo 'N';
}

?>