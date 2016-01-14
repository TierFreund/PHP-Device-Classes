<?
<<<<<<< HEAD:Old/plex.class.php
/*---------------------------------------------------------------------------/
	
File:  
	Desc     : PHP Classes to Control Plex Media Server 
	Date     : 2015-03-22T22:30:51+01:00
	Version  : 1.00.45
	Publisher: (c)2015 Xaver Bauer 
	Contact  : x.bauer@tier-freunde.net

Device:
	Device Type  : urn:schemas-upnp-org:device:MediaServer:1
	URL 		 : http://192.168.112.1:32469/DeviceDescription.xml	
	Friendly Name: Plex Media Server: Multimedia Server
	Manufacturer : Plex, Inc.
	URL 		 : http://www.plexapp.com/
	Model        : Plex Media Server
	Name 		 : Plex Media Server
	Number 		 : 0.9.11.14
	URL 		 : http://www.plexapp.com/
	Serialnumber : 
	UDN          : uuid:1e9f9d14-f975-765b-bdd8-5e3e8deef9ae

/*--------------------------------------------------------------------------*/
=======
//---------------------------------------------------------------------------/
//	
//  
//	Desc     : PHP Classes to Control Plex Media Server 
//	Date     : 2015-04-10T01:05:46+02:00
//	Version  : 1.00.45
//	Publisher: (c)2015 Xaver Bauer 
//	Contact  : x.bauer@tier-freunde.net
//
//--------------------------------------------------------------------------/

>>>>>>> origin/master:plex.class.php
/*##########################################################################/
/*  Class  : PlexXmlRpcDevice 
/*  Desc   : Master Class to Controll Device 
/*	Vars   :
/*  protected _SERVICES  : (object) Holder for all Service Classes
/*  protected _DEVICES   : (object) Holder for all Service Classes
/*  protected _IP        : (string) IP Adress from Device
/*  protected _PORT      : (int)    Port from Device
/*##########################################################################*/
class PlexXmlRpcDevice {
    protected $_SERVICES=null;
    protected $_DEVICES=null;
    protected $_IP='';
    protected $_PORT=32469;
    /***************************************************************************
    /* Funktion : __construct
    /* 
    /*  Benoetigt:
    /*    @url (string)  Device Url eg. '192.168.112.1:32469'
    /*
    /*  Liefert als Ergebnis: Nichts
    /*
    /****************************************************************************/
    public function __construct($url){
        $p=parse_url($url);
        $this->_IP=(isSet($p['host']))?$p['host']:$url;
        $this->_PORT=(isSet($p['port']))?$p['port']:32469;
        $this->_SERVICES=new stdClass();
        $this->_DEVICES=new stdClass();
        $this->_SERVICES->X_MS_MediaReceiverRegistrar=new PlexX_MS_MediaReceiverRegistrar($this);
        $this->_SERVICES->ContentDirectory=new PlexContentDirectory($this);
        $this->_SERVICES->ConnectionManager=new PlexConnectionManager($this);
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
<<<<<<< HEAD:Old/plex.class.php
            case 0 : return array('width'=>260,'height'=>260,'url'=>'http://192.168.112.1:32469/proxy/027d672c6a70ab72aa7c/260x260.png');break;
            case 1 : return array('width'=>120,'height'=>120,'url'=>'http://192.168.112.1:32469/proxy/74b2fce849107951c424/120x120.png');break;
            case 2 : return array('width'=>48,'height'=>48,'url'=>'http://192.168.112.1:32469/proxy/23b1739a6c33d02bf0ba/48x48.png');break;
            case 3 : return array('width'=>260,'height'=>260,'url'=>'http://192.168.112.1:32469/proxy/e2ba69ed701bbef38438/260x260.jpg');break;
            case 4 : return array('width'=>120,'height'=>120,'url'=>'http://192.168.112.1:32469/proxy/ae306aa36533cd3e14b4/120x120.jpg');break;
            case 5 : return array('width'=>48,'height'=>48,'url'=>'http://192.168.112.1:32469/proxy/f5095c764513b3c19c6b/48x48.jpg');break;
=======
            case 0 : return array('width'=>260,'height'=>260,'url'=>$this->GetBaseUrl().'/proxy/027d672c6a70ab72aa7c/260x260.png');break;
            case 1 : return array('width'=>120,'height'=>120,'url'=>$this->GetBaseUrl().'/proxy/74b2fce849107951c424/120x120.png');break;
            case 2 : return array('width'=>48,'height'=>48,'url'=>$this->GetBaseUrl().'/proxy/23b1739a6c33d02bf0ba/48x48.png');break;
            case 3 : return array('width'=>260,'height'=>260,'url'=>$this->GetBaseUrl().'/proxy/e2ba69ed701bbef38438/260x260.jpg');break;
            case 4 : return array('width'=>120,'height'=>120,'url'=>$this->GetBaseUrl().'/proxy/ae306aa36533cd3e14b4/120x120.jpg');break;
            case 5 : return array('width'=>48,'height'=>48,'url'=>$this->GetBaseUrl().'/proxy/f5095c764513b3c19c6b/48x48.jpg');break;
>>>>>>> origin/master:plex.class.php
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
    function IconCount() { return 6;}
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
    public function Upnp($url,$SOAP_service,$SOAP_action,$SOAP_arguments = '',$XML_filter = '', $ReturnValue=true){
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
            return $r===false?null:$ReturnValue;
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
        if(!$p=$this->FunctionExist($FunctionName))
            throw new Exception('Unbekannte Funktion '.$FunctionName.' !!!');
        return $this->CallService($p,$FunctionName, $arguments);
    }
    /***************************************************************************
    /* Funktion : FunctionExist
    /* 
    /*  Benoetigt:
    /*    @FunctionName (string)
    /*
    /*  Liefert als Ergebnis:
    /*    @result (object||false) ServiceObject mit der gesuchten Funktion
    /*
    /****************************************************************************/
    public function FunctionExist($FunctionName){
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
/*  Class  : PlexUpnpClass 
/*  Desc   : Basis Class for Services
/*	Vars   :
/*  protected SERVICE     : (string) Service URN
/*  protected SERVICEURL  : (string) Path to Service Control
/*  protected EVENTURL    : (string) Path to Event Control
/*  protected BASE        : (Object) Points to MasterClass
/*##########################################################################*/
class PlexUpnpClass {
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
/*  Class  : X_MS_MediaReceiverRegistrar 
/*  Service: urn:microsoft.com:service:X_MS_MediaReceiverRegistrar:1
/*	     Id: urn:microsoft.com:serviceId:X_MS_MediaReceiverRegistrar 
/*##########################################################################*/
class PlexX_MS_MediaReceiverRegistrar extends PlexUpnpClass {
    protected $SERVICE='urn:microsoft.com:service:X_MS_MediaReceiverRegistrar:1';
    protected $SERVICEURL='/X_MS_MediaReceiverRegistrar/1e9f9d14-f975-765b-bdd8-5e3e8deef9ae/control.xml';
    protected $EVENTURL='/X_MS_MediaReceiverRegistrar/1e9f9d14-f975-765b-bdd8-5e3e8deef9ae/event.xml';
    /***************************************************************************
    /* Funktion : IsAuthorized
    /* 
    /*  Benoetigt:
    /*          @DeviceID (string) 
    /*
    /*  Liefert als Ergebnis:
    /*          @Result (int) 
    /*
    /****************************************************************************/
    public function IsAuthorized($DeviceID){
        $args="<DeviceID>$DeviceID</DeviceID>";
        $filter="Result";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'IsAuthorized',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : RegisterDevice
    /* 
    /*  Benoetigt:
    /*          @RegistrationReqMsg (bin.base64) 
    /*
    /*  Liefert als Ergebnis:
    /*          @RegistrationRespMsg (bin.base64) 
    /*
    /****************************************************************************/
    public function RegisterDevice($RegistrationReqMsg){
        $args="<RegistrationReqMsg>$RegistrationReqMsg</RegistrationReqMsg>";
        $filter="RegistrationRespMsg";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'RegisterDevice',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : IsValidated
    /* 
    /*  Benoetigt:
    /*          @DeviceID (string) 
    /*
    /*  Liefert als Ergebnis:
    /*          @Result (int) 
    /*
    /****************************************************************************/
    public function IsValidated($DeviceID){
        $args="<DeviceID>$DeviceID</DeviceID>";
        $filter="Result";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'IsValidated',$args,$filter);
    }}

/*##########################################################################*/
/*  Class  : ContentDirectory 
/*  Service: urn:schemas-upnp-org:service:ContentDirectory:1
/*	     Id: urn:upnp-org:serviceId:ContentDirectory 
/*##########################################################################*/
class PlexContentDirectory extends PlexUpnpClass {
    protected $SERVICE='urn:schemas-upnp-org:service:ContentDirectory:1';
    protected $SERVICEURL='/ContentDirectory/1e9f9d14-f975-765b-bdd8-5e3e8deef9ae/control.xml';
    protected $EVENTURL='/ContentDirectory/1e9f9d14-f975-765b-bdd8-5e3e8deef9ae/event.xml';
    /***************************************************************************
    /* Funktion : Browse
    /* 
    /*  Benoetigt:
    /*          @ObjectID (string) 
    /*          @BrowseFlag (string)  => Auswahl: BrowseMetadata|BrowseDirectChildren
    /*          @Filter (string) 
    /*          @StartingIndex (ui4) 
    /*          @RequestedCount (ui4) 
    /*          @SortCriteria (string) 
    /*
    /*  Liefert als Ergebnis: Array mit folgenden Keys
    /*          @Result (string) 
    /*          @NumberReturned (ui4) 
    /*          @TotalMatches (ui4) 
    /*          @UpdateID (ui4) 
    /*
    /****************************************************************************/
    public function Browse($ObjectID, $BrowseFlag, $Filter, $StartingIndex, $RequestedCount, $SortCriteria){
        $args="<ObjectID>$ObjectID</ObjectID><BrowseFlag>$BrowseFlag</BrowseFlag><Filter>$Filter</Filter><StartingIndex>$StartingIndex</StartingIndex><RequestedCount>$RequestedCount</RequestedCount><SortCriteria>$SortCriteria</SortCriteria>";
        $filter="Result,NumberReturned,TotalMatches,UpdateID";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'Browse',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : GetSortCapabilities
    /* 
    /*  Benoetigt: Nichts
    /*
    /*  Liefert als Ergebnis:
    /*          @SortCaps (string) 
    /*
    /****************************************************************************/
    public function GetSortCapabilities(){
        $args="";
        $filter="SortCaps";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'GetSortCapabilities',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : GetSystemUpdateID
    /* 
    /*  Benoetigt: Nichts
    /*
    /*  Liefert als Ergebnis:
    /*          @Id (ui4) 
    /*
    /****************************************************************************/
    public function GetSystemUpdateID(){
        $args="";
        $filter="Id";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'GetSystemUpdateID',$args,$filter);
    }
    /***************************************************************************
    /* Funktion : GetSearchCapabilities
    /* 
    /*  Benoetigt: Nichts
    /*
    /*  Liefert als Ergebnis:
    /*          @SearchCaps (string) 
    /*
    /****************************************************************************/
    public function GetSearchCapabilities(){
        $args="";
        $filter="SearchCaps";
        return $this->BASE->Upnp($this->SERVICEURL,$this->SERVICE,'GetSearchCapabilities',$args,$filter);
    }}

/*##########################################################################*/
/*  Class  : ConnectionManager 
/*  Service: urn:schemas-upnp-org:service:ConnectionManager:1
/*	     Id: urn:upnp-org:serviceId:ConnectionManager 
/*##########################################################################*/
class PlexConnectionManager extends PlexUpnpClass {
    protected $SERVICE='urn:schemas-upnp-org:service:ConnectionManager:1';
    protected $SERVICEURL='/ConnectionManager/1e9f9d14-f975-765b-bdd8-5e3e8deef9ae/control.xml';
    protected $EVENTURL='/ConnectionManager/1e9f9d14-f975-765b-bdd8-5e3e8deef9ae/event.xml';
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
    }}

?>