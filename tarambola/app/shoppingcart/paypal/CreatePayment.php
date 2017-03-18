<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CreatePayment
 *
 * @author paulofernandes
 */
require SERVER_URL.'/tarambola/app/shoppingcart/paypal/vendor/autoload.php';

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Address;
use PayPal\Api\CreditCard;
use PayPal\Api\FundingInstrument;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;


class CreatePayment {

    private $payer;
    private $itemList;
    private $arrayList = array();
    private $details;
    private $amount;
    private $transaction;
    private $description;
    private $redirectUrls;
    private $payment;
    
    
    
    public function __construct($method, $description, $intent="sale")
    {
        $this->description = $description;
        
        $this->payer = new Payer();
        $this->payer->setPaymentMethod($method);
        
        $this->itemList = new ItemList();
        
        $this->payment = new Payment();
        $this->payment->setIntent($intent);

    }
    public function setRedirect($success, $cancel)
    {
        $this->redirectUrls = new RedirectUrls();
        $this->redirectUrls->setReturnUrl($success)
                           ->setCancelUrl($cancel);
    }
    public function addItem($name, $qtd, $price, $currency="EUR")
    {
        $item = new Item();
        $item->setName($name)
             ->setCurrency($currency)
             ->setQuantity($qtd)
             ->setPrice($price);
        
        array_push($this->arrayList, $item);
    }
    public function setDetails($shipping, $tax, $subTotal, $fee='0.00')
    {
        $this->itemList->setItems($this->arrayList);
        
        $this->details = new Details();
        $this->details->setShipping($shipping)
                      ->setTax($tax)
                      ->setSubtotal($subTotal);
    }
    public function setAmount($total, $currency="EUR")
    {
        $this->amount = new Amount();
        $this->amount->setCurrency($currency)
                     ->setTotal($total)
                     ->setDetails($this->details);
    }
    public function setTransaction()
    {
        $this->transaction = new Transaction();
        $this->transaction->setAmount($this->amount)
                          ->setItemList($this->itemList)
                          ->setDescription($this->description);
    }
    public function setPayment()
    {
        $this->payment->setPayer($this->payer)
                      ->setRedirectUrls($this->redirectUrls)
                      ->setTransactions(array($this->transaction));
    }
    public function execute()
    {
        try {
            $this->payment->create($this->getApiContext());
        } 
        catch (PayPal\Exception\PPConnectionException $ex) 
        {
            echo "Exception: " . $ex->getMessage(). ", Data: " . $ex->getData() . PHP_EOL;
            var_dump($ex->getData());	
            exit(1);
        }
        
        foreach($this->payment->getLinks() as $link) 
        {
            if($link->getRel() == 'approval_url') 
            {
                    $redirectUrl = $link->getHref();
                    break;
            }
        }
        
        $_SESSION['paymentId'] = $this->payment->getId();
        if(isset($redirectUrl)) 
        {
            header("Location: $redirectUrl");
            exit;
        }
    }
    public function prepareAndExecute()
    {
        $this->setTransaction();
        $this->setPayment();
        $this->execute();
    }
    
    private function getApiContext() 
    {
	// ### Api context
	// Use an ApiContext object to authenticate 
	// API calls. The clientId and clientSecret for the 
	// OAuthTokenCredential class can be retrieved from 
	// developer.paypal.com
        global $__tarambola_CONN__;
        
        $sql="SELECT * FROM tara_configuracoes WHERE tipo='paypal'";
        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
            $stmt->execute();

            $object = $stmt->fetchObject();
        }
	$apiContext = new ApiContext(
		new OAuthTokenCredential(
			$object->campo,
			$object->campo2
		)
	);



	// #### SDK configuration
	
	// Comment this line out and uncomment the PP_CONFIG_PATH
	// 'define' block if you want to use static file 
	// based configuration
        if(DEBUG_PAYPAL)
            $mode='sandbox';
        else
            $mode='live';
	$apiContext->setConfig(
		array(
			'mode' => $mode,
			'http.ConnectionTimeOut' => 30,
			'log.LogEnabled' => true,
			'log.FileName' => SERVER_URL.'tarambola/app/shoppingcart/paypal/paypal.log',
			'log.LogLevel' => 'FINE'
		)
	);
	
	/*
	// Register the sdk_config.ini file in current directory
	// as the configuration source.
	if(!defined("PP_CONFIG_PATH")) {
		define("PP_CONFIG_PATH", __DIR__);
	}
	*/

	return $apiContext;
    }
    private function createAuthorization($apiContext) {
	$addr = new Address();
	$addr->setLine1("3909 Witmer Road")
		->setLine2("Niagara Falls")
		->setCity("Niagara Falls")
		->setState("NY")
		->setPostalCode("14305")
		->setCountryCode("US")
		->setPhone("716-298-1822");
	
	$card = new CreditCard();
	$card->setType("visa")
		->setNumber("4417119669820331")
		->setExpireMonth("11")
		->setExpireYear("2019")
		->setCvv2("012")
		->setFirstName("Joe")
		->setLastName("Shopper")
		->setBillingAddress($addr);
	
	$fi = new FundingInstrument();
	$fi->setCreditCard($card);
	
	$payer = new Payer();
	$payer->setPaymentMethod("credit_card")
		->setFundingInstruments(array($fi));
	
	$amount = new Amount();
	$amount->setCurrency("USD")
		->setTotal("1.00");
	
	$transaction = new Transaction();
	$transaction->setAmount($amount)
		->setDescription("Payment description.");
	
	$payment = new Payment();

	// Setting intent to authorize creates a payment
	// authorization. Setting it to sale creates actual payment
	$payment->setIntent("authorize")
		->setPayer($payer)
		->setTransactions(array($transaction));
	
	$paymnt = $payment->create($apiContext);
	$resArray = $paymnt->toArray();
	
	return $authId = $resArray['transactions'][0]['related_resources'][0]['authorization']['id'];
    }
}

?>
