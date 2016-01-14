<?
function AutoLoadRpcClasses($className) { 
	$fn=__DIR__ . "\\$className.class.php";
echo "Autoload: $fn<br>";	
	if( file_exists($fn ) ){ 
		include_once($fn);
	}else{ 
		//set_include_path(ROOT_DIR.DS.'lib'.DS); 
		spl_autoload($className ); 
	} 
} 
spl_autoload_register('AutoLoadRpcClasses'); 
?>