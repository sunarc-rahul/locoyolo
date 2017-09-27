<?php

if($_POST)
{

    $errorcheck = false;

     $userid = $_POST['userid'];
     $buddyid = $_POST['buddyid'];

    $status = "Pending";
    $notification_type = "Add_Buddy";
    $message = "Buddy request sent";
    $notification_date = date("Y-m-d H:i:s");

   $data['user_id']= $userid;
    $data['buddy_id']= $buddyid;
    $data['status']=$status;

    $id = $DB->InsertRecord('buddies', $data);

//    $buddydata['buddy_id']= $buddyid;
//    $buddydata['user_id']= $userid;
//    $buddydata['status']=$status;
//    $id = $DB->InsertRecord('buddies', $buddydata);

    $notificationsdata['user_id'] = $userid;
    $notificationsdata['other_user_id'] = $buddyid;
    $notificationsdata['notification_type'] = $notification_type;
    $notificationsdata['status'] = $status;
    $notificationsdata['notification_date'] = $notification_date;

    $id = $DB->InsertRecord('notifications', $notificationsdata);

    echo 'Y';
}
else
{
    echo 'N';
}

// Check name
?>