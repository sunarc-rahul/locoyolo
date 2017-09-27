<?php
include_once(TEMPPATH . "/header.php");
?>
<script>
    function cancelping(id){

        //POST BY AJAX TO DISPLAY EVENTS IN MAP LIST
        $.ajax({
            type: "POST",
            url: "<?php
                echo createURL('index.php', 'mod=ajax&do=cancel_event');
                ?>",
            data: { event_id:id },
            //dataType: 'json',
            cache: false,
            success: function(data)
            {
                if(data == 'Y') {
                    result = data;
                    $('#pingstatus'+id).html('<font class="ArialVeryDarkGreyBold15">Cancelled</font>');
                } else {
                    alert("There is some problem while cancelling ping.");
                }

            }
        });
    }
</script>
<?php
if ($_SESSION['success']) {
?>
<div style="height:95px">

        <div class="alert alert-success col-sm-8 ">
            <strong>Success!</strong> <?php
            echo $_SESSION['success'];
            ?>
        </div>
</div>
        <?php
        $_SESSION['success'] = '';
    }else{
        ?>

<div style="height:30px"></div>
    <?php
}
    ?>

<?php
function limit_text($text, $limit)
{
    if (str_word_count($text, 0) > $limit) {
        $words = str_word_count($text, 2);
        $pos   = array_keys($words);
        $text  = substr($text, 0, $pos[$limit]) . '...';
    }
    return $text;
}
$userid        = $_GET["userid"];
$status        = "Confirmed";
$sql           = "Select * from buddies where user_id=$userid and status='$status'";
$buddiesData   = $DB->RunSelectQuery($sql);
$buddies_count = count($buddiesData);

$today                = date("Y-m-d");
$sql                  = "Select * from event_bookings where user_id=$userid and booking_status='$status' and start_date>'$today'";
$eventData            = $DB->RunSelectQuery($sql);
$event_attended_count = count($eventData);

$event_status          = "L";
$sql                   = "Select * from events where event_status='$event_status' and user_id=$userid and entry_type=''";
$eventData             = $DB->RunSelectQuery($sql);
$event_organised_count = count($eventData);
$current_user_id       = $user_id;
$sql                   = "Select * from public_users where id=$current_user_id";
$userData              = $DB->RunSelectQuery($sql);
foreach ($userData as $resultuser) {
$resultuser = (array) $resultuser;
$firstname  = $resultuser["firstname"];
if ($resultuser["profile_pic"] == "") {
    $profile_pic = ROOTURL . "/images/no_profile_pic.gif";
} else {
    $profile_pic = ROOTURL . "/" . $resultuser["profile_pic"];
}
?>

<div class="container events-page fixed-footer">
    <div class="jumbotron ">
        <div class="row">
        <div class="col-sm-5">
            <div class="row">
                <div class="col-sm-4">
                    <img width="120" height="120" style="border-radius:60px" src="<?php  echo $profile_pic;  ?>" />
                </div>
                <div class="col-sm-8 ">

                    <div class="col-sm-3 profile-content">
                    <h4><?php
                        echo $buddies_count;
                        ?></h4>
                        <h class=""><?php if( $buddies_count>1) echo  'Buddies'; echo 'Buddy'; ?></h>
                    </div>
                    <div class="col-sm-3 profile-content">
                    <h4><?php
                        echo $event_attended_count;
                        ?></h4>
                        <h class=""><?php if( $event_attended_count>1) echo  'Events'; echo 'Event'; ?> attended
                        </h>
                    </div>

                    <div class="col-sm-3 profile-content">
                     <h4><?php
                         echo $event_organised_count;
                         ?></h4>
                        <h class=""> <?php if( $event_organised_count>1) {echo  'Events';}else {echo 'Event';} ?> organised</h>
                    </div></div>
            </div>

            <?php
            if (isset($user_id)) {
                if ($current_user_id == $resultuser["id"]) {
                    ?>
                    <button class="btn btn-primary edit-button" onClick="location.href='<?php
                    echo CreateURL('index.php', "mod=user&do=editprofile");
                    ?>'"  class="slimbuttonblue">Edit profile</button>
                    <?php
                }
            }
            ?>

        </div>
        <div class="col-sm-7">
            <div class="row">
                <p class="ArialVeryDarkGrey25"><?php
                    echo $resultuser["firstname"] . " " . $resultuser["lastname"];
                    ?></p>
                <span><?php
                    echo $resultuser["mood_statement"];
                    ?></span>

            </div>
        </div>
    </div>
    </div>

    <?php
    }
    ?>
    <hr>
	<h2>Events organised</h2>
    <div class="row events">

        

        <?php
        $event_attended = "Y";
        $sql            = "Select * from events where user_id=$userid and entry_type=''";
        $eventData      = $DB->RunSelectQuery($sql);
        $i              = 1;
        if (count($userData) > 0) {
            foreach ($eventData as $result_events_organised) {
                $result_events_organised = (array) $result_events_organised;
                if(file_get_contents(ROOTURL .'/'.$result_events_organised["event_photo"])!='' && $result_events_organised["event_photo"]!='')
                {
                    $img = $result_events_organised["event_photo"];
                }
                else
                {
                        $img ='images/dummy-bg.jpg';
                }
                ?>
                <div class="is-in-all-event col-sm-3">
                   <div style="overflow:hidden"> <img width="240" src="<?php
                      echo ROOTURL . '/' .$img;
                     ?>" />

                <a class="is-in-all-event-name"
                    href="<?php echo createURL('index.php', "mod=event&do=eventdetails&eventid=" . $result_events_organised["event_id"]);
                            ?>"> </div>
                    <h4 class="ArialVeryDarkGreyBold18" style="text-overflow: ellipsis">
                        <?php

                        echo $result_events_organised["event_name"];
                        ?>
                    </h4>
                </a>
                    <?php
                    $str = $result_events_organised["start_date"];
                    $strEnd = $result_events_organised["end_date"];

                    if( $str=='0000-00-00 00:00:00' && $strEnd =='0000-00-00 00:00:00'){

                        echo '<p> Time not available</p>';

                    }else{
                        if($str=='0000-00-00 00:00:00') {

                            $startDate =' Start time not available.';
                        }else{
                            $startDate = date(" d M Y - h:i A", strtotime($str));
                        }
                        if( $strEnd =='0000-00-00 00:00:00') {

                            $EndDate ='End time not available.';
                        }else{
                            $EndDate = date(" d M Y - h:i A", strtotime($strEnd));
                        }
                        ?>
                        <h5 class="ArialVeryDarkGrey15">
                            <span><?php echo  $startDate ?></span> | <span><?php echo  $EndDate ?></span>
                        </h5>
                    <?php  }
                    ?>

                <p class="ArialVeryDarkGrey15">
                    <?php
                    $booking_status   = "Confirmed";
                    $sql = "SELECT * from event_bookings where event_id=" . $result_events_organised["event_id"] . " and booking_status='$booking_status'GROUP BY user_id";
                    $eventbookingData = $DB->RunSelectQuery($sql);
                    if (!is_array($eventbookingData)) {
                        $eventbookingData = array();
                    }
                    $noOfPeople = count($eventbookingData);
                   if($noOfPeople == 0)
                   { ?>

                       No people attending

                 <?php  }else{ ?>

                    <strong>
                      <?php  echo $noOfPeople ?>
                    </strong> people attending
                       
              <?php     }
                    ?>

                </p>
                <?php
                $sql           = "SELECT * from event_locations where event_id=" . $result_events_organised["event_id"].' limit 1';
                $eventlocation = $DB->RunSelectQuery($sql);
                foreach ($eventlocation as $resultloc) {
                    $resultloc = (array) $resultloc;
                    ?>
                    <p class="ArialVeryDarkGrey15 word-break-it" style="text-overflow: ellipsis"><?php
                        echo str_replace(' ','&nbsp;',$resultloc["event_location"]);
                        ?></p>
                    <?php
                }
                ?>


                    <?php
                    $today = date("d-m-Y h:i:s");
                    $currentDate = strtotime($today);

                    $end = $result_events_organised["end_date"];
                    $endDate = strtotime($end);


                    if($endDate < $currentDate)
                    {
                        $eventCompletedStatus = 'True';
                        ?>
                        <div class="event-completed"><img src="images/green_tick.gif" style="vertical-align:middle" width="15"/>&nbsp;<span>Event Completed</span><div>

                            </div></div>

                    <?php  }else{
                        $eventCompletedStatus ='False';
                    }
                    ?>

                <?php
                if($eventCompletedStatus != 'True'){
            if ($current_user_id == $userid) {
                ?>
                <div class="section-bottom">
                    <button class="btn"
                            onClick="location.href='<?php echo createURL('index.php', "mod=event&do=editevent&eventid="
                                . $result_events_organised["event_id"]);?>'" class="slimbuttonblue">
                        Edit event
                    </button>
                <button class="btn" onClick="location.href='<?php echo createURL('index.php', "mod=event&do=participants&eventid="
                    . $result_events_organised["event_id"]);?>'" class="slimbutton">
                    Check participants
                </button>
                </div>
                <?php
            } else {
                ?>
                <button class="btn" onClick="location.href='<?php  echo ROOTURL;?>/participants.php?eventid=<?php
                echo $result_events_organised["event_id"];?>'"  class="slimbutton">See participants
                </button>
                <?php
            }}
                ?>
                </div>
                <?
                $i++;
                if ($i % 4 == 0) {

                    $i = 1;
                }
            } ?>
    </div>
<?
        } else {
            ?>
            <h5 class="ArialVeryDarkGrey15">No events organised yet...</h5>
            <?
        }
        ?>


    </div>

</body>
</html>
