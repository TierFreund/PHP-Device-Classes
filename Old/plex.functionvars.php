<?
$Plex_FunctionsVars=array(
   "X_MS_MediaReceiverRegistrar"=>array(
      "IsAuthorized"=>array(
         "DeviceID"=>array(
            "mode"=>'in',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'string'
         ),
         "Result"=>array(
            "mode"=>'out',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'int'
         )
      ),
      "RegisterDevice"=>array(
         "RegistrationReqMsg"=>array(
            "mode"=>'in',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'bin.base64'
         ),
         "RegistrationRespMsg"=>array(
            "mode"=>'out',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'bin.base64'
         )
      ),
      "IsValidated"=>array(
         "DeviceID"=>array(
            "mode"=>'in',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'string'
         ),
         "Result"=>array(
            "mode"=>'out',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'int'
         )
      )
   ),
   "ContentDirectory"=>array(
      "Browse"=>array(
         "ObjectID"=>array(
            "mode"=>'in',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'string'
         ),
         "BrowseFlag"=>array(
            "mode"=>'in',
            "default"=>null,
            "allowed"=>'BrowseMetadata|BrowseDirectChildren',
            "typ"=>'string'
         ),
         "Filter"=>array(
            "mode"=>'in',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'string'
         ),
         "StartingIndex"=>array(
            "mode"=>'in',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'ui4'
         ),
         "RequestedCount"=>array(
            "mode"=>'in',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'ui4'
         ),
         "SortCriteria"=>array(
            "mode"=>'in',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'string'
         ),
         "Result"=>array(
            "mode"=>'out',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'string'
         ),
         "NumberReturned"=>array(
            "mode"=>'out',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'ui4'
         ),
         "TotalMatches"=>array(
            "mode"=>'out',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'ui4'
         ),
         "UpdateID"=>array(
            "mode"=>'out',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'ui4'
         )
      ),
      "GetSortCapabilities"=>array(
         "SortCaps"=>array(
            "mode"=>'out',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'string'
         )
      ),
      "GetSystemUpdateID"=>array(
         "Id"=>array(
            "mode"=>'out',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'ui4'
         )
      ),
      "GetSearchCapabilities"=>array(
         "SearchCaps"=>array(
            "mode"=>'out',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'string'
         )
      )
   ),
   "ConnectionManager"=>array(
      "GetCurrentConnectionInfo"=>array(
         "ConnectionID"=>array(
            "mode"=>'in',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'i4'
         ),
         "RcsID"=>array(
            "mode"=>'out',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'i4'
         ),
         "AVTransportID"=>array(
            "mode"=>'out',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'i4'
         ),
         "ProtocolInfo"=>array(
            "mode"=>'out',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'string'
         ),
         "PeerConnectionManager"=>array(
            "mode"=>'out',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'string'
         ),
         "PeerConnectionID"=>array(
            "mode"=>'out',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'i4'
         ),
         "Direction"=>array(
            "mode"=>'out',
            "default"=>null,
            "allowed"=>'Input|Output',
            "typ"=>'string'
         ),
         "Status"=>array(
            "mode"=>'out',
            "default"=>null,
            "allowed"=>'OK|ContentFormatMismatch|InsufficientBandwidth|UnreliableChannel|Unknown',
            "typ"=>'string'
         )
      ),
      "GetProtocolInfo"=>array(
         "Source"=>array(
            "mode"=>'out',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'string'
         ),
         "Sink"=>array(
            "mode"=>'out',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'string'
         )
      ),
      "GetCurrentConnectionIDs"=>array(
         "ConnectionIDs"=>array(
            "mode"=>'out',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'string'
         )
      )
   ),
   "PlexUpnpClass"=>array(
      "__construct"=>array(
         "BASE"=>array(
            "mode"=>'in',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'object'
         ),
         "SERVICE"=>array(
            "mode"=>'in',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'string'
         ),
         "SERVICEURL"=>array(
            "mode"=>'in',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'string'
         ),
         "EVENTURL"=>array(
            "mode"=>'in',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'string'
         )
      ),
      "RegisterEventCallback"=>array(
         "callback_url"=>array(
            "mode"=>'in',
            "default"=>'',
            "allowed"=>null,
            "typ"=>'string'
         ),
         "timeout"=>array(
            "mode"=>'in',
            "default"=>'3600',
            "allowed"=>null,
            "typ"=>'int'
         ),
         "SID"=>array(
            "mode"=>'out',
            "default"=>'',
            "allowed"=>null,
            "typ"=>'string'
         ),
         "TIMEOUT"=>array(
            "mode"=>'out',
            "default"=>'',
            "allowed"=>null,
            "typ"=>'int'
         ),
         "Server"=>array(
            "mode"=>'out',
            "default"=>'',
            "allowed"=>null,
            "typ"=>'string'
         )
      ),
      "UnRegisterEventCallback"=>array(
         "SID"=>array(
            "mode"=>'in',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'string'
         )
      )
   ),
   "PlexXmlRpcDevice"=>array(
      "__construct"=>array(
         "url"=>array(
            "mode"=>'in',
            "default"=>'',
            "allowed"=>null,
            "typ"=>'string'
         )
      ),
      "Upnp"=>array(
         "url"=>array(
            "mode"=>'in',
            "default"=>'',
            "allowed"=>null,
            "typ"=>'string'
         ),
         "SOAP_service"=>array(
            "mode"=>'in',
            "default"=>'',
            "allowed"=>null,
            "typ"=>'string'
         ),
         "SOAP_action"=>array(
            "mode"=>'in',
            "default"=>'',
            "allowed"=>null,
            "typ"=>'string'
         ),
         "SOAP_arguments"=>array(
            "mode"=>'in',
            "default"=>'',
            "allowed"=>null,
            "typ"=>'string'
         ),
         "XML_filter"=>array(
            "mode"=>'in',
            "default"=>'',
            "allowed"=>null,
            "typ"=>'string'
         ),
         "result"=>array(
            "mode"=>'out',
            "default"=>'',
            "allowed"=>null,
            "typ"=>'string|array'
         )
      ),
      "Filter"=>array(
         "subject"=>array(
            "mode"=>'in',
            "default"=>'',
            "allowed"=>null,
            "typ"=>'string'
         ),
         "pattern"=>array(
            "mode"=>'in',
            "default"=>'',
            "allowed"=>null,
            "typ"=>'string|stringlist|array of strings'
         ),
         "result"=>array(
            "mode"=>'out',
            "default"=>'',
            "allowed"=>null,
            "typ"=>'variant'
         )
      )
   )
);
?>