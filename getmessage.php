<?php
if (file_exists('message.txt')) {
    echo file_get_contents('message.txt');
} else {
    echo "Aiden is BANNED FOREVER";
}
?>