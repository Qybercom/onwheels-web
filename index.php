<?php
include '../Quark/Quark.php';

use Quark\Quark;
use Quark\QuarkConfig;
use Quark\QuarkCredentials;
use Quark\QuarkCultureRU;

$config = new QuarkConfig();
//$config->Culture(new QuarkCultureRU());

$mongoSource = new QuarkCredentials('mongodb');
$mongoSource->Endpoint('127.0.0.1', 27017);
$mongoSource->User('webapp', 'onwheels!web');
$mongoSource->Resource('onwheels');

$mongoDB = new \Quark\Extensions\Mongo\Config();
$mongoDB->Source('main', $mongoSource);

$config->Extension($mongoDB);
$config->Extension(new \Quark\Extensions\Facebook\Config(
	'678583925524753',
	'0f6e778d59e8a74b5efb78b434cb9c46'
));

Quark::On(Quark::EVENT_HTTP_EXCEPTION, function () {
	echo 'Something wrong...';
});

Quark::On(Quark::EVENT_CONNECTION_EXCEPTION, function ($e) {
	print_r($e);
});

Quark::Run($config);