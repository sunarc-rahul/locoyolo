<?php
/*=======================================================================
	@Auther 	Richa verma
	@Date   	16-06-2011
	@Company	Sunarc Technologies
//======================================================================= */
defined("ACCESS") or die("Access Restricted");
function getMax($max)
{
	if($max < 2)
	{
		return 0;
	}
	if($max > 10)
	{
		getMax($max/2);
	}
	else
	{
		return $max;
	}
}
function getConductedExamInfo()
{
	global $DB,$frmdata;
	
	$query="select exam.* from examination as exam
			join candidate_test_subject_history as ctsh on ctsh.exam_id=exam.id ";
	$query .= " JOIN exam_candidate ec on ec.exam_id = exam.id ";
	
	$query .= 'where ctsh.candidate_id ='.$_SESSION['candidate_id'].' group by ctsh.exam_id order by exam.exam_name ';

	$result = $DB->RunSelectQuery($query);
	
	return $result;
}
//-------------------------------

function getTestDetail(&$totalCount, $exam_id)
{
	global $DB,$frmdata;
	$query = "select cth.exam_id,cth.test_instance, cth.test_id, t.test_name,cth.result,cth.created_date, t.test_date
				from candidate_test_history as cth
				left join test as t on t.id=cth.test_id where candidate_id=".$_SESSION['candidate_id']." ORDER by cth.created_date DESC";
	
	//echo $query;
	$result = $DB->RunSelectQueryWithPagination($query, $totalCount);
	//echo '<pre>';print_r($result);
	return $result;
}



//-------------------------------
function getTestDetailByExamId(&$totalCount, $exam_id)
{
	global $DB,$frmdata;
	
	$year_condition = '';
	$month_condition = '';
	$stream_condition = '';
	
	if($frmdata['year'])
	{
		$year = $frmdata['year'];
		if($frmdata['month'])
		{
			$month = $frmdata['month'];
			$year_condition = " and (DATE_FORMAT(t.test_date, '%Y-%m-%d') <= LAST_DAY(STR_TO_DATE('01,$month,$year','%d,%m,%Y'))) AND (DATE_FORMAT(t.test_date_end, '%Y-%m-%d') >= STR_TO_DATE('01,$month,$year','%d,%m,%Y')) ";
		}
		else
		{
			$year_condition = " and ((YEAR(t.test_date) <= $year) AND (YEAR(t.test_date_end) >= $year)) ";
		}
	}
	
	if($frmdata['stream_id'])
	{
		$stream_condition = " and t.stream_id=".$frmdata['stream_id']." ";
	}
	
	$query = "select cth.exam_id,cth.test_instance, cth.test_id,cth.result,cth.created_date, t.test_name, t.test_date,t.test_date_end, if(cth.result='P',1,0) as pass, 
				if(cth.result='F',1,0) as fail
				from candidate_test_history as cth
				left join test as t on t.id=cth.test_id ";
	
		$query .= " where 1 ";


	if($exam_id)
	{
		$query .= " AND cth.exam_id=$exam_id AND cth.candidate_id =".$_SESSION['candidate_id'];
	}
	
	//$query .= $year_condition.$stream_condition." group by cth.test_id";
	$query .= $year_condition.$stream_condition." ORDER by cth.created_date DESC";
		
	//echo $query;
	$result = $DB->RunSelectQueryWithPagination($query, $totalCount);

	return $result;
}
function getPageRecords()
{
	global $frmdata;
	$output='Show:';
	$output.='<select name="record" id="record" onchange="return FormRecord()" class="rounded">';
	$output.='<option value="10"';if(!isset($frmdata['record']) || $frmdata['record']=="10")$output.='selected';$output.='>10</option>';
	$output.= '<option value="20"';if($frmdata['record']=='20')$output.='selected'; $output.='>20</option>';
	$output.= '<option value="50"';if($frmdata['record']=='50')$output.=' selected ';$output.='>50</option>';
	$output.= '<option value="100"';if($frmdata['record']=='100')$output.=' selected ';$output.='>100</option>';
	$output.= '<option value="All"';if($frmdata['record']=='All')$output.=' selected ';$output.='>All</option>';
	$output.= '</select>';
	return $output;
}

function getSubjectNameByExamId($exam_id, $test_id='')
{
	global $DB,$frmdata;
	if($test_id)
	{
		$test_info = $DB->SelectRecord('test', "id=$test_id");
		$qMedia = $test_info->question_media;
		
		if($qMedia == 'subject')
		{
			$query = "SELECT ts.exam_id, ts.subject_id, sub.subject_name, 
				ts.subject_total_marks as subject_max_mark
				from test_subject as ts
				left join exam_subjects as es on es.subject_id=ts.subject_id
				left join subject as sub on sub.id= ts.subject_id 
				where ts.exam_id=$exam_id and es.exam_id=$exam_id 
				and ts.test_id=$test_id";
		}
	}
	else
	{
		$query="select es.exam_id, es.subject_id, sub.subject_name, 
				es.subject_max_mark   
				from exam_subjects as es
				left join subject as sub on sub.id= es.subject_id
				where es.exam_id=$exam_id";
	}
	//echo $query;exit;
	$result = $DB->RunSelectQuery($query);
	return $result;  
}


function getResultSheetReport(&$totalCount, $exam_type='', $exam_id, $test_id='', $candidate_id='',$test_instance='')
{
	global $DB,$frmdata;
	
	if($candidate_id!='')
	{
		$_SESSION['candidate_id']=$candidate_id;
	}
	$candidate_condition = '';
	$test_condition = '';
	$year_condition = '';
	$month_condition = '';
	$stream_condition = '';
	if($test_id!='')
	{
		$test_condition = " and test_id=$test_id ";
	}
	
	if($frmdata['year'])
	{
		$year = $frmdata['year'];
		if($frmdata['month'])
		{
			$month = $frmdata['month'];
			$year_condition = " and (DATE_FORMAT(t.test_date, '%Y-%m-%d') <= LAST_DAY(STR_TO_DATE('01,$month,$year','%d,%m,%Y'))) AND (DATE_FORMAT(t.test_date_end, '%Y-%m-%d') >= STR_TO_DATE('01,$month,$year','%d,%m,%Y')) ";
		}
		else
		{
			$year_condition = " and ((YEAR(t.test_date) <= $year) AND (YEAR(t.test_date_end) >= $year)) ";
		}
	}
	
	if($frmdata['candidate_id'])
	{
		$candidate_condition = " AND c.id=$candidate_id ";
	}
	
	 $query = "select ctsh.marks_obtained, t.test_name, exam.exam_name,
			exam.id as exam_id, t.id as test_id,
			ctsh.result, ctsh.subject_id, c.id, sub.subject_name,
			c.first_name, c.last_name
			from candidate_test_subject_history as ctsh
			left join candidate as c on c.id=ctsh.candidate_id
			left join subject as sub on ctsh.subject_id=sub.id
			left join test as t on t.id=ctsh.test_id
			left join examination as exam on exam.id=ctsh.exam_id "; 
			
			if($_SESSION['admin_user_type'] == 'P')
			{
				$query .= " JOIN parent_candidate pc on pc.candidate_id = c.id ";
				$query .= " WHERE (pc.is_approved = '1') AND pc.parent_id = '".$_SESSION['admin_user_id']."' ";
			}
			elseif ($_SESSION['admin_user_type'] == 'T')
			{
				$query .= " JOIN exam_candidate_teacher ect ON ((ect.candidate_id = c.id) AND ect.exam_id = exam.id) ";
				$query .= " WHERE ect.teacher_id = '".$_SESSION['admin_user_id']."' AND t.teacher_id = '".$_SESSION['admin_user_id']."' ";
			}
			else
			{
				$query .= " WHERE 1 ";
			}
			
	$query .= " AND ctsh.exam_id=".$exam_id.$test_condition.$year_condition.$month_condition.$candidate_condition." 
			and ctsh.id in (
				select id from candidate_test_subject_history 
				where exam_id=".$exam_id.$test_condition." and ctsh.candidate_id=".$_SESSION['candidate_id']." and ctsh.test_instance=".$test_instance." 
				
			)
			order by c.id";
		
		//echo $query;
		//exit;

		$result = $DB->RunSelectQuery($query);
		
	return $result;
}
function countCandidateSubjectMarks($exam_id, $test_id, $candidate_id,$test_instance)
{
	global $DB,$frmdata;
	
	$query .="select sub.subject_name, cqh.subject_id, 
				ctsh.marks_obtained as subject_marks,
				SUM(solve_time) as solve_time 
				from candidate_question_history as cqh
				join candidate_test_subject_history as ctsh ON (cqh.candidate_id=ctsh.candidate_id AND cqh.test_id=ctsh.test_id AND cqh.subject_id=ctsh.subject_id)
				left join question as q on q.id=cqh.question_id
				left join subject as sub on cqh.subject_id=sub.id
				where cqh.test_id=$test_id and cqh.exam_id=$exam_id and cqh.candidate_id=$candidate_id";
				if($test_instance!='')
				{
				$query .=" and cqh.test_instance=$test_instance ";
				}
				$query .=" group by cqh.subject_id";
	 $query ;
	$result=$DB->RunSelectQuery($query);
								
	return $result;
}

?>