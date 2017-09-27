<?php

/**
 * @author :  Akshay Yadav
 */
class Ping
{
    /* Create a new ping */
    function createPing($userData)
    {


        global $DB, $frmdata, $userData;
        $characters = 'ab0cd1ef2gh3ij4kl5mn6op7qr8st9uvwxyz';
        $string = '';
        $requestcode = '';
        $max = strlen($characters) - 1;
        $event_code = '';
        for ($i = 0; $i < 15; $i++) {
            $event_code .= $characters[mt_rand(0, $max)];
        }


        $date=$frmdata['timerange'];
        $duratuion= $frmdata['duratuion']?$frmdata['duratuion']:1 ;
        $start_date = date('Y-m-d h:i:s', strtotime($date));
        $end_date = date('Y-m-d h:i:s', strtotime($start_date."+ $duratuion hours"));
        $frmdata['event_code'] = $event_code;
        $frmdata['user_id'] = $userData->id;
        $frmdata['start_date'] = $start_date;
        $frmdata['end_date'] = $end_date;
        $frmdata['entry_type'] = "Ping";
        $frmdata['event_status'] = "L";
        $frmdata['event_entry_date'] = date("Y-m-d H:i:s");
        $event_start_time = $frmdata['start_time'];
        $errormessage = array();
        if (isset($frmdata['event_name']) && ($frmdata['event_name'] != '')) {
             trim($frmdata['event_name']);
        } else {
            $errormessage['error']['event_name'] = "Please enter event name.<br>";
        }
        if (isset($frmdata['event_description']) && ($frmdata['event_description'] != '')) {
            trim($frmdata['event_description']);
        } else {
            $errormessage['error']['event_description'] = "Please enter event description.<br>";
        }

        if (isset($frmdata['event_location']) && ($frmdata['event_location'] != '')) {
             trim($frmdata['event_location']);
        } else {

            $errormessage['error']['event_location'] = "Please enter event location.<br>";
        }

        if (isset($frmdata['timerange']) && ($frmdata['timerange'] != '')) {
            trim($frmdata['timerange']);
        } else {

            $errormessage['error']['timerange'] = "Please select Date-Time.<br>";
        }

        if (isset($frmdata['event_participants_max']) && ($frmdata['event_participants_max'] != '')) {
            trim($frmdata['event_participants_max']);
        } else {

            $errormessage['error']['event_participants_max'] = "Please enter max participants.<br>";
        }

        if (count($errormessage) == 0) {
            $frmdataInsert = str_replace("'", '&#39;', $frmdata);
            $id = $DB->InsertRecord('events', $frmdataInsert);

            $selectUser = $DB->SelectRecord('events', "user_id='" . $userData->id . "' AND event_code='" . $frmdata['event_code'] . "'");


            $pingLocationData['event_lat'] = $frmdata['event_lat'];
            $pingLocationData['event_location'] = $frmdata['event_location'];
            $pingLocationData['event_long'] = $frmdata['event_long'];
            $pingLocationData['event_id'] = $selectUser->event_id;
//
            $insertDataIntoPingLocationTable = $DB->InsertRecord('ping_locations', $pingLocationData);


                $str = $frmdata["start_date"];
                $strEnd = $frmdata["end_date"];

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
                    }}


                $stmt = "Select * from events where event_code=$event_code order by event_id desc limit 1";

              foreach ($stmt as $data) {
                  $result = (array)$data;
                    $eventid = $result["event_id"];
                  $event_name = $result["event_name"];
                }



            $PingImage = ROOTURL.'/'."images/ping.jpg";

            $message = "<p>You have just created a new Ping!</p>

		<table width='340' style='border:#FFCC66 1px solid;padding:5px;border-radius:3px;'><tr><td width='120'>

		<img width='120' src=".$PingImage." />
		
		
		</td><td width='10'></td><td><strong>".$frmdata['event_name']."</strong><div style='height:5px'></div>".$startDate."  </td></tr></table>" ;


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
            $subject = 'You have just created a new ping!';

            include_once(ROOTPATH.'/lib/mailer.php');
            $mail = new mailer();
            $mail->addTo($to);
            $mail->setSubject($subject);
            $mail->setMessage($emailmessage);
            $mail->send();
//                header(ROOTURL."/pingdetails.php?eventid=" . $selectUser->event_id);

            $_SESSION['PingSuccess'] = 'The ping has been created.';

            Redirect( CreateURL('index.php',"mod=ping&do=pingdetails&eventid=".$selectUser->event_id));

        } else {

            return  $errormessage;
        }

    }

    /* Edit ping */

    function editPing($ping_id){

        global $DB, $frmdata, $userData;

        $characters = 'ab0cd1ef2gh3ij4kl5mn6op7qr8st9uvwxyz';
        $string = '';
        $requestcode = '';
        $max = strlen($characters) - 1;
        $event_code = '';
        for ($i = 0; $i < 15; $i++) {
            $event_code .= $characters[mt_rand(0, $max)];
        }
        $date=$frmdata['timerange'];
        $duratuion= $frmdata['duratuion']?$frmdata['duratuion']:1 ;
        $start_date = date('Y-m-d h:i:s', strtotime($date));
        $end_date = date('Y-m-d h:i:s', strtotime($start_date."+ $duratuion hours"));
        $frmdata['event_code'] = $event_code;
        $frmdata['user_id'] = $userData->id;
        $frmdata['start_date'] = $start_date;
        $frmdata['end_date'] = $end_date;
        $frmdata['entry_type'] = "Ping";
        $frmdata['event_status'] = "L";
        $frmdata['event_entry_date'] = date("Y-m-d H:i:s");
        $event_start_time = $frmdata['start_time'];
        $errormessage = array();
        if (isset($frmdata['event_name']) && ($frmdata['event_name'] != '')) {
            $email = trim($frmdata['event_name']);
        } else {
            $errormessage['error']['event_name'] = "Please enter event name.<br>";
        }

        if (isset($frmdata['event_location']) && ($frmdata['event_location'] != '')) {
            $email = trim($frmdata['event_location']);
        } else {

            $errormessage['error']['event_location'] = "Please enter event location.<br>";
        }
        if (count($errormessage) == 0) {
            $userFrmdata = str_replace("'", '&#39;', $frmdata);
            $DB->UpdateRecord('events',$userFrmdata,'event_id="'.$ping_id.'"');

//            $id = $DB->InsertRecord('events', $frmdata);

            $selectUser = $DB->SelectRecord('events', "user_id='" . $userData->id . "' AND event_code='" . $frmdata['event_code'] . "'");


            $pingLocationData['event_lat'] = $frmdata['event_lat'];
            $pingLocationData['event_location'] = $frmdata['event_location'];
            $pingLocationData['event_long'] = $frmdata['event_long'];
            $pingLocationData['event_id'] = $selectUser->event_id;
//
//            $insertDataIntoPingLocationTable = $DB->InsertRecord('ping_locations', $pingLocationData);

            $insertDataIntoPingLocationTable = $DB->UpdateRecord('ping_locations',$pingLocationData,'event_id="'.$ping_id.'"');

            $notification_date = date('m-d-Y');
            $selectrelatedUser = $DB->SelectRecords('event_bookings', "event_id='".$ping_id."'");
            /*echo "<pre>";print_r($selectrelatedUser);
            exit;*/
            foreach($selectrelatedUser as $res)
            {
                $ndata['other_user_id'] = $res->user_id;
                $ndata['event_id'] = $ping_id;
                $ndata['notification_type'] = 'ping_updated';
                $ndata['status'] = 'Pending';
                $ndata['notification_date'] = $notification_date;
                $insertDataIntoEvent = $DB->InsertRecord('notifications', $ndata);
            }

            $stmt = "Select * from events where event_code=$event_code order by event_id desc limit 1";

            foreach ($stmt as $data) {
                $result = (array)$data;
                $eventid = $result["event_id"];
                $event_name = $result["event_name"];
            }

            $startdate = date("j F Y", strtotime($event_entry_date));
            $starttime = date("g:ia", strtotime($event_start_time));
            $endtime = date("g:ia", strtotime($event_end_time));

            $message = "<p>You have just pinged a meetup!</p>
		<table width=\"340\" style=\"border:#FFCC66 1px solid;padding:5px;border-radius:3px;\"><tr><td width=\"120\"><img width=\"120\" src=\"http://www.locoyolo.com/" . $photofilename . "\" /></td><td width=\"10\"></td><td>" . $frmdata['event_name'] . "<div style=\"height:5px\"></div><strong>" . $startdate . "</strong> | " . $starttime . "</td></tr></table>";
            $emailmessage = '<html>
			<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			</head>
			<body>
			<div>
					<p>Hello ' . $_SESSION["user_email"] . '!</p>
					<p>' . $message . '</p>
					<p><strong>LocoYolo Team</strong></p>

			</div>
			</body>
			</html>';
            $to = $_SESSION["login_user_email"];
            $subject = 'New Event: ' . $event_name;
            $headers = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $headers .= 'From: mail@locoyolo.com\r\n';
//                mail($to, $subject, $emailmessage, $headers);
            header(ROOTURL."/pingdetails.php?eventid=" . $selectUser->event_id);

//            $messagegood = "The event has been added";
            $_SESSION['success'] = 'The ping has been updated.';

        } else {

            return  $errormessage;
        }

    }

    function showPingDetail($userEvent_id)
    {
        global $DB,  $userData;
        return $userEvent_id;

    }
    function bookingStatus($event_id)
    {
        global $DB, $userData;
        $result = $DB->SelectRecord('event_bookings', "event_id='" . $event_id . "' AND  user_id='"         . $userData->id . "'");
        return $result;
    }
    function peopleJoined($event_id)
    {
        global $DB, $userData;
        $user_id = $userData->id;
        $status = "Confirmed";
        $query = "Select p.profile_pic,concat(p.firstname,' ',p.lastname) as name ,b.* from event_bookings b left join public_users as p on p.id= b.user_id where event_id =$event_id and booking_status like '$status'";

        $res_data = $DB->RunSelectQuery($query);
        $total = count((array)$res_data);

        return $total;

    }
}

?>