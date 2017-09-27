<?php
/*=======================================================================
	@Auther 	Richa verma
	@Date   	16-06-2011
	@Company	Sunarc Technologies
//======================================================================= */
defined("ACCESS") or die("Access Restricted");

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
		elseif($qMedia == 'paper')
		{
			$test_paper = $DB->SelectRecord('test_paper','test_id='.$test_id);
			$paper_id = $test_paper->paper_id;
			
			$query = "SELECT es.exam_id, ps.subject_id, sub.subject_name, 
				ps.subject_total_marks as subject_max_mark
				from paper_subject as ps
				left join exam_subjects as es on es.subject_id=ps.subject_id
				left join subject as sub on sub.id= ps.subject_id 
				where es.exam_id=$exam_id 
				and ps.paper_id=$paper_id";
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

function getSubjectInfoByExamId($exam_id)
{
	global $DB,$frmdata;
	
	$query="select es.*, sub.subject_name from exam_subjects as es 
			left join subject as sub on es.subject_id=sub.id
			where es.exam_id='$exam_id' order by subject_id ";

	$result = $DB->RunSelectQuery($query);
	
	return $result;  
	
}

function makeRandomSession($random_que_id_set='')
{
	global $DB,$frmdata;
	$no_subjective = ' AND ( question_type <> "S" ) ';
	//echo "ok";exit;
	//$_SESSION['total']["beg_".$marks.'_question']
	if ($random_que_id_set!='') 
	{
		
			$random_que_id_array = explode(",",$random_que_id_set);
			
			for($index=0; $index<count($random_que_id_array); $index++)
			{
				$question_info = $DB->SelectRecord('question','id='.$random_que_id_array[$index]);
				
				if($question_info->question_level=='B')
				{
					if(isset($beginner_question[$question_info->marks]))
						$beginner_question[$question_info->marks]+=1;
					else 
						$beginner_question[$question_info->marks] = 1;
				}
				
				if($question_info->question_level=='I')
				{
					if(isset($inter_question[$question_info->marks]))
						$inter_question[$question_info->marks]+=1;
					else 
						$inter_question[$question_info->marks] = 1;
				}
			
				if($question_info->question_level=='H')
				{
					if(isset($higher_question[$question_info->marks]))
						$higher_question[$question_info->marks]+=1;
					else 
						$higher_question[$question_info->marks] = 1;
				}
			}
			
			if(isset($beginner_question))
			{
				foreach($beginner_question as $key=>$val) // key=marks val=no of question
				{
					$_SESSION['subject']["beg_".$key]=$val;
					$_SESSION['subject']["beg_".$key.'_question']=$val;
				}
			}
			unset($beginner_question);
			
			if(isset($inter_question))
			{
				foreach($inter_question as $key=>$val) // key=marks val=no of question
				{
					$_SESSION['subject']["int_".$key]=$val;
					$_SESSION['subject']["int_".$key.'_question']=$val;
				}
			}
			unset($inter_question);
		
			if(isset($higher_question))
			{
				foreach($higher_question as $key=>$val) // key=marks val=no of question
				{
					$_SESSION['subject']["high_".$key]=$val;
					$_SESSION['subject']["high_".$key.'_question']=$val;
				}
			}
			unset($higher_question);
	}
}

//========This function return subject details of a test====================
function getTestSubjectByTestId($test_id)
{
	global $DB,$frmdata;
	
	$query="select * from test_subject where test_id=$test_id";
	
	$result = $DB->RunSelectQuery($query);
	
	return $result;  
}


//========This function return subject details of a paper====================
function getPaperSubjectByPaperId($paper_id)
{
	global $DB,$frmdata;
	
	$query="select * from paper_subject where paper_id=$paper_id";

	$result = $DB->RunSelectQuery($query);
	
	return $result;  
}
//========This function return subject details of a homework====================
function getHomeworkSubjectByHomeworkId($homework_id)
{
	global $DB,$frmdata;
	
	$query="select * from homework_subject where homework_id=$homework_id";

	$result = $DB->RunSelectQuery($query);
	
	return $result;  
}
//=========This function return an array of total subject question for a test. This array's keys are subject id========
function getTotalSubjectQuestion($test_subject, $for = 'test')
{
	global $DB,$frmdata;

	if($for == 'test')
	{
		$query="select count(*) as total_question, subject_id from test_questions where test_id=".$test_subject[0]->test_id."
	 			group by subject_id";
	}
	elseif ($for == 'paper')
	{
		$query="select count(*) as total_question, subject_id from paper_questions where paper_id=".$test_subject[0]->paper_id."
 			group by subject_id";	
	}
	elseif($for == 'homework')
	{
		$query="select count(*) as total_question, subject_id from homework_questions where homework_id=".$test_subject[0]->homework_id."
	 			group by subject_id";
	}
	
	$result = $DB->RunSelectQuery($query);
	$subject_total_question = array();
	for($counter=0; $counter<count($result); $counter++)
	{
		$subject_total_question[$result[$counter]->subject_id] = $result[$counter]->total_question;
	}
	
	return $subject_total_question; 
}

//=========This function return an array of random question ids string (seperated by ,) for a test. This array's keys are subject id========
function getSubjectRandomQuestionId($test_subject, $for = 'test')
{
	global $DB,$frmdata;
	
	if($for == 'test')
	{
		$query="select question_id, subject_id from test_questions 
	 			where test_id=".$test_subject[0]->test_id." and question_selection='R'";
	}
	elseif($for == 'paper')
	{
		$query="select question_id, subject_id from paper_questions 
 			where paper_id=".$test_subject[0]->paper_id." and question_selection='R'";	
	}
	elseif($for == 'homework')
	{
		$query="select question_id, subject_id from homework_questions 
	 			where homework_id=".$test_subject[0]->homework_id." and question_selection='R'";
	}
	
	$result = $DB->RunSelectQuery($query);
	
	$subject_random_question_set = array();
	for($counter=0; $counter<count($result); $counter++)
	{
		$subject_random_question_set[$result[$counter]->subject_id].= $result[$counter]->question_id.",";
	}
	
	return $subject_random_question_set; 
}

//=========This function return an array of total custom question for a test. This array's keys are subject id========
function getSubjectCustomQuestionId($test_subject, $for = 'test')
{
	global $DB,$frmdata;
	
	if($for == 'test')
	{
		$query=" select question_id, subject_id from test_questions 
	 			where test_id=".$test_subject[0]->test_id." and question_selection='C'";
	}
	elseif($for == 'paper')
	{
		$query=" select question_id, subject_id from paper_questions 
 			where paper_id=".$test_subject[0]->paper_id." and question_selection='C'";
	}
	elseif($for == 'homework')
	{
		$query=" select question_id, subject_id from homework_questions 
	 			where homework_id=".$test_subject[0]->homework_id." and question_selection='C'";
	}
	
	$result = $DB->RunSelectQuery($query);
	$subject_custom_question_set = array();
	for($counter=0; $counter<count($result); $counter++)
	{
		$subject_custom_question_set[$result[$counter]->subject_id].= $result[$counter]->question_id.",";
	}
	
	return $subject_custom_question_set; 
}



function getSubjectGroupQuestionId($test_subject, $for = 'test')
{
	global $DB,$frmdata;
	
	if($for == 'test')
	{
		$query=" select subject_id, group_question_id from test_questions 
	 			where test_id=".$test_subject[0]->test_id." and question_selection='G' group by group_question_id";
	}
	elseif($for == 'paper')
	{
		$query=" select question_id, subject_id from paper_questions 
 			where paper_id=".$test_subject[0]->paper_id." and question_selection='G'";
	}
	elseif($for == 'homework')
	{
		$query=" select question_id, subject_id from homework_questions 
	 			where homework_id=".$test_subject[0]->homework_id." and question_selection='G'";
	}
	
	$result = $DB->RunSelectQuery($query);
	$subject_group_question_set = array();
	for($counter=0; $counter<count($result); $counter++)
	{
		
		$subject_group_question_set[$result[$counter]->subject_id].= $result[$counter]->group_question_id.",";
	}
	
	return $subject_group_question_set; 
}




//==========get candidate's exam information by candidate id================================
function getCandidateExamInfo($candidate_id)
{
	global $DB,$frmdata;
	
	$query="select exam.* from examination as exam
 			left JOIN candidate_test_subject_history as cs on exam.id=cs.exam_id
 			where cs.candidate_id=".$candidate_id." group by exam.id";
	//echo $query;exit;
	$result = $DB->RunSelectQuery($query);
	
	return $result;  
}

//======to get candidate's last subject history=====================
function getCandidateTestSubjectHistory($exam_id, $candidate_id, $subject_id='')
{
	global $DB,$frmdata;
	
	$query = "select * from candidate_test_subject_history as ctsh 
				 where ctsh.candidate_id=$candidate_id and exam_id=$exam_id";
	
	if($subject_id!='')
	{
		$query.=" and subject_id=$subject_id ";
	}
	
	$query.=" order by ctsh.id desc limit 1";
	//echo $query;exit;
	$candidate_test_subject_history = $DB->RunSelectQuery($query);
	//print_r($candidate_test_history);//exit;
	
	return $candidate_test_subject_history; 
}

function getCandidateSubjectInfo($exam_id, $candidate_id)
{
	global $DB,$frmdata;
	
	$query = "select sub.subject_name, ctsh.* 
				from candidate_test_subject_history as ctsh
				left join subject as sub on ctsh.subject_id=sub.id
				where ctsh.exam_id=$exam_id and ctsh.candidate_id=$candidate_id
				and ctsh.id in (
				select max(id) from candidate_test_subject_history 
				where exam_id=$exam_id and candidate_id=$candidate_id
				group by candidate_id, subject_id
				)";
	
	//echo $query;exit;
	$result = $DB->RunSelectQuery($query);
	//print_r($candidate_test_history);//exit;
	
	return $result; 
}

function getResultSheetReport(&$totalCount, $exam_type, $exam_id, $test_id='', $candidate_id='')
{
	global $DB,$frmdata;
	
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
	
	if($candidate_id)
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
				select max(id) from candidate_test_subject_history 
				where exam_id=".$exam_id.$test_condition." 
				group by candidate_id, subject_id
			)
			order by c.id";
		
		echo $query;//exit;

		$result = $DB->RunSelectQuery($query);
	
	return $result;
}

function getResultSheetReportForExcel($exam_id, $test_id='', $candidate_id='', $year='', $stream_id='', $month='')
{
	global $DB,$frmdata;
	
	$test_condition = '';
	$year_condition = '';
	$month_condition = '';
	if($test_id!='')
	{
		$test_condition = " and t.id=$test_id ";
	}
	
	if($candidate_id!='')
	{
		$test_condition.= " and ctsh.candidate_id=$candidate_id ";
	}
	
	if($year!='')
	{
		if($month!='')
		{
			$year_condition = " and (DATE_FORMAT(t.test_date, '%Y-%m-%d') <= LAST_DAY(STR_TO_DATE('01,$month,$year','%d,%m,%Y'))) AND (DATE_FORMAT(t.test_date_end, '%Y-%m-%d') >= STR_TO_DATE('01,$month,$year','%d,%m,%Y')) ";
		}
		else
		{
			$year_condition = " and ((YEAR(t.test_date) <= $year) AND (YEAR(t.test_date_end) >= $year)) ";
		}
	}
	
	$query = "select ctsh.marks_obtained,
				ctsh.result, ctsh.subject_id, c.id, sub.subject_name, 
				c.first_name, c.last_name, t.id as test_id,
				COUNT(question_id) as total_questions,
				
				sum(if(q.question_level='B', 1, 0)) AS total_b, 
				sum(if(q.question_level='I', 1, 0)) AS total_i, 
				sum(if(q.question_level='H', 1, 0)) AS total_h,
				
				sum(if(((q.question_level='B') AND (is_answer_correct = 'Y')), 1, 0)) AS correct_b, 
				sum(if(((q.question_level='I') AND (is_answer_correct = 'Y')), 1, 0)) AS correct_i, 
				sum(if(((q.question_level='H') AND (is_answer_correct = 'Y')), 1, 0)) AS correct_h,
				
				sum(if(((q.question_level='B') AND (is_answer_correct = 'N') AND (given_answer_id != '') AND (given_answer_id != '0')), 1, 0)) AS wrong_b, 
				sum(if(((q.question_level='I') AND (is_answer_correct = 'N') AND (given_answer_id != '') AND (given_answer_id != '0')), 1, 0)) AS wrong_i, 
				sum(if(((q.question_level='H') AND (is_answer_correct = 'N') AND (given_answer_id != '') AND (given_answer_id != '0')), 1, 0)) AS wrong_h,
				
				sum(if(((q.question_level='B') AND ((given_answer_id = '') OR (given_answer_id = '0') OR (is_answer_correct = ''))), 1, 0)) AS skipped_b, 
				sum(if(((q.question_level='I') AND ((given_answer_id = '') OR (given_answer_id = '0') OR (is_answer_correct = ''))), 1, 0)) AS skipped_i, 
				sum(if(((q.question_level='H') AND ((given_answer_id = '') OR (given_answer_id = '0') OR (is_answer_correct = ''))), 1, 0)) AS skipped_h
				
				from candidate_test_subject_history as ctsh
				left join candidate_question_history cqh on ((cqh.exam_id = ctsh.exam_id) AND (cqh.test_id = ctsh.test_id) AND (cqh.candidate_id = ctsh.candidate_id) AND (cqh.subject_id = ctsh.subject_id))
				left join question as q on q.id = cqh.question_id
				left join candidate as c on c.id=ctsh.candidate_id
				left join subject as sub on ctsh.subject_id=sub.id
				left join test as t on t.id=ctsh.test_id
				where ctsh.exam_id=".$exam_id.$test_condition.$year_condition.$month_condition ;

	if($test_id=='')
	{
		$query.= " and ctsh.id in (
					select max(id) from candidate_test_subject_history 
					where exam_id=".$exam_id.$test_condition." 
					group by candidate_id, subject_id
				) ";
	}
	
	$query.=	" group by sub.id,cqh.candidate_id,t.id,ctsh.exam_id
				order by c.id, subject_name";
		
	//	echo $query;exit;
	
	$result = $DB->RunSelectQuery($query);
	return $result;
}

function getConductedExamInfo()
{
	global $DB,$frmdata;
	
	$query="select exam.* from examination as exam
			join candidate_test_subject_history as ctsh on ctsh.exam_id=exam.id ";
	
	if($_SESSION['admin_user_type'] == 'P')
	{
		$query .= " JOIN exam_candidate ec on ec.exam_id = exam.id ";
		$query .= " JOIN parent_candidate pc on pc.candidate_id = ec.candidate_id ";
		$query .= " WHERE (pc.is_approved = '1') AND pc.parent_id = '".$_SESSION['admin_user_id']."' ";
	}
	elseif ($_SESSION['admin_user_type'] == 'T')
	{
		$query .= " JOIN exam_candidate ec on ec.exam_id = exam.id ";
		$query .= " JOIN exam_candidate_teacher ect ON ((ect.candidate_id = ec.candidate_id) AND ect.exam_id = exam.id) ";
		$query .= " WHERE ect.teacher_id = '".$_SESSION['admin_user_id']."' ";
	}
	
	$query .= ' group by ctsh.exam_id order by exam.exam_name ';
	
	$result = $DB->RunSelectQuery($query);
	
	return $result;
}

function getConductedExamInfoForHomework()
{
	global $DB,$frmdata;
	
	$query="select exam.* from examination as exam
			join candidate_homework_history as chh on chh.exam_id=exam.id ";
	
	if($_SESSION['admin_user_type'] == 'P')
	{
		$query .= " JOIN exam_candidate ec on ec.exam_id = exam.id ";
		$query .= " JOIN parent_candidate pc on pc.candidate_id = ec.candidate_id ";
		$query .= " WHERE (pc.is_approved = '1') AND pc.parent_id = '".$_SESSION['admin_user_id']."' ";
	}
	elseif ($_SESSION['admin_user_type'] == 'T')
	{
		$query .= " JOIN exam_candidate ec on ec.exam_id = exam.id ";
		$query .= " JOIN exam_candidate_teacher ect ON ((ect.candidate_id = ec.candidate_id) AND ect.exam_id = exam.id) ";
		$query .= " WHERE ect.teacher_id = '".$_SESSION['admin_user_id']."' ";
	}
	
	$query .= " group by chh.exam_id order by exam.exam_name ";
	
	$result = $DB->RunSelectQuery($query);
	
	return $result;
}

function getConductedHomework()
{
	global $DB,$frmdata;
	
	$query="select homework.* from homework
			join candidate_homework_history as chh on chh.homework_id=homework.id ";
	
	if($_SESSION['admin_user_type'] == 'P')
	{
		$query .= " JOIN exam_candidate ec on ec.exam_id = chh.exam_id ";
		$query .= " JOIN parent_candidate pc on pc.candidate_id = ec.candidate_id ";
		$query .= " WHERE (pc.is_approved = '1') AND pc.parent_id = '".$_SESSION['admin_user_id']."' ";
	}
	elseif ($_SESSION['admin_user_type'] == 'T')
	{
		$query .= " JOIN exam_candidate ec on ec.exam_id = chh.exam_id ";
		$query .= " JOIN exam_candidate_teacher ect ON ((ect.candidate_id = ec.candidate_id) AND ect.exam_id = ec.exam_id) ";
		$query .= " WHERE ect.teacher_id = '".$_SESSION['admin_user_id']."' AND homework.teacher_id = '".$_SESSION['admin_user_id']."' ";
	}
	
	$query .= " group by homework.id order by homework.homework_name ";
	
	$result = $DB->RunSelectQuery($query);
	
	return $result;
}

function getHomeworkYears()
{
	global $DB;
	
	$homeworkyearlistStart = $DB->SelectRecords('homework',"1", 'year(homework_date) as year', 'group by year(homework_date)');
	$homeworkyearlistEnd = $DB->SelectRecords('homework',"homework_date<=now()", 'year(homework_date_end) as year', 'group by year(homework_date_end)');
	$homeworkyearlist = array();

	foreach($homeworkyearlistStart as $year)
	{
		$homeworkyearlist[$year->year] = $year->year;
	}
		
	foreach($homeworkyearlistEnd as $year)
	{
		$homeworkyearlist[$year->year] = $year->year;
	}
	
	sort($homeworkyearlist, SORT_NUMERIC);
	return $homeworkyearlist;
}

function getCandidatePersonalData(&$totalCount)
{
	global $DB,$frmdata;
	$cond=0;
	$query="select c.*, GROUP_CONCAT(e.exam_name SEPARATOR ', ') as exam_name 
			from candidate as c
			left join exam_candidate as ec on ec.candidate_id=c.id
			left join examination as e on ec.exam_id=e.id ";
	
	if(isset($frmdata['name']) && $frmdata['name'] !='')
	{
		$query.=" where (";
		$query.=" c.first_name like '%".$frmdata['name']."%' or 
					c.last_name like '%".$frmdata['name']."%' or
					(concat(trim(c.first_name),' ',trim(c.last_name)) like '%".addslashes($frmdata['name'])."%')";
		$query.=")";
		
		$cond++;
	}
	if(isset($frmdata['exam_id']) && $frmdata['exam_id'] !='')
	{
		if($cond==0)
			$query.=" where ";
		else
			$query.=" and ";
		$query.=" e.id='".$frmdata['exam_id']."'";
	}

	$query .= ' GROUP BY c.id ORDER BY c.first_name, c.last_name ';
	
	//echo $query;
	
	$_SESSION['queryForCandidate'] = $query;
	
	$result = $DB->RunSelectQueryWithPagination($query, $totalCount);
	
	return $result;
}

function getCandidatePersonalDataForExcel()
{
	global $DB,$frmdata;

	$query = $_SESSION['queryForCandidate'];
	$result = $DB->RunSelectQuery($query);
	
	return $result;
}

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
	
	$query = "select cth.exam_id, cth.test_id, t.test_name, t.test_date,t.test_date_end,
				COUNT(cth.candidate_id) as total_candidate, sum(if(cth.result='P',1,0)) as pass, 
				sum(if(cth.result='F',1,0)) as fail
				from candidate_test_history as cth
				left join test as t on t.id=cth.test_id ";
	
	if($_SESSION['admin_user_type'] == 'P')
	{
		$query .= " JOIN parent_candidate pc on pc.candidate_id = cth.candidate_id ";
		$query .= " WHERE (pc.is_approved = '1') AND pc.parent_id = '".$_SESSION['admin_user_id']."' ";
	}
	elseif ($_SESSION['admin_user_type'] == 'T')
	{
		$query .= " JOIN exam_candidate_teacher ect ON ((ect.candidate_id = cth.candidate_id) AND ect.exam_id = cth.exam_id) ";
		$query .= " WHERE ect.teacher_id = '".$_SESSION['admin_user_id']."' AND t.teacher_id = '".$_SESSION['admin_user_id']."' ";
	
	}
	else
	{
		$query .= " where 1 ";
	}

	if($exam_id)
	{
		$query .= " AND cth.exam_id=$exam_id ";
	}
	
	$query .= $year_condition.$stream_condition." group by cth.test_id";
		
	//echo $query;
	$result = $DB->RunSelectQueryWithPagination($query, $totalCount);
	//echo '<pre>';print_r($result);
	return $result;
}

function getTestInfoByTestId($test_id)
{
	global $DB,$frmdata;
	
	/*$query = "select t.*, tl.level_name from test as t
				left join test_level as tl on tl.id=t.level_id
				where t.id=$test_id";*/
	
	$query = "select t.*
				from test as t
				where t.id=$test_id";
		
		//echo $query;exit;
	
	$result = $DB->RunSelectQuery($query);
	return $result;
}

function getExamSummaryData($exam_id)
{
	global $DB,$frmdata;
	
	/*$query = "select count(*) as no_of_times, cth.candidate_id 
			from candidate_test_subject_history as cth
			where cth.exam_id=$exam_id
			group by cth.candidate_id";*/
	
	$query = "select count(*) as no_of_times, cth.candidate_id 
			from candidate_test_history as cth
			where cth.exam_id=$exam_id
			group by cth.candidate_id";
		
		//echo $query;exit;
	
	$result = $DB->RunSelectQuery($query);
	return $result;
}

function getTableRecordId($table_name, $condition, $table_field, $data, $stream_id='', $spcl_case='')
{
	global $DB,$frmdata;
	
	if($spcl_case)
	{
		$field1 = $table_name . "_name" ;
		$value1 = $condition['name'];

		$condition1 = " $field1 = '$value1' ";
		
		$field2 = $condition['second_field'];
		$value2 = $condition[$field2];
		$condition2 = " $field2='$value2' ";
		
		$condition_final = $condition1 . " AND " . $condition2;
		
		$data_info = $DB->SelectRecord($table_name, $condition_final);
		if($data_info)
		{
			$id = $data_info->id;
		}
		else
		{
			$data_info = $DB->SelectRecord($table_name, $condition1);
			if($data_info)
			{
				$id = false;
			}
			else
			{
				$table_data[$field1] = $value1;
				$table_data[$field2] = $value2;
				$id = $DB->InsertRecord($table_name, $table_data);
			}
		}
		
		return $id;
	}
	
	$data_info = $DB->SelectRecord($table_name, $condition);
    if($data_info)
    {
    	if($table_name=='subject' && $stream_id!='')
    	{
    		if($data_info->include_stream=='' || $data_info->include_stream=='N')
    		{
    			$table_update_data['include_stream'] = 'Y';
    			$table_update_data['stream_id'] = $stream_id;
    			$DB->UpdateRecord($table_name, $table_update_data, "id=".$data_info->id);
    		}
    		else
    		{
    			return 0;
    		}
    		
    	}
       $result = $data_info->id;
    }
    else
    {
    	if($table_name=='subject')
    	{
    		if($stream_id!='')
    		{
    			$table_data['include_stream'] = 'Y';
    			$table_data['stream_id'] = $stream_id;
    		}
    	}
    	
    	$table_data[$table_field] = $data;
       	$result = $DB->InsertRecord($table_name, $table_data);
    }
	return $result;
}

function getSubjectNameBySubjectId($subject_id)
{
	global $DB,$frmdata;
	
	$subject_info = $DB->SelectRecord('subject', "id=$subject_id and id!=-1", 'subject_name');
	
	return $subject_info->subject_name;
}

//=========count candidate marks according to subjects=================
function countCandidateSubjectMarks($exam_id, $test_id, $candidate_id)
{
	global $DB,$frmdata;
	
	$query ="select sub.subject_name, cqh.subject_id, 
				ctsh.marks_obtained as subject_marks,
				SUM(solve_time) as solve_time 
				from candidate_question_history as cqh
				join candidate_test_subject_history as ctsh ON (cqh.candidate_id=ctsh.candidate_id AND cqh.test_id=ctsh.test_id AND cqh.subject_id=ctsh.subject_id)
				left join question as q on q.id=cqh.question_id
				left join subject as sub on cqh.subject_id=sub.id
				where cqh.test_id=$test_id and cqh.exam_id=$exam_id and cqh.candidate_id=$candidate_id
				group by cqh.subject_id";
	
	$result=$DB->RunSelectQuery($query);
								
	return $result;
}

/**
 * 	@author	:	Ashwini Agarwal
 * 	@param	:	Test ID
 * 	@desc	:	Get active/enbable test having same stream
 */
function getActiveTestHavingSameStream($test_id)
{
	global $DB;
	
	$query = " SELECT test.id, test.test_name FROM test
			JOIN examination as exam ON exam.id = test.exam_id
			JOIN exam_filter as ef ON ef.exam_id = exam.id
			WHERE (ef.stream_id IN
				(SELECT ef.stream_id FROM test 
				JOIN examination as exam ON exam.id = test.exam_id
				JOIN exam_filter as ef ON ef.exam_id = exam.id WHERE test.id = '$test_id' )) 
			AND (test.id <> '$test_id') AND (test.enable_test = 'Y')";
	
	$result = $DB->RunSelectQuery($query);
	if(($result == '') || (count($result) == 0))
		return false;
		
	return $result;
}

function getSubjectsByTeacher($teacherId)
{
	global $DB;
	
	$query = "SELECT * FROM exam_subject_teacher est 
			JOIN examination e ON e.id = exam_id 
			JOIN subject s ON s.id = subject_id 
			WHERE est.teacher_id = '$teacherId' ";
	
	$result = $DB->RunSelectQuery($query);
	
	if(($result == '') || (count($result) == 0))
		$result = array();

	$temp = array();
	foreach($result as $r)
		$temp[] = (array) $r;
		
	return $temp;
}

function getCandidateByParent($parentId)
{
	global $DB;
	
	$query = "SELECT pc.*, CONCAT_WS(' ',first_name, last_name) as candidate_name FROM parent_candidate pc 
			JOIN candidate c ON c.id = pc.candidate_id 
			WHERE pc.parent_id = '$parentId' AND is_approved = 1 ";
	
	$result = $DB->RunSelectQuery($query);
	
	if(($result == '') || (count($result) == 0))
		$result = array();

	$temp = array();
	foreach($result as $r)
		$temp[] = (array) $r;

	return $temp;
}
?>