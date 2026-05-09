<?php
session_start();

if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
    echo 'admin';
} else {
    echo 'not_admin';
}
?>