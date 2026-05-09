<?php
if (isset($_POST['message'])) {
    $message = $_POST['message'];
    file_put_contents('message.txt', $message);
    echo "success";
} else {
    echo "error";
}
?>