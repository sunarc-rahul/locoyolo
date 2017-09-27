<?php include_once (TEMPPATH."/header.php");
$result = $res_data ;
if(!is_array($result))
{
$result =(array)$result;
}
$data = array();
$currentUserData = $userData;

?>
<style>
.search.container .searchresult {
    /*border-bottom: 1px solid #f4f4f4 !important;*/
    float: left;
   /* height: 86px !important;*/
/*    margin: 3px 0 !important;
    padding: 5px !important;*/
    width: 100%;
}
.search.container {
    margin-top: 10px;
}

</style>
    <div class="search container" id="search-result-page-wrapper">
        <div class="has-event-message">
          <div class="event-message-content col-sm-12">
                             <p>
                             <?php
                             if($res_data_count[0]->total==1)
                             {
                                echo $res_data_count[0]->total." "."Search result for"." ".$_REQUEST['search'];
                             }
                             else
                             {
                                echo $res_data_count[0]->total." "."Search results for"." ".$_REQUEST['search'];
                             }
                             ?>
                             </p>
                        </div>
          </div>

<?php if (count($result)> 0) {

foreach($result as $row) {
    ?>


            <?php
$row = (array) $row;

$data[] = $row['event_name'];
//echo $row['event_name'];
$search_event_id= $row['id'];
//echo $search_event_id;
//echo $row['type'];
//echo $row['data_type'];
$link='';
if($row['type']=='user')
{
$link =	$link = createURL('index.php',"mod=user&do=profile&userid=".$row['id']."&s=".$row['event_name']);
    $name = $row['event_name'];
}
if($row['type']=='event')
{
if($row['data_type']=='Ping')
{

$link = createURL('index.php',"mod=ping&do=pingdetails&eventid=".$row['id']."&s=".$row['event_name']);
    $name = $row['event_name'];
}
else
{

$link = createURL('index.php',"mod=event&do=eventdetails&eventid=".$row['id']."&s=".$row['event_name']);
    $name = $row['event_name'];
}
}
//		if(file_exists(ROOTURL.'/'.$row['img']))
if($row['img']!= null)
{
$img = ROOTURL.'/'.$row['img'];
}
else
{
$img = ROOTURL.'/images/defaultbg.jpg';
}
?>
    <div class="main-div-for-searchresult">
            <div class="col-md-3 goes-on-left">
                <?php if($row['data_type']!='Ping'){?>
                    <img class="media-object" data-src="<?php echo $img;?>" alt="64x64"
                         src="<?php echo $img;?>"><?php }else{ ?>
                    <img class="media-object" data-src="<?php echo ROOTURL.'/images/ping-bg.jpg'?>" alt="64x64"
                         src="<?php echo  ROOTURL.'/images/ping-bg.jpg'?>">
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
            $i=1;
            $a =1;
           $sql4 = "Select p.profile_pic,concat(p.firstname,' ',p.lastname) as name ,b.* from event_bookings b left join public_users as p on p.id= b.user_id where event_id=$search_event_id and booking_status like '$status' GROUP BY event_id";
            $getallgoing = $DB->RunSelectQuery($sql4);
/*            echo "<pre>";print_r($getallgoing);
            exit;*/
            if(is_array($getallgoing))
            {
                $getallgoing =$getallgoing;
            }
            else
            {
                $getallgoing = array();
            }
            ?>
            <?php $event_attended = "Y";
            $status = "Pending";
            $i=1;
            $a =1;
            $sql4 = "Select p.profile_pic,b.booking_status,concat(p.firstname,' ',p.lastname) as name ,b.* from event_bookings b left join public_users as p on p.id= b.user_id where event_id=$search_event_id and booking_status like '$status' GROUP BY event_id";
            $getallins=  $DB->RunSelectQuery($sql4);

         $book_status= $getallins[0]->booking_status;
            //exit;
            if(is_array($getallins))
            {
                $getallins=$getallins;
            }
            else
            {
                $getallins= array();

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
			//echo "<pre>";print_r($eventData);
			//exit;
			foreach ($eventData as $data) {
			    $result = (array)$data;
			    $userid = $result["user_id"];
			    //echo $userid;
			    $event_name = $result["event_name"];
			    $start_date = $result["start_date"];
			    $event_price = $result["event_price"];
			    $eventid= $result["event_id"];
			}

			$sql2 = "SELECT * from public_users where id=$userid";
			$userData = $DB->RunSelectQuery($sql2);
/*			echo "<pre>";print_r($userData);
			exit;*/
			foreach ($userData as $data) {
			    $resultuser = (array)$data;
			    $emailto = $resultuser["email"];
			    $organiser_name = $resultuser["firstname"];
			    $userid = $result["id"];
			}

			$email = $_SESSION['user_email'];

			$query = "SELECT * from public_users where email='$email'";
			$resultdata = $DB->RunSelectQuery($query);
			/*echo "<pre>";print_r($resultdata);
			exit;*/
			foreach ($resultdata as $data1) {

			    $result = (array)$data1;
			    $participant_name = $result["firstname"];
			    $current_user_id = $result["id"];
			    $current_profile_pic = $result["profile_pic"];

			}

			?>
<!-- Completed Here-->
            <div class="col-md-2" id="has-results">
                <div class="div-going-users">
                    <div class="has-number"><h2><?php echo count($getallgoing); ?></h2></div>
                    <div class="interested going"> Going</div>
                </div>
                <div class="div-interested-users">
                <div class="has-number"><h2><?php echo count($getallins); ?></h2></div>
                    <div class="interested"> Interested </div>
                </div>
            </div>
            <div class="col-md-6" id="event-details-informations">
            <a class="searchresult" href="<?php echo $link;?>">
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

                    <div  class="is-booked-event-condition"
                    <?php if($book_status=='Pending')
                    { echo 'disabled="disabled"';
                    } ?> id="<?php echo $eventid ?>"
                    ><?php if($book_status=='Pending')
                                 {
                                    echo '<sapn> <img src="images/green_tick.gif" /> </span>';
                                    echo '<span class="make-some-space"></span>';
                                    echo "<sapn>Booked</span>";

                                 }
                                 else

                                 {
                                    echo '<button class="btn btn-warning standardbutton bookbtn" type="button" name="bookbtn" id="'.$eventid.'">Book</button>';
                                 }
                   ?></div><?php }else{
                       if ($row['data_type'] != 'Ping')
                         {
                             echo 'You organising this event';
                         }
                       else
                           {
                               echo 'You organising this ping';
                           }
                       } ?>


                    <input type="hidden" value="<?php echo $event_name;?>" name="event_name" id="event_name_<?php echo $eventid;?>">
                    <input type="hidden" value="<?php echo $start_date;?>" name="start_date" id="start_date_<?php echo $eventid;?>">
                    <input type="hidden" value="<?php echo $event_price;?>" name="event_price" id="event_price_<?php echo $eventid;?>">
                    <input type="hidden" value="<?php echo $eventid;?>" name="event_id" id="event_id_<?php echo $eventid;?>">
                    <input type="hidden" value="<?php echo $organiser_name;?>" name="organiser_name" id="organiser_name_<?php echo $eventid;?>">
                    <input type="hidden" value="<?php echo $emailto;?>" name="emailto" id="emailto_<?php echo $eventid;?>">

                </div>
            </div>
            <!-- Completed Book event button work -->
<!-- Finsihed Serach Result Page by Nitin Soni -->
          </div>

        </div>
        <?php

// echo json_encode($data);exit;
}

}
else
{
?><div class="display_box" align="left">
    <span class="name">No result found!</span></div>
    <?php

}

?>
        </div>

<!-- Model Box is started Here -->

<!-- The Cancel Event Modal -->
<div id="bookpopup" class="modal">
    <!-- Modal content -->
    <div id="modal_content" class="modal-content" style="width:400px; padding:10px">
        <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
                <td>
                    <div id="join_message_content">
                        <table id="form_table">
                            <tr>
                                <td>
                                    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td><font class="ArialOrange18">Join event</font><br/>
                                                <br/>
                                                <font class="ArialVeryDarkGrey15">Send <span id='event_organiser_name'></span> a message
                                                    to join this event. Replies will come to your email.</font><br/>
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
                                                            <input type="hidden" id="clicked_event_id" value=""/>
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

                        <div id="respose_result" style="display: none;">
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td align="right">
                    <input class="standardbutton" style="cursor:pointer" type="submit" id="messagesentbtn"
                           name="messagesentbtn" value="OK"/><input class="standardbutton" style="cursor:pointer"
                                                                    type="submit" id="sendmessagebtn"
                                                                    name="sendmessagebtn" value="Send"/></td>
            </tr>
        </table>
    </div>
</div>

<!-- JavaScript Code Starts Here by Nitin Soni  -->

 <script type="text/javascript">




     $(document).ready(function () {

        var messagesentbtn = document.getElementById('messagesentbtn');
        // When the user clicks on the button, open the modal
        messagesentbtn.onclick = function () {
            bookpopup.style.display = "none";
        }
        //messagesentbtn.style.visibility = "hidden";
        // Get the button that opens the modal
         // Get the modal
        var bookpopup = document.getElementById('bookpopup');
		// When the user clicks anywhere outside of the modal, close it
        window.onclick = function (event) {
            if (event.target == bookpopup) {
                bookpopup.style.display = "none";
            }
        }
		/* This code will work,when we click on "Book" button */
        var bookbtn ='';
        //alert($('.bookbtn').length);
        if($('.bookbtn').length>=1){
            var bookbtn = $('.bookbtn');
        }
        // When the user clicks on the button, open the modal


               $(document).on('click','.bookbtn', function() {
                 
           		//To Get different  id of button
         		var Id = $(this).attr('id');

               $('#event_organiser_name').text($('#organiser_name_'+Id).val());
              // alert( $('#event_organiser_name').text($('#organiser_name_'+Id).val()););
           	   $('#clicked_event_id').val(Id);
               bookpopup.style.display = "block";
               $('#messagestatus').val('');
               $('#booking_message').html('').val('');
               $('#form_table').show();
               $('#sendmessagebtn').show();
               $('#respose_result').hide().html('');
               $('#messagesentbtn').hide();

           });

    		var sendmessagebtn = document.getElementById("sendmessagebtn");
            sendmessagebtn.addEventListener('click', function () {
            var event_id = $('#clicked_event_id').val();
            var emailfrom = "<?php echo $currentUserData->email; ?>";
            var emailto = $('#emailto_'+event_id).val();
            var event_name = $('#event_name_'+event_id).val();
            var organiser_name = $('#organiser_name_'+event_id).val();
            var participant_name = "<?php echo $currentUserData->firstname.' '.$currentUserData->lastname; ?>";
            var start_date = $('#start_date_'+event_id).val();
            var user_id = "<?php echo $currentUserData->id; ?>";
            var event_price = $('#event_price_'+event_id).val();
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
                    //$('#join_message_content').html(result);
                    //$('#booking_message').hide();
                    $('#form_table').hide();
               		$('#respose_result').show().html(result);
                    	$('#sendmessagebtn').hide();
                    	$('#messagesentbtn').show();
                        //This will Show After, when book is confirmed

                        $('#'+event_id).html('<img src="images/blue_tick.gif" style="vertical-align:middle" width="15" />&nbsp;<h5 class="ArialVeryDarkGrey15">Booking request sent</h5>');
                        $('#'+event_id).attr('disabled','disabled');


                    document.getElementById("messagestatus").value;
                    if (document.getElementById("messagestatus").value == "Sent") {
                        messagesentbtn.style.visibility = "visible";
                        sendmessagebtn.style.visibility = "hidden";
                        $('#bookstatus').html('<img src="images/blue_tick.gif" style="vertical-align:middle" width="15" />&nbsp;<h5 class="ArialVeryDarkGrey15">Booking request sent</h5>');
                    }

                }
            });

        });

});
</script>
<!--JavaScript Code Ended here -->
<div class="wrapper">
    <ul id="results"><!-- results appear here as list --></ul>
    <input type="hidden" id="lastID" value="1">
</div>
<?php if ($res_data_count[0]->total != '') {?>
<div id="loader" style="text-align:center;display: none"><img src="<?php echo ROOTURL . '/images/loading_icon.gif' ?>" /></div>
<?php } ?>
</div>

<script>
    $(document).ready(function(){

        var oneval = 0;

        var totalRec = "<?php echo   $res_data_count[0]->total; ?>";
        if($('#count').length!=0){

            oneval=$('#count').val();
        }else {

            oneval = 0;
        }
        $(window).scroll(function(){
            var lastID = $('#lastID').val();


            if (($(window).scrollTop() == $(document).height() - $(window).height() && lastID != 0) && (((parseInt(lastID)*5)+parseInt(5))<=parseInt(totalRec)) && parseInt(totalRec)>5){
                $('#search-result-page-wrapper').append("<div class='load-more'></div>");

                $('#loader').show();
                var keyword = "<?php echo $_REQUEST['search'];?>";
                $.ajax({
                    type:'POST',
                    url:'<?php echo createURL('index.php', 'mod=ajax&do=search');?>',
                    data:{lastID:lastID,keyword:keyword},
                    success:function(html){
                        $('.load-more').remove();

                        $('#lastID').val(parseInt(lastID)+parseInt(1));
                        $('#search-result-page-wrapper').append(html);
                        $('#loader').hide();
//                        alert((((parseInt(lastID)+parseInt(1))*5)+parseInt(5)));
                        if (((((parseInt(lastID)+parseInt(1))*5)+parseInt(5))>=parseInt(totalRec))){
                            $('#search-result-page-wrapper').append("<div class='load-more'>No more records.</div>");
                        }
                    }
                });
            }

        });
    });
    </script>