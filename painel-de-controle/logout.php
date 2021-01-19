<?php

session_start();
session_unset();
$_SESSION['user'] = null;
$_SESSION['name'] = null;
$_SESSION['picture'] = null;
$_SESSION['isAuth'] = false;
session_destroy();

header('Location: ../entrar');