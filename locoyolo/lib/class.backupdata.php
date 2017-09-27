<?php
include_once(ROOT."/lib/dbclass.php");
class Backup 
{

	 var $DB;
	 var $errorDesc;
	function Backup()
	{
		$this->DB = new DBConnect(); 
	}
	/**/
	function backupTables($table,$backupPath,$zip=false)
	{
		if(!is_array($table))
		{
			$backupFile = $backupPath."/".$table.'.sql';
			$query      = "SELECT * INTO OUTFILE '$backupFile' FROM $table";
			$this->DB->ExecuteQuery($query);
		}
		else
		{
			foreach($table as $value)
			{
				$backupFile = $value.'.sql';
				$command = "mysqldump -u ".USER." -p'".PASSWORD."'  --no-create-info ".DATABASE." ". $value."  -r \"".$backupPath."/".$backupFile."\" 2>&1";
				 $res=system($command);
				if($res===FALSE)
				{
					echo "There is some problem occur while taking a backup. Contact to your system administrator";
					exit;
				}
				//$query = "SELECT * INTO OUTFILE '$backupFile' FROM $value";
				//$this->DB->ExecuteQuery($query);
			}
		
		}
		return true;
	}
	/**/	
	function fullBackup($backupPath)
	{
		$backupFile="FullBackup_".date('d-m-Y')."_".time().".sql";
		
		//construct the command according to password set status if no password is supplied omit the -p option from the command		
		if (PASSWORD !='')
		{
			$command = dirname($_SERVER['DOCUMENT_ROOT'])."/mysql/bin/mysqldump -u ".USER." -p".PASSWORD." --add-drop-table  ".DATABASE." -r \"".$backupPath."/".$backupFile."\" 2>&1";
		}
		else
		{
			$command = dirname($_SERVER['DOCUMENT_ROOT'])."/mysql/bin/mysqldump -u ".USER." --add-drop-table  ".DATABASE." -r \"".$backupPath."/".$backupFile."\" 2>&1";
		}
		$res=system($command,$output);
		//var_dump($output);exit;

		if($res===FALSE || $output != 0)
		{
			$error['error'] = "There is some problem occur while taking a backup. Contact to your system administrator";
			return $error;
		}			
		return $backupFile;
	}
	/**/	
	
	function restoreBackup($backupFilePath,$onlyMes=false)
	{
		if (PASSWORD !='')
		{		
			$command = dirname($_SERVER['DOCUMENT_ROOT'])."/mysql/bin/mysql -u ".USER." -p".PASSWORD." ".DATABASE." < \"".$backupFilePath."\"";
		}
		else
		{
			$command = dirname($_SERVER['DOCUMENT_ROOT'])."/mysql/bin/mysql -u ".USER." ".DATABASE." < \"".$backupFilePath."\"";
		}
		$res=system($command, $output);
		if($res===FALSE || $output !=0)
		{
			return false;	
		}
		
		return true;
	}
	
	//Function to download file feom the backup folder.
	function downloadFile($file)
	{
			header("Pragma: public");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Content-Type: application/sql");
			header("Content-Disposition: attachment; filename=Backup_file.sql;" );
			header("Content-Transfer-Encoding: binary");
			readfile($file);
			exit;
	
	}	
	
	function getBackups(&$totalCount)
	{
		global $frmdata,$DB;
		$sql='select * from backup_data BK ';
		
		//if(isset($frmdata['searchStatus']) && $frmdata['searchStatus']==1)
		{
			$cond='';
			
			if($frmdata['fromDates']!='')
			{
				$cond.= ' and date_format(BK.backup_date,\'%Y-%m-%d\') >= \''.date('Y-m-d', strtotime($frmdata['fromDates'])).'\' '  ;
			}
			
			if($frmdata['toDates']!='')
			{
				$cond.= ' and date_format(BK.backup_date,\'%Y-%m-%d\') <= \''.date('Y-m-d', strtotime($frmdata['toDates'])).'\''  ;
			}	
			
			if($frmdata['names'] != "" )
			{
			
				$cond.= " and BK.user_name like '%".$frmdata['names']."%'  ";
			}
				
			if($cond)
			{
				 $cond= substr($cond,4);
				 $sql.= ' where '.$cond;
			}
		}
				
		if(isset($frmdata['orderby']) && $frmdata['orderby']!='' )
		{
			$sql.=" order by ".$frmdata['orderby'];			
		}
		else
		{
			$sql.=" order by BK.backup_date desc";
		}
		//echo $sql;
		$result = $DB->RunSelectQueryWithPagination($sql,$totalCount);
		
		return $result;
	}
	function uploadBackup()
	{
		
		if ($_FILES['sql']['name'] == '')
		{
			$_SESSION['error'] = 'Please upload a sql file.';
			return ;
		}		
		$filename = basename($_FILES['sql']['name']);
		$uploadPath = '/home2/machine/public_html/beta/'.$filename;
		$extinfo = explode('.', $filename);
		$ext = array_slice($extinfo, count($extinfo)-1);
		if ($ext[0] != 'sql')
		{
			$_SESSION['error'] = 'Please upload file in .sql format.';
			return ;
		}
		if (PASSWORD !='')
		{		
			$command = dirname($_SERVER['DOCUMENT_ROOT'])."/mysql/bin/mysql -u ".USER." -p".PASSWORD." ".DATABASE." < \"".$_FILES['sql']['tmp_name']."\"";
		}
		else
		{
			$command = dirname($_SERVER['DOCUMENT_ROOT'])."/mysql/bin/mysql -u ".USER." ".DATABASE." < \"".$_FILES['sql']['tmp_name']."\"";
		}
		$res=system($command, $output);
		if($res===FALSE || $output !=0)
		{
			$_SESSION['error'] = 'There is some problem while restoring backup. Please contact to system administrator.';
			return false;	
		}
		$_SESSION['success'] = 'Backup has been restored successfully.';
		return true;		
	}
}
?>