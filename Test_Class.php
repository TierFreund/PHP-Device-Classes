<?

    $ip = "192.168.112.51";

require '_autoload.php';
$api=new rpc2sonos($ip);
$api->Play();
sleep(2);
$api->Stop();

?>