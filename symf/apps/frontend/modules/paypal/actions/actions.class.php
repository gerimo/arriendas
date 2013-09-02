<?php

/**
 * paypal actions.
 *
 * @package    CarSharing
 * @subpackage paypal
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class paypalActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    //$this->forward('default', 'module');
  }
  
  
   public function executeOrderReview(sfWebRequest $request)
   {
  			sfConfig::set('sf_web_debug', false);
	
			$PayPalNVP = new PayPalNVP();
  
				// Check to see if the Request object contains a variable named 'token'	
				$token = "";
				if (isset($_REQUEST['token']))
				{

					$token = $_REQUEST['token'];
					$_SESSION["confirmtoken"] = $token;
				}

				// If the Request object contains the variable 'token' then it means that the user is coming from PayPal site.	
				if ( $token != ""  && $_SESSION["token"] == $token )
				{
				

					/*
					'------------------------------------
					' Calls the GetExpressCheckoutDetails API call
					'
					' The GetShippingDetails function is defined in PayPalFunctions.jsp
					' included at the top of this file.
					'-------------------------------------------------
					*/
		

					$resArray = $PayPalNVP->GetShippingDetails( $token );
					$ack = strtoupper($resArray["ACK"]);
					if( $ack == "SUCCESS" || $ack == "SUCESSWITHWARNING") 
					{
					
							$q = Doctrine::getTable('Transaction')->createQuery('t')->where('t.id = ? ', $_SESSION["tid"]);
							$t = $q->fetchOne();
							$this->price = $t->getPrice();
							$this->car = $t->getCar();

						/*
						' The information that is returned by the GetExpressCheckoutDetails call should be integrated by the partner into his Order Review 
						' page		
						*/
						$this->email 				= $resArray["EMAIL"]; // ' Email address of payer.
						$this->payerId 			= $resArray["PAYERID"]; // ' Unique PayPal customer account identification number.
						$this->payerStatus		= $resArray["PAYERSTATUS"]; // ' Status of payer. Character length and limitations: 10 single-byte alphabetic characters.
						//$this->salutation			= $resArray["SALUTATION"]; // ' Payer's salutation.
						$this->firstName			= $resArray["FIRSTNAME"]; // ' Payer's first name.
						//$this->middleName			= $resArray["MIDDLENAME"]; // ' Payer's middle name.
						$this->lastName			= $resArray["LASTNAME"]; // ' Payer's last name.
						//$this->suffix				= $resArray["SUFFIX"]; // ' Payer's suffix.
						$this->cntryCode			= $resArray["COUNTRYCODE"]; // ' Payer's country of residence in the form of ISO standard 3166 two-character country codes.
						//$this->business			= $resArray["BUSINESS"]; // ' Payer's business name.
						$this->shipToName			= $resArray["PAYMENTREQUEST_0_SHIPTONAME"]; // ' Person's name associated with this address.
						$this->shipToStreet		= $resArray["PAYMENTREQUEST_0_SHIPTOSTREET"]; // ' First street address.
						//$this->shipToStreet2		= $resArray["PAYMENTREQUEST_0_SHIPTOSTREET2"]; // ' Second street address.
						$this->shipToCity			= $resArray["PAYMENTREQUEST_0_SHIPTOCITY"]; // ' Name of city.
						$this->shipToState		= $resArray["PAYMENTREQUEST_0_SHIPTOSTATE"]; // ' State or province
						$this->shipToCntryCode	= $resArray["PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE"]; // ' Country code. 
						$this->shipToZip			= $resArray["PAYMENTREQUEST_0_SHIPTOZIP"]; // ' U.S. Zip code or other country-specific postal code.
						$this->addressStatus 		= $resArray["ADDRESSSTATUS"]; // ' Status of street address on file with PayPal   
						//$this->invoiceNumber		= $resArray["INVNUM"]; // ' Your own invoice or tracking number, as set by you in the element of the same name in SetExpressCheckout request .
						//$this->phonNumber			= $resArray["PHONENUM"]; // ' Payer's contact telephone number. Note:  PayPal returns a contact telephone number only if your Merchant account profile settings require that the buyer enter one. 
					} 
					else  
					{
						//Display a user friendly Error on the page using any of the following error information returned by PayPal
						$ErrorCode = urldecode($resArray["L_ERRORCODE0"]);
						$ErrorShortMsg = urldecode($resArray["L_SHORTMESSAGE0"]);
						$ErrorLongMsg = urldecode($resArray["L_LONGMESSAGE0"]);
						$ErrorSeverityCode = urldecode($resArray["L_SEVERITYCODE0"]);
						
						echo "GetExpressCheckoutDetails API call failed. ";
						echo "Detailed Error Message: " . $ErrorLongMsg;
						echo "Short Error Message: " . $ErrorShortMsg;
						echo "Error Code: " . $ErrorCode;
						echo "Error Severity Code: " . $ErrorSeverityCode;
					}
				} 
  
  
   }
  
  
  
  
  
  public function executeConfirm(sfWebRequest $request)
  {

  
		
		$PayPalNVP = new PayPalNVP();
	
		
		if ( $_SESSION["token"] == $_SESSION["confirmtoken"])
		{
			/*
			'------------------------------------
			' The paymentAmount is the total value of 
			' the shopping cart, that was set 
			' earlier in a session variable 
			' by the shopping cart page
			'------------------------------------
			*/
						
			
			$finalPaymentAmount =  $_SESSION["Payment_Amount"];
				
			/*
			'------------------------------------
			' Calls the DoExpressCheckoutPayment API call
			'
			' The ConfirmPayment function is defined in the file PayPalFunctions.jsp,
			' that is included at the top of this file.
			'-------------------------------------------------
			*/

			$resArray = $PayPalNVP->ConfirmPayment ( $finalPaymentAmount );
			$ack = strtoupper($resArray["ACK"]);
			if( $ack == "SUCCESS" || $ack == "SUCCESSWITHWARNING" )
			{
			
				$q = Doctrine::getTable('Transaction')->createQuery('t')->where('t.id = ? ', $_SESSION["tid"]);
				$t = $q->fetchOne();
				$t->setCompleted(true);
				$t->save();
			
			
				/*
				'********************************************************************************************************************
				'
				' THE PARTNER SHOULD SAVE THE KEY TRANSACTION RELATED INFORMATION LIKE 
				'                    transactionId & orderTime 
				'  IN THEIR OWN  DATABASE
				' AND THE REST OF THE INFORMATION CAN BE USED TO UNDERSTAND THE STATUS OF THE PAYMENT 
				'
				'********************************************************************************************************************
				*/

				$transactionId		= $resArray["PAYMENTINFO_0_TRANSACTIONID"]; // ' Unique transaction ID of the payment. Note:  If the PaymentAction of the request was Authorization or Order, this value is your AuthorizationID for use with the Authorization & Capture APIs. 
				$transactionType 	= $resArray["PAYMENTINFO_0_TRANSACTIONTYPE"]; //' The type of transaction Possible values: l  cart l  express-checkout 
				$paymentType		= $resArray["PAYMENTINFO_0_PAYMENTTYPE"];  //' Indicates whether the payment is instant or delayed. Possible values: l  none l  echeck l  instant 
				$orderTime 			= $resArray["PAYMENTINFO_0_ORDERTIME"];  //' Time/date stamp of payment
				$amt				= $resArray["PAYMENTINFO_0_AMT"];  //' The final amount charged, including any shipping and taxes from your Merchant Profile.
				$currencyCode		= $resArray["PAYMENTINFO_0_CURRENCYCODE"];  //' A three-character currency code for one of the currencies listed in PayPay-Supported Transactional Currencies. Default: USD. 
				$feeAmt				= $resArray["PAYMENTINFO_0_FEEAMT"];  //' PayPal fee amount charged for the transaction
				//$settleAmt			= $resArray["PAYMENTINFO_0_SETTLEAMT"];  //' Amount deposited in your PayPal account after a currency conversion.
				$taxAmt				= $resArray["PAYMENTINFO_0_TAXAMT"];  //' Tax charged on the transaction.
				//$exchangeRate		= $resArray["PAYMENTINFO_0_EXCHANGERATE"];  //' Exchange rate if a currency conversion occurred. Relevant only if your are billing in their non-primary currency. If the customer chooses to pay with a currency other than the non-primary currency, the conversion occurs in the customer's account.
				
				/*
				' Status of the payment: 
						'Completed: The payment has been completed, and the funds have been added successfully to your account balance.
						'Pending: The payment is pending. See the PendingReason element for more information. 
				*/
				
				$paymentStatus	= $resArray["PAYMENTINFO_0_PAYMENTSTATUS"]; 

				/*
				'The reason the payment is pending:
				'  none: No pending reason 
				'  address: The payment is pending because your customer did not include a confirmed shipping address and your Payment Receiving Preferences is set such that you want to manually accept or deny each of these payments. To change your preference, go to the Preferences section of your Profile. 
				'  echeck: The payment is pending because it was made by an eCheck that has not yet cleared. 
				'  intl: The payment is pending because you hold a non-U.S. account and do not have a withdrawal mechanism. You must manually accept or deny this payment from your Account Overview. 		
				'  multi-currency: You do not have a balance in the currency sent, and you do not have your Payment Receiving Preferences set to automatically convert and accept this payment. You must manually accept or deny this payment. 
				'  verify: The payment is pending because you are not yet verified. You must verify your account before you can accept this payment. 
				'  other: The payment is pending for a reason other than those listed above. For more information, contact PayPal customer service. 
				*/
				
				$pendingReason	= $resArray["PAYMENTINFO_0_PENDINGREASON"];  

				/*
				'The reason for a reversal if TransactionType is reversal:
				'  none: No reason code 
				'  chargeback: A reversal has occurred on this transaction due to a chargeback by your customer. 
				'  guarantee: A reversal has occurred on this transaction due to your customer triggering a money-back guarantee. 
				'  buyer-complaint: A reversal has occurred on this transaction due to a complaint about the transaction from your customer. 
				'  refund: A reversal has occurred on this transaction because you have given the customer a refund. 
				'  other: A reversal has occurred on this transaction due to a reason not listed above. 
				*/
				
				$reasonCode		= $resArray["PAYMENTINFO_0_REASONCODE"];   
				
				//return sfView::NONE;  
				$this->redirect ('profile/transactions'); 
			}
			else  
			{
			
				$this->getUser()->setFlash('show', true); 
				$this->getUser()->setFlash('msg', 'No tiene saldo para realizar el pago'); 
				$this->forward ('paypal', 'orderReview');  
			
				//Display a user friendly Error on the page using any of the following error information returned by PayPal
				$ErrorCode = urldecode($resArray["L_ERRORCODE0"]);
				$ErrorShortMsg = urldecode($resArray["L_SHORTMESSAGE0"]);
				$ErrorLongMsg = urldecode($resArray["L_LONGMESSAGE0"]);
				$ErrorSeverityCode = urldecode($resArray["L_SEVERITYCODE0"]);
				
				echo "GetExpressCheckoutDetails API call failed. ";
				echo "Detailed Error Message: " . $ErrorLongMsg;
				echo "Short Error Message: " . $ErrorShortMsg;
				echo "Error Code: " . $ErrorCode;
				echo "Error Severity Code: " . $ErrorSeverityCode;
			}
		}		
		else
		{
		
				$this->getUser()->setFlash('show', true); 
				$this->getUser()->setFlash('msg', 'No se a ingresa'); 
				$this->forward ('paypal', 'orderReview');  

		
		}
		
		
  
  }
  
  
  
  
  
    public function executeCheckout(sfWebRequest $request)
  {
			sfConfig::set('sf_web_debug', false);
	
			$tid = $request->getParameter('tid');
			
			$q = Doctrine::getTable('Transaction')->createQuery('t')->where('t.id = ? ', $tid);
			$t = $q->fetchOne();
			
			$t->getPrice();
	
	
			$PayPalNVP = new PayPalNVP();
			 
			//'------------------------------------
			//' The paymentAmount is the total value of 
			//' the shopping cart, that was set 
			//' earlier in a session variable 
			//' by the shopping cart page
			//'------------------------------------
			
			$_SESSION["Payment_Amount"]  = $t->getPrice();
			
			$paymentAmount = $_SESSION["Payment_Amount"];

			//'------------------------------------
			//' The currencyCodeType and paymentType 
			//' are set to the selections made on the Integration Assistant 
			//'------------------------------------
			$currencyCodeType = "USD";
			$paymentType = "Sale";

			//'------------------------------------
			//' The returnURL is the location where buyers return to when a
			//' payment has been succesfully authorized.
			//'
			//' This is set to the value entered on the Integration Assistant 
			//'------------------------------------
			$returnURL = "http://" . $_SERVER['SERVER_NAME'] . $this->getController()->genUrl('paypal/orderReview');
			
			//'------------------------------------
			//' The cancelURL is the location buyers are sent to when they hit the
			//' cancel button during authorization of payment during the PayPal flow
			//'
			//' This is set to the value entered on the Integration Assistant 
			//'------------------------------------
			$cancelURL = "http://" . $_SERVER['SERVER_NAME'] . $this->getController()->genUrl('profile/transactions');

			//'------------------------------------
			//' Calls the SetExpressCheckout API call
			//'
			//' The CallShortcutExpressCheckout function is defined in the file PayPalFunctions.php,
			//' it is included at the top of this file.
			//'-------------------------------------------------
			$resArray = $PayPalNVP->CallShortcutExpressCheckout ($paymentAmount, $currencyCodeType, $paymentType, $returnURL, $cancelURL);
		   
			$ack = strtoupper($resArray["ACK"]);
			if($ack=="SUCCESS" || $ack=="SUCCESSWITHWARNING")
			{
				$_SESSION["token"] = $resArray["TOKEN"];
				$_SESSION["tid"] = $tid;
				$PayPalNVP->RedirectToPayPal ( $resArray["TOKEN"] );
			} 
			else  
			{
				//Display a user friendly Error on the page using any of the following error information returned by PayPal
				$ErrorCode = urldecode($resArray["L_ERRORCODE0"]);
				$ErrorShortMsg = urldecode($resArray["L_SHORTMESSAGE0"]);
				$ErrorLongMsg = urldecode($resArray["L_LONGMESSAGE0"]);
				$ErrorSeverityCode = urldecode($resArray["L_SEVERITYCODE0"]);
				
				echo "SetExpressCheckout API call failed. ";
				echo "Detailed Error Message: " . $ErrorLongMsg;
				echo "Short Error Message: " . $ErrorShortMsg;
				echo "Error Code: " . $ErrorCode;
				echo "Error Severity Code: " . $ErrorSeverityCode;
			}
  
  
  return sfView::NONE;  
  
  
  }
  
  
  
  
  
  
  
  
}
