<?php

class Paypal
{
 private  $id="geral@tarambola.pt";
 private $item_name;
 private $item_number;
 private $amount;

 var $last_error;                 // holds the last error encountered

   var $ipn_log;                    // bool: log IPN results to text file?

   var $ipn_log_file;               // filename of the IPN log
   var $ipn_response;               // holds the IPN response from paypal
   var $ipn_data = array();         // array contains the POST values for IPN

   var $fields = array();           // array holds the fields to submit to paypal


 public function __construct()
    {
      $this->paypal_url = 'https://www.teste.kompraki.pt';

      $this->last_error = '';

      $this->ipn_log_file = '.ipn_results.log';
      $this->ipn_log = true;
      $this->ipn_response = '';
      $this->add_field('rm','2');           // Return method = POST
      $this->add_field('cmd','_xclick'); 
    }
    
  public function add_field($field, $value) {

      // adds a key=>value pair to the fields array, which is what will be
      // sent to paypal as POST variables.  If the value is already in the
      // array, it will be overwritten.

      $this->fields["$field"] = $value;
   }
 public function setId($id)
 {
     $this->id = $id;
 }
  public function setItemNumber($in)
 {
     $this->item_number = $in;
 }
 public function setItemName($in)
 {
     $this->item_name = $in;
 }
 public function setAmount($a)
 {
     $this->amount = $a;
 }



public function getButton()
{
    $button = '
    <form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
        <input type="hidden" name="cmd" value="_xclick">
        <input type="hidden" name="business" value='.$this->id.'>
        <input type="hidden" name="item_name" value='.$this->item_name.'>
        <input type="hidden" name="item_number" value='.$this->item_number.'>
        <input type="hidden" name="amount" value='.$this->amount.'>
        <input type="hidden" name="cancel_return" value="'.URL_PUBLIC.'checkout/erro.html" />
        <input type="hidden" name="return" value="'.URL_PUBLIC.'checkout/sucesso.html" />
        <input type="hidden" name="no_shipping" value="0">
        <input type="hidden" name="no_note" value="1">
        <input type="hidden" name="currency_code" value="EUR">
        <input type="hidden" name="lc" value="PT">
        <input type="hidden" name="bn" value="PP-BuyNowBF">

        <input type="image" src="'.URL_PUBLIC.'public/themes/kompraki/_img/pagar_btn.png" style="width:126px" border="0" name="submit" alt="PayPal - The safer, easier way to pay online.">
        <img alt="" border="0" src="https://www.paypal.com/en_AU/i/scr/pixel.gif" width="1" height="1">
    </form>
';
    return($button);
}

   function validate_ipn() {

      // parse the paypal URL
      $url_parsed=parse_url($this->paypal_url);

      // generate the post string from the _POST vars aswell as load the
      // _POST vars into an arry so we can play with them from the calling
      // script.
      $post_string = '';
      foreach ($_POST as $field=>$value) {
         $this->ipn_data["$field"] = $value;
         $post_string .= $field.'='.urlencode(stripslashes($value)).'&';
      }
      $post_string.="cmd=_notify-validate"; // append ipn command

      // open the connection to paypal
      $fp = fsockopen($url_parsed[host],"80",$err_num,$err_str,30);
      if(!$fp) {

         // could not open the connection.  If loggin is on, the error message
         // will be in the log.
         $this->last_error = "fsockopen error no. $errnum: $errstr";
         $this->log_ipn_results(false);
         return false;

      } else {

         // Post the data back to paypal
         fputs($fp, "POST $url_parsed[path] HTTP/1.1\r\n");
         fputs($fp, "Host: $url_parsed[host]\r\n");
         fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
         fputs($fp, "Content-length: ".strlen($post_string)."\r\n");
         fputs($fp, "Connection: close\r\n\r\n");
         fputs($fp, $post_string . "\r\n\r\n");

         // loop through the response from the server and append to variable
         while(!feof($fp)) {
            $this->ipn_response .= fgets($fp, 1024);
         }

         fclose($fp); // close connection

      }

      if (eregi("VERIFIED",$this->ipn_response)) {

         // Valid IPN transaction.
         $this->log_ipn_results(true);
         return true;

      } else {

         // Invalid IPN transaction.  Check the log for details.
         $this->last_error = 'IPN Validation Failed.';
         $this->log_ipn_results(false);
         return false;

      }

   }

   function log_ipn_results($success) {

      if (!$this->ipn_log) return;  // is logging turned off?

      // Timestamp
      $text = '['.date('m/d/Y g:i A').'] - ';

      // Success or failure being logged?
      if ($success) $text .= "SUCCESS!\n";
      else $text .= 'FAIL: '.$this->last_error."\n";

      // Log the POST variables
      $text .= "IPN POST Vars from Paypal:\n";
      foreach ($this->ipn_data as $key=>$value) {
         $text .= "$key=$value, ";
      }

      // Log the response from the paypal server
      $text .= "\nIPN Response from Paypal Server:\n ".$this->ipn_response;

      // Write to log
      $fp=fopen($this->ipn_log_file,'a');
      fwrite($fp, $text . "\n\n");

      fclose($fp);  // close file
   }

   function dump_fields() {

      // Used for debugging, this function will output all the field/value pairs
      // that are currently defined in the instance of the class using the
      // add_field() function.

      echo "<h3>paypal_class->dump_fields() Output:</h3>";
      echo "<table width=\"95%\" border=\"1\" cellpadding=\"2\" cellspacing=\"0\">
            <tr>
               <td bgcolor=\"black\"><b><font color=\"white\">Field Name</font></b></td>
               <td bgcolor=\"black\"><b><font color=\"white\">Value</font></b></td>
            </tr>";

      ksort($this->fields);
      foreach ($this->fields as $key => $value) {
         echo "<tr><td>$key</td><td>".urldecode($value)."&nbsp;</td></tr>";
      }

      echo "</table><br>";
   }
}
?>