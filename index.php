


<?php



// USAR PARA PEGAR AS INFORMACOES DO PICPAY

  /* Simples integração com PicPay
   * Arquivo de CheckOut
   * @author: Script Mundo
   */
  
   /* Acesse a documentação do PicPay para mais informações
    * Para cada requisição o PicPay exige um IDReference diferente
    * Na class PicPay o IDReference recebe o id do produto
	* portanto toda vez que fizer a requisição não se esqueça de alterar o id do produto
	*/
	
	
  // class PicPay
  
  require_once 'paymentPicPay.php';



  $picpay = new PicPay;
  

  
  $data = json_decode(file_get_contents('php://input'), true);
  
  

  
  // Dados do produto
   $prod['ref']    = $data["referenceId"];		
   $prod['nome']  = $data["productName"];	
   $prod['valor'] = $data["value"];
   
   // Dados do cliente
   $cli['nome']      = $data["firstName"];
   $cli['sobreNome'] = $data["lastName"];
   $cli['cpf']		 = $data["document"];
   $cli['email']	 = $data["email"];
   $cli['telefone']  = $data["phone"];
   
	unset($produto);
   $produto = (object)$prod;
   $cliente = (object)$cli;
   
   $payment = $picpay->requestPayment($produto,$cliente);
  
	if(isset($payment->message)):
	
		echo '{ 
		"error" : "' . $payment->message . '" } ';
    
		
	else:
		 
 	   $link   = $payment->paymentUrl;
	   $qrCode = $payment->qrcode->base64;
	 
     echo '{ 
             "paymentUrl": "' . $payment->paymentUrl . '", 
             "qrcode" : "' . $payment->qrcode->content . '",
             "qrcodeBase64" : "' . $payment->qrcode->base64 . '" }';
	   
    endif;
  
  
?>
