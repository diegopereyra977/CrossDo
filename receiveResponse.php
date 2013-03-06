

<?php

require_once("libs.php");


$request = array();
$response = array();
//$sessionAttr = SessionAttr=>getInstance();
$ses = SessionAttr::getInstance();
$sessionAttr = array();
$sessionAttr = $ses->getData();
$userLoggedData = array();
Fb::log($ses->getData());







/**
 * Obtiene el request.
 */
foreach($_GET as $indice=>$valor){
	$request[$indice]= $valor;  
}

/**
 * Chequea el request y decide el tipo de pedido.
 */
if($request['password'] && $request['logId']){
	$response['sessionId'] = login($request);
} 

if($request['sesId']){
	
	If($request['sesId']){
		
		If($sessionAttr[$request['sesId']]){
			
			$latency = $sessionAttr[$request['sesId']]['latency'];
			
			if(restaHoras($latency,date("H:i"))> date("H:i",strtotime("00:05"))){
				
				Fb::log("session time out");
				unset($sessionAttr[$request['sesId']]);
				$ses = SessionAttr::getInstance();
				$ses->setData($sessionAttr);
				$response["error"] = "session timeout";
			}else{
				Fb::log("request manager");
				$sessionAttr[$request['sesId']]['latency'] = date("H:i");
				$ses->setData($sessionAttr);
			}
		}
	}
}

/**
 * Loguea el usuario y le devuelve el session id.
 * Si el usuario no coincide devuelve "".
 * @param <Object> $request 
 * @return <String>
 */
function login($request){
	$sessionId = "";
	$logId = $request['logId'];
	$pass = decrypt($request['password']);
	
	if($logId = "Diego" && $pass = "Uruguay2012"){
		$sessionId = rand(1000000000,9999999999);
		//Seteo de la session
		$userLoggedData['logId'] = $logId;
		$userLoggedData['latency'] = date("H:i");
		
		$sessionAttr[$sessionId] = $userLoggedData;
		$ses = SessionAttr::getInstance();
		$ses->setData($sessionAttr);
	}
	return $sessionId;
}

function restaHoras($horaIni, $horaFin){

	return (date("H:i", strtotime("00:00") + strtotime($horaFin) - strtotime($horaIni) ));

}

//Verifica en los atributos de session que sessionId sea el correcto
function checkSession($sessionId){
	
}

// devuelve el caracter segun el numero
function charFromCode($number){
	$codChars = " abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNSOPQRSTUVWXYZ0123456789";
	while($number > 63){
		$number = $number - 64;
	}
	
	return substr($codChars, $number, 1);
}

//devuelve el numero segun el caracter
function codeFromChar($cha){
	$codChars = " abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNSOPQRSTUVWXYZ0123456789";
	
	return strpos($codChars,$cha);  
	
}

//desencripta el string
function decrypt($str){
	
	$key = 'palabra clave'; 
	$result = '';
	$char = '';
	for($i=0; $i<strlen($str);$i++){
		$char = substr($str, $i, 1);
		$keychar = substr($key, ($i % strlen($key))-1, 1);
		$char = charFromCode(codeFromChar($char) - codeFromChar($keychar));
		$result.=$char;
	}
	
	return $result;
}



//verifica si el callback existe y si no usa "callback" por defecto.
if($request['callback']){
	$response = "try{" . $request['callback'] . "(". json_encode($response) . ");} catch(miError) { console.log('Callback not found. >> '+ miError + ' The function called " .$request['callback']. " not exist')}"; 
}else{
	$response = "try{callback(" . json_encode($response) . ");} catch(miError) { console.log('Callback not found ' + miError)}";
}
//crea el response
print $response;

?>
