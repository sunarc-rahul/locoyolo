<?php



$post = (!empty($_POST)) ? true : false;

if($post)
{

    $errorcheck = false;

    $event_id = $_POST['event_id'];
    $user_id = $_POST["user_id"];
    $start_date = $_POST["start_date"];
    $booking_status = $_POST["book_status"];
    $booking_date = date("Y-m-d");
    $dataToInsaert['event_id']=$event_id;
    $dataToInsaert['user_id']=$user_id;
    $dataToInsaert['start_date']=$start_date;
    if ($errorcheck == false){
        $dataToInsaert['booking_date']=date("Y-m-d");
        if ($booking_status == "N") {
            $dataToInsaert['booking_status']="Confirmed";

            $sql =$DB->InsertRecord('event_bookings',$dataToInsaert);

            if ($_POST["view_type"] == "mobile"){ ?>
                <font class="ArialVeryDarkGreyBold15">You have joined this meetup.</font>&nbsp;&nbsp;&nbsp;<input class="standardgreybutton" style="cursor:pointer" type="submit" name="bookbtn_mobile" id="bookbtn_mobile" value="Leave meetup" />
                <input type="hidden" name="book_status_mobile" id="book_status_mobile" value="Y" />
            <? } else { ?>
                <font class="ArialVeryDarkGreyBold15">You have joined this meetup.</font>&nbsp;&nbsp;&nbsp;<input class="standardgreybutton" style="cursor:pointer" type="submit" name="bookbtn" id="bookbtn" value="Leave meetup" />
                <input type="hidden" name="book_status" id="book_status" value="Y" />
            <? }

        }else if ($booking_status == "Y") {
            $deleteComment = $DB->DeleteRecord('event_bookings', 'user_id="' . $user_id . '" and event_id="'.$event_id.'"');
//
            ?>

            <? if ($_POST["view_type"] == "mobile"){ ?>
                <input class="standardbutton" style="cursor:pointer" type="submit" name="bookbtn_mobile" id="bookbtn_mobile" value="Join meetup" />
                <input type="hidden" name="book_status_mobile" id="book_status_mobile" value="N" />
            <? } else { ?>
                <input class="standardbutton" style="cursor:pointer" type="submit" name="bookbtn" id="bookbtn" value="Join meetup" />
                <input type="hidden" name="book_status" id="book_status" value="N" />
                <?
            }

        }

    }
}
// Check name
?>