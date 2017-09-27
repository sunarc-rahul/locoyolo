<?php
global $DB, $user;

$lastID = $_REQUEST['lastID'];
$search = $_REQUEST['keyword'];
$toShow = 5;
$startFrom = $toShow*$lastID;
$currentUserData = $userData;
if($search!='') {


    $res_data_count = $DB->RunSelectQuery("SELECT count(*) as total FROM events WHERE event_name LIKE '%" . $search . "%'");


    $totalRec = $res_data_count[0]->total;

    if ($startFrom <= $totalRec) {

      $qry = "SELECT event_id as id,user_id, event_name,event_price, event_photo as img, 'event' as type, entry_type as data_type FROM events WHERE event_name LIKE '%" . $search . "%' limit $startFrom, $toShow";

        $result = $res_data = $DB->RunSelectQuery($qry);


    if (count($result) > 0) {

        foreach ($result as $row) {
            ?>


            <?php
            $row = (array)$row;

            $data[] = $row['event_name'];
            $search_event_id = $row['id'];
            $link = '';
            if ($row['type'] == 'user') {
                $link = $link = createURL('index.php', "mod=user&do=profile&userid=" . $row['id'] . "&s=" . $row['event_name']);
                $name = $row['event_name'];
            }
            if ($row['type'] == 'event') {
                if ($row['data_type'] == 'Ping') {

                    $link = createURL('index.php', "mod=ping&do=pingdetails&eventid=" . $row['id'] . "&s=" . $row['event_name']);
                    $name = $row['event_name'];
                } else {

                    $link = createURL('index.php', "mod=event&do=eventdetails&eventid=" . $row['id'] . "&s=" . $row['event_name']);
                    $name = $row['event_name'];
                }
            }
            if ($row['img'] != null) {
                $img = ROOTURL . '/' . $row['img'];
            } else {
                $img = ROOTURL . '/images/defaultbg.jpg';
            }
            ?>
            <div class="main-div-for-searchresult">
                <div class="col-md-3 goes-on-left">
                    <?php if ($row['data_type'] != 'Ping') { ?>
                        <img class="media-object" data-src="<?php echo $img; ?>" alt="64x64"
                             src="<?php echo $img; ?>"><?php } else { ?>
                        <img class="media-object" data-src="<?php echo ROOTURL . '/images/ping-bg.jpg' ?>" alt="64x64"
                             src="<?php echo ROOTURL . '/images/ping-bg.jpg' ?>">
                    <?php } ?>
                </div>
                <div class="col-md-9 goes-on-right">
                    <!--
                        ToDO:- For show Dynamic content on Search Result Page
                        Developer: Nitin Soni
                        Date:- 08August/2017
                    -->
                    <?php $event_attended = "Y";
                    $status = "Confirmed";
                    $i = 1;
                    $a = 1;
                    $sql4 = "Select p.profile_pic,concat(p.firstname,' ',p.lastname) as name ,b.* from event_bookings b left join public_users as p on p.id= b.user_id where event_id=$search_event_id and booking_status like '$status' GROUP BY event_id";
                    $getallgoing = $DB->RunSelectQuery($sql4);
                    if (is_array($getallgoing)) {
                        $getallgoing = $getallgoing;
                    } else {
                        $getallgoing = array();
                    }
                    ?>
                    <? $event_attended = "Y";
                    $status = "Pending";
                    $i = 1;
                    $a = 1;
                    $sql4 = "Select p.profile_pic,b.booking_status,concat(p.firstname,' ',p.lastname) as name ,b.* from event_bookings b left join public_users as p on p.id= b.user_id where event_id=$search_event_id and booking_status like '$status' GROUP BY event_id";
                    $getallins = $DB->RunSelectQuery($sql4);

                    $book_status = $getallins[0]->booking_status;
                    if (is_array($getallins)) {
                        $getallins = $getallins;
                    } else {
                        $getallins = array();

                    }
                    ?>

                    <?php
                    $sql = "SELECT * from event_locations where event_id=$search_event_id";
                    $locationData = $DB->RunSelectQuery($sql);
                    foreach ($locationData as $location) {
                        $data = (array)$location;
                        $eventLat = $data['event_lat'];
                        $eventLong = $data['event_long'];

                    }
                    $sql = "SELECT * from events where event_id=$search_event_id";
                    $eventData = $DB->RunSelectQuery($sql);
                    foreach ($eventData as $data) {
                        $result = (array)$data;
                        $userid = $result["user_id"];
                        //echo $userid;
                        $event_name = $result["event_name"];
                        $start_date = $result["start_date"];
                        $event_price = $result["event_price"];
                        $eventid = $result["event_id"];
                    }

                    $sql2 = "SELECT * from public_users where id=$userid";
                    $userData = $DB->RunSelectQuery($sql2);
                    foreach ($userData as $data) {
                        $resultuser = (array)$data;
                        $emailto = $resultuser["email"];
                        $organiser_name = $resultuser["firstname"];
                        $userid = $result["id"];
                    }

                    $email = $_SESSION['user_email'];

                    $query = "SELECT * from public_users where email='$email'";
                    $resultdata = $DB->RunSelectQuery($query);
                    foreach ($resultdata as $data1) {

                        $result = (array)$data1;
                        $participant_name = $result["firstname"];
                        $current_user_id = $result["id"];
                        $current_profile_pic = $result["profile_pic"];

                    }

                    ?>
                    <!-- Completed Here-->


                    <div class="col-md-2" id="has-results">

                        <div class="has-number"><h2><?php echo count($getallgoing); ?></h2></div>
                        <div class="interested going"> Going</div>
                        <div class="has-number"><h2><?php echo count($getallins); ?></h2></div>
                        <div class="interested"> Interested</div>
                    </div>
                    <div class="col-md-6" id="event-details-informations">
                        <a class="searchresult" href="<?php echo $link; ?>">
                            <h3 class="media-heading"><?php echo $name; ?></h3>
                        </a>
                        <!--Event Location Starts here -->
                        <div class="has-map">
                            <?php
                            $query = "SELECT * from event_locations where event_id=$search_event_id";
                            $data = $DB->RunSelectQuery($query);
                            foreach ($data as $resultLocation) {
                                $resultloc = (array)$resultLocation;
                                $location_details = $resultloc["event_location_description"];
                                ?>
                                <?php echo $resultloc["event_location"] ?>
                                <?php
                            }
                            ?>
                        </div>
                        <!--  Event Locations End -->
                        <div class="has-content">
                            <?php if ($result["event_description"] == null) {
                                echo "N/A";
                            } else {
                                ?>

                                <p><?php echo str_replace(' ', '&nbsp;', $result["event_description"]) ?></p>
                            <?php } ?>
                        </div>
                    </div>

                    <!-- This is the Part of Event Booking Button -->

                    <div class="row has-booking-details col-md-4">

                        <?php    if ($row['data_type'] != 'Ping') {
                                     echo '<strong>Price S$ </strong>';if($row['event_price']==0){echo 'Free';}else{ echo $row['event_price'];}
                        }?>
                        <div id="bookstatus" >

                           <?php if($row['user_id']!=$currentUserData->id) { ?>
                               <div class="is-booked-event-condition" <?php if ($book_status == 'Pending') {
                                   echo 'disabled="
                             "disabled"';
                               } ?>" class="btn btn-warning standardbutton bookbtn" type="submit" name="bookbtn"
                               id="<?php echo $eventid ?>"><?php if ($book_status == 'Pending') {
                                   echo '<sapn> <img src="images/green_tick.gif" /> </span>';
                                   echo '<span class="make-some-space"></span>';
                                   echo "<sapn>Booked</span>";

                               } else {
                                   echo '<button class="btn btn-warning standardbutton bookbtn" type="button" name="bookbtn"
                            id="' . $eventid . '">Book</button>';

                               }
                          ?> </div><?php }else{echo 'You organising this event';}
                            ?>
                        </div>
                            <input type="hidden" value="<?php echo $event_name; ?>" name="event_name"
                                   id="event_name_<?php echo $eventid; ?>">
                            <input type="hidden" value="<?php echo $start_date; ?>" name="start_date"
                                   id="start_date_<?php echo $eventid; ?>">
                            <input type="hidden" value="<?php echo $event_price; ?>" name="event_price"
                                   id="event_price_<?php echo $eventid; ?>">
                            <input type="hidden" value="<?php echo $eventid; ?>" name="event_id"
                                   id="event_id_<?php echo $eventid; ?>">
                            <input type="hidden" value="<?php echo $organiser_name; ?>" name="organiser_name"
                                   id="organiser_name_<?php echo $eventid; ?>">
                            <input type="hidden" value="<?php echo $emailto; ?>" name="emailto"
                                   id="emailto_<?php echo $eventid; ?>">

                        </div>

                    </div>
                    <!-- Completed Book event button work -->
                    <!-- Finsihed Serach Result Page by Nitin Soni -->
                </div>

            </div>
            <?php
        }

    } else {
        ?>

        <div class="display_box" align="left">
            <span class="name">No result found!</span></div>

        <?php
    }
}
else
    {
        ?>

        <div id="countSearch" class="display_box" align="left">
            <span class="name">No more records.</span>

        </div>
        <?php

    }


}

?>