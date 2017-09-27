<?php
include_once (TEMPPATH . "/header.php");


?>
    <div id="event-notifications-page-wrapper">
        <div class="container fixed-footer">
            <div class="col-md-7 goes-on-left" id="id-for-notifications-page">
                <div class="row has-title">
                    <h3>Notifications</h3>
                </div>
                <div class="row has-notifications">

<!-- New Notification is added and show   -->

<!-- New Notification work completed here -->
<!-- @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->

    <!-- ----------------------ALL NOTIFICATIONS ROWS------------------------ -->
<?php
$sql = "Select count(*) as total from notifications where other_user_id=$user_id ";
$res_data = $DB->RunSelectQuery($sql);
$token = $res_data[0]->total;
if ($token == 0)
{
    echo 'no notification to show';
}else{


$status = "Seen";
$sql = "Select * from notifications where other_user_id=$user_id and status='$status' order by id desc";
$res_data = $DB->RunSelectQuery($sql);
/*echo count($res_data);
exit;*/
?>
<?php
$dateset = "";
if (count($res_data) > 0)
{
    foreach ($res_data as $result)
    {
        $result = (array)$result;
        $date = date("Y-m-d", strtotime($result["notification_date"]));
?>

<?php
//echo $result["notification_type"];exit;
// Added here  by Nitin Soni for status

/*
@@@@@@@@@@@@@@@@@@@@@@@@@@@@
@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
SHow Notification for updated event
*/
        //IF NOTIFICATION TYPE IS update event/ping
        if ($result["notification_type"] == "ping_updated"|| $result["notification_type"] == "event_updated")
        {
           /* echo "hello";
          exit;*/
            $sql = "Select * from events where event_id=" . $result["event_id"];
            $res_data = $DB->RunSelectQuery($sql);
            foreach ($res_data as $resulteventcomment)
            {
                $resulteventcomment = (array)$resulteventcomment;
                $event_name = $resulteventcomment["event_name"];
                if ($resulteventcomment["entry_type"] == "")
                {
                    $event_type = "event";
                    $event_link = createURL('index.php', "mod=event&do=eventdetails&eventid=" . $resulteventcomment["event_id"]);

                }
                else if ($resulteventcomment["entry_type"] == "Ping")
                {
                    $event_type = "ping";
                    $event_link = createURL('index.php', "mod=ping&do=pingdetails&eventid=" . $resulteventcomment["event_id"]);
                }


            }



            $sql = "Select * from public_users where id=" . $result["user_id"];
            $res_data = $DB->RunSelectQuery($sql);
?>
                    <div class="is-notification">
                  <div class="who-sent-notification">
                      <?php
                if ($resulteventcomment['event_photo'] == null)
                {
?>
                              <img alt="event-image" class="image-has-radius" src="<?php echo ROOTURL; ?>/images/no_profile_pic.gif" />
                          <?php
                }
                else
                {
?>
                              <img alt="event-image" class="image-has-radius" src="<? echo ROOTURL . '/' . $resulteventcomment["event_photo"]; ?>" width="37" height="37" />
                          <?php
                }
             ?>
                  </div>
                       <!--  <div class="who-sent-notification">
                            <img alt="event-image" src="images/profile_img.jpg" class="image-has-radius">
                        </div> -->
                        <div class="notification-itself">
                            <p class="notification-content">
                           <!--  <strong><a href="<?php
                           // echo createURL('index.php', "mod=user&do=profile&userid=" . $result["id"]);
                            ?>" style="color:#000000"><?php echo $user_name ?></a>
                            </strong> -->
                             <?php   if ($event_type == 'ping')
                                {
                                $var = 'The';
                                }else{
                                $var = 'An';
                                } ?>
                                <?php echo $var; ?> <?php echo $event_type; ?> <strong><a style="color:#000000" href="<?php echo $event_link;?>"><?php
                        echo $event_name;
                        ?></a></strong> has been modified by organiser. Please review the changes.
                            </p>
                            <p id="notification-date"><span class="glyphicon glyphicon-calendar"></span><span class="make-space"></span><span class="has-noti-date-time"><?php echo date("j F, H:i a", strtotime($result["notification_date"])) ?></span></p>
                        </div>
                    </div>

<!--                            --><? //

        }
		/*UPdate event/ping work completed here */

		/* Cancel Event/Ping started  */
        //IF NOTIFICATION TYPE IS cancel event/ping
        if ($result["notification_type"] == "Cancel Ping"|| $result["notification_type"] == "Cancel Event")
        {
            $sql = "Select * from events where event_id=" . $result["event_id"];
            $res_data = $DB->RunSelectQuery($sql);
            foreach ($res_data as $resulteventcomment)
            {
                $resulteventcomment = (array)$resulteventcomment;
                $event_name = $resulteventcomment["event_name"];
                if ($resulteventcomment["entry_type"] == "")
                {
                    $event_type = "event";
                    $event_link = createURL('index.php', "mod=event&do=eventdetails&eventid=" . $resulteventcomment["event_id"]);

                }
                else if ($resulteventcomment["entry_type"] == "Ping")
                {
                    $event_type = "ping";
                    $event_link = createURL('index.php', "mod=ping&do=pingdetails&eventid=" . $resulteventcomment["event_id"]);
                }
            }
            $sql = "Select * from public_users where id=" . $result["user_id"];
            $res_data = $DB->RunSelectQuery($sql);
            foreach ($res_data as $resultusercomment)
            {
                $resultusercomment = (array)$resultusercomment;
                $user_name = $resultusercomment["firstname"] . " " . $resultusercomment["lastname"];
            }
?>
                    <div class="is-notification">
                  <div class="who-sent-notification">
                      <?php
                if ($resulteventcomment['event_photo'] == null)
                {
?>
                              <img alt="event-image" class="image-has-radius" src="<?php echo ROOTURL; ?>/images/no_profile_pic.gif" />
                          <?php
                }
                else
                {
?>
                              <img alt="event-image" class="image-has-radius" src="<? echo ROOTURL . '/' . $resulteventcomment["event_photo"]; ?>" width="37" height="37" />
                          <?php
                }
             ?>
                  </div>
                       <!--  <div class="who-sent-notification">
                            <img alt="event-image" src="images/profile_img.jpg" class="image-has-radius">
                        </div> -->
                        <div class="notification-itself">
                            <p class="notification-content">

                                <?php   if ($event_type == 'ping')
                                {
                                    $var = 'The';
                                }else{
                                    $var = 'An';
                                } ?>
                                <?php echo $var; ?> <?php echo $event_type; ?> <strong><a style="color:#000000" href="<?php echo $event_link;?>"><?php
                        echo $event_name;
                        ?></a></strong> has been cancelled.</p>

                            <p id="notification-date"><span class="glyphicon glyphicon-calendar"></span><span class="make-space"></span><span class="has-noti-date-time"><?php echo date("j F, H:i a", strtotime($result["notification_date"])) ?></span></p>
                        </div>
                    </div>

<!--                            --><? //

        }
/* Completed cancel event/ping  */



/*Reorganize event started*/
	/* Cancel Event/Ping started  */
        //IF NOTIFICATION TYPE IS cancel event/ping
        if ($result["notification_type"] == "Event Reorganize" || $result["notification_type"] == "Ping Reorganize")
        {

        	/*echo "Hello";
        	exit;*/
            $sql = "Select * from events where event_id=" . $result["event_id"];
            $res_data = $DB->RunSelectQuery($sql);
            // 'event_status' => 'L'
            foreach ($res_data as $resulteventcomment)
            {
                $resulteventcomment = (array)$resulteventcomment;
                $event_name = $resulteventcomment["event_name"];
                if ($resulteventcomment["entry_type"] == "")
                {
                    $event_type = "event";
                    $event_link = createURL('index.php', "mod=event&do=eventdetails&eventid=" . $resulteventcomment["event_id"]);

                }
                else if ($resulteventcomment["entry_type"] == "Ping")
                {
                    $event_type = "ping";
                    $event_link = createURL('index.php', "mod=ping&do=pingdetails&eventid=" . $resulteventcomment["event_id"]);
                }
            }
            $sql = "Select * from public_users where id=" . $result["user_id"];
            $res_data = $DB->RunSelectQuery($sql);
            foreach ($res_data as $resultusercomment)
            {
                $resultusercomment = (array)$resultusercomment;
                $user_name = $resultusercomment["firstname"] . " " . $resultusercomment["lastname"];
            }
?>
                    <div class="is-notification">
                  <div class="who-sent-notification">
                      <?php
                if ($resulteventcomment['event_photo'] == null)
                {
?>
                              <img alt="event-image" class="image-has-radius" src="<?php echo ROOTURL; ?>/images/no_profile_pic.gif" />
                          <?php
                }
                else
                {
?>
                              <img alt="event-image" class="image-has-radius" src="<? echo ROOTURL . '/' . $resulteventcomment["event_photo"]; ?>" width="37" height="37" />
                          <?php
                }
             ?>
                  </div>
                       <!--  <div class="who-sent-notification">
                            <img alt="event-image" src="images/profile_img.jpg" class="image-has-radius">
                        </div> -->
                        <div class="notification-itself">
                            <p class="notification-content">
                                <?php   if ($event_type == 'ping')
                                {
                                    $var = 'The';
                                }else{
                                    $var = 'An';
                                } ?>
                                <?php echo $var ?> <?php echo $event_type ?> <strong><a href=<?php echo $event_link ?> style="color:#000000"><?php echo $event_name ?></a></strong>
                                has been reorganized.
                            </p>
                            <p id="notification-date"><span class="glyphicon glyphicon-calendar"></span><span class="make-space"></span><span class="has-noti-date-time"><?php echo date("j F, H:i a", strtotime($result["notification_date"])) ?></span></p>
                        </div>
                    </div>

<!--                            --><? //

        }
/*Completed here*/
/*@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@*/
/*@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@*/
/*Completed event update work*/

        //IF NOTIFICATION TYPE IS A COMMENT
        if ($result["notification_type"] == "C")
        {

        	//echo "hello";
        /*	exit;*/
            $sql = "Select * from events where event_id=" . $result["event_id"];
            $res_data = $DB->RunSelectQuery($sql);
            foreach ($res_data as $resulteventcomment)
            {
                $resulteventcomment = (array)$resulteventcomment;
                $event_name = $resulteventcomment["event_name"];
                if ($resulteventcomment["entry_type"] == "")
                {
                    $event_type = "event";
                    $event_link = createURL('index.php', "mod=event&do=eventdetails&eventid=" . $resulteventcomment["event_id"]);

                }
                else if ($resulteventcomment["entry_type"] == "Ping")
                {
                    $event_type = "ping";
                    $event_link = createURL('index.php', "mod=ping&do=pingdetails&eventid=" . $resulteventcomment["event_id"]);
                }
            }
            $sql = "Select * from public_users where id=" . $result["user_id"];
            $res_data = $DB->RunSelectQuery($sql);
            foreach ($res_data as $resultusercomment)
            {
                $resultusercomment = (array)$resultusercomment;
                $user_name = $resultusercomment["firstname"] . " " . $resultusercomment["lastname"];
            }
?>
                    <div class="is-notification">
                  <div class="who-sent-notification">
                      <?php
            //Query is changed by Nitin Soni
           /* $sql = "SELECT * from public_users where id=$user_id";*/
            $sql = "Select * from public_users where id=" . $result["user_id"];
            $before_result = $DB->RunSelectQuery($sql);
            foreach ($before_result as $result)
            {
                $result = (array)$result;
                if ($result['profile_pic'] == null)
                {
?>
                              <img alt="event-image" class="image-has-radius" src="<?php echo ROOTURL; ?>/images/no_profile_pic.gif" />
                          <?php
                }
                else
                {
?>
                              <img alt="event-image" class="image-has-radius" src="<? echo ROOTURL . '/' . $result["profile_pic"]; ?>" width="37" height="37" />
                          <?php
                }
            } ?>
                  </div>
                       <!--  <div class="who-sent-notification">
                            <img alt="event-image" src="images/profile_img.jpg" class="image-has-radius">
                        </div> -->
                        <div class="notification-itself">
                            <p class="notification-content"><strong><a href="<?php
            echo createURL('index.php', "mod=user&do=profile&userid=" . $result["$user_id"]);
?>" style="color:#000000"><?php echo $user_name ?></a></strong>has commented on your<?php echo $event_type ?> <strong><a href=<?php echo $event_link ?> style="color:#000000"><?php echo $event_name ?></a>&nbsp;</strong></p>
                            <p id="notification-date"><span class="has-noti-icon"><img alt="notifocation-image" src="images/comment_box.gif"></span><span class="make-space"></span><span class="has-noti-date-time"><?php echo date("j F, H:i a", strtotime($result["notification_date"])) ?></span></p>
                        </div>
                    </div>

<!--                            --><? //

        }

//Completed work for show cancel Event notification

        //IF NOTIFICATION TYPE IS A COMMENT
        if ($result["notification_type"] == "Comment")
        {
            $sql = "Select * from events where event_id=" . $result["event_id"];
            $res_data = $DB->RunSelectQuery($sql);
            foreach ($res_data as $resulteventcomment)
            {
                $resulteventcomment = (array)$resulteventcomment;
                $event_name = $resulteventcomment["event_name"];
                if ($resulteventcomment["entry_type"] == "")
                {
                    $event_type = "event";
                    $event_link = createURL('index.php', "mod=event&do=eventdetails&eventid=" . $resulteventcomment["event_id"]);

                }
                else if ($resulteventcomment["entry_type"] == "Ping")
                {
                    $event_type = "ping";
                    $event_link = createURL('index.php', "mod=ping&do=pingdetails&eventid=" . $resulteventcomment["event_id"]);
                }
            }
            $sql = "Select * from public_users where id=" . $result["user_id"];
            $res_data = $DB->RunSelectQuery($sql);
            foreach ($res_data as $resultusercomment)
            {
                $resultusercomment = (array)$resultusercomment;
                $user_name = $resultusercomment["firstname"] . " " . $resultusercomment["lastname"];
            }
?>
                    <div class="is-notification">
                  <div class="who-sent-notification">
                      <?php
            //Query is changed by Nitin Soni
           /* $sql = "SELECT * from public_users where id=$user_id";*/
            $sql = "Select * from public_users where id=" . $result["user_id"];
            $before_result = $DB->RunSelectQuery($sql);
            foreach ($before_result as $result)
            {
                $result = (array)$result;
                if ($result['profile_pic'] == null)
                {
?>
                              <img alt="event-image" class="image-has-radius" src="<?php echo ROOTURL; ?>/images/no_profile_pic.gif" />
                          <?php
                }
                else
                {
?>
                              <img alt="event-image" class="image-has-radius" src="<? echo ROOTURL . '/' . $result["profile_pic"]; ?>" width="37" height="37" />
                          <?php
                }
            } ?>
                  </div>
                       <!--  <div class="who-sent-notification">
                            <img alt="event-image" src="images/profile_img.jpg" class="image-has-radius">
                        </div> -->
                        <div class="notification-itself">
                            <p class="notification-content"><strong><a href="<?php
            echo createURL('index.php', "mod=user&do=profile&userid=" . $result["id"]);
?>" style="color:#000000"><?php echo $user_name ?></a></strong> has commented on your <?php echo $event_type ?> <strong><a href=<?php echo $event_link ?> style="color:#000000"><?php echo $event_name ?></a>&nbsp;</strong></p>
                            <p id="notification-date"><span class="has-noti-icon"><img alt="notifocation-image" src="images/comment_box.gif"></span><span class="make-space"></span><span class="has-noti-date-time"><?php echo date("j F, H:i a", strtotime($result["notification_date"])) ?></span></p>
                        </div>
                    </div>

<!--                            --><? //

        }
        //IF NOTIFICATION TYPE IS AN ADD BUDDY
if ($result["notification_type"] == "Add_Buddy")
{

$sql = "Select * from public_users where id=" . $result["user_id"];
$res_data = $DB->RunSelectQuery($sql);
foreach ($res_data as $data)
{
    $resultuser = (array)$data;

    $user_name = $resultuser["firstname"] . " " . $resultuser["lastname"];
}
?>
                    <!--  Add new commented here  -->
                    <div  class="is-notification">
                        <!-- Added by Nitin Soni for show user image 18Aug/2017 -->
                        <div class="who-sent-notification">
                            <?php
                            if ($resultuser['profile_pic'] == null)
                            {
                                ?>
                                <img alt="event-image" class="image-has-radius" src="<?php echo ROOTURL; ?>/images/no_profile_pic.gif" />
                                <?php
                            }
                            else
                            {
                                ?>
                                <img alt="event-image" class="image-has-radius" src="<? echo ROOTURL . '/' . $resultuser["profile_pic"]; ?>" width="37" height="37" />
                                <?php
                            } ?>
                        </div>
                        <!-- Finsihed by Nitin Soni for user image -->
                        <div class="notification-itself">
                            <p class="notification-content"><?php echo $user_name; ?> wants to add you as a buddy <?php echo $event_type; ?> <strong><a style="color:#000000" href="<?php echo $event_link;?>"><?php
                                        echo $event_name;
                                        ?></a></strong>
                                <?php $sql = "SELECT * from buddies where user_id=$user_id and buddy_id=" . $resultuser["id"];
                                $res_data = $DB->RunSelectQuery($sql);
                                if ($res_data[0]->status =='Confirmed buddy')
                                {

                                ?>
                                <img src="images/green_tick.gif" style="vertical-align:middle" width="15" />&nbsp;<span class="ArialVeryDarkGreyBold15">Accepted</span>
                            </p>
                            <p id="notification-date"><span class="glyphicon glyphicon-user"></span><span class="make-space"></span><span class="has-noti-date-time"><?php echo date("j F, H:i a", strtotime($result["notification_date"])) ?></span></p>
                        </div>
                    </div>
                <?php
                }
                else
                {
                    ?>
                    <div class="accept-req" id="confirm_add_buddy_status<?php
                    echo $resultuser["id"];
                    ?>" style="display:inline-block"><div  onClick="confirm_add_buddy(<?php
                        echo $resultuser["id"];
                        ?>)" class="slimbuttonblue" style="width:130px;">Accept request</div></div>
                    <p id="notification-date"><span class="glyphicon glyphicon-user"></span><span class="make-space"></span><span class="has-noti-date-time"><?php echo date("j F, H:i a", strtotime($result["notification_date"])) ?></span> </p>
                    <?php
                }?>

                </div>
            </div>
            <!-- Add new task completed  -->
            <?php
            }
        //IF NOTIFICATION TYPE IS A CONFIRMED BUDDY
        if ($result["notification_type"] == "Confirmed Buddy")
        {
            $sql = "Select * from public_users where id=" . $result["user_id"];
            $res_data = $DB->RunSelectQuery($sql);
            foreach ($res_data as $resultuser)
            {
                $resultuser = (array)$resultuser;
                $user_name = $resultuser["firstname"] . " " . $resultuser["lastname"];
            }
?>

<div class="is-notification">
<div class="who-sent-notification">
                                                    <img width="37" height="37" src="<? echo ROOTURL . '/' . $resultuser["profile_pic"]; ?>" class="image-has-radius" alt="event-image">
 </div>
<div class="notification-itself">
                            <p class="notification-content"><strong><a href="<?php
            echo createURL('index.php', "mod=user&do=profile&userid=" . $resultuser["id"]);
?>" style="color:#000000"><?php
            echo $user_name;
?></a></strong>&nbsp; has accepted your buddy request.</p>
                            <p id="notification-date"><span class="glyphicon glyphicon-user"></span><span class="make-space"></span><span class="has-noti-date-time"><?php echo date("j F, H:i a", strtotime($result["notification_date"])) ?></span></p>
 </div>

</div>
                        <?php
        }
        if ($result["notification_type"] == "Booking Request")
        {
            $sql = "Select * from events where event_id=" . $result["event_id"];
            $res_data = $DB->RunSelectQuery($sql);
            foreach ($res_data as $resulteventcomment)
            {
                $resulteventcomment = (array)$resulteventcomment;
                $event_name = $resulteventcomment["event_name"];
                if ($resulteventcomment["entry_type"] == "")
                {
                    $event_type = "event";
                    $event_link = createURL('index.php', "mod=event&do=eventdetails&eventid=" . $resulteventcomment["event_id"]);
                }
                else if ($resulteventcomment["entry_type"] == "Ping")
                {
                    $event_type = "ping";
                    $event_link = createURL('index.php', "mod=ping&do=pingdetails&eventid=" . $resulteventcomment["event_id"]);
                }
            }


            $sql = "Select * from public_users where id=" . $result["user_id"];
            $res_data = $DB->RunSelectQuery($sql);

            foreach ($res_data as $resultusercomment)
            {
                $resultusercomment = (array)$resultusercomment;

                $user_name = $resultusercomment["firstname"] . " " . $resultusercomment["lastname"];
            } ?>
            <!--  Add new commented here  -->
            <div  class="is-notification">
                <!-- Added by Nitin Soni for show user image 18Aug/2017 -->
                <div class="who-sent-notification">
                    <?php
                    if ($resultusercomment['profile_pic'] == null)
                    {
                        ?>
                        <img alt="event-image" class="image-has-radius" src="<?php echo ROOTURL; ?>/images/no_profile_pic.gif" />
                        <?php
                    }
                    else
                    {

                        ?>
                        <img alt="event-image" class="image-has-radius" src="<? echo ROOTURL . '/' . $resultusercomment['profile_pic']; ?>" width="37" height="37" />
                        <?php
                    }
                    ?>
                </div>
                <!-- Finsihed by Nitin Soni for user image -->
                <div class="notification-itself">
                    <p class="notification-content"><?php echo $user_name; ?> sent you a booking request for <?php echo $event_type; ?> <strong><a style="color:#000000" href="<?php echo $event_link;?>"><?php
                                echo $event_name;
                                ?></a></strong> </p>
                    <p id="notification-date"><span class="glyphicon glyphicon-calendar"></span><span class="make-space"></span><span class="has-noti-date-time">                            <?php
                            echo date("j F, H:i a", strtotime($result["notification_date"]));
                            ?></span></p>
                </div>
            </div>
            <!-- Add new task completed  -->

            <?php
        }
        if ($result["notification_type"] == "Cancel Booking")
        {
            $sql = "Select * from events where event_id=" . $result["event_id"];
            $res_data = $DB->RunSelectQuery($sql);
            foreach ($res_data as $resulteventcomment)
            {
                $resulteventcomment = (array)$resulteventcomment;
                $event_name = $resulteventcomment["event_name"];
                if ($resulteventcomment["entry_type"] == "")
                {
                    $event_type = "event";
                    $event_link = createURL('index.php', "mod=event&do=eventdetails&eventid=" . $resulteventcomment["event_id"]);
                }
                else if ($resulteventcomment["entry_type"] == "Ping")
                {
                    $event_type = "ping";
                    $event_link = createURL('index.php', "mod=ping&do=pingdetails&eventid=" . $resulteventcomment["event_id"]);
                }
            }


            $sql = "Select * from public_users where id=" . $result["user_id"];
            $res_data = $DB->RunSelectQuery($sql);

            foreach ($res_data as $resultusercomment)
            {
                $resultusercomment = (array)$resultusercomment;

                $user_name = $resultusercomment["firstname"] . " " . $resultusercomment["lastname"];
            } ?>
            <!--  Add new commented here  -->
            <div  class="is-notification">
                <!-- Added by Nitin Soni for show user image 18Aug/2017 -->
                <div class="who-sent-notification">
                    <?php
                    if ($resultusercomment['profile_pic'] == null)
                    {
                        ?>
                        <img alt="event-image" class="image-has-radius" src="<?php echo ROOTURL; ?>/images/no_profile_pic.gif" />
                        <?php
                    }
                    else
                    {

                        ?>
                        <img alt="event-image" class="image-has-radius" src="<? echo ROOTURL . '/' . $resultusercomment['profile_pic']; ?>" width="37" height="37" />
                        <?php
                    }
                    ?>
                </div>
                <!-- Finsihed by Nitin Soni for user image -->
                <div class="notification-itself">
                    <p class="notification-content"><?php echo $user_name; ?> Left <?php echo $event_type; ?> <strong><a style="color:#000000" href="<?php echo $event_link;?>"><?php
                                echo $event_name;
                                ?></a></strong> </p>
                    <p id="notification-date"><span class="glyphicon glyphicon-calendar"></span><span class="make-space"></span><span class="has-noti-date-time">                            <?php
                            echo date("j F, H:i a", strtotime($result["notification_date"]));
                            ?></span></p>
                </div>
            </div>
            <!-- Add new task completed  -->

            <?php
        }
?>
        <?php
    }
}
else
{
?>
       
            <div style="height:20px"></div><span class="ArialVeryDarkGrey15">There are no new notifications...</span>
     <?php
}}
$status = "Seen";
$updateData['status'] = $status;
$DB->updateRecord('notifications', $updateData, "other_user_id='" . $user_id . "'");
?>
<!-- New notification finished here -->
                </div>
            </div>

    </div>
    </div>


<script>
    function cancelping(id){

        //POST BY AJAX TO DISPLAY EVENTS IN MAP LIST
        $.ajax({
            type: "POST",
            url: "cancel_ping.php",
            data: { event_id:id },
            //dataType: 'json',
            cache: false,
            success: function(data)
            {
                if(data == 'OK') {
                    result = data;
                } else {
                    result = data;
                }
                $('#pingstatus'+id).html(result);
            }
        });
    }
    function confirm_add_buddy(id){

        var current_user_id = "<?php
echo $user_id;
?>";
        //POST BY AJAX TO DISPLAY EVENTS IN MAP LIST
        $.ajax({
            type: "POST",
            url: "<?php
echo CreateURL('index.php', "mod=ajax&do=confirm_buddy");
?>",

            data: { buddy_id:id, current_user_id:current_user_id },
            //dataType: 'json',
            cache: false,
            Asynchronous:false,
            success: function(data)
            {
                if(data == 'OK') {
                    alert(data);
                    result = data;
                    $('<div><img src="images/green_tick.gif" style="vertical-align:middle" width="15" />&nbsp;<h4 class="ArialVeryDarkGreyBold15">Accepted</h4></div>');
                } else {
                    result = data;
                }
//                $('#confirm_add_buddy_status'+id).html(result);
            }
        });
    }
</script>
        </html></div>