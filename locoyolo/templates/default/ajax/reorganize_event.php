<?php

include_once("../../config.php");
include_once(INC."/commonfunction.php");
include_once(INC.'/dbfilter.php');
include_once(INC.'/dbqueries.php');
include_once(INC.'/dbhelper.php');

if(isset($_POST['event_id']))
{

    $errorcheck = false;
    $event_id = $_POST["event_id"];
    $user_id = $_POST["user_id"];

    $updateData = [
        'event_status' => 'L'
    ];
    $query_update = $DB->UpdateRecord('events',$updateData,'event_id='.$event_id);
    if($query_update){ echo 'Y' ;}else{ echo 'N';}


        $sql = "SELECT * from events where event_id=$event_id";
         $query = $DB->RunSelectQuery($sql);
        foreach ($query as $resultCheck) {
            $result_check_cancel = (array)$resultCheck;

         $event_organiser_id= $result_check_cancel["user_id"];
        if ($result_check_cancel["event_status"] !== "L ") {
            $dataToUpdate = [
                'event_status' => "L"
            ];
            $sql = "Select * from event_bookings where event_id = $event_id GROUP BY user_id" ;
            $query = $DB->RunSelectQuery($sql);
            foreach ($query as $resultData) {
                $resultuser = (array)$resultData;


                $insertData["other_user_id"] = $resultuser["user_id"];
                $insertData["user_id"] = $event_organiser_id;
                $insertData["event_id"] = $event_id;
                $insertData["notification_type"] = "Event Reorganize";
                $insertData["status"] = "Pending";
                $insertData["notification_date"] = date("Y-m-d H:i:s");
                $sql = $DB->InsertRecord('notifications', $insertData);
            }

            }
        }

}
else
{
    echo 'NO';
}

?>