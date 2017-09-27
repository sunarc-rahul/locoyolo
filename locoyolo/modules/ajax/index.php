<?php 

defined("ACCESS") or die("Access Restricted");
include_once('class.account.php');	
global $DB;	
$DB = new DBFilter();
$AccountOBJ= new Account();
	
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
	case 'eventslistupdate':
	
		$CFG->template="ajax/eventslistupdate.php";
	break;

	case 'events_on_map' :
	
	$CFG->template="ajax/events_on_map.php";
		
	break;

	case 'send_comment' :

	$CFG->template="ajax/send_comment.php";

	break;
	case 'show_more_comment' :

	$CFG->template="ajax/show_more_comments.php";

	break;
	case 'delete_comment' :

	$CFG->template="ajax/delete_comment.php";

	break;
	case 'join_ping' :

	$CFG->template="ajax/join_ping.php";

	break;
	case 'confirm_buddy' :

	$CFG->template="ajax/confirm_buddy.php";

	break;
	case 'mainsearch' :
	
	$CFG->template="ajax/mainsearch.php";

	break;

    case 'search' :

        $CFG->template="ajax/search.php";

        break;
	case 'cancel_ping' :

	$CFG->template="ajax/cancel_ping.php";

	break;

    case 'cancel_booking' :

	$CFG->template="ajax/cancel_booking.php";

	break;

    case 'cancel_event' :

	$CFG->template="ajax/cancel_event.php";

	break;
    case 'join_event_message' :

	$CFG->template="ajax/join_event_message.php";

	break;

    case 'join_ping' :

	$CFG->template="ajax/join_ping.php";

	break;

    case 'send_buddy_request' :

	$CFG->template="ajax/send_buddy_request.php";

	break;

    case 'invite' :
	    $CFG->template="ajax/invite.php";

	break;

	case 'reorganize_event' :
	    $CFG->template="ajax/reorganize_event.php";

	break;

    case 'booking_confirmation_message' :

        $CFG->template="ajax/booking_confirmation_message.php";

        break;
    case 'verify_mobile' :

        $CFG->template="ajax/verify_mobile.php";

        break;

    case 'fetch_notifications' :

        $CFG->template="ajax/fetch_notifications.php";

        break;

   


} 

include(TEMP."/index.php");
exit;
?>