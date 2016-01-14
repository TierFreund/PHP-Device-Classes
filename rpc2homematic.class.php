<?
/*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
 |  Class           :rpc2homematic extends uRpcDevice                             |
 |  Version         :2.2                                                          |
 |  BuildDate       :Mon 11.01.2016 17:20:07                                      |
 |  Publisher       :(c)2016 Xaver Bauer                                          |
 |  Contact         :xaver65@gmail.com                                            |
 |  Desc            :PHP Classes to Control Homematic CCU2                        |
 |  port            :2001                                                         |
 |  base            :http://192.168.112.15:2001                                   |
 |  scpdurl         :/hm_description.xml                                          |
 |  modelName       :Homematic CCU                                                |
 |  deviceType      :urn:schemas-upnp-org:device:Homematic:1                      |
 |  friendlyName    :Homematic CCU                                                |
 |  manufacturer    :Homematic, EQ3                                               |
 |  manufacturerURL :http://www.eq-3.de/                                          |
 |  modelNumber     :CCU2                                                         |
 |  modelURL        :http://www.eq-3.de/zentralen-und-gateways.html               |
 |  UDN             :uuid:HM_CCU                                                  |
 +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

class rpc2homematic extends uRpcDevice {
  // Name:string
  protected function GetServiceConnData($name){
    switch($name){
      case 'Control' : return [2001,"urn:schemas-upnp-org:service:Control:1","/","","/hm_control.xml"];
    }
    return null;
  }
  // url:string, defaultPort:ui4, requestType:string
  public function __construct($url, $defaultPort=2001, $requestType='curl'){
    parent::__construct($url,$defaultPort,$requestType);
    self::SetResponseMode('xmlrpc');
  }
  // Address:string, Peer_address:string, Long_press:boolean
  public function activateLinkParamset($Address,$Peer_address,$Long_press){
    if (!$this->GetOnlineState()) return null;
    $args=array('Address'=>$Address,'Peer_address'=>$Peer_address,'Long_press'=>$Long_press);
    return self::Call('Control','activateLinkParamset',$args,null);
  }
  // Serial_number:string
  public function addDevice($Serial_number){
    if (!$this->GetOnlineState()) return null;
    $args=array('Serial_number'=>$Serial_number);
    $filter=array('DeviceDescription');
    return self::Call('Control','addDevice',$args,$filter);
  }
  // Sender:string, Receiver:string, Description:string
  public function addLink($Sender,$Receiver,$Description){
    if (!$this->GetOnlineState()) return null;
    $args=array('Sender'=>$Sender,'Receiver'=>$Receiver,'Description'=>$Description);
    return self::Call('Control','addLink',$args,null);
  }
  // Passphrase:string
  public function changekey($Passphrase){
    if (!$this->GetOnlineState()) return null;
    $args=array('Passphrase'=>$Passphrase);
    return self::Call('Control','changekey',$args,null);
  }
  // Address:string
  public function clearConfigCache($Address){
    if (!$this->GetOnlineState()) return null;
    $args=array('Address'=>$Address);
    return self::Call('Control','clearConfigCache',$args,null);
  }
  // Address:string, Flags:integer
  public function deleteDevice($Address,$Flags){
    if (!$this->GetOnlineState()) return null;
    $args=array('Address'=>$Address,'Flags'=>$Flags);
    return self::Call('Control','deleteDevice',$args,null);
  }
  // Address:string, Paramset_key:string, Parameter_id:string
  public function determineParameter($Address,$Paramset_key,$Parameter_id){
    if (!$this->GetOnlineState()) return null;
    $args=array('Address'=>$Address,'Paramset_key'=>$Paramset_key,'Parameter_id'=>$Parameter_id);
    return self::Call('Control','determineParameter',$args,null);
  }
  // Object_id:string
  public function getAllMetadata($Object_id){
    if (!$this->GetOnlineState()) return null;
    $args=array('Object_id'=>$Object_id);
    $filter=array('Struct');
    return self::Call('Control','getAllMetadata',$args,$filter);
  }
  // Address:string
  public function getDeviceDescription($Address){
    if (!$this->GetOnlineState()) return null;
    $args=array('Address'=>$Address);
    $filter=array('Description_array');
    return self::Call('Control','getDeviceDescription',$args,$filter);
  }

  public function getInstallMode(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('InstallMode');
    return self::Call('Control','getInstallMode',null,$filter);
  }
  // Reset:boolean
  public function getKeyMismatchDevice($Reset){
    if (!$this->GetOnlineState()) return null;
    $args=array('Reset'=>$Reset);
    $filter=array('MismatchDevice');
    return self::Call('Control','getKeyMismatchDevice',$args,$filter);
  }
  // Sender:string, Receiver:string
  public function getLinkInfo($Sender,$Receiver){
    if (!$this->GetOnlineState()) return null;
    $args=array('Sender'=>$Sender,'Receiver'=>$Receiver);
    $filter=array('Info');
    return self::Call('Control','getLinkInfo',$args,$filter);
  }
  // Address:string
  public function getLinkPeers($Address){
    if (!$this->GetOnlineState()) return null;
    $args=array('Address'=>$Address);
    $filter=array('LinkPeers');
    return self::Call('Control','getLinkPeers',$args,$filter);
  }
  // Address:string, Flags:integer
  public function getLinks($Address,$Flags){
    if (!$this->GetOnlineState()) return null;
    $args=array('Address'=>$Address,'Flags'=>$Flags);
    $filter=array('Link');
    return self::Call('Control','getLinks',$args,$filter);
  }
  // Object_id:string, Data_id:string
  public function getMetadata($Object_id,$Data_id){
    if (!$this->GetOnlineState()) return null;
    $args=array('Object_id'=>$Object_id,'Data_id'=>$Data_id);
    $filter=array('Result_variant');
    return self::Call('Control','getMetadata',$args,$filter);
  }
  // Address:string, Paramset_key:string
  public function getParamset($Address,$Paramset_key){
    if (!$this->GetOnlineState()) return null;
    $args=array('Address'=>$Address,'Paramset_key'=>$Paramset_key);
    $filter=array('Paramset');
    return self::Call('Control','getParamset',$args,$filter);
  }
  // Address:string, Paramset_type:string
  public function getParamsetDescription($Address,$Paramset_type){
    if (!$this->GetOnlineState()) return null;
    $args=array('Address'=>$Address,'Paramset_type'=>$Paramset_type);
    $filter=array('Description_array');
    return self::Call('Control','getParamsetDescription',$args,$filter);
  }
  // Address:string, Type:string
  public function getParamsetId($Address,$Type){
    if (!$this->GetOnlineState()) return null;
    $args=array('Address'=>$Address,'Type'=>$Type);
    $filter=array('ID');
    return self::Call('Control','getParamsetId',$args,$filter);
  }

  public function getServiceMessages(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('Result_array');
    return self::Call('Control','getServiceMessages',null,$filter);
  }
  // Address:string, Value_key:string
  public function getValue($Address,$Value_key){
    if (!$this->GetOnlineState()) return null;
    $args=array('Address'=>$Address,'Value_key'=>$Value_key);
    $filter=array('ValueType');
    return self::Call('Control','getValue',$args,$filter);
  }
  // Url:string, Interface_id:string
  public function init($Url,$Interface_id){
    if (!$this->GetOnlineState()) return null;
    $args=array('Url'=>$Url,'Interface_id'=>$Interface_id);
    return self::Call('Control','init',$args,null);
  }

  public function listBidcosInterfaces(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('Interfaces');
    return self::Call('Control','listBidcosInterfaces',null,$filter);
  }

  public function listDevices(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('Description_Array of DeviceDescription');
    return self::Call('Control','listDevices',null,$filter);
  }

  public function listTeams(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('DeviceDescriptions');
    return self::Call('Control','listTeams',null,$filter);
  }
  // Level:integer
  public function logLevel($Level){
    if (!$this->GetOnlineState()) return null;
    $args=array('Level'=>$Level);
    $filter=array('Level');
    return self::Call('Control','logLevel',$args,$filter);
  }
  // Address:string, Paramset_key:string, Paramset:array
  public function putParamset($Address,$Paramset_key,$Paramset){
    if (!$this->GetOnlineState()) return null;
    $args=array('Address'=>$Address,'Paramset_key'=>$Paramset_key,'Paramset'=>$Paramset);
    return self::Call('Control','putParamset',$args,null);
  }
  // Sender:string, Receiver:string
  public function removeLink($Sender,$Receiver){
    if (!$this->GetOnlineState()) return null;
    $args=array('Sender'=>$Sender,'Receiver'=>$Receiver);
    return self::Call('Control','removeLink',$args,null);
  }
  // Address:string, Value_id:string, Ref_counter:integer
  public function reportValueUsage($Address,$Value_id,$Ref_counter){
    if (!$this->GetOnlineState()) return null;
    $args=array('Address'=>$Address,'Value_id'=>$Value_id,'Ref_counter'=>$Ref_counter);
    $filter=array('Result');
    return self::Call('Control','reportValueUsage',$args,$filter);
  }
  // Address:string
  public function restoreConfigToDevice($Address){
    if (!$this->GetOnlineState()) return null;
    $args=array('Address'=>$Address);
    return self::Call('Control','restoreConfigToDevice',$args,null);
  }

  public function rssiInfo(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('Info');
    return self::Call('Control','rssiInfo',null,$filter);
  }

  public function searchDevices(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('Count');
    return self::Call('Control','searchDevices',null,$filter);
  }
  // Device_address:string, Interface_address:string, Rooming:boolean
  public function setBidcosInterface($Device_address,$Interface_address,$Rooming){
    if (!$this->GetOnlineState()) return null;
    $args=array('Device_address'=>$Device_address,'Interface_address'=>$Interface_address,'Rooming'=>$Rooming);
    return self::Call('Control','setBidcosInterface',$args,null);
  }
  // On:boolean
  public function setInstallMode($On){
    if (!$this->GetOnlineState()) return null;
    $args=array('On'=>$On);
    return self::Call('Control','setInstallMode',$args,null);
  }
  // Sender:string, Receiver:string, Name:string, Description:string
  public function setLinkInfo($Sender,$Receiver,$Name,$Description){
    if (!$this->GetOnlineState()) return null;
    $args=array('Sender'=>$Sender,'Receiver'=>$Receiver,'Name'=>$Name,'Description'=>$Description);
    return self::Call('Control','setLinkInfo',$args,null);
  }
  // Object_id:string, Data_id:string, Value:variant
  public function setMetadata($Object_id,$Data_id,$Value){
    if (!$this->GetOnlineState()) return null;
    $args=array('Object_id'=>$Object_id,'Data_id'=>$Data_id,'Value'=>$Value);
    return self::Call('Control','setMetadata',$args,null);
  }
  // Address:string, Team_address:string
  public function setTeam($Address,$Team_address){
    if (!$this->GetOnlineState()) return null;
    $args=array('Address'=>$Address,'Team_address'=>$Team_address);
    return self::Call('Control','setTeam',$args,null);
  }
  // Passphrase:string
  public function setTempKey($Passphrase){
    if (!$this->GetOnlineState()) return null;
    $args=array('Passphrase'=>$Passphrase);
    return self::Call('Control','setTempKey',$args,null);
  }
  // Address:string, Value_key:string, ValueType:string
  public function setValue($Address,$Value_key,$ValueType){
    if (!$this->GetOnlineState()) return null;
    $args=array('Address'=>$Address,'Value_key'=>$Value_key,'ValueType'=>$ValueType);
    return self::Call('Control','setValue',$args,null);
  }
  // Devices:array
  public function updateFirmware($Devices){
    if (!$this->GetOnlineState()) return null;
    $args=array('Devices'=>$Devices);
    $filter=array('Status');
    return self::Call('Control','updateFirmware',$args,$filter);
  }
}
?>