<?php 

//An array containing mod and their search content information.
$MODDETAILSARR = array(
	'examination'=>array('exam_name_manage'),
	'test_master'=>array('name'),
	'paper'=>array('paper_title_manage'),
	'homework'=>array('name'),
	'question_master'=>array('subject_id_manage','question_type_manage', 'question_level_manage','question_title_manage'),
	'subject_master'=>array('subject_name_manage'),
	'teacher_master'=>array('name'),
	'candidate_master'=>array('candidate_id_manage', 'name', 'exam_id_manage'),
	'parent_master'=>array('name'),
	'feedback'=>array('candidate_name', 'test_name'),
	'role'=>array('role'),
	'users'=>array('keywords','roleName')
);
				
				
$MODARR	= array(
	'examination',
	'test_master',
	'paper',
	'homework',
	'question_master',
	'subject_master',
	'teacher_master',
	'candidate_master',
	'teacher_master',
	'feedback',	
	'role',
	'users'
);
?>