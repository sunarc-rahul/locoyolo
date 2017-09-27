<?php
//echo 'm heree';exit;

defined("ACCESS") or die("Access Restricted");

$do = '';
if(isset($getVars['do']))
    $do=$getVars['do'];
switch($do)
{

    default:
    case 'searchlist':
        checker();

    $search = $_REQUEST['search'];

    if($search!='') { 
         $qry = "SELECT event_id as id,user_id, event_name,event_price, event_photo as img, 'event' as type, entry_type as data_type FROM events WHERE event_name LIKE '%" . $search . "%' limit 0, 5";

        $res_data_count = $DB->RunSelectQuery("SELECT count(*) as total FROM events WHERE event_name LIKE '%" . $search . "%'");

        $result = $res_data = $DB->RunSelectQuery($qry);
        if (!is_array($result)) {
            $result = $res_data = array();
        }
    }else
    {
        $result = $res_data = array();
    }
        $CFG->template="search/searchResult.php";
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