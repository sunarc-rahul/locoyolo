<?php

include_once("../../config.php");
include_once(INC."/commonfunction.php");
include_once(INC.'/dbfilter.php');
include_once(INC.'/dbqueries.php');
include_once(INC.'/dbhelper.php');


//error_reporting (E_ALL ^ E_NOTICE);

$post = (!empty($_POST)) ? true : false;
//echo $event_id;exit;
if($post)
{

    $errorcheck = false;
    $event_id = $_POST["event_id"];
    $user_id = $_POST["user_id"];
    $comment =$_POST['comment_message'];

    $insertData['comment'] = $comment;
    $insertData['user_id'] = $user_id;
    $insertData['event_id'] = $event_id;
    $insertData['entry_date'] = date("Y-m-d H:i:s");
    $status = "Pending";
//    echo $_POST['event_id'];exit;
    $sql = $DB->InsertRecord('comments',$insertData);



    $notification_type = "Comment";
    $notification_id_array = array();

    $sql = "Select * from events where event_id= $event_id";
    $query = $DB->RunSelectQuery($sql);
    foreach ($query as $data){
        $result = (array)$data;
        if ($result["user_id"] !== $user_id){
            array_push($notification_id_array, $result["user_id"]);
        }
    }

    $sql = "Select * from comments where event_id= $event_id";
    $query = $DB->RunSelectQuery($sql);
    foreach ($query as $data){
        $result = (array)$data;
        if (!in_array($result["user_id"], $notification_id_array)){
            if ($result["user_id"] !== $user_id){
                array_push($notification_id_array, $result["user_id"]);
            }
        }
    }

    $notification_date = date("Y-m-d H:i:s");
    foreach($notification_id_array as $notify_id) {

        $dataToInsert['user_id'] = $user_id;
        $dataToInsert['other_user_id'] = $notify_id;
        $dataToInsert['event_id'] = $event_id;
        $dataToInsert['notification_type'] = $notification_type;
        $dataToInsert['status'] = $status;
        $dataToInsert['notification_date'] = $status;
        $sql = $DB->InsertRecord('notifications', $dataToInsert);
    }

    $sql = "Select * from comments where event_id=$event_id order by id desc limit 0,1";
    $query =$DB->RunSelectQuery($sql);
        foreach ($query as $data){
            $result = (array)$data;
        }
//print_r($result["id"]);exit;
//        $userId =$result["user_id"];?>
        <div class='comment-person'>
            <div style="display:inline-block; vertical-align:top; width:35px">
                <?
                $sql2 = "SELECT * from public_users where id=$user_id";
                $query =$DB->RunSelectQuery($sql2);
                foreach ($query as $data){
                    $resultuser = (array)$data;
                    $profilepic = $resultuser['profile_pic'];
                    $user_name = $resultuser['firstname']." ".$resultuser['lastname'];
                    if ($profilepic == "") {
                        ?>
                        <img width="30" height="30" valign="middle" style="border-radius:100px" src="images/no_profile_pic.gif" />
                    <? }else{ ?>
                        <img width="30" height="30" valign="middle" style="border-radius:100px" src="<? echo ROOTURL . '/'.$profilepic; ?>" />
                    <? }
                }?>
            </div>
            <div style="display:inline-block; width:10px; vertical-align:top;"><div style="height:5px"></div><img src="images/speech_triangle.gif" width="10" /></div>
            <div style="display:inline-block;">
                <!-- <div style="height:5px"></div> -->
                <div style="border-radius:0px 3px 3px 3px; padding:5px" id="comment_content<?=$result["id"] ?>">
                    <font class="ArialVeryDarkGreyBold15"><? echo $user_name; ?></font>
                    <font class="ArialVeryDarkGrey15"> <? echo $result["comment"] ?></font>
                   <!--  <br /> -->
                    <div style="display:inline-block;">
                        <font class="ArialVeryDarkGrey15" style="color:#999; font-size:13px"> <?=date("j M Y", strtotime($result["entry_date"])) ?>, <?=date("h:i a", strtotime($result["entry_date"])) ?></font>
                    </div>
                    &nbsp;&nbsp;
                    <div style="display:inline-block; cursor:pointer" onclick="delete_comment(<?=$result["id"] ?>, '<?=$user_name ?>')"><font class="ArialVeryDarkGrey15" style="color:#F63; font-size:13px">Delete</font>
                    </div>
                </div>
            </div>
        </div>
        <?
    }
    ?>
    </div>

