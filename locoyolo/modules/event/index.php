<?php
defined("ACCESS") or die("Access Restricted");
include_once('class.event.php');
include_once('image_manipulator.php');
//include_once('send_comments.php');
global $DB;
$DB = new DBFilter();
$eventOBJ= new Event();

if(isset($frmdata['event_submit'])) {
    $result = $eventOBJ->addEvent();
}
if(!isset($_SESSION['user_id']) || ($_SESSION['user_id'] == ''))
{
    Redirect(CreateURL('index.php'));
}


if(isset($frmdata['edit_event_submit'])) {
    $result = $eventOBJ->editEvent();
}

if( $result['error']){
    $error =$result['error'];
}
if(isset($_GET['id']))
{
    $nameID=$_GET['id'];
}
$event_id = $_GET['eventid'];

$do = '';
if(isset($getVars['do']))
    $do=$getVars['do'];
switch($do)
{
    default:
    case 'event':
        checker();
        $pageTitle = 'Organise Event';
        $CFG->template="event/event.php";

        break;
    case 'events':
        checker();
        $pageTitle = 'See All Events';
        $CFG->template="event/events.php";
       
        break;
    case 'participants':
        checker();
        global $userData;
        $pageTitle = 'Participants';
        $event_id = $_GET['eventid'];
        $userEventData = $DB->SelectRecord('events', " event_id='" . $event_id . "'");
        $userId= (array)$userEventData;
        $loggedInId =(array)$userData;
        $friendsql = "select concat(firstname,' ',lastname) as name, u.id from public_users u left join buddies b on b.buddy_id=u.id where b.user_id=".$userData->id;
    $userfriends = $DB->RunSelectQuery($friendsql);

  if($loggedInId['id']!=$userId['user_id'])
  {
      Redirect(CreateURL('index.php'));
  }
        $CFG->template="event/participants.php";
        break;

    case 'eventdetails':
        checker();

        global $userData;

        $userEvent_id = $_GET['eventid'];
        $userEventData = $DB->SelectRecord('events',"event_id='" . $event_id . "'");
       if($userEventData->entry_type=='Ping')
        {
            $mod = 'ping';
            $do = 'pingdetails';

            Redirect(CreateURL('index.php',"mod=ping&do=pingdetails&eventid=".$event_id));
        }
        $eventName = $userEventData->event_name;
        $pageTitle = $eventName;
        $CFG->template="event/eventdetails.php";

        break;

    case 'editevent':
        checker();

        global $userData;
        $userEventData = $DB->SelectRecord('events', "user_id='" . $userData->id . "' AND  event_id='" . $event_id . "'");

          $EventuserId = $userEventData->user_id;
          $EventName = $userEventData->event_name;
            $pageTitle = 'Edit - '."$EventName";

        if(!isset($_SESSION['user_id']) || ($_SESSION['user_id'] == '')||($_SESSION['user_id'] != $EventuserId)|| $userEventData==0)
        {
            Redirect(CreateURL('index.php'));
        }

        if($userEventData->entry_type=='Ping')
        {
            $mod = 'ping';
            $do = 'editping';
            
            Redirect(CreateURL('index.php',"mod=ping&do=editping&eventid=".$event_id));
        }

             $data = $userEventData->event_objectives;
        $objectives = explode(";", $data);
        $userLocationData = $DB->SelectRecord('event_locations', "event_id='" . $event_id . "'");
        $CFG->template="event/event.php";

        break;


}

include(TEMP."/index.php");
//exit;
?>