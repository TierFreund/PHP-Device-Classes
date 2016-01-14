<?
/*---------------------------------------------------------------------------/
	
File:  
	Desc     : PHP Classes to Control Samsung TV DMR 
	Date     : 2015-03-22T22:26:34+01:00
	Version  : 1.00.45
	Publisher: (c)2015 Xaver Bauer 
	Contact  : x.bauer@tier-freunde.net

Device:
	Device Type  : urn:schemas-upnp-org:device:MediaRenderer:1
	URL 		 : http://192.168.112.60:7676/smp_16_	
	Friendly Name: [TV] Samsung
	Manufacturer : Samsung Electronics
	URL 		 : http://www.samsung.com/sec
	Model        : Samsung TV DMR
	Name 		 : UE55F6400
	Number 		 : AllShare1.0
	URL 		 : http://www.samsung.com/sec
	Serialnumber : 20110517DMR
	UDN          : uuid:0ee6b280-00fa-1000-b849-0c891041f72d

/*--------------------------------------------------------------------------*/
/*##########################################################################/
/*  Class  : SamsungUpnpDevice 
/*  Desc   : Master Class to Controll Device 
/*	Vars   :
/*  protected _SERVICES  : (object) Holder for all Service Classes
/*  protected _DEVICES   : (object) Holder for all Service Classes
/*  protected _IP        : (string) IP Adress from Device
/*  protected _PORT      : (int)    Port from Device
/*##########################################################################*/
class SamsungUpnpDevice {
    protected $_SERVICES=null;
    protected $_DEVICES=null;
    protected $_IP='';
    protected $_PORT=7676;
    /***************************************************************************
    /* Funktion : __construct
    /* 
    /*  Benoetigt:
    /*    @url (string)  Device Url eg. '192.168.112.60:7676'
    /*
    /*  Liefert als Ergebnis: Nichts
    /*
    /****************************************************************************/
    public function __construct($url){
        $p=parse_url($url);
        $this->_IP=(isSet($p['host']))?$p['host']:$url;
        $this->_PORT=(isSet($p['port']))?$p['port']:7676;
        $this->_SERVICES=new stdClass();
        $this->_DEVICES=new stdClass();
        $this->_SERVICES->RenderingControl=new SamsungRenderingControl($this);
        $this->_SERVICES->ConnectionManager=new SamsungConnectionManager($this);
        $this->_SERVICES->AVTransport=new SamsungAVTransport($this);
    }
    /***************************************************************************
    /* Funktion : GetIcon
    /* 
    /*  Benoetigt:
    /*    @IconNr (int)
    /*
    /*  Liefert als Ergebnis: Array mit folgenden Keys
    /*    @width  (int)
    /*    @height (int)
    /*    @url (string)
    /*
    /****************************************************************************/
    function GetIcon($id) {
        switch($id){
            case 0 : return array('width'=>48,'height'=>48,'url'=>'http://192.168.112.60:7676/dmr/icon_SML.jpg');break;
            case 1 : return array('width'=>120,'height'=>120,'url'=>'http://192.168.112.60:7676/dmr/icon_LRG.jpg');break;
            case 2 : return array('width'=>48,'height'=>48,'url'=>'http://192.168.112.60:7676/dmr/icon_SML.png');break;
            case 3 : return array('width'=>120,'height'=>120,'url'=>'http://192.168.112.60:7676/dmr/icon_LRG.png');break;
        }
        return array('width'=>0,'height'=>0,'url'=>'');
    }
    /***************************************************************************
    /* Funktion : IconCount
    /* 
    /*  Benoetigt: Nichts
    /*
    /*  Liefert als Ergebnis:
    /*    @count (int) => The Numbers of Icons Avail
    /*
    /****************************************************************************/
    function IconCount() { return 4;}
    /***************************************************************************
    /* Funktion : Upnp
    /* 
    /*  Benoetigt:
    /*    @url (string)
    /*    @SOAP_service (string)
    /*    @SOAP_action (string)
    /*    @SOAP_arguments (sting) [Optional]
    /*    @XML_filter (string|stringlist|array of strings) [Optional]
    /*
    /*  Liefert als Ergebnis:
    /*    @result (string|array) => The XML Soap Result
    /*
    /****************************************************************************/
    public function Upnp($url,$SOAP_service,$SOAP_action,$SOAP_arguments = '',$XML_filter = ''){
        $POST_xml = '<s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/" s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">';
        $POST_xml .= '<s:Body>';
        $POST_xml .= '<u:'.$SOAP_action.' xmlns:u="'.$SOAP_service.'">';
        $POST_xml .= $SOAP_arguments;
        $POST_xml .= '</u:'.$SOAP_action.'>';
        $POST_xml .= '</s:Body>';
        $POST_xml .= '</s:Envelope>';
        $POST_url = $this->_IP.":".$this->_PORT.$url;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_URL, $POST_url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: text/xml", "SOAPAction: ".$SOAP_service."#".$SOAP_action));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $POST_xml);
        $r = curl_exec($ch);
        curl_close($ch);
        if ($XML_filter != '')
            return $this->Filter($r,$XML_filter);
        else
            return $r;
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
    /***************************************************************************
    /* Funktion : GetServiceNames
    /* 
    /*  Benoetigt: Nichts
    /*
    /*  Liefert als Ergebnis:
    /*    @result (array) => Namen der vorhandenen Services
    /*
    /****************************************************************************/
    public function GetServiceNames(){
        foreach($this->_SERVICES as $fn=>$tmp)if(substr($fn,0,1)!='_')$n[]=$fn;
        foreach($this->_DEVICES as $fn=>$tmp)if(substr($fn,0,1)!='_')$n[]=$fn;
        return $n;
    }
    /***************************************************************************
    /* Funktion : GetServiceFunctionNames
    /* 
    /*  Benoetigt:
    /*    @ServiceName (string)
    /*
    /*  Liefert als Ergebnis:
    /*    @result (array) => Namen der vorhandenen Service Funktionen
    /*
    /****************************************************************************/
    public function GetServiceFunctionNames($ServiceName){
        if(isSet($this->_SERVICES->$ServiceName)){
            $p=&$this->_SERVICES->$ServiceName;
        }else if(isSet($this->_DEVICES->$ServiceName)){
            $p=&$this->_DEVICES->$ServiceName;
        }else throw new Exception('Unbekanner Service-Name '.$ServiceName.' !!!');
        foreach(get_class_methods($p) as $fn)if(substr($fn,0,1)!='_')$n[]=$fn;
        return $n;	
    }
    /***************************************************************************
    /* Funktion : CallService
    /* 
    /*  Benoetigt:
    /*    @ServiceName (string)
    /*    @FunctionName (string)
    /*    @Arguments (string,array) [Optional] Funktions Parameter
    /*
    /*  Liefert als Ergebnis:
    /*    @result (array|variant) => siehe Funktion
    /*
    /****************************************************************************/
    public function CallService($ServiceName, $FunctionName, $Arguments=null){
        if(is_object($ServiceName))$p=$ServiceName;
        else if(isSet($this->_SERVICES->$ServiceName)){
            $p=&$this->_SERVICES->$ServiceName;
        }else if(isSet($this->_DEVICES->$ServiceName)){
            $p=&$this->_DEVICES->$ServiceName;
        }else throw new Exception('Unbekanner Service-Name '.$ServiceName.' !!!');
        if(!method_exists($p,$FunctionName)) throw new Exception('Unbekannter Funktions-Name '.$FunctionName.' !!! Service:'.$ServiceName);
        if(!is_null($Arguments)){
            $a=&$Arguments;
            if (!is_array($a))$a=Array($a);
            switch(count($a)){
                case 1: return $p->$FunctionName($a[0]);break;
                case 2: return $p->$FunctionName($a[0],$a[1]);break;
                case 3: return $p->$FunctionName($a[0],$a[1],$a[2]);break;
                case 4: return $p->$FunctionName($a[0],$a[1],$a[2],$a[3]);break;
                default: return $p->$FunctionName();
            }
        }else return $p->$FunctionName();
    }
    /***************************************************************************
    /* Funktion : __call
    /* 
    /*  Benoetigt:
    /*    @FunctionName (string)
    /*    @arguments (array)
    /*
    /*  Liefert als Ergebnis:
    /*    @result (variant) => siehe aufzurufende Funktion
    /*
    /****************************************************************************/
    public function __call($FunctionName, $arguments){
        if(!$p=$this->_ServiceObjectByFunctionName($FunctionName))
            throw new Exception('Unbekannte Funktion '.$FunctionName.' !!!');
        return $this->CallService($p,$FunctionName, $arguments);
    }
    /***************************************************************************
    /* Funktion : _ServiceObjectByFunctionName
    /* 
    /*  Benoetigt:
    /*    @FunctionName (string)
    /*
    /*  Liefert als Ergebnis:
    /*    @result (function||null) ServiceObject mit der gusuchten Function
    /*
    /****************************************************************************/
    private function _ServiceObjectByFunctionName($FunctionName){
        foreach($this->_SERVICES as $fn=>$tmp)if(method_exists($this->_SERVICES->$fn,$FunctionName)){return $this->_SERVICES->$fn;}
        foreach($this->_DEVICES as $fn=>$tmp)if(method_exists($this->_DEVICES->$fn,$FunctionName)){return $this->_DEVICES->$fn;}
        return false;
    }
    /***************************************************************************
    /* Funktion : sendPacket
    /* 
    /*  Benoetigt:
    /*    @content (string)
    /*
    /*  Liefert als Ergebnis:
    /*    @result (array)
    /*
    /****************************************************************************/
    public function sendPacket( $content ){
        $fp = fsockopen($this->_IP, $this->_PORT, $errno, $errstr, 10);
        if (!$fp)throw new Exception("Error opening socket: ".$errstr." (".$errno.")");
            fputs ($fp,$content);$ret = "";
            while (!feof($fp))$ret.= fgetss($fp,128); // filters xml answer
            fclose($fp);
        if(strpos($ret, "200 OK") === false)throw new Exception("Error sending command: ".$ret);
        foreach(preg_split("/\n/", $ret) as $v)if(trim($v)&&(strpos($v,"200 OK")===false))$array[]=trim($v);
        return $array;
    }
    /***************************************************************************
    /* Funktion : GetBaseUrl
    /* 
    /*  Benoetigt: Nichts
    /*
    /*  Liefert als Ergebnis:
    /*    @result (string)
    /*
    /****************************************************************************/
    public function GetBaseUrl(){ 
        return $this->_IP.':'.$this->_PORT;
    }
}
/*##########################################################################/
/*  Class  : SamsungUpnpClass 
/*  Desc   : Basis Class for Services
/*	Vars   :
/*  protected SERVICE     : (string) Service URN
/*  protected SERVICEURL  : (string) Path to Service Control
/*  protected EVENTURL    : (string) Path to Event Control
/*  protected BASE        : (Object) Points to MasterClass
/*##########################################################################*/
class SamsungUpnpClass {
    protected $SERVICE="";
    protected $SERVICEURL="";
    protected $EVENTURL="";
    protected $BASE=null;
    /***************************************************************************
    /* Funktion : __construct
    /* 
    /*  Benoetigt:
    /*    @BASE (object) Referenz of MasterClass
    /*    @SERVICE (string) [Optional]
    /*    @SERVICEURL (string) [Optional]
    /*    @EVENTURL (string) [Optional]
    /*
    /*  Liefert als Ergebnis: Nichts
    /*
    /****************************************************************************/
    public function __construct($BASE, $SERVICE="", $SERVICEURL="", $EVENTURL=""){
        $this->BASE=$BASE;
        if($SERVICE)$this->SERVICE=$SERVICE;
        if($SERVICEURL)$this->SERVICEURL=$SERVICEURL;
        if($EVENTURL)$this->EVENTURL=$EVENTURL;
    }
    /***************************************************************************
    /* Funktion : RegisterEventCallback
    /* 
    /*  Benoetigt:
    /*    @callback_url (string) Url die bei Ereignissen aufgerufen wird
    /*    @timeout (int) Gueltigkeitsdauer der CallbackUrl
    /*
    /*  Liefert als Ergebnis: Array mit folgenden Keys
    /*    @SID (string)
    /*    @TIMEOUT (int)
    /*    @Server (string)
    /*
    /****************************************************************************/
    public function RegisterEventCallback($callback_url,$timeout=300){
        if(!$this->EVENTURL)return false;	
        $content="SUBSCRIBE {$this->EVENTURL} HTTP/1.1\nHOST: ".$this->BASE->GetBaseUrl()."\nCALLBACK: <$callback_url>\nNT: upnp:event\nTIMEOUT: Second-$timeout\nContent-Length: 0\n\n";
        $a=$this->BASE->sendPacket($content);$res=false;
        if($a)foreach($a as $r){$m=explode(':',$r);if(isSet($m[1])){$b=array_shift($m);$res[$b]=implode(':',$m);}}
        return $res;
    }
    /***************************************************************************
    /* Funktion : UnRegisterEventCallback
    /* 
    /*  Benoetigt:
    /*    @SID (string)
    /*
    /*  Liefert als Ergebnis: Nichts
    /*
    /****************************************************************************/
    public function UnRegisterEventCallback($SID){ 
        if(!$this->EVENTURL)return false;	
        $content="UNSUBSCRIBE {$this->EVENTURL} HTTP/1.1\nHOST: ".$this->BASE->GetBaseUrl()."\nSID: $SID\nContent-Length: 0\n\n";
        return $this->BASE->sendPacket($content);
    }
}
/*##########################################################################*/
/*  Class  : RenderingControl 
/*  Service: urn:schemas-upnp-org:service:RenderingControl:1
/*	     Id: urn:upnp-org:serviceId:RenderingControl 
/*##########################################################################*/
class SamsungRenderingControl extends SamsungUpnpClass {
    protected $SERVICE='urn:schemas-upnp-org:service:RenderingControl:1';
    protected $SERVICEURL='/smp_18_';
    protected $EVENTURL='/smp_19_';
    /***************************************************************************
    /* Funktion : ListPresets
    /* 
    /*  Benoetigt:
    /*          @InstanceID (ui4) Vorgabe = 0 
    /*
    /*  Liefert als Ergebnis:
    /*          @CurrentPresetNameList (string) 
    /*
    /****************************************************************************/
    public function ListPresets($InstanceID=0){
        $args="<InstanceID>$InstanceID</InstanceID>";
        $filter="CurrentPresetNameList";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'ListPresets',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : SelectPreset
    /* 
    /*  Benoetigt:
    /*          @InstanceID (ui4) Vorgabe = 0 
    /*          @PresetName (string)  => Auswahl: FactoryDefaults
    /*
    /*  Liefert als Ergebnis: Nichts
    /*
    /****************************************************************************/
    public function SelectPreset($PresetName, $InstanceID=0){
        $args="<InstanceID>$InstanceID</InstanceID><PresetName>$PresetName</PresetName>";
        $filter="";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'SelectPreset',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : GetMute
    /* 
    /*  Benoetigt:
    /*          @InstanceID (ui4) Vorgabe = 0 
    /*          @Channel (string) Vorgabe = 'Master'  => Auswahl: Master
    /*
    /*  Liefert als Ergebnis:
    /*          @CurrentMute (boolean) 
    /*
    /****************************************************************************/
    public function GetMute($InstanceID=0, $Channel='Master'){
        $args="<InstanceID>$InstanceID</InstanceID><Channel>$Channel</Channel>";
        $filter="CurrentMute";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'GetMute',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : SetMute
    /* 
    /*  Benoetigt:
    /*          @InstanceID (ui4) Vorgabe = 0 
    /*          @Channel (string) Vorgabe = 'Master'  => Auswahl: Master
    /*          @DesiredMute (boolean) 
    /*
    /*  Liefert als Ergebnis: Nichts
    /*
    /****************************************************************************/
    public function SetMute($DesiredMute, $InstanceID=0, $Channel='Master'){
        $args="<InstanceID>$InstanceID</InstanceID><Channel>$Channel</Channel><DesiredMute>$DesiredMute</DesiredMute>";
        $filter="";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'SetMute',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : GetVolume
    /* 
    /*  Benoetigt:
    /*          @InstanceID (ui4) Vorgabe = 0 
    /*          @Channel (string) Vorgabe = 'Master'  => Auswahl: Master
    /*
    /*  Liefert als Ergebnis:
    /*          @CurrentVolume (ui2) 
    /*
    /****************************************************************************/
    public function GetVolume($InstanceID=0, $Channel='Master'){
        $args="<InstanceID>$InstanceID</InstanceID><Channel>$Channel</Channel>";
        $filter="CurrentVolume";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'GetVolume',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : SetVolume
    /* 
    /*  Benoetigt:
    /*          @InstanceID (ui4) Vorgabe = 0 
    /*          @Channel (string) Vorgabe = 'Master'  => Auswahl: Master
    /*          @DesiredVolume (ui2) 
    /*
    /*  Liefert als Ergebnis: Nichts
    /*
    /****************************************************************************/
    public function SetVolume($DesiredVolume, $InstanceID=0, $Channel='Master'){
        $args="<InstanceID>$InstanceID</InstanceID><Channel>$Channel</Channel><DesiredVolume>$DesiredVolume</DesiredVolume>";
        $filter="";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'SetVolume',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : GetBrightness
    /* 
    /*  Benoetigt:
    /*          @InstanceID (ui4) Vorgabe = 0 
    /*
    /*  Liefert als Ergebnis:
    /*          @CurrentBrightness (ui2) 
    /*
    /****************************************************************************/
    public function GetBrightness($InstanceID=0){
        $args="<InstanceID>$InstanceID</InstanceID>";
        $filter="CurrentBrightness";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'GetBrightness',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : SetBrightness
    /* 
    /*  Benoetigt:
    /*          @InstanceID (ui4) Vorgabe = 0 
    /*          @DesiredBrightness (ui2) 
    /*
    /*  Liefert als Ergebnis: Nichts
    /*
    /****************************************************************************/
    public function SetBrightness($DesiredBrightness, $InstanceID=0){
        $args="<InstanceID>$InstanceID</InstanceID><DesiredBrightness>$DesiredBrightness</DesiredBrightness>";
        $filter="";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'SetBrightness',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : GetContrast
    /* 
    /*  Benoetigt:
    /*          @InstanceID (ui4) Vorgabe = 0 
    /*
    /*  Liefert als Ergebnis:
    /*          @CurrentContrast (ui2) 
    /*
    /****************************************************************************/
    public function GetContrast($InstanceID=0){
        $args="<InstanceID>$InstanceID</InstanceID>";
        $filter="CurrentContrast";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'GetContrast',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : SetContrast
    /* 
    /*  Benoetigt:
    /*          @InstanceID (ui4) Vorgabe = 0 
    /*          @DesiredContrast (ui2) 
    /*
    /*  Liefert als Ergebnis: Nichts
    /*
    /****************************************************************************/
    public function SetContrast($DesiredContrast, $InstanceID=0){
        $args="<InstanceID>$InstanceID</InstanceID><DesiredContrast>$DesiredContrast</DesiredContrast>";
        $filter="";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'SetContrast',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : GetSharpness
    /* 
    /*  Benoetigt:
    /*          @InstanceID (ui4) Vorgabe = 0 
    /*
    /*  Liefert als Ergebnis:
    /*          @CurrentSharpness (ui2) 
    /*
    /****************************************************************************/
    public function GetSharpness($InstanceID=0){
        $args="<InstanceID>$InstanceID</InstanceID>";
        $filter="CurrentSharpness";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'GetSharpness',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : SetSharpness
    /* 
    /*  Benoetigt:
    /*          @InstanceID (ui4) Vorgabe = 0 
    /*          @DesiredSharpness (ui2) 
    /*
    /*  Liefert als Ergebnis: Nichts
    /*
    /****************************************************************************/
    public function SetSharpness($DesiredSharpness, $InstanceID=0){
        $args="<InstanceID>$InstanceID</InstanceID><DesiredSharpness>$DesiredSharpness</DesiredSharpness>";
        $filter="";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'SetSharpness',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : X_UpdateAudioSelection
    /* 
    /*  Benoetigt:
    /*          @InstanceID (ui4) Vorgabe = 0 
    /*          @AudioPID (ui2) 
    /*          @AudioEncoding (string) 
    /*
    /*  Liefert als Ergebnis: Nichts
    /*
    /****************************************************************************/
    public function X_UpdateAudioSelection($AudioPID, $AudioEncoding, $InstanceID=0){
        $args="<InstanceID>$InstanceID</InstanceID><AudioPID>$AudioPID</AudioPID><AudioEncoding>$AudioEncoding</AudioEncoding>";
        $filter="";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'X_UpdateAudioSelection',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : X_GetAudioSelection
    /* 
    /*  Benoetigt:
    /*          @InstanceID (ui4) Vorgabe = 0 
    /*
    /*  Liefert als Ergebnis: Array mit folgenden Keys
    /*          @AudioPID (ui2) 
    /*          @AudioEncoding (string) 
    /*
    /****************************************************************************/
    public function X_GetAudioSelection($InstanceID=0){
        $args="<InstanceID>$InstanceID</InstanceID>";
        $filter="AudioPID,AudioEncoding";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'X_GetAudioSelection',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : X_UpdateVideoSelection
    /* 
    /*  Benoetigt:
    /*          @InstanceID (ui4) Vorgabe = 0 
    /*          @VideoPID (ui2) 
    /*          @VideoEncoding (string) 
    /*
    /*  Liefert als Ergebnis: Nichts
    /*
    /****************************************************************************/
    public function X_UpdateVideoSelection($VideoPID, $VideoEncoding, $InstanceID=0){
        $args="<InstanceID>$InstanceID</InstanceID><VideoPID>$VideoPID</VideoPID><VideoEncoding>$VideoEncoding</VideoEncoding>";
        $filter="";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'X_UpdateVideoSelection',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : X_GetVideoSelection
    /* 
    /*  Benoetigt:
    /*          @InstanceID (ui4) Vorgabe = 0 
    /*
    /*  Liefert als Ergebnis: Array mit folgenden Keys
    /*          @VideoPID (ui2) 
    /*          @VideoEncoding (string) 
    /*
    /****************************************************************************/
    public function X_GetVideoSelection($InstanceID=0){
        $args="<InstanceID>$InstanceID</InstanceID>";
        $filter="VideoPID,VideoEncoding";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'X_GetVideoSelection',$args,$filter);
    }}

/*##########################################################################*/
/*  Class  : ConnectionManager 
/*  Service: urn:schemas-upnp-org:service:ConnectionManager:1
/*	     Id: urn:upnp-org:serviceId:ConnectionManager 
/*##########################################################################*/
class SamsungConnectionManager extends SamsungUpnpClass {
    protected $SERVICE='urn:schemas-upnp-org:service:ConnectionManager:1';
    protected $SERVICEURL='/smp_21_';
    protected $EVENTURL='/smp_22_';
    /***************************************************************************
    /* Funktion : GetCurrentConnectionInfo
    /* 
    /*  Benoetigt:
    /*          @ConnectionID (i4) 
    /*
    /*  Liefert als Ergebnis: Array mit folgenden Keys
    /*          @RcsID (i4) 
    /*          @AVTransportID (i4) 
    /*          @ProtocolInfo (string) 
    /*          @PeerConnectionManager (string) 
    /*          @PeerConnectionID (i4) 
    /*          @Direction (string)  => Auswahl: Input|Output
    /*          @Status (string)  => Auswahl: OK|ContentFormatMismatch|InsufficientBandwidth|UnreliableChannel|Unknown
    /*
    /****************************************************************************/
    public function GetCurrentConnectionInfo($ConnectionID){
        $args="<ConnectionID>$ConnectionID</ConnectionID>";
        $filter="RcsID,AVTransportID,ProtocolInfo,PeerConnectionManager,PeerConnectionID,Direction,Status";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'GetCurrentConnectionInfo',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : GetProtocolInfo
    /* 
    /*  Benoetigt: Nichts
    /*
    /*  Liefert als Ergebnis: Array mit folgenden Keys
    /*          @Source (string) 
    /*          @Sink (string) 
    /*
    /****************************************************************************/
    public function GetProtocolInfo(){
        $args="";
        $filter="Source,Sink";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'GetProtocolInfo',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : GetCurrentConnectionIDs
    /* 
    /*  Benoetigt: Nichts
    /*
    /*  Liefert als Ergebnis:
    /*          @ConnectionIDs (string) 
    /*
    /****************************************************************************/
    public function GetCurrentConnectionIDs(){
        $args="";
        $filter="ConnectionIDs";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'GetCurrentConnectionIDs',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : PrepareForConnection
    /* 
    /*  Benoetigt:
    /*          @RemoteProtocolInfo (string) 
    /*          @PeerConnectionManager (string) 
    /*          @PeerConnectionID (i4) 
    /*          @Direction (string)  => Auswahl: Input|Output
    /*
    /*  Liefert als Ergebnis: Array mit folgenden Keys
    /*          @ConnectionID (i4) 
    /*          @AVTransportID (i4) 
    /*          @RcsID (i4) 
    /*
    /****************************************************************************/
    public function PrepareForConnection($RemoteProtocolInfo, $PeerConnectionManager, $PeerConnectionID, $Direction){
        $args="<RemoteProtocolInfo>$RemoteProtocolInfo</RemoteProtocolInfo><PeerConnectionManager>$PeerConnectionManager</PeerConnectionManager><PeerConnectionID>$PeerConnectionID</PeerConnectionID><Direction>$Direction</Direction>";
        $filter="ConnectionID,AVTransportID,RcsID";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'PrepareForConnection',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : ConnectionComplete
    /* 
    /*  Benoetigt:
    /*          @ConnectionID (i4) 
    /*
    /*  Liefert als Ergebnis: Nichts
    /*
    /****************************************************************************/
    public function ConnectionComplete($ConnectionID){
        $args="<ConnectionID>$ConnectionID</ConnectionID>";
        $filter="";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'ConnectionComplete',$args,$filter);
    }}

/*##########################################################################*/
/*  Class  : AVTransport 
/*  Service: urn:schemas-upnp-org:service:AVTransport:1
/*	     Id: urn:upnp-org:serviceId:AVTransport 
/*##########################################################################*/
class SamsungAVTransport extends SamsungUpnpClass {
    protected $SERVICE='urn:schemas-upnp-org:service:AVTransport:1';
    protected $SERVICEURL='/smp_24_';
    protected $EVENTURL='/smp_25_';
    /***************************************************************************
    /* Funktion : Play
    /* 
    /*  Benoetigt:
    /*          @InstanceID (ui4) Vorgabe = 0 
    /*          @Speed (string) Vorgabe = 1 
    /*
    /*  Liefert als Ergebnis: Nichts
    /*
    /****************************************************************************/
    public function Play($InstanceID=0, $Speed=1){
        $args="<InstanceID>$InstanceID</InstanceID><Speed>$Speed</Speed>";
        $filter="";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'Play',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : Stop
    /* 
    /*  Benoetigt:
    /*          @InstanceID (ui4) Vorgabe = 0 
    /*
    /*  Liefert als Ergebnis: Nichts
    /*
    /****************************************************************************/
    public function Stop($InstanceID=0){
        $args="<InstanceID>$InstanceID</InstanceID>";
        $filter="";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'Stop',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : Next
    /* 
    /*  Benoetigt:
    /*          @InstanceID (ui4) Vorgabe = 0 
    /*
    /*  Liefert als Ergebnis: Nichts
    /*
    /****************************************************************************/
    public function Next($InstanceID=0){
        $args="<InstanceID>$InstanceID</InstanceID>";
        $filter="";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'Next',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : Previous
    /* 
    /*  Benoetigt:
    /*          @InstanceID (ui4) Vorgabe = 0 
    /*
    /*  Liefert als Ergebnis: Nichts
    /*
    /****************************************************************************/
    public function Previous($InstanceID=0){
        $args="<InstanceID>$InstanceID</InstanceID>";
        $filter="";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'Previous',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : SetPlayMode
    /* 
    /*  Benoetigt:
    /*          @InstanceID (ui4) Vorgabe = 0 
    /*          @NewPlayMode (string)  => Auswahl: NORMAL
    /*
    /*  Liefert als Ergebnis: Nichts
    /*
    /****************************************************************************/
    public function SetPlayMode($NewPlayMode, $InstanceID=0){
        $args="<InstanceID>$InstanceID</InstanceID><NewPlayMode>$NewPlayMode</NewPlayMode>";
        $filter="";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'SetPlayMode',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : GetMediaInfo
    /* 
    /*  Benoetigt:
    /*          @InstanceID (ui4) Vorgabe = 0 
    /*
    /*  Liefert als Ergebnis: Array mit folgenden Keys
    /*          @NrTracks (ui4) 
    /*          @MediaDuration (string) 
    /*          @CurrentURI (string) 
    /*          @CurrentURIMetaData (string) 
    /*          @NextURI (string) 
    /*          @NextURIMetaData (string) 
    /*          @PlayMedium (string)  => Auswahl: NONE|NETWORK
    /*          @RecordMedium (string)  => Auswahl: NOT_IMPLEMENTED
    /*          @WriteStatus (string)  => Auswahl: NOT_IMPLEMENTED
    /*
    /****************************************************************************/
    public function GetMediaInfo($InstanceID=0){
        $args="<InstanceID>$InstanceID</InstanceID>";
        $filter="NrTracks,MediaDuration,CurrentURI,CurrentURIMetaData,NextURI,NextURIMetaData,PlayMedium,RecordMedium,WriteStatus";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'GetMediaInfo',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : GetDeviceCapabilities
    /* 
    /*  Benoetigt:
    /*          @InstanceID (ui4) Vorgabe = 0 
    /*
    /*  Liefert als Ergebnis: Array mit folgenden Keys
    /*          @PlayMedia (string) 
    /*          @RecMedia (string) 
    /*          @RecQualityModes (string) 
    /*
    /****************************************************************************/
    public function GetDeviceCapabilities($InstanceID=0){
        $args="<InstanceID>$InstanceID</InstanceID>";
        $filter="PlayMedia,RecMedia,RecQualityModes";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'GetDeviceCapabilities',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : SetAVTransportURI
    /* 
    /*  Benoetigt:
    /*          @InstanceID (ui4) Vorgabe = 0 
    /*          @CurrentURI (string) 
    /*          @CurrentURIMetaData (string) 
    /*
    /*  Liefert als Ergebnis: Nichts
    /*
    /****************************************************************************/
    public function SetAVTransportURI($CurrentURI, $CurrentURIMetaData, $InstanceID=0){
        $args="<InstanceID>$InstanceID</InstanceID><CurrentURI>$CurrentURI</CurrentURI><CurrentURIMetaData>$CurrentURIMetaData</CurrentURIMetaData>";
        $filter="";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'SetAVTransportURI',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : SetNextAVTransportURI
    /* 
    /*  Benoetigt:
    /*          @InstanceID (ui4) Vorgabe = 0 
    /*          @NextURI (string) 
    /*          @NextURIMetaData (string) 
    /*
    /*  Liefert als Ergebnis: Nichts
    /*
    /****************************************************************************/
    public function SetNextAVTransportURI($NextURI, $NextURIMetaData, $InstanceID=0){
        $args="<InstanceID>$InstanceID</InstanceID><NextURI>$NextURI</NextURI><NextURIMetaData>$NextURIMetaData</NextURIMetaData>";
        $filter="";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'SetNextAVTransportURI',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : GetTransportSettings
    /* 
    /*  Benoetigt:
    /*          @InstanceID (ui4) Vorgabe = 0 
    /*
    /*  Liefert als Ergebnis: Array mit folgenden Keys
    /*          @PlayMode (string)  => Auswahl: NORMAL
    /*          @RecQualityMode (string)  => Auswahl: NOT_IMPLEMENTED
    /*
    /****************************************************************************/
    public function GetTransportSettings($InstanceID=0){
        $args="<InstanceID>$InstanceID</InstanceID>";
        $filter="PlayMode,RecQualityMode";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'GetTransportSettings',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : GetTransportInfo
    /* 
    /*  Benoetigt:
    /*          @InstanceID (ui4) Vorgabe = 0 
    /*
    /*  Liefert als Ergebnis: Array mit folgenden Keys
    /*          @CurrentTransportState (string)  => Auswahl: STOPPED|PAUSED_PLAYBACK|PLAYING|TRANSITIONING|NO_MEDIA_PRESENT
    /*          @CurrentTransportStatus (string)  => Auswahl: OK|ERROR_OCCURRED
    /*          @CurrentSpeed (string) 
    /*
    /****************************************************************************/
    public function GetTransportInfo($InstanceID=0){
        $args="<InstanceID>$InstanceID</InstanceID>";
        $filter="CurrentTransportState,CurrentTransportStatus,CurrentSpeed";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'GetTransportInfo',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : Pause
    /* 
    /*  Benoetigt:
    /*          @InstanceID (ui4) Vorgabe = 0 
    /*
    /*  Liefert als Ergebnis: Nichts
    /*
    /****************************************************************************/
    public function Pause($InstanceID=0){
        $args="<InstanceID>$InstanceID</InstanceID>";
        $filter="";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'Pause',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : Seek
    /* 
    /*  Benoetigt:
    /*          @InstanceID (ui4) Vorgabe = 0 
    /*          @Unit (string)  => Auswahl: TRACK_NR|REL_TIME|ABS_TIME|ABS_COUNT|REL_COUNT|X_DLNA_REL_BYTE|FRAME
    /*          @Target (string) 
    /*
    /*  Liefert als Ergebnis: Nichts
    /*
    /****************************************************************************/
    public function Seek($Unit, $Target, $InstanceID=0){
        $args="<InstanceID>$InstanceID</InstanceID><Unit>$Unit</Unit><Target>$Target</Target>";
        $filter="";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'Seek',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : GetPositionInfo
    /* 
    /*  Benoetigt:
    /*          @InstanceID (ui4) Vorgabe = 0 
    /*
    /*  Liefert als Ergebnis: Array mit folgenden Keys
    /*          @Track (ui4) 
    /*          @TrackDuration (string) 
    /*          @TrackMetaData (string) 
    /*          @TrackURI (string) 
    /*          @RelTime (string) 
    /*          @AbsTime (string) 
    /*          @RelCount (i4) 
    /*          @AbsCount (i4) 
    /*
    /****************************************************************************/
    public function GetPositionInfo($InstanceID=0){
        $args="<InstanceID>$InstanceID</InstanceID>";
        $filter="Track,TrackDuration,TrackMetaData,TrackURI,RelTime,AbsTime,RelCount,AbsCount";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'GetPositionInfo',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : GetCurrentTransportActions
    /* 
    /*  Benoetigt:
    /*          @InstanceID (ui4) Vorgabe = 0 
    /*
    /*  Liefert als Ergebnis:
    /*          @Actions (string) 
    /*
    /****************************************************************************/
    public function GetCurrentTransportActions($InstanceID=0){
        $args="<InstanceID>$InstanceID</InstanceID>";
        $filter="Actions";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'GetCurrentTransportActions',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : X_DLNA_GetBytePositionInfo
    /* 
    /*  Benoetigt:
    /*          @InstanceID (ui4) Vorgabe = 0 
    /*
    /*  Liefert als Ergebnis: Array mit folgenden Keys
    /*          @TrackSize (string) 
    /*          @RelByte (string) 
    /*          @AbsByte (string) 
    /*
    /****************************************************************************/
    public function X_DLNA_GetBytePositionInfo($InstanceID=0){
        $args="<InstanceID>$InstanceID</InstanceID>";
        $filter="TrackSize,RelByte,AbsByte";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'X_DLNA_GetBytePositionInfo',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : X_GetStoppedReason
    /* 
    /*  Benoetigt:
    /*          @InstanceID (ui4) Vorgabe = 0 
    /*
    /*  Liefert als Ergebnis: Array mit folgenden Keys
    /*          @StoppedReason (string) 
    /*          @StoppedReasonData (string) 
    /*
    /****************************************************************************/
    public function X_GetStoppedReason($InstanceID=0){
        $args="<InstanceID>$InstanceID</InstanceID>";
        $filter="StoppedReason,StoppedReasonData";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'X_GetStoppedReason',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : X_SetAutoSlideShowMode
    /* 
    /*  Benoetigt:
    /*          @InstanceID (ui4) Vorgabe = 0 
    /*          @AutoSlideShowMode (string)  => Auswahl: ON|OFF
    /*
    /*  Liefert als Ergebnis: Nichts
    /*
    /****************************************************************************/
    public function X_SetAutoSlideShowMode($AutoSlideShowMode, $InstanceID=0){
        $args="<InstanceID>$InstanceID</InstanceID><AutoSlideShowMode>$AutoSlideShowMode</AutoSlideShowMode>";
        $filter="";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'X_SetAutoSlideShowMode',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : X_SetSlideShowEffectHint
    /* 
    /*  Benoetigt:
    /*          @InstanceID (ui4) Vorgabe = 0 
    /*          @SlideShowEffectHint (string)  => Auswahl: ON|OFF
    /*
    /*  Liefert als Ergebnis: Nichts
    /*
    /****************************************************************************/
    public function X_SetSlideShowEffectHint($SlideShowEffectHint, $InstanceID=0){
        $args="<InstanceID>$InstanceID</InstanceID><SlideShowEffectHint>$SlideShowEffectHint</SlideShowEffectHint>";
        $filter="";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'X_SetSlideShowEffectHint',$args,$filter);
    }}

?>