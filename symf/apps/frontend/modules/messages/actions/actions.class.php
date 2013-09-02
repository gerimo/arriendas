<?php

/**
 * messages actions.
 *
 * @package    CarSharing
 * @subpackage messages
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class messagesActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward('default', 'module');
  }
  public function executeNew(sfWebRequest $request) {
    $this->user_from = $this->getUser()->getAttribute("userid");
	  $this->user = Doctrine_Core::getTable('user')->find($this->getRequestParameter("id"));
  }
  public function executeSavenew(){
    try {
        $date = date("Y-m-d H:i:s");
        $conv = new Conversation();
        $conv->setUserFromId($this->getRequestParameter("user_from"));
        $conv->setUserToId($this->getRequestParameter("user_to"));
        $conv->setStart($date);
        $conv->save();

        $mess = new Message();
        $mess->setBody($this->getRequestParameter("description"));
        $mess->setDate($date);
        $mess->setConversationId($conv->getId());
        $mess->setUserId($this->getRequestParameter("user_from"));
        $mess->save();

        $this->getUser()->setFlash("message_ok","Su mensaje fue enviado");

    } catch (Exception $e) {

    $this->getUser()->setFlash("message_error","Su mensaje no pudo ser enviado por el siquiente motivo $e");
    }

    $this->redirect("messages/inbox");
  }
  public function executeInbox(){
    $this->user_id = $this->getUser()->getAttribute("userid");
    $this->messages  = Doctrine_Core::getTable("Message")->findOpenToUserId($this->getUser()->getAttribute("userid"));
    $this->old_messages  = Doctrine_Core::getTable("Message")->findReceived($this->getUser()->getAttribute("userid"));
  }
  public function executeConversation(){
    $message = Doctrine_Core::getTable("Message")->find($this->getRequestParameter("message_id"));;
    if( $message->getUserId() != $this->getUser()->getAttribute("userid") ) {

	    $message->setReceived(1);
	    $message->save();
    }
    $this->messages  = Doctrine_Core::getTable("Message")->findConversation($this->getRequestParameter("id"));
    $this->conversation = Doctrine_Core::getTable("Conversation")->find($this->getRequestParameter("id"));
	  $this->user = Doctrine_Core::getTable('user')->find($this->getUser()->getAttribute("userid"));
    $this->user_id = $this->getUser()->getAttribute("userid");
  }
  public function executeSaveconversation(){
    	
    try {
    	
      	$date = date("Y-m-d H:i:s");
        $mess = new Message();
        $mess->setBody($this->getRequestParameter("description"));
        $mess->setDate($date);
        $mess->setConversationId($this->getRequestParameter("conversation_id"));
        $mess->setUserId($this->getRequestParameter("user_id"));
        $mess->save();

		$this->sendNotificationConversation($this->getRequestParameter("conversation_id"), $this->getRequestParameter("description"), $this->getRequestParameter("user_id"));

        $this->getUser()->setFlash("message_ok","Su mensaje fue enviado");

    } catch (Exception $e) {
    $this->getUser()->setFlash("message_error","Su mensaje no pudo ser enviado por el siquiente motivo $e");
    }

    $this->redirect("messages/conversation?id=".$mess->getConversationId()."&message_id=".$mess->getId());
  }
  
    public function sendNotificationConversation($idconversation, $msg, $userid) {

		try {
					
			$receptor  = Doctrine_Core::getTable("Message")->getUserConversation($idconversation, $userid);
			$user = Doctrine_Core::getTable("User")->find($userid);
			
	        $to = $receptor->getEmail();
	        $subject = "Arriendas.cl - Mensaje nuevo";
	
	        // compose headers
	        $headers = "From: \"Arriendas Reservas\" <no-reply@arriendas.cl>\n";
	        $headers .= "Content-type: text/html\n";
	        $headers .= "X-Mailer: PHP/" . phpversion() . "\n";
	
	        $mail = 'Estimado(a) '.$receptor->getFirstName().' '.$receptor->getLastName().':<br><br>
			
			Le han enviado un nuevo mensaje, de:<br><br>
			
			'.$user->getFirstName().' '.$user->getLastName().'<br><br>
			
			Mensaje:<br>
			
			'.$msg.'<br><br>
			
			El equipo de Arriendas.cl';
	
	        // send email
	        mail($to, $subject, $mail, $headers);
			
		} catch(Exception $e) { die($e); }
    }
  
  public function executeDelete(){

    try {
      $mess = Doctrine_Core::getTable("Message")->find($this->getRequestParameter("id"));
      $con_id = $mess->getConversationId();
      $mess->delete();
      $convs = Doctrine_Core::getTable("Message")->countByConversationId($con_id);
      if ($convs == 0) {
        $con = Doctrine_Core::getTable("Conversation")->find($con_id);
        $con->delete();
      }
      $this->getUser()->setFlash("message_ok","Su mensaje fue enviado");
    } catch (Exception $e) {
      $this->getUser()->setFlash("message_error","Su mensaje no pudo ser enviado por el siquiente motivo $e");
    }

    $this->redirect("messages/inbox");
  }
}
