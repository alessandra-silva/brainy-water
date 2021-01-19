<?php

http_response_code(401);

session_start();
$isAuth = isset($_SESSION['user']);
if ($isAuth && $_SESSION['isAuth']) {
	http_response_code(200);
} else {
	http_response_code(401);
}