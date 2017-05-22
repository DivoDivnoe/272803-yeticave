<?php

require_once 'classes/Database.php';
require_once 'classes/User.php';
require_once 'functions.php';

session_start();

$db = new Database('localhost', 'root', '', 'yeticave');
$user = new User($db);

