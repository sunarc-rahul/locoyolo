<?php
defined("ACCESS") or die("Access Restricted");

function UploadFile(&$SavePath,$FileObject,$FileType = '')
{
    $Message='';
	$Upldate=time();
	$savefile = 1;
	
    if($FileObject->name=='')
	{ 
	   return;
	}	
	
	 $FileObject->name = str_replace(" ","",$FileObject->name);
	 $pos=strpos($FileObject->name,".");
	 $SavePath=substr($FileObject->name,0,$pos);
	 
	if($FileType == 'video')
	{
		$maxsize = VIDEOSIZE;
		$extension = VIDEOEXT;
	}
	elseif($FileType == 'image')
	{
		$maxsize = IMAGESIZE;
		$extension = IMAGEEXT;
	}
	else
	{
		$maxsize = FILESIZE;
		$extension = FILEEXT;
    }	
	
	//================================ Check file size ===================
	
	   if($FileObject->size>$maxsize)
	   { 
			 $savefile = 0;
			 $Message = MAXSIZEMESSAGE;
		}
		elseif($FileObject->size <= 0)
		{
			$savefile = 0;
			$Message = MINSIZEMESSAGE;
			
		}
	//================================ Check file type ===================
	  $fileext = explode("/",$FileObject->type); // Fetch the file type
	  $extension1 = explode(",",$extension); // Change the string into array
	  $filename = $FileObject->name; // Fetch the filename of posted file
	  $pos=strrpos($FileObject->name,"."); 
	 $ext = strtolower(substr($FileObject->name,$pos+1)); // Acheive only file extionsion
	

  //##########################################################################
	 $key = array_search($ext,$extension1); // Search the received extionsion in array  
         
	   if($extension1[$key] != $ext)
		  {
			$Message = EXTMESSAGE.$extension." files.";
	        $savefile = 0;
		  } 
      		
	//================================ copy file all validations are true ===================
		if($savefile == 1)
		{
		   $SavePath.= time().".".$ext;
		   $copypath = FILEPATH.$SavePath;
		   //echo $copypath;exit;
		
			if(is_uploaded_file($FileObject->tmp_name))
		 	{
				if(!move_uploaded_file($FileObject->tmp_name, $copypath))
				{
					$Message=FAILEDCOPYMESSAGE." ".$copypath;
					$Message=FAILEDCOPYMESSAGE;
					$SavePath = '';
				}
			}
			else
			{
				$Message="File not uploaded.";
				$SavePath = '';
			}
		}
	return $Message;
	}			
 				
//========================================End=============================================================

//==================== Upload Image Files After Compression =============================================//
function UploadImageFile(&$SavePath,$FileObject,$FileType)
{ 
    $Message='';
	$Upldate=time();
	$savefile = 1;
	
    if($FileObject->name=='')
	{ 
	   return;
	}	
	
	 $FileObject->name = str_replace(" ","",$FileObject->name);
	 $pos=strpos($FileObject->name,".");
	 $SavePath=substr($FileObject->name,0,$pos);
	 
	 
	if($FileType == 'image')
	{
		$maxsize = IMAGESIZE;
		$extension = IMAGEEXT;
	}
	else
	{
		$maxsize = FILESIZE;
		$extension = FILEEXT;
    }	
	
	//================================ Check file size ===================
	   if($FileObject->size>$maxsize)
	   { 
			 $savefile = 0;
			 $Message = MAXSIZEMESSAGE;
		}
		elseif($FileObject->size <= 0)
		{
			$savefile = 0;
			$Message = MINSIZEMESSAGE;
			
		}
	//================================ Check file type ===================
	  $fileext = explode("/",$FileObject->type); // Fetch the file type
	  $extension1 = explode(",",$extension); // Change the string into array
	  $filename = $FileObject->name; // Fetch the filename of posted file
	  $pos=strrpos($FileObject->name,"."); 
	 $ext = strtolower(substr($FileObject->name,$pos+1)); // Acheive only file extionsion
	

  //##########################################################################
	 $key = array_search($ext,$extension1); // Search the received extionsion in array  
         
	   if($extension1[$key] != $ext)
		  {
			$Message = EXTMESSAGE.$extension." files.";
	        $savefile = 0;
		  } 
      		
	//================================ copy file all validations are true ===================
		if($savefile == 1)
		{
		   echo $SavePath.= time().".".$ext;
		   $copypath = FILEPATH.$SavePath;
		   //echo $copypath;exit;
		
			if(is_uploaded_file($FileObject->tmp_name))
		 	{
				//move_uploaded_file()
		 		include_once(ROOT.'/lib/image.class.php');
				$img = new thumb_image;
				$img->GenerateThumbFile($FileObject->tmp_name, $copypath);
		 		
				if(!file_exists($copypath))
				{
					$Message=FAILEDCOPYMESSAGE;
					$SavePath = '';
				}
			}
			else
			{
				$Message="File not uploaded.";
				$SavePath = '';
			}
		}
	return $Message;
}


/**
 * @Author 	: Niteen Acharya
 * @param	: string name of file
 * @return 	: --
 * @Desc 	: redirect to given file
 */
function Redirect($file)
{
 echo  '<script>';
 echo  'location.href="'.$file.'"';
 echo  '</script>';

}

function RecordPerPage($no)
{
	if ($no)
	{
		return $no;
	}	
	else
	{
		return 10;
	}	
}

//=============================================================================================
   // function pagination
//======================================================================================       
function PaginationWork($option='')
{
	global $frmdata ;
	
	if(!isset($frmdata['record'])) $frmdata['record'] = '';
	
	$recordPerPage=RecordPerPage($frmdata['record']);
  	
	//	if page number not set or search done
	if(!isset($frmdata['pageNumber']))
	{	
		$frmdata['pageNumber']=1;
	}
	if($recordPerPage!='All')
	{
		// at first page 
		if($frmdata['pageNumber']==1)
		{
			 $frmdata['from']=0;
			 $frmdata['to']=$recordPerPage;
		}
	   //for next pages
		else
		{
	       if($frmdata['pageNumber']<=0)
	       {
				$frmdata['pageNumber']=1;
				$frmdata['from']=0;
				$frmdata['to']=$recordPerPage;
	       }
	       else
	       {
				$frmdata['from']= $recordPerPage * ( ( (int) $frmdata['pageNumber']) - 1);
				$frmdata['to']=$recordPerPage;
	       }
		}	 
	}
}

/**
 * @Auther : Niteen Acharya
 * @para	: int (total number of record)
 * @return : ----
 * @Des 	: to show pagination at the bottom of the page
 */
function PaginationDisplay($totalCount,$option='')
{	
	global $frmdata;
		
	$recordPerPage = RecordPerPage($frmdata['record']);
	if($recordPerPage!='All')
	{
		if($totalCount > $recordPerPage)
		{
			echo '<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" class="fontstyle" >
			 		<tr valign="bottom" > 
					<td width="70" height="5" align="left">';
			
			// previous page link
			if($frmdata['from'] >0)
			{
				echo '<a href="javascript:PrePage();" ><img border="0" src="'.ROOTURL.'/images/previous.gif"></a>';
			}
						
			echo '</td>
					<td>&nbsp;</td>';
						
			//INDIVIDUAL PAGE LINKS.....
			$i=1;
			$j=$frmdata['pageNumber'];
			if($j>=10)
			{
				$i=$j-9;
			}
			
			if($totalCount > 2* $recordPerPage)
			{
				for(;$i<=10+$frmdata['pageNumber'] &&($totalCount >($i-1)*$recordPerPage) ;$i++)
				{
					if($i==$frmdata['pageNumber'])
					{ 
						 echo '<a onmouseover="this.style.cursor=\'hand\'" >
							<td height="20" width="23" align="center" valign="middle" onmouseover="this.style.cursor=\'hand\'" style="color:#50BFC9" class="fontstyle">';
									
						echo $i;								
						echo '</td> </a> <td  width="1" valign="middle" >|</td>';
					}
					else
					{
						echo '<td height="10" width="23"  valign="middle" align="center"class="fontstyle">';
						echo '<a href="javascript:setvalue(document.frmlist,document.frmlist.pageNumber,'.$i.');">'; 
											
						echo $i;
						echo '</a></td><td   width="1" valign="middle">|</td>';
					}
				}
			}

			$frmdata['pageNumber']=$j;
					
			echo '<td>&nbsp;</td>';
			echo '<td  width="50" height="5" align="right" valign="top">&nbsp; ';
					  
			//NEXT PAGE LINK.....
			if($totalCount > ($frmdata['from'] + $frmdata['to']))
			{
				echo '<a href="javascript:NextPage();" ><img border="0" src="'.IMAGEURL.'/next.gif"></a>';
			}
					
			echo '</td></tr></table>';
		}
	}
}

/**
 * @Auther : Niteen Acharya
 * @para	: --
 * @return : --
 * @Des 	: to remove trailing and leading spaces from posted data
 */
function RemoveSpaces()
{
	global $frmdata;
	foreach($frmdata as $key => $value)
	{
		if($key == 'question_title') continue;
		if(is_array($frmdata[$key]))
		{
			foreach($frmdata[$key] as $key2=>$value2)
			{
				if(!is_array($frmdata[$key][$key2]))
					$frmdata[$key][$key2]=trim($value2);
			}
		}
		else
		{
			$frmdata[$key]=trim($value);
		}	
	}
}

/**
function: setSearchContent($mod)
Author	: Shakti Singh Bhati
Date	: 10-02-2009
param 	:$mod  : Current Running mod
Return	:
Purpose	: Userful when user want to go back from edit page to list page with previous status of list page.
		(with page number and searched records, that they have before edit) 
		This function is used to set or unset search content of modules. 
		to set the search content for currently runnning module in the $_SESSION[] array 
		and unset the search content from the $_SESSION[] array for temporary inactive modules 
		and also store the current page number.

Description	: If any new module needs to keep history of last visited page:-
			1. Set the module name in $_SESSION['mods'] array with all search fields.
			2. Set module name only, in $_SESSION['mod'] array. 

Suggetions	: Keep the search button name =>'search', clear search button name =>'clearsearch',
			keep search field's names different from field name of add and edit form.
*/
function setSearchContent($mod,$submod,$do,&$activePage)
{
	global $frmdata,$MODDETAILSARR,$MODARR,$SUBMODARR,$SUBMODDETAILSARR,$DOARR,$DODETAILSARR;
	
	if(false!==in_array($mod,$MODARR))
	{
		$activePage=$mod;
		foreach ($MODDETAILSARR as $key => $value)
		{
			if($mod == $key) //to set search content for current module
			{
				foreach ($MODDETAILSARR[$key] as $fieldName)
				{
					if (isset($frmdata[$fieldName])) 
					{
						$_SESSION[$activePage]['search'][$fieldName]=$frmdata[$fieldName];//set the search content to the $_SESSION array.
					}
					else //when checkbox fields are unchecked they should be unset from the search session array beacause they will not automatically contain as the $frmdata does not contain the field name as a key
					{
						if (isset($frmdata['Submit']) && $mod == 'candidate_master')//unset the value only when the searchuser button is clicked
						{
							if ($fieldName == 'is_board_member_manage')
							{
							 	unset($_SESSION[$activePage]['search'][$fieldName]);
							}
						}
						elseif (isset($frmdata['Submit']) && $mod == 'examination')//unset the value only when the searchuser button is clicked
						{
							if ($fieldName == 'subjective_manage')
							{
							 	unset($_SESSION[$activePage]['search'][$fieldName]);
							}
						}
					}		
				}
			}
			else //clear search content of unclicked modules.
			{
				unset($_SESSION[$key]['search']);
				unset($_SESSION[$key]['pageNumber']);
			}
		}
	}
	elseif(false!==in_array($do,$DOARR))
	{
		$activePage=$do;
		foreach ($DODETAILSARR as $key => $value)
		{
			if($do==$key) //to set search content for current module
			{
				foreach ($DODETAILSARR[$key] as $fieldName)
				{
					if (isset($frmdata[$fieldName])) 
					{
						$_SESSION[$activePage]['search'][$fieldName]=$frmdata[$fieldName];//set the search content to the $_SESSION array.
					}
				}
			}
			else //clear search content of unclicked modules.
			{
				unset($_SESSION[$key]['search']);
				unset($_SESSION[$key]['pageNumber']);
				
			}
		}
	}
	elseif(false!==in_array($submod,$SUBMODARR))
	{
		$activePage=$submod;
		foreach ($SUBMODDETAILSARR as $key => $value)
		{
			if($submod==$key) //to set search content for current module
			{
				foreach ($SUBMODDETAILSARR[$key] as $fieldName)
				{
					if (isset($frmdata[$fieldName])) 
					{
						$_SESSION[$activePage]['search'][$fieldName]=$frmdata[$fieldName];//set the search content to the $_SESSION array.
					}	
				}
			}
			else //clear search content of unclicked modules.
			{
				unset($_SESSION[$key]['search']);
				unset($_SESSION[$key]['pageNumber']);
			}
		}
	}
	else
	{
		unset($_SESSION[$mod]['search']);
		unset($_SESSION[$mod]['pageNumber']);
		return;
	}
	
	if (isset($frmdata['record']))//set the record number per page.
	{
		$_SESSION[$activePage]['search']['record']=$frmdata['record'];
	}
	if (isset($frmdata['searchuser']) || isset($frmdata['search']) || isset($frmdata['searchentity']) || isset($frmdata['searchenttype']) || isset($frmdata['searchsalescon']))
	{
			
		$frmdata['pageNumber']=1;//set pageNumber=1 when search button is clicked.
	}
	if (isset($frmdata['pageNumber']))//if page number is set store it.
	{	
		$_SESSION[$activePage]['pageNumber']=$frmdata['pageNumber'];		
	}
	if (isset($frmdata['orderby']) && $frmdata['orderby']!='' )//set the order by if order by clicked
	{
		$_SESSION[$activePage]['search']['orderby']=$frmdata['orderby'];
	}
	if (isset($frmdata['clear_search']))//clear the search content and stored pagenumber if clearsearch button is clicked
	{
		unset($_SESSION[$activePage]['pageNumber']);
		unset($_SESSION[$activePage]['search']);
	}
}

/**
Function: setPageSettings($mod)
Author 	: Shakti Singh Bhati
Date 	: 10-02-2009
Param 	: $mod : Current Running mod
Return 	:
Purpose	: This function is used to set search content and 
			page number back to the $frmdata to get recent history of modules.
*/
function setPageSettings($mod)
{
	global $frmdata,$getVars;
	$submod='';
	$do='';
	
	if(isset($getVars['submod']))
	{
		$submod=$getVars['submod'];
	}
	
	if(isset($getVars['do']))
	{
		$do=$getVars['do'];
	}
	
	$activePage='';
	setSearchContent($mod,$submod,$do,$activePage);//to set and unset the search content and page number of modules.
	
	if (isset($_SESSION[$activePage]['pageNumber']))
	{
		$frmdata['pageNumber']=$_SESSION[$activePage]['pageNumber'];//set stored page number back to the $frmdata
	}
	//print_r($frmdata);
	if (isset($_SESSION[$activePage]['search']))
	{
		foreach ($_SESSION[$activePage]['search'] as $key => $value)//set search content back to the $frmdata
		{
			$frmdata[$key]=$value;	
		}
	}	
}


/**
function: getPageRecords()
paran 	:
Return 	: Combo box of  Page Record
Purpose	: to add feature, Number of row per page 
Date	: 17-02-2009
Author 	: Shakti Singh Bhati
*/
function getPageRecords()
{
	global $frmdata;
	$output='Show:';
	$output.='<select name="record" id="record" onchange="return FormRecord()" class="rounded">';
	$output.='<option value="10"';if(!isset($frmdata['record']) || $frmdata['record']=="10")$output.='selected';$output.='>10</option>';
	$output.= '<option value="20"';if($frmdata['record']=='20')$output.='selected'; $output.='>20</option>';
	$output.= '<option value="50"';if($frmdata['record']=='50')$output.=' selected ';$output.='>50</option>';
	$output.= '<option value="100"';if($frmdata['record']=='100')$output.=' selected ';$output.='>100</option>';
	$output.= '<option value="All"';if($frmdata['record']=='All')$output.=' selected ';$output.='>All</option>';
	$output.= '</select>';
	return $output;
}

/**
 * @author	: Ashwini Agarwal
 * @date 	: March 05, 2012
 * @desc 	: To highlight searched string.
 */
function highlightSubString($sub_str, $str)
{
	$result = '';
	$len = strlen($sub_str);
	if($len > 0)
	{
		$found = stripos($str, $sub_str);
		$last = 0;
		
		while($found !== false)
		{
			$result .= substr($str, $last, ($found - $last));
			$result .= "<label class = 'highlightSubString'>";
			$result .= substr($str, $found, $len);
			$result .= "</label>";
			
			$last = $found + $len;
			$found = stripos($str, $sub_str, $found + 1);
		}
		$result .= substr($str, $last);
	}
	else
	{
		$result = $str;
	}
	return ($result);
}


