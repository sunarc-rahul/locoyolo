<?php
ini_set("date.timezone", 'Asia/Calcutta');
error_reporting(E_ALL);
ini_set('display_errors', 0);

include_once("start.php");
session_name('emath360_admin');
session_start();
$DB= new DBFilter();

$link = mysql_connect(HOST,USER,PASSWORD);
if ($link)
{
	Print "";
} 
else
{
	Print "No connection to the database";
}
if (!mysql_select_db(DATABASE))
{
	Print "Couldn't connect database";
} 

// Show how many option going to display when user select type of question
function showoption($type, $no_of_images='', $no_of_options='', $no_of_cols='')
{
	$objResponse = new xajaxResponse();
	$output='';
	
	if($type=='T')
	{
		$output='<table id="tbl_dashboard"><tr>
						    <td width="140" valign="top"><div>Enter Options:<span class="red">*</span></div></td>
						    <td colspan="3" valign="top">&nbsp;</td>
						  </tr>';
		
		for($counter=1;$counter<=2;$counter++)
		{
			if (isset($_SESSION['option_'.$counter]))
			{
				$value=$_SESSION['option_'.$counter];
				unset($_SESSION['option_'.$counter]);
			}
			elseif (isset($_SESSION['pre_option_'.$counter]))
			{
				$value=$_SESSION['pre_option_'.$counter];
				unset($_SESSION['pre_option_'.$counter]);
			}
			
			if($value=='' && $counter==1)
			{
				$value='True';
			}
			elseif($value=='' && $counter==2)
			{
				$value='False';
			}
			
			$output.='	<tr><td valign="top">
								<div align="right">'.$counter.'</div>
							</td> 
							<td valign="top">
								<input type="text" name ="option_'.$counter.'" class="rounded textfield required" size="30" value="'.htmlspecialchars($value).'" />
								<input name="correct_ans" type="radio" value="'.$counter.'"  ';
			
			if(isset($_SESSION['correct_ans']))
			{
				if($_SESSION['correct_ans'] == $counter)
				{
					$output .= ' checked ';
					unset($_SESSION['correct_ans']);
				}
			}
			
			$output .= ' /></td></tr>';
			$value='';
		}
		$output.='	<tr><td valign="top">&nbsp;</td>
						<td valign="top" colspan="3"><div align="left" id="mandatory"><strong>Note:</strong> Select radio button for correct answer. </div></td>
					</tr></table>';
								
	}
	elseif($type=='M')
	{
		$output='<table id="tbl_dashboard"><tr>
						    <td width="140" valign="top"><div>Enter Options:<span class="red">*</span></div></td>
						    <td valign="top">&nbsp;</td>
						  </tr>';
		for($counter=1;$counter<=5;$counter++)
		{
			if (isset($_SESSION['option_'.$counter]))
			{
				$value=$_SESSION['option_'.$counter];
				unset($_SESSION['option_'.$counter]);
			}
			elseif (isset($_SESSION['pre_option_'.$counter]))
			{
				$value=$_SESSION['pre_option_'.$counter];
				unset($_SESSION['pre_option_'.$counter]);
			}
			
			$output.='<tr><td valign="top"><div align="right">'.$counter.'</div></td> 
					<td valign="top">
								<textarea name ="option_'.$counter.'" class="rounded textfield required" style="height:50px"  id="question_title">'.htmlspecialchars($value).'</textarea></td><td>
								<input name="correct_ans[]" type="checkbox" value="'.$counter.'" ';
			
			if(isset($_SESSION['correct_ans']))
			{
				if(in_array($counter, $_SESSION['correct_ans']))
				{
					$output .= ' checked ';
				}
			}
			$output .= '/></td></tr>';
		}
		unset($_SESSION['correct_ans']);
		$output.='<tr>
						 <td valign="top">&nbsp;</td>
						 <td valign="top"><div align="left" id="mandatory"><strong>Note:</strong> Select Check Box for correct answer. </div></td>
				   </tr></table>';
	}
	elseif($type == 'I')
	{
		$no_of_images = ($no_of_images != '') ? $no_of_images : 3;
		$no_of_options = ($no_of_options != '') ? $no_of_options : 4;
		
		$output='<input type="hidden" name="image_type" id="image_type" value=""/>';
		//$output.='<iframe id="ifrm" name="ifrm" src="" width="0" heigth="0" border="0" style="display:none;"></iframe>';
		
		$output.='<table id="image_tab" border="0">
					<tr>
					 	<td valign="top" width="140">
					 		<div>
					 			Upload Images:
					 			<span class="red">*</span>
					 		</div>
					 	</td>
						<td valign="top">
							&nbsp;
						</td>
					</tr>';
		
		for($counter=1;$counter<=$no_of_images;$counter++)
		{
			if(isset($_SESSION['fileObject_image_'.$counter]))
			{
				$_SESSION['image_'.$counter] = $_SESSION['fileObject_image_'.$counter]['final_path'];
			}
			$value = '';
			
			$output.='<tr id="tr_image_'.$counter.'">
						<td valign="top">
							<div align="right">
								'.$counter.'
							</div>
						</td> 
						<td valign="top">
							
								<input type="file" name ="image_'.$counter.'" id="image_'.$counter.'" class="rounded textfield required" size="30" onchange="uploadTempFile(this.id);"/>&nbsp;';
			
			$output .='<input name="question_mark" type="radio" value="'.$counter.'" ';
			
			if(isset($_SESSION["question_mark"]))
			{
				if($_SESSION["question_mark"] == $counter)
				{
					$output .= ' checked ';
					//unset($_SESSION["question_mark"]);
				}
			}
			$output .= '/>&nbsp;';
			
			$output .='<span style="max-width:25px;max-height:25px;" id="img_image_'.$counter.'">';
			
			if(isset($_SESSION['image_'.$counter]))
			{
				$img = '/uploadfiles/' . $_SESSION['image_'.$counter];
				
				if(file_exists(ADMINROOT . $img))
				$output.=	'<img style="width:20px;heigth:20px;" src = ' . ADMINURL . $img . ' />';
				
				unset($_SESSION['image_'.$counter]);
			}
			elseif(isset($_SESSION['pre_image_'.$counter]))
			{
				$img = '/uploadfiles/' . $_SESSION['pre_image_'.$counter];
				
				if(file_exists(ADMINROOT . $img))
				$output.=	'<img style="width:20px;heigth:20px;" src = ' . ADMINURL . $img . ' />';
				
				//unset($_SESSION['pre_image_'.$counter]);
			}
			
			$output .=		'</span>
							</td>
						</tr>';
			
		}
		
		$output.='<tr id="image_add_more" >
					<td valign="top">&nbsp;</td>
					<td valign="top"><a href="javascript:void(0);" onclick="addMoreImage();" >Add More</a>&nbsp;&nbsp;&nbsp
					<span id="remove_image" valign="top">';
		
		if($no_of_images > 3)
		{			
			$output .='<a href="javascript:void(0);" onclick="removeLastImage();" >Remove Last</a>';
		} 
		
		$output .= '</span></td></tr>';
		
		$output.='<tr id="question_note">	 
						<td valign="top">&nbsp;</td>
						 <td valign="top" colspan="3">
						 	<div align="left" id="mandatory">
						 		<strong>Note:</strong> Select radio button to set image as question mark. &nbsp;
						 		<span><a href="javascript:void(0);" onclick="resetQMark();">Reset Question Mark</a></span><br>
						 		Please upload image in .JPG, .GIF, .PNG and .JPEG format.<br> 
						 		Image size should not be more than 2MB.
						 	</div>
						 </td>
				   </tr></table>';

		$output.='<table border="0">
					<tr>
					 	<td valign="top" width="140">
					 		<div>
					 			Upload Options:
					 			<span class="red">*</span>
					 		</div>
					 	</td>
						<td valign="top">
							&nbsp;
						</td>
					</tr>';
		for($counter=1;$counter<=$no_of_options;$counter++)
		{
			if(isset($_SESSION['fileObject_option_'.$counter]))
			{
				$_SESSION['option_'.$counter] = $_SESSION['fileObject_option_'.$counter]['final_path'];
			}
			$value="";
			
			$output.='<tr id="tr_option_'.$counter.'">
						<td valign="top">
							<div align="right">
								'.$counter.'
							</div>
						</td> 
						<td width="">
							<input type="file" name ="option_'.$counter.'" id="option_'.$counter.'" class="rounded textfield required" size="30" onchange="uploadTempFile(this.id);"/>';
							
			$output .='<input name="correct_ans[]" type="checkbox" value="'.$counter.'" ';
			
			if(isset($_SESSION['correct_ans']))
			{
				if(in_array($counter, $_SESSION['correct_ans']))
				{
					$output .= ' checked ';
				}
			}
			$output .= '/>&nbsp;<span style="max-width:25px;max-height:25px;" id="img_option_'.$counter.'">';
			
			if(isset($_SESSION['option_'.$counter]))
			{
				$img = '/uploadfiles/' . $_SESSION['option_'.$counter];
				
				if(file_exists(ADMINROOT . $img))
				$output.='<img style="width:20px;heigth:20px;" src = ' . ADMINURL . $img . ' />';
				
				unset($_SESSION['option_'.$counter]);
			}
			elseif(isset($_SESSION['initial_answer_'.$counter]))
			{
				$img = '/uploadfiles/' . $_SESSION['initial_answer_'.$counter];
				
				if(file_exists(ADMINROOT . $img))
				$output.='<img style="width:20px;heigth:20px;" src = ' . ADMINURL . $img . ' />';
				
				//unset($_SESSION['initial_answer_'.$counter]);
				unset($_SESSION['pre_option_'.$counter]);
			}
			$output .= '</span>';
			$output .= '</td></tr>';
		}
		unset($_SESSION['correct_ans']);
		
		$output.='<tr id="option_add_more" >
					<td valign="top">&nbsp;</td>
					<td valign="top" colspan="3"><a href="javascript:void(0);" onclick="addMoreOption();" >Add More</a>&nbsp;&nbsp;&nbsp
					<span id="remove_option" valign="top">';
		
		if($no_of_options > 4)
		{
			$output .= '<a href="javascript:void(0);" onclick="removeLastOption();" >Remove Last</a>';
		}
		
		$output.= '</span></td></tr>';
		
		$output.='<tr id="option_note">
						<td valign="top">&nbsp;</td>
						<td valign="top" colspan="3"><div align="left" id="mandatory"><strong>Note:</strong> Select radio button for correct answer. <br>Please upload image in .JPG, .GIF, .PNG and .JPEG format.<br> Image size should not be more than 2MB.</div></td>
				   </tr></table>';
	}
	elseif($type == 'S')
	{
		$output = '';
	}
	
	elseif($type == 'MT')
	{
		$no_of_cols = ($no_of_cols != '') ? $no_of_cols : 3;
		$output ='<br><table id="match_tab" border="0">
					<tr>
					 	<td valign="top" width="140">
					 		<div>
					 			Enter Coloumns:
					 			<span class="red">*</span>
					 		</div>
					 	</td>
						<td valign="top">
							&nbsp;
						</td>
					</tr>
					<tr>
						<td valign="top"></td>
						<td align="center"><b>Left</b></td>
						<td align="center"><b>Right</b></td>
					</tr>';
		
		for($counter = 1; $counter <= $no_of_cols; $counter++)
		{
			$left_col = '';
			if(isset($_SESSION['left_col_' . $counter]))
			{
				$left_col = $_SESSION['left_col_' . $counter]; 
				unset($_SESSION['left_col_' . $counter]);
			}

			$right_col = '';
			if(isset($_SESSION['right_col_' . $counter]))
			{
				$right_col = $_SESSION['right_col_' . $counter];
				unset($_SESSION['right_col_' . $counter]); 
			}
			
			$output.='<tr id="tr_col_'.$counter.'">
						<td valign="">
							<div align="right">
								'.$counter.'
							</div>
						</td> 
						<td width="">
							<input type="test" name ="left_col_'.$counter.'" id="left_col_'.$counter.'" 
								class="rounded textfield required" size="30" value="'. $left_col .'"/>
						</td> 
						<td width="">
							<input type="test" name ="right_col_'.$counter.'" id="right_col_'.$counter.'" 
								class="rounded textfield required" size="30" value="'. $right_col .'"/>
						</td>
					</tr>';
		}
		
		$output.='<tr id="col_add_more" >
					<td valign="top">&nbsp;</td>
					<td valign="top" colspan="3"><a href="javascript:void(0);" onclick="addMoreCol();" >Add More</a>&nbsp;&nbsp;&nbsp
					<span id="remove_col" valign="top">';
		
		if($no_of_cols > 2)
		{
			$output .= '<a href="javascript:void(0);" onclick="removeLastCol();" >Remove Last</a>';
		}
		
		$output.= '</span></td></tr>';
		$output.='<tr id="option_note">
						<td valign="top">&nbsp;</td>
						<td valign="top" colspan="3">
							<div align="left" id="mandatory">
								<strong>Note:</strong> Please enter match value of left coloumn in adjacent right column.
							</div>
						</td>
				   </tr></table>';
		
		$output .= '<table><br>';
	}
	//$objResponse->addAlert($output);
	
	$objResponse->addAssign('opt','innerHTML',$output);
	return $objResponse;
}

// Display how many questions according to their marks available in bank
function getquestion($cust_que_set, $subject_id='')
{
	global $DB;
	$objResponse = new xajaxResponse();
//$objResponse->addAlert($exam_id);
	if(isset($_SESSION['total_paper_set']))
	{
		unset($_SESSION['total_paper_set']);
	}
	if(isset($_SESSION['beg']))
	{
		unset($_SESSION['beg']);
	}
	if(isset($_SESSION['int']))
	{
		unset($_SESSION['int']);
	}
	if(isset($_SESSION['high']))
	{
		unset($_SESSION['high']);
	}

//	$no_subjective = ' AND ( question_type <> "S" ) ';

	$cust_que_set = array();
	if(is_array($_SESSION['totalCustomQuestion']))
	{
		foreach($_SESSION['totalCustomQuestion'] as $key => $val)
		{
			if(is_array($_SESSION['checkedCustomQuestion']) && in_array($key,$_SESSION['checkedCustomQuestion']))
			{
				$cust_que_set[] = $key;
			}
		}
	}
	$cust_que_set = implode(',',$cust_que_set);

	if($cust_que_set!='')
		$que_set_condition = " and id not in (".$cust_que_set.")";
	else
		$que_set_condition="";
	
	if($subject_id!='')
	{
		$common_query_cond = " (subject_id = $subject_id ) ";
		$html_onchange_function = 'subjectQuestionRestrict(this.value,this.name)';
	}
	
	$common_query_cond .= $no_subjective;
	
	//Fetch Beginner level Question
	$boutput='';
	
	$beg_marks_sql = "select distinct(marks) from question where $common_query_cond and group_question_id = 0 and  question_level= 'B' $que_set_condition";
	
	//$objResponse->addAlert($beg_tmarks_sql);
	$beg_mark_result = mysql_query($beg_marks_sql);
	$noQuestionFound = true;
	$boutput.='<fieldset class="rounded"><legend>Beginner</legend><table align="center"><tr><td></td></tr>';
	while($beg_mark=mysql_fetch_array($beg_mark_result))
	{
		if($beg_mark!='')
		{
			$marks=	$beg_mark['marks'];
			$beg_tmarks_sql ="select count(marks) as count from question where ".$common_query_cond." and question_level= 'B' and  group_question_id = 0 and marks=".$marks.$que_set_condition;
			
			//$objResponse->addAlert($beg_tmarks_sql);
			$beg_tmark_result=mysql_query($beg_tmarks_sql);
			$beg_tmark=mysql_fetch_array($beg_tmark_result);
			$total_beg=$beg_tmark['count'];
			$_SESSION['beg'][$marks]=$total_beg;

			$boutput.='<tr><td>'.$marks.' x <input type="text" maxlength="2" value="'.$_SESSION['total']["beg_".$marks].'" size="1" id="beg_'.$marks.'_'.$total_beg.'" name="beg_'.$marks.'_'.$total_beg.'"  onchange="'.$html_onchange_function.';"> / '.$total_beg.'</td></tr>';
			unset($_SESSION['beg-'.$marks]);
			$noQuestionFound = false;
		}
		
	}
	
	if($noQuestionFound)
	{
		$boutput.="<tr><td>No Question available.</td></tr><tr><td></td></tr>";	
	}
	
	$boutput.='</table></fieldset>';
	
	//Fetech Intermediate level Question
	$ioutput='';
	
	$int_marks_sql ="select distinct(marks) from question where ".$common_query_cond." and group_question_id = 0 and  question_level= 'I'".$que_set_condition;
	
	$int_mark_result=mysql_query($int_marks_sql);
	$noQuestionFound = true;
	$ioutput.='<fieldset class="rounded"><legend>Intermediate</legend><table align="center"><tr><td></td></tr>';
	while($int_mark=mysql_fetch_array($int_mark_result))
	{	
		
		$marks=	$int_mark['marks'];
		
			$int_tmarks_sql ="select count(marks) as count from question where ".$common_query_cond." and group_question_id = 0 and question_level= 'I' and marks=".$marks.$que_set_condition;
		
		$int_tmark_result=mysql_query($int_tmarks_sql);
		$int_tmark=mysql_fetch_array($int_tmark_result);
		$total_int=$int_tmark['count'];
		$_SESSION['int'][$marks]=$total_int;
		
		$ioutput.='<tr><td>'.$marks.' x <input type="text" maxlength="2" size="1" value="'.$_SESSION['total']["int_".$marks].'" id="int_'.$marks.'_'.$total_int.'" name="int_'.$marks.'_'.$total_int.'"  onchange="'.$html_onchange_function.';"> / '.$total_int.'</td></tr>';
		unset($_SESSION['int-'.$marks]);
		$noQuestionFound = false;
	}
	if($noQuestionFound)
	{
		$ioutput.="<tr><td>No Question available.</td></tr><tr><td></td></tr>";	
	}
	$ioutput.='</table></fieldset>';
	
	//Fetech Higher level Question
	$houtput='';
	
	$high_marks_sql ="select distinct(marks) from question where ".$common_query_cond." and group_question_id = 0 and  question_level= 'H'".$que_set_condition;
	
	$high_mark_result=mysql_query($high_marks_sql);
	$noQuestionFound = true;
	$houtput.='<fieldset class="rounded"><legend>Higher</legend><table align="center"><tr><td></td></tr>';
	while($high_mark=mysql_fetch_array($high_mark_result))
	{
		$marks=	$high_mark['marks'];
		
		$high_tmarks_sql ="select count(marks) as count from question where ".$common_query_cond." and group_question_id = 0 and  question_level= 'H' and marks=".$marks.$que_set_condition;
		
		//$objResponse->addAlert($high_tmarks_sql);
		$high_tmark_result=mysql_query($high_tmarks_sql);
		$high_tmark=mysql_fetch_array($high_tmark_result);
		$total_high=$high_tmark['count'];
		$_SESSION['high'][$marks]=$total_high;
		
		$houtput.='<tr><td>'.$marks.' x <input type="text" maxlength="2" size="1" value="'.$_SESSION['total']["high_".$marks].'" id="high_'.$marks.'_'.$total_high.'" name="high_'.$marks.'_'.$total_high.'"  onchange="'.$html_onchange_function.'"> / '.$total_high.'</td></tr>';
		unset($_SESSION['high-'.$marks]);
		$noQuestionFound = false;
	}
	
	if($noQuestionFound)
	{
		$houtput.="<tr><td>No Question available.</td></tr><tr><td></td></tr>";	
	}

	$houtput.='</table></fieldset>';
	
	$message='<span style="color:red;font-size:10px">Marks x No. of question? / Question available</span>';

	$objResponse->addAssign('subject_message','innerHTML',$message);
	$objResponse->addAssign('subject_beg_level','innerHTML',$boutput);
	$objResponse->addAssign('subject_int_level','innerHTML',$ioutput);
	$objResponse->addAssign('subject_high_level','innerHTML',$houtput);
	
	return $objResponse;
}

// Show all questions belongs to particular stream for custom
function getcustomquestion($subject_id='', $filter = '',$exam_id='')
{
	global $DB;
	$objResponse = new xajaxResponse();
	
	if(isset($_SESSION['total_paper_set']))
	{
		$objResponse->addAssign('total_mark','innerHTML',0);
		unset($_SESSION['total_paper_set']);
	}
	
	if($subject_id!='')
	{
		$common_query_cond = " (subject_id = $subject_id) ";
		$html_onclick_function = 'showSubjectCustomTotal(this);';
	}
	
	$html_onclick_function .= 'xajax_saveCustomQuestionSession(this.value, this.checked);';
	$output='';

	$question_sql ="select * from question where group_question_id=0 and ".$common_query_cond.$no_subjective;
	
	$question_sqlresult = mysql_query($question_sql);

	$output .= '<div style="max-height:300px;overflow:auto;">'
			.	'<table style="width:100%;" class="border" align="center">'
			.	'<tr>'
			.	'<td class="border" style="color:#AF5403;text-align:center;">Select</td>'
			.	'<td class="border" style="color:#AF5403">Question</td>'
			.	'<td class="border" style="color:#AF5403;text-align:center;">Marks</td>'
			.	'<td class="border" style="color:#AF5403;text-align:center;" >Level</td>'
			.	'</tr>';
			
	$sub_output = '';
	$_SESSION['totalCustomQuestion'] = '';
	if(!isset($_SESSION['checkedCustomQuestion'])) $_SESSION['checkedCustomQuestion'] = array();
	
	while($get_quest = mysql_fetch_array($question_sqlresult))
	{
		$marks = $get_quest['marks'];
		$level = $get_quest['question_level'];
		
		if ($level=='B')
			$que_level = "beg";
			
		elseif ($level=='I')
			$que_level = "int";
			  
		elseif ($level=='H')
			$que_level = "high";
		
		$_SESSION['total'][$que_level.'_'.$marks.'_totalQuestion'] = 0;
	}
	
	$question_sqlresult = mysql_query($question_sql);
	while($get_quest = mysql_fetch_array($question_sqlresult))
	{
		$marks = $get_quest['marks'];
		$level = $get_quest['question_level'];
		$qid = $get_quest['id'];
		
		if($level=='B')
		{
			$level="Beginner";
			$que_level = "beg";    //=====this variable is for session which is only used for count selected question
	
			//================ to get total question of beginner level=====================================================
			if(isset($_SESSION['total']['beg_'.$marks.'_totalQuestion']) 
				&& ($_SESSION['total']['beg_'.$marks.'_totalQuestion'] > 0))
			{	
				$_SESSION['total']['beg_'.$marks.'_totalQuestion']++;
			}
			else
				$_SESSION['total']['beg_'.$marks.'_totalQuestion']=1;
		}
		if($level=='I')
		{
			$level="Intermediate";
			$que_level = "int";  

			//================ to get total question of Intermediate level=====================================================
			if(isset($_SESSION['total']['int_'.$marks.'_totalQuestion']) 
				&& ($_SESSION['total']['int_'.$marks.'_totalQuestion'] > 0))
			{	
				$_SESSION['total']['int_'.$marks.'_totalQuestion']++;
			}
			else
				$_SESSION['total']['int_'.$marks.'_totalQuestion']=1;
			
		}
		if($level=='H')
		{
			$level="Higher";
			$que_level = "high";  

			//================ to get total question of Higher level=====================================================
			if(isset($_SESSION['total']['high_'.$marks.'_totalQuestion']) 
				&& ($_SESSION['total']['high_'.$marks.'_totalQuestion'] > 0))
			{
				$_SESSION['total']['high_'.$marks.'_totalQuestion']++;
			}
			else
				$_SESSION['total']['high_'.$marks.'_totalQuestion']=1;
		}
		
		$_SESSION['totalCustomQuestion'][$qid] = $qid.'_'.$marks.'_'.$level;
		
		if (isset($_SESSION['custom_question_id_set'])) 
		{
			$custom_que_id_array = explode(',',$_SESSION['custom_question_id_set']);
			if (in_array($qid,$custom_que_id_array))
			{
				if(!(is_array($filter) && (count($filter) > 0)))
				$_SESSION['total'][$que_level."_".$marks.'_question']++;
				$_SESSION['total'][$qid]=1;
				$_SESSION['checkedCustomQuestion'][$qid] = $qid;
			}
		}
		
		if($_SESSION['total'][$qid] != 1)
		{
			if(is_array($_SESSION['checkedCustomQuestion']) && in_array($qid, $_SESSION['checkedCustomQuestion']))
			{
				if(!(is_array($filter) && (count($filter) > 0)))
				$_SESSION['total'][$que_level."_".$marks.'_question']++;
				$_SESSION['total'][$qid]=1;
			}
		}
	}

	if(is_array($filter))
	{
		if($filter['question_title'] != '')
			$question_sql .= ' AND (question_title LIKE "%'.mysql_real_escape_string($filter['question_title']).'%") ';
		if($filter['marks'] != '')
			$question_sql .= ' AND (marks = "'.mysql_real_escape_string($filter['marks']).'") ';
		if($filter['level'] != '')
			$question_sql .= ' AND (question_level = "'.$filter['level'].'") ';
	}
	
	$question_sql .= ' ORDER BY marks desc';
	
	$question_sqlresult = mysql_query($question_sql);
	while($get_quest=mysql_fetch_array($question_sqlresult))
	{
		$marks = $get_quest['marks'];
		$question = ($get_quest['question_title']);
		$qid = $get_quest['id'];
		$level = $get_quest['question_level'];
		$checked='';

		if($level=='B')
		{
			$level="Beginner";
			$que_level = "beg";
		}
		if($level=='I')
		{
			$level="Intermediate";
			$que_level = "int";  
		}
		if($level=='H')
		{
			$level="Higher";
			$que_level = "high";  
		}
		
		if (isset($_SESSION['custom_question_id_set'])) 
		{
			$custom_que_id_array = explode(',',$_SESSION['custom_question_id_set']);
			if (in_array($qid,$custom_que_id_array))
			{
				$checked='checked';
			}
		}
		
		if(is_array($_SESSION['checkedCustomQuestion']) && in_array($qid,$_SESSION['checkedCustomQuestion']))
			$checked = 'checked';

		$val = $qid.'_'.$marks.'_'.$level;
		
		$sub_output .= '<tr>'
					.	'<td class="border" style="text-align:center">'
					.	"<input type='checkbox' $checked name='custom_question[]' id='custom_question' value='$val' onclick='$html_onclick_function'>"
					.	'</td>'
					.	'<td class="border">'.$question.'</td>'
					.	'<td class="border" style="text-align:center">'.$marks.'</td>'
					.	'<td class="border" style="text-align:center">'.$level.'</td>'
					.	'</tr>';
	}
	
	if($sub_output == '')
	{
		$sub_output = "<tr><td colspan=4>No Question available.</td></tr>";
	}

	$output.= $sub_output.'</table></div>';
	if($subject_id!='')
	{
		$objResponse->addAssign('subject_showques','innerHTML',$output);
	}
	
	return $objResponse;
}

function saveCustomQuestionSession($value, $isChecked = '', $clearAll = '')
{
	$objResponse = new xajaxResponse();
	$val = explode('_',$value);

	if($clearAll)
	{
		$_SESSION['checkedCustomQuestion'] = '';
		unset($_SESSION['checkedCustomQuestion']);
		return $objResponse;
	}
	
	if($_SESSION['checkedCustomQuestion'][$val[0]] == -1)
	{
		$_SESSION['checkedCustomQuestion'][$val[0]] = '';
		unset($_SESSION['checkedCustomQuestion'][$val[0]]);
		return $objResponse;
	}
	
	if($isChecked == 'true')
	{
		$_SESSION['checkedCustomQuestion'][$val[0]] = $val[0];
	}
	else
	{
		$_SESSION['checkedCustomQuestion'][$val[0]] = '';
		unset($_SESSION['checkedCustomQuestion'][$val[0]]);
	}

	return $objResponse;
}

function saveGroupQuestionSession($value, $isChecked = '', $clearAll = '')
{
	$objResponse = new xajaxResponse();
	$val = explode('_',$value);
	//$objResponse->addAlert('saveGroupQuestionSession');
	if($clearAll)
	{
		$_SESSION['checkedGroupQuestion'] = '';
		unset($_SESSION['checkedGroupQuestion']);
		return $objResponse;
	}
	
	if($_SESSION['checkedGroupQuestion'][$val[0]] == -1)
	{
		$_SESSION['checkedGroupQuestion'][$val[0]] = '';
		unset($_SESSION['checkedGroupQuestion'][$val[0]]);
		return $objResponse;
	}
	
	if($isChecked == 'true')
	{
		$_SESSION['checkedGroupQuestion'][$val[0]] = $val[0];
	}
	else
	{
		$_SESSION['checkedGroupQuestion'][$val[0]] = '';
		unset($_SESSION['checkedGroupQuestion'][$val[0]]);
	}

	return $objResponse;
}

function getgroupquestion($subject_id='', $filter = '',$exam_id='')
{
	global $DB;
	$objResponse = new xajaxResponse();
        //$objResponse->addAlert($exam_id);
        
	if(isset($_SESSION['total_paper_set']))
	{
		$objResponse->addAssign('total_mark','innerHTML',0);
		unset($_SESSION['total_paper_set']);
	}
	
	if($subject_id!='')
	{
		$common_query_cond = " (subject_id = $subject_id) ";
		$html_onclick_function = 'showSubjectGroupTotal(this);';
	}
	
	$html_onclick_function .= 'xajax_saveGroupQuestionSession(this.value, this.checked);';
	$output='';

	$question_sql ="select * from group_questions where ".$common_query_cond.$no_subjective;
	$question_sqlresult = mysql_query($question_sql);

	$output .= '<div style="max-height:300px;overflow:auto;">'
			.	'<table style="width:100%;" class="border" align="center">'
			.	'<tr>'
			.	'<td class="border" style="color:#AF5403;text-align:center;">Select</td>'
			.	'<td class="border" style="color:#AF5403">Question</td>'
			.	'<td class="border" style="color:#AF5403;text-align:center;">Marks</td>'
			.	'</tr>';
			
	$sub_output = '';
	$_SESSION['totalGroupQuestion'] = '';
	if(!isset($_SESSION['checkedGroupQuestion'])) $_SESSION['checkedGroupQuestion'] = array();
	
	while($get_quest = mysql_fetch_array($question_sqlresult))
	{
		$marks = $get_quest['marks'];
		
		$_SESSION['total'][$que_level.'_'.$marks.'_totalQuestion'] = 0;
	}
	
	$question_sqlresult = mysql_query($question_sql);
	while($get_quest = mysql_fetch_array($question_sqlresult))
	{
		$marks = $get_quest['marks'];
		$qid = $get_quest['id'];
		
		$_SESSION['totalGroupQuestion'][$qid] = $qid.'_'.$marks;
		
		if (isset($_SESSION['group_question_id_set'])) 
		{
			$group_que_id_array = explode(',',$_SESSION['group_question_id_set']);
			if (in_array($qid,$group_que_id_array))
			{
				if(!(is_array($filter) && (count($filter) > 0)))
				$_SESSION['total'][$marks.'_question']++;
				$_SESSION['total'][$qid]=1;
				$_SESSION['checkedGroupQuestion'][$qid] = $qid;
			}
		}
		
		if($_SESSION['total'][$qid] != 1)
		{
			if(is_array($_SESSION['checkedGroupQuestion']) && in_array($qid, $_SESSION['checkedGroupQuestion']))
			{
				if(!(is_array($filter) && (count($filter) > 0)))
				$_SESSION['total'][$marks.'_question']++;
				$_SESSION['total'][$qid]=1;
			}
		}
	}

	if(is_array($filter))
	{
		if($filter['question_title'] != '')
			$question_sql .= ' AND (question_title LIKE "%'.mysql_real_escape_string($filter['question_title']).'%") ';
		if($filter['marks'] != '')
			$question_sql .= ' AND (marks = "'.mysql_real_escape_string($filter['marks']).'") ';
	}
        
	$question_sql .= ' ORDER BY marks desc ';

	$question_sqlresult = mysql_query($question_sql);
	while($get_quest=mysql_fetch_array($question_sqlresult))
	{
		$marks = $get_quest['marks'];
		$start = $get_quest['start'];
		$end = $get_quest['end'];
		$question = ($get_quest['question_title']);
		$qid = $get_quest['id'];
		$checked='';

		if (isset($_SESSION['group_question_id_set'])) 
		{
			$group_que_id_array = explode(',',$_SESSION['group_question_id_set']);
			if (in_array($qid,$group_que_id_array))
			{
				$checked='checked';
			}
		}
		
		if(is_array($_SESSION['checkedGroupQuestion']) && in_array($qid,$_SESSION['checkedGroupQuestion']))
			$checked = 'checked';
		

		$val = $qid.'_'.$marks;

		$sub_output .= '<tr>'
					.	'<td class="border" style="text-align:center">'
					.	"<input type='checkbox' $checked name='group_question[]' id='group_question' value='$val' onclick='$html_onclick_function'>"
					.	'</td>'
					.	'<td class="border"><b>Direction:('.$start.'-'.$end.')</b>'.$question.'</td>'
					.	'<td class="border" style="text-align:center">'.$marks.'</td>'
					.	'</tr>';
	}	
        
	if($sub_output == '')
	{
		$sub_output = "<tr><td colspan=4>No Question available.</td></tr>";
	}

	$output.= $sub_output . '</table></div>';
	if($subject_id!='')
	{
		$objResponse->addAssign('subjectq_showques','innerHTML',$output);
	}
	
	return $objResponse;
}

function gettotalmarks($mode, $current_marks, $value, $random_mark='', $totalques='', $level='', $subject_id='', $group_questions='')
{	
	$objResponse = new xajaxResponse();
	$total=0;
	
		//$objResponse->addAlert($group_questions);
			
	if($mode=='add')
	{
		$current_marks = 0;
		// condition when enter custom marks
		
		if($level=='')
		{
			if($group_questions == 'G')
		{
			$marks_id=explode('_',$value);
			$qid=$marks_id[0];
			$marks=$marks_id[1];
			$ischecked=$marks_id[2];
		}
		else
		{
			$marks_id=explode('_',$value);
			$qid=$marks_id[0];
			$marks=$marks_id[1];
			$level=$marks_id[2];
			$ischecked=$marks_id[3];
		}
		
			
			//$marks_id=explode('_',$value);
			//$qid=$marks_id[0];
			//$marks=$marks_id[1];
			//$level=$marks_id[2];
			if($level=='Beginner')
			{
				$level="beg";
			}
			elseif($level=='Intermediate')
			{
				$level="int";
			}
			elseif($level=='Higher')
			{
				$level="high";
			}
			//$objResponse->addAlert($value);
			//$ischecked=$marks_id[2];
		//	$objResponse->addAlert($ischecked);
			if($ischecked=='check')
			{
				
				if(($_SESSION['total'][$level."_".$marks."_totalQuestion"]!='') 
						&& ($_SESSION['total'][$level."_".$marks.'_question']!='') 
						&& ($_SESSION['total'][$level."_".$marks."_totalQuestion"] 
							<= $_SESSION['total'][$level."_".$marks.'_question']))
				{
					$objResponse->addAlert("Your question limit has been completed.");
					$objResponse->addScriptCall('unselectElement',$marks_id[0].'_'.$marks_id[1].'_'.$marks_id[2]);
					
					$_SESSION['checkedCustomQuestion'][$qid] = -1;
					return $objResponse;
				}
				elseif (isset($_SESSION['total'][$level."_".$marks.'_question']) && $_SESSION['total'][$level."_".$marks.'_question']>0)
				{
					$_SESSION['total'][$level."_".$marks.'_question']++;
					$_SESSION['total'][$qid]=1;
				}
				else 
				{
					$_SESSION['total'][$level."_".$marks.'_question']=1;
					$_SESSION['total'][$qid]=1;
				}
				//$objResponse->addAlert($current_marks);
				$total = ($current_marks+$marks);
			}
			else
			{	
				if(isset($_SESSION['total']['custom_total_que']) && $_SESSION['total']['custom_total_que']>0)
				{
					$_SESSION['total']['custom_total_que']--;
				}
				if(isset($_SESSION['total']['group_total_que']) && $_SESSION['total']['group_total_que']>0)
				{
					$_SESSION['total']['group_total_que']--;
				}
				
				if(isset($_SESSION['total'][$qid]) && $_SESSION['total'][$qid]==1)
				{
					$_SESSION['total'][$level."_".$marks.'_question']--;
					unset($_SESSION['total'][$qid]);
				}
			//	$objResponse->addAlert($marks);
			
				$total = $current_marks-$marks;
				
			}
			 if($group_questions!='G')
			 {//$objResponse->addAlert($group_questions.'heret');
				if($subject_id)
				{
					$objResponse->addScriptCall('findSubjectNumofQues','','');
				}
			}
		}
		elseif($_SESSION['total'][$level."_".$random_mark]!='')
		{
			$total = $current_marks-$random_mark*$_SESSION['total'][$level."_".$random_mark];
			if($value<=$_SESSION[$level][$random_mark])
			{
				$_SESSION['total'][$level."_".$random_mark] = $value;
				$total = $total+$random_mark*$value;
				if(isset($_SESSION['total'][$level."_".$random_mark.'_question']))
					$_SESSION['total'][$level."_".$random_mark.'_question']-=$_SESSION[$level][$random_mark]-$value;
			}
			else
			{
				if(isset($_SESSION['total'][$level."_".$random_mark.'_question']))
					$_SESSION['total'][$level."_".$random_mark.'_question']-=$_SESSION['total'][$level."_".$random_mark];
				$_SESSION['total'][$level."_".$random_mark] = '';
			}
		}
		elseif($value!='' && $level!='' && $value<=$_SESSION[$level][$random_mark])		//======================condition when enter random marks======================================
		{
			$total = $current_marks+$random_mark*$value;
			$_SESSION['total'][$level."_".$random_mark]=$value;
		
			if(isset($_SESSION['total'][$level."_".$random_mark.'_question']))
			{
				$_SESSION['total'][$level."_".$random_mark.'_question']+=$value;
			}
			else
			{
				$_SESSION['total'][$level."_".$random_mark.'_question']=$value;
			}
		}
		else
		{
			$total = $current_marks;
		}
		//$objResponse->addAlert($mode);
		if($group_questions == 'G')
		$objResponse->addScriptCall('updateTotalMarksGroup', $total);
		else
		$objResponse->addScriptCall('updateTotalMarks', $total);
	}
	elseif ($mode=='random_reset')
	{	
		if(isset($_SESSION['total']))
		{
			unset($_SESSION['total']);
		}
		
		$value = '';
		$value_arr = array();
		if(is_array($_SESSION['totalCustomQuestion']))
		{
			foreach($_SESSION['totalCustomQuestion'] as $key => $val)
			{
				$checked = 'uncheck';
				if(is_array($_SESSION['checkedCustomQuestion']) && in_array($key,$_SESSION['checkedCustomQuestion']))
				{
					$checked = 'check';
				}
				$value_arr[] = $val.'_'.$checked;
				$value .= $val.'_'.$checked.',';
			}
		}
		
		if(is_array($_SESSION['totalGroupQuestion']))
		{
			foreach($_SESSION['totalGroupQuestion'] as $key => $val)
			{
				$checked = 'uncheck';
				if(is_array($_SESSION['checkedGroupQuestion']) && in_array($key,$_SESSION['checkedGroupQuestion']))
				{
					$checked = 'check';
				}
				$value_arr[] = $val.'_'.$checked;
				$value .= $val.'_'.$checked.',';
			}
		}
		
		$value_arr=explode(',',$value);
		for($counter=0; $counter<count($value_arr); $counter++)
		{
			$marks_id=explode('_',$value_arr[$counter]);
			$qid=$marks_id[0];
			$marks=$marks_id[1];
			$level=$marks_id[2];
			$ischecked=$marks_id[3];
			if($level=='Beginner')
			{
				$level="beg";
				if(isset($_SESSION['total']['beg_'.$marks.'_totalQuestion']) && $_SESSION['total']['beg_'.$marks.'_totalQuestion']>0)
					$_SESSION['total']['beg_'.$marks.'_totalQuestion']++;
				else
					$_SESSION['total']['beg_'.$marks.'_totalQuestion']=1;
				
			}
			elseif($level=='Intermediate')
			{
				$level="int";
				if(isset($_SESSION['total']['int_'.$marks.'_totalQuestion']) && $_SESSION['total']['int_'.$marks.'_totalQuestion']>0)
					$_SESSION['total']['int_'.$marks.'_totalQuestion']++;
				else
					$_SESSION['total']['int_'.$marks.'_totalQuestion']=1;
				
			}
			elseif($level=='Higher')
			{
				$level="high";
				if(isset($_SESSION['total']['high_'.$marks.'_totalQuestion']) && $_SESSION['total']['high_'.$marks.'_totalQuestion']>0)
					$_SESSION['total']['high_'.$marks.'_totalQuestion']++;
				else
					$_SESSION['total']['high_'.$marks.'_totalQuestion']=1;
				
			}
			
			if($ischecked=='check')
			{
				if (isset($_SESSION['total'][$level."_".$marks.'_question']) && $_SESSION['total'][$level."_".$marks.'_question']>0)
				{
					$_SESSION['total'][$level."_".$marks.'_question']++;
					$_SESSION['total'][$qid]=1;
				}
				else 
				{
					$_SESSION['total'][$level."_".$marks.'_question']=1;
					$_SESSION['total'][$qid]=1;
				}
				$total = $total+$marks;
			}
		}
		
		if($subject_id!='')
		{	
			$objResponse->addAssign('subject_total_mark','innerHTML',$total);
		}
	}
	elseif ($mode=='custom_reset')
	{
		if(isset($_SESSION['beg']))
		{
			foreach($_SESSION['beg'] as $key=>$val)
			{
				if(isset($_SESSION['total']['beg_'.$key.'_totalQuestion']))
				{
					unset($_SESSION['total']['beg_'.$key.'_totalQuestion']);
				}
				
				if(isset($_SESSION['total']['beg_'.$key.'_question']))
				{
					unset($_SESSION['total']['beg_'.$key.'_question']);
					$_SESSION['total']["beg_".$key.'_question']=$_SESSION['total']["beg_".$key];
				}
				
				$total+= $_SESSION['total']["beg_".$key]*$key;
			}
		}
		if(isset($_SESSION['int']))
		{
			foreach($_SESSION['int'] as $key=>$val)
			{
				if(isset($_SESSION['total']['int_'.$key.'_totalQuestion']))
				{
					unset($_SESSION['total']['int_'.$key.'_totalQuestion']);
				}
				
				if(isset($_SESSION['total']['int_'.$key.'_question']))
				{
					unset($_SESSION['total']['int_'.$key.'_question']);
					$_SESSION['total']["int_".$key.'_question']=$_SESSION['total']["int_".$key];
				}
				
				$total+= $_SESSION['total']["int_".$key]*$key;
			}
		}
		
		if(isset($_SESSION['high']))
		{
			foreach($_SESSION['high'] as $key=>$val)
			{
				if(isset($_SESSION['total']['high_'.$key.'_totalQuestion']))
				{
					unset($_SESSION['total']['high_'.$key.'_totalQuestion']);
				}
				
				if(isset($_SESSION['total']['high_'.$key.'_question']))
				{
					unset($_SESSION['total']['high_'.$key.'_question']);
					$_SESSION['total']["high_".$key.'_question']=$_SESSION['total']["high_".$key];
				}
				
				$total+= $_SESSION['total']["high_".$key]*$key;
			}
		}
		
		if($subject_id!='')
		{
			$objResponse->addAssign('subject_total_mark','innerHTML',$total);
			unset($_SESSION['custom_question_id_set']);
		}
		else
		{
			$objResponse->addAssign('total_mark','innerHTML',$total);
                        
			unset($_SESSION['custom_question_id_set']);
		}
	}elseif($mode=='group_reset')
	{
		if(isset($_SESSION['total']))
		{
			unset($_SESSION['total']);
		}
		
		$value = '';
		$value_arr = array();
		if(is_array($_SESSION['totalGroupQuestion']))
		{
			foreach($_SESSION['totalGroupQuestion'] as $key => $val)
			{
				$checked = 'uncheck';
				if(is_array($_SESSION['checkedGroupQuestion']) && in_array($key,$_SESSION['checkedGroupQuestion']))
				{
					$checked = 'check';
				}
				$value_arr[] = $val.'_'.$checked;
				$value .= $val.'_'.$checked.',';
			}
		}
		
		$value_arr=explode(',',$value);
		for($counter=0; $counter<count($value_arr); $counter++)
		{
			$marks_id=explode('_',$value_arr[$counter]);
			$qid=$marks_id[0];
			$marks=$marks_id[1];
			$ischecked=$marks_id[2];
			if($ischecked=='check')
			{
				if (isset($_SESSION['total'][$marks.'_question']) && $_SESSION['total'][$marks.'_question']>0)
				{
					$_SESSION['total'][$marks.'_question']++;
					$_SESSION['total'][$qid]=1;
				}
				else 
				{
					$_SESSION['total'][$marks.'_question']=1;
					$_SESSION['total'][$qid]=1;
				}
				$total = $total+$marks;
			}
		}
		
		if($subject_id!='')
		{
			$objResponse->addAssign('subject_total_mark','innerHTML',$total);
		}else
		{
			$total = $current_marks;
		}

	}elseif($mode=='group_reset&custom_reset')
	{

		if(isset($_SESSION['total']))
		{
			unset($_SESSION['total']);
		}
		
		$value = '';
		$value_arr = array();
		
	if(is_array($_SESSION['totalCustomQuestion']))
	{
		foreach($_SESSION['totalCustomQuestion'] as $key => $val)
		{
				$checked = 'uncheck';
				if(is_array($_SESSION['checkedCustomQuestion']) && in_array($key,$_SESSION['checkedCustomQuestion']))
				{
					$checked = 'check';
				}
				$value_arr[] = $val.'_'.$checked;
				$value .= $val.'_'.$checked.',';
		}
	}

		if(is_array($_SESSION['totalGroupQuestion']))
		{
			foreach($_SESSION['totalGroupQuestion'] as $key => $val)
			{
				$checked = 'uncheck';
				if(is_array($_SESSION['checkedGroupQuestion']) && in_array($key,$_SESSION['checkedGroupQuestion']))
				{
					$checked = 'check';
				}
				$value_arr[] = $val.'_'.$checked;
				$value .= $val.'_'.$checked.',';
			}
		}
		//$objResponse->addAlert($value);
		
		$value_arr=explode(',',$value);
		for($counter=0; $counter<count($value_arr); $counter++)
		{
			$marks_id=explode('_',$value_arr[$counter]);
			$qid=$marks_id[0];
			$marks=$marks_id[1];
			$ischecked=$marks_id[2];
			if($ischecked=='check')
			{
				if (isset($_SESSION['total'][$marks.'_question']) && $_SESSION['total'][$marks.'_question']>0)
				{
					$_SESSION['total'][$marks.'_question']++;
					$_SESSION['total'][$qid]=1;
				}
				else 
				{
					$_SESSION['total'][$marks.'_question']=1;
					$_SESSION['total'][$qid]=1;
				}
				$total = $total+$marks;
			}
		}
		
		if($subject_id!='')
		{
		
			$objResponse->addAssign('subject_total_mark_group','innerHTML',$total);
		}else
		{
			$total = $current_marks;
		}

	}
	return $objResponse;
}

function deletePermission($id, $module)
{
	global $DB;
	$objResponse = new xajaxResponse();
	$msg = "";
	$msgno = 0;
	
	if($module == 'subject_master')
	{
		$module_entity = "subject";
		$subject_history = $DB->SelectRecords('candidate_test_subject_history','subject_id='.$id);
		
		if($subject_history)
		{
			$count_subject_history = count($subject_history);
			if($count_subject_history>1)
				$msg = "Permission denied. ".$count_subject_history." tests have been conducted";
			elseif($count_subject_history==1)
				$msg = "Permission denied. ".$count_subject_history." test has been conducted";
			
			$msgno++;
		}

		$future_exam= $DB->SelectRecords('exam_subjects','subject_id='.$id, 'distinct exam_id');
		if($future_exam)
		{
			$count_future_exam = count($future_exam);
			
			if($count_future_exam>1)
				$backstring = " courses are attached";
			elseif ($count_future_exam==1)
				$backstring = " course is attached";
			
			if($msgno>0)
			{
				$msg.=" \nand ".$count_future_exam.$backstring;
			}
			else 
			{
				$msg="Permission denied. ".$count_future_exam.$backstring;
			}
			$msgno++;
		}
		
		$paper_subject = $DB->SelectRecords('paper_subject',"subject_id = $id");
		if($paper_subject)
		{
			$count_ps = count($paper_subject);
			
			if($count_ps>1)
				$backstring = " papers are attached";
			elseif ($count_ps==1)
				$backstring = " paper is attached";
			
			if($msgno>0)
			{
				$msg.=" \nand ".$count_ps.$backstring;
			}
			else 
			{
				$msg="Permission denied. ".$count_ps.$backstring;
			}
			$msgno++;
		}
		
		if($msg!='')
		{
			$msg.=" with this subject.";
		}
	}
	elseif ($module=='examination')
	{
		$module_entity="course";
		$test_history = $DB->SelectRecords('test','exam_id='.$id);
		if($test_history)
		{
			$count_test_history = count($test_history);
			if($count_test_history>1)
				$msg = "Permission denied. ".$count_test_history." tests have been scheduled with this course.";
			elseif($count_test_history==1)
				$msg = "Permission denied. ".$count_test_history." test has been scheduled with this course.";
			
			$msgno++;
		}
	}
	elseif ($module=='test_master')
	{
		$module_entity="test";
		
		$test_history = $DB->SelectRecords('candidate_test_history','test_id='.$id);
		
		if(($test_history != '') && count($test_history) > 0)
		{
			$msg = "Permission denied. Test is already conducted.";
		}
		$msgno++;
	}
	elseif ($module=='question_master')
	{
		$module_entity="question";
		$candidate_question_history = $DB->SelectRecords('candidate_question_history','question_id='.$id, '*', 'group by test_id');
		if($candidate_question_history)
		{
			$count = count($candidate_question_history);
			if($count==1)
			{
				$msg = "Permission denied. ".$count." test is conducted with this question.";
			}
			else
			{
				$msg = "Permission denied. ".$count." tests are conducted with this question.";
			}	
			$msgno++;
		}
		else
		{
			$paper_question = $DB->SelectRecords('paper_questions',"question_id = '$id'");
			if($paper_question)
			{
				$count = count($paper_question);
				if($count==1)
				{
					$msg = "Permission denied. ".$count." paper is conducting with this question.";
				}
				else
				{
					$msg = "Permission denied. ".$count." papers are conducting with this question.";
				}	
				$msgno++;
			}
		}
	}
	elseif ($module=='group_questions')
	{
		$module_entity="group_questions";
		
		//$candidate_question_history = $DB->SelectRecords('candidate_question_history','question_id='.$id, '*', 'group by test_id');
		$qus_query = "select c.exam_id from candidate_question_history as c left join question as q ON c.question_id = q.id where q.group_question_id=".$id." group by c.test_id ";
		
		//$qus_query_run = mysql_query($qus_query);
		$candidate_question_history = $DB->RunSelectQuery($qus_query);
		//$cout = count($candidate_question_history);
			//$objResponse->addAlert(count($candidate_question_history[0]));
		if($candidate_question_history)
		{
			$count = count($candidate_question_history);
			
			if($count==1)
			{
				$msg = "Permission denied. ".$count." test is conducted with this question.";
			}
			else
			{
				$msg = "Permission denied. ".$count." tests are conducted with this question.";
			}	
			$msgno++;
		}
		
	}
	elseif ($module=='paper')
	{
		$module_entity="paper";
		$test_paper = $DB->SelectRecords('test_paper','paper_id='.$id);
		if($test_paper)
		{
			$count_test_paper = count($test_paper);
			if($count_test_paper>1)
				$msg = "Permission denied. ".$count_test_paper." tests have been scheduled with this paper.";
			elseif($count_test_paper==1)
				$msg = "Permission denied. ".$count_test_paper." test has been scheduled with this paper.";
			
			$msgno++;
		}
	}
	elseif ($module=='homework')
	{
		$module_entity="homework";
		$homework_history = $DB->SelectRecords('candidate_homework_history','homework_id='.$id);
		
		if(($homework_history != '') && count($homework_history) > 0)
		{
			$msg = "Permission denied. Homework is already conducted.";
		}
		$msgno++;
	}
	if($msg=='')
	{	if($module=='group_questions')
	{
		$objResponse->addScriptCall('deleterecord',$id, ROOTURL."/index.php?mod=question_master&do=delete&id=".$id,"Are you sure you want to delete this ".$module_entity."?");
	}
		$objResponse->addScriptCall('deleterecord',$id, ROOTURL."/index.php?mod=".$module."&do=delete&nameID=".$id,"Are you sure you want to delete this ".$module_entity."?");
	}
	else 
	{
		$objResponse->addAlert($msg);
	}
	return $objResponse;
}

function getExamTypeIdByExamId($exam_id, $is_clear_session = true)
{
	global $DB;
	$objResponse = new xajaxResponse();
	if($exam_id == '')
	{
		$objResponse->addScriptCall('showGeneralExamDetail','');
		$objResponse->addAssign('exam-subject-info', 'innerHTML', '');
		return $objResponse;
	}
	
	if(isset($_SESSION['total_paper_set']))
	{
		unset($_SESSION['total_paper_set']);
	}

	if($is_clear_session=='yes')
	{
		if(isset($_SESSION['beg']))
		{
			unset($_SESSION['beg']);
		}
		if(isset($_SESSION['int']))
		{
			unset($_SESSION['int']);
		}
		if(isset($_SESSION['high']))
		{
			unset($_SESSION['high']);
		}
		if(isset($_SESSION['total']))
		{
			unset($_SESSION['total']);
		}
	}
	
	$query = "SELECT SUM(subject_max_mark) as exam_max_marks, 
			GROUP_CONCAT(DISTINCT CONCAT_WS(':', subject_name, CONCAT(subject_max_mark, 'MM')) ORDER BY subject_name ASC SEPARATOR ', ') as subjects
				FROM exam_subjects JOIN subject ON subject.id = exam_subjects.subject_id
				WHERE exam_id = $exam_id GROUP BY exam_id";
	$result = $DB->RunSelectQuery($query);
	$result = $result[0];
	
	$exam_max_marks = $result->exam_max_marks;
	$objResponse->addAssign('exam_max_marks', 'innerHTML', $exam_max_marks);
	$objResponse->addScriptCall('showGeneralExamDetail', 'no');
	$objResponse->addScriptCall('xajax_getExamSubject', $exam_id);
	$objResponse->addScriptCall('xajax_getExamPaper', $exam_id);
	$objResponse->addAssign('exam-subject-info', 'innerHTML', '['.$result->subjects.']');

	return $objResponse;
}

function getExamSubject($exam_id)
{
	global $DB;
	$objResponse = new xajaxResponse();
	if($exam_id == '')
	{
		return $objResponse;
	}
	
	$query="select es.*, sub.subject_name from exam_subjects as es 
			left join subject as sub on es.subject_id=sub.id ";

	if ($_SESSION['admin_user_type'] == 'T')
	{
		$query .= " JOIN exam_subject_teacher est on (est.exam_id = es.exam_id AND est.subject_id = es.subject_id) ";
		$query .= " WHERE est.teacher_id = '".$_SESSION['admin_user_id']."' AND ";
	}
	else
	{
		$query .= " where 1 and ";
	}
	$query .= " es.exam_id='$exam_id' order by es.subject_id ";
	
	$subject = $DB->RunSelectQuery($query);
	
	$output = '<select id="select_subject_id" name="select_subject_id" class="rounded" onchange="subjectquestionform(this);"><option value="">Select Subject</option>';
	if(is_array($subject))
	{
		for($counter=0; $counter<count($subject); $counter++)
		{
			//$objResponse->addAlert($subject[$counter]->id.", ".$subject_selected_value);
			$selected='';
			if ($subject[$counter]->subject_id == $subject_selected_value)
			{
				$selected='selected';
			}
			$output.='<option value="'.$subject[$counter]->subject_id.'" '.$selected.' >'.stripslashes($subject[$counter]->subject_name).'</option>';
		}
	}
	$output.='</select>';
	
	$objResponse->addAssign('select_subject_td','innerHTML',$output);
	return $objResponse;
}

function changeMaxMarksByPaper($pid)
{
	global $DB;
	$objResponse = new xajaxResponse();
	
	$marks = 0;
	
	if($pid && !in_array($pid, $_SESSION['paperIdForCurrentExam']))
	{
		$message = "This paper is not identical with selected course.";
		$message .= "\n\nA paper is identical with a course only if both paper and course have same subject with equal maximum marks.";
		$objResponse->addAlert($message);
		$objResponse->addScript('$("#paper_id").val(" ");');
	}
	
	if($pid != '')
	{
		$paper = $DB->SelectRecord('paper',"id = '$pid'");
		$marks = $paper->maximum_marks;
	}
	
	$objResponse->addAssign('total_mark','innerHTML',$marks);
	
	return $objResponse;
}

function getExamPaper($exam_id)
{
	global $DB;
	$objResponse = new xajaxResponse();
	
	if($exam_id!='')
	{
		$subject = getSubjectInfoByExamId($exam_id);
		
		$sub_ids = array();
		if($subject)
		{
			for($counter=0; $counter<count($subject); $counter++)
			{
				$sub_ids[] = $subject[$counter]->subject_id;
			}
			
			$query = "SELECT DISTINCT paper.id
						FROM paper
						JOIN paper_subject AS ps ON paper.id = ps.paper_id
						JOIN exam_subjects AS es ON 
								( 
									( es.subject_id = ps.subject_id ) AND 
									( es.subject_max_mark = ps.subject_total_marks ) 
								) WHERE es.exam_id = '$exam_id'";
			
			$result = $DB->RunSelectQuery($query);
			
			$_SESSION['paperIdForCurrentExam'] = array();
			if($result != '')
			{
				foreach($result as $r)
				{
					$paper_id = $r->id;
					$query = "SELECT COUNT(*) as sub_count FROM paper_subject WHERE paper_id = '$paper_id' GROUP BY paper_id; ";

					$sub_count = $DB->RunSelectQuery($query);
					$sub_count = $sub_count[0]->sub_count;
					
					if($sub_count == count($sub_ids))
					{
						$_SESSION['paperIdForCurrentExam'][] = $paper_id;
					}
				}
			}
			

		}
	}
	
	$query = "SELECT DISTINCT paper.*, 
				GROUP_CONCAT(DISTINCT CONCAT_WS(':', subject_name, CONCAT(subject_total_marks, 'MM')) ORDER BY subject_name ASC SEPARATOR ', ') as subjects
					FROM paper
					JOIN paper_subject AS ps ON paper.id = ps.paper_id
					JOIN subject ON subject.id = ps.subject_id
					GROUP BY paper_id";
			
	$result = $DB->RunSelectQuery($query);
	
	$output.='<select id="paper_id" name="paper_id" class="rounded" onchange="xajax_changeMaxMarksByPaper(this.value);"><option value="">Select Paper</option>';
	if($result != '')
	{
		foreach($result as $r)
		{
			$paper_id = $r->id;
			$paper_title = $r->paper_title;
			$subjects = $r->subjects;
			
			$selected = '';
			if($_SESSION['selected_paper_id'] == $paper_id)
			{
				$selected = 'selected';
				unset($_SESSION['selected_paper_id']);
			}
				
			$output .= "<option value = '$paper_id' $selected>$paper_title ($subjects)</option>";
		}
	}
	
	$output.='</select>';
	$objResponse->addAssign('select_paper_td','innerHTML',$output);
	
	return $objResponse;
}

function getSubjectQuestion($subject_id, $custom_que_id_set, $random_que_set, $subject_total_mark, $tableRowCount, $mode, $subject_diff_question='N')
{
	global $DB;
	$objResponse = new xajaxResponse();

	$totalQuestion = 0;
	$random_que_set_array=explode(',',$random_que_set);
	$random_input_array = array();
	for($counter=0; $counter<count($random_que_set_array); $counter++)
	{
		$random_que_set_value_array=explode('_',$random_que_set_array[$counter]);
		$random_que_level = $random_que_set_value_array[0];
		$random_que_marks = $random_que_set_value_array[1];
		$random_que_available = $random_que_set_value_array[2];
		$random_que_input_value = $random_que_set_value_array[3];
		$random_input_array[$random_que_level."_".$random_que_marks."_".$random_que_available] = $random_que_input_value;
	}
	
	$custom_que_id_set = array();
	if(is_array($_SESSION['totalCustomQuestion']))
	{		//$objResponse->addAlert($group_que_id_set.'here');
		foreach($_SESSION['totalCustomQuestion'] as $key => $val)
		{
			if(is_array($_SESSION['checkedCustomQuestion']) && in_array($key,$_SESSION['checkedCustomQuestion']))
			{
				$custom_que_id_set[] = $key;
			}
		}
		
	}
	$custom_que_id_set = implode(',',$custom_que_id_set);
	
	if($custom_que_id_set!='')
	{		//$objResponse->addAlert($group_que_id_set.'here1');
		$cust_que_set_condition = " and id not in ($custom_que_id_set)";
		$custom_que_id_array = explode(',',$custom_que_id_set);
		$totalQuestion = count($custom_que_id_array);
		//$objResponse->addAlert(count($custom_que_id_array));
	}
	
	$group_que_id_set = array();
	if(is_array($_SESSION['totalGroupQuestion']))
	{	//$objResponse->addAlert($group_que_id_set.'here2');
	//$objResponse->addAlert('herecome');
	
	
	//$objResponse->addAlert(implode(',',$_SESSION['checkedGroupQuestion']));
	if($mode=='add')
	{	
		unset($_SESSION['checkedGroupQuestionedit']);
		$_SESSION['checkedGroupQuestionedit'] = $_SESSION['checkedGroupQuestion'];
		foreach($_SESSION['totalGroupQuestion'] as $key => $val)
		{
			if(is_array($_SESSION['checkedGroupQuestion']) && in_array($key,$_SESSION['checkedGroupQuestion']))
			{
				$group_que_id_set[] = $key;
			}
		}
	}
	else
	{
		foreach($_SESSION['totalGroupQuestion'] as $key => $val)
		{
			if(is_array($_SESSION['checkedGroupQuestionedit']) && in_array($key,$_SESSION['checkedGroupQuestionedit']))
			{
				$group_que_id_set[] = $key;
			}
		}
	}
		
	}
	$group_que_id_set = implode(',',$group_que_id_set);
	//$objResponse->addAlert($group_que_id_set.'herecomesit');
	if($group_que_id_set!='')
	{
	//$objResponse->addAlert('nothere');
		$cust_que_set_condition = " and id not in ($group_que_id_set)";
		$group_que_id_array = explode(',',$group_que_id_set);
		$totalQuestions = count($group_que_id_array);
		for($i=0;$i<$totalQuestions;$i++)
		{
			$gr_id = $group_que_id_array[$i];
			$total_gr_que = $DB->SelectRecords('question',"group_question_id=$gr_id");
			$total_gr_ques+=count($total_gr_que);
		}
		//$objResponse->addAlert($group_que_id_set);
		$totalQuestion += $total_gr_ques;
		//$objResponse->addAlert($totalQuestion);
	}
	
	//if($subject_id!='')
	{
		$common_query_cond = " (subject_id=$subject_id ";
		$subject_info = $DB->SelectRecord('subject',"id=$subject_id");
		
		$subject_name = $subject_info->subject_name;
		if($subject_info->stream_id!='' && $subject_info->stream_id!=0)
		{
			$common_query_cond.= "or stream_id=$subject_info->stream_id";
		}
		$common_query_cond.=") ";
	}
	//$objResponse->addAlert($group_que_set_condition);
	$random_que_id_set='';
	if(isset($_SESSION['beg']))
	{
		foreach($_SESSION['beg'] as $key=>$val) // key=marks val==no of question in database
		{
			$test_question_selection['question_total']=$random_input_array['beg_'.$key.'_'.$val];
			$selected=$test_question_selection['question_total'];
			if($selected!=0)
			{
				$questionDetails=$DB->SelectRecords('question',"question_level='B' and marks=$key and ".$common_query_cond.$cust_que_set_condition,'*',"limit $selected");
				$count=count($questionDetails);
					 
				for($counter=0;$counter<$count;$counter++)
				{
					$random_que_id_set.=$questionDetails[$counter]->id.",";
					$totalQuestion++;
				}
			}
		}
	}
	unset($_SESSION['beg']);
	
	if(isset($_SESSION['int']))
	{
		foreach($_SESSION['int'] as $key=>$val)
		{
			$test_question_selection['question_total']=$random_input_array['int_'.$key.'_'.$val];
			$selected=$test_question_selection['question_total'];
			if($selected!=0)
			{
				$questionDetails=$DB->SelectRecords('question',"question_level='I' and marks=$key and ".$common_query_cond.$cust_que_set_condition,'*',"limit $selected");
				$count=count($questionDetails);
				for($counter=0;$counter<$count;$counter++)
				{
					$random_que_id_set.=$questionDetails[$counter]->id.",";
					$totalQuestion++;
				}
			}
		}
		unset($_SESSION['int']);
	}
	
	if(isset($_SESSION['high']))
	{
		foreach($_SESSION['high'] as $key=>$val)
		{
			$test_question_selection['question_total']=$random_input_array['high_'.$key.'_'.$val];
			$selected=$test_question_selection['question_total'];
			if($selected!=0)
			{
				$questionDetails=$DB->SelectRecords('question',"question_level='H' and marks=$key and ".$common_query_cond.$cust_que_set_condition,'*',"limit $selected");
				$count=count($questionDetails);
				for($counter=0;$counter<$count;$counter++)
				{
					$random_que_id_set.=$questionDetails[$counter]->id.",";
					$totalQuestion++;
				}
				
			}
		}
		unset($_SESSION['high']);
	
	}
//$objResponse->addAlert($totalQuestion);
	$random_que_id_set = substr($random_que_id_set,0,strlen($random_que_id_set)-1);
	$subject_td_value = ucfirst(strtolower(stripcslashes($subject_name))).' <input type="hidden" name="subject_id_'.($tableRowCount).'" id="subject_id_'.($tableRowCount).'" value="'.$subject_id.'" />';
	$totalQuestion_td_value = $totalQuestion.' <input type="hidden" name="total_question_'.($tableRowCount).'" id="total_question_'.($tableRowCount).'" value="'.$totalQuestion.'" /><input type="hidden" name="random_que_id_'.($tableRowCount).'" id="random_que_id_'.($tableRowCount).'" value="'.$random_que_id_set.'" /><input type="hidden" name="custom_que_id_'.($tableRowCount).'" id="custom_que_id_'.($tableRowCount).'" value="'.$custom_que_id_set.'" /><input type="hidden" name="group_que_id_'.($tableRowCount).'" id="group_que_id_'.($tableRowCount).'" value="'.$group_que_id_set.'" />';
	$total_mark_td_value = $subject_total_mark.' <input type="hidden" name="subject_total_mark_'.($tableRowCount).'" id="subject_total_mark_'.($tableRowCount).'" value="'.$subject_total_mark.'" />';
	
	if($subject_diff_question == 'Y')
	{
		$subject_diff_question_td_value = 'Yes <input type="hidden" name="subject_diff_question_'.($tableRowCount).'" id="subject_diff_question_'.($tableRowCount).'" value="'.$subject_diff_question.'" />';
	}
	else
	{
		$subject_diff_question_td_value = 'No <input type="hidden" name="subject_diff_question_'.($tableRowCount).'" id="subject_diff_question_'.($tableRowCount).'" value="'.$subject_diff_question.'" />';
	}
	
	$objResponse->addAssign("subject_name","value",$subject_td_value);
	$objResponse->addAssign("subject_total_question","value",$totalQuestion_td_value);
	$objResponse->addAssign("subject_paper_mark","value",$total_mark_td_value);
	$objResponse->addAssign("subject_diff_question","value",$subject_diff_question_td_value);
	
	if($mode=='add')
	{
		$objResponse->addScriptCall("addRow");
	}
	else 
	{
		$objResponse->addScriptCall("editRow",'update',$tableRowCount);
	}

	return $objResponse;
}

function checkMaxMarks($candidate_marks, $exam_id, $marks_module, $textbox_id='', $subject_id='')
{
	global $DB;
	$objResponse = new xajaxResponse();
	
	if($marks_module=='subjective')
	{
		$subjective_exam_info = $DB->SelectRecord('subjective_exam', "exam_id=$exam_id");
		if($candidate_marks>$subjective_exam_info->max_marks)
		{
			$objResponse->addAlert('Please enter student subjective marks less than or equal to '.$subjective_exam_info->max_marks.'.');
			$objResponse->addAssign('subjective_mark','value','');
		}
	}
	
	if($marks_module=='practical')
	{
		$practical_exam_info = $DB->SelectRecord('practical_exam', "exam_id=$exam_id and subject_id=$subject_id");
		if($candidate_marks>$practical_exam_info->max_marks)
		{
			$objResponse->addAlert('Please enter student practical marks less than or equal to '.$practical_exam_info->max_marks.'.');
			$objResponse->addAssign($textbox_id,'value','');
		}
	}
	
	if($marks_module=='subject')
	{
		$exam_subject_info = $DB->SelectRecord('exam_subjects', "exam_id=$exam_id and subject_id=$subject_id");
		if($candidate_marks>$exam_subject_info->subject_max_mark)
		{
			$objResponse->addAlert('Please enter student marks less than or equal to '.$exam_subject_info->subject_max_mark.'.');
			$objResponse->addAssign($textbox_id,'value','');
		}
	}
	
	return $objResponse;
}

function enableTest($test_id)
{
	global $DB;
	$objResponse = new xajaxResponse();
	
	$test_data = array();
	if($test_id)
	{
		$isReload = true;
		$test_info = $DB->SelectRecord('test',"id=$test_id", 'enable_test');
		if($test_info->enable_test=='Y')
		{
			$test_data['enable_test'] = 'N';
			$DB->UpdateRecord('test', $test_data, "id=$test_id");
			$objResponse->addAssign('enableTest_'.$test_id, 'src',IMAGEURL.'/right.gif');
			$objResponse->addAssign('enableTest_'.$test_id, 'title','Enable test');
			$objResponse->addAlert('Test has been disabled successfully.');
		}
		else
		{
		/*	if(getActiveTestHavingSameStream($test_id) != FALSE)
			{
				$isReload = false;
				$objResponse->addAlert('Can\'t enable this test as a test having same stream is already enabled.');
			}
			else
		*/	{
				$test_data['enable_test'] = 'Y';
				$DB->UpdateRecord('test', $test_data, "id=$test_id");
				$objResponse->addAssign('enableTest_'.$test_id, 'src',IMAGEURL.'/disable.gif');
				$objResponse->addAssign('enableTest_'.$test_id, 'title','Disable test');
				$objResponse->addAlert('Test has been enabled successfully.');
			}
		}

		if($isReload)
		$objResponse->addScript('reload()');
	}
	
	return $objResponse;
}

function archiveTest($test_id)
{
	global $DB;
	$objResponse = new xajaxResponse();
	
	//$objResponse->addAlert($value);
	$test_data = array();
	if($test_id)
	{
		$test_info = $DB->SelectRecord('test',"id=$test_id", 'is_archive');
		if($test_info->is_archive=='Y')
		{
			$test_data['is_archive'] = 'N';
			$DB->UpdateRecord('test', $test_data, "id=$test_id");
			//$objResponse->addAssign('row_'.$test_id, 'innerHTML','');
			$_SESSION['success'] = 'Test has been activated successfully.';
		}
		else
		{
			$test_data['is_archive'] = 'Y';
			$DB->UpdateRecord('test', $test_data, "id=$test_id");
			//$objResponse->addAssign('row_'.$test_id, 'innerHTML','');
			$_SESSION['success'] = 'Test has been archived successfully.';
		}
		$objResponse->addScript('reload()');
	}
	return $objResponse;
}

function unsetSession($index)
{
	$objResponse = new xajaxResponse();
	if(isset($_SESSION[$index]) && $_SESSION[$index] != "")
	{
		$image_path = ROOT . '/uploadfiles/' . $_SESSION[$index];
		@unlink($image_path);
		unset($_SESSION[$index]);
		unset($_SESSION['fileObject_'.$index]);
	}
	return $objResponse;
}

function testcaptchaWork($frmname)
{
    
	$objResponse = new xajaxResponse();
  if($frmname == 'true')
  {
	$stringa = '';
		$cifre = 5;
		for($i=1;$i<=$cifre;$i++){
			$letteraOnumero = rand(1,2);
			if($letteraOnumero == 1){
				// lettera
				$lettere = 'ABEFHKMNRVWX';
				$x = rand(1,11);
				$lettera = substr($lettere,$x,1);
				$stringa .= $lettera;
			} else {
				$numero = rand(3,7);
				$stringa .= $numero;
			}
		}
	  // $_SESSION['captcha'] = $stringa;
		$inner = $stringa;
		
		$objResponse->AddAssign("showpass","innerHTML",$inner);
		$objResponse->AddAssign("passwords","value",$inner);
		$objResponse->AddAssign("pass","value",$inner);
		$objResponse->AddAssign("repass","value",$inner);
		$objResponse->AddAssign("pass","readOnly",'true');
		$objResponse->AddAssign("repass","readOnly",'true');
		}
	else
	 {
	    $objResponse->AddAssign("showpass","innerHTML",$inner);
		 $objResponse->AddAssign("pass","value",'');
		$objResponse->AddAssign("repass","value",'');
		$objResponse->AddAssign("pass","readOnly",'');
		$objResponse->AddAssign("repass","readOnly",'');
	 }
		return $objResponse;
}

/**************************************************************************************************
 * 		Added By	:		Ashwini Agarwal
 * 		Date		:		August 3, 2012
 */

function addedu($class = '', $year = '', $pmarks = '', $mmarks = '', $omarks = '', $grade = '', $stream = '')
{
	$objResponse = new xajaxResponse();
	
	if(!isset($_SESSION['cand_edu']))
	{
		$_SESSION['cand_edu'] = array();
		$_SESSION['cand_edu']['total_edu'] = 0;
		$_SESSION['cand_edu']['count_edu'] = 0;
	}
	
	$count = 0;
	$streamExists = 0;
	for(;$count <= $_SESSION['cand_edu']['count_edu'];)
	{
		if($_SESSION['cand_edu']['class_'.$count++] == $class)
		{
			$streamExists = 1;
			break;
		}
	}
	
	if($streamExists == 1)
	{
		$objResponse->addAlert("This class is already added.\nPlease select a different class.");
	}
	else
	{
		$key = ++$_SESSION['cand_edu']['count_edu'];
		
		$_SESSION['cand_edu']['class_'.$key] = $class;
		$_SESSION['cand_edu']['year_'.$key] = $year;
		$_SESSION['cand_edu']['pmarks_'.$key] = $pmarks;
		$_SESSION['cand_edu']['mmarks_'.$key] = $mmarks;
		$_SESSION['cand_edu']['omarks_'.$key] = $omarks;
		$_SESSION['cand_edu']['grade_'.$key] = $grade;
		$_SESSION['cand_edu']['stream_'.$key] = $stream;
		
		$_SESSION['cand_edu']['total_edu']++;
		
		$row = "<tr id = 'edu-$key' class='tdbgwhite'>".
				"<td class='serial_edu'>".$_SESSION['cand_edu']['total_edu']."</td>".
				"<td>$class</td>".
				"<td>$year</td>".
				"<td>$mmarks</td>".
				"<td>$omarks</td>".
				"<td>$pmarks</td>".
				"<td>$grade</td>".
				"<td>$stream</td>".
				"<td><a onclick='remove_edu($key);' class='links'>Remove</a></td>".
				"</tr>";
		
		$objResponse->addAppend('tbl_edu','innerHTML',$row);
		$objResponse->addScript('clearEdu()');
		$objResponse->addScript('showedu()');
	}
	
	return $objResponse;
}

function remove_edu($key)
{
	$objResponse = new xajaxResponse();
	
	unset($_SESSION['cand_edu']['class_'.$key]);
	unset($_SESSION['cand_edu']['year_'.$key]);
	unset($_SESSION['cand_edu']['marks_'.$key]);
	unset($_SESSION['cand_edu']['stream_'.$key]);
	
	$_SESSION['cand_edu']['total_edu']--;

	return $objResponse;
}

/**************************************************************************************************
 * 		Added By	:		Ashwini Agarwal
 * 		Date		:		August 3, 2012
 */

function addexp($company = '', $from = '', $to = '', $stream = '', $detail)
{
	$objResponse = new xajaxResponse();
	
	if(!isset($_SESSION['cand_exp']))
	{
		$_SESSION['cand_exp'] = array();
		$_SESSION['cand_exp']['total_exp'] = 0;
		$_SESSION['cand_exp']['count_exp'] = 0;
	}
	
	$count = 0;
	$streamExists = 0;
	for(;$count <= $_SESSION['cand_exp']['count_exp'];)
	{
		if($_SESSION['cand_exp']['company_'.$count++] == $company)
		{
			$streamExists = 1;
			break;
		}
	}
	
	if($streamExists == 1)
	{
		$objResponse->addAlert("Yo have already added this company.\nPlease enter a different one.");
	}
	else
	{
		$key = ++$_SESSION['cand_exp']['count_exp'];
		
		$detail = preg_replace("/\n/", "<br />", $detail);
		
		$_SESSION['cand_exp']['company_'.$key] = $company;
		$_SESSION['cand_exp']['from_'.$key] = $from;
		$_SESSION['cand_exp']['to_'.$key] = $to;
		$_SESSION['cand_exp']['stream_'.$key] = $stream;
		$_SESSION['cand_exp']['detail_'.$key] = $detail;
		
		$_SESSION['cand_exp']['total_exp']++;
		
		$row = "<tr id = 'exp-$key' class='tdbgwhite'>".
				"<td class='serial_exp'>".$_SESSION['cand_exp']['total_exp']."</td>".
				"<td>$company</td>".
				"<td>$from</td>".
				"<td>$to</td>".
				"<td>$stream</td>".
				"<td>".
				"<a onclick='showDetail($key);' class='links'>Details</a>&nbsp;&nbsp;".
				"<a onclick='remove_exp($key);' class='links'>Remove</a>".
				"</td>".
				"</tr>".
				"<tr id='detail-$key' class='tdbggrey'>".
				"<td valign='top'>Details</td>".
				"<td colspan='8' align='left'>$detail</td>".
				"</tr>";
		
		$objResponse->addAppend('tbl_exp','innerHTML',$row);
		$objResponse->addScript('clearExp()');
		$objResponse->addScript('showexp()');
	}
	
	return $objResponse;
}

function remove_exp($key)
{
	$objResponse = new xajaxResponse();
	
	unset($_SESSION['cand_exp']['company_'.$key]);
	unset($_SESSION['cand_exp']['from_'.$key]);
	unset($_SESSION['cand_exp']['to_'.$key]);
	unset($_SESSION['cand_exp']['stream_'.$key]);
	
	$_SESSION['cand_exp']['total_exp']--;

	return $objResponse;
}

/************************************************************************
 * 	Author		:	Ashwini Agarwal
 * 	Date		:	November 2, 2012
 * 	Description	:	Send mail to eligible candidates for particular test.
 */

function informCandidatesForTest($test_id)
{
	$objResponse = new xajaxResponse();
	global $DB;

	$teacher_condition = '';
	$test_details = $DB->SelectRecord('test', "id = $test_id");
	if($test_details->teacher_id)
	{
		$teacher_candidate = $DB->SelectRecord('exam_candidate_teacher', "((teacher_id = '$test_details->teacher_id') AND (exam_id = $test_details->exam_id))", 'GROUP_CONCAT(DISTINCT candidate_id) as candidate_id');
		
		if($teacher_candidate && $teacher_candidate->candidate_id)
		$teacher_condition = " AND (c.id IN ($teacher_candidate->candidate_id)) ";
	}
	
	$query = "SELECT c.first_name,c.last_name,c.email,t.* 
			FROM test as t
			JOIN examination as e ON e.id = t.exam_id
			JOIN exam_candidate as ec ON ec.exam_id = t.exam_id
			JOIN candidate as c ON c.id = ec.candidate_id
			JOIN test_candidate tc on (tc.candidate_id = c.id AND tc.test_id = t.id)
			WHERE (t.id = '$test_id') AND (c.email IS NOT NULL) $teacher_condition
			GROUP BY c.id;";
	
	$result = $DB->RunSelectQuery($query);
	
	if($result && (count($result) > 0))
	{	
		$message = '<p>This is to inform you that <b>'.$result[0]->test_name.'</b> is going to counduct.</p>';
		$message .= '<b><u>Test Details</u></b>';
		$message .= '<br>Test Start Date - '. date('d-m-Y h:i:s A', strtotime($result[0]->test_date));
		$message .= '<br>Test End Date - '. date('d-m-Y h:i:s A', strtotime($result[0]->test_date_end));
		$message .= '<br>Time Duration - '. floor($result[0]->time_duration/60).' Hours '.($result[0]->time_duration%60).' Minutes';
		$message .= '<br>Maximum Marks - '.$result[0]->maximum_marks;
		$message .= '<br><br>With Best Wishes,';
		$message .= '<br><b>Assess All Team</b>';
		
		require_once('lib/mailer.php');
		foreach($result as $r)
		{
			$msg = 'Hello '.ucwords($r->first_name.' '.$r->last_name) . $message.'<br>';
			$mail = new mailer();
			$mail->addTo($r->email, ucwords($r->first_name.' '.$r->last_name));
			$mail->setSubject('Assess All - Test');
			$mail->setMessage($msg);
			$mail->send();
		}

		$test_data = array();
		$test_data['is_informed'] = 1;
		$test_data['informed_count'] = count($result);
		$test_data['informed_date'] = date('Y-m-d H:i:s');
		
		$DB->UpdateRecord('test', $test_data, "id=$test_id");
		$_SESSION['success'] = count($result).' students are successfully informed.';
	}
	else
	{
		$_SESSION['success'] = 'There is not any student eligible for this test.';
	}
	
	
	
	$objResponse->addScript('reload()');
	return $objResponse;
}

function changeCandidateAuthority($candidate_id)
{
	global $DB;
	$objResponse = new xajaxResponse();
	
	$candidate_data = array();
	if($candidate_id)
	{
		$candidate_info = $DB->SelectRecord('candidate',"id=$candidate_id");
		if($candidate_info->is_disabled == 0)
		{
			$candidate_data['is_disabled'] = 1;
			$DB->UpdateRecord('candidate', $candidate_data, "id=$candidate_id");
			$objResponse->addAssign('auth_'.$candidate_id, 'src', IMAGEURL.'/enable.png');
			$objResponse->addAssign('auth_'.$candidate_id, 'title', 'Enable Student');
			$objResponse->addAlert('Student has been disabled successfully.');
		}
		else
		{
			$candidate_data['is_disabled'] = 0;
			$DB->UpdateRecord('candidate', $candidate_data, "id=$candidate_id");
			$objResponse->addAssign('auth_'.$candidate_id, 'src', IMAGEURL.'/disable.png');
			$objResponse->addAssign('auth_'.$candidate_id, 'title', 'Disable Student');
			$objResponse->addAlert('Student has been enabled successfully.');
		}
		
		return $objResponse;
	}
}

function changeTeacherAuthority($teacher_id)
{
	global $DB;
	$objResponse = new xajaxResponse();
	
	$teacher_data = array();
	if($teacher_id)
	{
		$teacher_info = $DB->SelectRecord('teacher',"id=$teacher_id");
		if($teacher_info->is_disabled == 0)
		{
			$teacher_data['is_disabled'] = 1;
			$DB->UpdateRecord('teacher', $teacher_data, "id=$teacher_id");
			$objResponse->addAssign('auth_'.$teacher_id, 'src', IMAGEURL.'/enable.png');
			$objResponse->addAssign('auth_'.$teacher_id, 'title', 'Enable Teacher');
			$objResponse->addAlert('Teacher has been disabled successfully.');
		}
		else
		{
			$teacher_data['is_disabled'] = 0;
			$DB->UpdateRecord('teacher', $teacher_data, "id=$teacher_id");
			$objResponse->addAssign('auth_'.$teacher_id, 'src', IMAGEURL.'/disable.png');
			$objResponse->addAssign('auth_'.$teacher_id, 'title', 'Disable Teacher');
			$objResponse->addAlert('Teacher has been enabled successfully.');
		}
		
		return $objResponse;
	}
}

function changeParentAuthority($parent_id)
{
	global $DB;
	$objResponse = new xajaxResponse();
	
	$parent_data = array();
	if($parent_id)
	{
		$parent_info = $DB->SelectRecord('parent',"id=$parent_id");
		if($parent_info->is_disabled == 0)
		{
			$parent_data['is_disabled'] = 1;
			$DB->UpdateRecord('parent', $parent_data, "id=$parent_id");
			$objResponse->addAssign('auth_'.$parent_id, 'src', IMAGEURL.'/enable.png');
			$objResponse->addAssign('auth_'.$parent_id, 'title', 'Enable Parent');
			$objResponse->addAlert('Parent has been disabled successfully.');
		}
		else
		{
			$parent_data['is_disabled'] = 0;
			$DB->UpdateRecord('parent', $parent_data, "id=$parent_id");
			$objResponse->addAssign('auth_'.$parent_id, 'src', IMAGEURL.'/disable.png');
			$objResponse->addAssign('auth_'.$parent_id, 'title', 'Disable Parent');
			$objResponse->addAlert('Parent has been enabled successfully.');
		}
		
		return $objResponse;
	}
}

/**
 * 	@author	:	Ashwini Agarwal
 * 	@param	:	Exam Id
 * 	@desc	:	Get test list by exam id
 */
function getTestOptionsByExam($exam_id)
{
	global $DB;
	$objResponse = new xajaxResponse();
	
	$examTest = $DB->SelectRecords('test', "exam_id = $exam_id", '*', 'ORDER BY test_name');
	
	$result = "<option value=''>Select Test</option>";
	
	if($examTest)
	{
		foreach($examTest as $test)
		{
			$selected = '';
			if(isset($_SESSION['selectedTestId']) && ($_SESSION['selectedTestId'] == $test->id))
			{
				$selected = 'selected';
				unset($_SESSION['selectedTestId']);
			}
			$result .= "<option value='$test->id' $selected>$test->test_name</option>";
		}
	}
	
	$objResponse->addScript('showTestTr();');
	$objResponse->addAssign('test-select', 'innerHTML', $result);
	return $objResponse;
}

function getExamSubjectOptions($exam_id, $selectId)
{
	global $DB;
	
	$objResponse = new xajaxResponse();
	$output = '<option value="">Select Subject</option>';
	
	$subject = array();
	if($exam_id)
	{
		$subject = getSubjectInfoByExamId($exam_id);
		if($subject == '') $subject = array();
	}

	for($counter=0; $counter<count($subject); $counter++)
	{
		$output.='<option value="'.$subject[$counter]->subject_id.'" >'.stripslashes($subject[$counter]->subject_name).'</option>';
	}
	
	$objResponse->addAssign($selectId, 'innerHTML', $output);
	return $objResponse;
}

function addTeacherSubject($exam_id, $subject_id, $deleteRow = false)
{
	global $DB;
	$objResponse = new xajaxResponse();
	
	if(!is_array($_SESSION['teacher_subject'])) $_SESSION['teacher_subject'] = array();
	
	if($deleteRow !== FALSE)
	{
		unset($_SESSION['teacher_subject'][$deleteRow]);
		$objResponse->addScript('xajax_showFilteredCandidate()');
		return $objResponse;
	}
	
	if(!$exam_id || !$subject_id)
	{
		return $objResponse;
	}
	
	foreach($_SESSION['teacher_subject'] as $teacher_subject)
	{
		if(($teacher_subject['exam_id'] == $exam_id) && ($teacher_subject['subject_id'] == $subject_id))
		{
			$objResponse->addAlert('This subject is already added.');
			return $objResponse;
		}
	}
	
	$key = uniqid();
	$_SESSION['teacher_subject'][$key]['exam_id'] = $exam_id;
	$_SESSION['teacher_subject'][$key]['subject_id'] = $subject_id;
	
	$_SESSION['teacher_subject'][$key]['exam_name'] = $exam_name = $DB->SelectRecord('examination', "id = $exam_id", 'exam_name')->exam_name;
	$_SESSION['teacher_subject'][$key]['subject_name'] = $subject_name = $DB->SelectRecord('subject', "id = $subject_id", 'subject_name')->subject_name;
	
	$row  = "<tr id='ts-$key' class='tdbgwhite'>";
	$row .= "<td class='ts-sno'></td>";
	$row .= "<td>$exam_name</td>";
	$row .= "<td>$subject_name</td>";
	$row .= "<td><a href='javascript:void(0);' onclick=\"deleteSubject('$key');\">Delete</a></td>";
	$row .= "</tr>";
	
	$objResponse->addAppend('teacher-subject','innerHTML',$row);
	$objResponse->addScript('finishSubjectAddition()');
	$objResponse->addScript('xajax_showFilteredCandidate()');
	return $objResponse;
}

/**************************************************************************************************
 * 		Added By	:		Ashwini Agarwal
 * 		Description	:		Show list of filtered candidates by exam.
 */
function showFilteredCandidate($exam_id = '', $fromTest = false, $extraFilter = array())
{
	$objResponse = new xajaxResponse();
	global $DB;
	
	$var = $fromTest ? 'test_candidates' : 'teacher_candidates';
	$candidate = $_SESSION[$var];
	$_SESSION[$var] = array();
	
	$exam_ids = array();
	if($fromTest)
	{
		if($exam_id)
		{
			$exam_ids[$exam_id] = $exam_id;
			$_SESSION[$var][$exam_id] = $candidate[$exam_id];
		}
	}
	else
	{
		foreach($_SESSION['teacher_subject'] as $teacher_subject)
		{
			$exam_id = $teacher_subject['exam_id'];
			$exam_ids[$exam_id] = $exam_id;
			$_SESSION[$var][$exam_id] = $candidate[$exam_id];
		}
	}
	
	if(count($exam_ids) > 0)
	{
		$exam_ids = implode(',', $exam_ids);
	}
	else
	{
		$blankMessage = 'Please select a course to see filtered students.';
		if(!$fromTest) 
		{	
			$blankMessage = '';
		}
		$_SESSION[$var] = array();
		$objResponse->addAssign('filtered_candidate', 'innerHTML', $blankMessage);
		return $objResponse;
	}
	
	$query = "SELECT cand.*, e.exam_name, e.id as exam_id FROM candidate cand
		JOIN exam_candidate ec on ec.candidate_id = cand.id
		JOIN examination e on ec.exam_id = e.id ";
	
	if ($_SESSION['admin_user_type'] == 'T')
	{
		$query .= " JOIN exam_candidate_teacher ect on (ect.exam_id = e.id AND ect.candidate_id = cand.id) ";
		$query .= " WHERE ect.teacher_id = '".$_SESSION['admin_user_id']."' ";
	}
	else
	{
		$query .= " where 1 ";
	}
	
	$candidate_selection_cond = '';
	if(count($extraFilter) > 0)
	{
		$extraQuery = false;
		if($extraFilter[0] && ($extraFilter[0] != null) && ($extraFilter[0] != 'null'))
		{
			$test_id = $extraFilter[0];
			$extraQuery = true;
		}
		
		if($extraFilter[1] && (count($extraFilter[1]) > 0))
		{
			$result = implode("','", $extraFilter[1]);
			$extraQuery = true;
		}
		
		if($extraQuery)
		{
			$test_cond = ($test_id) ? " AND (test_id = $test_id) " : '';
			$result_cond = $result ? "AND result IN ('$result')" : '';
			
			$candidate_selection = $DB->SelectRecord('candidate_test_history', "(exam_id=$exam_id) $test_cond $result_cond", 'GROUP_CONCAT(candidate_id) as candidate_id', 'GROUP BY test_id');
			$candidate_selection = $candidate_selection->candidate_id ? $candidate_selection->candidate_id : -1;
			$candidate_selection_cond = " AND (cand.id IN ($candidate_selection)) ";
		}
	}
	
	$query .= " $candidate_selection_cond AND ec.exam_id IN ($exam_ids) 
		ORDER BY e.exam_name, cand.first_name, cand.last_name";

	$result = $DB->RunSelectQuery($query);
	
	$table .= "<fieldset class='rounded'><legend>Select Students</legend>";
	
	if(count($result) > 0 && $result != 0)
	
	$table .= "<div id='celebs' style='max-height: 300px;overflow: auto;'>";
	$table .= '<table border="0" cellspacing="1" cellpadding="4" id="tbl_reports" width="100%" bgcolor="#e1e1e1" class="data">';
	
	if(count($result) > 0 && $result != 0)
	{
		$table .= "<thead><tr class='tblheading'><td>#</td><td></td><td>Student Name</td><td>Course Name</td></tr></thead><tbody>";
		
		for($count = 0; $count<count($result); $count++)
		{
			$class = (($count%2)==1) ? 'tdbgwhite' : 'tdbggrey';
			
			$included = '';
			$trClass = '';
			if(is_array($_SESSION[$var]) && is_array($_SESSION[$var][$result[$count]->exam_id]))
			{
				if(in_array($result[$count]->id, $_SESSION[$var][$result[$count]->exam_id]))
				{
					$included = "checked = 'checked'";
					$trClass = 'included';
				}	
			}
			
			$table .= "<tr class='$class $trClass'><td>" . ($count+1) . "</td>";
			$table .= "<td><input type='checkbox' class='include-check' id='".$result[$count]->id."' onchange='xajax_includeCandidate(".$result[$count]->exam_id.", ".$result[$count]->id.", this.checked, ".'"'.$var.'"'.");' $included /></td>";
	
			$fname=ucfirst(strtolower(stripslashes($result[$count]->first_name)));
			$lname=ucfirst(strtolower(stripslashes($result[$count]->last_name)));
			$name=$fname.' '.$lname;
			$table .= "<td>$name</td>";
			
			$table .= "<td>" . $result[$count]->exam_name . "</td></tr>";
		}
		$table .= "</tbody>";
	}
	else
	{
		$table .= "<tr class='tdbggrey'><td colspan='10'>No candidate found in this criteria.</td></tr>";
	}
	$table .= '</table></div></fieldset>';
	
	$objResponse->addAssign('filtered_candidate','innerHTML',$table);
//	$objResponse->addScript("$('.include-check').trigger('change');");
	return $objResponse;
}

/**************************************************************************************************
 * 		Added By	:		Ashwini Agarwal
 * 		Date		:		December 28, 2012
 */
function includeCandidate($exam_id, $candidate_id, $isChecked, $var)
{
	$objResponse = new xajaxResponse();
	
	if(!isset($_SESSION[$var])) 
		$_SESSION[$var] = array();
	
	if($isChecked == 'true')
		$_SESSION[$var][$exam_id][$candidate_id] = $candidate_id;
	else
		unset($_SESSION[$var][$exam_id][$candidate_id]);
	
	return $objResponse;
}

function addParentCandidate($candidate_id, $deleteRow = false)
{
	global $DB;
	$objResponse = new xajaxResponse();
	
	if(!is_array($_SESSION['parent_candidate'])) $_SESSION['parent_candidate'] = array();
	
	if($deleteRow !== FALSE)
	{
		unset($_SESSION['parent_candidate'][$deleteRow]);
		return $objResponse;
	}
	
	if(!$candidate_id)
	{
		return $objResponse;
	}
	
	foreach($_SESSION['parent_candidate'] as $parent_candidate)
	{
		if(($parent_candidate['candidate_id'] == $candidate_id))
		{
			$objResponse->addAlert('This student is already added.');
			return $objResponse;
		}
	}
	
	$key = uniqid();
	$_SESSION['parent_candidate'][$key]['candidate_id'] = $candidate_id;
	
	$candidate = $DB->SelectRecord('candidate', "id = $candidate_id");
	$candidate_name = $_SESSION['parent_candidate'][$key]['candidate_name'] = $candidate->first_name .' '. $candidate->last_name;
	
	$row  = "<tr id='pc-$key' class='tdbgwhite'>";
	$row .= "<td class='pc-sno'></td>";
	$row .= "<td>$candidate_name</td>";
	$row .= "<td><a href='javascript:void(0);' onclick=\"deleteCandidate('$key');\">Delete</a></td>";
	$row .= "</tr>";
	
	$objResponse->addAppend('parent-candidate','innerHTML',$row);
	$objResponse->addScript('finishCandidateAddition()');
	return $objResponse;
}

function enableHomework($homework_id)
{
	global $DB;
	$objResponse = new xajaxResponse();
	
	$homework_data = array();
	if($homework_id)
	{
		$isReload = true;
		$homework_info = $DB->SelectRecord('homework',"id=$homework_id", 'enable_homework');
		if($homework_info->enable_homework=='Y')
		{
			$homework_data['enable_homework'] = 'N';
			$DB->UpdateRecord('homework', $homework_data, "id=$homework_id");
			$objResponse->addAssign('enableHomework_'.$homework_id, 'src',IMAGEURL.'/right.gif');
			$objResponse->addAssign('enableHomework_'.$homework_id, 'title','Enable homework');
			$objResponse->addAlert('Homework has been disabled successfully.');
		}
		else
		{
			$homework_data['enable_homework'] = 'Y';
			$DB->UpdateRecord('homework', $homework_data, "id=$homework_id");
			$objResponse->addAssign('enableHomework_'.$homework_id, 'src',IMAGEURL.'/disable.gif');
			$objResponse->addAssign('enableHomework_'.$homework_id, 'title','Disable homework');
			$objResponse->addAlert('Homework has been enabled successfully.');
		}

		if($isReload)
		$objResponse->addScript('reload()');
	}
	
	return $objResponse;
}

/************************************************************************
 * 	Author		:	Ashwini Agarwal
 * 	Date		:	November 2, 2012
 * 	Description	:	Send mail to eligible candidates for particular homework.
 */

function informCandidatesForHomework($homework_id)
{
	$objResponse = new xajaxResponse();
	global $DB;
	
	$teacher_condition = '';
	$homework_details = $DB->SelectRecord('homework', "id = $homework_id");
	if($homework_details->teacher_id)
	{
		$teacher_candidate = $DB->SelectRecord('exam_candidate_teacher', "((teacher_id = '$homework_details->teacher_id') AND (exam_id = $homework_details->exam_id))", 'GROUP_CONCAT(DISTINCT candidate_id) as candidate_id');
		
		if($teacher_candidate && $teacher_candidate->candidate_id)
		$teacher_condition = " AND (c.id IN ($teacher_candidate->candidate_id)) ";
	}
	
	$query = "SELECT c.first_name,c.last_name,c.email,h.* 
			FROM homework as h
			JOIN examination as e ON e.id = h.exam_id
			JOIN exam_candidate as ec ON ec.exam_id = h.exam_id
			JOIN candidate as c ON c.id = ec.candidate_id
			JOIN homework_candidate tc on (tc.candidate_id = c.id AND tc.homework_id = h.id)
			WHERE (h.id = '$homework_id') AND (c.email IS NOT NULL) $teacher_condition
			GROUP BY c.id;";
	
	$result = $DB->RunSelectQuery($query);
	
	if($result && (count($result) > 0))
	{	
		$message = '<p>This is to inform you that homework <b>'.$result[0]->homework_name.'</b> is assign to you.</p>';
		$message .= '<b><u>Homework Details</u></b>';
		$message .= '<br>Homework Start Date - '. date('d-m-Y h:i:s A', strtotime($result[0]->homework_date));
		$message .= '<br>Homework End Date - '. date('d-m-Y h:i:s A', strtotime($result[0]->homework_date_end));
		$message .= '<br>Maximum Marks - '.$result[0]->maximum_marks;
		$message .= '<br><br>With Best Wishes,';
		$message .= '<br><b>Assess All Team</b>';
		
		require_once('lib/mailer.php');
		foreach($result as $r)
		{
			$msg = 'Hello '.ucwords($r->first_name.' '.$r->last_name) . $message.'<br>';
			$mail = new mailer();
			$mail->addTo($r->email, ucwords($r->first_name.' '.$r->last_name));
			$mail->setSubject('Assess All - Homework');
			$mail->setMessage($msg);
			$mail->send();
		}

		$homework_data = array();
		$homework_data['is_informed'] = 1;
		$homework_data['informed_count'] = count($result);
		$homework_data['informed_date'] = date('Y-m-d H:i:s');
		
		$DB->UpdateRecord('homework', $homework_data, "id=$homework_id");
		$_SESSION['success'] = count($result).' students are successfully informed.';
	}
	else
	{
		$_SESSION['success'] = 'There is not any student eligible for this homework.';
	}
	
	$objResponse->addScript('reload()');
	return $objResponse;
}

function assignMarksToSubjectiveTypeQuestion($candidate_id, $table_id, $question_id, $marks, $table, $maxTotalMarks = 0)
{
	$objResponse = new xajaxResponse();
	global $DB;
	
	$common_condition = " candidate_id=$candidate_id AND {$table}_id=$table_id ";
	$questionTab = ($table == 'test') ? 'candidate_question_history' : 'candidate_homework_question_history';
	$subjectTab = "candidate_{$table}_subject_history";
	$mainTab = "candidate_{$table}_history";
	
	$exam_id = $DB->SelectRecord($table, "id=$table_id")->exam_id;
	
	$pre_value = $DB->SelectRecord($questionTab, "$common_condition AND question_id=$question_id");
	$DB->UpdateRecord($questionTab, array("marks_obtained"=>$marks), "$common_condition AND question_id=$question_id");
	
	$pre_marks = $pre_value->marks_obtained;
	$subject_id = $pre_value->subject_id;
	
	$subject_marks = $DB->SelectRecord($subjectTab, "$common_condition AND subject_id=$subject_id")->marks_obtained;
	
	$exam_subject = $DB->SelectRecord('exam_subjects',"exam_id=".$exam_id." and subject_id=".$subject_id);
	$min_marks = $exam_subject->subject_max_mark * $exam_subject->subject_min_mark / 100;
	$subject_marks = $subject_marks - $pre_marks + $marks;
	$update = array("marks_obtained"=>$subject_marks);
	
	$update['result'] = 'P';
	if($subject_marks < $min_marks)
	{
		$update['result'] = 'F';
	}
	$DB->UpdateRecord($subjectTab, $update, "$common_condition AND subject_id=$subject_id");

	$testResult = 'F';
	if($update['result'] == 'P')
	{
		$testResult = 'P';
		$allSubjects = $DB->SelectRecords($subjectTab, "$common_condition");
		foreach($allSubjects as $sub)
		{
			if($sub->result == 'F')
			{
				$testResult = 'F';
				break;
			}
		}
	}
	
	$total_marks = $DB->SelectRecord($mainTab, "$common_condition");
	$total_mark = $total_marks->marks_obtained - $pre_marks + $marks;
	$total_percentage = $maxTotalMarks ? $total_mark / $maxTotalMarks * 100 : 0;
	
	$update = array("marks_obtained"=>$total_mark, 'percentage'=>$total_percentage, 'result'=>$testResult);
	$DB->UpdateRecord($mainTab, $update, "$common_condition");
	
	if($table == 'test')
	{
		$objResponse->addScript('location.reload();');	
	}
	return $objResponse;
}

function studentLinkRequest($id, $is_approve)
{
	$objResponse = new xajaxResponse();
	global $DB;

	if($is_approve)
	{
		$DB->UpdateRecord("parent_candidate", array('is_approved'=>'1'), "id = '$id'");
	}
	else
	{
		$DB->DeleteRecord("parent_candidate", "id = '$id'");
	}
	
	return $objResponse;
}

function sendTestReult($candidate_id, $exam_id, $test_id, $to)
{
	$objResponse = new xajaxResponse();
	global $DB;
	$error = '';
	
	if($to == 'parent')
	{
		$candidate_parent = $DB->SelectRecord('parent_candidate', "((candidate_id = $candidate_id) AND (is_approved = 1))", 'GROUP_CONCAT(DISTINCT parent_id) as parent_id', 'GROUP BY candidate_id');
		if(!$candidate_parent || !$candidate_parent->parent_id)
		{
			$error = 'This candidate is not associated with any parent.';
		}
		
		$reciever = $candidate_parent->parent_id;
	}
	elseif($to == 'teacher')
	{
		$candidate_teacher = $DB->SelectRecord('exam_candidate_teacher', "((candidate_id = $candidate_id) AND (exam_id = $exam_id))", 'GROUP_CONCAT(DISTINCT teacher_id) as teacher_id', 'GROUP BY candidate_id');
		if(!$candidate_teacher || !$candidate_teacher->teacher_id)
		{
			$error = 'This candidate is not associated with any teacher.';
		}
		$reciever = $candidate_teacher->teacher_id;
	}
	elseif($to == 'candidate')
	{
		$reciever = $candidate_id;
	}
	else
	{
		$error = 'An error occured while sending mail.';
	}
	
	if($error)
	{
		$objResponse->addAlert($error);
		return $objResponse;
	}
	$totalCount =0;
	$exam_type = 'test';
	require_once('includes/dbqueries.php');
	$exam_subject = getSubjectNameByExamId($exam_id, $test_id);
	
	$subject_result = getResultSheetReport($totalCount, $exam_type, $exam_id, $test_id, $candidate_id);
	
	$subject_message = '<table width="500px" align="center" border="1" cellspacing="0" cellpadding="5">';
	$subject_message.= '<thead><th width="10px">#</th><th width="200px">Subject</th><th width="100px">Marks Obtained</th><th width="100px">Result</th></thead>';
	$subject_message.= '<tbody>';
	
	$result = 'Pass';
	$counter = 1;
	foreach($subject_result as $sr)
	{
		$candidate_name = ucwords($sr->first_name.' '.$sr->last_name);
		$test_name = $sr->test_name;
		$exam_name = $sr->exam_name;
		
		if($sr->result == 'F') $result = 'Fail';
		
		$subject_message.= '<tr>';
		$subject_message.= "<td>".$counter++."</td>";
		$subject_message.= "<td>$sr->subject_name</td>";
		$subject_message.= "<td>$sr->marks_obtained</td>";
		$subject_message.= "<td>".($sr->result == 'F')?'Fail':'Pass'."</td>";
		$subject_message.= '</tr>';
	}
	$subject_message.= '</tbody>';
	$subject_message.= '</table>';
	
	$message = "$candidate_name has appeared in <b>".$test_name. " </b>under </b>".$exam_name." </b>course.<br>";
	$message.= "Test result is <strong>$result</strong>.<br><br>";
	$message.= "<strong><u>Subject Result</u></strong><br><br>$subject_message<br><br>";
	$message.= "<div>To view detailed result please <a href='".ROOTURL."'>login</a> to your Assess All account.</div>";
	$message.= '<br><br>With Best Wishes,';
	$message.= '<br><b>Assess All Team</b></br></br>';
	
	$result = $DB->SelectRecords($to, "id IN ($reciever)");
	
	$file = 'result_details'.uniqid().'.pdf';
	$pdf_file = ADMINROOT.'/uploadfiles/'.$file;
	$server_addr = $_SERVER['SERVER_ADDR'];
	$pdf_data = file_get_contents(ROOTURL."/index.php?mod=export&candidate_result_details=15&candidate_id=$candidate_id&test_id=$test_id&exam_id=$exam_id&SERVER_ADDR=$server_addr");
	

	file_put_contents($pdf_file, $pdf_data);
/*	$fp = fopen($pdf_file, 'w');
	echo	fwrite($fp, $pdf_data);
	fclose($fp);
*/		
	require_once('lib/mailer.php');
	foreach($result as $r)
	{	 
	
			
		//$msg = 'Hello '.ucwords($r->first_name.' '.$r->last_name).",<br><br>".$message;
		$msg ="<div style='background: none repeat scroll 0% 0% rgb(241, 241, 241); border: 1px solid rgb(6, 106, 117); width: 750px; font-family: Georgia;height:auto; min-height: 565px;'>
				<div style='width: 100%; background: none repeat scroll 0% 0% rgb(6, 106, 117); height: 80px;'>
				<div style='width:150px; height:60px;margin:8px; float:left'>
				<img alt='Assess all' src='http://assessall.com/wp-content/uploads/2014/07/logo.png'>
				</div>
				</div>
				  <div style='margin: auto; float: left; text-align: left; padding: 10px 10px 0px; width: 95%;height:auto;min-height: 435px;'>
				  <h2>Hello ".ucwords($r->first_name.' '.$r->last_name).",</h2>
				  <br><br>".$message."
				  
				  </div>
				  <div style='height:30px;padding-top:10px;float:left; width:100%;text-align:center;background:#1d5770;'>
					<span style='color:#fff;font-weight:bold'>&copy; 2014 Assess All.</span>
					
				  </div>
				</div>";
		
		$mail = new mailer();
		$mail->addTo($r->email, ucwords($r->first_name.' '.$r->last_name));
		$mail->setSubject('Assess All - Test Result');
		$mail->setMessage($msg);

		//$mail->addAttachment($pdf_file);

		$mail->send();
		
		
		/*	$filename = $file;
		$file = $pdf_file;
		$file_size = filesize($file);
		$handle = fopen($file, "r");
		$content = fread($handle, $file_size);
		fclose($handle);
		$content = chunk_split(base64_encode($content));
		$uid = md5(uniqid(time()));
		$name = basename($file);
		$header = "From: Assess All <assessall@assessall.com>\r\n";
		$header .= "Reply-To: assessall@assessall\r\n";
		$header .= "MIME-Version: 1.0\r\n";
		$header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
		$header .= "This is a multi-part message in MIME format.\r\n";
		$header .= "--".$uid."\r\n";
		$header .= "Content-type:text/html; charset=iso-8859-1\r\n";
		$header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
		$header .= $msg."\r\n\r\n";
		$header .= "--".$uid."\r\n";
		$header .= "Content-Type: application/octet-stream; name=\"".$filename."\"\r\n"; // use different content types here
		$header .= "Content-Transfer-Encoding: base64\r\n";
		$header .= "Content-Disposition: attachment; filename=\"".$filename."\"\r\n\r\n";
		$header .= $content."\r\n\r\n";
		$header .= "--".$uid."--";
		
		mail($r->email, 'Assess All - Test Result', $msg, $header);	*/
	}

	//@unlink($pdf_file);
	
	if($to == 'candidate') $to = 'Inbox';
	$objResponse->addAlert("Result successfully send to $to.");
	return $objResponse;
}

require("incajax.php");
$xajax->processRequests();
?>