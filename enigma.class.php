<?
$c=new EnigmaUpnpDevice('192.168.112.65');
$r=$c->GetServices();
dumpvar($r);


class EnigmaUpnpDevice {

	public function __construct($url){
		$this->url=$url;
	}
	

	function About() {
		$r=$this->CallService('about');
		return $r;
	}
	function DeviceInfo(){
		return $this->CallService('deviceinfo');
	}	
	function LocationInfo(){
		return $this->CallService('getlocations');
	}	
	function CurrentLocation(){
		return $this->CallService('web/getcurrlocation');
	}	
	function GetVolume(){
		return $this->Volume();
	}
	function SetVolume($volume){
		return $this->Volume($volume);
	}
	function UpVolume($volume){
		return $this->Volume('UP');
	}
	function DownVolume($volume){
		return $this->Volume('DOWN');
	}
	function ToggleMute(){
		return $this->Volume('MUTE');
	}
	function Volume($VolumeMode=''){
		if(!$VolumeMode){
			return $this->CallService('vol');
		}else if($VolumeMode=='UP'){
			return $this->CallService('vol','set=up');
		}else if($VolumeMode=='DOWN'){
			return $this->CallService('vol','set=down');
		}else if($VolumeMode=='MUTE'){
			return $this->CallService('vol','set=mute');
		}else return $this->CallService('vol','set=set'.$VolumeMode);
	}
	function GetAudiotracks(){
		return $this->CallService('getaudiotracks');
	}	
	function SetAudiotrack($id){
		return $this->CallService('selectaudiotrack','id='.$id);
	}	
	function GetTimers(){
		return $this->CallService('timerlist');
	}	
	function AddTimer($ServiceRef, $EventId, $DirName){
		$r=$this->CallService('timeraddbyeventid',
			array('sRef'=>$ServiceRef,'eventid'=>$EventId,'dirname'=>$DirName));
		return $r;
	}
	function DeleteTimer($ServiceRef, $begin, $End){
		$r=$this->CallService('timerdelete',array('sRef'=>$ServiceRef,'begin'=>$begin,'end'=>$End));
		return $r;
	}
	function Message($Text, $Type, $TimeOut=10){
		$r=$this->CallService('message',array('text'=>$Text,'type'=>$Type,'timeout'=>$Timeout));
		return $r;
	}
	function MessageAnswer(){
		$r=$this->CallService('messageanswer','getanswer=now');
		return $r;
	}
	function RemoteCommand($CommandId){
		$r=$this->CallService('remotecontrol','command='.$CommandId);
		return $r;
	}
	function GetServices($ServiceRef=''){
		$r=$this->CallService('getservices',$ServiceRef?'ServiceListBrowse='.$ServiceRef:'');
		return $r;
	}
	function GetAllServices(){
		$r=$this->CallService('getallservices');
		return $r;
	}	
	/*
		0, reloading lamedb and Userbouquets
		1, reloading lamedb only
		2, reloading Userbouquets only
	*/
	function ReloadServices($mode=0){
		$r=$this->CallService('servicelistreload','mode='.$mode);
		return $r;
	}
	function Zap($ServiceRef){
		$r=$this->CallService('zap','sRef='-$ServiceRef);
		return $r;
	}
	function Stream($ServiceRef){
		$r=$this->CallService('stream.m3u','ref='.$ServiceRef);
		return $r;
	}
	function CurrentService(){
		$r=$this->CallService('subservices');
		return $r;
	}
	function MovieList(){
		$r=$this->CallService('movielist');
		return $r;
	}	
	/*
		Powerstate NewState =
		0 = Toogle Standby
		1 = Deepstandby
		2 = Reboot
		3 = Restart Enigma2
		4 = Wakeup form Standby
		5 = Standby
	*/	
	function PowerState($NewState=null){
		$r=$this->CallService('powerstate',is_null($NewState)?'':'newstate='.$newstate);
		return $r;
	}	
	function CallService($action, $params=null, $filter=null ){
		if($params){
			if(is_array($params)){
				foreach($params as $k=>$v)$a[]="$k=$v";
				$args='?'.implode('&',$a);
			}else $args='?'.$params;
		}else $args='';	
		$string = file_get_contents("http://{$this->url}/web/$action$args");
		if($filter)
			return $this->Filter($string, $filter);
		else {
			$xml = simplexml_load_string($string);
			if(!$xml)return false;
			if($c=$xml->count()){
				if($c>1)
					return $this->xml2obj($xml,'e2');					
				else
					return $this->xml2obj($xml->Children()[0],'e2');
			} else 
				return 0;
		}
	}
    /***************************************************************************
    /* Funktion : Filter
    /* 
    /*  Benoetigt:
    /*    @subject (string)
    /*    @pattern (string|stringlist|array of strings)
    /*
    /*  Liefert als Ergebnis:
    /*    @result (array|variant) => Array format FilterPattern=>Value
    /*
    /****************************************************************************/
    public function Filter($subject,$pattern){
        $multi=is_array($pattern);
        if(!$multi){
            $pattern=explode(',',$pattern);
            $multi=(count($pattern)>1);
        }	
        foreach($pattern as $pat){
            if(!$pat)continue;
            preg_match('/\<'.$pat.'\>(.+)\<\/'.$pat.'\>/',$subject,$matches);
            if($multi)$n[$pat]=(isSet($matches[1]))?$matches[1]:false;
            else return (isSet($matches[1]))?$matches[1]:false;
        }	
        return $n;
    }
	
	function xml2obj($xml, $removeFromKey='') {
		$numcheck=false;
		$asNummber=false;
		$lastKey='';
		$r=[];
		foreach ($xml->children() as $child){
			$key=$child->getName();
			if($removeFromKey)$key=str_replace($removeFromKey,'',$key);
			if($child->count()>0){
				if(!$numcheck){
					if($lastKey){
						if($key==$lastKey){
							$lastKey=$r[$lastKey];
							$r[$key]=array($lastKey);
							$asNummber=true;
						}
						
						$numcheck=true;
					}else 
						$lastKey=$key;
				}	
				if($asNummber)
					$r[$key][]=$this->xml2obj($child,$removeFromKey);
				else
					$r[$key]=$this->xml2obj($child,$removeFromKey);
			}else 
				$r[$key]=(string)$child;
		}	
		return $r;
	}
}



?>