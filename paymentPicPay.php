<?php 

 /* www.scriptmundo.com
  * @PicPay Pagamentos
  * Simples integração com PicPay
  * Documentação: https://bit.ly/2VfBmjD
  */

  class PicPay{
	 
	 /*
	  *@var type String: $urlCallBack
	  */
	  
	  private $urlCallBack = "https://felipepayment.herokuapp.com";
	 
	  /*
	   *@var type String: $urlReturn
	   */
	  // o link da notificacao 
	  private $urlReturn = "https://felipepayment.herokuapp.com";
	 
	 /*
	  *@Get token: https://bit.ly/2XyAWCy
	  *@var type String: $x_picpay_token
	  */
	  private $x_picpay_token = "8e231c45-dd10-438e-bb2f-ae97900ea9a4";
	  
	  /*
	   *@var type String: $x_seller_token
	   */
	  private $x_seller_token = "cbeb36b9-4f69-4602-8d9e-4a6904e330e4";
	   
	   
	 
	 //Função que faz a requisição
	 public function requestPayment($produto,$cliente){
		
		  $data = array(
		         'referenceId' => $produto->ref,
		         'callbackUrl' => $this->urlCallBack,
		         'returnUrl'   => $this->urlReturn,
		         'value'       => $produto->valor,
		         'buyer'       => [
						  'firstName' => $cliente->nome,
						  'lastName'  => $cliente->sobreNome,
						  'document'  => $cliente->cpf,
						  'email'     => $cliente->email,
						  'phone'     => $cliente->telefone
						],
					);
		 
		 $ch = curl_init('https://appws.picpay.com/ecommerce/public/payments');
		 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		 curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
		 curl_setopt($ch, CURLOPT_HTTPHEADER, array('x-picpay-token: '.$this->x_picpay_token));
		 
		 $res = curl_exec($ch);
		 curl_close($ch);
	     $return = json_decode($res);
		 


		 return $return;
		  		  
	 }
	 
	 
	 
	 // Notificação PicPay
	 public function notificationPayment(){
		 
		$content = trim(file_get_contents("php://input"));
	    $payBody = json_decode($content);
		 
		
		   
		   $referenceId = $payBody->referenceId; 
		 
		   $ch = curl_init('https://appws.picpay.com/ecommerce/public/payments/'.$referenceId.'/status');
		   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		   curl_setopt($ch, CURLOPT_HTTPHEADER, array('x-picpay-token: '.$this->x_picpay_token)); 
		


		   $res = curl_exec($ch);
		   curl_close($ch);
		   $notification = json_decode($res); 
		  		   

		   
		   
		   return $notification;
		   
		 
	 }
	 

	 
	 
  }


  
  
  
?>
