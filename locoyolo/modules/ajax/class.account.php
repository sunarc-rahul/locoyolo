<?php
/*****************Developed by :-  Rahul Gahlot
	                Date         :- 3-july-2011
					Module       :- Candidatet master
					Purpose      :- Class for function to add and edit candidate details
***********************************************************************************/


class Account
{	
	function addCandidate()
	{
		global $DB,$frmdata;
		//print_r($frmdata);//exit;
		$err='';
		
		if ($frmdata['first_name']=='')
		{
			 $err.="Please enter first name.<br>";
		}
		
		if ($frmdata['email']=='')
		{
		//	$err.="Please enter email address.<br>";
		}
		
		if ($frmdata['email']!='')
		{
			 $exist=$DB->SelectRecord('candidate',"email='".$frmdata['email']."'");
			 //print_r($exist);exit;
			 if($exist->email!='')
			 {
				$err.="This email id is already in use by another student.<br>";
			 }
		}
		
		if ($frmdata['candidate_id']=='')
		{
			 $err.="Please enter username.<br>";
		}
		if ($frmdata['candidate_id']!='')
		{
			 $exist=$DB->SelectRecord('candidate',"candidate_id='".$frmdata['candidate_id']."'");
			 //print_r($exist);exit;
			 if($exist->candidate_id!='')
			 {
				$err.="Username already exist.<br>";
			 }
		}
		
		if ($frmdata['password']=='')
		{
			 $err.="Please enter password.<br>";
		}
		else
		{
			if($frmdata['confirm_password']=='')
			{
				$err.="Please enter confirm password.<br>";
			}
			elseif($frmdata['confirm_password']!=$frmdata['password'])
			{
				$err.="Confirm password doesn't match with password.<br>";
			}
		}
				
		if ($frmdata['birth_date'] == '')
		{
		//	$err.="Please enter date of birth.<br>";
		}
		
		if ($frmdata['birth_date']!='')
		{
			$birth_date = date( 'Y-m-d H:i:s', strtotime($frmdata['birth_date']) );
			$req_date=time();
			
			if(strtotime($birth_date)>$req_date)
			{
				$err.="Birth date must be less than today.<br>";
			}
		}
		
		if (($frmdata['exam_id']=='') || (count($frmdata['exam_id']) == 0))
		{
			 $err.="Please select at-least one course.<br>";
		}
		
		if($_FILES['pro_resume']['tmp_name']=='')
		{
		//	$err .= 'Please upload resume.<br>';
		}
		else
		{
			if($_FILES['pro_resume']['type']!='application/pdf' && $_FILES['pro_resume']['type']!='application/msword' && $_FILES['pro_resume']['type']!='application/vnd.openxmlformats-officedocument.wordprocessingml.document')
			{
				$err.="Please upload resume in correct format.<br>";
			}
			
			if($_FILES['pro_resume']['size']>2000000)
			{
				$err.="Please upload resume not more than 2MB.<br>";
			}
		}
		
		if($_FILES['pro_image']['tmp_name']!='')
		{
			if($_FILES['pro_image']['type']!='image/jpeg' && $_FILES['pro_image']['type']!='image/jpg' && $_FILES['pro_image']['type']!='image/gif' && $_FILES['pro_image']['type']!='image/png')
			{
				$err.="Please upload image in correct format.<br>";
			}
			
			if($_FILES['pro_image']['size']>2000000)
			{
				$err.="Please upload image not more than 2MB.<br>";
			}
		}
		
		if($_FILES['pro_sign']['tmp_name']!='')
		{
			if(!in_array($_FILES['pro_sign']['type'], array('image/jpeg', 'image/jpg', 'image/gif', 'image/png')))
			{
				$err.="Please upload signature image in correct format.<br>";
			}
			
			if($_FILES['pro_sign']['size']>2000000)
			{
				$err.="Please upload signature image not more than 2MB.<br>";
			}
		}
		
		if($_SESSION['cand_edu']['total_edu'] <= 0)
		{
		//	$err .= 'Please enter education details.<br>';
		}
		else
		{
			$twelfth = false;
			$tenth = false;
			
			for($count = 0; $count <= $_SESSION['cand_edu']['count_edu']; $count++)
			{
				if($_SESSION['cand_edu']['class_'.$count] == 12)
				{
					$twelfth = true;
				}
			
				if($_SESSION['cand_edu']['class_'.$count] == 10)
				{
					$tenth = true;
				}
			}
			
			if($tenth == false || $twelfth == false)
			{
			//	$err .= "Please enter education details of 10<sup>th</sup> and 12<sup>th</sup> class.<br>";
			}
		}
		
		if($err=='')
		{
			$frmdata['password']=Encrypt($frmdata['password']);

			if($frmdata['birth_date']!='')
				$frmdata['birth_date'] = date( 'Y-m-d H:i:s', strtotime($frmdata['birth_date']) );
			
			if($_FILES['pro_resume']['tmp_name']!='')
			{
				$resume_path=ROOT.'/uploadfiles/'.$_FILES['pro_resume']['name'];
				$FileObject = (object)$_FILES['pro_resume'];
				UploadFile($resume_path,$FileObject,'resume');
				$frmdata['pro_resume']=$resume_path;
			}
				
			if($_FILES['pro_image']['tmp_name']!='')
			{
				$image_path=ROOT.'/uploadfiles/'.$_FILES['pro_image']['name'];
				$FileObject = (object)$_FILES['pro_image'];
				UploadImageFile($image_path,$FileObject,'image');
				$frmdata['pro_image']=$image_path;
			}

				
			if($_FILES['pro_sign']['tmp_name']!='')
			{
				$image_path=ROOT.'/uploadfiles/'.$_FILES['pro_sign']['name'];
				$FileObject = (object)$_FILES['pro_sign'];
				UploadFile($image_path,$FileObject,'image');
				$frmdata['pro_sign']=$image_path;
			}
			
			
			$id = $DB->InsertRecord('candidate',$frmdata);
			
			/******** Add exam candidates *************************************/
			
			foreach($frmdata['exam_id'] as $exam_id)
			{
				$exam_candidate = array('candidate_id' => $id);
				$exam_candidate['exam_id'] = $exam_id;
				
				$DB->InsertRecord('exam_candidate', $exam_candidate);
			}
			
			/******** Add Education Details ***********************************/
			
			for($count = 0; $count <= $_SESSION['cand_edu']['count_edu']; $count++)
			{
				$frmdata['candidate_id'] = $id;
				
				if(isset($_SESSION['cand_edu']['class_'.$count]) && ($_SESSION['cand_edu']['class_'.$count] != ''))
				{
					$frmdata['class'] = $_SESSION['cand_edu']['class_'.$count];
					$frmdata['year'] = $_SESSION['cand_edu']['year_'.$count];
					$frmdata['percentage_marks'] = $_SESSION['cand_edu']['pmarks_'.$count];
					$frmdata['maximum_marks'] = $_SESSION['cand_edu']['mmarks_'.$count];
					$frmdata['obtained_marks'] = $_SESSION['cand_edu']['omarks_'.$count];
					$frmdata['grade'] = $_SESSION['cand_edu']['grade_'.$count];
					$frmdata['stream'] = $_SESSION['cand_edu']['stream_'.$count];
					
					$DB->InsertRecord('candidate_education', $frmdata);
				}
			}
			unset($_SESSION['cand_edu']);
			/******************************************************************/
			
			/******** Add Experience Details ***********************************/
			
			for($count = 0; $count <= $_SESSION['cand_exp']['count_exp']; $count++)
			{
				$frmdata['candidate_id'] = $id;
				
				if(isset($_SESSION['cand_exp']['company_'.$count]) && ($_SESSION['cand_exp']['company_'.$count] != ''))
				{
					$frmdata['company'] = $_SESSION['cand_exp']['company_'.$count];
					
					$frmdata['from_date'] = date( 'Y-m-d H:i:s', strtotime($_SESSION['cand_exp']['from_'.$count]) );
					$frmdata['to_date'] = date( 'Y-m-d H:i:s', strtotime($_SESSION['cand_exp']['to_'.$count]) );
					$frmdata['stream'] = $_SESSION['cand_exp']['stream_'.$count];
					$frmdata['details'] = $_SESSION['cand_exp']['detail_'.$count];
					
					$DB->InsertRecord('candidate_experience', $frmdata);
				}
			}
			unset($_SESSION['cand_exp']);
			/******************************************************************/
			
			$_SESSION['success']="Student details has been added successfully.";
			$frmdata='';
			Redirect(CreateURL('index.php','mod=candidate_master&do=manage'));
			exit;
		}
		elseif($err!='')
		{
			$_SESSION['error']=$err;
		}
		
		
	}	
	
//==========================================================================
// to edit candidate information
//==========================================================================
	function editCandidate($nameID)
	{
		global $DB,$frmdata;
		$err='';
		if(isset($_GET['nameID']) && $_GET['nameID'])
		{
			$nameID=$_GET['nameID'];
		}
		else
		{
			$nameID=$_SESSION['candidate_id'];
		}
		
		if($frmdata['edit_account']=='Upload')
		{
			if ($_FILES['pro_image']['tmp_name']!='')
			{
				if($_FILES['pro_image']['type']!='image/jpeg' && $_FILES['pro_image']['type']!='image/jpg' && $_FILES['pro_image']['type']!='image/gif' && $_FILES['pro_image']['type']!='image/png')
				{
					$err.="Please upload image in right format.<br>";
				}

			
				if($_FILES['pro_image']['size']>2000000)
				{
					$err.="Please upload image not more than 2MB.<br>";
				}
			}
		}
		//echo $_FILES['pro_image']['tmp_name'];exit;
		
		else if($frmdata['edit_account']=='Submit')
		{
		if ($frmdata['first_name']=='')
		{
			 $err.="Please enter first name.<br>";
		}
		
		if ($frmdata['candidate_id']=='')
		{
			 //$err.="Please enter username.<br>";
		}
		if ($frmdata['candidate_id']!='')
		{
			 $exist=$DB->SelectRecord('candidate',"candidate_id='".$frmdata['candidate_id']."' and id!=".$nameID);
			 
			 if($exist->candidate_id!='')
			 {
				$err.="Username already exist.<br>";
			 }
		}
		
		if ($frmdata['email']=='')
		{
			$err.="Please enter email address.<br>";
		}
		
		if ($frmdata['email']!='')
		{
			 $exist=$DB->SelectRecord('candidate',"email='".$frmdata['email']."' and id!=".$nameID);
			 
			 if($exist->email!='')
			 {
				$err.="This email id is already in use for another student.<br>";
			 }
		}
		
		if ($frmdata['password']=='')
		{
			 //$err.="Please enter password.<br>";
			 unset($frmdata['password']);
		}
		else
		{
			$candidate_info = $DB->SelectRecord('candidate',"id=$nameID");
			if($frmdata['confirm_password']=='')
			{
				$err.="Please enter confirm password.<br>";
			}
			elseif($frmdata['confirm_password']!=$frmdata['password'])
			{
				$err.="Confirm password doesn't match with password.<br>";
			}
		}
		
		if ($frmdata['birth_date'] == '')
		{
			$err.="Please enter date of birth.<br>";
		}
		
		if ($frmdata['birth_date']!='')
		{
			$birth_date = date( 'Y-m-d H:i:s', strtotime($frmdata['birth_date']) );
			$req_date=time();
			
			if(strtotime($birth_date)>$req_date)//check if 18 year or not
			{
				$err.="Birth date must be less than today.<br>";
			}
		}

		if (($frmdata['exam_id']=='') || (count($frmdata['exam_id']) == 0))
		{
			 //$err.="Please select at-least one course.<br>";
		}
		
		if($_FILES['pro_resume']['tmp_name']!='')
		{
			if($_FILES['pro_resume']['type']!='application/pdf' && $_FILES['pro_resume']['type']!='application/msword' && $_FILES['pro_resume']['type']!='application/vnd.openxmlformats-officedocument.wordprocessingml.document')
			{
				$err.="Please upload resume in correct format.<br>";
			}
			
			if($_FILES['pro_resume']['size']>2000000)
			{
				$err.="Please upload resume not more than 2MB.<br>";
			}
		}
		/*
		if ($_FILES['pro_image']['tmp_name']!='')
		{
		
		
			if($_FILES['pro_image']['type']!='image/jpeg' && $_FILES['pro_image']['type']!='image/jpg' && $_FILES['pro_image']['type']!='image/gif' && $_FILES['pro_image']['type']!='image/png')
			{
				$err.="Please upload image in right format.<br>";
			}
			
			if($_FILES['pro_image']['size']>2000000)
			{
				$err.="Please upload image not more than 2MB.<br>";
			}
		}
		*/
		if($_FILES['pro_sign']['tmp_name']!='')
		{
			if(!in_array($_FILES['pro_sign']['type'], array('image/jpeg', 'image/jpg', 'image/gif', 'image/png')))
			{
				$err.="Please upload signature image in correct format.<br>";
			}
			
			if($_FILES['pro_sign']['size']>2000000)
			{
				$err.="Please upload signature image not more than 2MB.<br>";
			}
		}
		
		if($_SESSION['cand_edu']['total_edu'] <= 0)
		{
		//	$err .= 'Please enter education details.<br>';
		}
		else
		{
			$twelfth = false;
			$tenth = false;
			
			for($count = 0; $count <= $_SESSION['cand_edu']['count_edu']; $count++)
			{
				if($_SESSION['cand_edu']['class_'.$count] == 12)
				{
					$twelfth = true;
				}
			
				if($_SESSION['cand_edu']['class_'.$count] == 10)
				{
					$tenth = true;
				}
			}
			
			if($tenth == false || $twelfth == false)
			{
			//	$err .= "Please enter education details of 10<sup>th</sup> and 12<sup>th</sup> class.<br>";
			}
		}
		
		}/*End of else which is used for check value of edit account sybmit button*/
		
		if($err=='')
		{
			if($frmdata['password'])
			{
				$frmdata['password']=Encrypt($frmdata['password']);
			}
			
			if($_FILES['pro_image']['tmp_name']!='')
			{
				$cond_detail=$DB->SelectRecord('candidate',"id=".$nameID);
				$image_path = ROOT.'/panel/uploadfiles';
				$image_fullpath=ROOT.'/panel/uploadfiles/'.$_FILES['pro_image']['name'];
				//unlink($image_path."/".$cond_detail->pro_image);
			$FileObject = (object)$_FILES['pro_image'];
			 UploadImageFile($image_fullpath,$FileObject,'image');
				
			$frmdata['pro_image']=$image_fullpath;
				
				
			}
			
			if($_FILES['pro_resume']['tmp_name']!='')
			{
				$cond_detail=$DB->SelectRecord('candidate',"id=".$nameID);
				$resume_path = ROOT.'/uploadfiles';
				unlink($resume_path."/".$cond_detail->pro_resume);
				
				$resume_path=ROOT.'/uploadfiles/'.$_FILES['pro_resume']['name'];
				$FileObject = (object)$_FILES['pro_resume'];
				UploadFile($resume_path,$FileObject,'resume');
				$frmdata['pro_resume']=$resume_path;
			}
				
			if($_FILES['pro_sign']['tmp_name']!='')
			{
				$cond_detail=$DB->SelectRecord('candidate',"id=".$nameID);
				$sign_path = ROOT.'/uploadfiles';
				unlink($sign_path."/".$cond_detail->pro_sign);
				
				$image_path=ROOT.'/uploadfiles/'.$_FILES['pro_sign']['name'];
				$FileObject = (object)$_FILES['pro_sign'];
				UploadFile($image_path,$FileObject,'image');
				$frmdata['pro_sign']=$image_path;
			}
			
			if($frmdata['birth_date']!='')
				$frmdata['birth_date'] = date( 'Y-m-d H:i:s', strtotime($frmdata['birth_date']) );
			
			
			$DB->UpdateRecord('candidate',$frmdata,'id="'.$nameID.'"');
			
			/******** Add exam candidates *************************************/
			
			$DB->DeleteRecord('exam_candidate','candidate_id="'.$nameID.'"');
			foreach($frmdata['exam_id'] as $exam_id)
			{
				$exam_candidate = array('candidate_id' => $nameID);
				$exam_candidate['exam_id'] = $exam_id;
				
				$DB->InsertRecord('exam_candidate', $exam_candidate);
			}
			
			/******** Add Education Details ***********************************/
			$DB->DeleteRecord('candidate_education','candidate_id="'.$nameID.'"');
			
			for($count = 0; $count <= $_SESSION['cand_edu']['count_edu']; $count++)
			{
				$frmdata['candidate_id'] = $nameID;
				
				if(isset($_SESSION['cand_edu']['class_'.$count]) && ($_SESSION['cand_edu']['class_'.$count] != ''))
				{
					$frmdata['class'] = $_SESSION['cand_edu']['class_'.$count];
					$frmdata['year'] = $_SESSION['cand_edu']['year_'.$count];
					$frmdata['percentage_marks'] = $_SESSION['cand_edu']['pmarks_'.$count];
					$frmdata['maximum_marks'] = $_SESSION['cand_edu']['mmarks_'.$count];
					$frmdata['obtained_marks'] = $_SESSION['cand_edu']['omarks_'.$count];
					$frmdata['grade'] = $_SESSION['cand_edu']['grade_'.$count];
					$frmdata['stream'] = $_SESSION['cand_edu']['stream_'.$count];
					$frmdata['stream'] = $_SESSION['cand_edu']['stream_'.$count];
					
					$DB->InsertRecord('candidate_education', $frmdata);
				}
			}
			unset($_SESSION['cand_edu']);
			/******************************************************************/
			
			/******** Add Experience Details ***********************************/
			$DB->DeleteRecord('candidate_experience','candidate_id="'.$nameID.'"');
			
			for($count = 0; $count <= $_SESSION['cand_exp']['count_exp']; $count++)
			{
				$frmdata['candidate_id'] = $nameID;
				
				if(isset($_SESSION['cand_exp']['company_'.$count]) && ($_SESSION['cand_exp']['company_'.$count] != ''))
				{
					$frmdata['company'] = $_SESSION['cand_exp']['company_'.$count];
					$frmdata['from_date'] = date( 'Y-m-d H:i:s', strtotime($_SESSION['cand_exp']['from_'.$count]) );
					$frmdata['to_date'] = date( 'Y-m-d H:i:s', strtotime($_SESSION['cand_exp']['to_'.$count]) );
					$frmdata['stream'] = $_SESSION['cand_exp']['stream_'.$count];
					$frmdata['details'] = $_SESSION['cand_exp']['detail_'.$count];
					
					$DB->InsertRecord('candidate_experience', $frmdata);
				}
			}
			unset($_SESSION['cand_exp']);
			/******************************************************************/
			
			if($_GET['show'] && $_GET['show']==1)
			{
				Redirect(CreateURL('index.php','mod=dashboard&do=showinfo&master_nav=1'));
			}
			else
			{
			$_SESSION['success']="Your details has been updated successfully.";
			$frmdata='';
			Redirect(CreateURL('index.php','mod=account&do=myaccount'));
			}
			exit;
		}
		elseif($err!='')
		{
			$_SESSION['error']=$err;
		}
		
		//print_r($_SESSION['error']);
	}
	
	
	/******************************************************************
	Des: A function to get candidate information
	******************************************************************/
	
	function getRecordCandidate(&$totalCount)
	{
			
		global $DB,$frmdata;
		
		$query='';
		$query="select cand.*, GROUP_CONCAT(e.exam_name SEPARATOR ', ') as exam_name
				from ".PREFIX."candidate cand
				LEFT JOIN ".PREFIX."exam_candidate on cand.id = exam_candidate.candidate_id 
				LEFT JOIN ".PREFIX."examination e on e.id = exam_candidate.exam_id ";
		
		if($_SESSION['admin_user_type'] == 'P')
		{
			$query .= " JOIN ".PREFIX."parent_candidate pc on pc.candidate_id = cand.id ";
			$query .= " WHERE (pc.is_approved = '1') AND pc.parent_id = '".$_SESSION['admin_user_id']."' AND ";
		}
		elseif ($_SESSION['admin_user_type'] == 'T')
		{
			$query .= " JOIN exam_candidate_teacher ect ON ((ect.candidate_id = cand.id)) ";
			$query .= " WHERE ect.teacher_id = '".$_SESSION['admin_user_id']."' AND ";
		}
		else
		{
			$query.=" where 1 and ";
		}
		
		if(isset($frmdata['name']) && $frmdata['name'] !='')
		{
			$query.="(";
			$query.=" cand.first_name like '%".$frmdata['name']."%' or 
						cand.last_name like '%".$frmdata['name']."%' or
						(concat(trim(cand.first_name),' ',trim(cand.last_name)) like '%".addslashes($frmdata['name'])."%')";
			$query.=")";
			$query.=" and";	
		}
		if(isset($frmdata['exam_id_manage']) && $frmdata['exam_id_manage'] !='')
		{
			$query.=" exam_candidate.exam_id='".$frmdata['exam_id_manage']."'";
			$query.=" and";	
		}
		
		$query=substr($query,0,(strlen($query)-4));
		
		$query.= ' GROUP BY (cand.id) ';
		
		if(isset($frmdata['orderby']) && $frmdata['orderby']!='' && ($frmdata['orderby']!='log.created_date') )
		{	
	  		$query.=" order by ".$frmdata['orderby'];
		}	
		else
		{
			$query.=' order by id desc ';
		}
		
	 	//echo $query;//exit;
		$result = $DB->RunSelectQueryWithPagination($query,$totalCount);
		// print_r($result);exit;
		return $result;   
	}
	
	//==========================================================================
	//to upload candidates
	//==========================================================================	
	function listCandidate()
	{
		global $DB,$frmdata;

		$log_str = '';
		$err='';
		$log_err_str = '';
		$warning = '';
		$log_warning_str = '';
		$warning_no = 0;
		$upload_fail_candidate=0;
		$uploaded_candidate = 0;
		$err_img_no = 0;
		$log_err_img = '';
		$err_session=0;
		$uploaded_files = array();

		$candidate_table_fields = array('first_name', 'last_name', 'address', 'email', 'contact_no', 'exam_id', 'candidate_id', 'password', 'martial_status', 'children', 'birth_date', 'pro_image', 'remark');
		$candidate_data = array();
		
		$edu_table_fields = array('class','year','maximum_marks','obtained_marks','grade','stream');
		$edu_data = array();
		
		$exp_table_fields = array('company','from_date','to_date','ex-stream','details');
		$exp_data = array();
		
		$parent_table_fields = array('first_name');

		$forceUpload = 0;
		$fileName = $_FILES["candidate_list"]["name"];
		$fileTempName = $_FILES["candidate_list"]["tmp_name"];
		
		if(isset($frmdata['force_upload']) && ($frmdata['force_upload'] == 1))
		{
			$forceUpload = 1;
			$fileName = $fileTempName = ROOTURL . '/uploadfiles/' . $_SESSION['file_path'];
		}
		
		$row = 0;
		$file_ext = substr($fileName, -3);
		if($file_ext != "csv")
		{
			$_SESSION['error']='<span style="padding-left: 185px;">Please select CSV file.</span>';
			return false;
		}
		
		$handle = fopen($fileTempName, "r");
		if (!$handle) 
		{
			$_SESSION['error']='<span style="padding-left: 185px;">An error occoured while reading file.</span>';
			return false;
		}
		
		$csv_row_no=1;
		
		$autoId = $DB->ExecuteQuery("SHOW TABLE STATUS LIKE 'candidate'");
		$autoId = $DB->FetchArray($autoId);
		$autoId = $autoId['Auto_increment'];
		$autoId;

		while (($data = fgetcsv($handle, 1000, ","))) 
		{
			$num = count($data);
			if($frmdata['skip']=='y')
	       	{
		       	unset($frmdata['skip']);
		       	$csv_row_no++;
		       	continue;
   		 	}
       
	    	if(count(array_filter($data))==0)
    		{
    			$row++;
    			continue;
    		}

	    	$data[1]  = trim($data[1]);		//===first name===
	    	$data[2]  = trim($data[2]);		//===last name===
	    	$data[3]  = trim($data[3]);		//===address===
	    	$data[4]  = trim($data[4]);		//===email===
	    	$data[5]  = trim($data[5]);		//===contact no.===
	    	$data[6]  = trim($data[6]);		//===course===
	    	$data[7]  = trim($data[7]);		//===username===
	    	$data[8]  = trim($data[8]);		//===password===
	    	$data[9]  = trim($data[9]);		//===Marital status===
	    	$data[10] = trim($data[10]);	//===Children===
	    	$data[11] = trim($data[11]);	//===Date of birth===
	    	$data[12] = trim($data[12]);	//===Profile Image===
	    	$data[13] = trim($data[13]);	//===Remark===    	
	    	
    		if($data[1]=='')
	       	{
       			$upload_fail_candidate++;
       			$err.= "&nbsp;".$upload_fail_candidate.".) Row No. ".$csv_row_no.", student's first name not entered.<br/>";
       			$log_err_str.= " ".$upload_fail_candidate.".) Row No. ".$csv_row_no.", student's first name not entered.\r\n";
       			$csv_row_no++;
       			continue;
       		}
       		else
       		{
       			if(!(preg_match("#^[A-Za-z'. ]*$#", $data[1])))
       			{
       				$upload_fail_candidate++;
       				$err.= "&nbsp;".$upload_fail_candidate.".) Row No. ".$csv_row_no.", First name is not valid string.<br/>";
       				$log_err_str.= " ".$upload_fail_candidate.".) Row No. ".$csv_row_no.", First name is not valid string.\r\n";
       				$csv_row_no++;
       				continue;
       			}
	       	}			
		       			
		    if($data[4]=='')
	       	{
       			$upload_fail_candidate++;
       			$err.= "&nbsp;".$upload_fail_candidate.".) Row No. ".$csv_row_no.", student's email id not entered.<br/>";
       			$log_err_str.= " ".$upload_fail_candidate.".) Row No. ".$csv_row_no.", student's email id not entered.\r\n";
       			$csv_row_no++;
       			continue;
       		}
       		else
		    {
            	if(!(preg_match("/^([a-z0-9._-]{1,100})+@([-a-z0-9]{3,500}\.)+([a-z]{2}|com|net|edu|org|gov|mil|int|biz|pro|info|arpa|aero|coop|name|museum|nic|in|co.in|co|inc|ac.in)$/", $data[4])))
            	{
            		$upload_fail_candidate++;
       				$err.= "&nbsp;".$upload_fail_candidate.".) Row No. ".$csv_row_no.", email is not valid.<br/>";
       				$log_err_str.= " ".$upload_fail_candidate.".) Row No. ".$csv_row_no.", email is not valid.\r\n";
       				$csv_row_no++;
       				continue;
            	}
            	else
            	{
            		$emailExists = 0;
            		$candidate_email_info = $DB->SelectRecord('candidate', "email='".$data[4]."'");
            		if($candidate_email_info)
            		{
            			$emailExists = 1;
            		}
            		else
            		{
            			for($checkEmail = 0; $checkEmail <= $row; $checkEmail++)
            			{
            				if($candidate_data[$checkEmail]['email'] == $data[4])
            				{
            					$emailExists = 1;
            					break;
            				}
            			}
            		}
            		
            		if($emailExists == 1)
            		{
            			$upload_fail_candidate++;
       					$err.= "&nbsp;".$upload_fail_candidate.".) Row No. ".$csv_row_no.", This email id is already in use for another student.<br/>";
       					$log_err_str.= " ".$upload_fail_candidate.".) Row No. ".$csv_row_no.", This email id is already in use for another student.\r\n";
       					$csv_row_no++;
       					continue;
            		}
            	}
            }
				       	
    		if($data[6]=='')
       		{
       			$upload_fail_candidate++;
       			$err.= "&nbsp;".$upload_fail_candidate.".) Row No. ".$csv_row_no.", Course not entered.<br/>";
       			$log_err_str.= " ".$upload_fail_candidate.".) Row No. ".$csv_row_no.", Course not entered.\r\n";
       			$csv_row_no++;
       			continue;
       		}
       		else
       		{
       			if(strlen($data[6]) < 2)
       			{
       				$upload_fail_candidate++;
       				$err.= "&nbsp;".$upload_fail_candidate.".) Row No. ".$csv_row_no.", Course is less than 2 characters long.<br/>";
       				$log_err_str.= " ".$upload_fail_candidate.".) Row No. ".$csv_row_no.", Course is less than 2 characters long.\r\n";
       				$csv_row_no++;
       				continue;
       			}
       			elseif(!(preg_match("#^[-A-Za-z0-9'&./ ()]*$#", $data[6])))
       			{
       				$upload_fail_candidate++;
       				$err.= "&nbsp;".$upload_fail_candidate.".) Row No. ".$csv_row_no.", Course is not valid string.<br/>";
       				$log_err_str.= " ".$upload_fail_candidate.".) Row No. ".$csv_row_no.", Course is not valid string.\r\n";
       				$csv_row_no++;
       				continue;
       			}
       			else
       			{
       				$condition = "exam_name='".$data[6]."'";
            		$exam = $DB->SelectRecord('examination', $condition);
            		if($exam && $exam->id)
            		{
            			$data[6] = $exam->id;
            		}
            		else
            		{
	       				$upload_fail_candidate++;
	       				$err.= "&nbsp;".$upload_fail_candidate.".) Row No. ".$csv_row_no.", Course doesn't exists.<br/>";
	       				$log_err_str.= " ".$upload_fail_candidate.".) Row No. ".$csv_row_no.", Course doesn't exists.\r\n";
	       				$csv_row_no++;
	       				continue;
            		}
       			}
       		}
       			
			if($data[7]=='')
	       	{
       			$upload_fail_candidate++;
       			$err.= "&nbsp;".$upload_fail_candidate.".) Row No. ".$csv_row_no.", Username not entered.<br/>";
       			$log_err_str.= " ".$upload_fail_candidate.".) Row No. ".$csv_row_no.", Username not entered.\r\n";
       			$csv_row_no++;
       			continue;
       		}
       		else
       		{
       			if(!(preg_match("#^[A-Za-z0-9\.]*$#", $data[7])))
            	{
            		$upload_fail_candidate++;
       				$err.= "&nbsp;".$upload_fail_candidate.".) Row No. ".$csv_row_no.", Invalid username.<br/>";
       				$log_err_str.= " ".$upload_fail_candidate.".) Row No. ".$csv_row_no.", Invalid username.\r\n";
       				$csv_row_no++;
       				continue;
            	}
            	
       			$candidate_info = $DB->SelectRecord('candidate', "candidate_id='".$data[7]."'");
       			
       			$unameExists = 0;
       			if($candidate_info)
            	{
            		$unameExists = 1;
            	}
            	else
            	{
            		for($checkUname = 0; $checkUname <= $row; $checkUname++)
            		{
            			if($candidate_data[$checkUname]['candidate_id'] == $data[7])
            			{
            				$unameExists = 1;
            				break;
            			}
            		}
            	}
       		
            	if($unameExists)
       			{
       				$upload_fail_candidate++;
       				$err.= "&nbsp;".$upload_fail_candidate.".) Row No. ".$csv_row_no.", Username already exist.<br/>";
       				$log_err_str.= " ".$upload_fail_candidate.".) Row No. ".$csv_row_no.", Username already exist.\r\n";
       				$csv_row_no++;
       				continue;
       			}
       		}
			              	
    		if($data[8]=='')
       		{
       			$upload_fail_candidate++;
       			$err.= "&nbsp;".$upload_fail_candidate.".) Row No. ".$csv_row_no.", Password not entered.<br/>";
       			$log_err_str.= " ".$upload_fail_candidate.".) Row No. ".$csv_row_no.", Password not entered.\r\n";
       			$csv_row_no++;
       			continue;
       		}
       		elseif(strlen($data[8])<5 || strlen($data[8])>10)
       		{
       			$upload_fail_candidate++;
       			$err.= "&nbsp;".$upload_fail_candidate.".) Row No. ".$csv_row_no.", Password is not in valid length (i.e. 5 to 10 characters).<br/>";
       			$log_err_str.= " ".$upload_fail_candidate.".) Row No. ".$csv_row_no.", Password is not in valid length (i.e. 5 to 10 characters).\r\n";
       			$csv_row_no++;
       			continue;
       		}
				       	
       		if($data[11] != '')
       		{
       			if(strpos($data[11], '/'))
				{
					$data[11] = str_replace('/', '-', $data[11]);
				}
				
            	if(!(preg_match ("/^([0-9]{1,2})-([0-9]{1,2})-([0-9]{4})$/", $data[11])))
            	{
            		$upload_fail_candidate++;
       				$err.= "&nbsp;".$upload_fail_candidate.".) Row No. ".$csv_row_no.", Date of birth is not valid.<br/>";
       				$log_err_str.= " ".$upload_fail_candidate.".) Row No. ".$csv_row_no.", Date of birth is not valid.\r\n";
       				$csv_row_no++;
       				continue;
            	}
            	elseif(strtotime($data[11]) == '')
            	{
            		$upload_fail_candidate++;
       				$err.= "&nbsp;".$upload_fail_candidate.".) Row No. ".$csv_row_no.", Please enter a valid Birth Date.<br/>";
       				$log_err_str.= " ".$upload_fail_candidate.".) Row No. ".$csv_row_no.", Please enter a valid Birth Date.<br/>";
       				$csv_row_no++;
       				continue;
            	}
            	else
            	{
            		$req_date= time();
					if(strtotime($data[11])>$req_date)
					{
						$upload_fail_candidate++;
	       				$err.= "&nbsp;".$upload_fail_candidate.".) Row No. ".$csv_row_no.", Birth date must be less than today.<br/>";
	       				$log_err_str.= " ".$upload_fail_candidate.".) Row No. ".$csv_row_no.", Birth date must be less than today.<br/>";
	       				$csv_row_no++;
	       				continue;
					}
            	}
       		}
		       			
			for ($counter=1; $counter < $num; $counter++) 
	        {
	           	if($candidate_table_fields[$counter-1]=='password')
            	{
            		$candidate_data[$row][$candidate_table_fields[$counter-1]] = Encrypt($data[$counter]);
            	}
	        	
               	elseif($candidate_table_fields[$counter-1]=='pro_image')
            	{
            		if($data[$counter]!='')
            		{
            			$time = time().uniqid();;
            			$temp_img_path =  TEMPIMG."/candidate/".$data[$counter];//exit;
            			if(file_exists($temp_img_path))
            			{
            				$image_size = filesize($temp_img_path);
            				$image_info = getimagesize($temp_img_path);
							
            				if($image_size>2000000)
            				{
            					$warning_no++;
	       						$warning.= "&nbsp;".$warning_no.".) Row No. ".$csv_row_no.", Image not in valid size.<br/>";
	       						$log_warning_str.= " ".$warning_no.".) Row No. ".$csv_row_no.", Image not in valid size.\r\n";
            				}
            				elseif($image_info['mime']!='image/jpeg' && $image_info['mime']!='image/jpg' && $image_info['mime']!='image/gif' && $image_info['mime']!='image/png')
							{
								$warning_no++;
	       						$warning.= "&nbsp;".$warning_no.".) Row No. ".$csv_row_no.", Image not in right format.<br/>";
	       						$log_warning_str.= " ".$warning_no.".) Row No. ".$csv_row_no.", Image not in right format.\r\n";
							}
            				else
            				{
            					copy($temp_img_path, ROOT."/uploadfiles/".$time.$data[$counter]);
            					$uploaded_files[] = $candidate_data[$row][$candidate_table_fields[$counter-1]] = $time.$data[$counter];
            				}
            			}
            			else
            			{
            				$warning_no++;
	       					$warning.= "&nbsp;".$warning_no.".) Row No. ".$csv_row_no.", Image not found.<br/>";
	       					$log_warning_str.= " ".$warning_no.".) Row No. ".$csv_row_no.", Image not found.\r\n";
            			}
            		}
            	}
            	
            	elseif($candidate_table_fields[$counter-1]=='last_name')
            	{
            		if($data[$counter]!='')
            		{
            			if(!(preg_match("#^[A-Za-z'. ]*$#", $data[$counter])))
            			{
            				$warning_no++;
	       					$warning.= "&nbsp;".$warning_no.".) Row No. ".$csv_row_no.", last name is not valid string.<br/>";
	       					$log_warning_str.= " ".$warning_no.".) Row No. ".$csv_row_no.", last name is not valid string.\r\n";
            			}
            			else
            			{
            				$candidate_data[$row][$candidate_table_fields[$counter-1]] = $data[$counter];
            			}
            		}
            	}
        		
            	elseif($candidate_table_fields[$counter-1]=='contact_no')
            	{
            		if($data[$counter]!='')
            		{
            			if(!(preg_match("/^\+?([0-9]{2,4})-?([0-9]{3,5})-?([0-9]{4,8})$/", $data[$counter])))
            			{
            				$warning_no++;
	       					$warning.= "&nbsp;".$warning_no.".) Row No. ".$csv_row_no.", contact number is not valid.<br/>";
	       					$log_warning_str.= " ".$warning_no.".) Row No. ".$csv_row_no.", contact number is not valid.\r\n";
		            	}
            			else
            			{
            				$contact_noExists = 0;
            				$candidate_contact_no_info = $DB->SelectRecord('candidate', "contact_no='".$data[$counter]."'");
            				if($candidate_contact_no_info)
            				{
            					$contact_noExists = 1;
            				}
            				else
            				{
            					for($checkcontact_no = 0; $checkcontact_no <= $row; $checkcontact_no++)
            					{
            						if($candidate_data[$checkcontact_no]['contact_no'] == $data[$counter])
            						{
            							$contact_noExists = 1;
            							break;
            						}
            					}
            				}
            				
            				if($contact_noExists == 1)
            				{
            					$warning_no++;
       							$warning.= "&nbsp;".$warning_no.".) Row No. ".$csv_row_no.", This contact no is already in use for another student.<br/>";
       							$log_warning_str.= " ".$warning_no.".) Row No. ".$csv_row_no.", This contact no is already in use for another student.\r\n";
            				}
            				
            				$candidate_data[$row][$candidate_table_fields[$counter-1]] = $data[$counter];
            			}
            		}
            	}
	        	
        		elseif($candidate_table_fields[$counter-1]=='martial_status')
            	{
            		if($data[$counter]!='')
            		{
            			$data[$counter] = strtoupper($data[$counter]);
            			if($data[$counter]!='M' && $data[$counter]!='S')
            			{
            				$warning_no++;
	       					$warning.= "&nbsp;".$warning_no.".) Row No. ".$csv_row_no.", marital status is not valid.<br/>";
	       					$log_warning_str.=" ".$warning_no.".) Row No. ".$csv_row_no.", marital status is not valid.\r\n";
		            	}
            			else
            			{
            				$candidate_data[$row][$candidate_table_fields[$counter-1]] = $data[$counter];
            			}	
            		}
            	}
        		
            	elseif($candidate_table_fields[$counter-1]=='children')
            	{
            		if($data[$counter]!='')
            		{
            			if(!(filter_var($data[$counter], FILTER_VALIDATE_INT)) && ($data[$counter] != 0))
            			{
            				$warning_no++;
	       					$warning.= "&nbsp;".$warning_no.".) Row No. ".$csv_row_no.", children not an integer.<br/>";
	       					$log_warning_str.= " ".$warning_no.".) Row No. ".$csv_row_no.", children not an integer.\r\n";
		            	}
            			else
            			{
            				$candidate_data[$row][$candidate_table_fields[$counter-1]] = $data[$counter];
            			}
            		}
            	}
            	
            	elseif($candidate_table_fields[$counter-1]=='birth_date')
            	{
            		if($data[$counter]!='')
            		{
            			$candidate_data[$row][$candidate_table_fields[$counter-1]] = date('Y-m-d', strtotime($data[$counter]));
            		}
            	}
            	
            	else if($data[$counter]!='')
            	{
            		$candidate_data[$row][$candidate_table_fields[$counter-1]] = $data[$counter];
            	}
        	}
        	
	        $uploaded_candidate++;
	        
	        $row++;
    		$csv_row_no++;
	   	}
	
	   	//	echo $err;
		//	echo "<pre>";print_r($candidate_data);exit;
	   	fclose($handle);
		
		if($err!='')
		{
			$candidate_err_heading="$upload_fail_candidate student details not uploaded because of following error: <br/>".$err;
			$log_str.="\r\n$upload_fail_candidate student details not uploaded because of following error: \r\n".$log_err_str;
			$_SESSION['error']='<div style="overflow: scroll; overflow-x: hidden; height:100px;">'.$candidate_err_heading.'</div>';
			$err_session=1;
		}
		
		if($warning != '')
		{
			if($forceUpload == 1)
			{
				$_SESSION['warning']='<div style="overflow: scroll; overflow-x: hidden; height:100px;">Student added but some details are not, due to following reasons:<br/>'.$warning.'</div>';
				$log_str.="\r\nCandidate uploaded but some details are not, due to following reasons:\r\n".$log_warning_str;
			}
			else
			{
				$onClick = "window.location='".ROOTURL."/index.php?mod=candidate_master&do=upload_candidate'";
				$_SESSION['warning']='<div style="overflow: scroll; overflow-x: hidden; height:100px;">'.
					'Some details of students will not be uploaded due to following reasons:<br/>'
					.$warning.'<br>'.
					'</div>'.
					'<div style="border:2px #CCCCCC solid;margin-top:2px;padding:5px;">'.
					'<b>Do you still want to add these records?</b>&nbsp;&nbsp;'.
					'<form action="" method="post">'.
					'<input type="hidden" name="force_upload" id="force_upload" value="1" />'.
					'<input type="submit" name="listCandidate" value="Proceed Anyway" class="buttons" />&nbsp;&nbsp;'.
					'<input type="button" value="Upload Later" class="buttons" onclick="'.$onClick.'"/>'.
					'</form>'.
					'</div>';
			}
		}
		
		if($err_img_no>0)
        {
            $img_err_heading = ($err_img_no)." images not uploaded because :<br/>".$err_img;
            $log_str.= "\r\n".($err_img_no)." images not uploaded because :<br/>".$log_err_img;
            if($err_session==0)
            {
            	$_SESSION['error'].='<div style="overflow: scroll; overflow-x: hidden; height:100px;">'.$img_err_heading.'</div>';
            }
            else
            {
            	$_SESSION['error'].="<br/>".$img_err_heading.'</div>';
            }
        }
        
        $isUploaded = 0;
		if($warning == '' || $forceUpload == 1)
		{
			$lastInsertId = '';
			$isUploaded = 1;
			for($count=0; $count <= $row; $count++)
			{
				if(isset($candidate_data[$count]))
				{
					$lastInsertId = $DB->InsertRecord('candidate', array_filter($candidate_data[$count]));
					$candidate_data[$count]['candidate_id'] = $lastInsertId;
					$DB->InsertRecord('exam_candidate', array_filter($candidate_data[$count]));
				}
			}

			if(isset($_SESSION['file_path']))
			@unlink(ROOT . '/uploadfiles/' . $_SESSION['file_path']);
		}
		else
		{
			UploadFile($target, (object)$_FILES["candidate_list"]);
			$_SESSION['file_path'] = $target;
			
			foreach($uploaded_files as $up)
			{
				@unlink(ROOT . '/uploadfiles/' . $up);
			}
			$uploaded_files = array();
		}
        
		if($uploaded_candidate>0 && $isUploaded == 1)
		{
			$_SESSION['success']="$uploaded_candidate student details have been uploaded successfully.";
			$log_str.="\r\n$uploaded_candidate student details have been uploaded successfully.";
			$frmdata='';
		}
		
		$myFile = ROOT."/log/candidate_log.txt";
		@chmod($myFile, 0777);
		$fh = fopen($myFile, 'a+') or die("can't open file");
		$stringData = "\r\n\r\nDate:".date('d-m-Y h:i:s A')."\r\n";
		fwrite($fh, $stringData);
		fwrite($fh, $log_str);
		fclose($fh);
		
		if((isset($_SESSION['error'])) && $_SESSION['error']=='')
		{
			//Redirect(CreateURL('index.php','mod=candidate_master&do=manage'));
			exit;
		}
		
	}
	//==========================================================================	

	function getCandidateLog(&$totalCount)
	{
		global $DB,$frmdata;
		$cond=0;
		
		//echo "<pre>";print_r($frmdata);
		
		$query="SELECT cand . * , log . *
					FROM (
						SELECT cl.candidate_id as cand_id, cl.created_date, 'Login' AS log_type FROM candidate_log cl
						UNION
						SELECT ct.candidate_id as cand_id, ct.created_date, 'Test' AS log_type	FROM candidate_test_history ct
						UNION
						SELECT cst.candidate_id as cand_id, cst.created_date, 'Sample Test' AS log_type FROM candidate_sample_test_history cst
					) AS log
					JOIN candidate AS cand ON cand.id = log.cand_id ";
		
		if($_SESSION['admin_user_type'] == 'P')
		{
			$query .= " JOIN parent_candidate pc on pc.candidate_id = cand.id ";
			$query .= " WHERE (pc.is_approved = '1') AND pc.parent_id = '".$_SESSION['admin_user_id']."' ";
		}
		elseif ($_SESSION['admin_user_type'] == 'T')
		{
			$query .= " JOIN exam_candidate_teacher ect ON ((ect.candidate_id = cand.id)) ";
			$query .= " WHERE ect.teacher_id = '".$_SESSION['admin_user_id']."' ";
		}
		else
		{
			$query.=" where 1 ";
		}
		
		if(isset($frmdata['name']) && $frmdata['name'] !='')
		{
			$query.=" and (";
			$query.=" cand.first_name like '%".$frmdata['name']."%' or 
						cand.last_name like '%".$frmdata['name']."%' or
						(concat(trim(cand.first_name),' ',trim(cand.last_name)) like '%".addslashes($frmdata['name'])."%')";
			$query.=")";
		}
		if(isset($frmdata['log_type']) && $frmdata['log_type'] !='')
		{
			$query.=" and log.log_type like '%".$frmdata['log_type']."%'";			
		}

		
		if(isset($frmdata['orderby']) && $frmdata['orderby']!='' )
		{
	  		$query.=" order by ".$frmdata['orderby'];
		}	
		else
		{
			$query.=' order by log.created_date DESC ';
		}
		
		//echo $query;
		
		$_SESSION['queryForCandidateLog'] = $query;
		
		$result = $DB->RunSelectQueryWithPagination($query, $totalCount);
		
		return $result;
	}

	function linkCandidate()
	{
		global $DB,$frmdata;
		
		if($frmdata['email'] == '')
		{
			$_SESSION['error'] = 'Please enter email address.';
			return false;
		}
		
		$email = $frmdata['email'];
		$candidate = $DB->SelectRecord('candidate', "email = '".addslashes($email)."'");
		
		if($candidate && $candidate->id)
		{
			$parent_id = $_SESSION['admin_user_id'];
			$pc = $DB->SelectRecord('parent_candidate', "(parent_id = '$parent_id') AND (candidate_id = '$candidate->id')");
			
			if($pc && $pc->id)
			{
				if($pc->is_approved == 1)
				{
					$error = 'You are already linked with this student.';
				}
				else
				{
					$error = 'We have already recieved this request from you.<br>You will be informed shortly.';
				}
				
				$_SESSION['error'] = $error;
				return false;
			}
			else
			{
				$data['parent_id'] = $parent_id;
				$data['candidate_id'] = $candidate->id;
				$data['is_approved'] = '0';
				$DB->InsertRecord('parent_candidate', $data);
				$_SESSION['success'] = 'Your request is sucessfully sent to administrator.';
				return true;
			}
		}
		else
		{
			$_SESSION['error'] = 'No student found with this email address.';
		}
	}
	
}//end of class
?>