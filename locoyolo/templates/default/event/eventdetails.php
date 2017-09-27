<?php
$loginuserData = $userData;
include_once(TEMPPATH . "/header.php");
$eventid = $_REQUEST['eventid'];
$sql = "SELECT entry_type from events where event_id = $userEvent_id";
$result = $DB->RunSelectQuery($sql);

if ($result) {
    if (isset($result['entry_type']) && ($result['entry_type'] != '' || $result['entry_type'] == 'ping')) {

        header("location:/locomvc");
    }
} else {
    //header("location:/lokoyolo/index.php");
//    echo "<script>window.location.href='/lokoyolo/index.php'</script>";
}

if (isset($_POST['cancelyesbutton'])) {
    $sql = "SELECT * from events where event_id=$userEvent_id";
    $query = $DB->RunSelectQuery($sql);
 //echo "<pre>";print_r($query);
    foreach ($query as $resultCheck) {
        $result_check_cancel = (array)$resultCheck;
         /*
            ToDO:- For show other_user_id on cancel event page
            Dev:- Nitin Soni
            Date:- 17Aug/2017
         */
         $event_organiser_id= $result_check_cancel["user_id"];
        /* echo $event_organiser_id;*/
        if ($result_check_cancel["event_status"] !== "C") {
            $dataToUpdate = [
                'event_status' => "C"
            ];

            $query = $DB->UpdateRecord('events', $dataToUpdate, 'event_id="' . $userEvent_id . '"');
            $sql = "Select * from event_bookings where event_id = $userEvent_id";
            $query = $DB->RunSelectQuery($sql);
           /* echo "<pre>";print_r($query);
            exit;*/

            foreach ($query as $data) {
                $fetchData = (array)$data;
                $insertData["other_user_id"] = $fetchData["user_id"];
                 $insertData["user_id"] =  $event_organiser_id;
                $insertData["event_id"] = $userEvent_id;
                $insertData["notification_type"] = "Cancel Event";
                $insertData["status"] = "Pending";
                $insertData["notification_date"] = date("Y-m-d H:i:s");

            }
            $sql = $DB->InsertRecord('notifications', $insertData);
        }
    }
}


$sql = "SELECT * from event_locations where event_id=$userEvent_id";
$locationData = $DB->RunSelectQuery($sql);
foreach ($locationData as $location) {
    $data = (array)$location;
    $eventLat = $data['event_lat'];
    $eventLong = $data['event_long'];

}

$sql = "SELECT * from events where event_id=$userEvent_id";
$eventData = $DB->RunSelectQuery($sql);
foreach ($eventData as $data) {
    $result = (array)$data;
    $userid = $result["user_id"];
    $event_name = $result["event_name"];
    $start_date = $result["start_date"];
    $event_price = $result["event_price"];

}

$sql2 = "SELECT * from public_users where id=$userid";
$userData = $DB->RunSelectQuery($sql2);
foreach ($userData as $data) {
    $resultuser = (array)$data;
    $emailto = $resultuser["email"];
    $organiser_name = $resultuser["firstname"];
}

$email = $_SESSION['user_email'];
$loggedInUserId = $_SESSION['user_id'];

$query = "SELECT * from public_users where email='$email'";
$resultdata = $DB->RunSelectQuery($query);
foreach ($resultdata as $data1) {

    $result = (array)$data1;
    $participant_name = $result["firstname"];
    $current_user_id = $result["id"];
    $current_profile_pic = $result["profile_pic"];

}

?>


<script type="text/javascript">
    function initMap() {

        var eventLat = <?php echo  $eventLat ? $eventLat : ''; ?>;
        var eventLong = <?php echo  $eventLong ? $eventLong : ''; ?>;
        // Create the map.
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 16,
            center: {lat: eventLat, lng: eventLong}
        });

        // Add the circle for this city to the map.
        var cityCircle = new google.maps.Circle({
            strokeColor: '#FF0000',
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: '#FF0000',
            fillOpacity: 0.35,
            map: map,
            center: {lat: eventLat, lng: eventLong},
            radius: 100
        });

        // Create the MOBILE map.
        var map_mobile = new google.maps.Map(document.getElementById('map_mobile'), {
            zoom: 16,
            center: {lat: eventLat, lng: eventLong}
        });

        // Add the circle for this city to the map.
        var cityCircle = new google.maps.Circle({
            strokeColor: '#FF0000',
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: '#FF0000',
            fillOpacity: 0.35,
            map: map_mobile,
            center: {lat: eventLat, lng: eventLong},
            radius: 100
        });

    }

    $(document).ready(function () {

        var sendcommentbtn = document.getElementById("sendcommentbtn");
        sendcommentbtn.addEventListener('click', function () {
            var comment_message = document.getElementById("comment_message").value;
            var user_id = "<?php echo  $current_user_id ?>";
            var event_id = "<?php echo  $eventid ?>";
            if (comment_message == '') {
                alert('Please enter comment.');
                return false;
            }
            //POST BY AJAX TO DISPLAY EVENTS IN MAP LIST
            $.ajax({
                type: "POST",
                url: "<?php echo CreateURL('index.php', "mod=ajax&do=send_comment");?>",
                data: {comment_message: comment_message, user_id: user_id, event_id: event_id},
                //dataType: 'json',
                cache: false,
                success: function (data) {
                    if (data == 'OK') {
                        result = data;
                    } else {
                        result = data;
                    }

                    if (document.getElementById("comments_number").value == "0") {
                        $('#commentsdisplay').html('');
                    }

                    $('#comment_message').val('');
                    $('#commentsdisplay').prepend(result);


                    document.getElementById("comments_number").value = parseInt(document.getElementById("comments_number").value) + 1;
                    document.getElementById("total_comments_number").value = parseInt(document.getElementById("total_comments_number").value) + 1;
                    var number_of_records = document.getElementById("comments_number").value;
                    var total_number_of_records = document.getElementById("total_comments_number").value;

                    if (number_of_records < total_number_of_records) {
                        $('#comments_display_progress').html('<font class="ArialVeryDarkGrey15" style="color:#999">Showing ' + number_of_records + ' of ' + total_number_of_records + ' comments | </font><div style="display:inline-block" onclick="add_comments()"><font class="ArialVeryDarkGrey15" style="color:#09C">See more comments</font>');
                    } else {
                        $('#comments_display_progress').html('<font class="ArialVeryDarkGrey15" style="color:#999">Showing ' + number_of_records + ' of ' + total_number_of_records + ' comments</font>');
                    }

                }
            });

        });


        var messagesentbtn = document.getElementById('messagesentbtn');
        // Get the modal
        var cancelpopup = document.getElementById('cancelpopup');
        // Get the modal
        var bookpopup = document.getElementById('bookpopup');
        // Get the button that opens the modal

        // Get the button that opens the modal
        var bookbtn ='';

        if($('#bookbtn').length>=1){
            var bookbtn = document.getElementById('bookbtn');
        }

//	var bookbtn_mobile = document.getElementById('bookbtn_mobile');

        // Get the <span> element that closes the modal
        var cancelyesbtn = document.getElementById("cancelyesbutton");
        var cancelnobtn = document.getElementById("cancelnobutton");
        // When the user clicks on the button, open the modal
        if (document.getElementById("canceleventbtn") && document.getElementById("canceleventbtn").value) {
            var canceleventbtn = document.getElementById('canceleventbtn');
            canceleventbtn.onclick = function () {
                cancelpopup.style.display = "block";
            }
        }
        if (document.getElementById("canceleventbtn_mobile") && document.getElementById("canceleventbtn_mobile").value) {
            var canceleventbtn_mobile = document.getElementById('canceleventbtn_mobile');
            canceleventbtn_mobile.onclick = function () {
                cancelpopup.style.width = "90%";
                cancelpopup.style.display = "block";
            }
        }

        // When the user clicks on the button, open the modal
        messagesentbtn.onclick = function () {
            bookpopup.style.display = "none";
        }
        messagesentbtn.style.visibility = "hidden";
        // When the user clicks on <span> (x), close the modal
        cancelyesbtn.onclick = function () {
            cancelpopup.style.display = "none";
        }
        cancelnobtn.onclick = function () {
            cancelpopup.style.display = "none";
        }
        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function (event) {
            if (event.target == cancelpopup) {
                cancelpopup.style.display = "none";
            }
            if (event.target == bookpopup) {
                bookpopup.style.display = "none";
            }
        }
        // When the user clicks on the button, open the modal
        if($('#bookbtn').length>=1){

           bookbtn.onclick = function () {
               bookpopup.style.display = "block";
           }
       }
//	bookbtn_mobile.onclick = function() {
//		bookpopup.style.display = "block";
//		document.getElementById("modal_content").style.width = "300px";
//	}

        var eventjustupdated = '<?
            echo $_SESSION["event_just_updated"];
            ?>';
        var eventupdatedpopup = document.getElementById('eventupdatedpopup');
        if (eventjustupdated == "YES") {
            eventupdatedpopup.style.display = "block";
        }
        var eventupdateokbtn = document.getElementById('eventupdateokbtn');
        eventupdateokbtn.onclick = function () {
            eventupdatedpopup.style.display = "none";
        }

        var sendmessagebtn = document.getElementById("sendmessagebtn");
        sendmessagebtn.addEventListener('click', function () {
            $(".se-pre-con2").fadeIn("fast");
            var emailfrom = "<?php echo $loginuserData->email; ?>";
            var emailto = "<?php echo  $emailto ?>";
            var event_name = "<?php echo  $event_name ?>";
            var organiser_name = "<?php echo  $organiser_name ?>";
            var participant_name = "<?php echo  $participant_name ?>";
            var event_id = "<?php echo  $eventid ?>";
            var start_date = "<?php echo  $start_date ?>";
            var user_id = "<?php echo  $current_user_id ?>";
            var event_price = "<?php echo  $event_price ?>";
            var booking_message = document.getElementById("booking_message").value;


            //POST BY AJAX TO DISPLAY EVENTS IN MAP LIST
            $.ajax({
                type: "POST",
                url: "<?php echo createURL('index.php', 'mod=ajax&do=join_event_message');?>",
                data: {
                    emailfrom: emailfrom,
                    emailto: emailto,
                    organiser_name: organiser_name,
                    participant_name: participant_name,
                    event_id: event_id,
                    booking_message: booking_message,
                    event_name: event_name,
                    start_date: start_date,
                    user_id: user_id,
                    event_price: event_price
                },
                //dataType: 'json',
                cache: false,
                success: function (data) {
                    if (data == 'OK') {
                        result = data;
                    } else {
                        result = data;
                    }
                    $('#join_message_content').html(result);
                    $(".se-pre-con2").fadeOut("slow");

                    if (document.getElementById("messagestatus").value == "Sent") {

                        messagesentbtn.style.visibility = "visible";
                        sendmessagebtn.style.visibility = "hidden";
                        $('#bookstatus').html('<img src="images/blue_tick.gif" style="vertical-align:middle" width="15" />&nbsp;<font class="ArialVeryDarkGrey15">Booking request sent</font>');
                    }

                }
            });

        });
        if($('#addbuddybtn').length>1) {
            var addbuddybtn = document.getElementById("addbuddybtn");
            addbuddybtn.addEventListener('click', function () {
                var user_id = "<?php echo  $current_user_id ?>";
                var buddy_id = "<?php echo  $userid ?>";
                //POST BY AJAX TO DISPLAY EVENTS IN MAP LIST
                $.ajax({
                    type: "POST",
                    url: "<?php echo CreateURL('index.php', 'mod=ajax&do=send_buddy_request'); ?>",
                    data: {userid: user_id, buddyid: buddy_id},
                    //dataType: 'json',
                    cache: false,
                    success: function (data) {
                        if (data == 'Y') {
                            $('#add_buddy_content').html(result);
                        } else {
                            alert('Unable to send request.');
                        }

                    }
                });
            });
        }
    });

    function delete_comment(id, name) {

        $.ajax({
            type: "POST",
            url: "<?php echo CreateURL('index.php', "mod=ajax&do=delete_comment");?>",
            data: {comment_id: id},
            //dataType: 'json',
            cache: false,
            success: function (data) {

                if (data == 'Y') {
                    $('#comment_content' + id).parent().parent().remove();
                } else {

                    alert('The comment was not deleted.');
                }
//                $('#comment_content'+id).html('<font class="ArialVeryDarkGreyBold15">'+name+'</font>&nbsp;<font class="ArialVeryDarkGrey15" style="color:#F63; font-style: italic;">Comment has been deleted.</font>');
            }
        });
    }
    function add_comments() {
//POST BY AJAX TO DISPLAY EVENTS IN MAP LIST

        var number_of_records = document.getElementById("comments_number").value;
        var total_number_of_records = document.getElementById("total_comments_number").value;
        var user_id = "<?php echo  $current_user_id ?>";
        var event_id = "<?php echo  $eventid ?>";
        var event_user_id = "<?php echo $userid ?>";
        $.ajax({
            type: "POST",
            url: "<?php echo CreateURL('index.php', "mod=ajax&do=show_more_comment");?>",
            data: {number_of_records: number_of_records, user_id: user_id, event_id: event_id,event_user_id:event_user_id},
            //dataType: 'json',
            cache: false,
            success: function (data) {
                if (data == 'OK') {
                    result = data;
                } else {
                    result = data;
                }
                $('#commentsdisplay').append(result);
                number_of_records = parseInt(number_of_records) + 10;
                if (number_of_records < total_number_of_records) {
                    document.getElementById("comments_number").value = number_of_records;
                    number_of_records = parseInt(number_of_records) - 10;
                    $('#comments_display_progress').html('<div class="ArialVeryDarkGrey15" style="color:#999">Showing ' + number_of_records + ' of ' + total_number_of_records + ' comments | </div><div style="display:inline-block" onclick="add_comments()"><div class="ArialVeryDarkGrey15" style="color:#09C">See more comments</div>');
                } else {
                    $('#comments_display_progress').html('<div class="ArialVeryDarkGrey15" style="color:#999">Showing ' + total_number_of_records + ' of ' + total_number_of_records + ' comments</div>');
                }
            }
        });
    }
</script>


<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDHXsI2hOfs6x7NJLR8LnN5wG-2N-ha0S8&callback=initMap">
</script>

<?php if (isset($_SESSION['success'])) {?>
    <div class="shows-errors_div has-error-message">
    <div class="container is-error-message">
        <div class="Show-error on-success">
            <span class="error-close on-success-close">X</span>
            <div class="alert alert-success">
                <?php  echo $_SESSION['success'];

                unset($_SESSION['success']);
                ?></div>

        </div>
    </div>
    </div><?php
}
?>
<!-- The Cancel Event Modal -->
<div id="cancelpopup" class="modal">

    <!-- Modal content -->
    <div class="modal-content" style="width:400px; padding:10px">
        <table width="420" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
                <td height="10"></td>
            </tr>
            <tr>
                <td height="20" class="ArialOrange18">Cancel Event<br/>
                    <br/></td>
            </tr>
            <tr>
                <td class="ArialVeryDarkGrey15">
                    <table width="400" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td>Are you sure you want to cancel this event?</td>
                            <td width="10">&nbsp;</td>
                            <td>
                                <form action="" method="post"><input class="standardbutton" style="cursor:pointer"
                                                                     type="submit" id="cancelyesbutton"
                                                                     name="cancelyesbutton" value="Yes"/>
                                    <input type="hidden" name="eventid" id="eventid" value="<?php echo $eventid ?>"/>
                                </form>
                            </td>
                            <td width="10">&nbsp;</td>
                            <td><input class="standardgreybutton" style="cursor:pointer" type="submit"
                                       id="cancelnobutton" value="No"/></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
        </table>
    </div>
</div>

<!-- The Event Update Modal -->
<div id="eventupdatedpopup" class="modal">

    <!-- Modal content -->
    <div class="modal-content" style="width:340px; padding:10px">
        <table width="320" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
                <td height="10"></td>
            </tr>
            <tr>
                <td class="ArialVeryDarkGrey15">
                    <table width="300" border="0" align="center" cellpadding="0" cellspacing="0">
                        <tr>
                            <td>Your event has been updated!</td>
                            <td width="10">&nbsp;</td>
                            <td><input class="standardbutton" style="cursor:pointer" type="submit" id="eventupdateokbtn"
                                       name="eventupdateokbtn" value="OK"/>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td height="10"></td>
            </tr>
        </table>
    </div>
</div>


<!-- The Cancel Event Modal -->
<div id="bookpopup" class="modal">
    <!-- Modal content -->
    <div id="modal_content" class="modal-content" style="width:400px; padding:10px">
        <div class="se-pre-con2"></div>
        <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
                <td>
                    <div id="join_message_content">
                        <table>
                            <tr>
                                <td>
                                    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td><div class="ArialOrange18">Join event</div><br/>
                                                <br/>
                                                <div class="ArialVeryDarkGrey15">Send <?php echo  $organiser_name ?> a message
                                                    to join this event. Replies will come to your email.</div><br/>
                                                <br/></td>
                                        </tr>
                                        <tr>
                                            <td class="ArialVeryDarkGrey15">
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                    <tr>
                                                        <td colspan="4"><textarea name="booking_message"
                                                                                  style="width:100%" rows="10"
                                                                                  class="textboxbottomborder"
                                                                                  id="booking_message"
                                                                                  placeholder="Send a request to join this event..."></textarea>
                                                            <input type="hidden" id="messagestatus" value=""/>
                                                            <br/>
                                                            <br/></td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
            <tr>
                <td align="right">
                    <input class="standardbutton" style="cursor:pointer" type="submit" id="messagesentbtn"
                           name="messagesentbtn" value="OK"/><input class="standardbutton" style="cursor:pointer"
                                                                    type="button" id="sendmessagebtn"
                                                                    name="sendmessagebtn" value="Send"/></td>
            </tr>
        </table>
    </div>
</div>
<!--Modal See all participant-->

<?php
$event_attended = "Y";
$status = "Pending";
$eventid = $_GET['eventid'];
$i=1;
$a =1;
$sql4 = "Select p.profile_pic,concat(p.firstname,' ',p.lastname) as name ,b.* from event_bookings b left join public_users as p on p.id= b.user_id where event_id = $eventid and booking_status like '$status'GROUP BY user_id";
$getallins=  $DB->RunSelectQuery($sql4);
//                print_r($getallins); exit;
if (is_array($getallins))
{
    $getallins=$getallins;
}
else
{
    $getallins= array();

}
?>
<!-- *******************************************Attending*******************************************************************************-->

<?php $event_attended = "Y";
$status = "Confirmed";
$i=1;
$a =1;
$sql4 = "Select p.profile_pic,concat(p.firstname,' ',p.lastname) as name ,b.* from event_bookings b left join public_users as p on p.id= b.user_id where event_id=$eventid and booking_status like '$status'GROUP BY user_id";
$getallgoing = $DB->RunSelectQuery($sql4);
if(is_array($getallgoing))
{
    $getallgoing =$getallgoing;
}
else
{
    $getallgoing = array();
}
?>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModal">Search Going & Interested Users</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#going">Going</a></li>
                        <li ><a href="#interested">Interested</a></li>
                    </ul>
                    <div class="tab-content">

                        <div id="going" class="tab-pane fade in active">

                            <div class="userlist">
                                <ul>
                                    <?php
                                    if(count($getallgoing)>=1)
                                    {
                                        foreach ($getallgoing as $goin_user )
                                        {
                                            $goin_user = (array) $goin_user; ?>
                                            <!--                                    <div><b>--><?php //echo $goin_user['name']?><!--</b></div>--><?php
                                            if ($goin_user['profile_pic'] == ""){
                                                $profile_pic = ROOTURL.'/'."images/no_profile_pic.gif";
                                            }else{
                                                $profile_pic = ROOTURL.'/'.$goin_user['profile_pic'];
                                            }


                                            ?>



                                            <li class="userdata">
                                                <img src="<?php echo $profile_pic; ?>">
                                                <a  href='<?php echo createURL('index.php', "mod=user&do=profile&userid=" . $goin_user["user_id"]); ?>'>
                                                    <span class="username"><?php echo $goin_user['name']?></span>
                                                </a>

                                            </li>
                                        <?php  }}else{?>
                                        <li class="userdata">No Users avalible.</li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>

                        <div id="interested" class="tab-pane fade">

<!--                            <div class="search-name"><input type="text" class="form-control" id="recipient-name" placeholder="Search name"></div>-->
                            <div class="userlist">
                                <ul>
                                    <?php
                                    if(count($getallins)>0)
                                    {
                                        foreach ($getallins as $goin_user )
                                        {
                                            $goin_user = (array) $goin_user;

                                            if ($goin_user['profile_pic'] == ""){
                                                $profile_pic = ROOTURL.'/'."images/no_profile_pic.gif";
                                            }else{
                                                $profile_pic = ROOTURL.'/'.$goin_user['profile_pic'];
                                            }
                                            ?>

                                            <li class="userdata">
                                                <img src="<?php echo $profile_pic; ?>">
                                                <a  href='<?php echo createURL('index.php', "mod=user&do=profile&userid=" . $goin_user["user_id"]); ?>'>
                                                    <span class="username"><?php echo $goin_user['name']?></span>
                                                </a>
                                            </li>
                                        <?php  }}else
                                    { ?>
                                        <li class="userdata">No Users avalible.</li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



<div id="event-detail-page-wrapper">
    <div class="container fixed-footer" style="width: 1170px;">
        <div class="has-event-message">
            <?
            $sql = "SELECT * from events where event_id=$userEvent_id";
            $eventData = $DB->RunSelectQuery($sql);
            foreach ($eventData as $event) {
                $result = (array)$event;
                $userid = $result["user_id"];
            }
            $sql2 = "SELECT * from public_users where id= $userid";
            $userData = $DB->RunSelectQuery($sql2);
            //
            foreach ($userData as $data) {
            $public_user = (array)$data;
            $profilepic = $public_user['profile_pic'];
            ?>
            <?
            if ($profilepic == null) {


                ?>
                <div class="event-message-image col-sm-1"><img class="image-has-radius" src="images/no_profile_pic.gif"
                                                               alt="event-image"></div>



                <div class="event-message-content col-sm-11">

                        <a id="si-ping-title" href='<?php echo createURL('index.php', "mod=user&do=profile&userid=" . $resultuser["id"]); ?>'>
                            <strong>  <?php echo $resultuser["firstname"]; ?></strong>
                        </a>

                    <p>is organizing an event.</p></div>
                <?
            } else {

                ?>
                <div class="event-message-image col-sm-1"><img class="image-has-radius" src="<?
                    echo ROOTURL . '/' . $profilepic;
                    ?>" alt="event-image"></div>
                <div class="event-message-content col-sm-11">

                        <a id="si-ping-title" href='<?php echo createURL('index.php', "mod=user&do=profile&userid=" . $resultuser["id"]); ?>'>
                            <strong><?php echo $resultuser["firstname"]; ?></strong>
                        </a>

                    <p>is organizing an event.</p>
                </div>
                <?php
            } ?>
        </div>
        <div class="modal fade" id="organizeEvent" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Undo Cancelled Event</h4>
                    </div>
                    <div class="modal-body">
                        <p>Do you want reorganize <b> <?php echo  $result['event_name'] ?></b> event? </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="reorganize_event_details"
                                onclick="organize_events(<?php echo  $result['event_id']; ?>);" class="btn btn-info"
                                data-dismiss="modal">&nbsp;Yes&nbsp;&nbsp;</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>
        <!-- Modal for see alll -->

        <div class="col-md-5 goes-on-left">
            <div class="row has-event-image">
                <?php if ($result["event_photo"] == "") { ?>
                    <img class="image-doesnt-has-radius" src="images/no_profile_pic.gif" alt="image">
                <?php } else { ?>
                    <img class="image-doesnt-has-radius" src="<?php echo ROOTURL . '/' . $result["event_photo"] ?>"
                         alt="image">
                <?php } ?>
            </div>

            <div class="row has-event-title">
                <h3><strong><?php echo  $result["event_name"] ?></strong></h3>
            </div>
            <div class="row has-date-time">
                <?php
                $str = $result["start_date"];
                $strEnd = $result["end_date"];

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

            <?php
            $today = date("d-m-Y h:i:s");
            $currentDate = strtotime($today);

            $end = $result["end_date"];
            $endDate = strtotime($end);


            if($endDate < $currentDate)
           {
               $eventCompletedStatus = 'True';
               ?>

               <div class="event-completed row"><img src="images/green_tick.gif" style="vertical-align:middle" width="15"/>&nbsp;<span>Event Completed</span></div>

         <?php  }else{
               $eventCompletedStatus ='False';
           }
            ?>

            <?php
            if ($userid == $current_user_id) {
                if( $eventCompletedStatus != 'True'){
                if ($result["event_status"] == "L") {
                    ?>
                    <div class="row" id="add_buddy_content">
                        <button class="btn btn-primary" name="edit_event"
                            <?php echo CreateURL('index.php', "mod=ajax&do=send_comment"); ?>
                                onclick="location.href='<?php echo CreateURL('index.php', "mod=event&do=editevent&eventid=" . $result['event_id']); ?>'">
                            Edit event
                        </button>

                        <button class="btn btn-primary" type="submit" id="canceleventbtn" value="Cancel event" ?>Cancel
                            event
                        </button>

                    </div>

                    <?
                } else {
                    ?>

                    <?
                }
            }}
            ?>
            <?
            $event_user_id = $result["user_id"];
            $current_user_email = $_SESSION["user_email"];
            $sqlQuery = "SELECT * from public_users where email='$current_user_email'  ";
            $queryData = $DB->RunSelectQuery($sqlQuery);

            foreach ($queryData as $resultdata) {
                $resultuser = (array)$resultdata;
                $current_user_id = $resultuser["id"];
            }

            $sql4 = "SELECT * from event_bookings where event_id=$userEvent_id and user_id=$current_user_id";

            $data = $DB->RunSelectQuery($sql4);

            foreach ($data as $resultData) {
                $result_booking = (array)$resultData;
                $booking_status = $result_booking["booking_status"];

            }


            if ($_SESSION["user_id"] == "") {
                ?>
                <span>Please<a href="<?php echo ROOTURL; ?>"
                               style="text-decoration:underline">sign in</a> to join.</span>
                <?
            } else {
                if ($current_user_id == $event_user_id) {
                    ?>

                    <?
                } else {
                    if ($result["event_status"] == "L" && $data < 1 && $endDate > $currentDate) {
                        ?>
                        <div class="row has-booking-details">
                                   <?php if($result['event_price'] <= 0){?>
                            <h3>Free</h3><span class="make-more-space"></span>

                            <?php }else{?>
                            <h3>S$ <?php echo $result['event_price'];?></h3><span class="make-more-space"></span>
                            <?php } ?>
                            <div id="bookstatus">
                                <button class="btn btn-warning standardbutton" type="button" name="bookbtn"
                                        id="bookbtn">Book a spot</button>
                            </div>
                        </div>
                        <?//
                    }
                    if ($result["event_status"] == "L" & $booking_status == "Pending") {
                        ?><div class="row"><img src="images/blue_tick.gif" style="vertical-align:middle" width="15"/>&nbsp;<span>Booking request sent</span></div>
                        <?
                    }
                    if ($result["event_status"] == "L" & $booking_status == "Confirmed" & $endDate > $currentDate ) {
                        ?><div class="row"><img src="images/green_tick.gif" style="vertical-align:middle" width="15"/>&nbsp;<span>Booking confirmed</span></div>
                        <?
                    }
                    if ($result["event_status"] == "N") {
                        ?>
                        <div class="row"><div class="ArialRedBold15">Event closed</div></div><?
                    }
                }
                if ($result["event_status"] == "C") { ?>
                    <!--                    <div class="ArialRedBold15">Event cancelled</div>-->
					<div class="row">
                    <button class="btn btn-danger btn_bold " disabled="disabled">
                        Event cancelled
                    </button>
                    <?php if ($userid == $loggedInUserId){?>
                    <button class="btn btn-info btn_bold btn-reorganise" data-toggle="modal" data-target="#organizeEvent">
                        Undo Cancel
                    </button>
					
                        <?php } ?>
						</div>
                    <!--                    <div class="ArialRedBold15">Reor</div>-->
                    <?
                }
            }
            ?>

            <?php $status = "Confirmed";
            $qryToFetchBuddy = " SELECT buddies.buddy_id, public_users.profile_pic, CONCAT(public_users.firstname, ' ', public_users.lastname ) As FullName
                FROM buddies INNER JOIN public_users ON buddies.buddy_id=public_users.id WHERE user_id = $current_user_id AND status = '$status'";
            $data = $DB->RunSelectQuery($qryToFetchBuddy);
            if (!is_array($data)) {
                $data = array();
            }
            ?>
<!-- *******************************************Attending*******************************************************************************-->
           
            <div class="has-people-interested">
                <div class="row has-participants">
                    <p>
                        <span class="has-number"><?php echo count($getallgoing); ?></span><span class="interested going"> going</span><span>, </span><span class="has-number"><?php echo count($getallins); ?></span><span class="interested"> interested </span><span class="make-more-space"></span>
                        <button type="button" class="btn btn-custom_468 btn_bold" data-toggle="modal" data-target="#exampleModal"> See All  </button>
                    </p>
                </div>
            </div>

            <?php if (count($data) < 1) { ?>
                <div class="row" id="has-invite-friends">
                    <h4><strong>Invite your buddy</strong></h4>
                    <span>No Friend Available</span>
                </div>
            <?php } else { ?>

                <div class="row" id="has-invite-friends">
                    <h4><strong>Invite your buddy</strong></h4>
                    <!--                --><?php foreach ($data as $buddyData) {
                        $buddy = (array)$buddyData;

                        if (isset($buddy['profile_pic']) && $buddy['profile_pic'] != '') {
                            $img = $buddy['profile_pic'];
                        } else {
                            $img = 'images/profile_img.jpg';

                        }
                        ?>
                        <div class="has-invites">
                        <span class="buddy-img"><img class="image-has-radius" src="<?php echo $img ?>"
                                                     alt="event-image"></span>
                            <span class="make-more-space"></span>
                            <span class="has-friend-name"><strong><?php echo $buddy['FullName'] ?></strong></span>
                            <a class="invite-button">invite</a>
                        </div>
                    <?php } ?>


                    <div class="has-see-all-friends-btn">
                        <button class="btn btn-primary">See all friends</button>
                    </div>
                </div>
            <?php } ?>
        </div>
        <!-- Work Started Here  -->
        <div class="col-md-7 goes-on-right">
            <div class="row has-event-summary">
                <div class="has-title">
                    <h3>Event Summary</h3>
                </div>
                <div class="has-content">
                    <?php if ($result["event_description"] == null) {
                        echo "N/A";
                    } else {
                        ?>

                        <p><?php echo  $result["event_description"] ?></p>
                    <?php } ?>
                </div>
            </div>
            <div class="row has-event-summary has-event-objective">
                <div class="has-title">
                    <h3>Event Objectives</h3>
                </div>
                <div class="has-content">
                    <?php
                    if ($result["event_objectives"] == null)
                    {
                        echo "N/A";
                    }else { ?>
                    <ol>
                        <?php //$event_objectives_string = substr(trim($result["event_objectives"]), 0, -1);
                        //$event_objectives = explode(";", $event_objectives_string);
                        $event_objectives = explode(";", $result["event_objectives"]);

                        $i = 0;
                        while ($i < sizeof($event_objectives)) { ?>
                            <li><?php echo  $event_objectives[$i] ?></li>
                            <?php $i++;
                        }
                        }
                        ?>
                    </ol>
                </div>
            </div>
            <!-- Main work is here -->
            <div class="row has-event-summary has-event-requirements">
                <div class="has-title">
                    <h3>Requirements</h3>
                </div>
                <div class="has-content">
                    <p><span><strong>Attire:&nbsp;</strong></span><span><?php echo  $result["event_attire"] ?></span></p>
                    <p><?php echo  $result["event_attire_desc"] ?></p>
                </div>

                <div class="has-content">
                    <p><span><strong>F&amp;B:&nbsp;</strong></span><span><?php echo  $result["event_food_and_drinks"] ?></span>
                    </p>
                    <span><?
                        if ($result["event_food_and_drinks_desc"] !== "Not applicable") {
                            echo $result["event_food_and_drinks_desc"];
                        }
                        ?></span>
                </div>

                <?
                if ($result["event_fitness"] !== "Not applicable") {
                    ?>
                    <div class="has-content">
                        <p><span><strong>Fitness:&nbsp;</strong></span><span> <?php echo  $result["event_fitness"] ?></span>
                        </p>
                        <p> <?php echo  $result["event_fitness_desc"] ?></p>
                    </div>
                    <?
                }
                if ($result["event_essentials"] !== "") {
                    ?>
					<div class="has-content">
						<p><span><strong>Essentials:&nbsp;</strong></span><span><?php echo  $result["event_essentials"] ?></span>
						</p>
					</div>
                    <?
                }
                if ($result["event_safety"] !== "") {
                    ?>
                    <div class="has-content">
                        <p><span><strong>Safety:&nbsp;</strong></span><span><?php echo  $result["event_safety"] ?></span></p>
                    </div>
                    <?
                }
                if ($result["event_additional_notes"] !== "") {
                    ?>
					<div class="has-content">
						<p>
							<span><strong>Additional notes:&nbsp;</strong></span><span><?php echo  $result["event_additional_notes"] ?></span>
						</p>
					</div>

                    <?
                }
                ?>
            </div><!-- Main work is completed here -->
            <?php


            ?>
            <div class="row has-map-title"><h3>Event Location</h3></div>

            <div class="row has-map">
                <?
                $query = "SELECT * from event_locations where event_id=$userEvent_id";
                $data = $DB->RunSelectQuery($query);
                foreach ($data as $resultLocation) {
                    $resultloc = (array)$resultLocation;
                    $location_details = $resultloc["event_location_description"];
                    ?>
                    <?php echo  $resultloc["event_location"] ?>
                    <?
                }
                ?>
                <div id="map" style="height:300px"></div>
                <!--                --><?//
                //                if ($location_details !== "") {
                //                    ?>
                <!--                    <span><b>Location Details:</b></span>-->
                <!--                    --><?//= $location_details ?>
                <!--                    --><?//
                //                }
                //                ?>

            </div>


            <div class="has-title footer-heading-comments"><h4>Comments</h4></div>
            <div class="row has-comments">

                <?php
                if ($current_profile_pic == "") {
                    ?>
                    <div class="col-md-2 event-user-image"><img class="image-has-radius" src="images/no_profile_pic.gif"
                                                                alt="event-image"></div>
                    <?php
                } else {
                    ?>

                    <div class="col-md-2 event-user-image"><img class="image-has-radius" src="<?
                        echo ROOTURL . '/' . $current_profile_pic;
                        ?>" alt="event-image"></div>
                    <?php
                }
                ?>
                <div class="row col-md-8 comment-textarea"><input type="textarea" class="comment-area"
                                                                  id="comment_message" placeholder="Send a Comment...">
                </div>
                <div class="row col-md-2 send-comment"><input class="standardgreybutton" style="cursor:pointer"
                                                              type="submit" name="sendcommentbtn" id="sendcommentbtn"
                                                              value="Send"/></div>
            </div>
            <div class="has-comment-area">

            </div>
            <?

            $sql = "Select * from comments where event_id= $userEvent_id";

            $commentData = $DB->RunSelectQuery($sql);
            $total_comments_number = count($commentData);
            //
            $sql = "Select * from comments where event_id=$userEvent_id order by id desc limit 0,3";
            $stmt = $DB->RunSelectQuery($sql);
            ?>


            <div id="commentsdisplay">
                <?
                if (count($stmt) > 0) {
                    foreach ($stmt as $commentData) {
                        $result = (array)$commentData;
                        ?>
                        <div class="comment-person" id="comment-person">

                            <div style="display:inline-block; vertical-align:top; width:35px">
                                <?
                                $user_id = $result["user_id"];
                                $sql2 = "SELECT * from public_users where id= $user_id";
                                $Data = $DB->RunSelectQuery($sql2);
                                foreach ($Data as $resultData) {
                                    $resultuser = (array)$resultData;
                                    $profilepic = $resultuser['profile_pic'];
                                    $user_name = $resultuser['firstname'] . " " . $resultuser['lastname'];
                                    if ($profilepic == "") {
                                        ?>
                                        <img width="30" height="30" valign="middle"
                                             style="border-radius:100px" src="images/no_profile_pic.gif"/>
                                    <?php } else { ?>
                                        <img width="30" height="30" valign="middle"
                                             style="border-radius:100px"
                                             src="<?php echo ROOTURL . '/' . $profilepic; ?>"/>
                                    <?php }
                                } ?>
                            </div>


                            <div style="display:inline-block; width:10px; vertical-align:top;">

                                <img src="images/speech_triangle.gif" width="10"/></div>


                            <div style="display:inline-block;">
                                <div style="border-radius:0px 3px 3px 3px; padding:0 5px 5px 5px"
                                     id="comment_content<?php echo  $result["id"] ?>"><div
                                            class="ArialVeryDarkGreyBold15"><?php echo $user_name;?></div>
                                    <div class="ArialVeryDarkGrey15"> <?php echo $result["comment"] ?></div>
                                    <div style="display:inline-block;"><div class="ArialVeryDarkGrey15"
                                                                             style="color:#999; font-size:13px"> <?php echo  date("j M Y", strtotime($result["entry_date"])) ?>
                                            , <?php echo  date("h:i a", strtotime($result["entry_date"])) ?></div>
                                    </div>
                                    &nbsp;&nbsp;
                                    <?php if ($loginuserData->id == $resultuser['id'] || $loginuserData->id == $event_user_id) { ?>

                                        <div style="display:inline-block; cursor:pointer"
                                             onclick="delete_comment(<?php echo  $result["id"] ?>, '<?php echo  $user_name ?>')"><div
                                                    class="ArialVeryDarkGrey15" style="color:#F63; font-size:13px">Delete</div>
                                        </div>
                                    <?php }
                                    ?>
                                </div>
                            </div>


                        </div>
                        <?
                    }
                } else { ?>
                    <div style="height:40px; width:100% margin: 0 auto; text-align:center">
                        <div style="height:20px"></div>
                        <div class="ArialVeryDarkGrey15">No comments yet...</div></div>
                    <?
                }
                ?>
            </div>

        </div><!-- completed Here -->
        <input type="hidden" value="<?php echo  count($stmt) ?>" id="comments_number"/>
        <input type="hidden" value="<?php echo  $total_comments_number ?>" id="total_comments_number"/>
        <div id="comments_display_progress"> <?
            if ($total_comments_number > 3) {
                ?><div class="ArialVeryDarkGrey15" style="color:#999">Showing <?php echo  count($stmt) ?>
                of <?php echo  $total_comments_number ?> comments | </div>
                <div style="display:inline-block" onclick="add_comments()"><div class="ArialVeryDarkGrey15"
                                                                                 style="color:#09C">See more
                        comments</div></div>    <?
            }

            }
            ?></div>
    </div>
</div>
<div id="reorganizeEvent" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Event Status</h4>
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
<script>
//    $(document).ready(function () {

    function organize_events(eventId) {

            $.ajax({
                type: "POST",
                url: "<?php echo CreateURL('index.php', "mod=ajax&do=reorganize_event")  ?>",
                data: {event_id: eventId},
                cache: false,
                success: function (data) {

                    if (data == 'Y') {

                        $('#reorganizeEvent').modal('show');
                        $('#organizeEvent').css('display', 'none');
                        $('#show_message').html('Event reorganized successfully.');
                    }
                    else  {
                        $('#reorganizeEvent').modal('show');
                        $('#organizeEvent').css('display', 'none');
                        $('#show_message').html('Sorry! some problem occured while querying data.');

                    }
                }

            });
    }
//    }) ;
</script>
<script>
    $(document).ready(function(){

        $(".nav-tabs a").click(function(){
            $(this).tab('show');
        });
        $('.nav-tabs a').on('shown.bs.tab', function(event){
            var x = $(event.target).text();         // active tab
            var y = $(event.relatedTarget).text();  // previous tab
            $(".act span").text(x);
            $(".prev span").text(y);
        });
    });
</script>
</body>


</html>