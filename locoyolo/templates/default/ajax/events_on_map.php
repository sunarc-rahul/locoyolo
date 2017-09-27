<?php
include_once("../../config.php");
include_once(INC."/commonfunction.php");
include_once(INC.'/dbfilter.php');
include_once(INC.'/dbqueries.php');
include_once(INC.'/dbhelper.php');

$eventType = $_POST['eventType']?$_POST['eventType']:0;
$getPings = $_POST['getPings'];
$getEvents = $_POST['getEvents'];
$SWlat = $_POST["SWlat"];
$SWlng = $_POST["SWlng"];
$NElat = $_POST["NElat"];
$NElng = $_POST["NElng"];
$date = explode('-',$_POST["daterange"]);
$time = explode('-',$_POST["timerange"]);
$start_date = date('Y-m-d',strtotime($date[0]));
$end_date = date('Y-m-d',strtotime($date[1]));
$start_time = date('H:i:s',strtotime("".$time[0].""));
$end_time = date('H:i:s',strtotime("".$time[1].""));
$return_arr = array();
$catrgoryQry=$getpingQry=$geteventQry='';

if(($getPings==0&&$getEvents==1)||($getPings==1&&$getEvents==0))
{
	if($getPings!=0)
	{
		$getpingQry=" and entry_type='Ping'";
	}
	if($getEvents!=0)
	{
		$geteventQry=" and entry_type=''";
	}
}
if($eventType!=0)
{
	$catrgoryQry=" and event_category=".$eventType;
}
$return_arr = array();
//==============================PAGINATION START SCRIPT=======================================//

$query = "SELECT * from events where ((event_lat >= '$SWlat' and event_lat <=  '$NElat') and (event_long <= '$NElng' and event_long >= '$SWlng')) and date(start_date) between  date('$start_date') and date('$end_date') and time(start_date) between  time('$start_time') and time('$end_time')  and  date(end_date)>date(now()) $getpingQry $geteventQry $catrgoryQry";

 $res = $DB->RunSelectQuery($query);

//$stmt = $pdo->prepare("SELECT * from event_locations where event_lat > ? and event_lat < ? and event_long < ? and event_long > ? limit 10");
/*
$stmt->bindValue(1, $SWlat, PDO::PARAM_STR);
$stmt->bindValue(2, $NElat, PDO::PARAM_STR);
$stmt->bindValue(3, $NElng, PDO::PARAM_STR);
$stmt->bindValue(4, $SWlng, PDO::PARAM_STR);
$stmt->bindValue(5, $start_time, PDO::PARAM_STR);
$stmt->bindValue(6, $end_time, PDO::PARAM_STR);
$stmt->bindValue(7, $event_date, PDO::PARAM_STR);
$stmt->execute();*/

$a = count($res);

$numrows = $a;
// number of rows to show per page
$rowsperpage = 10;
// find out total pages
$totalpages = ceil($numrows / $rowsperpage);

// get the current page or set a default
if (isset($_POST['currentpage']) && is_numeric($_POST['currentpage'])) {
   // cast var as int
   $currentpage = (int) $_POST['currentpage'];
} else {
   // default page num
   $currentpage = 20;
} // end if

// if current page is greater than total pages...
if ($currentpage > $totalpages) {
   // set current page to last page
   $currentpage = $totalpages;
} // end if
// if current page is less than first page...
if ($currentpage < 1) {
   // set current page to first page
   $currentpage = 1;
} // end if

// the offset of the list, based on current page 
$offset = ($currentpage - 1) * $rowsperpage;

 $stmt = "SELECT * from events where ((event_lat >= '$SWlat' and event_lat <= '$NElat') and (event_long <= '$NElng' and event_long >= '$SWlng')) and date(start_date) between  date('$start_date') and date('$end_date') and time(start_date) between  time('$start_time') and time('$end_time')  and  date(end_date)>date(now()) $getpingQry $geteventQry $catrgoryQry LIMIT $offset, $rowsperpage";
 $result_data = $DB->RunSelectQuery($stmt);
//$stmt = $pdo->prepare("SELECT * from event_locations where event_lat > ? and event_lat < ? and event_long < ? and event_long > ? limit 10");
/*
$stmt->bindValue(1, $SWlat, PDO::PARAM_STR);
$stmt->bindValue(2, $NElat, PDO::PARAM_STR);
$stmt->bindValue(3, $NElng, PDO::PARAM_STR);
$stmt->bindValue(4, $SWlng, PDO::PARAM_STR);
$stmt->bindValue(5, $start_time, PDO::PARAM_STR);
$stmt->bindValue(6, $end_time, PDO::PARAM_STR);
$stmt->bindValue(7, $event_date, PDO::PARAM_STR);
$stmt->bindValue(8, $offset, PDO::PARAM_INT);
$stmt->bindValue(9, $rowsperpage, PDO::PARAM_INT);
$stmt->execute();*/
$i=1;
foreach($result_data as $result ){
	$result = (array) $result;
			$row_array["eventlat"] = $result["event_lat"];
			$row_array["eventlng"] = $result["event_long"];
			$row_array["eventname"] = $result["event_name"];
			$row_array["date_time_display"] = date("j F Y", strtotime($result["start_date"]))." | ".date("g:ia", strtotime($result["start_time"]))." - ".date("g:ia", strtotime($result["end_time"]));
			$row_array["eventid"] = $result["event_id"];
			
			if ($result["entry_type"] == "Ping"){
				$row_array["organiser_statement"] = "has pinged a meetup";
			}else{
				$row_array["organiser_statement"] = "is organising an event";
			}
			
			$qry = "SELECT * from public_users where id=".$result["user_id"];
			$result_data = $DB->RunSelectQuery($qry);
			foreach($result_data as $resultuser ){
				$resultuser= (array) $resultuser;
				if ($resultuser["profile_pic"] == ""){
					$row_array["organiser_pic"] = "images/no_profile_pic.gif";
				}else{
					$row_array["organiser_pic"] = ROOTURL.$resultuser["profile_pic"];
				}
				$row_array["organiser_name"] = $resultuser["firstname"]." ".$resultuser["lastname"];
			}
			
			$event_type = $result["event_category"];
			
			if ($result["entry_type"] == "Ping"){
			$sql3 = "SELECT * from ping_types where id = $event_type";
			}else{
			$sql3 = "SELECT * from event_types where id = $event_type";
			}
			
			$result_data = $DB->RunSelectQuery($sql3);
			foreach($result_data as $resulttype){
				$resulttype = (array) $resulttype;
				$row_array["eventicon"] = $resulttype["map_icon"];
			}
			$row_array["firsttimepost"] = "no";
			$row_array["number_of_pages"] = $totalpages;
			
			if ($result["event_price"] < 1) { 
			$row_array["price"] = "Free"; 
			}else{ 
			$row_array["price"] = "S$".$result["event_price"]; 
			}
			$row_array["location"] = $result["event_location"]; 
			
			array_push($return_arr,$row_array);
}

echo json_encode($return_arr);
?>
