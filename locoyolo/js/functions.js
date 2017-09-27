function finishTest(isHomework)
{
	var mod = 'test';
	if(isHomework == 1) mod = 'homework'; 
	//alert(Math.round(new Date().getTime() / 1000));
    //var x = confirm("Are you sure you want to finish this "+mod+" ?");
   /* if (x)
    {*/ 
        //return true;
		//document.getElementById('tbl_totalquestions').style.display='none';
		document.getElementById('is_finish').value=1;
		//document.getElementById('test_end_time').value = new Date().getTime();
		document.examform.submit();
   /* }
    else
    { 
		return false;
    }*/
}


function finishConfirmation()
{
    alert("You have completed all questions.\nPress the 'Previous Question' button to review all questions once again. \nPress the 'Finish Test' button to finish this test and view the results.");
}

function confirmSkip()
{    
    return;
    
    var x = confirm("Are you sure you want to skip this question and navigate to another one ?\nIf you like to select an answer, choose appropriate answer and press the button 'Select Answer'.\n\nPress 'OK' to skip this question. \n\nPress 'Cancel' to stay in this page so that you can select an answer.");
    if (x)
    { 
        return true;
    }
    else
    { 
        return false;
    }
}

function load(url) {
    location.href=url;
}

function popup(url, field_name) 
{
	if(field_name=='change password')
	{
		var width  = 750;
 		var height = 400;
	}
	else
	{
		var width  = 1000;
 		var height = 630;
	}
 var left   = (screen.width  - width)/2;
 var top    = (screen.height - height)/2;
 var params = 'width='+width+', height='+height;
 params += ', top='+top+', left='+left;
 params += ', directories=no';
 params += ', location=no';
 params += ', menubar=no';
 params += ', resizable=no';
 params += ', scrollbars=yes';
 params += ', status=no';
 params += ', toolbar=no';
 newwin=window.open(url,'windowname5', params);
 if (window.focus) {newwin.focus()}
 return false;
}

function PrintValues(){

  var myString = document.mainform.file.value
  var myStringLength = myString.length
  var Comma = myString.lastIndexOf(',')
  var SufNumChars = Comma + 1

  document.mainform.result.value=('');

  for(i=0; i<Comma; i++) 
	document.mainform.result.value+=(myString.charAt(i));

  document.mainform.result.value+=(' ');

  for(i=SufNumChars; i<myStringLength; i++) 
	document.mainform.result.value+=(myString.charAt(i));

}

function checkContactNumber(val)
{
	if (val.value.length != 0) 
	{
	  var contactformat = /^\+?([0-9]{2,4})-?([0-9]{3,5})-?([0-9]{4,8})$/;
      if (!contactformat.test(val.value))
	  {
	     alert("Please enter valid contact number. E.g. +91-151-1234567, 01234567891, +912345678901");
		 val.value="";
		 val.focus();
		 return false;
      }
	}
	return true;
}

function CheckEmailId(val) {
// Check for a properly formatted email address.
  if ((val.value.length == 0)) {
      return false;
   }

   if (val.value.length != 0) {
	   var emailformat = /^([a-z0-9._-]{1,100})+@([-a-z0-9]{3,500}\.)+([a-z]{2}|com|net|edu|org|gov|mil|int|biz|pro|info|arpa|aero|coop|name|museum|nic|in|co.in|co|inc|ac.in)$/;
      //var emailformat = /^[^@\s]+@([-a-z0-9]{3,500}\.)+([a-z]{2}|com|net|edu|org|gov|mil|int|biz|pro|info|arpa|aero|coop|name|museum|nic|in|co.in|co|inc|ac.in)$/;
	  //var emailformat = /^[^@\s]+@([-a-z0-9A-Z]+\.)+([a-z]{2}|com|net|edu|org|gov|mil|int|biz|pro|info|arpa|aero|coop|name|museum|nic|in|co.in|co|inc)$/;
  /*var emailformat = /^[^@\s]+@([-a-z0-9]+\.)+([a-z]{2}|com|net|edu|org|gov|mil|int|biz|pro|info|arpa|aero|coop|name|museum|nic|in|co.in|co|inc)$/;*/
      if (!emailformat.test(val.value))
	   {
	     alert("Please enter valid email id.");
		 val.value="";
		 val.focus();
		 return false;
      }
      else
      {
    	  //alert(val.value.indexOf('@'));
    	  var email_username = val.value.substr(0, val.value.indexOf('@'));
    	  if(email_username.length<6)
    	  {
    		//  alert("Please enter your email username atleast 6 characters long.");
    		//  val.value="";
    		//  val.focus();
    		//  return false;
    	  }
      }
   }
   return true;
}

function isChar(val,id, field_name)
{
	//alert(field_name);
	/*if(!isNaN(val))
	{
		alert('Please enter characters only.');
		document.getElementById(id).value='';
		document.getElementById(id).focus();
		return false;
	}*/
	if (val.length!=0)
    {  
		if(field_name=='rank name' || field_name=='subject name')
		{
			if(val.length>=2)
			{
			   for (i = 0; i < val.length; i++)
			   {
				   var ch = val.charAt(i);
				   //if ((ch >= "A" && ch <= "Z") || (ch >= "a" && ch <= "z") ||  (ch == ".") || (ch == "&") || (ch == " ") || (ch == "'") || (ch == "(") || (ch == ")") || (ch == '"') || (ch == "/") || (ch == "-"))
				   
				   if(field_name=='rank name')
				   {
					   if ((ch >= "A" && ch <= "Z") || (ch >= "a" && ch <= "z") || (ch == " ") || (ch == ".") || (ch == "'") || (ch == "/")) 
					   {
						  continue;
						}
					   else
					   {
						alert("Please enter only characters in "+field_name+".");
						document.getElementById(id).value='';
						document.getElementById(id).focus();
						return false;
					   }
				   }
				   else if(field_name=='subject name')
				   {
					   if ((ch >= "A" && ch <= "Z") || (ch >= "a" && ch <= "z") || (ch == " ") || (ch == "&") || (ch == ".") || (ch == "'") || (ch == "/") || (ch == "(") || (ch == ")")) 
					   {
						  continue;
						}
					   else
					   {
						alert("Please enter only characters in "+field_name+".");
						document.getElementById(id).value='';
						document.getElementById(id).focus();
						return false;
					   }
				   }
			   }
			}
			else
			{
				alert("Please enter data at least 2 characters long.");
				document.getElementById(id).value='';
				document.getElementById(id).focus();
				return false;
			}
		}
		else
		{
			if(val.length>=3)
			{
			   for (i = 0; i < val.length; i++)
			   {
				   var ch = val.charAt(i);
				   //if ((ch >= "A" && ch <= "Z") || (ch >= "a" && ch <= "z") ||  (ch == ".") || (ch == "&") || (ch == " ") || (ch == "'") || (ch == "(") || (ch == ")") || (ch == '"') || (ch == "/") || (ch == "-"))
				   
				   
					  // alert("ok");
					   if ((ch >= "A" && ch <= "Z") || (ch >= "a" && ch <= "z") || (ch == " ") || (ch == ".") || (ch == "'")) 
					   {
						  continue;
						}
					   else
					   {
						alert("Please enter only characters in "+field_name+".");
						document.getElementById(id).value='';
						document.getElementById(id).focus();
						return false;
					   }
			   }
			}
			else
			{
				alert("Please enter data at least 3 characters long.");
				document.getElementById(id).value='';
				document.getElementById(id).focus();
				return false;
			}
		}
    }

   return true;
}
function managePageCheckIsAlphaNum(val, field_name) 
{
  if (val.value.length != 0)
   {
	   for (i = 0; i < val.value.length; i++)
	   {
		   var ch = val.value.charAt(i);
		  // if ((ch >= "A" && ch <= "Z") || (ch >= "a" && ch <= "z") || (ch >= "0" && ch <= "9")  || (ch == " ") || (ch == ".") || (ch == ",") || (ch == "-") (ch == "|")||
		   if(field_name=='student number')
		   {
			   if ((ch >= "A" && ch <= "Z") || (ch >= "a" && ch <= "z") || (ch >= "0" && ch <= "9") || (ch == "-"))
			   {
				  continue;
				} 
			   else
			   {
				alert("Please enter alphanumeric value in "+field_name+".");
				val.value='';
				val.focus();
				return false;
			   }
		   }
		   else
		   {
			   if ((ch >= "A" && ch <= "Z") || (ch >= "a" && ch <= "z") || (ch >= "0" && ch <= "9")  || (ch == " ") || (ch == ".") || (ch == "&") ||(ch == "'") || (ch == "(") || (ch == ")") || (ch == "-"))
			   {
				  continue;
				} 
			   else
			   {
				alert("Please enter alphanumeric value in "+field_name+".");
				val.value='';
				val.focus();
				return false;
			   }
		   }
	  }
	  
  }

   return true;
}

function checkPassword(val)
{
	if(val.value.length!=0)
	{
		if(val.value.length<5)
		{
			alert('Please enter password at least 5 characters long.');
			val.value="";
			val.focus();
			return false;
		}
	}
	return true;
}