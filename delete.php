<?php
session_start();
include 'functions.php';

if (!is_admin()) {
    if (!is_author($_SESSION['user']['id'], $_GET['id'])) {
        $name = 'danger';
        $message = 'Можно изменить только свои данные';
        set_flash_message($name, $message);

        redirect_to('Location: /users.php');
    } else {
        $delete = delete($_GET['id']);
        unset($_SESSION['user']);
        redirect_to('Location: /page_register.php');
    }
} else {
    $delete = delete($_GET['id']);
    redirect_to('Location: /users.php');
}
