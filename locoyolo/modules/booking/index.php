<?php
//echo 'm heree';exit();

defined("ACCESS") or die("Access Restricted");
include_once('class.notification.php');
//$UserOBJ = new Notification();
global $DB;
//if(isset($frmdata['submit']))
// $UserOBJ->userNotification();
//$DB = new DBFilter();
//$AccountOBJ= new Account();


//echo $_SESSION['candidate_id'];
//$candidateData=$DB->SelectRecord('candidate','id='.$_SESSION['candidate_id']); //SelectRecord($table,$cond='',$fields='*')
//print_r($candidateData);
if(isset($_GET['id']))
{
    $nameID=$_GET['id'];
}



$do = '';
if(isset($getVars['do']))
    $do=$getVars['do'];

switch($do)
{

    default:
    case 'booking_confirmation':
      $CFG->template="booking/booking_confirmation.php";
      break;

}

include(TEMP."/index.php");
exit;
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