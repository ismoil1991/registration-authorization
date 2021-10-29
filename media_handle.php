<?php
session_start();
include "functions.php";

$edit = upload_avatar($_FILES['img'],$_SESSION['user']['id']);

if ($edit){
    $message = '<strong>Успешно изменен</strong>';
    $name ='success';
    set_flash_message($name, $message);

    redirect_to('Location: /page_profile.php?id='.$_SESSION['user']['id']);
} else {
    $message = '<strong>Что-то пошло не так</strong>';
    $name ='danger';
    set_flash_message($name, $message);

    redirect_to('Location: /edit.php?id='.$_SESSION['user']['id']);
}
