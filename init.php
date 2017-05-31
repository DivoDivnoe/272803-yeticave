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
$users_repository = new UsersRepository($db);
$categories_repository = new CategoriesRepository($db);
$lots_repository = new LotsRepository($db);
$bets_repository = new BetsRepository($db);
$user = new User($users_repository);


