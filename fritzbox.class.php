<?
/*---------------------------------------------------------------------------/
	
File:  
	Desc     : PHP Classes to Control FRITZ!Box Fon WLAN 7390 
	Date     : 2015-01-30T13:00:17+01:00
	Version  : 1.00.45
	Publisher: (c)2015 Xaver Bauer 
	Contact  : x.bauer@tier-freunde.net

Device:
	Device Type  : urn:schemas-upnp-org:device:InternetGatewayDevice:1
	URL 		 : http://fritz.box:49000/igddesc.xml	
	Friendly Name: gateway
	Manufacturer : AVM Berlin
	URL 		 : http://www.avm.de
	Model        : FRITZ!Box Fon WLAN 7390
	Name 		 : FRITZ!Box Fon WLAN 7390
	Number 		 : avm
	URL 		 : http://www.avm.de
	Serialnumber : 

/*--------------------------------------------------------------------------*/
/*##########################################################################/
/*  Class  : FritzBoxUpnpDevice 
/*  Desc   : Master Class to Controll Device 
/*	Vars   :
/*  protected _SERVICES  : (object) Holder for all Service Classes
/*  protected _DEVICES   : (object) Holder for all Service Classes
/*  protected _IP        : (string) IP Adress from Device
/*  protected _PORT      : (int)    Port from Device
/*##########################################################################*/
class FritzBoxUpnpDevice {
    protected $_SERVICES=null;
    protected $_DEVICES=null;
    protected $_IP='';
    protected $_PORT=1400;
    /***************************************************************************
    /* Funktion : __construct
    /* 
    /*  Benoetigt:
    /*    @url (string)  Device Url eg. '192.168.1.1:1400'
    /*
    /*  Liefert als Ergebnis: Nichts
    /*
    /****************************************************************************/
    public function __construct($url){
        $p=parse_url($url);
        $this->_IP=(isSet($p['host']))?$p['host']:$url;
        $this->_PORT=(isSet($p['port']))?$p['port']:1400;
        $this->_SERVICES=new stdClass();
        $this->_DEVICES=new stdClass();
        $this->_SERVICES->Any=new FritzBoxAny($this);
        $this->_DEVICES->WANCommonInterfaceConfig=new FritzBoxWANCommonInterfaceConfig($this);
        $this->_DEVICES->WANDSLLinkConfig=new FritzBoxWANDSLLinkConfig($this);
        $this->_DEVICES->WANIPConnection=new FritzBoxWANIPConnection($this);
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
            case 0 : return array('width'=>118,'height'=>119,'url'=>'http://fritz.box:49000/ligd.gif');break;
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
    function IconCount() { return 1;}
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
                case 5: return $p->$FunctionName($a[0],$a[1],$a[2],$a[3],$a[4]);break;
                case 6: return $p->$FunctionName($a[0],$a[1],$a[2],$a[3],$a[4],$a[5]);break;
                case 7: return $p->$FunctionName($a[0],$a[1],$a[2],$a[3],$a[4],$a[5],$a[6]);break;
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
/*  Class  : FritzBoxUpnpClass 
/*  Desc   : Basis Class for Services
/*	Vars   :
/*  protected SERVICE     : (string) Service URN
/*  protected SERVICEURL  : (string) Path to Service Control
/*  protected EVENTURL    : (string) Path to Event Control
/*  protected BASE        : (Object) Points to MasterClass
/*##########################################################################*/
class FritzBoxUpnpClass {
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
/*  Class  : Any 
/*  Service: urn:schemas-any-com:service:Any:1
/*	     Id: urn:any-com:serviceId:any1 
/*##########################################################################*/
class FritzBoxAny extends FritzBoxUpnpClass {
    protected $SERVICE='urn:schemas-any-com:service:Any:1';
    protected $SERVICEURL='/igdupnp/control/any';
    protected $EVENTURL='/igdupnp/control/any';
}

/*##########################################################################*/
/*  Class  : WANCommonInterfaceConfig 
/*  Service: urn:schemas-upnp-org:service:WANCommonInterfaceConfig:1
/*	     Id: urn:upnp-org:serviceId:WANCommonIFC1 
/*##########################################################################*/
class FritzBoxWANCommonInterfaceConfig extends FritzBoxUpnpClass {
    protected $SERVICE='urn:schemas-upnp-org:service:WANCommonInterfaceConfig:1';
    protected $SERVICEURL='/igdupnp/control/WANCommonIFC1';
    protected $EVENTURL='/igdupnp/control/WANCommonIFC1';
    /***************************************************************************
    /* Funktion : GetCommonLinkProperties
    /* 
    /*  Benoetigt: Nichts
    /*
    /*  Liefert als Ergebnis: Array mit folgenden Keys
    /*          @NewWANAccessType (string)  => Auswahl: DSL|POTS|Cable|Ethernet|Other
    /*          @NewLayer1UpstreamMaxBitRate (ui4) 
    /*          @NewLayer1DownstreamMaxBitRate (ui4) 
    /*          @NewPhysicalLinkStatus (string)  => Auswahl: Up|Down|Initializing|Unavailable
    /*
    /****************************************************************************/
    public function GetCommonLinkProperties(){
        $args="";
        $filter="NewWANAccessType,NewLayer1UpstreamMaxBitRate,NewLayer1DownstreamMaxBitRate,NewPhysicalLinkStatus";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'GetCommonLinkProperties',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : GetTotalBytesSent
    /* 
    /*  Benoetigt: Nichts
    /*
    /*  Liefert als Ergebnis:
    /*          @NewTotalBytesSent (ui4) 
    /*
    /****************************************************************************/
    public function GetTotalBytesSent(){
        $args="";
        $filter="NewTotalBytesSent";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'GetTotalBytesSent',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : GetTotalBytesReceived
    /* 
    /*  Benoetigt: Nichts
    /*
    /*  Liefert als Ergebnis:
    /*          @NewTotalBytesReceived (ui4) 
    /*
    /****************************************************************************/
    public function GetTotalBytesReceived(){
        $args="";
        $filter="NewTotalBytesReceived";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'GetTotalBytesReceived',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : GetTotalPacketsSent
    /* 
    /*  Benoetigt: Nichts
    /*
    /*  Liefert als Ergebnis:
    /*          @NewTotalPacketsSent (ui4) 
    /*
    /****************************************************************************/
    public function GetTotalPacketsSent(){
        $args="";
        $filter="NewTotalPacketsSent";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'GetTotalPacketsSent',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : GetTotalPacketsReceived
    /* 
    /*  Benoetigt: Nichts
    /*
    /*  Liefert als Ergebnis:
    /*          @NewTotalPacketsReceived (ui4) 
    /*
    /****************************************************************************/
    public function GetTotalPacketsReceived(){
        $args="";
        $filter="NewTotalPacketsReceived";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'GetTotalPacketsReceived',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : GetAddonInfos
    /* 
    /*  Benoetigt: Nichts
    /*
    /*  Liefert als Ergebnis: Array mit folgenden Keys
    /*          @NewByteSendRate (ui4) 
    /*          @NewByteReceiveRate (ui4) 
    /*          @NewPacketSendRate (ui4) 
    /*          @NewPacketReceiveRate (ui4) 
    /*          @NewTotalBytesSent (ui4) 
    /*          @NewTotalBytesReceived (ui4) 
    /*          @NewAutoDisconnectTime (ui4) 
    /*          @NewIdleDisconnectTime (ui4) 
    /*          @NewDNSServer1 (string) 
    /*          @NewDNSServer2 (string) 
    /*          @NewVoipDNSServer1 (string) 
    /*          @NewVoipDNSServer2 (string) 
    /*          @NewUpnpControlEnabled (boolean) 
    /*          @NewRoutedBridgedModeBoth (ui1) 
    /*
    /****************************************************************************/
    public function GetAddonInfos(){
        $args="";
        $filter="NewByteSendRate,NewByteReceiveRate,NewPacketSendRate,NewPacketReceiveRate,NewTotalBytesSent,NewTotalBytesReceived,NewAutoDisconnectTime,NewIdleDisconnectTime,NewDNSServer1,NewDNSServer2,NewVoipDNSServer1,NewVoipDNSServer2,NewUpnpControlEnabled,NewRoutedBridgedModeBoth";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'GetAddonInfos',$args,$filter);
    }}

/*##########################################################################*/
/*  Class  : WANDSLLinkConfig 
/*  Service: urn:schemas-upnp-org:service:WANDSLLinkConfig:1
/*	     Id: urn:upnp-org:serviceId:WANDSLLinkC1 
/*##########################################################################*/
class FritzBoxWANDSLLinkConfig extends FritzBoxUpnpClass {
    protected $SERVICE='urn:schemas-upnp-org:service:WANDSLLinkConfig:1';
    protected $SERVICEURL='/igdupnp/control/WANDSLLinkC1';
    protected $EVENTURL='/igdupnp/control/WANDSLLinkC1';
    /***************************************************************************
    /* Funktion : SetDSLLinkType
    /* 
    /*  Benoetigt:
    /*          @NewLinkType (string)  => Auswahl: EoA|IPoA|CIP|PPPoA|PPPoE|Unconfigured
    /*
    /*  Liefert als Ergebnis: Nichts
    /*
    /****************************************************************************/
    public function SetDSLLinkType($NewLinkType){
        $args="<NewLinkType>$NewLinkType</NewLinkType>";
        $filter="";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'SetDSLLinkType',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : GetDSLLinkInfo
    /* 
    /*  Benoetigt: Nichts
    /*
    /*  Liefert als Ergebnis: Array mit folgenden Keys
    /*          @NewLinkType (string)  => Auswahl: EoA|IPoA|CIP|PPPoA|PPPoE|Unconfigured
    /*          @NewLinkStatus (string)  => Auswahl: Up|Down|Initializing|Unavailable
    /*
    /****************************************************************************/
    public function GetDSLLinkInfo(){
        $args="";
        $filter="NewLinkType,NewLinkStatus";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'GetDSLLinkInfo',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : GetAutoConfig
    /* 
    /*  Benoetigt: Nichts
    /*
    /*  Liefert als Ergebnis:
    /*          @NewAutoConfig (boolean) 
    /*
    /****************************************************************************/
    public function GetAutoConfig(){
        $args="";
        $filter="NewAutoConfig";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'GetAutoConfig',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : GetModulationType
    /* 
    /*  Benoetigt: Nichts
    /*
    /*  Liefert als Ergebnis:
    /*          @NewModulationType (string)  => Auswahl: ADSL G.lite|G.shdsl|IDSL|HDSL|SDSL|VDSL
    /*
    /****************************************************************************/
    public function GetModulationType(){
        $args="";
        $filter="NewModulationType";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'GetModulationType',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : SetDestinationAddress
    /* 
    /*  Benoetigt:
    /*          @NewDestinationAddress (string) 
    /*
    /*  Liefert als Ergebnis: Nichts
    /*
    /****************************************************************************/
    public function SetDestinationAddress($NewDestinationAddress){
        $args="<NewDestinationAddress>$NewDestinationAddress</NewDestinationAddress>";
        $filter="";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'SetDestinationAddress',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : GetDestinationAddress
    /* 
    /*  Benoetigt: Nichts
    /*
    /*  Liefert als Ergebnis:
    /*          @NewDestinationAddress (string) 
    /*
    /****************************************************************************/
    public function GetDestinationAddress(){
        $args="";
        $filter="NewDestinationAddress";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'GetDestinationAddress',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : SetATMEncapsulation
    /* 
    /*  Benoetigt:
    /*          @NewATMEncapsulation (string)  => Auswahl: LLC|VCMUX
    /*
    /*  Liefert als Ergebnis: Nichts
    /*
    /****************************************************************************/
    public function SetATMEncapsulation($NewATMEncapsulation){
        $args="<NewATMEncapsulation>$NewATMEncapsulation</NewATMEncapsulation>";
        $filter="";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'SetATMEncapsulation',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : GetATMEncapsulation
    /* 
    /*  Benoetigt: Nichts
    /*
    /*  Liefert als Ergebnis:
    /*          @NewATMEncapsulation (string)  => Auswahl: LLC|VCMUX
    /*
    /****************************************************************************/
    public function GetATMEncapsulation(){
        $args="";
        $filter="NewATMEncapsulation";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'GetATMEncapsulation',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : SetFCSPreserved
    /* 
    /*  Benoetigt:
    /*          @NewFCSPreserved (boolean) 
    /*
    /*  Liefert als Ergebnis: Nichts
    /*
    /****************************************************************************/
    public function SetFCSPreserved($NewFCSPreserved){
        $args="<NewFCSPreserved>$NewFCSPreserved</NewFCSPreserved>";
        $filter="";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'SetFCSPreserved',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : GetFCSPreserved
    /* 
    /*  Benoetigt: Nichts
    /*
    /*  Liefert als Ergebnis:
    /*          @NewFCSPreserved (boolean) 
    /*
    /****************************************************************************/
    public function GetFCSPreserved(){
        $args="";
        $filter="NewFCSPreserved";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'GetFCSPreserved',$args,$filter);
    }}

/*##########################################################################*/
/*  Class  : WANIPConnection 
/*  Service: urn:schemas-upnp-org:service:WANIPConnection:1
/*	     Id: urn:upnp-org:serviceId:WANIPConn1 
/*##########################################################################*/
class FritzBoxWANIPConnection extends FritzBoxUpnpClass {
    protected $SERVICE='urn:schemas-upnp-org:service:WANIPConnection:1';
    protected $SERVICEURL='/igdupnp/control/WANIPConn1';
    protected $EVENTURL='/igdupnp/control/WANIPConn1';
    /***************************************************************************
    /* Funktion : SetConnectionType
    /* 
    /*  Benoetigt:
    /*          @NewConnectionType (string) 
    /*
    /*  Liefert als Ergebnis: Nichts
    /*
    /****************************************************************************/
    public function SetConnectionType($NewConnectionType){
        $args="<NewConnectionType>$NewConnectionType</NewConnectionType>";
        $filter="";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'SetConnectionType',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : GetConnectionTypeInfo
    /* 
    /*  Benoetigt: Nichts
    /*
    /*  Liefert als Ergebnis: Array mit folgenden Keys
    /*          @NewConnectionType (string) 
    /*          @NewPossibleConnectionTypes (string)  => Auswahl: Unconfigured|IP_Routed|IP_Bridged
    /*
    /****************************************************************************/
    public function GetConnectionTypeInfo(){
        $args="";
        $filter="NewConnectionType,NewPossibleConnectionTypes";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'GetConnectionTypeInfo',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : RequestConnection
    /* 
    /*  Benoetigt: Nichts
    /*
    /*  Liefert als Ergebnis: Nichts
    /*
    /****************************************************************************/
    public function RequestConnection(){
        $args="";
        $filter="";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'RequestConnection',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : ForceTermination
    /* 
    /*  Benoetigt: Nichts
    /*
    /*  Liefert als Ergebnis: Nichts
    /*
    /****************************************************************************/
    public function ForceTermination(){
        $args="";
        $filter="";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'ForceTermination',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : GetStatusInfo
    /* 
    /*  Benoetigt: Nichts
    /*
    /*  Liefert als Ergebnis: Array mit folgenden Keys
    /*          @NewConnectionStatus (string)  => Auswahl: Unconfigured|Connecting|Authenticating|PendingDisconnect|Disconnecting|Disconnected|Connected
    /*          @NewLastConnectionError (string)  => Auswahl: ERROR_NONE|ERROR_ISP_TIME_OUT|ERROR_COMMAND_ABORTED|ERROR_NOT_ENABLED_FOR_INTERNET|ERROR_BAD_PHONE_NUMBER|ERROR_USER_DISCONNECT|ERROR_ISP_DISCONNECT|ERROR_IDLE_DISCONNECT|ERROR_FORCED_DISCONNECT|ERROR_SERVER_OUT_OF_RESOURCES|ERROR_RESTRICTED_LOGON_HOURS|ERROR_ACCOUNT_DISABLED|ERROR_ACCOUNT_EXPIRED|ERROR_PASSWORD_EXPIRED|ERROR_AUTHENTICATION_FAILURE|ERROR_NO_DIALTONE|ERROR_NO_CARRIER|ERROR_NO_ANSWER|ERROR_LINE_BUSY|ERROR_UNSUPPORTED_BITSPERSECOND|ERROR_TOO_MANY_LINE_ERRORS|ERROR_IP_CONFIGURATION|ERROR_UNKNOWN
    /*          @NewUptime (ui4) 
    /*
    /****************************************************************************/
    public function GetStatusInfo(){
        $args="";
        $filter="NewConnectionStatus,NewLastConnectionError,NewUptime";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'GetStatusInfo',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : GetNATRSIPStatus
    /* 
    /*  Benoetigt: Nichts
    /*
    /*  Liefert als Ergebnis: Array mit folgenden Keys
    /*          @NewRSIPAvailable (boolean) 
    /*          @NewNATEnabled (boolean) 
    /*
    /****************************************************************************/
    public function GetNATRSIPStatus(){
        $args="";
        $filter="NewRSIPAvailable,NewNATEnabled";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'GetNATRSIPStatus',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : GetGenericPortMappingEntry
    /* 
    /*  Benoetigt:
    /*          @NewPortMappingIndex (ui2) 
    /*
    /*  Liefert als Ergebnis: Array mit folgenden Keys
    /*          @NewRemoteHost (string) 
    /*          @NewExternalPort (ui2) 
    /*          @NewProtocol (string)  => Auswahl: TCP|UDP
    /*          @NewInternalPort (ui2) 
    /*          @NewInternalClient (string) 
    /*          @NewEnabled (boolean) 
    /*          @NewPortMappingDescription (string) 
    /*          @NewLeaseDuration (ui4) 
    /*
    /****************************************************************************/
    public function GetGenericPortMappingEntry($NewPortMappingIndex){
        $args="<NewPortMappingIndex>$NewPortMappingIndex</NewPortMappingIndex>";
        $filter="NewRemoteHost,NewExternalPort,NewProtocol,NewInternalPort,NewInternalClient,NewEnabled,NewPortMappingDescription,NewLeaseDuration";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'GetGenericPortMappingEntry',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : GetSpecificPortMappingEntry
    /* 
    /*  Benoetigt:
    /*          @NewRemoteHost (string) 
    /*          @NewExternalPort (ui2) 
    /*          @NewProtocol (string)  => Auswahl: TCP|UDP
    /*
    /*  Liefert als Ergebnis: Array mit folgenden Keys
    /*          @NewInternalPort (ui2) 
    /*          @NewInternalClient (string) 
    /*          @NewEnabled (boolean) 
    /*          @NewPortMappingDescription (string) 
    /*          @NewLeaseDuration (ui4) 
    /*
    /****************************************************************************/
    public function GetSpecificPortMappingEntry($NewRemoteHost, $NewExternalPort, $NewProtocol){
        $args="<NewRemoteHost>$NewRemoteHost</NewRemoteHost><NewExternalPort>$NewExternalPort</NewExternalPort><NewProtocol>$NewProtocol</NewProtocol>";
        $filter="NewInternalPort,NewInternalClient,NewEnabled,NewPortMappingDescription,NewLeaseDuration";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'GetSpecificPortMappingEntry',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : AddPortMapping
    /* 
    /*  Benoetigt:
    /*          @NewRemoteHost (string) 
    /*          @NewExternalPort (ui2) 
    /*          @NewProtocol (string)  => Auswahl: TCP|UDP
    /*          @NewInternalPort (ui2) 
    /*          @NewInternalClient (string) 
    /*          @NewEnabled (boolean) 
    /*          @NewPortMappingDescription (string) 
    /*          @NewLeaseDuration (ui4) 
    /*
    /*  Liefert als Ergebnis: Nichts
    /*
    /****************************************************************************/
    public function AddPortMapping($NewRemoteHost, $NewExternalPort, $NewProtocol, $NewInternalPort, $NewInternalClient, $NewEnabled, $NewPortMappingDescription, $NewLeaseDuration){
        $args="<NewRemoteHost>$NewRemoteHost</NewRemoteHost><NewExternalPort>$NewExternalPort</NewExternalPort><NewProtocol>$NewProtocol</NewProtocol><NewInternalPort>$NewInternalPort</NewInternalPort><NewInternalClient>$NewInternalClient</NewInternalClient><NewEnabled>$NewEnabled</NewEnabled><NewPortMappingDescription>$NewPortMappingDescription</NewPortMappingDescription><NewLeaseDuration>$NewLeaseDuration</NewLeaseDuration>";
        $filter="";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'AddPortMapping',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : DeletePortMapping
    /* 
    /*  Benoetigt:
    /*          @NewRemoteHost (string) 
    /*          @NewExternalPort (ui2) 
    /*          @NewProtocol (string)  => Auswahl: TCP|UDP
    /*
    /*  Liefert als Ergebnis: Nichts
    /*
    /****************************************************************************/
    public function DeletePortMapping($NewRemoteHost, $NewExternalPort, $NewProtocol){
        $args="<NewRemoteHost>$NewRemoteHost</NewRemoteHost><NewExternalPort>$NewExternalPort</NewExternalPort><NewProtocol>$NewProtocol</NewProtocol>";
        $filter="";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'DeletePortMapping',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : GetExternalIPAddress
    /* 
    /*  Benoetigt: Nichts
    /*
    /*  Liefert als Ergebnis:
    /*          @NewExternalIPAddress (string) 
    /*
    /****************************************************************************/
    public function GetExternalIPAddress(){
        $args="";
        $filter="NewExternalIPAddress";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'GetExternalIPAddress',$args,$filter);
    }}

?>