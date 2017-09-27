<?php 
/**
 * 	@author	:	Akshay Yadav
 * 	@desc	:	Methods to get dashboard data
 */
class Dashboard
{
	/******************************************************************
	Des: A function to count candidate
	******************************************************************/
	function countCandidate()
	{
		global $DB,$frmdata;
		
		$totalCandidate = $DB->SelectRecord('candidate', '', 'count(*) as candidate');
		//print_r($totalCandidate);
		return $totalCandidate;
	}

	function countStreamCandidate()
	{
		global $DB,$frmdata;
		$query = "	select 	e.exam_name, e.id as exam_id,
							count(*) as candidate
							from candidate as c 
							join exam_candidate as ec on ec.candidate_id=c.id
							join examination as e on ec.exam_id=e.id
							group by e.id
							order by candidate desc";
			
		$result = $DB->RunSelectQuery($query);
		
		return $result;
	}
	
	/******************************************************************
	Des: A function to count finished test
	******************************************************************/
	function countfinishedTest()
	{
		global $DB,$frmdata;
		
		$query="select count(*) as finishtest,tc.candidate_id  from test join test_candidate as tc on tc.test_id = test.id
				where test_date<now() AND tc.candidate_id =".$_SESSION['candidate_id'];
				
		$result = $DB->RunSelectQuery($query);
		return $result;
	}
	
	/******************************************************************
	Des: A function to count remaining test
	******************************************************************/
	function countremainingTest()
	{
		global $DB,$frmdata;
		
		$query="select count(*) as test from test where test_date>=now()";
				
		$result = $DB->RunSelectQuery($query);
		return $result;
	}
	
	/******************************************************************
	Des: A function to get last test detail
	******************************************************************/
	function lastTestdetail()
	{
		global $DB,$frmdata;
		 $query = "	select cd.first_name as fname,cd.last_name as lname,t.id as test_id,t.exam_id as exam_id,c.candidate_id,c.marks_obtained,t.maximum_marks ,c.percentage,c.result,t.test_name,t.test_date,t.test_date_end,c.created_date from candidate_test_history as c 
					join test as t on t.id = c.test_id 
					join candidate as cd on cd.id = c.candidate_id
					where t.test_date < now() AND c.candidate_id =".$_SESSION['candidate_id']." ORDER BY `c`.`created_date` DESC limit 0,1";	
	$result = $DB->RunSelectQuery($query);
		return $result;
	}
	
	function lastTestResultTotal(&$totalCount)
	{
		global $DB,$frmdata;
		$query = $this->lastTestQuery();
		$_SESSION['queryForTotal'] = $query;
		$result = $DB->RunSelectQueryWithPagination($query,$totalCount);
		return $result;
	}
	
	function lastTestResultPass(&$totalCount)
	{
		global $DB,$frmdata;
		$query = $this->lastTestQuery('P');
		$_SESSION['queryForPass'] = $query;
		$result = $DB->RunSelectQueryWithPagination($query,$totalCount);
		return $result;
	}

	function lastTestResultFail(&$totalCount)
	{
		global $DB,$frmdata;		
		$query = $this->lastTestQuery('F');
		$_SESSION['queryForFail'] = $query;
		$result = $DB->RunSelectQueryWithPagination($query,$totalCount);
		return $result;
	}
	
	function lastTestQuery($for = '')
	{
		global $frmdata;
		$query = " select cand.*, cth.marks_obtained as marks,
					if(cth.result='P', 'PASS', 'FAIL') as result
							
					from candidate as cand
					JOIN candidate_test_history as cth ON cand.id = cth.candidate_id
					
					where cth.test_id= 
						(	select id from test 
							where test_date<now()
							order by test_date desc 
							limit 0,1	) and";
		
		if($for) $query.= " (cth.result = '$for') AND";
		
		if(isset($frmdata['name']) && $frmdata['name'] !='')
		{
			$query.=" (";
			$query.=" cand.first_name like '%".$frmdata['name']."%' or 
						cand.last_name like '%".$frmdata['name']."%' or
						(concat(trim(cand.first_name),' ',trim(cand.last_name)) like '%".addslashes($frmdata['name'])."%')";
			$query.=")";
			$query.=" and";	
		}
		
		$query=substr($query,0,(strlen($query)-4));
		
		if(isset($frmdata['orderby']) && $frmdata['orderby']!='' )
		{
			$query.=" order by ".$frmdata['orderby'];
		}	
		else
		{
			$query.=' order by cand.id desc';
		}
		
		return $query;
	}
	
		
	
	function getTestXml()
	{
		$finishedTest = $this->countfinishedTest();
		$remainingTest = $this->countremainingTest();
		
		$FCExporter = ROOTURL . "/lib/FusionCharts/ExportHandlers/JavaScript/index.php";
		$xml = "<chart caption='Test' showLegend='1' showPercentValues='0' 
					bgColor='E6E6E6,F0F0F0' bgAlpha='100,50' bgRatio='50,100' bgAngle='270' 
					showNames='1' useEllipsesWhenOverflow='0' showBorder='1' >
					
				   <set label='Finsihed Test' value='20' />
				   <set label='Remaining Test' value='40' />
				</chart>";
		
		header('Cache-control: no-cache' and 'pragma: no-cache');
		header('Content-type: text/xml');
		echo $xml;
		exit;
	}
	
	function getCandidateXml()
	{
		$candidates = $this->countStreamCandidate();
		
		$FCExporter = ROOTURL . "/lib/FusionCharts/ExportHandlers/JavaScript/index.php";
		$xml = "<chart caption='Student' showLegend='1' showPercentValues='0'
					bgColor='E6E6E6,F0F0F0' bgAlpha='100,50' bgRatio='50,100' bgAngle='270' 
					showNames='1' useEllipsesWhenOverflow='0' showBorder='1' >";
		
		foreach( $candidates as $candidate )
		{
			$xml .= "<set label='$candidate->exam_name' value='$candidate->candidate' />";
		}
		
		$xml .= "</chart>";
		
		header('Cache-control: no-cache' and 'pragma: no-cache');
		header('Content-type: text/xml');
		echo $xml;
		exit;
	}
	

	function recentlySubmittedHomework()
	{
		global $DB;
		
		$query = " SELECT chh.*, c.first_name, c.last_name, h.homework_name, e.exam_name " 
				." FROM candidate_homework_history chh "
				." JOIN candidate c ON c.id = chh.candidate_id "
				." JOIN examination e ON e.id = chh.exam_id "
				." JOIN homework h ON h.id = chh.homework_id "
				." GROUP BY chh.id ORDER BY chh.created_date DESC LIMIT 0,5 ";
		
		return $DB->RunSelectQuery($query);
	}
}
?>