<?
/****************************************************************************/
/* File:  
/*  Desc     : PHP Classes to Control Homatic CCU or BidCoS-Interfaces
/*  Date     : 2015-01-31T13:45:47+01:00
/*  Version  : 1.00.00
/*  Publisher: (c)2015 Xaver Bauer 
/*  Contact  : x.bauer@tier-freunde.net
/****************************************************************************/

class HomeMaticUpnpDevice {
    private $_PROTOKOL = 'console';
    private $_IP  = null;
    private $_PORT = null;
    private $_URL = null;
    private $request = null;
    /*--------------------------------------------------------------------------*/
    /*  Funktion: Construct                                                     */
    /*                                                                          */
    /*     Erwartet:                                                            */
    /*            url (string)                                                  */
    /*                                                                          */
    /*      Liefert:                                                            */
    /*   HomatikSlass (object)                                                  */
    /*--------------------------------------------------------------------------*/
    public function __construct($url){
        $u = parse_url($url);if(!isSet($u['path']))$u['path']='';
        $this->_URL = (isset($u['query']))?$u['path'].'?'.$u['query']:$u['path'];
        $this->_PROTOKOL = isSet($u['scheme'])?$u['scheme']:'http';
		$this->_IP = $u['host'];
        $this->_PORT=(isSet($u['port']))?$u['port']:2001;
    }
    /*--------------------------------------------------------------------------*/
    /*  Funktion: RegisterEventCallback                                         */
    /*                                                                          */
    /*     Erwartet:                                                            */
    /*   callback_url (string)                                                  */
    /*   interface_id (string)                                                  */
    /*      Liefert:Nichts                                                      */
    /* Beschreibung:                                                            */
    /* Siehe Funktion HM_init                                                   */
    /*--------------------------------------------------------------------------*/
    public function RegisterEventCallback($callback_url, $interface_id='HMT'){
        return $this->HM_init($callback_url, $interface_id);
    }
    /*--------------------------------------------------------------------------*/
    /*  Funktion: UnRegisterEventCallback                                       */
    /*                                                                          */
    /*     Erwartet:                                                            */
    /*   callback_url (string)                                                  */
    /*      Liefert:Nichts                                                      */
    /* Beschreibung:                                                            */
    /* Siehe Funktion HM_init                                                   */
    /*--------------------------------------------------------------------------*/
    public function UnRegisterEventCallback($callback_url){ 
        return $this->HM_init($callback_url,'');
    }
    /*--------------------------------------------------------------------------*/
    /*  Funktion: HM_init                                                       */
    /*                                                                          */
    /*     Erwartet:                                                            */
    /*               url (string)                                               */
    /*      interface_id (string)                                               */
    /*                                                                          */
    /*      Liefert:                                                            */
    /*            Nichts                                                        */
    /*                                                                          */
    /* Beschreibung:                                                            */
    /* Mit dieser Methode teilt die Logikschicht dem                            */
    /* Schnittstellenprozess mit, dass sie gerade gestartet wurde. Der          */
    /* Schnittstellenprozess wird sich daraufhin selbst initialisieren          */
    /* und z.B. mit listDevices() die der Logikschicht bekannten Geräte         */
    /* abfragen.                                                                */
    /* Der Parameter url gibt die Adresse des XmlRpc-Servers an, unter          */
    /* der die Logikschicht zu erreichen ist.                                   */
    /* Der Parameter interface_id teilt dem Schnittstellenprozess die           */
    /* Id, mit unter der er sich gegenüber der Logikschicht                     */
    /* identifiziert.                                                           */
    /* Zum Abmelden von der Ereignisbehandlung wird interface_id leer           */
    /* gelassen.                                                                */
    /*                                                                          */
    /*--------------------------------------------------------------------------*/
    function HM_init ($url, $interface_id){
      return $this->__call('init',array($url,$interface_id));
    }
    /*--------------------------------------------------------------------------*/
    /*  Funktion: HM_getLinks                                                   */
    /*                                                                          */
    /*     Erwartet:                                                            */
    /*           address (string)                                               */
    /*             flags (integer)                                              */
    /*                                                                          */
    /*      Liefert:                                                            */
    /*              Link (array)                                                */
    /*                                                                          */
    /*                                                                          */
    /* Beschreibung:                                                            */
    /* Diese Methode gibt alle einem logischen Kanal oder Gerät                 */
    /* zugeordneten Kommunikationsbeziehungen zurück.                           */
    /* Der Parameter address ist die Kanal- oder Geräteadresse des              */
    /* logischen Objektes, auf das sich die Abfrage bezieht. Bei                */
    /* address=="" werden alle Kommunikationsbeziehungen des gesamten           */
    /* Schnittstellenprozesses zurückgegeben.                                   */
    /* Der Parameter flags ist ein bitweises oder folgender Werte:              */
    /* · 1 = GL_FLAG_GROUP                                                      */
    /* Wenn address einen Kanal bezeichnet, der sich in einer Gruppe            */
    /* befindet, werden die Kommunikationsbeziehungen für alle Kanäle           */
    /* der Gruppe zurückgegeben.                                                */
    /* · 2 = GL_FLAG_SENDER_PARAMSET                                            */
    /* Das Feld SENDER_PARAMSET des Rückgabewertes wird gefüllt.                */
    /* · 4 = GL_FLAG_RECEIVER_PARAMSET                                          */
    /* Das Feld RECEIVER_PARAMSET des Rückgabewertes wird gefüllt.              */
    /* flags ist optional. Defaultwert ist 0x00.                                */
    /* Der Rückgabewert ist ein Array von Strukturen. Jede dieser               */
    /* Strukturen enthält die folgenden Felder:                                 */
    /* · SENDER                                                                 */
    /* Datentyp String. Adresse des Senders der Kommunikationsbeziehung         */
    /* · RECEIVER                                                               */
    /* Datentyp String. Adresse des Empfängers der                              */
    /* Kommunikationsbeziehung                                                  */
    /* · FLAGS                                                                  */
    /* Datentyp Integer. FLAGS ist ein bitweises oder folgender Werte:          */
    /*  1=LINK_FLAG_SENDER_BROKEN                                               */
    /* Diese Verknüpfung ist auf der Senderseite nicht intakt                   */
    /*  2=LINK_FLAG_RECEIVER_BROKEN                                             */
    /* Diese Verknüpfung ist auf der Empfängerseite nicht intakt                */
    /* · NAME                                                                   */
    /* Datentyp String. Name der Kommunikationsbeziehung                        */
    /* · DESCRIPTION                                                            */
    /* Datentyp String. Textuelle Beschreibung der                              */
    /* Kommunikationsbeziehung                                                  */
    /* · SENDER_PARAMSET                                                        */
    /* Datentyp Paramset. Parametersatz dieser Kommunikationsbeziehung          */
    /* für die Senderseite                                                      */
    /* · RECEIVER_PARAMSET                                                      */
    /* Datentyp Paramset. Parametersatz dieser Kommunikationsbeziehung          */
    /* für die Empfängerseite                                                   */
    /*                                                                          */
    /*--------------------------------------------------------------------------*/
    function HM_getLinks ($address, $flags){
      return $this->__call('getLinks',array($address,$flags));
    }
    /*--------------------------------------------------------------------------*/
    /*  Funktion: HM_addLink                                                    */
    /*                                                                          */
    /*     Erwartet:                                                            */
    /*            sender (string)                                               */
    /*          receiver (string)                                               */
    /*       description (string)                                               */
    /*                                                                          */
    /*      Liefert:                                                            */
    /*            Nichts                                                        */
    /*                                                                          */
    /* Beschreibung:                                                            */
    /* Diese Methode erstellt eine Kommunikationsbeziehung zwischen zwei        */
    /* logischen Geräten. Die Parameter sender und receiver bezeichnen          */
    /* die beiden zu verknüpfenden Partner. Die Parameter name und              */
    /* description sind optional und beschreiben die Verknüpfung näher.         */
    /*                                                                          */
    /*--------------------------------------------------------------------------*/
    function HM_addLink ($sender, $receiver, $description){
      return $this->__call('addLink',array($sender,$receiver,$description));
    }
    /*--------------------------------------------------------------------------*/
    /*  Funktion: HM_removeLink                                                 */
    /*                                                                          */
    /*     Erwartet:                                                            */
    /*            sender (string)                                               */
    /*          receiver (string)                                               */
    /*                                                                          */
    /*      Liefert:                                                            */
    /*            Nichts                                                        */
    /*                                                                          */
    /* Beschreibung:                                                            */
    /* Diese Methode löscht eine Kommunikationsbeziehung zwischen zwei          */
    /* Geräten. Die Parameter sender und receiver bezeichnen die beiden         */
    /* Kommunikationspartnern deren Kommunikationszuordnung gelöscht            */
    /* werden soll.                                                             */
    /*                                                                          */
    /*--------------------------------------------------------------------------*/
    function HM_removeLink ($sender, $receiver){
      return $this->__call('removeLink',array($sender,$receiver));
    }
    /*--------------------------------------------------------------------------*/
    /*  Funktion: HM_setLinkInfo                                                */
    /*                                                                          */
    /*     Erwartet:                                                            */
    /*            sender (string)                                               */
    /*          receiver (string)                                               */
    /*              name (string)                                               */
    /*       description (string)                                               */
    /*                                                                          */
    /*      Liefert:                                                            */
    /*            Nichts                                                        */
    /*                                                                          */
    /* Beschreibung:                                                            */
    /* Diese Methode ändert die beschreibenden Texte einer                      */
    /* Kommunikationsbeziehung. Die Parameter sender und receiver               */
    /* bezeichnen die beiden zu verknüpfenden Partner. Die Parameter            */
    /* name und description beschreiben die Verknüpfung textuell.               */
    /*                                                                          */
    /*--------------------------------------------------------------------------*/
    function HM_setLinkInfo ($sender, $receiver, $name, $description){
      return $this->__call('setLinkInfo',array($sender,$receiver,$name,$description));
    }
    /*--------------------------------------------------------------------------*/
    /*  Funktion: HM_getLinkInfo                                                */
    /*                                                                          */
    /*     Erwartet:                                                            */
    /*            sender (string)                                               */
    /*          receiver (string)                                               */
    /*                                                                          */
    /*      Liefert:                                                            */
    /*              Info (array)                                                */
    /*                                                                          */
    /*                                                                          */
    /* Beschreibung:                                                            */
    /* Diese Methode gibt den Namen und die Beschreibung für eine               */
    /* bestehende Kommunikationsbeziehung zurück. Die Parameter                 */
    /* sender_address und receiver_address bezeichnen die beiden                */
    /* verknüpften Partner.                                                     */
    /*                                                                          */
    /*--------------------------------------------------------------------------*/
    function HM_getLinkInfo ($sender, $receiver){
      return $this->__call('getLinkInfo',array($sender,$receiver));
    }
    /*--------------------------------------------------------------------------*/
    /*  Funktion: HM_activateLinkParamset                                       */
    /*                                                                          */
    /*     Erwartet:                                                            */
    /*           address (string)                                               */
    /*      peer_address (string)                                               */
    /*        long_press (boolean)                                              */
    /*                                                                          */
    /*      Liefert:                                                            */
    /*            Nichts                                                        */
    /*                                                                          */
    /* Beschreibung:                                                            */
    /* Mit dieser Methode wird ein Link-Parameterset aktiviert. Das             */
    /* logische Gerät verhält sich dann so als ob es direkt von dem             */
    /* entsprechenden zugeordneten Gerät angesteuert worden wäre.               */
    /* Hiermit kann z.B. ein Link-Parameter-Set getestet werden.                */
    /* Der Parameter address ist die Addresses des anzusprechenden              */
    /* logischen Gerätes.                                                       */
    /* Der Parameter peer_address ist die Addresse des                          */
    /* Kommunikationspartners, dessen Link- Parameter-Set aktiviert             */
    /* werden soll.                                                             */
    /* Der Parameter long_press gibt an, ob das Parameterset für den            */
    /* langen Tastendruck aktiviert werden soll.                                */
    /*                                                                          */
    /*--------------------------------------------------------------------------*/
    function HM_activateLinkParamset ($address, $peer_address, $long_press){
      return $this->__call('activateLinkParamset',array($address,$peer_address,$long_press));
    }
    /*--------------------------------------------------------------------------*/
    /*  Funktion: HM_getLinkPeers                                               */
    /*                                                                          */
    /*     Erwartet:                                                            */
    /*           address (string)                                               */
    /*                                                                          */
    /*      Liefert:                                                            */
    /*         LinkPeers (array <string>)                                       */
    /*                                                                          */
    /*                                                                          */
    /* Beschreibung:                                                            */
    /* Diese Methode gibt alle einem logischen Gerät zugeordneten               */
    /* Kommunikationspartner zurück. Die zurückgegebenen Werte können           */
    /* als Parameter paramset_key für getParamset() und putParamset()           */
    /* verwendet werden. Der Parameter address ist die Adresse eines            */
    /* logischen Gerätes.                                                       */
    /*                                                                          */
    /*--------------------------------------------------------------------------*/
    function HM_getLinkPeers ($address){
      return $this->__call('getLinkPeers',array($address));
    }
    /*--------------------------------------------------------------------------*/
    /*  Funktion: HM_determineParameter                                         */
    /*                                                                          */
    /*     Erwartet:                                                            */
    /*           address (string)                                               */
    /*      paramset_key (string)                                               */
    /*      parameter_id (string)                                               */
    /*                                                                          */
    /*      Liefert:                                                            */
    /*            Nichts                                                        */
    /*                                                                          */
    /* Beschreibung:                                                            */
    /* Mit dieser Methode wird ein Parameter eines Parameter-Sets               */
    /* automatisch bestimmt. Der Parameter kann bei erfolgreicher               */
    /* Ausführung anschließend sofort über getParamset gelesen werden.          */
    /* Der Parameter address ist die Addresses eines logischen Gerätes.         */
    /* Der Parameter paramset_key ist „MASTER“, „VALUES“ oder die               */
    /* Adresse eines Kommunikationspartners für das entsprechende               */
    /* Link-Parameter-Set (siehe getLinkPeers).                                 */
    /* Der Parameter parameter_id bestimmt den automatisch zu                   */
    /* bestimmenden Parameter.                                                  */
    /*                                                                          */
    /*--------------------------------------------------------------------------*/
    function HM_determineParameter ($address, $paramset_key, $parameter_id){
      return $this->__call('determineParameter',array($address,$paramset_key,$parameter_id));
    }
    /*--------------------------------------------------------------------------*/
    /*  Funktion: HM_getParamsetDescription                                     */
    /*                                                                          */
    /*     Erwartet:                                                            */
    /*           address (string)                                               */
    /*     paramset_type (string)                                               */
    /*                                                                          */
    /*      Liefert:                                                            */
    /*       Description (array)                                                */
    /*                                                                          */
    /*                                                                          */
    /* Beschreibung:                                                            */
    /* Mit dieser Methode wird die Beschreibung eines Parameter-Sets            */
    /* ermittelt. Der Parameter address ist die Adresse eines logischen         */
    /* Gerätes (z.B. von listDevices zurückgegeben). Der Parameter              */
    /* paramset_type ist „MASTER“, „VALUES“ oder „LINK“.                        */
    /*                                                                          */
    /*--------------------------------------------------------------------------*/
    function HM_getParamsetDescription ($address, $paramset_type){
      return $this->__call('getParamsetDescription',array($address,$paramset_type));
    }
    /*--------------------------------------------------------------------------*/
    /*  Funktion: HM_getParamsetId                                              */
    /*                                                                          */
    /*     Erwartet:                                                            */
    /*           address (string)                                               */
    /*              type (string)                                               */
    /*                                                                          */
    /*      Liefert:                                                            */
    /*                ID (string)                                               */
    /*                                                                          */
    /*                                                                          */
    /* Beschreibung:                                                            */
    /* Diese Methode gibt die Id eines Parametersets zurück. Diese wird         */
    /* verwendet, um spezialisierte Konfigurationsdialoge (Easymode) den        */
    /* Parametersets zuzuordnen.                                                */
    /*                                                                          */
    /*--------------------------------------------------------------------------*/
    function HM_getParamsetId ($address, $type){
      return $this->__call('getParamsetId',array($address,$type));
    }
    /*--------------------------------------------------------------------------*/
    /*  Funktion: HM_getParamset                                                */
    /*                                                                          */
    /*     Erwartet:                                                            */
    /*           address (string)                                               */
    /*      paramset_key (string)                                               */
    /*                                                                          */
    /*      Liefert:                                                            */
    /*          Paramset (array)                                                */
    /*                                                                          */
    /*                                                                          */
    /* Beschreibung:                                                            */
    /* Mit dieser Methode wird ein komplettes Parameter-Set für ein             */
    /* logisches Gerät gelesen. Der Parameter address ist die Addresses         */
    /* eines logischen Gerätes. Der Parameter paramset_key ist „MASTER“,        */
    /* „VALUES“ oder die Adresse eines Kommunikationspartners für das           */
    /* entsprechende Link-Parameter-Set (siehe getLinkPeers).                   */
    /*                                                                          */
    /*--------------------------------------------------------------------------*/
    function HM_getParamset ($address, $paramset_key){
      return $this->__call('getParamset',array($address,$paramset_key));
    }
    /*--------------------------------------------------------------------------*/
    /*  Funktion: HM_putParamset                                                */
    /*                                                                          */
    /*     Erwartet:                                                            */
    /*           address (string)                                               */
    /*      paramset_key (string)                                               */
    /*          Paramset (array)                                                */
    /*                                                                          */
    /*      Liefert:                                                            */
    /*            Nichts                                                        */
    /*                                                                          */
    /* Beschreibung:                                                            */
    /* Mit dieser Methode wird ein komplettes Parameter-Set für ein             */
    /* logisches Gerät geschrieben. Der Parameter address ist die               */
    /* Addresses eines logischen Gerätes. Der Parameter paramset_key ist        */
    /* „MASTER“, „VALUES“ oder die Adresse eines Kommunikationspartners         */
    /* für das entsprechende Link-Parameter-Set (siehe getLinkPeers).           */
    /* Der Parameter set ist das zu schreibende Parameter-Set. In set           */
    /* nicht vorhandene Member werden einfach nicht geschrieben und             */
    /* behalten ihren alten Wert.                                               */
    /*                                                                          */
    /*--------------------------------------------------------------------------*/
    function HM_putParamset ($address, $paramset_key, $Paramset){
      return $this->__call('putParamset',array($address,$paramset_key,$Paramset));
    }
    /*--------------------------------------------------------------------------*/
    /*  Funktion: HM_getValue                                                   */
    /*                                                                          */
    /*     Erwartet:                                                            */
    /*           address (string)                                               */
    /*         value_key (string)                                               */
    /*                                                                          */
    /*      Liefert:                                                            */
    /*         ValueType (string)                                               */
    /*                                                                          */
    /*                                                                          */
    /* Beschreibung:                                                            */
    /* Mit dieser Methode wird ein einzelner Wert aus dem Parameter-Set         */
    /* „VALUES“ gelesen. Der Parameter address ist die Addresse eines           */
    /* logischen Gerätes. Der Parameter value_key ist der Name des zu           */
    /* lesenden Wertes. Die möglichen Werte für value_key ergeben sich          */
    /* aus der ParamsetDescription des entsprechenden Parameter-Sets            */
    /* „VALUES“.                                                                */
    /*                                                                          */
    /*--------------------------------------------------------------------------*/
    function HM_getValue ($address, $value_key){
      return $this->__call('getValue',array($address,$value_key));
    }
    /*--------------------------------------------------------------------------*/
    /*  Funktion: HM_setValue                                                   */
    /*                                                                          */
    /*     Erwartet:                                                            */
    /*           address (string)                                               */
    /*         value_key (string)                                               */
    /*         ValueType (string)                                               */
    /*                                                                          */
    /*      Liefert:                                                            */
    /*            Nichts                                                        */
    /*                                                                          */
    /* Beschreibung:                                                            */
    /* Mit dieser Methode wird ein einzelner Wert aus dem Parameter-Set         */
    /* „VALUES“ geschrieben. Der Parameter address ist die Addresse             */
    /* eines logischen Gerätes. Der Parameter value_key ist der Name des        */
    /* zu schreibenden Wertes. Die möglichen Werte für value_key ergeben        */
    /* sich aus der ParamsetDescription des entsprechenden                      */
    /* Parameter-Sets „VALUES“. Der Parameter value ist der zu                  */
    /* schreibende Wert.                                                        */
    /*                                                                          */
    /*--------------------------------------------------------------------------*/
    function HM_setValue ($address, $value_key, $ValueType){
      return $this->__call('setValue',array($address,$value_key,$ValueType));
    }
    /*--------------------------------------------------------------------------*/
    /*  Funktion: HM_setInstallMode                                             */
    /*                                                                          */
    /*     Erwartet:                                                            */
    /*                on (boolean)                                              */
    /*                                                                          */
    /*      Liefert:                                                            */
    /*            Nichts                                                        */
    /*                                                                          */
    /* Beschreibung:                                                            */
    /* Diese Methode aktiviert und deaktiviert den Installations-Modus,         */
    /* in dem neue Geräte an der HomeMatic-CCU angemeldet werden können.        */
    /* Der Parameter on bestimmt, ob der Installations-Modus aktiviert          */
    /* oder deaktiviert werden soll.                                            */
    /*                                                                          */
    /*--------------------------------------------------------------------------*/
    function HM_setInstallMode ($on){
      return $this->__call('setInstallMode',array($on));
    }
    /*--------------------------------------------------------------------------*/
    /*  Funktion: HM_getInstallMode                                             */
    /*                                                                          */
    /*     Erwartet:                                                            */
    /*            Nichts                                                        */
    /*                                                                          */
    /*      Liefert:                                                            */
    /*       InstallMode (integer)                                              */
    /*                                                                          */
    /*                                                                          */
    /* Beschreibung:                                                            */
    /* Diese Methode gibt die verbliebene Restzeit in Sekunden im               */
    /* Anlernmodus zurück.                                                      */
    /* Der Wert 0 bedeutet, der Anlernmodus ist nicht aktiv.                    */
    /*                                                                          */
    /*--------------------------------------------------------------------------*/
    function HM_getInstallMode (){
      return $this->__call('getInstallMode');
    }
    /*--------------------------------------------------------------------------*/
    /*  Funktion: HM_getKeyMismatchDevice                                       */
    /*                                                                          */
    /*     Erwartet:                                                            */
    /*             reset (boolean)                                              */
    /*                                                                          */
    /*      Liefert:                                                            */
    /*    MismatchDevice (string)                                               */
    /*                                                                          */
    /*                                                                          */
    /* Beschreibung:                                                            */
    /* Diese Methode gibt die Seriennummer des letzten Gerätes zurück,          */
    /* das aufgrund eines falschen AES-Schlüssels nicht angelernt werden        */
    /* konnte.                                                                  */
    /* Mit reset=true wird diese Information im Schnittstellenprozess           */
    /* zurückgesetzt.                                                           */
    /*                                                                          */
    /*--------------------------------------------------------------------------*/
    function HM_getKeyMismatchDevice ($reset){
      return $this->__call('getKeyMismatchDevice',array($reset));
    }
    /*--------------------------------------------------------------------------*/
    /*  Funktion: HM_addDevice                                                  */
    /*                                                                          */
    /*     Erwartet:                                                            */
    /*     serial_number (string)                                               */
    /*                                                                          */
    /*      Liefert:                                                            */
    /* DeviceDescription (array)                                                */
    /*                                                                          */
    /*                                                                          */
    /* Beschreibung:                                                            */
    /* Diese Methode lernt ein Gerät anhand seiner Seriennummer an die          */
    /* CCU an. Diese Funktion wird nicht von jedem Gerät unterstützt.           */
    /* Rückgabewert ist die DeviceDescription des neu angelernten               */
    /* Geräts.                                                                  */
    /*                                                                          */
    /*--------------------------------------------------------------------------*/
    function HM_addDevice ($serial_number){
      return $this->__call('addDevice',array($serial_number));
    }
    /*--------------------------------------------------------------------------*/
    /*  Funktion: HM_listDevices                                                */
    /*                                                                          */
    /*     Erwartet:                                                            */
    /*            Nichts                                                        */
    /*                                                                          */
    /*      Liefert:                                                            */
    /*       description (Array of DeviceDescription)                           */
    /*                                                                          */
    /*                                                                          */
    /* Beschreibung:                                                            */
    /* Diese Methode gibt alle dem Schnittstellenprozess bekannten              */
    /* Geräte in Form von Gerätebeschreibungen zurück.                          */
    /*                                                                          */
    /*--------------------------------------------------------------------------*/
    function HM_listDevices (){
      return $this->__call('listDevices');
    }
    /*--------------------------------------------------------------------------*/
    /*  Funktion: HM_searchDevices                                              */
    /*                                                                          */
    /*     Erwartet:                                                            */
    /*            Nichts                                                        */
    /*                                                                          */
    /*      Liefert:                                                            */
    /*             Count (integer)                                              */
    /*                                                                          */
    /*                                                                          */
    /* Beschreibung:                                                            */
    /* Diese Methode durchsucht den Bus nach neuen Geräten und gibt die         */
    /* Anzahl gefundener Geräte zurück. Die neu gefundenen Geräte werden        */
    /* mit newDevices der Logikschicht gemeldet.                                */
    /*                                                                          */
    /*--------------------------------------------------------------------------*/
    function HM_searchDevices (){
      return $this->__call('searchDevices');
    }
    /*--------------------------------------------------------------------------*/
    /*  Funktion: HM_deleteDevice                                               */
    /*                                                                          */
    /*     Erwartet:                                                            */
    /*           address (string)                                               */
    /*             flags (integer)                                              */
    /*                                                                          */
    /*      Liefert:                                                            */
    /*            Nichts                                                        */
    /*                                                                          */
    /* Beschreibung:                                                            */
    /* Diese Methode löscht ein Gerät aus dem Schnittstellenprozess.            */
    /* Der Parameter address ist die Addresse des zu löschenden Gerätes.        */
    /* flags ist ein bitweises oder folgender Werte:                            */
    /* · 0x01=DELETE_FLAG_RESET                                                 */
    /* Das Gerät wird vor dem Löschen in den Werkszustand zurückgesetzt         */
    /* · 0x02=DELETE_FLAG_FORCE                                                 */
    /* Das Gerät wird auch gelöscht, wenn es nicht erreichbar ist               */
    /* · 0x04=DELETE_FLAG_DEFER                                                 */
    /* Wenn das Gerät nicht erreichbar ist, wird es bei nächster                */
    /* Gelegenheit gelöscht.                                                    */
    /*                                                                          */
    /*--------------------------------------------------------------------------*/
    function HM_deleteDevice ($address, $flags){
      return $this->__call('deleteDevice',array($address,$flags));
    }
    /*--------------------------------------------------------------------------*/
    /*  Funktion: HM_getDeviceDescription                                       */
    /*                                                                          */
    /*     Erwartet:                                                            */
    /*           address (string)                                               */
    /*                                                                          */
    /*      Liefert:                                                            */
    /*       description (array)                                                */
    /*                                                                          */
    /*                                                                          */
    /* Beschreibung:                                                            */
    /* Diese Methode gibt die Gerätebeschreibung des als address                */
    /* übergebenen Gerätes zurück.                                              */
    /*                                                                          */
    /*--------------------------------------------------------------------------*/
    function HM_getDeviceDescription ($address){
      return $this->__call('getDeviceDescription',array($address));
    }
    /*--------------------------------------------------------------------------*/
    /*  Funktion: HM_changekey                                                  */
    /*                                                                          */
    /*     Erwartet:                                                            */
    /*        passphrase (string)                                               */
    /*                                                                          */
    /*      Liefert:                                                            */
    /*            Nichts                                                        */
    /*                                                                          */
    /* Beschreibung:                                                            */
    /* Diese Methode ändert den vom Schnittstellenprozess verwendeten           */
    /* AES-Schlüssel. Der Schlüssel wird ebenfalls in allen angelernten         */
    /* Geräten getauscht.                                                       */
    /*                                                                          */
    /*--------------------------------------------------------------------------*/
    function HM_changekey ($passphrase){
      return $this->__call('changekey',array($passphrase));
    }
    /*--------------------------------------------------------------------------*/
    /*  Funktion: HM_setTempKey                                                 */
    /*                                                                          */
    /*     Erwartet:                                                            */
    /*        passphrase (string)                                               */
    /*                                                                          */
    /*      Liefert:                                                            */
    /*            Nichts                                                        */
    /*                                                                          */
    /* Beschreibung:                                                            */
    /* Diese Methode ändert den von der CCU verwendeten temporären              */
    /* AES-Schlüssel. Der temporäre AES-Schlüssel wird verwendet, um ein        */
    /* Gerät anzulernen, in dem ein anderer Schlüssel gespeichert ist           */
    /* als der Schlüssel der CCU.                                               */
    /*                                                                          */
    /*--------------------------------------------------------------------------*/
    function HM_setTempKey ($passphrase){
      return $this->__call('setTempKey',array($passphrase));
    }
    /*--------------------------------------------------------------------------*/
    /*  Funktion: HM_listTeams                                                  */
    /*                                                                          */
    /*     Erwartet:                                                            */
    /*            Nichts                                                        */
    /*                                                                          */
    /*      Liefert:                                                            */
    /*DeviceDescriptions (array)                                                */
    /*                                                                          */
    /*                                                                          */
    /* Beschreibung:                                                            */
    /* Diese Methode gibt alle dem Schnittstellenprozess bekannten Teams        */
    /* in Form von Gerätebeschreibungen zurück.                                 */
    /*                                                                          */
    /*--------------------------------------------------------------------------*/
    function HM_listTeams (){
      return $this->__call('listTeams');
    }
    /*--------------------------------------------------------------------------*/
    /*  Funktion: HM_setTeam                                                    */
    /*                                                                          */
    /*     Erwartet:                                                            */
    /*           address (string)                                               */
    /*      team_address (string)                                               */
    /*                                                                          */
    /*      Liefert:                                                            */
    /*            Nichts                                                        */
    /*                                                                          */
    /* Beschreibung:                                                            */
    /* Diese Methode fügt den Kanal channel_address zum Team                    */
    /* team_address hinzu.                                                      */
    /* Bei team_address="" wird der Kanal channel_address seinem eigenen        */
    /* Team zugeordnet.                                                         */
    /* Dabei muss team_address entweder leer sein ("") oder eine                */
    /* Seriennummer eines existierenden Teams enthalten. Teams werden           */
    /* dabei je nach Bedarf erzeugt und gelöscht.                               */
    /*                                                                          */
    /*--------------------------------------------------------------------------*/
    function HM_setTeam ($address, $team_address){
      return $this->__call('setTeam',array($address,$team_address));
    }
    /*--------------------------------------------------------------------------*/
    /*  Funktion: HM_restoreConfigToDevice                                      */
    /*                                                                          */
    /*     Erwartet:                                                            */
    /*           address (string)                                               */
    /*                                                                          */
    /*      Liefert:                                                            */
    /*            Nichts                                                        */
    /*                                                                          */
    /* Beschreibung:                                                            */
    /* Diese Methode überträgt alle zu einem Gerät in der CCU                   */
    /* gespeicherten Konfigurationsdaten erneut an das Gerät.                   */
    /*                                                                          */
    /*--------------------------------------------------------------------------*/
    function HM_restoreConfigToDevice ($address){
      return $this->__call('restoreConfigToDevice',array($address));
    }
    /*--------------------------------------------------------------------------*/
    /*  Funktion: HM_clearConfigCache                                           */
    /*                                                                          */
    /*     Erwartet:                                                            */
    /*           address (string)                                               */
    /*                                                                          */
    /*      Liefert:                                                            */
    /*            Nichts                                                        */
    /*                                                                          */
    /* Beschreibung:                                                            */
    /* Diese Methode löscht alle zu einem Gerät in der CCU gespeicherten        */
    /* Konfigurationsdaten. Diese werden nicht sofort wieder vom Gerät          */
    /* abgefragt, sondern wenn sie das nächste mal benötigt werden.             */
    /*                                                                          */
    /*--------------------------------------------------------------------------*/
    function HM_clearConfigCache ($address){
      return $this->__call('clearConfigCache',array($address));
    }
    /*--------------------------------------------------------------------------*/
    /*  Funktion: HM_rssiInfo                                                   */
    /*                                                                          */
    /*     Erwartet:                                                            */
    /*            Nichts                                                        */
    /*                                                                          */
    /*      Liefert:                                                            */
    /*              Info (array)                                                */
    /*                                                                          */
    /*                                                                          */
    /* Beschreibung:                                                            */
    /* Gibt ein zweidimensionales assoziatives Array zurück, dessen             */
    /* Schlüssel die Geräteadressen sind. Die Felder des assoziativen           */
    /* Arrays sind Tupel, die die Empfangsfeldstärken zwischen beiden           */
    /* Schlüsselgeräten für beide Richtungen in dbm angeben. ein Wert           */
    /* von 65536 bedeutet, dass keine Informationen vorliegen.                  */
    /* · Rückgabewert[<Gerät 1>][<Gerät 2>][0]                                  */
    /* Empfangsfeldstärke an Gerät 1 für Sendungen von Gerät 2                  */
    /* · Rückgabewert[<Gerät 1>][<Gerät 2>][1]                                  */
    /* Empfangsfeldstärke an Gerät 2 für Sendungen von Gerät 1.                 */
    /*                                                                          */
    /*--------------------------------------------------------------------------*/
    function HM_rssiInfo (){
      return $this->__call('rssiInfo');
    }
    /*--------------------------------------------------------------------------*/
    /*  Funktion: HM_updateFirmware                                             */
    /*                                                                          */
    /*     Erwartet:                                                            */
    /*           devices (array)                                                */
    /*                                                                          */
    /*      Liefert:                                                            */
    /*            status (array <boolean>)                                      */
    /*                                                                          */
    /*                                                                          */
    /* Beschreibung:                                                            */
    /* Diese Methode führt ein Firmware-Update der in devices                   */
    /* enthaltenen Geräte durch. Die Geräte werden durch Ihre jeweilige         */
    /* Seriennummer spezifiziert. Der Rückgabewert gibt für jedes Gerät         */
    /* an, ob das Firmware-Update erfolgreich war.                              */
    /*                                                                          */
    /*--------------------------------------------------------------------------*/
    function HM_updateFirmware ($devices){
      return $this->__call('updateFirmware',array($devices));
    }
    /*--------------------------------------------------------------------------*/
    /*  Funktion: HM_logLevel                                                   */
    /*                                                                          */
    /*     Erwartet:                                                            */
    /*             level (integer)                                              */
    /*                                                                          */
    /*      Liefert:                                                            */
    /*             level (integer)                                              */
    /*                                                                          */
    /*                                                                          */
    /* Beschreibung:                                                            */
    /* Diese Methode gibt den aktuellen Log-Level zurück (1) bzw. setzt         */
    /* diesen (2). Folgende Werte sind für level möglich:                       */
    /* · 6=LOG_FATAL_ERROR: Nur schwere Fehler loggen.                          */
    /* · 5=LOG_ERROR: Zusätzlich normale Fehler loggen.                         */
    /* · 4=LOG_WARNING: Zusätzlich Warnungen loggen.                            */
    /* · 3=LOG_NOTICE: Zusätzlich Notizmeldungen loggen.                        */
    /* · 2=LOG_INFO: Zusätzlich Infomeldungen loggen.                           */
    /* · 1=LOG_DEBUG: Zusätzlich Debugmeldungen loggen.                         */
    /* · 0=LOG_ALL: Alles wird geloggt.                                         */
    /*                                                                          */
    /*--------------------------------------------------------------------------*/
    function HM_logLevel ($level){
      return $this->__call('logLevel',array($level));
    }
    /*--------------------------------------------------------------------------*/
    /*  Funktion: HM_reportValueUsage                                           */
    /*                                                                          */
    /*     Erwartet:                                                            */
    /*           address (string)                                               */
    /*          value_id (string)                                               */
    /*       ref_counter (integer)                                              */
    /*                                                                          */
    /*      Liefert:                                                            */
    /*            Result (boolean)                                              */
    /*                                                                          */
    /*                                                                          */
    /* Beschreibung:                                                            */
    /* Diese Methode teilt dem Interfaceprozess in ref_counter mit, wie         */
    /* oft der Wert value_id des Kanals address innerhalb der                   */
    /* Logikschicht (z.B. in Programmen) verwendet bwird. Dadurch kann          */
    /* der Interfaceprozess die Verbindung mit der entsprechenden               */
    /* Komponente herstellen bzw. löschen. Diese Funktion sollte bei            */
    /* jeder Änderung aufgerufen werden.                                        */
    /* Der Rückgabewert ist true, wenn die Aktion sofort durchgeführt           */
    /* wurde. Er ist false, wenn die entsprechende Komponente nicht             */
    /* erreicht werden konnte und vom Benutzer zunächst in den                  */
    /* Config-Mode gebracht werden muss. Der Interfaceprozess hat dann          */
    /* aber die neue Einstellung übernommen und wird sie bei nächster           */
    /* Gelegenheit automatisch an die Komponente übertragen.                    */
    /* In diesem Fall ist dann auch der Wert CONFIG_PENDING im Kanal            */
    /* MAINTENANCE der Komponente gesetzt.                                      */
    /*                                                                          */
    /*--------------------------------------------------------------------------*/
    function HM_reportValueUsage ($address, $value_id, $ref_counter){
      return $this->__call('reportValueUsage',array($address,$value_id,$ref_counter));
    }
    /*--------------------------------------------------------------------------*/
    /*  Funktion: HM_setBidcosInterface                                         */
    /*                                                                          */
    /*     Erwartet:                                                            */
    /*    device_address (string)                                               */
    /* interface_address (string)                                               */
    /*           rooming (boolean)                                              */
    /*                                                                          */
    /*      Liefert:                                                            */
    /*            Nichts                                                        */
    /*                                                                          */
    /* Beschreibung:                                                            */
    /* Diese Methode setzt das für die Kommunikation mit dem durch              */
    /* device_address spezifizierten Gerät verwendete Bidcos-Interface.         */
    /* Die Seriennummer des in Zukunft für die Kommunikation mit diesem         */
    /* Gerät zu verwendenden Interfaces wird in interface_address               */
    /* übergeben. Ist der Parameter roaming gesetzt, so wird die                */
    /* Interfacezuordnung für das Gerät automatisch in Abhängigkeit von         */
    /* der Empfangsfeldstärke angepasst. Das ist für nicht ortsfeste            */
    /* Geräte wie Fernbedienungen sinnvoll.                                     */
    /*                                                                          */
    /*--------------------------------------------------------------------------*/
    function HM_setBidcosInterface ($device_address, $interface_address, $rooming){
      return $this->__call('setBidcosInterface',array($device_address,$interface_address,$rooming));
    }
    /*--------------------------------------------------------------------------*/
    /*  Funktion: HM_listBidcosInterfaces                                       */
    /*                                                                          */
    /*     Erwartet:                                                            */
    /*            Nichts                                                        */
    /*                                                                          */
    /*      Liefert:                                                            */
    /*        Interfaces (array)                                                */
    /*                                                                          */
    /*                                                                          */
    /* Beschreibung:                                                            */
    /* Diese Methode gibt eine Liste aller vorhandenen BidCoS-Interfaces        */
    /* in Form eines Arrays von Structs zurück.                                 */
    /* Der Rückgabewert ist ein Array von Strukturen. Jede dieser               */
    /* Strukturen enthält die folgenden Felder:                                 */
    /* · ADDRESS                                                                */
    /* Datentyp String. Seriennummer des BidCoS-Interfaces.                     */
    /* · DESCRIPTION                                                            */
    /* Datentyp String. Textuelle Beschreibung des Interfaces wie in der        */
    /* Konfigurationsdatei für den Schnittstellenprozess angegeben.             */
    /* · CONNECTED                                                              */
    /* Datentyp Boolean. Gibt an, ob zum Zeitpunkt der Abfrage eine             */
    /* Kommunikationsverbindung zum Interface besteht.                          */
    /* · DEFAULT                                                                */
    /* Datentyp Boolean. Gibt an, ob es sich um das Standardinterface           */
    /* handelt. Das Standardinterface wird verwendet, wenn das einem            */
    /* Gerät zugeordnete Interface nicht mehr existiert.                        */
    /*                                                                          */
    /*--------------------------------------------------------------------------*/
    function HM_listBidcosInterfaces (){
      return $this->__call('listBidcosInterfaces');
    }
    /*--------------------------------------------------------------------------*/
    /*  Funktion: HM_getServiceMessages                                         */
    /*                                                                          */
    /*     Erwartet:                                                            */
    /*            Nichts                                                        */
    /*                                                                          */
    /*      Liefert:                                                            */
    /*            Result (array)                                                */
    /*                                                                          */
    /*                                                                          */
    /* Beschreibung:                                                            */
    /* Diese Methode gibt eine Liste aller vorhandenen Servicemeldungen         */
    /* in Form eines Arrays zurück.                                             */
    /* Der Rückgabewert ist ein Array mit einem Element pro                     */
    /* Servicemeldung. Jedes Element ist wiederum ein Array mit drei            */
    /* Feldern:                                                                 */
    /* · Rückgabewert[index][0]                                                 */
    /* Datentyp String. Adresse (Seriennummer) des Kanals, der die              */
    /* Servicemeldung generiert hat                                             */
    /* · Rückgabewert[index][1]                                                 */
    /* Datentyp String. ID der Servicemeldung (CONFIG_PENDING, UNREACH,         */
    /* etc.)                                                                    */
    /* · Rückgabewert[index][2]                                                 */
    /* Datentyp variabel. Wert der Servicemeldung                               */
    /*                                                                          */
    /*--------------------------------------------------------------------------*/
    function HM_getServiceMessages (){
      return $this->__call('getServiceMessages');
    }
    /*--------------------------------------------------------------------------*/
    /*  Funktion: HM_getAllMetadata                                             */
    /*                                                                          */
    /*     Erwartet:                                                            */
    /*         object_id (string)                                               */
    /*                                                                          */
    /*      Liefert:                                                            */
    /*            Struct (array)                                                */
    /*                                                                          */
    /*                                                                          */
    /* Beschreibung:                                                            */
    /* Diese Methode gibt alle zuvor gesetzten Metadaten zu einem Objekt        */
    /* zurück.                                                                  */
    /* object_id ist die Id des Metadaten-Objekts. Üblicherweise ist            */
    /* dies die Seriennummer eines Gerätes oder Kanals.                         */
    /* Durch Übergabe einer beliebigen Id können aber auch eigene               */
    /* Metadaten-Objekte angelegt werden.                                       */
    /* Der Rückgabewert ist ein Struct, der zu jedem zuvor gesetzten            */
    /* Metadatum ein Feld enthält. Der Feldname ist der zuvor an                */
    /* setMetadata() als Parameter data_id übergebene Wert. Der Wert des        */
    /* Feldes entspricht in Datentyp und Wert der zuvor an setMetadata()        */
    /* als Parameter value übergebenen Variablen.                               */
    /*                                                                          */
    /*--------------------------------------------------------------------------*/
    function HM_getAllMetadata ($object_id){
      return $this->__call('getAllMetadata',array($object_id));
    }
    /*--------------------------------------------------------------------------*/
    /*  Funktion: HM_getMetadata                                                */
    /*                                                                          */
    /*     Erwartet:                                                            */
    /*         object_id (string)                                               */
    /*           data_id (string)                                               */
    /*                                                                          */
    /*      Liefert:                                                            */
    /*            Result (variant)                                              */
    /*                                                                          */
    /*                                                                          */
    /* Beschreibung:                                                            */
    /* Diese Methode gibt ein Metadatum zu einem Objekt zurück.                 */
    /* object_id ist die Id des Metadaten-Objekts. Üblicherweise ist            */
    /* dies die Seriennummer eines Gerätes oder Kanals. Durch Übergabe          */
    /* einer beliebigen Id können aber auch eigene Metadaten-Objekte            */
    /* angelegt werden.                                                         */
    /* data_id ist die Id des abzufragenden Metadatums. Diese Id kann           */
    /* frei gewählt werden. Der Rückgabewert entspricht in Datentyp und         */
    /* Wert der zuvor an setMetadata() als Parameter value übergebenen          */
    /* Variablen.                                                               */
    /*                                                                          */
    /*--------------------------------------------------------------------------*/
    function HM_getMetadata ($object_id, $data_id){
      return $this->__call('getMetadata',array($object_id,$data_id));
    }
    /*--------------------------------------------------------------------------*/
    /*  Funktion: HM_setMetadata                                                */
    /*                                                                          */
    /*     Erwartet:                                                            */
    /*         object_id (string)                                               */
    /*           data_id (string)                                               */
    /*             value (variant)                                              */
    /*                                                                          */
    /*      Liefert:                                                            */
    /*            Nichts                                                        */
    /*                                                                          */
    /* Beschreibung:                                                            */
    /* Diese Methode setzt ein Metadatum zu einem Objekt.                       */
    /* object_id ist die Id des Metadaten-Objekts. Üblicherweise ist            */
    /* dies die Seriennummer eines Gerätes oder Kanals. Durch Übergabe          */
    /* einer beliebigen Id können aber auch eigene Metadaten-Objekte            */
    /* angelegt werden.                                                         */
    /* data_id ist die Id des zu setzenden Metadatums. Diese Id kann            */
    /* frei gewählt werden.                                                     */
    /* value ist eine beliebige Variable. Diese wird gespeichert und            */
    /* kann später mittels getMetadata() und getAllMetadata()wieder             */
    /* abgefragt werden.                                                        */
    /*                                                                          */
    /*--------------------------------------------------------------------------*/
    function HM_setMetadata ($object_id, $data_id, $value){
      return $this->__call('setMetadata',array($object_id,$data_id,$value));
    }
    /*--------------------------------------------------------------------------*/
    /*  Funktion: __call                                                        */
    /*                                                                          */
    /*     Erwartet:                                                            */
    /*           name (string)                                                  */
    /*      arguments (array)                                                   */
    /*                                                                          */
    /*      Liefert:                                                            */
    /*         Result (variant)                                                 */
    /*--------------------------------------------------------------------------*/
    public function __call ($name,$params=null){
        $this->request = xmlrpc_encode_request($name,$params,array('encoding'=>'utf-8'));
        return $this->Upnp();
    }
    /*--------------------------------------------------------------------------*/
    /*  Funktion: Upnp                                                          */
    /*                                                                          */
    /*     Erwartet:Nichts                                                      */
    /*      Liefert:                                                            */
    /*         Result (xml string)                                              */
    /*--------------------------------------------------------------------------*/
    private function Upnp (){
        $url=$this->_PROTOKOL.'://'.$this->_IP.':'.$this->_PORT.$this->_URL;
        $h=new HomeMatic_UpnpHelperClient($url);
        $h->send($this->request);
        $data=xmlrpc_decode($h->response());
        if (is_array($data) && xmlrpc_is_fault($data))throw new Exception($data['faultString'],$data['faultCode']);
        return $data;
    }
    public function __getFunctions (){
		return $this->__call('system.listMethods');
	}
}
class HomeMatic_UpnpHelperClient {
    private $IO = null;
    private $RESULT = null;
    public function __construct ($url){
        if ($url)$this->IO = curl_init($url);
        else throw new Exception('url parameter is required');
    }
    public function send($data, $method='POST', $output=true){
        if($method=='POST'){
            curl_setopt($this->IO, CURLOPT_POST, 1);
            curl_setopt($this->IO, CURLOPT_POSTFIELDS, $data);	
        }
        if($output)curl_setopt($this->IO, CURLOPT_RETURNTRANSFER, true);
        $this->RESULT = curl_exec($this->IO);
        curl_close($this->IO);
    }
    public function response(){	
        if(!is_null($this->RESULT))return $this->RESULT;	
    }
}

?>