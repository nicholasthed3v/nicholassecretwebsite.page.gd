<?php
// Set session cookie parameters before starting session
ini_set('session.cookie_lifetime', 86400);
ini_set('session.cookie_path', '/');
ini_set('session.cookie_secure', 0);
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_samesite', 'Lax');

session_start();

$loggedInUser = $_SESSION['logged_in_user'] ?? 'null';
$guestMode = isset($_SESSION['guest_mode']) && $_SESSION['guest_mode'] ? 'true' : 'false';

echo $loggedInUser . '|' . $guestMode;
?>
session_start();

$loggedInUser = $_SESSION['logged_in_user'] ?? 'null';
$guestMode = isset($_SESSION['guest_mode']) && $_SESSION['guest_mode'] ? 'true' : 'false';

echo $loggedInUser . '|' . $guestMode;
?>