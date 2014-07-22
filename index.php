<?php
include '../Quark/Quark.php';

use Quark\Quark;
use Quark\QuarkConfig;
use Quark\QuarkCredentials;

$config = new QuarkConfig();

$mongoSource = new QuarkCredentials('mongodb');
$mongoSource->Endpoint('127.0.0.1', 27017);
$mongoSource->User('user', 'pass');
$mongoSource->Resource('onwheels');

$mongoDB = new \Quark\Extensions\Mongo\Config();
$mongoDB->Source('main', $mongoSource);

$config->Extension($mongoDB);
$config->Extension(new \Quark\Extensions\Facebook\Config(
	'app_id',
	'app_secret'
));

Quark::Run($config);