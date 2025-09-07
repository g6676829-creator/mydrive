<?php
//create complicated pattern
$pattern = "Q}/?p`Ao]{ZiX~uSyW$-tEr)_De2Cw#Vq8%*FaRs7TdG=+[fB6g^N3hH4jYk9U!lJ5@mMnK&(bI;:<z .,v1OcL\|>0xP";

$len = strlen($pattern)-1;
$password = [];
for ($i=0; $i < 8; $i++) { 
	$indexing = rand(0,$len);
	$password[] = $pattern[$indexing];
}

echo implode($password);

?>