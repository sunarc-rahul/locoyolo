<?php



$post = (!empty($_POST)) ? true : false;

if($post)
{

    $errorcheck = false;

    $comment = $_POST['comment_message'];
    $user_id = $_POST["user_id"];
    $event_id = $_POST["event_id"];
    $entry_date = date("Y-m-d H:i:s");
    $status = "Pending";

    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_username, $db_password);
    $sql = "Insert into comments (user_id, event_id, comment, entry_date) values (:user_id, :event_id, :comment, :entry_date)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':event_id', $event_id, PDO::PARAM_INT);
    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
    $stmt->bindParam(':entry_date', $entry_date, PDO::PARAM_STR);
    $stmt->execute();

    $notification_type = "Comment";
    $notification_id_array = array();

    $sql = "Select * from event_bookings where event_id=:event_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':event_id', $event_id, PDO::PARAM_INT);
    $stmt->execute();
    while($result = $stmt->fetch( PDO::FETCH_ASSOC )){
        if ($result["user_id"] !== $user_id){
            array_push($notification_id_array, $result["user_id"]);
        }
    }

    $sql = "Select * from comments where event_id=:event_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':event_id', $event_id, PDO::PARAM_INT);
    $stmt->execute();
    while($result = $stmt->fetch( PDO::FETCH_ASSOC )){
        if (!in_array($result["user_id"], $notification_id_array)){
            if ($result["user_id"] !== $user_id){
                array_push($notification_id_array, $result["user_id"]);
            }
        }
    }

    $notification_date = date("Y-m-d H:i:s");
    foreach($notification_id_array as $notify_id){
        $sql = "Insert into notifications (user_id, other_user_id, event_id, notification_type, status, notification_date) values (:user_id, :other_user_id, :event_id, :notification_type, :status, :notification_date)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $notify_id, PDO::PARAM_INT);
        $stmt->bindParam(':other_user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':event_id', $event_id, PDO::PARAM_INT);
        $stmt->bindParam(':notification_type', $notification_type, PDO::PARAM_STR);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':notification_date', $notification_date, PDO::PARAM_STR);
        $stmt->execute();
    }

    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_username, $db_password);
    $sql = "Select * from comments where event_id=:event_id order by id desc limit 0,1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':event_id', $event_id, PDO::PARAM_INT);
    $stmt->execute();
    while($result = $stmt->fetch( PDO::FETCH_ASSOC )){
        ?>
        <div class='commentli'>
            <div style="display:inline-block; vertical-align:top; width:35px">
                <?
                $sql2 = "SELECT * from public_users where id=:userid";
                $stmt2 = $pdo->prepare($sql2);
                $stmt2->bindParam(':userid', $result["user_id"], PDO::PARAM_INT);
                $stmt2->execute();
                while($resultuser = $stmt2->fetch( PDO::FETCH_ASSOC )){
                    $profilepic = $resultuser['profile_pic'];
                    $user_name = $resultuser['firstname']." ".$resultuser['lastname'];
                    if ($profilepic == "") {
                        ?>
                        <img width="30" height="30" valign="middle" style="border-radius:100px" src="images/no_profile_pic.gif" />
                    <? }else{ ?>
                        <img width="30" height="30" valign="middle" style="border-radius:100px" src="<? echo "http://www.locoyolo.com/".$profilepic; ?>" />
                    <? }
                }?>
            </div>
            <div style="display:inline-block; width:10px; vertical-align:top;"><div style="height:5px"></div><img src="images/speech_triangle.gif" width="10" /></div>
            <div style="display:inline-block;">
                <div style="height:5px"></div>
                <div style="border-radius:0px 3px 3px 3px; padding:5px" id="comment_content<?=$result["id"] ?>">
                    <font class="ArialVeryDarkGreyBold15"><? echo $user_name; ?></font>
                    <font class="ArialVeryDarkGrey15"> <? echo $result["comment"] ?></font>
                    <br />
                    <div style="display:inline-block;">
                        <font class="ArialVeryDarkGrey15" style="color:#999; font-size:13px"> <?=date("j M", strtotime($result["entry_date"])) ?>, <?=date("h:i a", strtotime($result["entry_date"])) ?></font>
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

<? } ?>