<?php

require_once 'classes/Database.php';
require_once 'classes/User.php';
require_once 'classes/Queries_repository.php';
require_once 'functions.php';

session_start();

$db = new Database('localhost', 'root', '', 'yeticave');
$query_result = new Queries_repository($db);
$user = new User($query_result);


