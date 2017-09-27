<?php

if(isset($_REQUEST['buddyid'])) {
    $data['user_id'] = $_POST['buddyid'];
    $data['other_user_id'] = $_POST['senderId'];
    $data['message'] = $message;
    $data['event_id'] = $_POST['eventId'];
    $data['notification_type'] = $notification_type;
    $data['status'] = $status;
    $data['notification_date'] = $notification_date;
    $id = $DB->InsertRecord('notifications', $data);
    if ($id) {
        echo 'Y';
    } else {
        echo 'N';
    }
}else
{
    echo 'N';
}
?>