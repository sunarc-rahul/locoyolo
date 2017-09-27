<?php
//echo 'hi m classs';exit;
/*****************Developed by :-  Rahul Gahlot
 * Date         :- 3-july-2011
 * Module       :- Candidatet master
 * Purpose      :- Class for function to add and edit candidate details
 ***********************************************************************************/
class Event
{
    function addEvent()
    {
        global $DB, $frmdata, $userData;
      
//        $errormessage='';
        if (isset($frmdata['event_name']) && ($frmdata['event_name'] != '')) {
            $email = trim($frmdata['event_name']);
        } else {
            $errormessage['error']['event_name'] = "Please enter event name.<br>";
        }
        if ($frmdata["event_location"] == ""){
            $errormessage['error']['event_location'] = "Please enter a valid nearest or exact location.";
        }
        if ($frmdata["event_price"] == ""){
            $errormessage['error']['event_price'] = "Please enter price.";
        }
        if ($frmdata["daterange"] == ""){
            $errormessage['error']['daterange'] = "Please enter event date.";
        }
        if ($frmdata["event_participants_max"] < 1){
            $errormessage['error']['event_participants_max'] = "Please specify the maximum number of participants. It must be more than zero";
        }

/*
    ToDO:- Remove Achievement statement Section
    Developer: Nitin Soni
    Date:- 05Aug/2017

*/

/*        if ($frmdata["achievement_statement"] == ""){
            $errormessage['error']['achievement_statement'] = "Please provide an achievement statement.";
        }*/

        $characters = 'ab0cd1ef2gh3ij4kl5mn6op7qr8st9uvwxyz';
        $string = '';
        $requestcode = '';
        $max = strlen($characters) - 1;
        for ($i = 0; $i < 8; $i++) {
            $string .= $characters[mt_rand(0, $max)];
        }
        for ($i = 0; $i < 8; $i++) {
            $requestcode .= $characters[mt_rand(0, $max)];
        }

        $event_code='';
        for ($i = 0; $i < 15; $i++) {
            $event_code .= $characters[mt_rand(0, $max)];
        }

        $objectivelist = "";

        $numberofobjectives = $_POST['objective_number'];
        if ($numberofobjectives < 1){
            $numberofobjectives = $_POST['objective_number_mobile'];
        }
        $i=1;
        $a = 0;
        while ($i < ($numberofobjectives+1)){
            if ($_POST["event_objective".$i] !== ""){
                $objectivelist .= $_POST["event_objective".$i].";";
                $a++;
            }
            $i++;
        }

        if ($a < 1){
            $errormessage['error']['event_objective'] = "Please specify at least one event objective.<br>";
        }

        $objectivelist = substr($objectivelist,0,-1);
        $characters = 'ab0cd1ef2gh3ij4kl5mn6op7qr8st9uvwxyz';
        $string = '';
        $requestcode = '';
        $max = strlen($characters) - 1;
        for ($i = 0; $i < 8; $i++) {
            $string .= $characters[mt_rand(0, $max)];
        }
        for ($i = 0; $i < 8; $i++) {
            $requestcode .= $characters[mt_rand(0, $max)];
        }

        $event_code = '';
        for ($i = 0; $i < 15; $i++) {
            $event_code .= $characters[mt_rand(0, $max)];
        }
        $frmdata['event_objectives'] = $objectivelist;
        $frmdata['event_entry_date'] = date("Y-m-d H:i:s");
        $frmdata['user_id'] = $userData->id;
        $frmdata['event_code'] = $event_code;
        $frmdata['request_code'] = $requestcode;
        $frmdata['event_status'] = "Live";

         $time= explode('-', $_POST["daterange"]); ;
        $start_time = date('Y-m-d H:i:s', strtotime("" . $time[0] . ""));
        $end_time   = date('Y-m-d H:i:s', strtotime("" . $time[1] . ""));

        $frmdata['start_date']= $start_time;
        $frmdata['end_date']= $end_time;

        if (($_FILES['event_photo']['name'])!= '') {
            if (!$_FILES['event_photo']['error']) {

                //now is the time to modify the future file name and validate the file
                $temp = explode(".", $_FILES["event_photo"]["name"]);
                $new_file_name = round(microtime(true)) . date("YmdHis") . '.' . end($temp);
                if ($_FILES['event_photo']['size'] > 1024000) //can't be larger than 1 MB
                {
                    $valid_file = false;
                    $message = 'Oops!  Your file\'s size is to large.';
                }


                $validExtensions = array('.jpg', '.jpeg','.JPEG','.JPG','.PNG', '.gif', '.png','.GIF');
                // get extension of the uploaded file
                $fileExtension = strrchr($_FILES['event_photo']['name'], ".");
                // check if file Extension is on the list of allowed ones
                if (in_array($fileExtension, $validExtensions)) {
                    $manipulator = new ImageManipulator($_FILES['event_photo']['tmp_name']);
                    $manipulator->save('images/event_images/' . $new_file_name);
                    $newImage = $manipulator->resample(400, 400);
                    $width = $manipulator->getWidth();
                    $height = $manipulator->getHeight();
                    $centreX = round($width / 2);
                    $centreY = round($height / 2);
                    // our dimensions will be 200x200
                    $x1 = $centreX - 150; // 200 / 2
                    $y1 = $centreY - 110; // 200 / 2
                    $x2 = $centreX + 150; // 200 / 2
                    $y2 = $centreY + 110; // 200 / 2
                    // center cropping
                    $newImage = $manipulator->crop($x1, $y1, $x2, $y2);
                    // saving file to uploads folder
                    $manipulator->save('images/event_images/' . $new_file_name);
                    $photofilename = 'images/event_images/' . $new_file_name;
                    $photofilename = 'images/event_images/' . $new_file_name;
                } else {
                    $errormessage['error']['event_photo'] = "The event photo failed to upload. Please upload a .jpg or .gif file<br>";
                }
            } //if there is an error...
            else {
                //set that to be the returned message
                $errormessage['error']['event_photo'] = 'The event photo failed to upload. Please check the file you are uploading.';
            }
        } else {
            //set that to be the returned message

            $errormessage['error']['event_photo'] = 'Please upload an event photo.';
        }

            $frmdata['event_photo'] = $photofilename;
        $frmdataInsert = str_replace("'", '&#39;', $frmdata);
        if (count($errormessage) == 0) {

            $insertDataIntoEvent = $DB->InsertRecord('events', $frmdataInsert);
             $selectUser = $DB->SelectRecord('events', "user_id='" . $userData->id . "' AND event_code='" . $frmdata['event_code'] . "'");

            $eventLoactionData['event_lat'] = $frmdata['event_lat'];
            $eventLoactionData['event_location_description'] = $frmdata['event_location_description'];
            $eventLoactionData['travel_directions'] = $frmdata['travel_directions'];
            $eventLoactionData['event_location'] = $frmdata['event_location'];
            $eventLoactionData['event_long'] = $frmdata['event_long'];
            $eventLoactionData['event_id'] = $selectUser->event_id;
            $frmdataInsertLocation = str_replace("'", '&#39;', $eventLoactionData);

            $insertDataIntoEventLocationTable = $DB->InsertRecord('event_locations', $frmdataInsertLocation);
///////start////
///
             $eventImage = ROOTURL.'/'.$photofilename;
            $startdate = date("j F Y", strtotime($frmdata['event_entry_date']));
            $starttime = date("g:ia", strtotime($start_time));
            $endtime = date("g:ia", strtotime($end_time));

            $message = "<p>You have just created a new event!</p>
		<table width='340' style='border:#FFCC66 1px solid;padding:5px;border-radius:3px;'><tr><td width='120'>

		<img width='120' src=".$eventImage." />
		
		
		</td><td width='10'></td><td><strong>".$frmdata['event_name']."</strong><div style='height:5px'></div>".$startdate." | ".$starttime." - ".$endtime."</td></tr></table>" ;

            $emailmessage='<html>
			<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			</head>
			<body>
			
			<div>
					<p>Hello '.$_SESSION['user_name'].'!</p>
					'.$message.'
					<p><strong>LocoYolo Team</strong></p>
			
			</div>
			</body>
			</html>';

            $to      = $_SESSION["user_email"];
            $subject = 'You have just created a new event!';

            include_once(ROOTPATH.'/lib/mailer.php');
            $mail = new mailer();
            $mail->addTo($to);
//            $mail->setFrom($emailfrom, ucwords($fromuser->firstname.' '.$fromuser->lastname));
            $mail->setSubject($subject);
            $mail->setMessage($emailmessage);
            $mail->send();

/// end////


           $_SESSION['success'] = 'The Event has been cerated.';
            Redirect( CreateURL('index.php',"mod=event&do=eventdetails&eventid=".$selectUser->event_id));
        }else{

            return $errormessage;

        }


    }

    function editEvent()
    {

        global $DB, $frmdata, $userData,$id;

        $event_id = $_GET['eventid'];


        if (isset($frmdata['event_name']) && ($frmdata['event_name'] != '')) {
            $email = trim($frmdata['event_name']);
        } else {
            $errormessage['error']['event_name'] = "Please enter event name.<br>";
        }



        if ($frmdata["end_time"] < $frmdata["start_time"]){
            $errormessage['error']['end_time']= "Please enter a valid end time that is atleast 30 minutes after the start time.";
        }
        if ($frmdata["event_location"] == ""){
            $errormessage['error']['event_location'] = "Please enter a valid nearest or exact location.";
        }
        if ($frmdata["event_participants_max"] < 1){
            $errormessage['error']['event_participants_max'] = "Please specify the maximum number of participants. It must be more than zero";
        }

 /*
    ToDO:- Remove Achievement statement Section
    Developer: Nitin Soni
    Date:- 05Aug/2017

*/

        $characters = 'ab0cd1ef2gh3ij4kl5mn6op7qr8st9uvwxyz';
        $string = '';
        $requestcode = '';
        $max = strlen($characters) - 1;
        for ($i = 0; $i < 8; $i++) {
            $string .= $characters[mt_rand(0, $max)];
        }
        for ($i = 0; $i < 8; $i++) {
            $requestcode .= $characters[mt_rand(0, $max)];
        }

        $event_code='';
        for ($i = 0; $i < 15; $i++) {
            $event_code .= $characters[mt_rand(0, $max)];
        }

        $objectivelist = "";

        $numberofobjectives = $_POST['objective_number'];
        if ($numberofobjectives < 1){
            $numberofobjectives = $_POST['objective_number_mobile'];
        }
        $i=1;
        $a = 0;
        while ($i < ($numberofobjectives+1)){
            if ($_POST["event_objective".$i] !== ""){
                $objectivelist .= $_POST["event_objective".$i].";";
                $a++;
            }
            $i++;
        }

        if ($a < 1){
            $errormessage['error']['event_objective'] = "Please specify at least one event objective.<br>";
        }

        $objectivelist = substr($objectivelist,0,-1);
        $characters = 'ab0cd1ef2gh3ij4kl5mn6op7qr8st9uvwxyz';
        $string = '';
        $requestcode = '';
        $max = strlen($characters) - 1;
        for ($i = 0; $i < 8; $i++) {
            $string .= $characters[mt_rand(0, $max)];
        }
        for ($i = 0; $i < 8; $i++) {
            $requestcode .= $characters[mt_rand(0, $max)];
        }

        $event_code = '';
        for ($i = 0; $i < 15; $i++) {
            $event_code .= $characters[mt_rand(0, $max)];
        }
        $event_location = $frmdata['event_location'];
        $frmdata['event_objectives'] = $objectivelist;
        $frmdata['event_entry_date'] = date("Y-m-d H:i:s");
        $frmdata['user_id'] = $userData->id;
        $frmdata['event_code'] = $event_code;
        $frmdata['request_code'] = $requestcode;
        $frmdata['event_status'] = "Live";

        $time= explode('-', $_POST["daterange"]); ;
        $start_time = date('Y-m-d H:i:s', strtotime("" . $time[0] . ""));
        $end_time   = date('Y-m-d H:i:s', strtotime("" . $time[1] . ""));

        $frmdata['start_date']= $start_time;
        $frmdata['end_date']= $end_time;

        if ($_FILES['event_photo']['name'] != null) {

            //if no errors...
            if (!$_FILES['event_photo']['error']) {

                //now is the time to modify the future file name and validate the file
                $temp = explode(".", $_FILES["event_photo"]["name"]);
                $new_file_name = round(microtime(true)) . date("YmdHis") . '.' . end($temp);
                if ($_FILES['event_photo']['size'] > 1024000) //can't be larger than 1 MB
                {
                    $valid_file = false;
                    $message = 'Oops!  Your file\'s size is to large.';
                }


                $validExtensions = array('.jpg', '.jpeg','.JPEG','.JPG','.PNG', '.gif', '.png','.GIF');
                // get extension of the uploaded file
                $fileExtension = strrchr($_FILES['event_photo']['name'], ".");
                // check if file Extension is on the list of allowed ones
                if (in_array($fileExtension, $validExtensions)) {
                    $manipulator = new ImageManipulator($_FILES['event_photo']['tmp_name']);
                    $manipulator->save('images/event_images/' . $new_file_name);
                    $newImage = $manipulator->resample(400, 400);
                    $width = $manipulator->getWidth();
                    $height = $manipulator->getHeight();
                    $centreX = round($width / 2);
                    $centreY = round($height / 2);
                    // our dimensions will be 200x200
                    $x1 = $centreX - 150; // 200 / 2
                    $y1 = $centreY - 110; // 200 / 2
                    $x2 = $centreX + 150; // 200 / 2
                    $y2 = $centreY + 110; // 200 / 2
                    // center cropping
                    $newImage = $manipulator->crop($x1, $y1, $x2, $y2);
                    // saving file to uploads folder
                    $manipulator->save('images/event_images/' . $new_file_name);
                    $photofilename = 'images/event_images/' . $new_file_name;
                    $photofilename = 'images/event_images/' . $new_file_name;
                    $frmdata['event_photo'] = $photofilename;
                } else {


                    $errormessage['error']['event_photo'] = "The event photo failed to upload. Please upload a .jpg or .gif file<br>";
                }
            } //if there is an error...
            else {

                //set that to be the returned message
                $errormessage['error']['event_photo'] = 'The event photo failed to upload. Please check the file you are uploading.';
            }
        } else {

            $userEventData = $DB->SelectRecord('events', "user_id='" . $userData->id . "' AND  event_id='" . $event_id . "'");


            $frmdata['event_photo'] = $userEventData->event_photo;

        }

        if (count($errormessage) == 0) {

            //======================IF EVENT LOCATION HAS BEEN CHANGED=======================//
            if ($event_location !== ""){

                $DB->UpdateRecord('events', $frmdata, 'event_id="' . $event_id . '"');
//                exit;

            }
            //======================IF EVENT LOCATION HAS NOT BEEN CHANGED=======================//
            if ($event_location == ""){


                $DB->UpdateRecord('events', $frmdata, 'event_id="' . $event_id . '"');
            }
            //======================IF EVENT LOCATION HAS NOT BEEN CHANGED=======================//
            if ($event_location == ""){
                $selectUser = $DB->SelectRecord('events', "user_id='" . $userData->id . "' AND event_code='" . $frmdata['event_code'] . "'");

                $eventLoactionData['event_lat'] = $frmdata['event_lat'];
                $eventLoactionData['event_location_description'] = $frmdata['event_location_description'];
                $eventLoactionData['travel_directions'] = $frmdata['travel_directions'];
                $eventLoactionData['event_location'] = $frmdata['event_location'];
                $eventLoactionData['event_long'] = $frmdata['event_long'];
                $eventLoactionData['event_id'] = $selectUser->event_id;
                $DB->UpdateRecord('event_locations', $eventLoactionData, 'event_id="' . $event_id . '"');


//========IF EVENT LOCATION HAS NOT BEEN CHANGED, REDIRECT TO EVENT DETAILS PAGE=======================//
            }else{

                $selectUser = $DB->SelectRecord('events', "user_id='" . $userData->id . "' AND event_code='" . $frmdata['event_code'] . "'");


                $eventLoactionData['event_lat'] = $frmdata['event_lat'];
                $eventLoactionData['event_location_description'] = $frmdata['event_location_description'];
                $eventLoactionData['travel_directions'] = $frmdata['travel_directions'];
                $eventLoactionData['event_location'] = $frmdata['event_location'];
                $eventLoactionData['event_long'] = $frmdata['event_long'];
                $eventLoactionData['event_id'] = $selectUser->event_id;

                $DB->UpdateRecord('event_locations', $eventLoactionData, 'event_id="' . $event_id . '"');

            }

            $selectUser = $DB->SelectRecord('events', "user_id='" . $userData->id . "' AND event_code='" . $frmdata['event_code'] . "'");

            $eventLoactionData['event_lat'] = $frmdata['event_lat'];
            $eventLoactionData['event_location_description'] = $frmdata['event_location_description'];
            $eventLoactionData['travel_directions'] = $frmdata['travel_directions'];
            $event_location = $frmdata['event_location'];
            $eventLoactionData['event_long'] = $frmdata['event_long'];
            $eventLoactionData['event_id'] = $selectUser->event_id;
            $_SESSION['success'] = 'The Event has been updated.';

           $notification_date = date('m-d-Y');
           $selectrelatedUser = $DB->SelectRecords('event_bookings', "event_id='".$event_id."'");

           foreach($selectrelatedUser as $res)
           {
            $ndata['other_user_id'] = $res->user_id;
            $ndata['user_id'] = $userData->id;
            $ndata['event_id'] = $event_id;
            $ndata['notification_type'] = 'event_updated';
            $ndata['status'] = 'Pending';
            $ndata['notification_date'] = $notification_date;
               $insertDataIntoEvent = $DB->InsertRecord('notifications', $ndata);
           }

            Redirect( CreateURL('index.php',"mod=event&do=eventdetails&eventid=".$event_id));

        }else{

            return $errormessage;

        }


    }

    function showEventData()
    {
         global $userData,$DB,$frmdata;
        $selectUser = $DB->SelectRecord('events', "user_id='" . $userData->id . "' AND event_code='" . $frmdata['event_code'] . "'");
        return $selectUser;

    }

}

?>