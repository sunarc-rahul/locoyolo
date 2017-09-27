<?php
defined("ACCESS") or die("Access Restricted");
include_once('class.ping.php');

$UserOBJ = new Ping();
global $DB;

$ping_id = $_GET['pingid'];
if(isset($frmdata['ping_submit'])) {
    $result = $UserOBJ->createPing();
}

if(isset($frmdata['edit_ping_submit'])) {
    $result = $UserOBJ->editPing($ping_id);
}

if(!isset($_SESSION['user_id']) || ($_SESSION['user_id'] == ''))
{
    Redirect(CreateURL('index.php'));
}

if( $result['error']){
    $error =$result['error'];
}


$do = '';
if(isset($getVars['do']))
    $do=$getVars['do'];
switch($do)
{

    default:
    case 'createPing':
        checker();
        $pageTitle = 'Organise Ping';
        $CFG->template="ping/addping.php";
        break;

case 'editping':
     checker();
     global $userData;
    $event_id = $_GET['pingid'];
    $userEventData = $DB->SelectRecord('events', "user_id='" . $userData->id . "' AND  event_id='" . $event_id . "'");
    $PingUserId = $userEventData->user_id;
    $PingName = $userEventData->event_name;
    $pageTitle = 'Edit - '."$PingName";

        if(!isset($_SESSION['user_id']) || ($_SESSION['user_id'] == '')|| ($_SESSION['user_id'] != $PingUserId)|| $userEventData==0)
        {
            Redirect(CreateURL('index.php'));
        }

    if($userEventData->entry_type!='Ping')
    {
        $mod = 'event';
        $do = 'editevent';
        //   goto forexactmod;
        Redirect(CreateURL('index.php',"mod=event&do=editevent&eventid=".$event_id));
    }

        $data = $userEventData->event_objectives;
        $objectives = explode(";", $data);
        $userLocationData = $DB->SelectRecord('ping_locations', "event_id='" . $event_id . "'");
        $CFG->template="ping/pingedit.php";
        break;

 case 'pings':
     $pageTitle = 'See All Pings';
        $CFG->template="ping/pings.php";
        break;

    case 'pingdetails':
        checker();

        global $userData;
        if(!isset($_SESSION['user_id']) || ($_SESSION['user_id'] == ''))
        {
            Redirect(CreateURL('index.php'));
        }

        $event_id = $_GET['eventid'];
        $userEventData = $DB->SelectRecord('events',  " event_id='" . $event_id . "'");
        if($userEventData->entry_type!='Ping')
        {
            $mod = 'event';
            $do = 'eventdetails';
         //   goto forexactmod;
            Redirect(CreateURL('index.php',"mod=event&do=eventdetails&eventid=".$event_id));
        }
        $pingName = $userEventData->event_name;

        $CFG->template="ping/pingdetails.php";
        $pageTitle = $pingName;
        break;
}

include(TEMP."/index.php");
//exit;
?>

<?php
/*if(isset($_GET['close']) && ($_GET['close'] == 1))
{
	unset($_SESSION['lastTestId']);
	echo '<script>window.close();</script>';
}

if(!isset($_SESSION['lastTestId']) || ($_SESSION['lastTestId'] == ''))
{
	Redirect(CreateURL('index.php',"mod=guidelines"));
	exit;
}

if(isset($frmdata['forexam']))
{
	if(($frmdata['forexam'] != '') || ($frmdata['forsoftware'] != ''))
	{
		global $DB;

		$feed = array();
		$feed['candidate_id'] = $_SESSION['candidate_id'];
		$feed['test_id'] = $_SESSION['lastTestId'];
		$feed['feed_exam'] = $frmdata['forexam'];
		$feed['feed_software'] = $frmdata['forsoftware'];

		$DB->InsertRecord('candidate', $feed);

		unset($_SESSION['lastTestId']);
		$_SESSION['feed_done'] = true;
	}
}*/

?>