<?php
include_once(TEMPPATH . "/header.php");
if ($_GET["userid"]) {
    $userid = $_GET["userid"];
} else {
    $userid = $user_id;
}


?>

<script>
    $(document).ready(function () {

        // Get the modal
        var canceleventpopup = document.getElementById('canceleventpopup');
        // Get the modal
        var cancelbookingpopup = document.getElementById('cancelbookingpopup');
        // Get the button that closes the modal
        var rejectcancelbutton = document.getElementById("rejectcancelbutton");
        // Get the button that opens the modal
        var cancelconfirmbutton = document.getElementById("cancelconfirmbutton");
        // Get the button that closes the modal
        var rejectcancelbookingbutton = document.getElementById("rejectcancelbookingbutton");
        // Get the button that opens the modal
        var cancelbookingconfirmbutton = document.getElementById("cancelbookingconfirmbutton");
        // Get the button that closes the modal

        var close_cancelpopup = document.getElementById("close_cancelpopup");
        close_cancelpopup.style.display = "none";

        rejectcancelbutton.onclick = function () {
            canceleventpopup.style.display = "none";
        }
        rejectcancelbookingbutton.onclick = function () {
            cancelbookingpopup.style.display = "none";
        }
        cancelbookingconfirmbutton.onclick = function () {
            cancelbookingpopup.style.display = "none";
        }
        cancelconfirmbutton.onclick = function () {
            canceleventpopup.style.display = "none";
        }

        close_cancelpopup.onclick = function () {
            canceleventpopup.style.display = "none";
            document.getElementById('cancel_modal_btn_display_end').style.display = "none";
            document.getElementById('cancel_modal_btn_display_start').style.display = "block";
        }
        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function (event) {
            if (event.target == canceleventpopup) {

                canceleventpopup.style.display = "none";
            }
            if (event.target == cancelbookingpopup) {
                cancelbookingpopup.style.display = "none";
            }
        }
        /*Joined part*/
        cancelconfirmbutton.addEventListener('click', function () {

            $(".se-pre-con3").fadeIn("fast");
            var id = document.getElementById("section_id").value;
            //POST BY AJAX TO DISPLAY EVENTS IN MAP LIST
            $.ajax({
                type: "POST",
                url: "<?php echo createURL('index.php', "mod=ajax&do=cancel_event");?>",
                data: {event_id: id},
                //dataType: 'json',
                cache: false,
                success: function (data) {
                    if (data == 'Y') {
                        $('#eventstatus' + id).html('<br>&nbsp;&nbsp;&nbsp;The event has been cancelled.');
                        $('#cancelactivitymodal').modal('show');
                        $('#cancelactivitymodal').modal({backdrop: 'static', keyboard: false})
                    } else {
                        $(".se-pre-con3").fadeOut("slow");

                    }
                    document.getElementById('cancel_modal_btn_display_end').style.display = "block";
                    document.getElementById('cancel_modal_btn_display_start').style.display = "none";
                    $('#eventstatus' + id).html('<font class="ArialVeryDarkGreyBold15">Cancelled</font>');
                    $('#eventstatus' + id).attr('onclick', '');
                    $('#confirm_delete_event_content').html('<br>&nbsp;&nbsp;&nbsp;The event has been cancelled.');
                }
            });
        });

        /*Ping part*/
        cancelbookingconfirmbutton.addEventListener('click', function () {
            var id = document.getElementById("section_id").value;
            var userid = "<?php echo $userid; ?>";
            //POST BY AJAX TO DISPLAY EVENTS IN MAP LIST
            $.ajax({
                type: "POST",
                url: "<?php echo createURL('index.php', "mod=ajax&do=cancel_booking");?>",
                data: {event_id: id, user_id: userid},
                //dataType: 'json',
                cache: false,
                success: function (data) {

                    if (data == 'Y') {

                        $('#bookingstatus' + id).html('<h5 class="ArialVeryDarkGreyBold15">Cancelled</h5>');
                        $('#cancelactivitymodal').modal('show');
                        $('#cancelactivitymodal').modal({backdrop: 'static', keyboard: false})
                    } else {
                        alert('Sorry! Not able to cancel right now');
                    }

                    document.getElementById('cancel_booking_modal_btn_display_start').style.display = "none";
                    $('#confirm_cancel_booking_content').html('<br>&nbsp;&nbsp;&nbsp;Your booking has been cancelled.');

                }
            });
        });

    });

    function cancel_event(id) {
        document.getElementById("eventname").value = document.getElementById("eventname" + id).value;
        document.getElementById("eventphoto").value = document.getElementById("eventphoto" + id).value;
        document.getElementById("eventdatetime").value = document.getElementById("eventdatetime" + id).value;
        document.getElementById("eventlocation").value = document.getElementById("eventlocation" + id).value;
        document.getElementById("section_id").value = id;

        var eventname = document.getElementById("eventname").value;
        var eventphoto = document.getElementById("eventphoto").value;
        var eventdatetime = document.getElementById("eventdatetime").value;
        var eventlocation = document.getElementById("eventlocation").value;

        if ($("#profile_mobile").css('display') == 'block') {
            document.getElementById("cancel_event_modal").style.width = "300px";
        }

        $('#confirm_delete_event_content').html('<table width="100%" style="padding:5px"><tr><td colspan="3">Please confirm that you want to cancel:<br><br></td></tr><td width="35%"><img class="img-thumbnail" src="' + eventphoto + '" width="100%" style="vertical-align:middle"></td><td width="5%"></td><td><h5 style="ArialVeryDarkGrey15"><strong>' + eventname + '</strong><br>' + eventdatetime + '<br><h5 style="font-size:13px">' + eventlocation + '</h5></h5></td></tr></table>');
        canceleventpopup.style.display = "block";
    }

    function cancel_booking(id) {
        document.getElementById("eventname").value = document.getElementById("eventname" + id).value;
        document.getElementById("eventphoto").value = document.getElementById("eventphoto" + id).value;
        document.getElementById("eventdatetime").value = document.getElementById("eventdatetime" + id).value;
        document.getElementById("eventlocation").value = document.getElementById("eventlocation" + id).value;
        document.getElementById("section_id").value = id;

        var eventname = document.getElementById("eventname").value;
        var eventphoto = document.getElementById("eventphoto").value;
        var eventdatetime = document.getElementById("eventdatetime").value;
        var eventlocation = document.getElementById("eventlocation").value;

        if ($("#profile_mobile").css('display') == 'block') {
            document.getElementById("cancel_booking_modal").style.width = "300px";
        }

        $('#confirm_cancel_booking_content').html('<table width="100%" style="padding:5px"><tr><td colspan="3">Do you want to cancel your booking for:<br><br></td></tr><td width="35%"><img class="img-thumbnail" src="' + eventphoto + '" width="100%" style="vertical-align:middle"></td><td width="5%"></td><td><h5 style="ArialVeryDarkGrey15"><strong>' + eventname + '</strong><br>' + eventdatetime + '<br><h5 style="font-size:13px">' + eventlocation + '</h5></h5></td></tr></table>');
        cancelbookingpopup.style.display = "block";
    }


    function add_buddy(id) {
            var user_id = "<?php echo $user_id ?>";
            var buddy_id = id;

            //POST BY AJAX TO DISPLAY EVENTS IN MAP LIST
            $.ajax({
                type: "POST",
                url: "<?php echo CreateURL('index.php', 'mod=ajax&do=send_buddy_request'); ?>",
                data: {userid: user_id, buddyid: buddy_id},
                //dataType: 'json',
                cache: false,
                success: function (data) {

                    if (data == 'Y') {

                        $(".addBuddy span").html($(".addBuddy span").html() == 'Add buddy' ? 'Request sent' : 'Add buddy' ).insertAfter(".addBuddy");
                        $('.addBuddy').remove();
                    } else {
                        alert('Unable to send request.');
                    }

                }
            });

    }

</script>

<style>

    .profile-wrapper .is-attended-ping {
        padding-left: 12px;
        box-shadow: 0 1px 1px 1px #dcdcdc;
    }

    .is-attended-event.col-md-4 {

        box-shadow: 0 1px 1px 1px #DCDCDC;
        height: 385px;
        padding: 5px;
        overflow: hidden;

    }

    .is-attended-ping .col-md-4 {
        /*padding: 10px!important;*/
    }

    .is-attended-location {
        /*height: 15%;*/
        min-height: 35px;
    }

    .is-attended-ping.col-md-4 {
        height: 320px;
    }

    .is-attended-ping > p {
        font-size: 13px;
        font-weight: bold;
    }

    .is-attended-event-image img {

        min-height: 160px;
    }

    /*.is-attended-event-image > img {*/
    /*height: 40%;*/
    /*}*/
    .btn-outline {
        background-color: transparent;
        color: inherit;
        transition: all .5s;
    }

    .btn-primary.btn-outline {
        color: #428bca;
    }

    .btn-success.btn-outline {
        color: #5cb85c;
    }

    .btn-info.btn-outline {
        color: #5bc0de;
    }

    .btn-warning.btn-outline {
        color: #f0ad4e;
    }

    .btn-danger.btn-outline {
        color: #d9534f;
    }

    .btn-primary.btn-outline:hover,
    .btn-success.btn-outline:hover,
    .btn-info.btn-outline:hover,
    .btn-warning.btn-outline:hover,
    .btn-danger.btn-outline:hover {
        color: #fff;
    }
</style>
</head>

<body>
<!-- <div style="height:65px"></div> -->
<input type="hidden" id="eventname" value="">
<input type="hidden" id="eventphoto" value="">
<input type="hidden" id="eventdatetime" value="">
<input type="hidden" id="eventlocation" value="">
<input type="hidden" id="section_id" value="">
<!-- The Cancel Event Modal -->


<!-- The Cancel Booking Modal -->
<!-- The Cancel Booking Modal -->
<!-- The Cancel event/ping Modal -->


<div class="modal fade" id="organizeEvent"
     role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close"
                        data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Undo Cancelled Event</h4>
            </div>
            <div class="modal-body">
                <p>Do you want to reorganize
                    <b id="modal_event_name"></b>? </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    Close
                </button>
                <input type="hidden" name="modal-event-id" id="modal-event-id" value="">
                <button type="button" id="reorganize_event_details"
                        onclick="organize_events_reorganize();"
                        class="btn btn-info"
                        data-dismiss="modal">&nbsp;Yes&nbsp;&nbsp;</button>
            </div>
        </div>
    </div>
</div>
<!-- The Cancel event/ping Modal -->


<?php
function limit_text($text, $limit)
{
    if (str_word_count($text, 0) > $limit) {
        $words = str_word_count($text, 2);
        $pos = array_keys($words);
        $text = substr($text, 0, $pos[$limit]) . '...';
    }
    return $text;
}
$loggedInUserId = $_SESSION['user_id'];

$status = "Confirmed";
$sql = "Select user_id from buddies where buddy_id=$userid and status='Confirmed buddy'";
$result = $DB->RunSelectQuery($sql);
if (!is_array($result)) {
    $result = array();
}
$buddies_count = count($result);

$sql = "Select user_id from buddies where buddy_id=$userid and status='Pending'";
$result = $DB->RunSelectQuery($sql);
if (!is_array($result)) {
    $result = array();
}
 $requestSent_count = count($result);

//echo $status;exit;
$today = date("Y-m-d");
$sql = "Select count(*) as total from events where user_id=$userid and entry_type=''";
$eventCount = $DB->RunSelectQuery($sql);

$event_organised_count = $eventCount[0]->total;

$entry_type = "Ping";
$sqlping = "Select count(*) as total from events where user_id=$userid and entry_type='$entry_type'";
$pingCount = $DB->RunSelectQuery($sqlping);

$ping_organised_count = $pingCount[0]->total;

$current_user_id = $user_id;

if ($current_user_id != $userid) {
    $userIdForImage = $_GET["userid"];
} else {
    $userIdForImage = $user_id;
}

$sql = "Select user_id from buddies where buddy_id=$loggedInUserId and status='Confirmed buddy'";
$resultForBuddy = $DB->RunSelectQuery($sql);
foreach ($resultForBuddy as $resultuser) {
$buddy = (array)$resultuser;}

$sql = "Select * from public_users where id=$userIdForImage";
$result = $DB->RunSelectQuery($sql);
foreach ($result as $resultuser) {
    $resultuser = (array)$resultuser;
    $firstname = $resultuser["firstname"];
    if ($resultuser["profile_pic"] == "") {
        $profile_pic = ROOTURL . "/images/no_profile_pic.gif";
    } else {
        $profile_pic = ROOTURL . '/' . $resultuser["profile_pic"];
    }}

    ?>
    <div class="profile-wrapper">
        <div class="container fixed-footer">
            <div class="all-modal">
                <div class="row">
                    <div class="col-md-8">
                        <div class="modal fade" id="cancelactivitymodal" data-backdrop="static" data-keyboard="false"
                             tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">

                                    <div class="modal-body">
                                        <h4>Cancelled successfully.</h4>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" onClick="(location.reload(true))" class="btn btn-info "
                                                data-dismiss="modal">Close
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-8">
                        <div id="canceleventpopup" class="modal">

                            <!-- Modal content -->
                            <!--    <div class="modal-content" id="cancel_event_modal" style="width:400px; padding:10px">-->
                            <div class="modal-dialog modal-md" style=" position: relative; top: 100px;">
                                <div class="se-pre-con3"></div>
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Cancel event</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div id="cancel_modal_btn_display_start">

                                            <div id="confirm_delete_event_content">&nbsp;&nbsp;&nbsp;Please confirm that
                                                you want to
                                                cancel:
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <div id="cancel_modal_btn_display_end">
                                            <input class="standardgreybutton" style="cursor:pointer" type="submit"
                                                   id="close_cancelpopup" value="Ok"/>
                                        </div>
                                        <input class="btn btn-info" style="cursor:pointer" type="submit"
                                               id="cancelconfirmbutton" name="cancelconfirmbutton" value="Yes"/>&nbsp;&nbsp;
                                        <input class="btn btn-danger" style="cursor:pointer" type="submit"
                                               id="rejectcancelbutton" value="No"/>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8">
                        <div id="cancelbookingpopup" class="modal">

                            <div id="cancel_booking_modal" role="dialog">
                                <div class="modal-dialog modal-md">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Cancel Booking</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div id="confirm_cancel_booking_content">&nbsp;&nbsp;
                                                <p>Do you want to cancel your booking for:</p>
                                            </div>
                                        </div>
                                        <div class="modal-footer" id="confirm_cancel_booking_content">
                                            <button id="cancelbookingconfirmbutton" name="cancelconfirmbutton"
                                                    type="submit" class="btn btn-info btn-sm" data-dismiss="modal">Yes
                                            </button>
                                            <button type="submit" id="rejectcancelbookingbutton"
                                                    class="btn btn-danger btn-sm" data-dismiss="modal">NO
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="goes-left col-md-2">
                <div class="has-total-info">
                    <div class="has-profile-info">
                        <div class="has-profile-image">
                            <!--                            <img src="images/profile_img.jpg">-->
                            <img src="<?php echo $profile_pic; ?>" class="image-has-radius-2">
                        </div>
                        <div class="has-profile-name">
                            <strong><?php echo $firstname; ?></strong>
                        </div>
                        <?php if ($userid == $user_id) { ?>
                            <div class="has-add-buddy-btn">
                                <button class="btn btn-custom_535 btn_bold"
                                        onclick="location.href='<?php echo createURL('index.php', "mod=user&do=editprofile"); ?>'">
                                    Edit Profile
                                </button>
                            </div>
                        <?php } else { ?>
                            <div class="has-add-buddy-btn">
                                <?php if ($requestSent_count > 0) {?>
                                    <span>Request Sent</span>
                                <?php }else{?>
                         <?php if ($buddy['user_id'] != $userid || $buddy == ''){?>
                             <button class="btn btn-custom_535 btn_bold addBuddy" id="#addbuddybtn" onclick="add_buddy(<?php echo $userid ?>)"><span>Add buddy</span></button>

                                <?php }else{?>
                             <button class="btn btn-custom_535 btn_bold" id="#addbuddybtn" >buddy</button>
                                <?php } ?>
                           <?php } ?>
                            </div>
                            <div class="has-message-btn">
                                <button class="btn btn-custom_cb btn_bold">Message</button>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="has-profile-count">
                        <div class="has-buddy-count">
                            <div class="is-count"> <?php echo $buddies_count; ?></div>
                            <div class="is-count-info">buddies</div>
                        </div>
                        <div class="has-organised-event-count">
                            <div class="is-count"> <?php echo $event_organised_count; ?></div>
                            <div class="is-count-info">events organised</div>
                        </div>
                        <div class="has-ping-count">
                            <div class="is-count"><?php echo $ping_organised_count; ?></div>
                            <div class="is-count-info">pings</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="goes-right col-md-10">
                <div class="row has-title bookedNjoined" id="hasTitle">
                    <h3>Booked & Joined</h3>
                </div>
                <div class="row has-booked-joined">

                    <?php
                    $status = "Confirmed";
                    $status_ping = "Ping";
                    $today = date("Y-m-d");
                    $sql4 = "SELECT event_bookings.*, events.* FROM `event_bookings` join events on events.event_id = event_bookings.event_id where event_bookings.user_id = $userid and events.event_status != 'C' and event_bookings.booking_status = '$status' and events.user_id not in ($userid) group by events.event_id ORDER BY FIELD(entry_type, 'Ping') ASC";




                    $result = $DB->RunSelectQuery($sql4);
                    if (!is_array($result)) {
                        $result = array();
                    }
                    $i = 1;
                    if (is_array($result) && count($result) > 0) {
                        foreach ($result as $result_events_booked) {
                            $result_events_booked = (array)$result_events_booked;
                            $sql = "Select * from events where event_id=" . $result_events_booked["event_id"];
                            $result = $DB->RunSelectQuery($sql);
                            foreach ($result as $result_event) {
                                $result_event = (array)$result_event;
                                if ($result_event['entry_type'] == 'Ping') {
                                    ?>

                                    <div class="is-attended-ping col-md-4 ping ">
                                        <input type="hidden" id="eventname<?php echo $result_event["event_id"] ?>"
                                               value="<?php echo $result_event["event_name"] ?>">

                                        <input type="hidden" id="eventdatetime<?php echo $result_event["event_id"] ?>"
                                               value="<?php echo date("j F Y", strtotime($result_event["start_date"])) ?> | <?php echo date("g:ia", strtotime($result_event["start_time"])) ?>">
                                        <input type="hidden" id="eventlocation<?php echo $result_event["event_id"] ?>"
                                               value="<?php echo $result_event["event_location"] ?>">
                                        <input type="hidden" id="eventphoto<?php echo $result_event["event_id"] ?>"
                                               value="<?php
                                               echo ROOTURL . '/' . $result_event["event_photo"] ? ROOTURL . '/' . $result_event["event_photo"] : 'Image Not Available'; ?>">
                                        <div class="is-attended-event-image"></div>
                                        <div class="is-attended-ping-title">
                                            <div class="is-ping-heading">ping.</div>
                                        </div>
                                        <div class="in-info-whose-event-container">
                                            <div class="is-info-whose-event">
                                                <div class="is-info-has-image">
                                                    <?php
                                                    $organiser_id = $result_event["user_id"];
                                                    $sql = "Select * from public_users where id=$organiser_id";
                                                    $result = $DB->RunSelectQuery($sql);
                                                    $is_image_exist = file_exists(IMAGEURL . '/' . 'profile_pic/' . $result[0]->profile_pic);

                                                    if ($result[0]->profile_pic == '') { ?>
                                                        <img width="37" class="image-has-radius"
                                                             src="<?php echo IMAGEURL . '/no_profile_pic.gif'; ?>">

                                                    <?php } else { ?>
                                                        <img width="37" class="image-has-radius"
                                                             src="<?php echo $result[0]->profile_pic; ?>"
                                                             alt="event-image">
                                                    <?php } ?>
                                                </div>
                                                <div class="is-info-has-name lh37">
                                                    <a id="si-ping-title" href='<?php echo createURL('index.php', "mod=user&do=profile&userid=" . $result[0]->id); ?>'>
                                                        <span><strong><?php echo $result[0]->firstname; ?></strong></span>
                                                    </a>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="is-ping-title">
                                            <h3 class="is-bold">
												<a id="si-ping-title" href='<?php echo createURL('index.php', "mod=event&do=eventdetails&eventid=" . $result_event["event_id"]); ?>'>
                                                <?php echo $result_event["event_name"]; ?>
												</a>
                                            </h3>

                                        </div>
                                        <div class="is-ping-subtitle">
                                            <p><?php echo $result_event["event_objectives"] ? $result_event["event_objectives"] : 'NA' ?></p>
                                        </div>
                                        <div class="has-date-time">
                                            <span><?php echo date("j F Y", strtotime($result_event["start_date"])) ?></span>
                                            <span class="make-space">,</span><span>
                            <?
                            echo date("g:i A", strtotime($result_event["start_time"])) . " - " . date("g:i A", strtotime($result_event["end_time"]));
                            ?>
                        </span>
                                        </div>
                                        <div class="is-joined-ping-condition">
                                            <span><img src="images/green_tick.gif"></span>
                                            <span style="cursor: default">
                                                <?php echo $result_events_booked["booking_status"]; ?>
                                            </span>

                                            <span class="make-some-space"></span><span
                                                    class="make-some-space"></span>
                                    <?php if ($userid == $user_id) { ?>
                                            <button class="btn btn-danger"
                                                    id="bookingstatus<?php $result_event["event_id"] ?>"
                                                    data-target="cancelbookingpopup"
                                                    onClick="cancel_booking(<?php echo $result_event["event_id"] ?>)">
                                                Leave
                                            </button>
                                        <?php }?>
                                        </div>
                                        <!--                                        <div class="is-attended-ping-bg"></div>-->
                                    </div>
                                    <?php
                                } else
                                {
                                   ?>

<!--                                    --><?php //if ($result_event['user_id'] != $userid)
//                                    {
                                        ?>
                                        <div class="is-attended-event col-md-4 event">
                                            <input type="hidden" id="eventphoto<?php echo $result_event["event_id"] ?>"
                                                   value="<?php
                                                   echo ROOTURL . '/' . $result_event["event_photo"];
                                                   ?>">
                                            <input type="hidden" id="eventname<?php echo $result_event["event_id"] ?>"
                                                   value="<?php echo $result_event["event_name"] ?>">

                                            <input type="hidden"
                                                   id="eventdatetime<?php echo $result_event["event_id"] ?>"
                                                   value="<?php echo date("j F Y", strtotime($result_event["start_date"])) ?> | <?php echo date("g:ia", strtotime($result_event["start_time"])) ?>">
                                            <input type="hidden"
                                                   id="eventlocation<?php echo $result_event["event_id"] ?>"
                                                   value="<?php echo $result_event["event_location"] ?>">
                                            <input type="hidden" id="eventphoto<?php echo $result_event["event_id"] ?>"
                                                   value="<?php
                                                   echo ROOTURL . '/' . $result_event["event_photo"] ? ROOTURL . '/' . $result_event["event_photo"] : 'Image Not Available'; ?>">

                                            <div class="is-attended-event-image">
                                                <?php
                                                if (!$result_event["event_photo"] == '') { ?>
                                                    <img src="<?php echo ROOTURL . '/' . $result_event["event_photo"]; ?>">

                                                <?php } else { ?>
                                                    <!--                                            <span class="is-ping-heading"> --><?php //echo $result_event["entry_type"]; ?><!-- </span>-->
                                                    <img src="<?php echo IMAGEURL . '/no_profile_pic.gif'; ?>">

                                                <?php } ?>
                                            </div>
                                            <?php
                                            $eventId = $result_event['user_id'];
                                            $sql = "Select * from public_users where id=$eventId";
                                            $result = $DB->RunSelectQuery($sql);
                                            foreach ($result as $resultuser) {
                                                $resultuser = (array)$resultuser;
                                                $firstname = $resultuser["firstname"];
                                                if ($resultuser["profile_pic"] == "") {
                                                    $profile_pic = ROOTURL . "/images/no_profile_pic.gif";
                                                } else {
                                                    $profile_pic = ROOTURL . '/' . $resultuser["profile_pic"];
                                                }}

                                            ?>
                                            <div class="in-info-whose-event-container">
                                                <div class="is-info-whose-event">
                                                    <div class="is-info-has-image">
                                                        <img width="37px" class="image-has-radius"
                                                             src="<?php echo $profile_pic; ?>" alt="profile-image">
                                                    </div>
                                                    <div class="is-info-has-name lh37">
                                                        <a id="si-ping-title" href='<?php echo createURL('index.php', "mod=user&do=profile&userid=" . $resultuser["id"]); ?>'>
                                                            <span><strong><?php echo $firstname; ?></strong></span>
                                                        </a>


                                                    </div>
                                                </div>
                                                <div class="is-info-price">
                                                    <?php if (!$result_event["event_price"] == '') { ?>
                                                        <?php echo 'S$ ' . $result_event["event_price"]; ?>
                                                    <?php } elseif ($result_event["event_price"] == 0) { ?>
                                                        FREE
                                                    <?php } ?>
                                                </div>


                                            </div>
                                            <div class="is-attended-event-name">


                                                <a href='<?php echo createURL('index.php', "mod=event&do=eventdetails&eventid=" . $result_event["event_id"]); ?>'>
                                                    <?php echo  $result_event["event_name"]; ?> </a>
                                            </div>

                                            <div class="has-date-time">
                                                <?php
                                                $str = $result_event["start_date"];
                                                $strEnd = $result_event["end_date"];

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

                                                <div class="is-attended-people">
                                                    <strong>
                                                        <?php
                                                        $booking_status = "Confirmed";
                                                        $sql = "SELECT * from event_bookings where event_id=" . $result_event["event_id"] . " and booking_status='$booking_status' GROUP BY user_id";
                                                        $result = $DB->RunSelectQuery($sql);
                                                        if (!is_array($result)) {
                                                            $result = array();
                                                        }
                                                        echo count($result);
                                                        ?>
                                                    </strong> people attending
                                                </div>
                                                <div class="is-attended-location">
                                                    <?php
                                                    $sql = "SELECT * from event_locations where event_id=" . $result_event["event_id"];
                                                    $result = $DB->RunSelectQuery($sql);
                                                    foreach ($result as $resultloc) {
                                                        $resultloc = (array)$resultloc;
                                                        ?>
                                                        <p class="word-break-it" style="color:#666">
                                                            <?php

                                                            echo $result_event["event_location"];
                                                            ?>
                                                        </p>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            <?php
                                    $today = date("d-m-Y h:i:s");
                                    $currentDate = strtotime($today);

                                    $end = $result_event["end_date"];
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
                                            <?php if ($userid == $user_id) {
                                    if( $eventCompletedStatus != 'True'){?>


                                                <div class="is-booked-event-condition">
                                                    <span><img src="images/green_tick.gif"></span><span
                                                            class="make-some-space"></span>
                                                    <span class="booking-status"><?= $result_events_booked["booking_status"] ?> </span>
                                                    <span class="make-some-space"></span><span
                                                            class="make-some-space"> </span>
                                                    <span id="bookingstatus<?php $result_event["event_id"] ?>">

                            <button id="is-cancel-booking-button" data-target="cancelbookingpopup"
                                    onClick="cancel_booking(<?php echo $result_event["event_id"] ?>)"
                                    class="btn btn-warning">Cancel booking</button>


                        </span>
                                                </div>
                                            <?php } ?>


                                        <?php
//                                    }
                                }
                                ?></div> <?php
                            }

                            $i++;
                            if ($i % 3 == 0) {
                                $i = 1;
                            }
                        }
                    }} else {


                        ?>
                        <div id="booked-joined">
                            <font class="ArialVeryDarkGrey15">No upcoming events booked...<a
                                        href="<?php echo ROOTURL; ?>">Find stuff to
                                    do!</a></font>
                        </div>
                    <?php } ?>
                    <?php
                    if (count($result) > 10) { ?>
                        <div style="text-align:center">
                            <input class="standardbutton" style="cursor:pointer" type="submit" name="submit" id="submit"
                                   value="More events booked">
                        </div><?php } ?>
                </div>


                    <div class="row has-title organised" id="hasTitle">
                        <span style="color: #b06335;" class="h3">Events organised </span>

                        <?php
                        $event_attended = "Y";
                        $sql = "Select * from events where user_id=$userid and entry_type=''ORDER BY event_id DESC limit 6";
                        $result = $DB->RunSelectQuery($sql);

                        if (!array($result)) {
                            $result = array();
                        }
                        if (count($result) >= 6) {
                            ?>
                            <span class="sprint-right btn btn-info btn-outline btn-sm"
                                  onClick="location.href='<?php echo createURL('index.php', "mod=event&do=events&userid=" . $userid); ?>'">
                        See all <strong>
                            <?php echo $event_organised_count ?></strong> events
                    </span>
                            <?php
                        }
                        ?>
                        <div style="height: 7px;"></div>
                    </div>
                <div class="row has-events-organised">
                    <?php
                    $i = 1;
                    if (is_array($result) && count($result) > 0) {
                        foreach ($result as $result_events_organised) {
                            $result_events_organised = (array)$result_events_organised;
                            ?>
                            <div class="is-attended-event col-md-4">

                                <div class="is-attended-event-image">
                                    <?php if (!$result_events_organised["event_photo"] == '') { ?>
                                        <img src="<?php echo ROOTURL . '/' . $result_events_organised["event_photo"]; ?>">
                                    <?php } else { ?>
                                        <img src="<?php echo IMAGEURL . '/no_profile_pic.gif'; ?>">
                                    <?php } ?>
                                </div>

                                <div class="is-organised-event-price is-count">
                                    <?php if (!$result_events_organised["event_price"] == '') { ?>
                                        <?php echo 'S$ ' . $result_events_organised["event_price"]; ?>
                                    <?php } elseif ($result_events_organised["event_price"] == 0) { ?>
                                        FREE
                                    <?php } ?>
                                </div>
                                <div class="is-attended-event-name">
                                    <a href=' <?php echo createURL('index.php', "mod=event&do=eventdetails&eventid=" . $result_events_organised["event_id"]); ?>'>
                                        <?php
                                        echo $result_events_organised["event_name"];
                                        ?>
                                    </a>
                                </div>

                                <div class="has-date-time">
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
                                    <div class="is-attended-people">
                                        <?
                                        $booking_status = "Confirmed";
                                        $sql = "SELECT * from event_bookings where event_id=" . $result_events_organised["event_id"] . " and booking_status='$booking_status'GROUP BY user_id ";
                                        $result = $DB->RunSelectQuery($sql);
                                        if (!is_array($result)) {
                                            $result = array();
                                        }
                                         $noOfPeople = count($result);
                                        if($noOfPeople == 0)
                                        { ?>
                                           No people attending

                                     <?php   }else{ ?>
                                            <strong>
                                          <?php  echo $noOfPeople; ?>
                                            </strong> people attending
                                    <?php    }
                                        ?>

                                    </div>
                                    <div class="is-attended-location">
                                        <?php
                                        $sql = "SELECT * from event_locations where event_id=" . $result_events_organised["event_id"];
                                        $result = $DB->RunSelectQuery($sql);
                                        foreach ($result as $resultloc) {
                                            $resultloc = (array)$resultloc;
                                            ?>
                                            <p class="word-break-it" style="color:#666">
                                                <?php
                                                echo $resultloc["event_location"];
                                                ?>
                                            </p>
                                        <?php } ?>
                                    </div>
                                </div>

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
                                <div class="is-booked-event-condition display-block">
                                    <?php
                                    if ($current_user_id == $userid) {
                                        if( $eventCompletedStatus != 'True'){
                                        ?>
                                        <?php if ($result_events_organised["event_status"] == 'L') { ?>

                                            <span class="editEvent" onClick="location.href='<?php echo createURL('index.php', "mod=event&do=editevent&eventid=" . $result_events_organised["event_id"]); ?>'">
                                <button class="btn is-grey-colored">Edit Event</button>
                            </span>
                                        <?php } else {
                                            ?>
                                            <span> <button id="is-cancel-booking-button" style="cursor: default;"
                                                           class="btn disabled">Cancelled </button> </span>
                                        <?php } ?>
                                        <span class="make-some-space"> </span>
                                        <span class="make-some-space"></span>

                <?php if ($result_events_organised["event_status"] == 'C') { ?>
                                <span id="reorganizeButton" class="reorganizeButton" event_id="<?php echo $result_events_organised["event_id"]; ?>" >
                    <button data-toggle="modal" data-target="#organizeEvent" class="btn btn-info is-grey-colored">Undo Cancel </button>

                            </span>
<!--                                        --><?php //} else { ?>
                                        <?php } else { ?>
<span class="seeParticipants" onClick=location.href="<?php echo createURL('index.php', "mod=event&do=participants&eventid=" . $result_events_organised["event_id"]); ?>">
    <button class="btn btn-custom_535 is-grey-colored"> See participants </button>
</span>
                                        <?php } ?>
    <span class="make-some-space"></span> <span class="make-some-space"></span>

                                    <?php } }?>
                                </div>
                            </div>
                            <?php
                            $i++;
                            if ($i % 4 == 0) { ?>
                                <?php $i = 1;
                            }
                        }
                    } else {
                        ?>
                        <font class="ArialVeryDarkGrey15">No events organised yet...</font>
                        <?php
                    }
                    ?>
                </div>


                    <div class="row has-title organised" style="margin-bottom: 7px;"  id="hasTitle">
                        <span class="h3" style="color: #b06335">Pings</span>
                        <?php
                        $event_attended = "Y";
                        $entry_type = "Ping";
                      $sql = "Select * from events where user_id=$userid and entry_type='" . $entry_type . "'ORDER BY event_id DESC limit 6";

                        $result = $DB->RunSelectQuery($sql);
                        if (!is_array($result)) {
                            $result = array();
                        }
                        if (count($result) >= 6) {
                            ?>
                            <span class="sprint-right btn btn-info btn-outline btn-sm"
                                  onClick="location.href='<?php echo createURL('index.php', "mod=ping&do=pings&userid=" . $userid); ?>'">
                                See all <strong><?php echo $ping_organised_count ?></strong> pings
                            </span>
                            <?php
                        }
                        ?>
                    </div>
                <div class="row has-events-pings">
                    <?php
                    $i = 1;
                    if (count($result) > 0) {
                        foreach ($result as $result_events_organised) {
                            $result_events_organised = (array)$result_events_organised;
                            ?>
                            <div class="is-attended-ping col-md-4">
                            <input type="hidden" id="eventname<?php echo $result_events_organised["event_id"] ?>"
                                   value="<?php echo $result_events_organised["event_name"] ?>">
                            <input type="hidden" id="eventphoto<?php echo $result_events_organised["event_id"] ?>"
                                   value="<?php
                                   echo ROOTURL .'/images/ping-bg.jpg';
                                   ?>">
                            <input type="hidden" id="eventdatetime<?php echo $result_events_organised["event_id"] ?>"
                                   value="<?php echo date("j F Y", strtotime($result_events_organised["start_date"])) ?> | <?php echo date("g:ia", strtotime($result_events_organised["start_time"])) ?>">
                            <input type="hidden" id="eventlocation<?php echo $result_events_organised["event_id"] ?>"
                                   value="<?php echo $result_events_organised["event_location"] ?>">


                                <div class="is-attended-ping-title">
                                    <div class="is-ping-heading">ping</div>
                                </div>
                                <?php
                                $eventId = $result_events_organised['user_id'];
                                $sql = "Select * from public_users where id=$eventId";
                                $result = $DB->RunSelectQuery($sql);
                                foreach ($result as $resultuser) {
                                    $resultuser = (array)$resultuser;
                                    $firstname = $resultuser["firstname"];
                                    if ($resultuser["profile_pic"] == "") {
                                        $profile_pic_ping = ROOTURL . "/images/no_profile_pic.gif";
                                    } else {
                                        $profile_pic_ping = ROOTURL . '/' . $resultuser["profile_pic"];
                                    }}

                                ?>
                                <div class="in-info-whose-event-container">
                                    <div class="is-info-whose-event">
                                        <div class="is-info-has-image">
                                            <?php if (!$profile_pic_ping == '') { ?>
                                                <img width="45px" class="image-has-radius"
                                                     src="<?php echo $profile_pic_ping; ?>"
                                                     alt="ping-image">
                                            <?php } else { ?>
                                                <img class="image-has-radius"
                                                     src="<?php echo IMAGEURL . '/no_profile_pic.gif'; ?>">
                                            <?php } ?>
                                        </div>

                                        <div class="is-info-has-name lh37">
                                            <a id="si-ping-title" href='<?php echo createURL('index.php', "mod=user&do=profile&userid=" . $resultuser["id"]); ?>'>
                                                <span><strong><?php echo $firstname; ?></strong></span>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="is-ping-title">
                                    <h3 class="is-bold">
                                        <a href='<?php echo createURL('index.php', "mod=ping&do=pingdetails&eventid=" . $result_events_organised["event_id"]); ?>'>
                                            <?php echo $result_events_organised["event_name"] ?>
                                        </a>
                                    </h3>
                                </div>
                                <div class="is-ping-subtitle">
                                    <p><?php echo $result_events_organised["event_objectives"] ?></p></div>
                                <div class="has-date-time">
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
                                </div>

                                <div class="is-attended-people">
                                    <?php
                                    $booking_status = "Confirmed";
                                    $sql = "SELECT * from event_bookings where event_id=" . $result_events_organised["event_id"] . " and booking_status='$booking_status'GROUP BY user_id ";
                                    $result = $DB->RunSelectQuery($sql);
                                    if (!is_array($result)) {
                                        $result = array();
                                    }
                                    $noOfPeople = count($result);
                                    if ($noOfPeople == 0)
                                    { ?>
                                        No people attending
                                    <?php  }else{ ?>

                                        <strong>
                                            <?php  echo $noOfPeople; ?>
                                        </strong> people attending

                                     <?php  }
                                    ?>

                                </div>
                                <div class="is-attended-location">
                                    <?php
                                    $sql = "SELECT * from ping_locations where event_id=" . $result_events_organised["event_id"];
                                    $result = $DB->RunSelectQuery($sql);
                                    foreach ($result as $resultloc) {
                                        $resultloc = (array)$resultloc;
                                        ?>
                                        <p class="word-break-it" style="color:#666">
                                            <?php
                                            echo $resultloc["event_location"]; ?>
                                        </p>
                                        <?php
                                    }
                                    ?>
                                </div>
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
                                <div class="is-attended-ping-bg"></div>
                                <div class="modal fade" id="organizeEvent<?php echo $result_events_organised["event_id"]; ?>"
                                     role="dialog">
                                    <div class="modal-dialog">

                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close"
                                                        data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title">Undo Cancelled Ping</h4>
                                            </div>
                                            <div class="modal-body">
                                                <p>Do you want to reorganize
                                                    <b> <?php echo $result_events_organised['event_name'] ?></b>? </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                                    Close
                                                </button>

                                                <button type="button" id="reorganize_event_details"
                                                        onclick="organize_events(<?php echo $result_events_organised['event_id'] ?>);"
                                                        class="btn btn-info"
                                                        data-dismiss="modal">&nbsp;Yes&nbsp;&nbsp;</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="is-booked-event-condition display-block">

                                    <?php if ($current_user_id == $userid) {
                                        if( $eventCompletedStatus != 'True'){?>
                                        <?php if ($result_events_organised["event_status"] == "L") { ?>
                                            <div id="eventstatus<?php echo $result_events_organised["event_id"]; ?>">
                                                <button id="is-cancel-booking-button"
                                                        onClick="cancel_event(<?php echo $result_events_organised["event_id"] ?>)"
                                                        class="btn">Cancel Ping
                                                </button>
                                                <span class="make-some-space"></span>
                                                <span class="make-some-space"></span>
                                                <span class="btn is-grey-colored"
                                                      onClick="location.href='<?php echo createURL('index.php', "mod=ping&do=editping&pingid=" . $result_events_organised["event_id"]); ?>'">Edit Ping</span>

                                            </div>
                                        <?php } else if ($result_events_organised["event_status"] == "C") { ?>
                                            <button id="is-cancel-booking-button" class="btn disabled">Cancelled
                                            </button>
                                            <span class="make-some-space"></span>
                                            <span class="make-some-space"></span>
                                            <button id="reorganizeButton" class="reorganizeButton btn btn-info is-grey-colored" event_id="<?php echo $result_events_organised["event_id"]; ?>" class="btn is-grey-colored"
                                                    data-toggle="modal"
                                                    datasrc="<?php echo $result_events_organised['entry_type'] ?>"
                                                    data-target="#organizeEvent">
                                                Undo Cancel
                                            </button>

                                        <?php } ?>
                                    <?php }} ?>

                                </div>
                            </div>

                            <?php
                        }
                    } else {
                        ?>
                        <font class="ArialVeryDarkGrey15">Nothing pinged yet...</font>
                        <?php
                    }

                    ?>
                </div>

            </div>
            <div id="reorganizePing" class="modal fade" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 id="show_status" class="modal-title"></h4>
                        </div>
                        <div class="modal-body">
                            <p id="show_message"></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" onclick="window.location.href = window.location.href;"
                                    class="btn btn-default" data-dismiss="modal">Close
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div><!--Container -->
    </div><!--Profile- wrapper-->


    <?php
    $sql = "Select * from public_users where id=" . $userid;
    $result = $DB->RunSelectQuery($sql);
    foreach ($result as $resultuser) {
        $resultuser = (array)$resultuser;
        $firstname = $resultuser["firstname"];
        if ($resultuser["profile_pic"] == "") {
            $profile_pic = ROOTURL . "/images/no_profile_pic.gif";
        } else {
            $profile_pic = ROOTURL . '/' . $resultuser["profile_pic"];
        }
    }
//}
?>

<script>
    function organize_events(eventId) {
        $.ajax({
            type: "POST",
            url: "<?php echo CreateURL('index.php', "mod=ajax&do=reorganize_event")  ?>",
            data: {event_id: eventId},
            cache: false,
            success: function (data) {
                if (data == 'Y') {
                    $('#reorganizePing').modal('show');
                    $('#organizeEvent').css('display', 'none');
                    $('#show_message').html('Ping reorganized successfully.');
                    $('#show_status').html('Event Status');
                }
                else {
                    $('#reorganizeEvent').modal('show');
                    $('#organizeEvent').css('display', 'none');
                    $('#show_message').html('Sorry! some problem occured while querying data.');
                    $('#show_status').html('Event Status');

                }
            }

        });
    }
    function organize_events_reorganize() {

        var eventId = $("#modal-event-id").val();
        $.ajax({
            type: "POST",
            url: "<?php echo CreateURL('index.php', "mod=ajax&do=reorganize_event")  ?>",
            data: {event_id: eventId},
            cache: false,
            success: function (data) {
                if (data == 'Y') {
                    $("#reorganizePing").modal('show');
                    $('#organizeEvent').css('display', 'none');
                    $('#show_message').html('Ping reorganized successfully.');
                    $('#show_status').html('Ping Status');
                }
                else {
                    $('#reorganizePing').modal('show');
                    $('#organizeEvent').css('display', 'none');
                    $('#show_message').html('Sorry! some problem occured while querying data.');
                    $('#show_status').html('Ping Status');

                }
            }

        });
    }

    $('.reorganizeButton').click(function(){

        $('#modal-event-id').val($(this).attr('event_id'));
         if($(this).parent().parent().find('.is-attended-event-name').length<=0)
        {
             var title = $(this).parent().parent().find('.is-ping-title h3 a').text();
        }
        else
        {
            var title = $(this).parent().parent().find('.is-attended-event-name a').text();
        }
         $('#modal_event_name').text(title);
    });
</script>
</body>
</html>