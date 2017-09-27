<?php
include_once(TEMPPATH . "/header.php");

?>
<script>
    function cancelping(id){

        //POST BY AJAX TO DISPLAY EVENTS IN MAP LIST
        $.ajax({
            type: "POST",
            url: "<?php echo createURL('index.php', 'mod=ajax&do=cancel_ping');?>",
            data: { event_id:id },
            //dataType: 'json',
            cache: false,
            success: function(data)
            {
                if(data == 'Y') {
                    result = data;
                    $('#pingstatus'+id).html('Cancelled').addClass('disable-ping').attr('onclick','');

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
function limit_text($text, $limit) {
    if (str_word_count($text, 0) > $limit) {
        $words = str_word_count($text, 2);
        $pos = array_keys($words);
        $text = substr($text, 0, $pos[$limit]) . '...';
    }
    return $text;
}


$userid = $_REQUEST['userid'];

$status = "Confirmed";
$sql = "Select * from buddies where user_id= $userid and status='$status'";
$buddiesData = $DB->RunSelectQuery($sql);
$buddies_count = count($buddiesData);

$today = date("Y-m-d");
$sql = "Select * from event_bookings where user_id=$userid and booking_status='$status' and start_date>'$today'";
$booingData = $DB->RunSelectQuery($sql);
$event_attended_count = count($booingData);

$event_status = "L";
$sql = "Select * from events where event_status='$event_status' and user_id=$userid and entry_type=''";
$eventsData = $DB->RunSelectQuery($sql);
$event_organised_count = count($eventsData);

$current_user_id = $user_id;

$sql = "Select * from public_users where id=$userid";
$userData = $DB->RunSelectQuery($sql);
foreach($userData as $resultuser){
    $resultuser = (array) $resultuser;
    $firstname = $resultuser["firstname"];
    if ($resultuser["profile_pic"] == ""){
        $profile_pic = ROOTURL."/images/no_profile_pic.gif";
    }else{
        $profile_pic = ROOTURL."/".$resultuser["profile_pic"];
    }
    ?>

<div class="container pings-page fixed-footer">
    <div class="jumbotron">
        <div class="row">
            <div class="col-sm-5">

                <div class="row">
                    <div class="col-sm-6">
                        <img width="120" height="120" style="border-radius:60px" src="<?php  echo $profile_pic;  ?>" />
                    </div>
                    <div class="col-sm-6">
                        <h3 class="ArialVeryDarkGrey25"><?php
                            echo $resultuser["firstname"] . " " . $resultuser["lastname"];
                            ?></h3>
                        <h4><?php
                            echo $resultuser["mood_statement"];
                            ?></h4>
                        <?php
                        if (isset($user_id)) {
                            if ($current_user_id == $resultuser["id"]) {
                                ?>
                                <button class="btn btn-primary" onClick="location.href='<?php
                                echo CreateURL('index.php', "mod=user&do=editprofile");
                                ?>'"  class="slimbuttonblue">Edit profile</button>
                                <?php
                            }
                        }
                        ?>
                    </div> </div></div>
            <div class="col-sm-7">
                <div class="row">
                    <div class="col-sm-4">
                        <h1><?php
                            echo $buddies_count;
                            ?></h1>
                        <h4 class="ArialVeryDarkGrey15"><?php if( $buddies_count>1) echo  'Buddies'; echo 'Buddy'; ?></h4>
                    </div>
                    <div class="col-sm-4">
                        <h1><?php
                            echo $event_attended_count;
                            ?></h1>
                        <h4 class="ArialVeryDarkGrey15"><?php if( $event_attended_count>1) echo  'Events'; echo 'Event'; ?> attended
                        </h4>
                    </div>
                    <h1><?php
                        echo $event_organised_count;
                        ?></h1>
                    <h4 class="ArialVeryDarkGrey15"><?php if( $event_organised_count>1) {echo  'Events';}else {echo 'Event';} ?> organised
                    </h4></div>
            </div>
        </div>
    </div>
<?php } ?>
<h2>Pings organised</h2>
    <div class="row events">

        

            <?php $event_attended = "Y";
            $entry_type = "Ping";
            $sql = "Select * from events where user_id=$userid and entry_type='$entry_type'";
            $eventData = $DB->RunSelectQuery($sql);
            $i=1;
            if (count($eventData)>0){
            foreach($eventData as $result_events_organised){
            $result_events_organised = (array) $result_events_organised;
            ?>

                <div class="is-in-all-ping col-sm-3">
                        <div class="all-pings-name" style="padding:8px">
                                <a class="is-in-all-ping-name" href='<?php echo createURL('index.php',
                                    "mod=ping&do=pingdetails&eventid=".$result_events_organised["event_id"]);?>'>
                                    <?php  echo str_replace(' ','&nbsp;',$result_events_organised["event_name"]);
                                    ?>
                                </a>
                            </div>
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
                               <?php $booking_status="Confirmed";
                               $sql = "SELECT * from event_bookings where event_id=".$result_events_organised["event_id"]." and booking_status='$booking_status'";
                               $eventbookingData = $DB->RunSelectQuery($sql);
                               if (!is_array($eventbookingData)) {
                                   $eventbookingData = array();
                               }
                               $noOfPeople = count($eventbookingData);
                              if ($noOfPeople == 0)
                              { ?>
                                 No people attending
                            <?php  }else
                              { ?>
                                  <strong>
                                  <?php echo $noOfPeople; ?>

                                </strong> people attending
                           <?php    }
                               ?>
                              </p>
                            <?php $sql = "SELECT * from ping_locations where event_id=".$result_events_organised["event_id"];
                            $pinglocationData = $DB->RunSelectQuery($sql);
                            foreach( $pinglocationData as $resultloc){
                                $resultloc = (array)$resultloc;
                                ?>
                                 <h5 class="ArialVeryDarkGrey15 word-break-it" style="color:#666">
                                    <?php  echo str_replace(' ','&nbsp;',$resultloc["event_location"]);?>

                                </h5>
                            <?php } ?>

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

                    if ($current_user_id == $userid)
                    { ?>
                    <div class="section-bottom">
                   <button id="pingstatus<?=$result_events_organised["event_id"] ?>"
                           onClick="cancelping(<?=$result_events_organised["event_id"] ?>)" <? if ($result_events_organised["event_status"] == "L") { ?>
                           class="btn btn-primary"> Cancel ping

                                        <?php } else if ($result_events_organised["event_status"] == "C") { ?>
                                            class="btn btn-primary disable-ping">  Cancelled
                                        <?php } ?>
                                    </button></div>

                      <?php } }?>

                    </div>
            <?php $i++;
            if ($i%4 == 0){ ?>

            <?php $i=1; } }
            }else {
                ?>
                <h4 class="ArialVeryDarkGrey15">Nothing pinged yet...</h4>
            <? } ?>
       </div> </div>


</body>
</html>
