<?php 
/**
 * Created by Niteen Acharya
 * to parse URLS
 */
function ParsingURL()
{
	global $getVars;
	$urlArr=@parse_url($_SERVER['REQUEST_URI']);
 	@parse_str( Decode($urlArr['query']),$getVars);
 	@parse_str( $urlArr['query'],$getVars);
}

/**
 * Created by Niteen Acharya
 * to send post data
 */
function ChangeData()
{
	global $_POST;
	return  $_POST;
}

function Encode($value)
{
	return base64_encode($value);
}

function Encrypt($value)
{
	return crypt($value, 'seigolonhcet#CRANUS321$');
}

function Decode($value)
{
	return base64_decode($value);
}

/**
 * @Auther 	: Niteen Acharya
 * @Des 	: to make a link
 */
function CreateURL($url,$querystring='',$encode=false,$redirect=false)
{
	$url = ROOTURL.'/'.$url;
	if($querystring)
	{
		if($encode)
		{
			return $url.'?'.Encode($querystring);		
		}
		else
		{
			return $url.'?'.$querystring;		
		}
	}
	else
	{
		return $url;
	}
	return false;
}
?>