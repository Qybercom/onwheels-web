<?php
include '../Quark/Quark.php';

use Quark\Quark;
use Quark\QuarkConfig;
use Quark\QuarkCredentials;

$config = new QuarkConfig();

$mongoSource = new QuarkCredentials('mongodb');
$mongoSource->Endpoint('host', 27017);
$mongoSource->User('login', 'password');
$mongoSource->Resource('database');

$mongoDB = new \Quark\Extensions\Mongo\Config();
$mongoDB->Source('main', $mongoSource);

$config->Extension($mongoDB);
$config->Extension(new \Quark\Extensions\Facebook\Config(
	'id',
	'secret'
));

Quark::Run($config);