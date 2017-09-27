<?php
echo $_REQUEST;
if($_REQUEST['event_id'])
{
    $errorcheck = false;

    $event_id = $_POST["event_id"];
    $entry_type = "Ping";
    $event_status = "C";
    $updateData = array('event_status'=>$event_status);
    $DB->updateRecord('events', $updateData, "event_id='".$event_id."'");

    $notification_type = "Cancel Event";
    $status = "Pending";
    $notification_date = date("Y-m-d H:i:s");
    $sqlEvent = "Select user_id from events where event_id = $event_id ";
    $eventData = $DB->RunSelectQuery($sqlEvent);
    foreach($eventData as $UserId){
        $eventUserId = (array) $UserId;}

    $sql = "Select * from event_bookings where event_id = $event_id GROUP BY user_id";
    $eventsBookingData = $DB->RunSelectQuery($sql);
    foreach($eventsBookingData as $result){
        $result = (array) $result;

        $frmdata['user_id'] = $eventUserId["user_id"];
        $frmdata['other_user_id'] = $result["user_id"];
        $frmdata['event_id'] = $event_id;
        $frmdata['notification_type'] = $notification_type;
        $frmdata['status'] = $status;
        $frmdata['notification_date'] = $notification_date;
        $insertDataIntoEvent = $DB->InsertRecord('notifications', $frmdata);
    }
echo 'Y';

}
else
{
    echo 'N';
}

// Check name
?>