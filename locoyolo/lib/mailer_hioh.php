<?php 
/*=======================================================================
	@Auther 	Ashwini Agarwal
	@Date   	November 2, 2012
//======================================================================= */
defined("ACCESS") or die("Access Restricted");
require_once(ADMINROOT."/lib/phpmailer/phpmailer.inc.php");

class mailer
{
	private $mail;
	private $from;
	private $replyTo;
	
	function __construct()
	{
		$this->mail = new PHPMailer();
		$this->mail->IsHTML(true);
		$this->from = array('address'=>'root@localhost.localdomain', 'name'=>'EMath360');
		$this->replyTo = array('address'=>'root@localhost.localdomain', 'name'=>'EMath360');
	}
	
	function addTo($address, $name = '')
	{
		$this->mail->AddAddress($address, $name);
	}
	
	function setFrom($address, $name = '')
	{
		$this->mail->From = $address;
		$this->mail->FromName = $name;
		$this->from = false;
	}
	
	function setReplyTo($address, $name = '')
	{
		$mail->AddReplyTo($address, $name);
		$this->replyTo = false;
	}
	
	function setSubject($subject)
	{
		$this->mail->Subject = $subject;
	}
	
	function setMessage($message)
	{
		$this->mail->Body = $message;
	}
	
	function addCc($address, $name = '')
	{
		$this->mail->AddCC($address, $name);
	}
	
	function addCcArray($array)
	{
		foreach($array as $arr)
		{
			if(is_array($arr))
				$this->addCc($arr['address'], $arr['name']);
			else
				$this->addCc($arr);
		}
	}
	
	function addBcc($address, $name = '')
	{
		$this->mail->AddBCC($address, $name);
	}
	
	function addBccArray($array)
	{
		foreach($array as $arr)
		{
			if(is_array($arr))
				$this->addBcc($arr['address'], $arr['name']);
			else
				$this->addBcc($arr);
		}
	}
	
	function addAttachment($path)
	{
		$this->mail->AddAttachment($path);
	}
	
	function send()
	{
		if($this->from != false)
		{
			$this->mail->From = $this->from['address'];
			$this->mail->FromName = $this->from['name'];
		}
		
		if($this->replyTo != false)
		{
			$this->mail->AddReplyTo($this->replyTo['address'], $this->replyTo['name']);
		}
		
		$is_send = $this->mail->Send(); 
	//	$is_send = true;
		if(!$is_send)
		{
			//print_r($this->mail);
			return $this->mail->ErrorInfo;
		}
		else
		{
			return true;
		}
	}

}

?>