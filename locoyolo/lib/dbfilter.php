<?php
/*=======================================================================
	@Auther 	Niteen Acharya
	@Date   	18/03/2007
	@Company	Sunarc Technologies
//======================================================================= */
defined("ACCESS") or die("Access Restricted");
include_once("dbclass.php");

class DBFilter extends DBConnect
{
	
	/* This is a constructor which can be used to initialize variables.*/
	function DBFilter()
	{

	}


	/* This is a function to start the transaction */
	function TransBegin()
	{
		$this->StartTransaction();
	}
	
	/* This is a function to End the transaction */
	function TransEnd()
	{
		$this->EndTransaction();
	}
	/***************************************************************************************************
		@para
		@$table : String( Name of table)
		@$data  : Array of data that needed tobe enter in database
		@Description : this function is used to take data as formof array and gerates a insert query.
	***************************************************************************************************/
	function InsertRecord($table,$data)
	{
		//added by niteen Achrya to insert date in contacts table
		//this is change comes after system has been developed
		if($table=='contacts')
		{
			if($data && is_array($data))
				$data['insdate']=date('Y-m-d');
		}	
			
		if($table=='')
		{
			return false;
		}	
		else
		{
			if($data=='')
			{
				return false;
			}
			else
			{
				
				$resource=$this->ExecuteQuery("SHOW COLUMNS FROM ".PREFIX.$table);
				
				if($resource)
				{
					$fields="";
					$values="";
					
					while($row = $this->FetchArray($resource))
					{
					
						if(array_key_exists($row['Field'],$data))
						{
							 $fields.=$row['Field'].",";
							if(is_array($data[$row['Field']]))
							{
							   // $data[$row['Field']]=array_values(array_filter($data[$row['Field']]));
								//print_r($data[$row['Field']]);
								$data[$row['Field']]=implode(",",$data[$row['Field']]);
							}	
							
							if($data[$row['Field']]=='NULL')
							{
								$values.="NULL,";
							}
							else
							{
								$values.="'".addslashes($data[$row['Field']])."',";
							}	
						}
					}
					
					if($fields)
					{
						$fields=substr($fields,0,strlen($fields)-1);
						$values=substr($values,0,strlen($values)-1);					
					  	$query="Insert into ".PREFIX.$table."(".$fields.") values(".$values.")";
						$id=$this->ExecuteQuery($query);
						//echo $query;exit;
						return $id;
/*						if($DB->ExecuteQuery($query))
						{
							return $DB->InsertId();
						}
						return false;
*/
					}
					
				}
				
			}
		}
		return false;			
	}

	/*
		@para
		@$table : String( Name of table)
		@$data  : Array of data that needed tobe enter in database
		@$cond  : it will be an array. 'or' or 'and' will also be part of array as
			  element like array{ 'name'=> Niteen, 'or' => 'or', 'country' => 'India'  }
		@Description : this function is used to take data as formof array and gerates a update query.

	*/

	function UpdateRecord($table,$data,$cond='')
	{

		//echo "ok";exit;
		if($table=='')
		{  
			return false;
		}	
		else
		{
			if($data=='')
			{   
				return false;
			}
			else	
			{
				
				$resource= $this->ExecuteQuery("SHOW COLUMNS FROM ".PREFIX.$table);
				
				if($resource)
				{
					$sql="";
							
					while($row = $this->FetchArray($resource))
					{
						if(array_key_exists($row['Field'],$data))
						{
						     if(is_array($data[$row['Field']]))
							 {
							     $data[$row['Field']]=array_values(array_filter($data[$row['Field']]));
								 $data[$row['Field']]=implode(",",$data[$row['Field']]);
							  } 	
							if($data[$row['Field']]==='NULL')
							{
									
									$sql.=$row['Field']."=NULL,";
							}
							else
							{
								 $sql.=$row['Field']."='".addslashes($data[$row['Field']])."',";
							} 
						}
					}
					 
					if($sql)
					{
						$sql=substr($sql,0,strlen($sql)-1);
											
						$query="update ".PREFIX.$table." set ".$sql;
						if($cond)
						{
							if(!is_array($cond))
							{
								$query.=" where ".$cond;
							}
							else
							{
									$count=count($cond);
									if($count)
									{
										$fields=@array_keys($cond);
										$query.=" where ";
										for($counter=0;$counter<$count;$counter++)
										{
											
											if($fields[$counter]=='or' || $fields[$counter]=='and')
											{
													 $query.=" ".$fields[$counter]." "; 
											}
											else
											{
												
													 $query.=" ".$fields[$counter]."='".$cond[$fields[$counter]]."'"; 
											}		
										}
									}
								}		
						}

             //echo $query.'<br/>';exit;
						if($this->ExecuteQuery($query))
						{
							return true;
						}
						return false;
					}
					
				}
				
			}
		}
		return false;			
	}	


	/*
		@para
		@$table : String( Name of table)
		
		@$cond  : condtion to delte reocrd
		@Description : this function is used to delte record
	
	*/
	function DeleteRecord($table,$cond='')
	{
 
	 	if($table)
		{
			$query="delete from ".PREFIX.$table;
			if($cond)
			{
				 $query.=" where ".$cond;
			}
			if($this->ExecuteQuery($query))
			{
				return true;
			}
		}
		return false;
	}



	/*
		@para
		@$table : String( Name of table)
		@$cond  : condtion to select recorde reocrd
		@$fields : name of fields data are needed from query
		@backqury : It wil be include in end of query like order by statement
	
		@Description : this function is used to select multiple records
	
	*/
	function SelectRecords($table,$cond='',$fields='*',$backquery='')
	{ 
		global $frmdata;
		if($table)
		{
			if($fields)
			{		
				if(is_array($fields))
				{
					$fields=implode(",",$fields);
				}
				
				$query="select ".$fields." from ".PREFIX.$table." ";
				
				if($cond)
				{
					 $query.="where ".$cond." ";
				}
				
			 		
				if($backquery)
				{
					    $query.=$backquery;
				}
				//echo $query;//exit;
				if($result= $this->ExecuteQuery($query))
				{
					if($this->NumRows($result) > 0)
					{
						$arr=array();
				  		while($row=$this->FetchObject($result)) 
				  		{	
				         $arr[]=$row;
					    }
						return $arr;
					}
					else
					{
						return 0;
					}		
				 	 
				}
				
			}
		}
		return false;
	}
function SelectdistinctRecords($table,$cond='',$fields='*',$backquery='')
	{
		global $frmdata;
		if($table)
		{
			if($fields)
			{		
				if(is_array($fields))
				{
					$fields=implode(",",$fields);
				}
				
				 $query="select distinct(".$fields.") from ".PREFIX.$table." ";
				
				if($cond)
				{
					 $query.="where ".$cond." ";
				}
				
			 		
				if($backquery)
				{
					    $query.=$backquery;
				}
		//echo  $query;
				if($result= $this->ExecuteQuery($query))
				{
					if($this->NumRows($result) > 0)
					{
						$arr=array();
				  		while($row=$this->FetchObject($result)) 
				  		{	
				         $arr[]=$row;
					    }
						return $arr;
					}
					else
					{
						return 0;
					}		
				 	 
				}
				
			}
		}
		return false;
	}

	/*
		@para
		@$table : String( Name of table)
		@$cond  : condtion to select recorde reocrd
		@$fields : name of fields data are needed from query
		@backqury : It wil be include in end of query like order by statement
		@totalcount : to count of records
		
		@Description : this function is used to select multiple records with pagination work
					
	
	*/
	function SelectRecordsWithPagination($table,$cond='',$fields='*',$backquery='',&$totalCount)
	{
		global $frmdata;
		$recordPerPage=RecordPerPage($frmdata['record']);
		
		if($table)
		{
			if($fields)
			{		
					if(is_array($fields))
					{
						$fields=implode(",",$fields);
					}
					
					$query="select ".$fields." from ".PREFIX.$table." ";
					
					if($cond)
					{
						 $query.="where ".$cond." ";
					}
					
					if($backquery)
					{
							$query.=$backquery;
					}
					
					if($result=$this->ExecuteQuery($query))
					{
						$totalCount=$this->NumRows($result);
						
						//=================================================================
						if($frmdata['from'] >= $totalCount)
						{
							$formCNT=((int) ($frmdata['from']/ $recordPerPage)) - ((int) ($totalCount/ $recordPerPage));
							
							if($frmdata['pageNumber']-($formCNT+1)<=0)
							{
								$frmdata['pageNumber']=1;
								$frmdata['from']=0;
							}
							else
							{
								$frmdata['pageNumber']=$frmdata['pageNumber']-($formCNT+1);
								$frmdata['from']=$frmdata['from']-(($formCNT+1)*$recordPerPage);
							}
								
						}
						
						//===================================================================
						
						if( $totalCount > 0)
						{
							
							if(!isset($frmdata['record']) || (isset($frmdata['record']) && $frmdata['record']!='All') )
							{
								if($frmdata['from'] && $frmdata['from']<$totalCount)
									mysqli_data_seek($result,$frmdata['from']);
							}		
							
							$countRows=1;
							
							$arr=array();
							while($row=$this->FetchObject($result)) 
							{	
								if(!isset($frmdata['record']) || (isset($frmdata['record']) && $frmdata['record']!='All') )
								{
									if($countRows <= $frmdata['to'])
										$arr[]=$row;
								}
								else
								{
									$arr[]=$row;
								}		
								$countRows++;
								
							}
							return $arr;
						}		
						else
						{
							return 0;
						} 
						 
					}
					
			}
		}
		return false;
	}
	/*
		@para
		@$table : String( Name of table)
		@$cond  : condtion to select recorde reocrd
		@$fields : name of fields data are needed from query
		
		
		@Description : this function is used to select single record
	
	*/
	function SelectRecord($table,$cond='',$fields='*')
	{
		
		if($table)
		{
			if($fields)
			{		
					if(is_array($fields))
					{
						 $fields=implode(",",$fields);
					}
					
					 $query="select ".$fields." from ".PREFIX.$table." ";
					
					if($cond)
					{
						 $query.="where ".$cond;
					}
					//echo $query;
					//exit;
					if($result=$this->ExecuteQuery($query,$table))
					{
						if($this->NumRows($result) > 0)
						{
							
							$row=$this->FetchObject($result); 
							
							return $row;
						}
						else
						{
							return 0;
						}		
						 
					}
					
			}
		}
		return false;
	}
	
	/*
		@para
		qury : query statement
		
		@Description : this function is used to run join or other complex quries
	
	*/
	function RunSelectQuery($query)
	{


		if($result=$this->ExecuteQuery($query))
		{
			
			if($this->NumRows($result) > 0)
			{
			 $this->NumRows($result);
			
				//if(mysql_num_rows($result) > 1)
				//{
				
					$arr=array();
					while($row=$this->FetchObject($result)) 
					{	
						
						$arr[]=$row;
						
					}
					
				//}
				//else
				//{	
					//$row=mysql_fetch_object($result);
					//$arr=$row;
				/*}*/
				
				return $arr;
			}
			else
			{
				return 0;
			}			
						 
		}
		return false;
	}
	
	
	
	 
	
	function RunSelectQueryWithPagination($query,&$totalCount)
	{
		
	echo $query;exit; 
		global $frmdata;
		$recordPerPage=RecordPerPage($frmdata['record']);
		
		
		if($result=$this->ExecuteQuery($query))
		{
			
			if($this->NumRows($result) > 0)
			{
				//if(mysql_num_rows($result) > 1)
				//{
					$totalCount=$this->NumRows($result);
					
					//echo (int) ($totalCount/ $recordPerPage);
					//=================================================================
						if($frmdata['from'] >= $totalCount)
						{
							$formCNT=((int) ($frmdata['from']/ $recordPerPage)) - ((int) ($totalCount/ $recordPerPage));
							
							if($frmdata['pageNumber']-($formCNT+1)<=0)
							{
								$frmdata['pageNumber']=1;
								$frmdata['from']=0;
							}
							else
							{
								$frmdata['pageNumber']=$frmdata['pageNumber']-($formCNT+1);
								$frmdata['from']=$frmdata['from']-(($formCNT+1)*$recordPerPage);
							}
								
						}
						
						//===================================================================
					if(!isset($frmdata['record']) || (isset($frmdata['record']) && $frmdata['record']!='All') )
					{
						if($frmdata['from'] && $frmdata['from']<$totalCount)
								mysqli_data_seek($result,$frmdata['from']);
					}			
							
					$countRows=1;
					
					$arr=array();
					while($row=$this->FetchObject($result)) 
					{	
						if(!isset($frmdata['record']) || (isset($frmdata['record']) && $frmdata['record']!='All') )
						{
							if($countRows <= $frmdata['to'])
								$arr[]=$row;
						}
						else
						{
							$arr[]=$row;
						}		
						$countRows++;
					}
				//}
				//else
				//{	
					//$row=mysql_fetch_object($result);
					//$arr=$row;
				/*}*/
				return $arr;
			}
			else
			{
				return 0;
			}			
						 
		}
		return false;
	}
	function RunQuery($query)
	{
		
		
		
		if($result=$this->ExecuteQuery($query))
		{
			
				return true;		
		}
		return false;
	} 
	/*
		@para
		qury : query statement
		
		@Description : this function is used to select multiple records .will return only single record
	
	*/
	function RunSelectQuerySigle($query)
	{
		
		
		
		if($result=$this->ExecuteQuery($query))
		{
			$row=$this->FetchObject($result);
			if($row)
				return $row;		
		}
		return false;
	} 
	
	
	/*
		@para
		@$table : String( Name of table)
		@$value  : single value
		@$cond  : condition for updation
		@Description : this function is used to take data as formof array and gerates a update status query.
	
	*/
	function UpdateStatus($table,$value,$cond,$field='statusval')
	{
		
		if($table=='')
		{
			return false;
		}	
		else
		{
			if($value=='')
			{
				return false;
			}
			else
			{
					$query="update ".PREFIX.$table." set ".$field."='".$value."'";
					if($cond)
					{
						 $query.=" where ".$cond;
					}
					//echo $query;
					if($this->ExecuteQuery($query))
					{
						return true;
					}
			}
		}
		return false;	
		
	}
	
	function CountNumRows($table,$cond='',$fields='*',$backquery='')
	{
		global $frmdata;
	
		if($table)
		{
			if($fields)
			{		
				if(is_array($fields))
				{
					$fields=implode(",",$fields);
				}
				
				$query="select ".$fields." from ".PREFIX.$table." ";
				
				if($cond)
				{
					 $query.="where ".$cond." ";
				}
				
				if($backquery)
				{
					    $query.=$backquery;
				}
				//echo $query;
				if($result= $this->ExecuteQuery($query))
				{
					if($this->NumRows($result) > 0)
					{
						return $this->NumRows($result);
					}		
				 	else
					{
						return 0;
					}
				}
				
			}
		}
		
	}
	
}

?>