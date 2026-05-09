<?php
require_once 'profanity_filter.php';

if (isset($_POST['text'])) {
    $text = $_POST['text'];
    if (isProfane($text)) {
        echo 'profane';
    } else {
        echo 'clean';
    }
}
?>