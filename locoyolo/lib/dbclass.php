<?php
//defined("ACCESS") or die("Access Restricted");
//db_class.php
/*=========================================================================================
//=========================================================================================
	@AUTHOR AR
	Date     	-	3-feb-2007
	SunArc Tech. Pvt. Ltd.
	File des.	-	=======================================================================
					This file is used to handle all DATABASE related operations. The file 
	contains the class [DBConnect] which has methods to handle different data base operations.
//========================================================================================*/
//=========================================================================================
class DBConnect 
{
			/******Parameters******/
			var $host;
			var $user;
			var $password='';
			var $database;
			var $status;
			/*database to be connected*/
			var $Result='';
			var $mySqlResult="";
			var $ErrorMessage;
			
			/* Variables used to Check the status for transaction */
			var $TransactionStatus="off";
			
			var $query;
		/****************************************************************************
		-----------------------------------------------------------------------------
							Constructs a new database connection
		-----------------------------------------------------------------------------
		****************************************************************************/
		function DBConnect()
		{
				$this->host = HOST;
				$this->user = USER;
				$this->password = PASSWORD;
				$this->database = DATABASE;
				$this->status=false;
		}
		
		// To Open the connection 
		function OpenConnection()
		{
			if($this->status==false)
			{
				 $this->host = HOST;
				 $this->user = USER;
				 $this->password = PASSWORD;
				$this->database = DATABASE;
				$this->conn=mysqli_connect($this->host,$this->user,$this->password) or $this->ErrorHandler();
		       
				/*********Actives database for current use*****************/
				mysqli_select_db($this->conn,$this->database) or	$this->ErrorHandler();
				$this->status=true;
					
			}
			
		}
		
		/****************************************************************************
						close the connection
						  * @returns void
		****************************************************************************/
		function Close() 
		{ 
			@mysqli_close($this->conn) or $this->ErrorHandler();
		}
		/****************************************************************************
				   All in one function.
			( This function executes every kind of query including Transaction )
				* @param $query string.  An SQL statement
				* @returns mixed[]
		****************************************************************************/
		function ExecuteQuery($query) 
		{
		 //echo $query;

			if($this->status==false)
			{
				$this->OpenConnection();
				$this->status=true;
			}
			
			
			 $this->Result = mysqli_query($this->conn,$query) or $this->ErrorHandler(); 
			 //print_r($this->Result);
			  $RType=gettype($this->Result);
			
			if($this->Result)
			{
			
				 if($RType=='object')
				 {
						return $this->Result;
				 }
				 elseif($RType=='boolean')
				 {
						
						$Id=@mysqli_insert_id($this->conn);
						if($Id)
						{
							
							return $Id;
							
						}
						else
						{
							
							return $this->Result;
						}
				 }
			 }
			 else
			 {
			 //echo "Here </br>";echo $query;
			// echo mysqli_error($this->conn);
				if($this->TransactionStatus=='on')
				{
					$this->Result=@mysqli_query('ROLLBACK;',$this->conn) or $this->ErrorHandler();
					$this->TransactionStatus='off';
					return false;
				}
				
			
			 }
			 return false;
		}
		
		/********************************************************************************
			This function starts the transaction and set the transaction status on
		********************************************************************************/
		
		function StartTransaction()
		{
			if($this->status==false)
			{
				$this->OpenConnection();
				$this->status=true;
			}
			$this->Result=@mysqli_query('BEGIN;',$this->conn) or $this->ErrorHandler();
			$this->TransactionStatus='on';
					
		}
		
		/********************************************************************************
			This function ends the transaction with commit.
		********************************************************************************/
		function EndTransaction()
		{
				if($this->status==false)
			{
				$this->OpenConnection();
				$this->status=true;
			}
			if($this->TransactionStatus=='on')
			{
					$this->Result = @mysqli_query('COMMIT;',$this->conn) or $this->ErrorHandler();
					$this->TransactionStatus='off';
			}
			
		}
		
		/********************************************************************************
			This function is used to fetch array of a resource.
			@param : resource is handled here by ref.
			return : an array.
		********************************************************************************/
		
		
		function FetchArray(&$resource)
		{
			return @mysqli_fetch_array($resource);
		}
		
		/********************************************************************************
			This function is used to fetch row of a resource and moves the pointer ahead.
			@param : resource is handled here by ref.
			return : row of the current pointer.
		********************************************************************************/
		
		function FetchRow(&$resource)
		{
			return @mysqli_fetch_row($resource);
		}
		
		/********************************************************************************
			This function is used to fetch object of a resource.
			@param : resource is handled here by ref.
			return : object of the resource.
		********************************************************************************/
		function FetchObject(&$resource)
		{
			return @mysqli_fetch_object($resource);
		}
		
		/********************************************************************************
			This function is used to return no. of rows in a resource
			@param : resource is handled here by ref.
			return : no. of rows in a resource.
		********************************************************************************/
		function NumRows(&$resource)
		{
			if($resource)
			{
				
				return @mysqli_num_rows($resource); 
			}
			else
			{
				return 0;
			}
		}
		
		/********************************************************************************
			This function is used to returns result of a resource.
			@param : resource is handled here by ref.
			return : result of a resource
		********************************************************************************/
		
		function ResultValue(&$resource)
		{
			return @mysqli_result($this->mySqlResult );
		}
		
		/********************************************************************************************************
			This is a function which is used to handle the errors 
			There are different kind of modes
			CLIENT: This mode will show some client friendly message when an database error will occure.
			DEBUG: This mode will show the error no. and error message to the developer.
		**********************************************************************************************************/
		function ErrorHandler()
		{
			if(EMODE=='CLIENT')
				echo '<table><tr><td><b> There is some problem while executing query. </td></tr></table>';
			elseif(EMODE=='DEBUG')
				echo "<table><tr><td><b>Error NO : </b>".mysqli_errno($this->conn)."</td></tr><tr><td><b> Error Message : </b>".mysqli_error($this->conn)."</td></tr></table>"; 
				
			if($this->TransactionStatus=='on')
			{
				$this->Result=@mysqli_query('ROLLBACK;',$this->conn) or $this->ErrorHandler();
				$this->TransactionStatus='off';
			}
		//	exit;
		
		}
		
	  	/****************************************************************************
			Performs action on the database according to the given SQL Statement.  Returns the number of rows Affected.
			* @param $Query string.  An SQL statement
			
		****************************************************************************/
		function AffectedRows($line='affected_row',$module='db_class.php')
		{
			$Rows=@mysqli_affected_rows($this->conn) or $this->ErrorHandler();
			return $Rows;
		}


}
?>