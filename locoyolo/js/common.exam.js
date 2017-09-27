function newpage(path)
{
	document.examform.action = path;
	document.examform.submit();
}
function newpages(path, val)
{
	if(val=='ok')
	{
	resetOption($('form[name=examform]'));
	}
	document.examform.action = path;
	
	document.examform.submit();
}

function blockduplicate(sbox)
{
	var match = $('.match');

	if(sbox.value == '')
	{
		return true;
	}
	for(var count=0; count < match.length; count++)
	{
		if(sbox.id == match[count].id)
		{
			continue;
		}
		if(sbox.value == match[count].value)
		{
			alert("This value is already matched.\nPlease select a different one.");
			sbox.value = '';
			return false;
		}
	}
	return true;
}

$(document).ready(function()
{
	$('#ques-nav').scrollTop($('.current-que-link').offset().top-500);
	SyntaxHighlighter.defaults.toolbar = false;
	SyntaxHighlighter.all();
	
	if($("#hints"))
	{
		$("#hints").dialog({ autoOpen: false });
		$('#opener').click(function() {
			$("#hints").dialog('open')
			return false;
		});
	}
	
	if($("#video_preview"))
	{
		$("#video_preview").dialog({ autoOpen: false,width: 'auto' });
		$('#show_video').click(function() {
			$("#video_preview").dialog('open')
			return false;
		});
	}
});

function clickIE() 
{
	if (document.all) 
	{
		return false;
	}
}

function clickNS(e) 
{
	if(document.layers||(document.getElementById&&!document.all)) 
	{
		if (e.which==2||e.which==3) 
		{
			return false;
		}
	}
}
if (document.layers)
{
	document.captureEvents(Event.MOUSEDOWN);
	document.onmousedown=clickNS;
}
else
{
	document.onmouseup=clickNS;
	document.oncontextmenu=clickIE;
}

document.oncontextmenu=new Function("return false");

$('html').bind('keypress', function(e)
{
	// for disable print (CTRL+p or CTRL+P)
	if(e.ctrlKey && (e.which == 112 || e.which == 80))
	{
		//alert("You can't use print command.");
		return false;
	}
	
	// for disable copy (CTRL+c or CTRL+C)
	if(e.ctrlKey && (e.which == 99 || e.which == 67))
	{
		//alert("You can't use copy command.");
		return false;
	}
});