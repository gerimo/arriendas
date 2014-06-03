<?php

include('Mail.php');
include('Mail/mime.php');

class Email{
	private $headers = array(
                        'From'          => 'Soporte Arriendas<soporte@arriendas.cl>',
                        'To'			=> '',
                        'Bcc'			=> '',
                        'Return-Path'   => 'Soporte Arriendas<soporte@arriendas.cl>',
                        'Subject'       => 'Arriendas.cl'
                        );
	private $mime_params = array(
						'text_charset'  => 'UTF-8',
						'html_charset'  => 'UTF-8',
						'head_charset'  => 'UTF-8'
						);
	private $to = 'Mauri<mauricio@arriendas.cl>';
	private $html = '';
	private $crlf = "\n";

	private $files;
	//private $file_name = "nombreArchivo.txt";
	//private $content_type = 'application/pdf';
	//private $content_type = "text/plain";
	//private $content_type = "application/octet-stream";
	//private $content_type = "image/jpg";
	private $host = "smtp.sendgrid.net";
//	private $host = "localhost";
	private $username = "german@arriendas.cl";
	private $password = "whisper00@@__";

	function __constructor(){   
	}

	public function setTo($to){
		$this->to = $to;
		$this->headers['To'] = $to;
	}

	public function setCc($cc){
		$this->to = $this->to.','.$cc;
	}
	public function setBcc($bcc){
		$this->bcc = $bcc;
		//	$this->headers['Bcc'] = $bcc;
	}

	//destinatario
	public function setFrom($from){
		$this->headers['From'] = $from;
		$this->headers['Return-Path'] = $from;
	}


	//asunto
	public function setSubject($subject){
		$this->headers['Subject'] = 'Arriendas.cl - '.$subject;
	}

	//cuerpo del mensaje en html
	public function setBody($html){
		$this->html = $html."<p><br><br>Gracias,<br>El equipo de <a href='http://www.arriendas.cl'>Arriendas.cl</a></p><p><i>Nota: Para evitar posibles problemas con la recepcion de este correo le aconsejamos nos agregue a su libreta de contactos.</i></p>";
	}

	//adjunta archivo
	public function setFile($file){
		$this->files[] = $file;

		$file = explode('.', $file);
		$file = $file[count($file)-1];

	}

	//envía mail
	public function submit(){

		$mime = new Mail_mime(array('eol' => $this->crlf));
		$mime->setHTMLBody($this->html);

		foreach ($this->files as $file){
			$mime->addAttachment($file);
		}

		$body = $mime->get($this->mime_params);
        $headers = $mime->headers($this->headers);
 
        // Sending the email
        //$mail =& Mail::factory('mail');
        $smtp = Mail::factory('smtp',
 				array ('host' => $this->host,
					    'auth' => true,
					    'username' => $this->username,
					    'password' => $this->password));
		
		$recipients = strtolower($this->to.", ".$this->bcc);

//		sfContext::getInstance()->getLogger()->info('$recipients '.$recipients);
//		sfContext::getInstance()->getLogger()->info('$headers '.$headers);
//		sfContext::getInstance()->getLogger()->info('$body '.$body);

		
        if($smtp->send($recipients, $headers, $body)){
		sfContext::getInstance()->getLogger()->info('mail send returned true');	        
		return true;
        }else{
        	return false;
        }  

	}
        
        public function getMailer()
        {
            $transport = Swift_SmtpTransport::newInstance('smtp.sendgrid.net', 465, 'ssl')
            ->setUsername('german@arriendas.cl')
             ->setPassword('whisper00@@__');
            $mailer = Swift_Mailer::newInstance($transport);
            
            return $mailer;
        }
        
        public function getMessage()
        {
            $mail = Swift_Message::newInstance();
            $mail->setContentType('text/html');
            $mail->setFrom(array('soporte@arriendas.cl' => 'Soporte Arriendas'));

            return $mail;
        }
        
        public function addFooter($html){
		return $html."<p><br><br>Gracias,<br>El equipo de <a href='http://www.arriendas.cl'>Arriendas.cl</a></p><p><i>Nota: Para evitar posibles problemas con la recepcion de este correo le aconsejamos nos agregue a su libreta de contactos.</i></p>";
	}
}

?>
