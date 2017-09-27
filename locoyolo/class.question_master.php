<?php
/*****************Developed by :- Richa verma
	                Date         :- 2-july-2011
					Module       :- Question master
					Purpose      :- Class for function to add and edit question
***********************************************************************************/

class QuestionMaster
{
	//==========================================================================
	//to add a question
	//==========================================================================	
	function addQuestion()
	{
		global $DB,$frmdata;
		//print_r($frmdata);print_r($_FILES);exit;
		$err='';
		$_SESSION['correct_ans']=$frmdata['correct_ans'];
		if ($frmdata['question_title']=='')
		{
			$err.="Please enter question.<br>";
		}
		else
		{
			$question_info = $DB->SelectRecord('question', "question_title='".htmlentities(addslashes($frmdata['question_title']))."'");
			if($question_info)
			{
				$err.="This question is already exist.<br>";
			}
		}
		
		if ($frmdata['question_level']=='')
		{
			 $err.="Please select level.<br>";
		}

		if($frmdata['subject_id']=='')
		{
			 $err.="Please select subject.<br>";
		}

		if ($frmdata['question_type']=='')
		{
			 $err.="Please select question type.<br>";
		}
		else
		{
			if($frmdata['question_type'] == "I")
			{
				$no_of_images = (isset($frmdata['no_of_images']) && ($frmdata['no_of_images'] >= 3)) ? $frmdata['no_of_images'] : 3;
				$no_of_options = (isset($frmdata['no_of_options']) && ($frmdata['no_of_options'] >= 4)) ? $frmdata['no_of_options'] : 4;
				$question_mark = isset($frmdata['question_mark']) ? $frmdata['question_mark'] : 0;
				
				$_SESSION['no_of_images'] = $no_of_images;
				$_SESSION['no_of_options'] = $no_of_options;
				$_SESSION['question_mark'] = $question_mark;
				
				for($counter=1;$counter<=$no_of_images;$counter++)
				{
					if(isset($_SESSION['fileObject_image_'.$counter]))
					{
						$_FILES['image_'.$counter]=$_SESSION['fileObject_image_'.$counter];
						$_SESSION['image_'.$counter] = $_SESSION['fileObject_image_'.$counter]['final_path'];
					}
					
					if($counter <= 3 && ($counter != $question_mark))
					{
						if($_FILES['image_'.$counter]['name']=='')
						{
							$err.="Please upload atleast first three question images.<br>";
							break;
						}
					}
					
					if($_FILES['image_'.$counter]['tmp_name']!='' && ($counter != $question_mark))
					{
						if($_FILES['image_'.$counter]['type']!='image/jpeg' && $_FILES['image_'.$counter]['type']!='image/jpg' && $_FILES['image_'.$counter]['type']!='image/gif' && $_FILES['image_'.$counter]['type']!='image/png'  && $_FILES['image_'.$counter]['type']!='image/pjpeg' && $_FILES['image_'.$counter]['type']!='image/x-png')
						{
							$err.="Please upload question image $counter in correct format.<br>";
						}
						
						if($_FILES['image_'.$counter]['size']>2000000)
						{
							$err.="Please upload question image $counter not more than 2MB.<br>";
						}
					}
				}
				
				for($counter=1;$counter<=$no_of_options;$counter++)
				{
					if(isset($_SESSION['fileObject_option_'.$counter]))
					{
						$_FILES['option_'.$counter]=$_SESSION['fileObject_option_'.$counter];
						$_SESSION['option_'.$counter] = $_SESSION['fileObject_option_'.$counter]['final_path'];
					}
					if($counter <= 4)
					{
						if($_FILES['option_'.$counter]['name']=='')
						{
							$err.="Please upload atleast first four option images.<br>";
							break;
						}
					}
					
					if($_FILES['option_'.$counter]['tmp_name']!='')
					{
						if($_FILES['option_'.$counter]['type']!='image/jpeg' && $_FILES['option_'.$counter]['type']!='image/jpg' && $_FILES['option_'.$counter]['type']!='image/gif' && $_FILES['option_'.$counter]['type']!='image/png' && $_FILES['option_'.$counter]['type']!='image/pjpeg' && $_FILES['option_'.$counter]['type']!='image/x-png')
						{
							$err.="Please upload option image $counter in correct format.<br>";
						}
						
						if($_FILES['option_'.$counter]['size']>2000000)
						{
							$err.="Please upload option image $counter not more than 2MB.<br>";
						}
					}
				}
			}
			elseif($frmdata['question_type'] == "S")
			{
				// do notning;
			}
			elseif($frmdata['question_type'] == "MT")
			{
				$min_cols = 2;
				$left_vals = array();
				$right_vals = array();
				
				$no_of_cols = $frmdata['no_of_cols'];
				
				if($no_of_cols < $min_cols)
				{
					$err .= "Please add atleast $min_cols match coloumns.<br>";
				}
				else
				{
					$left_cols = 0;
					$suberr = '';
					for($counter = 1; $counter <= $no_of_cols; $counter++)
					{
						$frmdata['left_col_'. $counter] = trim($frmdata['left_col_'. $counter]);
						$frmdata['right_col_'. $counter] = trim($frmdata['right_col_'. $counter]);
						
						$_SESSION['left_col_'. $counter] = trim($frmdata['left_col_'. $counter]);
						$_SESSION['right_col_'. $counter] = trim($frmdata['right_col_'. $counter]);
						
						if($frmdata['right_col_'. $counter])
						$right_vals[] = strtolower($frmdata['right_col_'. $counter]);
						
						if($frmdata['left_col_'. $counter] != '')
						{
							$left_cols++;
							$left_vals[] = strtolower($frmdata['left_col_'. $counter]);
							 
							if($frmdata['right_col_'. $counter] == '')
							{
								$suberr .= 'Please enter match value in coloumn no. ' . $counter . '<br>';
							}
						}
					}
					if($left_cols < $min_cols)
					{
						$suberr = "Please fill entries for atleast $min_cols match coloumns.<br>";
					}
					else
					{
						$unique_err = '';
						if( count($left_vals) != count(array_unique($left_vals)))
						{
							$unique_err .= 'Please enter unique values in left coloumn.<br>';
						}
						if( count($right_vals) != count(array_unique($right_vals)))
						{
							$unique_err .= 'Please enter unique values in right coloumn.<br>';
						}
						
						if($unique_err) $suberr = $unique_err;
					}
				
					$err .= ($suberr != '') ? $suberr : '';
				}
			}
			elseif(isset($frmdata['option_3']))
			{
				$unique_options = array();
				for($counter=1;$counter<=4;$counter++)
				{
					$_SESSION['option_'.$counter]=$frmdata['option_'.$counter];//used to show form value again, if validation fail
					if($frmdata['option_'.$counter]=='')
					{
						$err.="Please enter all options.<br>";
						break;
					}
					$unique_options[] = $frmdata['option_'.$counter];
				}
				if(count($unique_options) != count(array_unique($unique_options)))
				{
					$err .= "Please enter unique options.<br>";
				}
			}
			else
			{
				$unique_options = array();
				for($counter=1;$counter<=2;$counter++)
				{
					$_SESSION['option_'.$counter]=$frmdata['option_'.$counter];
					if($frmdata['option_'.$counter]=='')
					{
						$err.="Please enter all options.<br>";
						break;
					}
					$unique_options[] = $frmdata['option_'.$counter];
				}
				if(count($unique_options) != count(array_unique($unique_options)))
				{
					$err .= "Please enter unique options.<br>";
				}
			}
			
			if(($frmdata['correct_ans']=='') && ($frmdata['question_type'] != "S") && ($frmdata['question_type'] != "MT"))
			{
				$err.="Please select answer.<br>";
			}
			else if($frmdata['question_type'] == "I")
			{
				foreach($frmdata['correct_ans'] as $counter)
				{
					if($_FILES['option_'.$counter]['tmp_name']=='')
					{
						$err.="Please select a valid answer.<br>";
						break;
					}
				}
			}
		}
		
		if ($frmdata['marks']=='')
		{
			 $err.="Please enter marks.<br>";
		}
		
		$frmdata['min_time_to_solve'] = $frmdata['minimum_min']*60+$frmdata['minimum_sec'];
		$frmdata['max_time_to_solve'] = $frmdata['maximum_min']*60+$frmdata['maximum_sec'];
		
		if($frmdata['max_time_to_solve'] < $frmdata['min_time_to_solve'])
		{
			$err .= "Minimum solving time must be less than maximum solving time.<br>";
		}
		
		if($_FILES['image']['name'] != '')
		{
			if ( !(($_FILES['image']['type'] == 'image/jpeg')
				|| ($_FILES['image']['type'] == 'image/jpg')
				|| ($_FILES['image']['type'] == 'image/gif')
				|| ($_FILES['image']['type'] == 'image/png')
				|| ($_FILES['image']['type'] == 'image/pjpeg')
				|| ($_FILES['image']['type'] == 'image/x-png')))
				
			{
				$err .= "Please upload hint image in correct format.<br>";
			}
			
			else if($_FILES['image']['size'] > 2000000)
			{
				$err .= "Please upload hint image less than 2MB in size.<br>";
			}
		}
		
		if($_FILES['video']['name'] != '')
		{
			if ( !(	($_FILES['video']['type'] == 'video/mpeg')
				|| ($_FILES['video']['type'] == 'video/mpeg4')
				|| ($_FILES['video']['type'] == 'video/avi')
				|| ($_FILES['video']['type'] == 'video/mov')
				|| ($_FILES['video']['type'] == 'video/AVI')
				|| ($_FILES['video']['type'] == 'video/mpg')
				|| ($_FILES['video']['type'] == 'video/wmv')
				|| ($_FILES['video']['type'] == 'video/vid') 
				|| ($_FILES['video']['type'] == 'video/x-flv')
				|| ($_FILES['video']['type'] == 'application/octet-stream')))
				
			{
				$err .= "Please upload video in correct format.<br>";
			}
			
			else if($_FILES['video']['size'] > 20000000)
			{
				$err .= "Please upload video less than 20MB in size.<br>";
			}
		}
		
		if($err!='')
		{
			$_SESSION['error']=$err;
		}
		elseif($err=='')
		{
			if($frmdata['stream_id']=='')
			{
				$frmdata['stream_id']='NULL';
			}
			if($frmdata['subject_id']=='')
			{
				$frmdata['subject_id']='NULL';
			}
			
			if($_FILES['image']['name'] != '')
			{
				$FileObject = (object)$_FILES['image'];
				UploadImageFile($image_path,$FileObject,'image');
				$frmdata['image'] = $image_path;
			}

			if($_FILES['video']['name'] != '')
			{
				$FileObject = (object)$_FILES['video'];
				UploadFile($video_path,$FileObject,'video');
				$frmdata['video'] = $video_path;
			}
			
			/**********************************************/
				$frmdata['question_title'] = preg_replace('#<script(.*?)>(.*?)</script>#is', "", $frmdata['question_title']);
				$frmdata['question_title'] = str_replace('  ', ' &nbsp;', str_replace('&nbsp;', ' ', $frmdata['question_title']));
				$ID=$DB->InsertRecord('question',$frmdata);

				if($frmdata['question_type'] == 'I')
				{
					for($counter=1;$counter<=$no_of_images;$counter++)
					{
						$frmdata['image_path'] = '';
						if(isset($_SESSION['fileObject_image_'.$counter]))
						{
							$frmdata['image_path']=$_SESSION['fileObject_image_'.$counter]['final_path'];
							unset($_SESSION['fileObject_image_'.$counter]);
						}

						if($counter == $question_mark)
							$frmdata['image_path'] = 0;


						unset($_SESSION['image_'.$counter]);
						$frmdata['question_id']=$ID;							
						
						if($frmdata['image_path'] !== '')
						$DB->InsertRecord('question_image',$frmdata);
					}
					
					$correct_ans = array();
					for($counter=1;$counter<=$no_of_options;$counter++)
					{
						if(isset($_SESSION['fileObject_option_'.$counter]))
						{
							$frmdata['answer_title'] = $_SESSION['fileObject_option_'.$counter]['final_path'];
							unset($_SESSION['fileObject_option_'.$counter]);
						
							unset($_SESSION['option_'.$counter]);
							$frmdata['question_id']=$ID;							
							
							if(in_array($counter, $frmdata['correct_ans']))
							{
								$correct_ans[]=$DB->InsertRecord('answer',$frmdata);					
							}
							else
							{
								$DB->InsertRecord('answer',$frmdata);
							}
							//echo "<br>".$image_path;exit;
						}
					}
					
					if(isset($_SESSION['on_of_images']))
						unset($_SESSION['on_of_images']);
					if(isset($_SESSION['on_of_options']))
						unset($_SESSION['on_of_options']);
				}
				else if($frmdata['question_type'] == "S")
				{
					//do nothing;
				}
				else if($frmdata['question_type'] == 'MT')
				{
					$left = array();
					$left['question_id'] = $ID;

					$right = array();
					$right['question_id'] = $ID;
					
					for($counter = 1; $counter <= $frmdata['no_of_cols']; $counter++)
					{
						$right_id = '';
						$right['value'] = '';
						
						if($frmdata['right_col_' . $counter] != '')
						{
							$right['value'] = $frmdata['right_col_' . $counter];
							unset($_SESSION['right_col_' . $counter]);
							
							$right_id = $DB->InsertRecord('question_match_right',$right);
							
							$left['value'] = '';
							if($frmdata['left_col_' . $counter] != '')
							{
								$left['value'] = $frmdata['left_col_' . $counter];
								unset($_SESSION['left_col_' . $counter]);
								
								$left['answer_id'] = $right_id;
								$DB->InsertRecord('question_match_left',$left);
							}
						}	
					}
				}
				elseif (isset($frmdata['option_3']))
				{
					$correct_ans = array();
					for($counter=1;$counter<=4;$counter++)
					{
						$frmdata['answer_title']=$frmdata['option_'.$counter];
						//$_SESSION['option_'.$counter]=$frmdata['option_'.$counter];
						unset($_SESSION['option_'.$counter]);
						$frmdata['question_id']=$ID;
						if(in_array($counter, $frmdata['correct_ans']))
						{
							$correct_ans[]=$DB->InsertRecord('answer',$frmdata);					
						}
						else
						{
							$DB->InsertRecord('answer',$frmdata);
						}
					}
				}
				else
				{
					$correct_ans = '';
					for($counter=1;$counter<=2;$counter++)
					{
						$frmdata['answer_title']=$frmdata['option_'.$counter];
						$frmdata['question_id']=$ID;
						unset($_SESSION['option_'.$counter]);
						if($frmdata['correct_ans']==$counter)
						{
							$correct_ans=$DB->InsertRecord('answer',$frmdata);
						}
						else
						{
							$DB->InsertRecord('answer',$frmdata);
						}
						
					}
				}
				
				$frmdata['question_id']=$ID;
				if(($frmdata['question_type'] != "S") && ($frmdata['question_type'] != "MT"))
				{
					if(is_array($correct_ans))
					{
						foreach($correct_ans as $cans)
						{
							$frmdata['answer_id']=$cans;
							$DB->InsertRecord('question_answer',$frmdata);
						}
					}
					else
					{
						$frmdata['answer_id']=$correct_ans;
						$DB->InsertRecord('question_answer',$frmdata);
					}
				}
				
				$_SESSION['success']="Question has been added successfully.";
				$frmdata='';
				Redirect(CreateURL('index.php','mod=question_master&do=manage'));
			exit;
		}
	}	
	//==========================================================================
	
	
	//==========================================================================
	// to edit question information
	//==========================================================================
	function editQuestion($nameID)
	{
		global $DB,$frmdata;
		$err='';
		//echo "fd"; exit;
		$question_id=$nameID;
		$_SESSION['correct_ans']=$frmdata['correct_ans'];
		$question = $DB->SelectRecord('question', "id=$nameID");
		$quest_opt_exist=$DB->selectRecords('answer','question_id='.$question_id);
		
		//echo '<pre>';print_r($frmdata);exit;
		
		if ($frmdata['question_title']=='')
		{
			$err.="Please enter question.<br>";
		}
		else
		{
			$question_info = $DB->SelectRecord('question', "question_title='".htmlentities(addslashes($frmdata['question_title']))."' and id!=$nameID");
			if($question_info)
			{
				$err.="This question is already exist.<br>";
			}
		}
		
		if ($frmdata['question_level']=='')
		{
			 $err.="Please select level.<br>";
		}
		if($frmdata['subject_id']=='')
		{
			 $err.="Please select subject.<br>";
		}
		else
		{
			$candidate_question_history = $DB->SelectRecords('candidate_question_history','question_id='.$nameID, '*', 'group by test_id');
			if($candidate_question_history)
			{
				$question_info = $DB->SelectRecord('question','id='.$nameID);
			
				if($question_info->subject_id!=$frmdata['subject_id'])
				{
					$count = count($candidate_question_history);
					if($count==1)
					{
						$err.= "You can't change subject because ".$count." test is conducted with this question.";
					}
					else
					{
						$err.= "You can't change subject because ".$count." tests are conducted with this question.";
					}	
				}
			}
		}

		
		if ($frmdata['question_type']=='')
		{
			 $err.="Please select question type.<br>";
		}
		else 
		{
			if($frmdata['question_type'] == "I")
			{
				$no_of_images = (isset($frmdata['no_of_images']) && ($frmdata['no_of_images'] >= 3)) ? $frmdata['no_of_images'] : 3;
				$no_of_options = (isset($frmdata['no_of_options']) && ($frmdata['no_of_options'] >= 4)) ? $frmdata['no_of_options'] : 4;
				$question_mark = isset($frmdata['question_mark']) ? $frmdata['question_mark'] : 0;
				
				$_SESSION['no_of_images'] = $no_of_images;
				$_SESSION['no_of_options'] = $no_of_options;
				$_SESSION['question_mark'] = $question_mark;
				
				
				for($counter=1;$counter<=$no_of_images;$counter++)
				{
					if(isset($_SESSION['fileObject_image_'.$counter]))
					{
						$_FILES['image_'.$counter]=$_SESSION['fileObject_image_'.$counter];
						$_SESSION['image_'.$counter] = $_SESSION['fileObject_image_'.$counter]['final_path'];
					}
					
					if($counter <= 3 && ($counter != $question_mark))
					{
						if(($_FILES['image_'.$counter]['name']=='') && ($_SESSION['pre_image_'.$counter] == ''))
						{
							$err.="Please upload atleast first three question images.<br>";
							break;
						}
					}
					
					if($_FILES['image_'.$counter]['tmp_name']!='' && ($counter != $question_mark))
					{
						if($_FILES['image_'.$counter]['type']!='image/jpeg' && $_FILES['image_'.$counter]['type']!='image/jpg' && $_FILES['image_'.$counter]['type']!='image/gif' && $_FILES['image_'.$counter]['type']!='image/png' && $_FILES['image_'.$counter]['type']!='image/pjpeg' && $_FILES['image_'.$counter]['type']!='image/x-png')
						{
							$err.="Please upload question image $counter in correct format.<br>";
						}
						
						if($_FILES['image_'.$counter]['size']>2000000)
						{
							$err.="Please upload question image $counter not more than 2MB.<br>";
						}
					}
				}
				for($counter=1;$counter<=$no_of_options;$counter++)
				{
					if(isset($_SESSION['fileObject_option_'.$counter]))
					{
						$_FILES['option_'.$counter]=$_SESSION['fileObject_option_'.$counter];
						$_SESSION['option_'.$counter] = $_SESSION['fileObject_option_'.$counter]['final_path'];
					}
					
					if($counter <= 4)
					{
						if($_FILES['option_'.$counter]['name']=='' && ($_SESSION['initial_answer_'.$counter] == ''))
						{
							$err.="Please upload atleast first four option images.<br>";
							break;
						}
					}					
					if($_FILES['option_'.$counter]['tmp_name']!='')
					{
						if($_FILES['option_'.$counter]['type']!='image/jpeg' && $_FILES['option_'.$counter]['type']!='image/jpg' && $_FILES['option_'.$counter]['type']!='image/gif' && $_FILES['option_'.$counter]['type']!='image/png' && $_FILES['option_'.$counter]['type']!='image/pjpeg' && $_FILES['option_'.$counter]['type']!='image/x-png')
						{
							$err.="Please upload option image $counter in correct format.<br>";
						}
						
						if($_FILES['option_'.$counter]['size']>2000000)
						{
							$err.="Please upload option image $counter not more than 2MB.<br>";
						}
					}
				}
			}
			else if ($frmdata['question_type'] == "S")
			{
				//do nothing;
			}
			elseif($frmdata['question_type'] == "MT")
			{
				$min_cols = 2;
				$left_vals = array();
				$right_vals = array();
				
				$no_of_cols = $frmdata['no_of_cols'];
				
				if($no_of_cols < $min_cols)
				{
					$err .= "Please add atleast $min_cols match coloumns.<br>";
				}
				else
				{
					$left_cols = 0;
					$suberr = '';
					for($counter = 1; $counter <= $no_of_cols; $counter++)
					{
						$frmdata['left_col_'. $counter] = trim($frmdata['left_col_'. $counter]);
						$frmdata['right_col_'. $counter] = trim($frmdata['right_col_'. $counter]);	
					
						$_SESSION['left_col_'. $counter] = $frmdata['left_col_'. $counter];
						$_SESSION['right_col_'. $counter] = $frmdata['right_col_'. $counter];
						
						if($frmdata['right_col_'. $counter])
						$right_vals[] = strtolower($frmdata['right_col_'. $counter]);
						
						if($frmdata['left_col_'. $counter] != '')
						{
							$left_cols++;
							$left_vals[] = strtolower($frmdata['left_col_'. $counter]);
							 
							if($frmdata['right_col_'. $counter] == '')
							{
								$suberr .= 'Please enter match value in coloumn no. ' . $counter . '<br>';
							}
						}
					}
					if($left_cols < $min_cols)
					{
						$suberr = "Please fill entries for atleast $min_cols match coloumns.<br>";
					}
					else
					{
						$unique_err = '';
						if( count($left_vals) != count(array_unique($left_vals)))
						{
							$unique_err .= 'Please enter unique values in left coloumn.<br>';
						}
						if( count($right_vals) != count(array_unique($right_vals)))
						{
							$unique_err .= 'Please enter unique values in right coloumn.<br>';
						}
						
						if($unique_err) $suberr = $unique_err;
					}
				
					$err .= ($suberr != '') ? $suberr : '';
				}
			}
			elseif(isset($frmdata['option_3']))
			{
				$unique_options = array();
				for($counter=1;$counter<=4;$counter++)
				{
					$_SESSION['option_'.$counter]=$frmdata['option_'.$counter];//used to show form value again, if validation fail
					if($frmdata['option_'.$counter]=='')
					{
						$err.="Please enter all options.<br>";
						break;
					}
					$unique_options[] = $frmdata['option_'.$counter];
				}
				if(count($unique_options) != count(array_unique($unique_options)))
				{
					$err .= "Please enter unique options.<br>";
				}
				
			}			
			else
			{
				$unique_options = array();
				for($counter=1;$counter<=2;$counter++)
				{
					if($frmdata['option_'.$counter]=='')
					{
						$err.="Please enter all options.<br>";
						break;
					}
					$unique_options[] = $frmdata['option_'.$counter];
				}
				if(count($unique_options) != count(array_unique($unique_options)))
				{
					$err .= "Please enter unique options.<br>";
				}
	
			}
			
			if (($frmdata['question_type'] != "S") && ($frmdata['question_type'] != "MT"))
			{
				if ($frmdata['correct_ans']=='')
				{
					$err.="Please select answer.<br>";
				}
				elseif ($frmdata['question_type'] == 'I')
				{
					foreach($frmdata['correct_ans'] as $counter)
					{
						if($_FILES['option_'.$counter]['tmp_name']=='' && !isset($_SESSION['initial_answer_'.$counter]))
						{
							$err.="Please select a valid answer.<br>";
							break;
						}
					}
				}
			}
			else
			{
				$frmdata['correct_ans']='';
			}
		}

		if ($frmdata['marks']=='')
		{
			 $err.="Please enter marks.<br>";
		}
		else
		{
			$candidate_question_history = $DB->SelectRecords('candidate_question_history','question_id='.$nameID, '*', 'group by test_id');
			if($candidate_question_history)
			{
				$question_info = $DB->SelectRecord('question','id='.$nameID);
			
				if($question_info->marks!=$frmdata['marks'])
				{
					$count = count($candidate_question_history);
					if($count==1)
					{
						$err.= "You can't change marks of this question because ".$count." test is conducted with this question.";
					}
					else
					{
						$err.= "You can't change marks of this question because ".$count." tests are conducted with this question.";
					}	
				}
			}
		}
		
		$frmdata['min_time_to_solve'] = $frmdata['minimum_min']*60+$frmdata['minimum_sec'];
		$frmdata['max_time_to_solve'] = $frmdata['maximum_min']*60+$frmdata['maximum_sec'];
		
		if($frmdata['max_time_to_solve'] < $frmdata['min_time_to_solve'])
		{
			$err .= "Minimum solving time must be less than maximum solving time.<br>";
		}
		
		if($_FILES['image']['name'] != '')
		{
			if ( !(($_FILES['image']['type'] == 'image/jpeg')
				|| ($_FILES['image']['type'] == 'image/jpg')
				|| ($_FILES['image']['type'] == 'image/gif')
				|| ($_FILES['image']['type'] == 'image/png')
				|| ($_FILES['image']['type'] == 'image/pjpeg')
				|| ($_FILES['image']['type'] == 'image/x-png')))
				
			{
				$err .= "Please upload hint image in correct format.<br>";
			}
			
			else if($_FILES['image']['size'] > 2000000)
			{
				$err .= "Please upload hint image less than 2MB in size.<br>";
			}
		}
		
		if($_FILES['video']['name'] != '')
		{
			if ( !(	($_FILES['video']['type'] == 'video/mpeg')
				|| ($_FILES['video']['type'] == 'video/mpeg4')
				|| ($_FILES['video']['type'] == 'video/avi')
				|| ($_FILES['video']['type'] == 'video/mov')
				|| ($_FILES['video']['type'] == 'video/AVI')
				|| ($_FILES['video']['type'] == 'video/mpg')
				|| ($_FILES['video']['type'] == 'video/wmv')
				|| ($_FILES['video']['type'] == 'video/vid') 
				|| ($_FILES['video']['type'] == 'video/x-flv')
				|| ($_FILES['video']['type'] == 'application/octet-stream')))
				
			{
				$err .= "Please upload hint video in correct format.<br>";
			}
			
			else if($_FILES['video']['size'] > 20000000)
			{
				$err .= "Please upload hint video less than 20MB in size.<br>";
			}
		}
		
		if($err!='')
		{
			$_SESSION['error']=$err;
		}
		elseif($err=='')
		{
			if($frmdata['stream_id']=='')
			{
				$frmdata['stream_id']='NULL';
			}
			if($frmdata['subject_id']=='')
			{
				$frmdata['subject_id']='NULL';
			}
			
			if($_FILES['image']['name'] != '')
			{
				if($question->image)
				{
					$ipath = ROOT . "/uploadfiles/" . $question->image;
					@unlink($ipath);
				}
				
				$FileObject = (object)$_FILES['image'];
				UploadImageFile($image_path,$FileObject,'image');
				$frmdata['image'] = $image_path;
			}
			
			if($_FILES['video']['name'] != '')
			{
				if($question->video)
				{
					$vpath = ROOT . "/uploadfiles/" . $question->video;
					@unlink($vpath);
				}
				
				$FileObject = (object)$_FILES['video'];
				UploadFile($video_path,$FileObject,'video');
				$frmdata['video'] = $video_path;
			}
			
			//print_r($frmdata);exit;
			$frmdata['question_title'] = preg_replace('#<script(.*?)>(.*?)</script>#is', "", $frmdata['question_title']);
				$frmdata['question_title'] = str_replace('  ', ' &nbsp;', str_replace('&nbsp;', ' ', $frmdata['question_title']));
			$DB->UpdateRecord('question',$frmdata,'id="'.$question_id.'"');
			
			$correct_ans = '';
			$DB->DeleteRecord('question_answer','question_id="'.$question_id.'"');
			
			if($frmdata['question_type'] == 'I')
			{
				if($question->question_type == 'I')
				{
					$quest_img=$DB->SelectRecords('question_image','question_id='.$question_id);
					$pre_count = count($quest_img);
					$count = ($pre_count > $no_of_images) ? $pre_count : $no_of_images;
					$frmdata['question_id']=$question_id;
					
					for($counter=1;$counter<=$count;$counter++)
					{
						$frmdata['image_path'] = '';
						
						if($pre_count >= $counter && $no_of_images >= $counter)
						{
							$set=$counter-1;
							$image_id =	$quest_img[$set]->id;
							$img_name = $quest_img[$set]->image_path;
							
							if(isset($_SESSION['fileObject_image_'.$counter]))
							{
								$frmdata['image_path']=$_SESSION['fileObject_image_'.$counter]['final_path'];
								unset($_SESSION['fileObject_image_'.$counter]);
								
								$image_name = ROOT . '/uploadfiles/' . $img_name;
								@unlink($image_name);
							}
							if($counter == $question_mark)
								$frmdata['image_path'] = 0;
								
							unset($_SESSION['pre_image_'.$counter]);
							unset($_SESSION['image_'.$counter]);
							
	
							if($frmdata['image_path'] !== '')
							$DB->UpdateRecord('question_image',$frmdata,'question_id="'.$question_id.'" and id="'.$image_id.'"');
						}
						elseif($pre_count < $counter)
						{
							if(isset($_SESSION['fileObject_image_'.$counter]))
							{
								$frmdata['image_path']=$_SESSION['fileObject_image_'.$counter]['final_path'];
								unset($_SESSION['fileObject_image_'.$counter]);
							}
							if($counter == $question_mark)
								$frmdata['image_path'] = 0;
								
							unset($_SESSION['pre_image_'.$counter]);
							unset($_SESSION['image_'.$counter]);
	
							if($frmdata['image_path'] !== '')
							$DB->InsertRecord('question_image',$frmdata);
						}
						elseif($no_of_images < $counter)
						{
							$set=$counter-1;
							$image_id =	$quest_img[$set]->id;
							$img_name = $quest_img[$set]->image_path;
							
							$image_name = ROOT . '/uploadfiles/' . $img_name;
							@unlink($image_name);
							$DB->DeleteRecord('question_image','question_id="'.$question_id.'" and id="'.$image_id.'"');
						}
					}
				}
				else
				{
					for($counter=1;$counter<=$no_of_images;$counter++)
					{
						$frmdata['image_path'] = '';
						if(isset($_SESSION['fileObject_image_'.$counter]))
						{
							$frmdata['image_path']=$_SESSION['fileObject_image_'.$counter]['final_path'];
							unset($_SESSION['fileObject_image_'.$counter]);
						}
						
						if($counter == $question_mark)
							$frmdata['image_path'] = 0;
						
						unset($_SESSION['pre_image_'.$counter]);
						unset($_SESSION['image_'.$counter]);
						$frmdata['question_id']=$question_id;
						
						if($frmdata['image_path'] !== '')
						$DB->InsertRecord('question_image',$frmdata);
					}
				}

				$pre_count = count($quest_opt_exist);
				$count = ($pre_count > $no_of_options) ? $pre_count : $no_of_options;
				$frmdata['question_id']=$question_id;
				$correct_ans = array();
				
				for($counter=1;$counter<=$count;$counter++)
				{
					if($pre_count >= $counter && $no_of_options >= $counter)
					{
						$set=$counter-1;
						$answer_id = $quest_opt_exist[$set]->id;
						$img_name = $quest_opt_exist[$set]->answer_title;
						
						$frmdata['answer_title'] = $img_name;
						unset($_SESSION['pre_option_'.$counter]);
						
						if(isset($_SESSION['fileObject_option_'.$counter]))
						{
							$frmdata['answer_title']=$_SESSION['fileObject_option_'.$counter]['final_path'];
							unset($_SESSION['fileObject_option_'.$counter]);
	
							$image_name = ROOT . '/uploadfiles/' . $img_name;
							@unlink($image_name);
						}
						unset($_SESSION['option_'.$counter]);
							
						if(in_array($counter, $frmdata['correct_ans']))
						{
							$correct_ans[]=$answer_id;
							$DB->UpdateRecord('answer',$frmdata,'question_id="'.$question_id.'" and id="'.$answer_id.'"');
						}
						else
						{
							$DB->UpdateRecord('answer',$frmdata,'question_id="'.$question_id.'" and id="'.$answer_id.'"');
						}
					}
					elseif($pre_count < $counter)
					{
						if(isset($_SESSION['fileObject_option_'.$counter]))
						{
							$frmdata['answer_title']=$_SESSION['fileObject_option_'.$counter]['final_path'];
							unset($_SESSION['fileObject_option_'.$counter]);
												
							unset($_SESSION['pre_option_'.$counter]);
							unset($_SESSION['option_'.$counter]);
						
							if(in_array($counter, $frmdata['correct_ans']))
							{
								$correct_ans[]=$DB->InsertRecord('answer',$frmdata);
							}
							else
							{
								$DB->InsertRecord('answer',$frmdata);
							}
						}
					}
					elseif($no_of_options < $counter)
					{
						$set=$counter-1;
						$answer_id = $quest_opt_exist[$set]->id;
						$img_name = $quest_opt_exist[$set]->answer_title;
						
						$image_name = ROOT . '/uploadfiles/' . $img_name;
						@unlink($image_name);
						$DB->DeleteRecord('answer','question_id="'.$question_id.'" and id="'.$answer_id.'"');
					}
				}
			}
			else if($frmdata['question_type'] == 'MT')
			{
				$DB->DeleteRecord('question_match_right','question_id="'. $question_id .'"');
				$DB->DeleteRecord('question_match_left','question_id="'. $question_id .'"');
				
				$left = array();
				$left['question_id'] = $question_id;
	
				$right = array();
				$right['question_id'] = $question_id;
				
				for($counter = 1; $counter <= $frmdata['no_of_cols']; $counter++)
				{
					$right_id = '';
					$right['value'] = '';
					
					if($frmdata['right_col_' . $counter] != '')
					{
						$right['value'] = $frmdata['right_col_' . $counter];
						unset($_SESSION['right_col_' . $counter]);
						
						$right_id = $DB->InsertRecord('question_match_right',$right);
						
						$left['value'] = '';
						if($frmdata['left_col_' . $counter] != '')
						{
							$left['value'] = $frmdata['left_col_' . $counter];
							unset($_SESSION['left_col_' . $counter]);
							
							$left['answer_id'] = $right_id;
							$DB->InsertRecord('question_match_left',$left);
						}
					}	
				}
			}
			elseif($frmdata['question_type'] == "S")
			{
				//do nothing;
			}
			elseif(isset($frmdata['option_3']))
			{
				$correct_ans = array();
				for($counter=1;$counter<=4;$counter++)
				{
					$frmdata['answer_title']=$frmdata['option_'.$counter];
					$frmdata['question_id']=$question_id;
					$set=$counter-1;
					$answer_id=	$quest_opt_exist[$set]->id;
					if(in_array($counter, $frmdata['correct_ans']))
					{
						$correct_ans[]=$answer_id;
					}
					
					$DB->UpdateRecord('answer',$frmdata,'question_id="'.$question_id.'" and id="'.$answer_id.'"');
				}
			}
			else
			{
				$correct_ans = '';
				for($counter=1;$counter<=2;$counter++)
				{
					$frmdata['answer_title']=$frmdata['option_'.$counter];
					$frmdata['question_id']=$question_id;
					$set=$counter-1;
					$answer_id=	$quest_opt_exist[$set]->id;
					if($frmdata['correct_ans']==$counter)
					{
						$correct_ans=$answer_id;
					}
					$DB->UpdateRecord('answer',$frmdata,'question_id="'.$question_id.'" and id="'.$answer_id.'"');
				}
			}
			
			if(($frmdata['question_type'] != "S") && ($frmdata['question_type'] != "MT"))
			{
				if(is_array($correct_ans))
				{
					foreach($correct_ans as $cans)
					{
						$frmdata['answer_id']=$cans;
						$DB->InsertRecord('question_answer',$frmdata);
					}
				}
				else
				{
					$frmdata['answer_id']=$correct_ans;
					$DB->InsertRecord('question_answer',$frmdata);
				}
			}
			
			//echo "<pre>";print_r($frmdata);exit;
			$_SESSION['success']="Question has been updated successfully.";
			$frmdata='';
			Redirect(CreateURL('index.php','mod=question_master&do=manage'));
			exit;
		}
	}
	//==========================================================================
	
	//==========================================================================
	//to upload question
	//==========================================================================	
	function listQuestion()
	{
		global $DB, $frmdata;
		$log_str              = '';
		$err                  = '';
		$log_err_str          = '';
		$upload_fail_question = 0;
		$uploaded_question    = 0;
		$err_session          = 0;
		$question_data        = array();
		$answer_data          = array();
		
		$file_ext = substr($_FILES["question_list"]["name"], -3); //exit;
		if ($file_ext == "csv")
		{
			$row = 1;
			if (($handle = fopen($_FILES["question_list"]["tmp_name"], "r")))
			{
				while (($data = fgetcsv($handle, 1000, ",")) !== false)
				{
					if ($frmdata['skip'] == 'y')
					{
						unset($frmdata['skip']);
						$row++;
						continue;
					} //$frmdata['skip'] == 'y'
					if (count(array_filter($data)) == 0)
					{
						$row++;
						continue;
					} //count(array_filter($data)) == 0
					
					$question_title = (trim($data[1])); 			//====question title
					$question_level = trim(strtoupper($data[2])); 	//======question level
					$subject        = trim($data[3]); 				//=======subject
					$question_type  = trim(strtoupper($data[4]));
					$marks          = trim($data[5]);
					$discussion     = trim($data[6]);
					$hints          = trim($data[7]);
					$video			= trim($data[8]);
					$answer1        = trim($data[9]);
					$answer2        = trim($data[10]);
					$answer3        = trim($data[11]);
					$answer4        = trim($data[12]);
					$correct_answer = trim($data[13]);
					
					if ($question_title == '')
					{
						$upload_fail_question++;
						$err .= "&nbsp;" . $upload_fail_question . ".) Row No. " . $row . ", question not entered.<br/>";
						$log_err_str .= " " . $upload_fail_question . ".) Row No. " . $row . ", question not entered.\r\n";
						$row++;
						continue;
					} //$question_title == ''
					else
					{
						$question_info = $DB->SelectRecord('question', "question_title='" . addslashes($question_title) . "'");
						if ($question_info)
						{
							$upload_fail_question++;
							$err .= "&nbsp;" . $upload_fail_question . ".) Row No. " . $row . ", question is already exist.<br/>";
							$log_err_str .= " " . $upload_fail_question . ".) Row No. " . $row . ", question is already exist.\r\n";
							$row++;
							continue;
						} //$question_info
					}
										
					if ($question_level == '')
					{
						$upload_fail_question++;
						$err .= "&nbsp;" . $upload_fail_question . ".) Row No. " . $row . ", question level not entered.<br/>";
						$log_err_str .= " " . $upload_fail_question . ".) Row No. " . $row . ", question level not entered.\r\n";
						$row++;
						continue;
					} //$question_level == ''
					elseif ($question_level != 'B' && $question_level != 'I' && $question_level != 'H')
					{
						$upload_fail_question++;
						$err .= "&nbsp;" . $upload_fail_question . ".) Row No. " . $row . ", question level is not valid.<br/>";
						$log_err_str .= " " . $upload_fail_question . ".) Row No. " . $row . ", question level is not valid.\r\n";
						$row++;
						continue;
					} //$question_level != 'B' && $question_level != 'I' && $question_level != 'H'
					
					if ($subject == '')
					{
						$upload_fail_question++;
						$err .= "&nbsp;" . $upload_fail_question . ".) Row No. " . $row . ", question's subject not entered.<br/>";
						$log_err_str .= " " . $upload_fail_question . ".) Row No. " . $row . ", question's subject not entered.\r\n";
						$row++;
						continue;
					} //$subject == ''
					else
					{
						if (strlen($subject) < 2)
						{
							$upload_fail_question++;
							$err .= "&nbsp;" . $upload_fail_question . ".) Row No. " . $row . ", Subject is less than 2 characters long.<br/>";
							$log_err_str .= " " . $upload_fail_question . ".) Row No. " . $row . ", Subject is less than 2 characters long.\r\n";
							$row++;
							continue;
						} 
						elseif (!(preg_match("#^[-A-Za-z0-9'./ &()]*$#", $subject)))
						{
							$upload_fail_question++;
							$err .= "&nbsp;" . $upload_fail_question . ".) Row No. " . $row . ", Subject is not valid string.<br/>";
							$log_err_str .= " " . $upload_fail_question . ".) Row No. " . $row . ", Subject is not valid string.\r\n";
							$row++;
							continue;
						} 
					}
					
					
					if ($question_type == '')
					{
						$upload_fail_question++;
						$err .= "&nbsp;" . $upload_fail_question . ".) Row No. " . $row . ", question type not entered.<br/>";
						$log_err_str .= " " . $upload_fail_question . ".) Row No. " . $row . ", question type not entered.\r\n";
						$row++;
						continue;
					} //$question_type == ''
					else
					{
						if ($question_type == 'M')
						{
							if ($answer1 == '' || $answer2 == '' || $answer3 == '' || $answer4 == '')
							{
								$upload_fail_question++;
								$err .= "&nbsp;" . $upload_fail_question . ".) Row No. " . $row . ", question's all options not entered.<br/>";
								$log_err_str .= " " . $upload_fail_question . ".) Row No. " . $row . ", question's all options not entered.\r\n";
								$row++;
								continue;
							}
						} //$question_type == 'M'
						
						elseif ($question_type == 'T')
						{
							if ($answer1 == '' || $answer2 == '')
							{
								$upload_fail_question++;
								$err .= "&nbsp;" . $upload_fail_question . ".) Row No. " . $row . ", question's all options not entered.<br/>";
								$log_err_str .= " " . $upload_fail_question . ".) Row No. " . $row . ", question's all options not entered.\r\n";
								$row++;
								continue;
							}
						} //$question_type == 'T'
						
						elseif ($question_type == 'MT')
						{
							if ($answer1 == '' || $answer2 == '')
							{
								$upload_fail_question++;
								$err .= "&nbsp;" . $upload_fail_question . ".) Row No. " . $row . ", both left column and right column not entered.<br/>";
								$log_err_str .= " " . $upload_fail_question . ".) Row No. " . $row . ", both left column and right column not entered.\r\n";
								$row++;
								continue;
							}
							else
							{
								$left_vals = explode(',', $answer1);
								$left_vals = array_filter(array_map('trim', $left_vals));
								
								if(count($left_vals) < 2)
								{
									$upload_fail_question++;
									$err .= "&nbsp;" . $upload_fail_question . ".) Row No. " . $row . ", minimum 2 values not entered in left column.<br/>";
									$log_err_str .= " " . $upload_fail_question . ".) Row No. " . $row . ", minimum 2 values not entered in left column.\r\n";
									$row++;
									continue;
								}
								elseif(count($left_vals) != count(array_unique($left_vals)))
								{
									$upload_fail_question++;
									$err .= "&nbsp;" . $upload_fail_question . ".) Row No. " . $row . ", all values are not unique in left column.<br/>";
									$log_err_str .= " " . $upload_fail_question . ".) Row No. " . $row . ", all values are not unique in left column.\r\n";
									$row++;
									continue;
								}
								
								
								$right_vals = explode(',', $answer2);
								$right_vals = array_filter(array_map('trim', $right_vals));
								
								if(count($left_vals) > count($right_vals))
								{
									$upload_fail_question++;
									$err .= "&nbsp;" . $upload_fail_question . ".) Row No. " . $row . ", number of values in left column is more than that in right column.<br/>";
									$log_err_str .= " " . $upload_fail_question . ".) Row No. " . $row . ", number of values in left column is more than that in right column.\r\n";
									$row++;
									continue;
								}
								elseif(count($right_vals) != count(array_unique($right_vals)))
								{
									$upload_fail_question++;
									$err .= "&nbsp;" . $upload_fail_question . ".) Row No. " . $row . ", all values are not unique in right column.<br/>";
									$log_err_str .= " " . $upload_fail_question . ".) Row No. " . $row . ", all values are not unique in right column.\r\n";
									$row++;
									continue;
								}
							}
						} //$question_type == 'MT'
						
						elseif ($question_type == 'R')
						{
							$question_type = 'I';
							$imageError = false;
							if ($answer1 == '' || $answer2 == '' || $answer3 == '' || $answer4 == '')
							{
								$imageError = true;
								$err .= "&nbsp;" . ($upload_fail_question + 1) . ".) Row No. " . $row . ", question's all options not entered.<br/>";
								$log_err_str .= " " . ($upload_fail_question + 1) . ".) Row No. " . $row . ", question's all options not entered.\r\n";
							}
							else
							{
								$temp_img_path =  TEMPIMG."/question/";
								
								$imgNotFound = array();
								$imgWrongFormat = array();
								$imgHighSize = array();
								
								$answerImages = array();
								
								for($imgCount = 1; $imgCount <= 4; $imgCount++)
								{
									$imageName = 'answer'.$imgCount;
									$answerImages[$imgCount] = $imageVar = ${$imageName};

									if(!file_exists($temp_img_path.$imageVar))
									{
										$imgNotFound[] = $imageName;
									}
									else
									{
										$image_size = filesize($temp_img_path.$imageVar);
			            				$image_info = getimagesize($temp_img_path.$imageVar);
										
										if($image_info['mime']!='image/jpeg' && $image_info['mime']!='image/jpg' && $image_info['mime']!='image/gif' && $image_info['mime']!='image/png')
										{
											$imgWrongFormat[] = $imageName;
										}
			            				elseif($image_size>2000000)
			            				{
			            					$imgHighSize[] = $imageName;
			            				}
									}
								}
											
								if(count($imgNotFound) > 0)
								{
									$imgNotFound = implode(", ", $imgNotFound);
									$imageError = true;
									$err .= "&nbsp;" . ($upload_fail_question + 1) . ".) Row No. " . $row . ", image not found for $imgNotFound.<br/>";
									$log_err_str .= " " . ($upload_fail_question + 1) . ".) Row No. " . $row . ", image not found for $imgNotFound.\r\n";
								}
								
								if(count($imgWrongFormat) > 0)
								{
									$imgWrongFormat = implode(', ', $imgWrongFormat);
									$imageError = true;
									$err .= "&nbsp;" . ($upload_fail_question + 1) . ".) Row No. " . $row . ", image not in right format for $imgWrongFormat.<br/>";
									$log_err_str .= " " . ($upload_fail_question + 1) . ".) Row No. " . $row . ", image not in right format for $imgWrongFormat.\r\n";
								}
								
								if(count($imgHighSize) > 0)
								{
									$imgHighSize = implode(', ', $imgHighSize);
									$imageError = true;
									
									$err .= "&nbsp;" . ($upload_fail_question + 1) . ".) Row No. " . $row . ", size of image is more than 2MB for $imgHighSize.<br/>";
									$log_err_str .= " " . ($upload_fail_question + 1) . ".) Row No. " . $row . ", size of image is more than 2MB for $imgHighSize.\r\n";
								}
							}
							
							
							$temp_img_path =  TEMPIMG."/question/";
							
							$imgNotFound = array();
							$imgWrongFormat = array();
							$imgHighSize = array();
							
							$optionImages = array();
							
							for($imgCount = 1; $data[13+$imgCount] != ''; $imgCount++)
							{
								$imageName = 'option'.$imgCount;
								$optionImages[$imgCount] = $imageVar = $data[13+$imgCount];

								if(!file_exists($temp_img_path.$imageVar))
								{
									$imgNotFound[] = $imageName;
								}
								else
								{
									$image_size = filesize($temp_img_path.$imageVar);
		            				$image_info = getimagesize($temp_img_path.$imageVar);
									
									if($image_info['mime']!='image/jpeg' && $image_info['mime']!='image/jpg' && $image_info['mime']!='image/gif' && $image_info['mime']!='image/png')
									{
										$imgWrongFormat[] = $imageName;
									}
		            				elseif($image_size>2000000)
		            				{
		            					$imgHighSize[] = $imageName;
		            				}
								}
							}
							
							if($imgCount < 4)
							{
								$imageError = true;
								$err .= "&nbsp;" . ($upload_fail_question + 1) . ".) Row No. " . $row . ", minimum 3 question images not entered.<br/>";
								$log_err_str .= " " . ($upload_fail_question + 1) . ".) Row No. " . $row . ", minimum 3 question images not entered.\r\n";
							}
							else
							{
								if(count($imgNotFound) > 0)
								{
									$imgNotFound = implode(", ", $imgNotFound);
									$imageError = true;
									$err .= "&nbsp;" . ($upload_fail_question + 1) . ".) Row No. " . $row . ", image not found for $imgNotFound.<br/>";
									$log_err_str .= " " . ($upload_fail_question + 1) . ".) Row No. " . $row . ", image not found for $imgNotFound.\r\n";
								}
								
								if(count($imgWrongFormat) > 0)
								{
									$imgWrongFormat = implode(', ', $imgWrongFormat);
									$imageError = true;
									$err .= "&nbsp;" . ($upload_fail_question + 1) . ".) Row No. " . $row . ", image not in right format for $imgWrongFormat.<br/>";
									$log_err_str .= " " . ($upload_fail_question + 1) . ".) Row No. " . $row . ", image not in right format for $imgWrongFormat.\r\n";
								}
								
								if(count($imgHighSize) > 0)
								{
									$imgHighSize = implode(', ', $imgHighSize);
									$imageError = true;
									
									$err .= "&nbsp;" . ($upload_fail_question + 1) . ".) Row No. " . $row . ", size of image is more than 2MB for $imgHighSize.<br/>";
									$log_err_str .= " " . ($upload_fail_question + 1) . ".) Row No. " . $row . ", size of image is more than 2MB for $imgHighSize.\r\n";
								}
							}

							if($imageError == true)
							{
								$upload_fail_question++;
								$row++;
								continue;
							}
								
						} //$question_type == 'R'
						
						else
						{
							$upload_fail_question++;
							$err .= "&nbsp;" . $upload_fail_question . ".) Row No. " . $row . ", question type is not valid.<br/>";
							$log_err_str .= " " . $upload_fail_question . ".) Row No. " . $row . ", question type is not valid.\r\n";
							$row++;
							continue;
						}
					}

					if ($marks == '')
					{
						$upload_fail_question++;
						$err .= "&nbsp;" . $upload_fail_question . ".) Row No. " . $row . ", question marks not entered.<br/>";
						$log_err_str .= " " . $upload_fail_question . ".) Row No. " . $row . ", question marks not entered.\r\n";
						$row++;
						continue;
					} //$marks == ''
					else
					{
						if (!(filter_var($marks, FILTER_VALIDATE_INT)))
						{
							$upload_fail_question++;
							$err .= "&nbsp;" . $upload_fail_question . ".) Row No. " . $row . ", question marks not an integer.<br/>";
							$log_err_str .= " " . $upload_fail_question . ".) Row No. " . $row . ", question marks not an integer.\r\n";
							$row++;
							continue;
						} 
						elseif ($marks <= 0 || $marks > 10)
						{
							$upload_fail_question++;
							$err .= "&nbsp;" . $upload_fail_question . ".) Row No. " . $row . ", question marks not in range (i.e. 1 to 10).<br/>";
							$log_err_str .= " " . $upload_fail_question . ".) Row No. " . $row . ", question marks not in range (i.e. 1 to 10).\r\n";
							$row++;
							continue;
						} //$marks <= 0 || $marks > 10
					}
					
					$question_data['video'] = '';
					if($video != '')
					{
						$temp_video_path =  TEMPIMG."/question/".$video;
						
						if(file_exists($temp_video_path))
						{
							$vsize = filesize($temp_video_path);

							$pos=strrpos($temp_video_path, "."); 
							$ext = strtolower(substr($temp_video_path, $pos+1));
							$ext_list = explode(",", VIDEOEXT);
							
							if(!in_array($ext, $ext_list))
							{
								$upload_fail_question++;
								$err .= "&nbsp;" . ($upload_fail_question + 1) . ".) Row No. " . $row . ", video not in right format.<br/>";
								$log_err_str .= " " . ($upload_fail_question + 1) . ".) Row No. " . $row . ", video not in right format.\r\n";
								$row++;
								continue;
							}
							if($vsize > 20000000)
							{
								$upload_fail_question++;
								$err .= "&nbsp;" . $upload_fail_question . ".) Row No. " . $row . ", video size is more than 20MB.<br/>";
								$log_err_str .= " " . $upload_fail_question . ".) Row No. " . $row . ", video size is more than 20MB.\r\n";
								$row++;
								continue;
							}
							
							$time = time();
							copy($temp_video_path, ROOT."/uploadfiles/".$time.$video);
			            	$question_data['video'] = $time.$video;
						}
						else
						{
							$upload_fail_question++;
							$err .= "&nbsp;" . $upload_fail_question . ".) Row No. " . $row . ", video not found.<br/>";
							$log_err_str .= " " . $upload_fail_question . ".) Row No. " . $row . ", video not found.\r\n";
							$row++;
							continue;
						}
					}
					
					if($question_type != 'MT')
					{
						if ($correct_answer == '')
						{
							$upload_fail_question++;
							$err .= "&nbsp;" . $upload_fail_question . ".) Row No. " . $row . ", question's correct answer not entered.<br/>";
							$log_err_str .= " " . $upload_fail_question . ".) Row No. " . $row . ", question's correct answer not entered.\r\n";
							$row++;
							continue;
						} //$correct_answer == ''
						else
						{
							if (($question_type == 'M') || ($question_type == 'I'))
							{
								if (strtolower($correct_answer) != strtolower($answer1) && strtolower($correct_answer) != strtolower($answer2) && strtolower($correct_answer) != strtolower($answer3) && strtolower($correct_answer) != strtolower($answer4))
								{
									$upload_fail_question++;
									$err .= "&nbsp;" . $upload_fail_question . ".) Row No. " . $row . ", question's correct answer doesn't match with given options.<br/>";
									$log_err_str .= " " . $upload_fail_question . ".) Row No. " . $row . ", question's correct answer doesn't match with given options.\r\n";
									$row++;
									continue;
								} 
							} //$question_type == 'M'
							elseif ($question_type == 'T')
							{
								if (strtolower($correct_answer) != strtolower($answer1) && strtolower($correct_answer) != strtolower($answer2))
								{
									$upload_fail_question++;
									$err .= "&nbsp;" . $upload_fail_question . ".) Row No. " . $row . ", question's correct answer doesn't match with given options.<br/>";
									$log_err_str .= " " . $upload_fail_question . ".) Row No. " . $row . ", question's correct answer doesn't match with given options.\r\n";
									$row++;
									continue;
								}
							} //$question_type == 'T'
						}
					}
					
					if ($hints == '')
					{
						$upload_fail_question++;
						$err .= "&nbsp;" . $upload_fail_question . ".) Row No. " . $row . ", Hints field is empty.<br/>";
						$log_err_str .= " " . $upload_fail_question . ".) Row No. " . $row . ", Hints field is empty.\r\n";
						$row++;
						continue;
					} //$hints == ''
					
					
					//========question title==============================
					$question_data['question_title'] = htmlentities($question_title);
					
					//============question level==========================
					$question_data['question_level'] = $question_level;
					
					//=============Question Hints=========================						
					$question_data['hints'] = $hints;
					
					//=======question subject==================
					if ($subject != '')
					{
						$condition  = "subject_name='" . $subject . "'";
						$subject_id = getTableRecordId('subject', $condition, 'subject_name', $subject);
						
						$question_data['subject_id'] = $subject_id;
					} //$stream == '' && $subject != ''

					//========question type=================
					$question_data['question_type'] = $question_type;
					
					//========question marks=================
					$question_data['marks'] = $marks;
					
					//========question discussion=================
					$question_data['discussion'] = $discussion;
					
					$question_id   = $DB->InsertRecord('question', $question_data);
					$question_data = '';
					
					if($question_type != 'MT')
					{
						$origAnswer1 = $answer1;
						$origAnswer2 = $answer2;
						$origAnswer3 = $answer3;
						$origAnswer4 = $answer4;
						
						if($question_type == 'I')
						{
							$objImg = new thumb_image;
							for($countImage = 1;$countImage <= count($answerImages); $countImage++)
							{
								$img = $answerImages[$countImage];
								
								$pos=strrpos($img, "."); 
								$ext = strtolower(substr($img, $pos));
								
								$uniq = time() . uniqid() . $ext;
								$temppath =  TEMPIMG . "/question/" . $img;
								$copypath = ROOT . "/uploadfiles/" . $uniq;
								$objImg->GenerateThumbFile($temppath, $copypath);
								
								${'answer'.$countImage} = $uniq;
							}
	
							$option_data = array();
							for($countImage = 1;$countImage <= count($optionImages); $countImage++)
							{
								$img = $optionImages[$countImage];
								
								$pos=strrpos($img, "."); 
								$ext = strtolower(substr($img, $pos));
								
								$uniq = time() . uniqid() . $ext;
								$temppath =  TEMPIMG . "/question/" . $img;
								$copypath = ROOT . "/uploadfiles/" . $uniq;
								$objImg->GenerateThumbFile($temppath, $copypath);
								
								$option_data['question_id'] = $question_id;
								$option_data['image_path'] = $uniq;
								$DB->InsertRecord('question_image', $option_data);
							}
							
						}
						
						$answer_data['question_id']  = $question_id;
						$answer_data['answer_title'] = htmlentities($answer1);
						$option1_id                  = $DB->InsertRecord('answer', $answer_data);
						
						$answer_data['answer_title'] = htmlentities($answer2);
						$option2_id                  = $DB->InsertRecord('answer', $answer_data);
						
						if (($question_type == 'M') || ($question_type == 'I'))
						{
							$answer_data['answer_title'] = htmlentities($answer3);
							$option3_id                  = $DB->InsertRecord('answer', $answer_data);
							
							$answer_data['answer_title'] = htmlentities($answer4);
							$option4_id                  = $DB->InsertRecord('answer', $answer_data);
						} //$question_type == 'M'
						
						
						switch (strtolower($correct_answer))
						{
							case strtolower($origAnswer1):
								$answer_id = $option1_id;
								break;
							case strtolower($origAnswer2):
								$answer_id = $option2_id;
								break;
							case strtolower($origAnswer3):
								$answer_id = $option3_id;
								break;
							case strtolower($origAnswer4):
								$answer_id = $option4_id;
								break;
						} //strtolower($correct_answer)
						$question_answer_data['question_id'] = $question_id;
						$question_answer_data['answer_id']   = $answer_id;
						
						$DB->InsertRecord('question_answer', $question_answer_data);
						/***************************/
						$question_answer_data = '';
					}
					else
					{
						$leftdata = array();
						$rightdata = array();
						
						$rightdata['question_id'] = $question_id;
						$leftdata['question_id'] = $question_id;
						
						for($count = 0; $count <= count($right_vals); $count++)
						{
							$rightdata['value'] = '';
							if($right_vals[$count] != '')
							{
								$rightdata['value'] = $right_vals[$count];
								$rid = $DB->InsertRecord('question_match_right', $rightdata);
								
								$leftdata['value'] = '';
								$leftdata['answer_id'] = '';
								if($left_vals[$count] != '')
								{
									$leftdata['value'] = $left_vals[$count];
									$leftdata['answer_id'] = $rid;
									$DB->InsertRecord('question_match_left', $leftdata);
								}
							}
						}
					}
					
					$row++;
					$uploaded_question++;
					
				} //($data = fgetcsv($handle, 1000, ",")) !== false
				
			} //($handle = fopen($_FILES["question_list"]["tmp_name"], "r"))
			
			fclose($handle); 
			
		} //$file_ext == "csv"
		else
		{
			$_SESSION['error'] = '<span style="padding-left: 185px;">Please select CSV file.</span>';
			$log_str .= " Please select CSV file.\r\n";
		}
		
		if ($err != '')
		{
			$question_err_heading = "$upload_fail_question question details not uploaded because of following errors: <br/>" . $err;
			$_SESSION['error']    = '<div style="overflow: scroll; overflow-x: hidden; height:100px;">' . $question_err_heading . '</div>';
			$err_session          = 1;
			
			$question_log_err_heading = "\r\n$upload_fail_question question details not uploaded because of following errors: \r\n" . $log_err_str;
			$log_str .= $question_log_err_heading;
		} //$err != ''
				
		if ($uploaded_question > 0)
		{
			$_SESSION['success'] = "$uploaded_question question details have been uploaded successfully.";
			$frmdata             = '';
			$log_str .= "\r\n$uploaded_question question details have been uploaded successfully.";
		} //$uploaded_question > 0
		
		$myFile = ROOT . "/log/question_log.txt";
		@chmod($myFile, 0777);
		$fh = fopen($myFile, 'a+') or die("can't open file");
		$stringData = "\r\n\r\nDate:" . date('d-m-Y h:i:s A') . "\r\n";
		fwrite($fh, $stringData);
		fwrite($fh, $log_str);
		fclose($fh);
		
		
		if (!(isset($_SESSION['error'])) && $_SESSION['error'] == '')
		{
			Redirect(CreateURL('index.php', 'mod=question_master&do=manage'));
			exit;
		} //!(isset($_SESSION['error'])) && $_SESSION['error'] == ''
	}


/************************************************************************************
 * 			Added By		:	Ashwini Agarwal
 * 			Date			:	March 9, 2012
 * 			Description		:	To export questions to csv file. 
 */

function exportQuestionToCsv()
{
	global $DB;
	$query = $_SESSION['queryForQuestions'];
	$que = $DB->RunSelectQuery($query);
	$counter = count($que);
	
	if(($fp = fopen('questions.csv', 'w')) && $counter > 0 && $que != 0)
	{
		$arr = array("S.No.", "Question", "Question Level(B=>Beginner,I=>Intermediate,H=>Higher)", "", "Subject", "Question type ( T = True/False, M = Multiple, S = Subjective)", "Marks", "Discussion", "Hints", "Answer1",	"Answer2", "Answer3", "Answer4", "Correct answer");
		fputcsv($fp, $arr);
		for ($count = 0; $count < $counter; $count++)
		{
			$arr[0] = $count + 1;
			$arr[1] = $que[$count]->question_title;
			$arr[2] = $que[$count]->question_level;
			$arr[3] = $que[$count]->stream_title;
			$arr[4] = $que[$count]->subject_name;
			$arr[5] = $que[$count]->question_type;
			$arr[6] = $que[$count]->marks;
			$arr[7] = $que[$count]->discussion;
			$arr[8] = $que[$count]->hints;
			
			$id = $que[$count]->id;
			$query = "SELECT * FROM answer WHERE question_id = '$id'";
			$ans = $DB->RunSelectQuery($query);
			
			$arr[9] = $ans[0]->answer_title;
			$arr[10] = $ans[1]->answer_title;
			$arr[11] = $ans[2]->answer_title;
			$arr[12] = $ans[3]->answer_title;
			
			$query = "SELECT a.answer_title FROM question_answer AS qa JOIN answer AS a ON qa.answer_id = a.id WHERE qa.question_id = '$id'";
			$corr_ans = $DB->RunSelectQuery($query);
			
			$arr[13] = $corr_ans[0]->answer_title;
			fputcsv($fp, $arr);
		}
	}
	else 
	{
		$arr[0] = 'No Record Found.';
		fputcsv($fp, $arr);
	}
	header('Content-type: application/csv');
	header('Content-Disposition: attachment; filename="Questions.csv"');
	readfile("questions.csv");
	fclose($fp);
	$fp = fopen('questions.csv', 'w');
	fclose($fp);
	exit;
}

/******************************************************************
Des: A function to get questions  Information.
******************************************************************/
function getRecordQuestion(&$totalCount)
{
	global $DB,$frmdata;
	
	$query='';
	$ExportQuery = '';
	
	$query="select subject.subject_name, que.* ";
		
	$query.=" from ".PREFIX."question que
				left join ".PREFIX."subject on que.subject_id = subject.id";
	
	if ($_SESSION['admin_user_type'] == 'T')
	{
		$query .= " JOIN exam_subject_teacher est on (est.subject_id = subject.id) ";
		$query .= " WHERE est.teacher_id = '".$_SESSION['admin_user_id']."' AND ";
	}
	else
	{
		$query .= ' WHERE 1 AND ';
	}
	
	if(isset($frmdata['question_level_manage']) && $frmdata['question_level_manage']!='')
	{
	
		$query.=" que.question_level='".$frmdata['question_level_manage']."'";
		$query.=" and";	
	}
	if (isset($frmdata['question_title_manage']) && $frmdata['question_title_manage'] != '')
	{
		$query.=" que.question_title like '%".addslashes($frmdata['question_title_manage'])."%'";
		$query.=" and";	
	}
	if(isset($frmdata['question_type_manage']) && $frmdata['question_type_manage'] !='')
	{
		$query.=" que.question_type='".$frmdata['question_type_manage']."'";
		$query.=" and";	
	}
	if(isset($frmdata['subject_id_manage']) && $frmdata['subject_id_manage'] !='')
	{
		$query.=" que.subject_id='".$frmdata['subject_id_manage']."'";
		$query.=" and";	
	}
		
	$query=substr($query,0,(strlen($query)-4));
	$ExportQuery = $query;
	$ExportQuery .= " and (que.question_type <> 'I') and (que.question_type <> 'MT')  ";
	
	$query .= " group by que.id ";
	$ExportQuery .= " group by que.id ";
	if(isset($frmdata['orderby']) && $frmdata['orderby']!='' )
	{
  		$query.=" order by ".$frmdata['orderby'];
  		$ExportQuery.=" order by ".$frmdata['orderby'];
	}	
	else
	{
		$query.=' order by que.id desc';
		$ExportQuery.=' order by que.id desc';
	}
 	//echo $query;//exit;
 	
	$_SESSION['queryForQuestions'] = $ExportQuery;
	$result = $DB->RunSelectQueryWithPagination($query,$totalCount);
	// print_r($result);exit;
	return $result;   
}

function clearSession()
{
	$total = 6;
	if(isset($_SESSION['no_of_images']))
	{
		$total = ($total > $_SESSION['no_of_images']) ? $total : $_SESSION['no_of_images'];
		unset($_SESSION['no_of_images']);
	}
		
	if(isset($_SESSION['no_of_options']))
	{
		$total = ($total > $_SESSION['no_of_options']) ? $total : $_SESSION['no_of_options'];
		unset($_SESSION['no_of_options']);
	}
	
	for($count = 1; $count <= $total; $count++)
	{
		if(isset($_SESSION["image_$count"]))
		{
			unset($_SESSION["image_$count"]);
		}
		
		if(isset($_SESSION["pre_image_$count"]))
		unset($_SESSION["pre_image_$count"]);
		
		if(isset($_SESSION["option_$count"]))
		{
			unset($_SESSION["option_$count"]);
		}
		
		if(isset($_SESSION["pre_option_$count"]))
		unset($_SESSION["pre_option_$count"]);
		
		if(isset($_SESSION["fileObject_image_$count"]))
		{
			@unlink(ROOT . '/uploadfiles/' . $_SESSION["fileObject_image_$count"]['final_path']);
			unset($_SESSION["fileObject_image_$count"]);
		}
		
		if(isset($_SESSION["fileObject_option_$count"]))
		{
			@unlink(ROOT . '/uploadfiles/' . $_SESSION["fileObject_option_$count"]['final_path']);
			unset($_SESSION["fileObject_option_$count"]);
		}
		
		if(isset($_SESSION['initial_answer_'.$count]))
		unset($_SESSION['initial_answer_'.$count]);
	}
	
	if(isset($_SESSION["correct_ans"]))
	unset($_SESSION["correct_ans"]);
	
	if(isset($_SESSION["question_mark"]))
	unset($_SESSION["question_mark"]);
	
	if(isset($_SESSION['no_of_options']))
	unset($_SESSION['no_of_options']);
}
}//end of class
?>