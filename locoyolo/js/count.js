/*
Author: Robert Hashemian
http://www.hashemian.com/

You can use this code in any manner so long as the author's
name, Web address and this disclaimer is kept intact.
********************************************************
Usage Sample:

<script language="JavaScript">
TargetDate = "12/31/2020 5:00 AM";
BackColor = "palegreen";
ForeColor = "navy";
CountActive = true;
CountStepper = -1;
LeadingZero = true;
DisplayFormat = "%%D%% Days, %%H%% Hours, %%M%% Minutes, %%S%% Seconds.";
FinishMessage = "It is finally here!";
</script>
<script language="JavaScript" src="http://scripts.hashemian.com/js/countdown.js"></script>
*/

BackColor = "";
ForeColor = "black";
CountActive = true;
CountStepper = -1;
LeadingZero = true;
DisplayFormat = "%%H%% : %%M%% : %%S%% ";
FinishMessage = "It is finally here!";

var scripts = document.getElementsByTagName('script');
var index = scripts.length - 1;
var myScript = scripts[index];
var queryString = myScript.src.replace(/^[^\?]+\??/,'');
query=queryString.split("=");
var page_mode=query[1];

function calcage(secs, num1, num2) {
  s = ((Math.floor(secs/num1))%num2).toString();
  if (LeadingZero && s.length < 2)
    s = "0" + s;
  return "<b>" + s + "</b>";
}

function CountBack(secs) 
{
  if (secs < 0) 
  {
	  if(page_mode=="result")
	  {
		  document.getElementById('result_timediv').innerHTML="";
		  //document.getElementById('result_timediv').style.display="none";
		  document.getElementById('less').style.display="";
	  }
	  else
	  {
		var x = alert("Time is up. Press OK to finish test.");
		document.getElementById("show_qus").style.display = 'none';
			document.getElementById("profileDiv").style.display = 'none';
			document.getElementById("instructionsDiv").style.display = 'none';
			document.getElementById("QPDiv").style.display = 'none';
			document.getElementById("conformsubmitDiv").style.display = 'none';
			document.getElementById("sectionSummaryDiv").style.display = 'block';
			document.getElementById("ques-nav").style.display = 'none';
			document.getElementById("mainright").style.display = 'block';
			document.getElementById("confirmation_buttons1").style.display = 'none';
			document.getElementById("fieldset_sec").style.display = 'none';
			document.getElementById("confirmation_buttons2").style.display = 'block';
			document.getElementById("mainright").style.height = '577px';
			document.getElementById("sectionSummaryDiv").style.height = '572px';
	   //	document.getElementById('is_finish').value=1;
		//document.examform.submit();
	  }
  }
  else
  {
	  DisplayStr = DisplayFormat.replace(/%%D%%/g, calcage(secs,86400,100000));
	  DisplayStr = DisplayFormat.replace(/%%H%%/g, calcage(secs,3600,24));
	  DisplayStr = DisplayStr.replace(/%%M%%/g, calcage(secs,60,60));
	  DisplayStr = DisplayStr.replace(/%%S%%/g, calcage(secs,1,60));

	ahow_alert(secs);
	  if((secs < 301) && (secs > 285))
	  { 
		  if((secs != 299) && (secs != 297) && (secs != 295))
		  {
			  document.getElementById('low-time-note').innerHTML = 'You have 5 minutes left. It\'s time to review questions.';
		  }
		  else
		  {
			  document.getElementById('low-time-note').innerHTML = '';
		  }
	  }
	  else
	  {
		  document.getElementById('low-time-note').innerHTML = '';
	  }
	  
	  document.getElementById("cntdwn").innerHTML = DisplayStr;
	  if (CountActive)
	  {
			  setTimeout("CountBack(" + (secs+CountStepper) + ")", SetTimeOutPeriod);
	  }
  }
}

function putspan(backcolor, forecolor) {
 document.write("<span id='cntdwn' style='background-color:" + backcolor + 
                "; color:" + forecolor + "'></span>");
}

if (typeof(BackColor)=="undefined")
  BackColor = "white";
if (typeof(ForeColor)=="undefined")
  ForeColor= "black";
if (typeof(TargetDate)=="undefined")
  TargetDate = "12/31/2020 5:00 AM";
if (typeof(DisplayFormat)=="undefined")
  DisplayFormat = "%%D%% Days, %%H%% Hours, %%M%% Minutes, %%S%% Seconds.";
if (typeof(CountActive)=="undefined")
  CountActive = true;
if (typeof(FinishMessage)=="undefined")
  FinishMessage = "";
if (typeof(CountStepper)!="number")
  CountStepper = -1;
if (typeof(LeadingZero)=="undefined")
  LeadingZero = true;


CountStepper = Math.ceil(CountStepper);
if (CountStepper == 0)
  CountActive = false;
var SetTimeOutPeriod = (Math.abs(CountStepper)-1)*1000 + 990;
putspan(BackColor, ForeColor);
var dthen = new Date(TargetDate);
var dnow = new Date(CurrentDate);
if(CountStepper>0)
  ddiff = new Date(dnow-dthen);
else
  ddiff = new Date(dthen-dnow);
gsecs = Math.floor(ddiff.valueOf()/1000);
CountBack(gsecs);