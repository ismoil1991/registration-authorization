<?php
session_start();
include 'functions.php';
$user = get_user_by_email($_POST['email']);

if (!empty($user)){
    $message = '<strong>Этот эл. адрес уже занят другим пользователем.</strong>';
    $name ='danger';
    set_flash_message($name, $message);

    redirect_to('Location: /create_user.php');
} else {
    $userId = add_user($_POST['email'], $_POST['password']);
    edit_information($userId, $_POST['firstname'], $_POST['job_title'], $_POST['phone'], $_POST['address']);
    set_status($_POST['status'], $userId);
    upload_avatar($_FILES['img'],$userId);
    add_social_links($_POST['telegram_link_href'], $_POST['instagram_link_href'], $_POST['vk_link_href'], $userId);

    $message = '<strong>Пользователь добавлен.</strong>';
    $name ='success';
    set_flash_message($name, $message);

    redirect_to('Location: /users.php');
}
