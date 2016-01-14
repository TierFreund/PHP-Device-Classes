<?

    $ip = "192.168.112.254";
    $port = "49000";
    $user = "<user name>";
    $pass = "<password>";

require '_autoload.php';

echo CallList();

function CallList(){
	global $user,$pass;
	$api=new Rpc2Fritzbox('192.168.112.254');
	$api->SetAuth($user,$pass);
	$r=$api->GetCallList();
	$xml=file_get_contents($r);
	return $xml;
}
function Phonebook(){
	global $user,$pass;
	$api=new Rpc2Fritzbox('192.168.112.254');
	$api->SetAuth($user,$pass);
	$r=$api->GetPhonebook(0);
	$xml=file_get_contents($r['NewPhonebookURL']);
	return $xml;
}

?>