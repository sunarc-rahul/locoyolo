<?php
defined("ACCESS") or die("Access Restricted");
include_once('class.calendar.php');
global $DB;
$DB = new DBFilter();

if( $result['error']){
    $error =$result['error'];
}

$do = '';
if(isset($getVars['do']))
    $do=$getVars['do'];
switch($do)
{
    default:
    case 'calendar':
        checker();
        $CFG->template="calendar/calendar.php";
        break;


}

include(TEMP."/index.php");
//exit;
?>