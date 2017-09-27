<?php
/**************************************************************************
					Developer: Shakti Singh
					Date: 26-03-2012
					A class for authorization
**************************************************************************/
class Authorise
{
	 var $read ;
	 var $add ;
	 var $edit ;	
	 var $permission;
	 
	/* Construct: map the actions with their synonyms terms used throughout the system*/
	function __construct()
	{
		$this->read = array('showinfo', 'total', 'fail', 'pass', 'manage', 'archive','result_sheet', 
							'test_detail','list','view','viewexam_detail','view_test_result',
							'candidate_performance','candidate_result_details','preview','log',
							'question_xml','test_xml','candidate_xml', 'details', 'discussion', 'link');
							 
		$this->add = array('add', 'upload_question', 'upload_candidate', 
							'takeBackup', 'assign','addexam_detail','addsubjectquestion');
							
		$this->edit = array('editassignrole', 'edit','editexam_detail','upload','restore','addsubjectquestion','Change');
		
		$this->export = array('candidate_detail', 'download','export_question');
		
		$this->delete = array('delete', 'del');
		
		$this->siblingorchild = array(
										array('examination', 'test_master', 'paper', 'homework'),
										array('stream_master', 'subject_master'),
										array('role', 'users'),
										array('report', 'homework_history'),
										array('mailer', 'feedback', 'backup')
									 );
		$this->showMessage = true;							 
	}
	
	//check if the requested action is permitted for this user to perform
	//return true if yes otherwise return false
	function isAuthorisedAction($user_id, $module, $action)
	{
		global $DB;
		if($module == 'homework_history') $module = 'report';

		$this->permission = '';
		if (in_array($action, $this->read))
		{
			$this->permission = 'R';
		}
		else if (in_array($action, $this->add))
		{
			$this->permission = 'A';
		}
		else if (in_array($action, $this->edit))
		{
			$this->permission = 'E';
		}
		else if (in_array($action, $this->export))
		{
			$this->permission = 'ED';
		}
		else if (in_array($action, $this->delete))
		{
			$this->permission = 'D';
			return false;
		}
		if ($this->permission == '') //if unknown action found don't allow to access
		{
			return false;
		}
		$query = 'SELECT * FROM module m LEFT JOIN rolepermission rp 
				  ON m.moduleID = rp.moduleID
				  LEFT JOIN role r ON rp.roleID = r.roleID
				  LEFT JOIN permission p ON rp.permissionID = p.permissionID ';

		if(in_array($_SESSION['admin_user_type'], array('T', 'P')))
		{
			$roleName = ($_SESSION['admin_user_type'] == 'T') ? 'Teacher' : 'Parent'; 
			$query .= " WHERE r.roleName = '$roleName' ";
		}
		else
		{
			$query .= " LEFT JOIN admin_users au ON rp.roleID = au.role_id WHERE au.id='$user_id' ";
		}
				  
		$query .= " AND m.moduleName='$module' AND p.permissionName='$this->permission' ";
				 
				  
		$result = $DB->RunSelectQuery($query);	
		//print_r($result);
		if (!empty($result) && $result[0]->permissionID !='' )
		{
			//Permitted action! authorised 
			return true;
		}
		return false;
	}
	
	function determineRedirection($user_id, $mod, $do)
	{		
		
		$url = '';
		//if the Read permission is restricted for a module redirect them to the module for which the user has access		
		if ($this->permission == 'R' || $this->permission =='') 
		{
			//find out at least one child or sibling module to redirect them
			if ($module_name = $this->getOneAccesibleChildORSiblingModule($user_id, $mod))
			{				
				$url = CreateURL('index.php','mod='.$module_name);
				if ($_GET['master_nav'] == 1) //if the link clicked from the master navigation 
				{
					$this->showMessage = false;
				}	
			}
			else if ($this->isModuleAccessible('dashboard',$user_id)) //if the dashboard is accessible redirect them 
			{
				 $url = CreateURL('index.php','mod=dashboard&do=showinfo');
			}
			else //otherwise find the one accessible module and construct redirect url
			{
				$module_name = $this->getOneAccessibleModule($user_id);
				if($module_name != '')
				{
					$url = CreateURL('index.php','mod='.$module_name);
				}
				else
				{
					echo "<script>alert('You are no longer authorized to access admin section.');</script>";
					$url = CreateURL('index.php','pid=103');
				}
			}				
		}
		//if the Add or Edit or Export Data permission is restricted for this module redirect them to the list page of the module
		if ($this->permission == 'A' || $this->permission == 'E' || $this->permission == 'ED' || $this->permission == 'D')
		{
			$url = CreateURL('index.php','mod='.$mod); 		
		}
		return $url;
	}
	//Return the one accessible module 
	function getOneAccessibleModule($user_id)
	{
		global $DB;
		 $query = 'SELECT * FROM module m LEFT JOIN rolepermission rp 
				  ON m.moduleID = rp.moduleID
				  LEFT JOIN role r ON rp.roleID = r.roleID
				  LEFT JOIN permission p ON rp.permissionID = p.permissionID ';
		 
		if(in_array($_SESSION['admin_user_type'], array('T', 'P')))
		{
			$roleName = ($_SESSION['admin_user_type'] == 'T') ? 'Teacher' : 'Parent'; 
			$query .= " WHERE r.roleName = '$roleName' ";
		}
		else
		{
			$query .= " LEFT JOIN admin_users au ON rp.roleID = au.role_id WHERE au.id='$user_id' ";
		}
		
		$query .= " AND p.permissionName='R' LIMIT 1 ";
		
		$result = $DB->RunSelectQuery($query);		
		//print_r($result);exit;	  
		if($result)
		{
			return $result[0]->moduleName;
		}				  
	}
	//check if the module is accessible return TRUE if yes, FALSE otherwise
	function isModuleAccessible($module,$user_id)
	{
		global $DB;
		if($module == 'homework_history') $module = 'report';
		
		$query = 'SELECT * FROM module m LEFT JOIN rolepermission rp 
				  	ON m.moduleID = rp.moduleID
				  	LEFT JOIN role r ON rp.roleID = r.roleID
				  	LEFT JOIN permission p ON rp.permissionID = p.permissionID ';
		
		if(in_array($_SESSION['admin_user_type'], array('T', 'P')))
		{
			$roleName = ($_SESSION['admin_user_type'] == 'T') ? 'Teacher' : 'Parent'; 
			$query .= " WHERE r.roleName = '$roleName' ";
		}
		else
		{
			$query .= " LEFT JOIN admin_users au ON rp.roleID = au.role_id WHERE au.id='$user_id' ";
		}
		
		$query .= " AND m.moduleName='$module' AND p.permissionName='R' ";
		
		$result = $DB->RunSelectQuery($query);			  
		if($result)
		{
			return true;
		}
		else 
		{
			return false;
		}		
	}
	/**/
	//Find out at least one child or sibling module which is accesible, to redirect the users
	function getOneAccesibleChildORSiblingModule($user_id, $mod)
	{
		global $DB;
		foreach ($this->siblingorchild as $module_group)
		{
			if (in_array($mod, $module_group))
			{
				$query = 'SELECT * FROM module m LEFT JOIN rolepermission rp 
				  			ON m.moduleID = rp.moduleID
				  			LEFT JOIN role r ON rp.roleID = r.roleID
				  			LEFT JOIN permission p ON rp.permissionID = p.permissionID ';
				
				if(in_array($_SESSION['admin_user_type'], array('T', 'P')))
				{
					$roleName = ($_SESSION['admin_user_type'] == 'T') ? 'Teacher' : 'Parent'; 
					$query .= " WHERE r.roleName = '$roleName' ";
				}
				else
				{
					$query .= " LEFT JOIN admin_users au ON rp.roleID = au.role_id WHERE au.id='$user_id' ";
				}
				
				$query .= " AND m.moduleName IN ('".implode("','",$module_group)."') AND p.permissionName='R' LIMIT 1 ";
				
				$result = $DB->RunSelectQuery($query);			  
				if($result)
				{
					return $result[0]->moduleName;
				}					
			}
		}
		//nothing found accessible from child or sibling
		return false;		
	}
} //end of the class
?>