<?php


class User
{
    /*******************************************************
     ********************* Поля класса *********************
     *******************************************************/



    /***********************************************************
     ********************** Методы класса **********************
     ***********************************************************/

    /*
     * Проверяем данные о пользователе
     * @var $login string - логин
     * @var $passwrod string - пароль
     *
     */
    public static function checkUserData($login, $password)
    {
        $sql = 'SELECT id FROM user WHERE login = :login AND password = :password';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':login', $login, PDO::PARAM_STR);
        $result->bindParam(':password', $password, PDO::PARAM_INT);
        $result->execute();

        // Обращаемся к записи
        $user = $result->fetch(PDO::FETCH_ASSOC);

        if($user)
        {
            return $user['id'];
        }
        return false;
    }

    /*
     * Запомниаем пользователя
     * @var $userId int - id пользователя
     */
    public static function auth($userId)
    {
        session_start();
        $_SESSION['user'] = $userId;
    }

    /*
     * Проверяем авторизовался ли пользователь
     */
    public static function checkLogged()
    {
        session_start();
        // Если сессия есть, возвращаем id пользователя
        if(isset($_SESSION['user']))
        {
            return $_SESSION['user'];
        }

        header('Location: /site/login');
    }

    /*
     * Получаем информацию о пользователе по Id
     * @var $id integer - id пользователя
     * retrun array() OR boolean
     */
    public static function getUser($id)
    {
        $sql = 'SELECT
          lastname, firstname, middlename, role_id,
          group_id, company_address_id
          FROM user
          WHERE id = :id';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->execute();

        // Обращаемся к записи
        $user = $result->fetch(PDO::FETCH_ASSOC);

        if($user)
        {
            return $user;
        }
        return false;
    }

    /*
     * Проверяет, имеется ли запись в БД с таким login
     * @var $login string - логин
     * return int or boolean
     */
    public static function searchLogin($login)
    {
        $sql = 'SELECT id FROM user WHERE login = :login';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':login', $login, PDO::PARAM_STR);
        $result->execute();

        // Обращаемся к записи
        $user = $result->fetch(PDO::FETCH_ASSOC);

        if($user)
        {
            return $user['id'];
        }
        return false;
    }

    /*
     * Добавление нового пользователя
     * @var $user array - Информация о пользвателе
     * return int or boolean
     */
    public static function createUser($user)
    {
        $sql = 'INSERT INTO user '
                    .'(lastname, firstname, middlename, login, password, role_id, flag) '
                    .'VALUES '
                    .'(:lastname, :firstname, :middlename, :login, :password, :role_id, :flag)';

        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':lastname', $user['lastname'], PDO::PARAM_STR);
        $result->bindParam(':firstname', $user['firstname'], PDO::PARAM_STR);
        $result->bindParam(':middlename', $user['middlename'], PDO::PARAM_STR);
        $result->bindParam(':login', $user['login'], PDO::PARAM_STR);
        $result->bindParam(':password', $user['password'], PDO::PARAM_STR);
        $result->bindParam(':role_id', $user['role_id'], PDO::PARAM_INT);
        $result->bindParam(':flag', $user['flag'], PDO::PARAM_INT);

        //$result->execute();

        if($result->execute())
        {
            return $db->lastInsertId();
        }
        return false;
    }
}