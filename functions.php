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
    return $pdo->lastInsertId();
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

    if ($user) {
        $password = password_verify($password, $user['password']);
        if ($password)
            $_SESSION['user'] = $user;
        return true;
    } else {
        return false;
    }
}

/**
 * Return value: null
 */
function set_session()
{
    $_SESSION['user'];
}

/**
 * Return value: boolean
 */
function is_not_logged_in()
{
    if (!isset($_SESSION['user']))
        return true;
    return false;
}

/**
 * Return value: boolean
 */
function is_admin()
{
    $role = $_SESSION['user']['role'];
    if ($role == 'admin')
        return true;
    return false;
}

/**
 * Return value: array
 */
function get_users()
{
    $pdo = new PDO("mysql:host=localhost;dbname=diving", "root", "root");
    $query = 'SELECT * FROM users';
    $statement = $pdo->prepare($query);
    $statement->execute();
    return $statement->fetchAll(2);
}

/**
 * Parameters: value: null
 */
function edit_information($user_id, $firstname, $job_title, $phone, $address)
{
    $params = [
        'id' => $user_id,
        'fname' => $firstname,
        'job_title' => $job_title,
        'phone' => $phone,
        'address' => $address
    ];

    $pdo = new PDO("mysql:host=localhost;dbname=diving", "root", "root");
    $query = 'UPDATE users SET fname =:fname, job_title =:job_title, phone =:phone, address =:address WHERE id =:id';
    $statement = $pdo->prepare($query);
    $statement->execute($params);

    return boolval($statement);
}

function set_status($status, $user_id)
{
    $pdo = new PDO("mysql:host=localhost;dbname=diving", "root", "root");
    $query = 'UPDATE users SET status =:status WHERE id =:id';
    $statement = $pdo->prepare($query);
    $statement->execute([
        'status' => $status,
        'id' => $user_id
    ]);

    return boolval($statement);
}

function upload_avatar($img, $user_id)
{
    $dir = 'img/uploads/';
    $file = basename($img["name"]);

    $user = get_user_by_id($user_id);
    if ($user['img'] == $dir . $file)
        return true;

    if (!$user['img']){
        move_uploaded_file($img['tmp_name'], $dir . $file);
    } else{
        unlink($user['img']);
        move_uploaded_file($img['tmp_name'], $dir . $file);
    }

    $pdo = new PDO("mysql:host=localhost;dbname=diving", "root", "root");
    $query = 'UPDATE users SET img =:img WHERE id =:id';
    $statement = $pdo->prepare($query);
    $statement->execute([
        'img' => $dir . $file,
        'id' => $user_id
    ]);
    return boolval($statement);
}

function add_social_links($telegram, $instagram, $vk, $user_id)
{
    $pdo = new PDO("mysql:host=localhost;dbname=diving", "root", "root");
    $query = 'UPDATE users SET vk_link_href =:vk_link_href, telegram_link_href =:telegram_link_href, instagram_link_href =:instagram_link_href WHERE id =:id';
    $statement = $pdo->prepare($query);
    $statement->execute([
        'vk_link_href' => $vk,
        'telegram_link_href' => $telegram,
        'instagram_link_href' => $instagram,
        'id' => $user_id
    ]);

    return boolval($statement);
}

function is_author($logged_user_id, $edit_user_id)
{
    $loggelUserId = get_user_by_id($logged_user_id);
    if ($loggelUserId['id'] == $edit_user_id)
        return true;
    return false;
}

function get_user_by_id($id)
{
    $pdo = new PDO("mysql:host=localhost;dbname=diving", "root", "root");
    $sql = 'SELECT * FROM users WHERE id =:id';
    $statement = $pdo->prepare($sql);
    $statement->execute(['id' => $id]);
    return $statement->fetch(2);
}

function edit_credentials($user_id, $email, $password)
{
    $user = get_user_by_email($email);

    if ($user['email'] == $email){
        if ($user['id'] != $user_id)
            return false;
    }

    $pdo = new PDO("mysql:host=localhost;dbname=diving", "root", "root");
    $sql = 'UPDATE users SET email =:email, password =:password WHERE id=:id';
    $statement = $pdo->prepare($sql);
    $statement->execute([
        'id' => $user_id,
        'email' => $email,
        'password' => password_hash($password, PASSWORD_DEFAULT)
    ]);
    return boolval($statement);
}

?>
