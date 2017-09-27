<?php 
/**
 * Date         :- 17 June,2011
 * Module       :- index.php
 * Purpose      :- It works as a entry file for other files
 */

$RootFolder = basename(__DIR__);

$AdminFolder = '';

@define("ROOT",$_SERVER['DOCUMENT_ROOT']."/$RootFolder");
@define("ROOTPATH",ROOT);
@define("ROOTURL","http://".$_SERVER['HTTP_HOST']."/$RootFolder");

@define("ADMINROOT",ROOT."/".$AdminFolder);
@define("ADMINURL",ROOTURL."/".$AdminFolder);
@define("ADMINEMAIL",'rahul.gahlot@sunarctechnologies.com');
@define("ADMINENAME",'Locoyolo Admin');

@define("INC",ROOT."/includes");
@define("STYLE",ROOTURL."/css");
@define("JS",ROOTURL."/js");

@define("MOD",ROOT."/modules");
@define("TEMP",ROOT."/templates/default");
define("CLS",ROOT."/classes");
@define("CURRENTTEMP",ROOT."/templates/default");
@define('ACCESS',1);

@define('BENCHMARKING','');
@define("TEMPURL",ROOTURL."/templates/default");
@define("TEMPPATH",ROOTPATH."/templates/default");
define("INCURL",ROOTURL."/includes");


define("TWLIO_SID",'ACbd1be4877b8ef44951b37e609fc6dff1');
define("TWLIO_TOKEN",'0ec5e4f67ee86d48278d1c38cebddef4');
define("TWLIO_FROM_NUMBER",'+19597775126');

define("IMAGESIZE",2000000);
define("IMAGEEXT","jpg,gif,png,jpeg,pjpeg");
define("VIDEOSIZE",20000000);
define("VIDEOEXT","flv,mp4,avi,wav,mpeg,mpeg4,mov,vmw,mpg,vid,m4v");
define("FILESIZE",2097152);
define("FILEEXT","pdf,doc,txt,xls,ppt,htm,html,msg,zip,rar,docx,csv");
define("FILEPATH",ROOT."/panel/uploadfiles/");

define("MAXSIZEMESSAGE","File is too big in size");
define("MINSIZEMESSAGE","File did not contain any data.");
define("FAILEDCOPYMESSAGE","File can not be copied");

@define('IMAGEHEIGHT',400);
@define('IMAGEWIDTH',400);


@define("HELPURL",ROOT."/help");
@define("IMAGEURL",ROOTURL."/images");

@define("ERRCOL","red");
define("MANMES","<span><strong>Note</strong> : Fields marked by (<font color='#FF0000'><strong>*</strong></font>) are mandatory.</span>"); // for mandatory notes
define("MANMARK","<font color=red>*</font>");

define("CHROOTDIR","/var/chroot/home");

define("LIB", ROOT . "/lib/api/");
define("LIBPAYTM", ROOT . "/lib/paytm/");
define('FB_APP_ID', '1536785746577857');
define('FB_APP_SECRET', '8b465fc23f8254a8a4db6a43ecffed14');
define('FB_REDIRECT_URL', 'http://assessall.com/assessall/index.php?mod=dashboard&do=showinfo&fblogin=1');

define('GOOGLE_CLIENT_ID', '156716040951-nqbkafcjkve6eukh5u26ptiihh933m29.apps.googleusercontent.com');
define('GOOGLE_CLIENT_SECRET', 'c4BTXlQo4N0lNVfqX8NnMebY');
define('GOOGLE_REDIRECT_URL', 'http://assessall.com/assessall/index.php?mod=dashboard&do=showinfo&googlelogin=1');

@define('PREFIX','');
@define("HOST","localhost");
@define("USER","root");
@define("PASSWORD",'');
@define("DATABASE","locoyolo");
@define("EMODE","CLIENT");


?>