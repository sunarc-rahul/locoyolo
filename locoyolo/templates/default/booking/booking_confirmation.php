<?php
$event_code = $_GET['event_code'];
$eventid = $_GET['event_id'];

if ($_POST["code"] !== ""){

    $request_code = $_POST["code"];
    $user_id = $user_id;

    $event_info = $DB->SelectRecord('events', "event_code = '$event_code' and event_id=".$eventid." and request_code='".$request_code."'");

    $numofrows = count($event_info);

    if ($numofrows > 0){

        $event_infos = $DB->SelectRecord('events', "event_id=".$eventid);
        foreach($event_infos as $result){
            $event_infos = (array) $event_infos;
            $max_participants = $result["event_participants_max"];
            $event_participants = $result["event_participants"];
        }

        $event_participants = $event_participants + 1;

        if ($event_participants > $max_participants) {

        }else{

           $event_booking_info = $DB->SelectRecord('event_bookings', "event_id=".$eventid."  and user_id=".$user_id);
            $booking_check_number = count($event_booking_info);
            foreach($event_booking_info as $resultuser){
                $resultuser =(array) $resultuser;
                $booking_status = $resultuser["booking_status"];
            }

            if ($booking_status !== "Confirmed") {
                //SUBMIT UPDATE OF EVENT PARTICIPANT NUMBER TO EVENTS TABLE
                $booking_status = "Confirmed";
                $data['booking_status']= $booking_status;
                $DB->UpdateRecord('event_bookings',$data, "event_id=$eventid");
                $updata['event_participants']=$event_participants;
                $DB->UpdateRecord('events',$updata, "event_id=$eventid");

                header(createURL('index.php', 'mod=event&do=eventdetails?eventid='.$eventid));
            }

        }
    }
}
 include_once(TEMPPATH . "/header.php"); ?>

<div style="height:95px"></div>
<?php
$event_info = $DB->SelectRecord('event_bookings', "event_id=".$eventid." and user_id='".$user_id."'");

$booking_check_number = count($event_info);
foreach($event_info as $resultuser){
    $resultuser= (array) $resultuser;
    $booking_status = $resultuser["booking_status"];
}
if ($booking_check_number > 0){
    ?>
    <form method="post" action="">
        <?php
            $event_info = $DB->SelectRecord('events', "event_id=".$eventid);
            foreach($event_info as $result){
        ?>
            <table width="600" border="0" align="center" cellpadding="5" cellspacing="0" class="tableorangeborder">
            <tr>
                <td height="40" colspan="2"><font class="ArialVeryDarkGreyBold20">Confirm your booking</font><br />
                    <span class="ArialVeryDarkGrey15"><br />
      Confirm your booking to attend this event: <br />
      <br />
      <table width="100%"><tr><td width="120">
      <img width="150" src="<?php echo ROOTURL;?>/<?=$result["event_photo"] ?>" /></td>
      <td width="10"></td><td valign="top">
      <?php
         $user_info = $DB->SelectRecord('public_users', "id=".$result["user_id"]);
      foreach($user_info as $resultuser){
          $resultuser =(array) $resultuser;
          if ($resultuser["profile_pic"] == ""){
              ?>
              <div style="display:inline-block; width:40px"><img width="35" height="35" style="border-radius:17.5px" src="<?php echo ROOTURL; ?>/images/no_profile_pic.gif" /></div>
          <?php }else{ ?>
              <div style="display:inline-block; width:40px"><img width="35" height="35" style="border-radius:17.5px" src="<?php  echo ROOTURL."/".$resultuser["profile_pic"]; ?>" /></div>
          <?php } ?>
    			<div style="display:inline-block"><font class="ArialVeryDarkGreyBold15"><? echo $resultuser["firstname"]." ".$resultuser["lastname"]; ?> </font>
          <?php
          $event_types = $DB->SelectRecord('event_types', "id=".$result["event_category"]);
             foreach($event_types as $resultcat ){
              $resultcat = (array) $resultcat;
              ?>
              <br />
              <font class="ArialVeryDarkGrey13" style="color:#666">is organising a <?=$resultcat["event_type"] ?> event</font></div>
          <?php } } ?>
                  <div style="height:10px"></div>
      <font class="ArialVeryDarkGreyBold18"><strong><?=$result["event_name"] ?></strong></font>
      <div style="height:5px"></div>
	  <font class="ArialVeryDarkGrey15"><?=date("j F Y", strtotime($result["start_date"])) ?>
      </font><font class="ArialVeryDarkGrey15"> | <?php echo date("g:ia", strtotime($result["start_time"]))." - ".date("g:ia", strtotime($result["end_time"])); ?></font></td></tr></table>
      </span>
                    <?php if (isset($_POST['submit'])){
                        if ($numofrows < 1){
                            echo '<br><font class="ArialVeryDarkGrey15" style="color:#963">The verification code is incorrect.</font>';
                        }
                    }
                    ?>
                </td>
            </tr>

            <?php if ($booking_status !== "Confirmed") { ?>
                <tr>
                    <td height="40" class="ArialOrange18"><span class="ArialVeryDarkGrey15">Please enter the verification code sent to your email: </span><span class="ArialVeryDarkGrey15">
      <input name="code" type="text" class="textboxbottomborder" id="code" size="10" placeholder="" />
&nbsp;&nbsp;&nbsp; </span><br /></td>
                    <td class="ArialOrange18"><table align="center">
                            <tr>
                                <td height="29"><input class="standardbutton" style="cursor:pointer" type="submit" id="submit" name="submit" value="Submit"></td>
                            </tr>
                        </table></td>
                </tr>

            <?php }else{ ?>
                <tr>
                    <td height="40" colspan="2" align="center"><font class="ArialVeryDarkGreyBold15">You have already joined this event.</font></td>
                </tr>
                </table></td>
                </tr>

            <?php } ?>

            </table>
        <?php } ?>
    </form>

<?php }else{ ?>
    <?
        $event_data = $DB->SelectRecord('events', "event_id=".$eventid);
        foreach($event_data as $result){
        $result = (array) $result;
        ?>
        <table width="500" border="0" align="center" cellpadding="5" cellspacing="0" class="tableorangeborder">
            <tr>
                <td height="40"><font class="ArialVeryDarkGreyBold18">You have not requested a booking for this event!</font><br /><br />
                    <table width="100%"><tr><td width="120">
                                <img width="150" src="http://www.locoyolo.com/<?=$result["event_photo"] ?>" /></td>
                            <td width="10"></td><td valign="top">
                                <?php
                                $user_data = $DB->SelectRecord('public_users', "id=".$result["user_id"]);
                                foreach($user_data as $resultuser){
                                    $resultuser = (array) $resultuser;
                                    if ($resultuser["profile_pic"] == ""){
                                        ?>
                                        <div style="display:inline-block; width:40px"><img width="35" height="35" style="border-radius:17.5px" src="images/no_profile_pic.gif" /></div>
                                    <?php }else{ ?>
                                        <div style="display:inline-block; width:40px"><img width="35" height="35" style="border-radius:17.5px" src="<?php echo ROOTURL."/".$resultuser["profile_pic"]; ?>" /></div>
                                    <?php } ?>
                                    <div style="display:inline-block"><font class="ArialVeryDarkGreyBold15"><?php echo $resultuser["firstname"]." ".$resultuser["lastname"]; ?> </font>
                                    <?php $types_data = $DB->SelectRecord('event_types', "id=".$result["event_category"]);

                                     foreach($types_data as $resultcat){$resultcat= (array) $resultcat; ?>
                                        <br />
                                        <font class="ArialVeryDarkGrey13" style="color:#666">is organising a <?=$resultcat["event_type"] ?> event</font></div>
                                    <?php } } ?>
                                <div style="height:10px"></div>
                                <font class="ArialVeryDarkGreyBold18"><strong><?=$result["event_name"] ?></strong></font>
                                <div style="height:5px"></div>
                                <font class="ArialVeryDarkGrey15"><?=date("j F Y", strtotime($result["start_date"])) ?></font><font class="ArialVeryDarkGrey15"> | <?php echo date("g:ia", strtotime($result["start_time"]))." - ".date("g:ia", strtotime($result["end_time"])); ?></font></td></tr></table>
                    </span>
                </td>
            </tr>
            <tr>
                <td height="40" class="ArialOrange18"><table align="center">
                        <tr>
                            <td height="29"><input onclick="location.href='http://www.locoyolo.com/eventdetails.php?eventid=<?=$eventid ?>';" class="standardbutton" style="cursor:pointer" type="submit" id="submit" name="submit" value="Event details"></td>
                        </tr>
                    </table></td>
            </tr>
        </table>
        <?
    } }
?>

