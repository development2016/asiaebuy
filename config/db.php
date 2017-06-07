<?php
//$cert = 'C:\xampp\htdocs\asiaebuy\compose.crt';
/*
$cert = '/var/www/vhosts/asiaebuy.com/httpdocs/stage/compose.crt';

$ctx = stream_context_create(array(
    "ssl" => array(
        "cafile"            => $cert,
        "allow_self_signed" => false,
        "verify_peer"       => true, 
        "verify_peer_name"  => true,
        "verify_expiry"     => true, 
    ),
));

return [

      
	'class' => '\yii\mongodb\Connection',
	'dsn' => 'mongodb://asiaebuy:Amtujpino.2017@aws-ap-southeast-1-portal.1.dblayer.com:15403/asiaebuy',
	'options' => [
		'ssl' => true
	],
	'driverOptions' => [
		'context' => $ctx
	]

];
*/



return [

      
    'class' => '\yii\mongodb\Connection',
    'dsn' => 'mongodb://asia:Amtujpino.1@127.0.0.1:27017/asiaebuy',



   //'class' => '\yii\mongodb\Connection',
   //'dsn' => 'mongodb://asia:Amtujpino.1@localhost:27017/asiaebuy',
    // for tem use this connect gii for mongo
]; 
