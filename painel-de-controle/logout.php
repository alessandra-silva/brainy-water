<?php

session_destroy();
session_unset();
$_SESSION['user'] = null;
$_SESSION['name'] = null;
$_SESSION['picture'] = null;
$_SESSION['isAuth'] = false;

header('Location: ../entrar');