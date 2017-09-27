<?php 
/**
 * 	@author	:	Ashwini Agarwal
 * 	@desc	:	Common database functions
 */
defined("ACCESS") or die("Access Restricted");

class DbHelper
{
	// get candidate subject history whether he is pass or fail
	function getCandidateTestSubjectHistory($candidate_id, $exam_id)
	{
		global $DB,$frmdata;
		$query = "SELECT * FROM candidate_test_subject_history
				WHERE id IN(
					SELECT MAX(id) FROM candidate_test_subject_history
					WHERE candidate_id = '$candidate_id' AND exam_id = '$exam_id'
					GROUP BY subject_id
				)";
		
		$result=$DB->RunSelectQuery($query);
		return $result;
	}
	
	// make a database condition string for candidate failed subject from candidate test subject history array
	function getCandidateFailSubjectCondidtion($candidate_test_subject_history, $toArray = false)
	{
		global $DB,$frmdata;
		$subject_condidtion = '';
		$subject_string = array();
		$pass_subject_string = array();
		$count_fail_subject = 0;
		
		for($counter=0; $counter < count($candidate_test_subject_history); $counter++)
		{
			if(!$candidate_test_subject_history[$counter]) continue;
			if($candidate_test_subject_history[$counter]->result=='F')
			{
				$count_fail_subject++;
				$subject_string[] = $candidate_test_subject_history[$counter]->subject_id;
			}
			elseif($candidate_test_subject_history[$counter]->result=='P')
			{
				$pass_subject_string[] = $candidate_test_subject_history[$counter]->subject_id;
			}
		}
		
		if((count($subject_string) > 0) || (count($pass_subject_string) > 0))
		{
			$subjects = implode(',',array_merge($subject_string, $pass_subject_string));
			
			if($_SESSION['questionMedia'] == 'paper')
			{
				$candidate_test_subject = $DB->SelectRecords('paper_subject', "paper_id=".$_SESSION['testPaperId']." and subject_id not in ($subjects)");
			}
			else
			{
				$candidate_test_subject = $DB->SelectRecords('test_subject', "test_id=".$_SESSION['testid']." and subject_id not in ($subjects)");
			}
		}
		//echo count("");
		if(isset($candidate_test_subject) && $candidate_test_subject!='')
			for($counter=0; $counter<count($candidate_test_subject); $counter++)
			{
				$count_fail_subject++;
				$subject_string[] = $candidate_test_subject[$counter]->subject_id;
			}
		
		$_SESSION['total_fail_subject']= $count_fail_subject;
		if(count($subject_string)!=0)
		{
			$subject_string_str = implode(',', $subject_string);
			$subject_condidtion = " and subject_id in ($subject_string_str)";
		}
		
		if($toArray) return $subject_string;
		return $subject_condidtion;
	}
}
?>