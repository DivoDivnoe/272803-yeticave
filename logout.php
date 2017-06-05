<?php

require_once 'classes/Database.php';
require_once 'classes/User.php';
require_once 'classes/Users_repository.php';
require_once 'configs/database_connect_data.php';

session_start();

$db = new Database(...$connect_data);
$users_repository = new UsersRepository($db);
$user = new User($users_repository);

$user->logout();
header('Location: login.php');
