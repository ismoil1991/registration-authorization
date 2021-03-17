<?php
session_start();
/**
 * Parametrs:
 * string - email
 *
 * Description: поиск пользователя по эл. адресу
 *
 * Return value: array
 **/
function get_user_by_email($email)
{
    $pdo = new PDO("mysql:host=localhost;dbname=diving", "root", "root");
    $sql = 'SELECT * FROM users WHERE email =:email';
    $statement = $pdo->prepare($sql);
    $statement->execute(['email' => $email]);
    return $statement->fetch(2);
}

/**
 * Parametrs:
 * string - email
 * string - password
 *
 * Description: добавить пользователя в БД
 *
 * Return value: null
 **/
function add_user($email, $password)
{
    $pdo = new PDO("mysql:host=localhost;dbname=diving", "root", "root");
    $sql = 'INSERT INTO users (email,password) VALUES (:email,:password)';
    $statement = $pdo->prepare($sql);
    $statement->execute([
        'email' => $email,
        'password' => password_hash($password, PASSWORD_DEFAULT)
    ]);
}

/**
 * Parametrs:
 * string - $name (ключ)
 * string - $message (значение, текст сообщения)
 *
 * Description: подготовить флеш сообщение
 *
 * Return value: null
 **/
function set_flash_message($name, $message)
{
    $_SESSION[$name] = $message;
}

/**
 * Parametrs:
 * string - $name (ключ)
 *
 * Description: вывести флеш сообщение
 *
 * Return value: null
 **/
function display_flash_message($name)
{
    if ($_SESSION[$name]) {
        echo '<div class="alert alert-' . $name . ' text-dark" role="alert">' . $_SESSION[$name] . '</div>';
        unset($_SESSION[$name]);
    }
}

/**
 * Parametrs:
 * string - $path
 *
 * Description: перенаправить на другую страницу
 *
 * Return value: null
 **/
function redirect_to($path)
{
    header($path);
    exit;
}

/**
 * Parametrs:
 * string - $email
 * string - $password
 *
 * Description: авторизовать пользователя
 *
 * Return value: boolean
 */
function login($email, $password)
{
    $pdo = new PDO("mysql:host=localhost;dbname=diving", "root", "root");
    $sql = 'SELECT * FROM users WHERE email =:email';
    $statement = $pdo->prepare($sql);
    $statement->execute(['email' => $email]);
    $user = $statement->fetch(2);

    $password = password_verify($password, $user['password']);

    if ($user['email'] == $email && $user['password'] == $password)
        return true;
    return false;
}
