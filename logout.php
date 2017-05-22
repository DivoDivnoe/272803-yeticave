<?php

require_once 'init.php';

$user->logout();
header('Location: /login.php');