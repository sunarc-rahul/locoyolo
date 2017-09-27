<?php

if(isset($_POST))
{

    $user_id = $_POST['user_id'];
    $status = "Seen";
    $updateData['status'] = $status;
    $DB->updateRecord('notifications', $updateData, "other_user_id='" . $user_id . "'");

    echo'Y';
    
}else
{
    echo'N';
}
?>