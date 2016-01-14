<?
$Sony_FunctionsVars=array(
   "RenderingControl"=>array(
      "ListPresets"=>array(
         "InstanceID"=>array(
            "mode"=>'in',
            "default"=>0,
            "allowed"=>null,
            "typ"=>'ui4'
         ),
         "CurrentPresetNameList"=>array(
            "mode"=>'out',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'string'
         )
      ),
      "SelectPreset"=>array(
         "InstanceID"=>array(
            "mode"=>'in',
            "default"=>0,
            "allowed"=>null,
            "typ"=>'ui4'
         ),
         "PresetName"=>array(
            "mode"=>'in',
            "default"=>null,
            "allowed"=>'FactoryDefaults',
            "typ"=>'string'
         )
      ),
      "GetMute"=>array(
         "InstanceID"=>array(
            "mode"=>'in',
            "default"=>0,
            "allowed"=>null,
            "typ"=>'ui4'
         ),
         "Channel"=>array(
            "mode"=>'in',
            "default"=>''Master'',
            "allowed"=>'Master',
            "typ"=>'string'
         ),
         "CurrentMute"=>array(
            "mode"=>'out',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'boolean'
         )
      ),
      "SetMute"=>array(
         "InstanceID"=>array(
            "mode"=>'in',
            "default"=>0,
            "allowed"=>null,
            "typ"=>'ui4'
         ),
         "Channel"=>array(
            "mode"=>'in',
            "default"=>''Master'',
            "allowed"=>'Master',
            "typ"=>'string'
         ),
         "DesiredMute"=>array(
            "mode"=>'in',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'boolean'
         )
      ),
      "GetVolume"=>array(
         "InstanceID"=>array(
            "mode"=>'in',
            "default"=>0,
            "allowed"=>null,
            "typ"=>'ui4'
         ),
         "Channel"=>array(
            "mode"=>'in',
            "default"=>''Master'',
            "allowed"=>'Master',
            "typ"=>'string'
         ),
         "CurrentVolume"=>array(
            "mode"=>'out',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'ui2'
         )
      ),
      "SetVolume"=>array(
         "InstanceID"=>array(
            "mode"=>'in',
            "default"=>0,
            "allowed"=>null,
            "typ"=>'ui4'
         ),
         "Channel"=>array(
            "mode"=>'in',
            "default"=>''Master'',
            "allowed"=>'Master',
            "typ"=>'string'
         ),
         "DesiredVolume"=>array(
            "mode"=>'in',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'ui2'
         )
      )
   ),
   "ConnectionManager"=>array(
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
      ),
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
      )
   ),
   "AVTransport"=>array(
      "SetAVTransportURI"=>array(
         "InstanceID"=>array(
            "mode"=>'in',
            "default"=>0,
            "allowed"=>null,
            "typ"=>'ui4'
         ),
         "CurrentURI"=>array(
            "mode"=>'in',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'string'
         ),
         "CurrentURIMetaData"=>array(
            "mode"=>'in',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'string'
         )
      ),
      "SetNextAVTransportURI"=>array(
         "InstanceID"=>array(
            "mode"=>'in',
            "default"=>0,
            "allowed"=>null,
            "typ"=>'ui4'
         ),
         "NextURI"=>array(
            "mode"=>'in',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'string'
         ),
         "NextURIMetaData"=>array(
            "mode"=>'in',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'string'
         )
      ),
      "GetMediaInfo"=>array(
         "InstanceID"=>array(
            "mode"=>'in',
            "default"=>0,
            "allowed"=>null,
            "typ"=>'ui4'
         ),
         "NrTracks"=>array(
            "mode"=>'out',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'ui4'
         ),
         "MediaDuration"=>array(
            "mode"=>'out',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'string'
         ),
         "CurrentURI"=>array(
            "mode"=>'out',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'string'
         ),
         "CurrentURIMetaData"=>array(
            "mode"=>'out',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'string'
         ),
         "NextURI"=>array(
            "mode"=>'out',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'string'
         ),
         "NextURIMetaData"=>array(
            "mode"=>'out',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'string'
         ),
         "PlayMedium"=>array(
            "mode"=>'out',
            "default"=>null,
            "allowed"=>'NETWORK',
            "typ"=>'string'
         ),
         "RecordMedium"=>array(
            "mode"=>'out',
            "default"=>null,
            "allowed"=>'NOT_IMPLEMENTED',
            "typ"=>'string'
         ),
         "WriteStatus"=>array(
            "mode"=>'out',
            "default"=>null,
            "allowed"=>'NOT_IMPLEMENTED',
            "typ"=>'string'
         )
      ),
      "GetTransportInfo"=>array(
         "InstanceID"=>array(
            "mode"=>'in',
            "default"=>0,
            "allowed"=>null,
            "typ"=>'ui4'
         ),
         "CurrentTransportState"=>array(
            "mode"=>'out',
            "default"=>null,
            "allowed"=>'STOPPED|PLAYING|PAUSED_PLAYBACK|TRANSITIONING|NO_MEDIA_PRESENT',
            "typ"=>'string'
         ),
         "CurrentTransportStatus"=>array(
            "mode"=>'out',
            "default"=>null,
            "allowed"=>'OK|ERROR_OCCURRED',
            "typ"=>'string'
         ),
         "CurrentSpeed"=>array(
            "mode"=>'out',
            "default"=>null,
            "allowed"=>'1',
            "typ"=>'string'
         )
      ),
      "GetPositionInfo"=>array(
         "InstanceID"=>array(
            "mode"=>'in',
            "default"=>0,
            "allowed"=>null,
            "typ"=>'ui4'
         ),
         "Track"=>array(
            "mode"=>'out',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'ui4'
         ),
         "TrackDuration"=>array(
            "mode"=>'out',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'string'
         ),
         "TrackMetaData"=>array(
            "mode"=>'out',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'string'
         ),
         "TrackURI"=>array(
            "mode"=>'out',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'string'
         ),
         "RelTime"=>array(
            "mode"=>'out',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'string'
         ),
         "AbsTime"=>array(
            "mode"=>'out',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'string'
         ),
         "RelCount"=>array(
            "mode"=>'out',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'i4'
         ),
         "AbsCount"=>array(
            "mode"=>'out',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'i4'
         )
      ),
      "GetDeviceCapabilities"=>array(
         "InstanceID"=>array(
            "mode"=>'in',
            "default"=>0,
            "allowed"=>null,
            "typ"=>'ui4'
         ),
         "PlayMedia"=>array(
            "mode"=>'out',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'string'
         ),
         "RecMedia"=>array(
            "mode"=>'out',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'string'
         ),
         "RecQualityModes"=>array(
            "mode"=>'out',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'string'
         )
      ),
      "GetTransportSettings"=>array(
         "InstanceID"=>array(
            "mode"=>'in',
            "default"=>0,
            "allowed"=>null,
            "typ"=>'ui4'
         ),
         "PlayMode"=>array(
            "mode"=>'out',
            "default"=>null,
            "allowed"=>'NORMAL|RANDOM|REPEAT_ONE|REPEAT_ALL',
            "typ"=>'string'
         ),
         "RecQualityMode"=>array(
            "mode"=>'out',
            "default"=>null,
            "allowed"=>'NOT_IMPLEMENTED',
            "typ"=>'string'
         )
      ),
      "Stop"=>array(
         "InstanceID"=>array(
            "mode"=>'in',
            "default"=>0,
            "allowed"=>null,
            "typ"=>'ui4'
         )
      ),
      "Play"=>array(
         "InstanceID"=>array(
            "mode"=>'in',
            "default"=>0,
            "allowed"=>null,
            "typ"=>'ui4'
         ),
         "Speed"=>array(
            "mode"=>'in',
            "default"=>1,
            "allowed"=>'1',
            "typ"=>'string'
         )
      ),
      "Pause"=>array(
         "InstanceID"=>array(
            "mode"=>'in',
            "default"=>0,
            "allowed"=>null,
            "typ"=>'ui4'
         )
      ),
      "Seek"=>array(
         "InstanceID"=>array(
            "mode"=>'in',
            "default"=>0,
            "allowed"=>null,
            "typ"=>'ui4'
         ),
         "Unit"=>array(
            "mode"=>'in',
            "default"=>null,
            "allowed"=>'TRACK_NR|REL_TIME',
            "typ"=>'string'
         ),
         "Target"=>array(
            "mode"=>'in',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'string'
         )
      ),
      "Next"=>array(
         "InstanceID"=>array(
            "mode"=>'in',
            "default"=>0,
            "allowed"=>null,
            "typ"=>'ui4'
         )
      ),
      "Previous"=>array(
         "InstanceID"=>array(
            "mode"=>'in',
            "default"=>0,
            "allowed"=>null,
            "typ"=>'ui4'
         )
      ),
      "SetPlayMode"=>array(
         "InstanceID"=>array(
            "mode"=>'in',
            "default"=>0,
            "allowed"=>null,
            "typ"=>'ui4'
         ),
         "NewPlayMode"=>array(
            "mode"=>'in',
            "default"=>null,
            "allowed"=>'NORMAL|RANDOM|REPEAT_ONE|REPEAT_ALL',
            "typ"=>'string'
         )
      ),
      "GetCurrentTransportActions"=>array(
         "InstanceID"=>array(
            "mode"=>'in',
            "default"=>0,
            "allowed"=>null,
            "typ"=>'ui4'
         ),
         "Actions"=>array(
            "mode"=>'out',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'string'
         )
      ),
      "X_GetOperationList"=>array(
         "AVTInstanceID"=>array(
            "mode"=>'in',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'ui4'
         ),
         "OperationList"=>array(
            "mode"=>'out',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'string'
         )
      ),
      "X_ExecuteOperation"=>array(
         "AVTInstanceID"=>array(
            "mode"=>'in',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'ui4'
         ),
         "ActionDirective"=>array(
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
         )
      )
   ),
   "IRCC"=>array(
      "X_SendIRCC"=>array(
         "IRCCCode"=>array(
            "mode"=>'in',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'string'
         )
      ),
      "X_GetStatus"=>array(
         "CategoryCode"=>array(
            "mode"=>'in',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'string'
         ),
         "CurrentStatus"=>array(
            "mode"=>'out',
            "default"=>null,
            "allowed"=>'0|801|804|805|806',
            "typ"=>'string'
         ),
         "CurrentCommandInfo"=>array(
            "mode"=>'out',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'string'
         )
      )
   ),
   "X_Tandem"=>array(
      "X_Tandem"=>null
   ),
   "X_CIS"=>array(
      "X_CIS_Command"=>array(
         "CISDATA"=>array(
            "mode"=>'in',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'string'
         ),
         "Res_CISDATA"=>array(
            "mode"=>'out',
            "default"=>null,
            "allowed"=>null,
            "typ"=>'string'
         )
      )
   ),
   "SonyUpnpClass"=>array(
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
   "SonyXmlRpcDevice"=>array(
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