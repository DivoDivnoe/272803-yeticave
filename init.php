<?php

require_once 'classes/Database.php';
require_once 'classes/User.php';
require_once 'classes/Queries_repository.php';
require_once 'classes/Categories_repository.php';
require_once 'classes/Users_repository.php';
require_once 'classes/Lots_repository.php';
require_once 'classes/Bets_repository.php';
require_once 'functions.php';

session_start();

$db = new Database('localhost', 'root', '', 'yeticave');
$users_queries = new Users_repository($db);
$categories_queries = new Categories_repository($db);
$lots_queries = new Lots_repository($db);
$bets_queries = new Bets_repository($db);
$user = new User($users_queries);


