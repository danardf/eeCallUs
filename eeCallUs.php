<?

    /*
        La fonctionnalité de ce script est de permettre de générer un appel entre deux correspondants via un 
        serveur VoIP Asterisk / Freepbx.
        Il utilise un lien AMI pour que l'eedomus puisse envoyer un ordre.
    */
	error_reporting(0);
	
	/* 
		Init vars
	*/
	$address 		= getArg('ip',$mandatory = true);
	$port 	        = getArg('port',$mandatory = false);
	$user 			= getArg('user',$mandatory = true);
	$secret 		= getArg('secret',$mandatory = true);
	$to 			= getArg('to',$mandatory = true); 
	$message        = utf8_encode(getArg('msg'),$mandatory = false);
	if(empty($message)){
		$call_from	= getArg('call_from',$mandatory = true);
		$driver     = strtolower(getArg('drv'),$mandatory = false);
	}
	else{
		$call_from 	= getArg('call_from',$mandatory = false);
		$driver     = strtolower(getArg('drv'),$mandatory = true);
	}
	$response		= False;
	
    /*
		Vérification de certains arguments
    */

    if (empty($address)){
        $err        = "Il manque d'adresse IP.";
    }
    
    if (empty($port)){
        $port        = "5038";
    }

    if (empty($user)){
        $err        = "Il manque l'utilisateur.";
    }
    
    if (empty($secret)){
        $err        = "Il manque le code secret.";
    }
    
    if (empty($to)){
        $err        = "Il manque le destinataire.";
    }
    
    if (!empty($message) && empty($driver)){
        $err        = "Il manque le driver.";
    }
     
    if(empty($err)){
		/* 
			Initialisation du socket vers le serveur Asterisk
		*/
		$socket 	= socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		if ($socket === false) {
			echo "Erreur socket_create(). Raison : ".socket_strerror(socket_last_error())."\n";
		} 
		
		$result = socket_connect($socket, $address, $port);
		if ($result === false) {
			echo "Erreur socket_connect(). Raison : $result - ".socket_strerror(socket_last_error($socket))."\n";
		} 
		
		$in 		= socket_read($socket, 2048);
		if(strpos($in,"Asterisk Call Manager") === False){
			echo "Serveur Asterisk non reconnu. Mais bon, essayons...\n";
		}
		else{
			echo "Serveur Asterisk reconnu....\n";
		}
		
		/* 
			Login to Asterisk Server through AMI 
		*/
		$login      = "Action: login\r\n";
		$login     .= "Username: ".$user."\r\n";
		$login     .= "Secret: ".$secret."\r\n";
		$login 	   .= "Events: off\r\n\r\n";
		socket_write($socket, $login, strlen($login));
		
		while($response == False){
			$in 	= socket_read($socket, 2048);
			if(!empty($in)){
				$response = True;	
			}
		}
		
		if(strpos($in,"Success") === False && strpos($in,"Authentication accepted") === False){
			echo "Authentification échouée!\n";
			socket_close($socket);
		}
		else{
			echo "Authentification acceptée.\n";
		}

		if(empty($message)){
			/* 
				Lets make a call 
			*/
			$request    = "Action: Originate\r\n";
			$request   .= "Channel: Local/".$call_from."\r\n";
			$request   .= "Context: from-internal\r\n";
			$request   .= "Exten: ".$to."\r\n";
			$request   .= "Priority: 1\r\n";
			$request   .= "Timeout: 30000\r\n";
			$request   .= "Callerid: ".$to."\r\n\r\n";
		
			socket_write($socket, $request, strlen($request));
			$in	    	= socket_read($socket, 2048);
			$in	    	= socket_read($socket, 2048);
			if(strpos($in,"Success") === False && strpos($in,"Originate successfully queued") === False){
				echo "Appel échouée!\n";
			}
			else{
				echo "Appel effectué.\n";
			}        
		}
		else{
			$request    = "Action: MessageSend\r\n";
			$request   .= "To: ".$driver.":".$to."\r\n";
			$request   .= "From: <eedomus>\r\n";
			$request   .= "Body: ".$message."\r\n\r\n";

			socket_write($socket, $request, strlen($request));
			$in	    	= socket_read($socket, 2048);
			$in	    	= socket_read($socket, 2048);
			
			if(strpos($in,"Success") === False && strpos($in,"Message successfully sent") === False){
				echo "Envoi message échouée!\n";
			}
			else{
				echo "Envoi message effectué.\n";
			} 
		}
		
		/* 
			Close socket 
		*/
		socket_close($socket);       
    }
    else{
		/* Il manque un ou plusieurs arguments */
		echo $err;
    }
?>
