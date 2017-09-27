<?php
/*############################################################################################################################
	Name : Richa verma
	Date : 15-06-2011
//##########################################################################################################################*/
try{
require_once ("xajax.inc.php");
if(file_exists("serverinc.php"))
	$xajax = new xajax("serverinc.php");
else
	$xajax = new xajax("../serverinc.php");

//this is function for geting room for a particular category
$xajax->registerFunction("showoption");
$xajax->registerFunction("showtotal");
$xajax->registerFunction("getquestion");
$xajax->registerFunction("getquestionedit");
$xajax->registerFunction("showtotalmarks");
$xajax->registerFunction("getcustomquestion");
$xajax->registerFunction("getcustomquestionedit");
$xajax->registerFunction("customMarks");
$xajax->registerFunction("gettotalmarks");
$xajax->registerFunction("deletePermission");
$xajax->registerFunction("getExamTypeIdByExamId");
$xajax->registerFunction("getExamSubject");
$xajax->registerFunction("getSubjectQuestion");
$xajax->registerFunction("makeTestListByExamId");
$xajax->registerFunction("isExamConducted");
$xajax->registerFunction("resetTotalMarks");
$xajax->registerFunction("checkMaxMarks");
$xajax->registerFunction("enableTest");
$xajax->registerFunction("archiveTest");
$xajax->registerFunction("unsetSession");

$xajax->registerFunction("testcaptchaWork");

$xajax->registerFunction("addedu");
$xajax->registerFunction("remove_edu");
$xajax->registerFunction("addexp");
$xajax->registerFunction("remove_exp");
$xajax->registerFunction("informCandidatesForTest");
$xajax->registerFunction("changeCandidateAuthority");
$xajax->registerFunction("saveCustomQuestionSession");
$xajax->registerFunction("saveGroupQuestionSession");
$xajax->registerFunction("getgroupquestion");
$xajax->registerFunction("getExamPaper");
$xajax->registerFunction("changeMaxMarksByPaper");
$xajax->registerFunction("excludeCandidate");
$xajax->registerFunction("getTestOptionsByExam");
$xajax->registerFunction("changeTeacherAuthority");
$xajax->registerFunction("changeParentAuthority");
$xajax->registerFunction("getExamSubjectOptions");
$xajax->registerFunction("addTeacherSubject");
$xajax->registerFunction("showFilteredCandidate");
$xajax->registerFunction("includeCandidate");
$xajax->registerFunction("addParentCandidate");
$xajax->registerFunction("enableHomework");
$xajax->registerFunction("informCandidatesForHomework");
$xajax->registerFunction("assignMarksToSubjectiveTypeQuestion");
$xajax->registerFunction("studentLinkRequest");
$xajax->registerFunction("sendTestReult");
}
catch (Exception $e)
{
	echo "catch ".$e->getMessage();exit;
}
?>