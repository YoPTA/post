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
        $_SESSION['session_start_time'] = time(); // Начало сессии
    }

    /*
     * Проверяем время начала входа пользователя.
     * Если время более 1 суток, то сессия отчищается.
     */
    public static function checkSessionTime()
    {
        $time_now = time();
        $time_limit = 86400;
        if ($time_now > $_SESSION['session_start_time'] +  $time_limit )
        {
            // Стартуем сессию
            $_SESSION = array();
            session_destroy ();
            // Перенаправляем пользователя на главную страницу
            header("Location: /site/login");
        }
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
          user.lastname,
          user.firstname,
          user.middlename,
          user.role_id,
          user.group_id,
          user.company_address_id,
          company_address.local_place_id
        FROM
          user
          INNER JOIN company_address ON (user.company_address_id = company_address.id)
        WHERE
          user.id = :id';
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

    /*
     * Получаем пользователей
     * @var $parameter int - параметр
     *
     * Возможные варианты $parameter:
     * 1 - Пользователи, которые относятся к организации
     * 2 - Пользователи, которые относятся к рйону
     *
     * @var $address_or_place_id int - ID ареса организации или района
     * return array()
     */
    public static function getUsersCompanyAddressOrLocalPlace($parameter = 1, $address_or_place_id = 0)
    {
        $where = '';

        if ($address_or_place_id == 0)
        {
            return false;
        }

        if ($parameter == 1)
        {
            $where = ' AND
                user.company_address_id = :address_or_place_id ';
        }
        else
        {
            $where = ' AND
                company_address.local_place_id = :address_or_place_id ';
        }

        $sql = 'SELECT
          user.id,
          user.lastname,
          user.firstname,
          user.middlename,
          user.login,
          user.role_id,
          user_role.is_notification
        FROM
          user
          INNER JOIN user_role ON (user.role_id = user_role.id)
          INNER JOIN company_address ON (user.company_address_id = company_address.id)
        WHERE
          user.flag > 0 ' . $where;

        $db = Database::getConnection();

        $result = $db->prepare($sql);
        $result->bindParam(':address_or_place_id', $address_or_place_id, PDO::PARAM_INT);
        $result->execute();

        // Получение и возврат результатов
        $users = null;
        $i = 0;
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $users[$i] = $row;
            $i++;
        }
        return $users;
    }
}