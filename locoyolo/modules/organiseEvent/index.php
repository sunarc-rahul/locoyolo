<?php
//echo 'hi m here'; exit();
defined("ACCESS") or die("Access Restricted");
include_once('class.event.php');
include_once('image_manipulator.php');
global $DB;
$DB = new DBFilter();
$eventOBJ= new Event();

if(isset($frmdata['event_submit'])) {
    $result = $eventOBJ->addEvent();
}

if( $result['error']){
    $error =$result['error'];
}
if(isset($_GET['id']))
{
    $nameID=$_GET['id'];
}

if(isset($frmdata['edit_account']))
{
    $AccountOBJ->editCandidate($nameID);
}

$do = '';
if(isset($getVars['do']))
    $do=$getVars['do'];
//echo $do;exit;
switch($do)
{
    default:
    case 'event':
        checker();
        $CFG->template="organiseEvent/event.php";
        break;

    case 'eventdetails':
        checker();
        if(!isset($_SESSION['user_id']) || ($_SESSION['user_id'] == ''))
            Redirect(CreateURL('index.php'));

        $event_id = 64;
        $eventdetail = $eventOBJ->showEvent($event_id);
        $CFG->template="organiseEvent/eventdetails.php";

        break;


}

include(TEMP."/index.php");
//exit;
?>