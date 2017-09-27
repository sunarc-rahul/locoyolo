<?php 
/*=======================================================================
	@Auther 	Ashwini Agarwal
	@Date   	November 2, 2012
//======================================================================= */
defined("ACCESS") or die("Access Restricted");
require_once(ROOTPATH."/lib/PHPMailer/PHPMailerAutoload.php");

class mailer
{
	private $mail;
	private $from;
	private $replyTo;
	
	function __construct()
	{
		$this->mail = new PHPMailer();
        $this->mail->isSMTP();                                      // Set mailer to use SMTP
        $this->mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
        $this->mail->SMTPAuth = true;                               // Enable SMTP authentication
        $this->mail->Username = 'johnarc5@gmail.com';                 // SMTP username
        $this->mail->Password = 'sunarc123';                           // SMTP password
        $this->mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $this->mail->Port = 587;
        $this->mail->setFrom('johnarc5@gmail.com', 'Locoyolo');
		$this->mail->addAddress('contact@locoyolo.com', 'Locoyolo');
        $this->mail->isHTML(true);
    }
	
	function addTo($address, $name = '')
	{
		$this->mail->addAddress($address, $name);
	}
	
	function setFrom($address, $name = '')
	{
		$this->mail->setFrom($address,$name );


	}
	
	function setReplyTo($address, $name = '')
	{
		$this->mail->addReplyTo($address, $name);
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
		$this->mail->addCC($address, $name);
	}
	
	function addCcArray($array)
	{
		foreach($array as $arr)
		{
			if(is_array($arr))
                $this->mail->addBCC($arr['address'], $arr['name']);
			else
                $this->mail->addBCC($arr);
		}
	}
	
	function addBcc($address, $name = '')
	{
        $this->mail->addBCC($address, $name);
	}
	
	function addBccArray($array)
	{
		foreach($array as $arr)
		{
			if(is_array($arr))
                $this->mail->addBCC($arr['address'], $arr['name']);
			else
                $this->mail->addBCC($arr);
		}
	}
	
	function addAttachment($path)
	{
		$this->mail->addAttachment($path);
	}
	
	function send()
	{

		if($this->replyTo != false)
		{
			$this->mail->addReplyTo('johnarc5@gmail.com', 'Locoyolo');
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