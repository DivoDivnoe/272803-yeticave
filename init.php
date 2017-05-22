<?php

require_once 'classes/Database.php';
require_once 'classes/User.php';
require_once 'functions.php';

session_start();

$db = new Database(['localhost', 'root', '', 'yeticave']);

if (isset($_SESSION['user'])) {
    $user = new User([$_SESSION['user'], $_SESSION['email'], $_SESSION['avatar'], true]);
} else {
    $user = new User([]);
}
