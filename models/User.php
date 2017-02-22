<?php


class User
{
    /*******************************************************
     ********************* Поля класса *********************
     *******************************************************/

    const SHOW_BY_DEFAULT = 20;

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
          user.login,
          user.role_id,
          user.group_id,
          user.company_address_id,
          user.workpost,
          user.flag,
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

    /*
     * Получаем пользователей, удовлетворяющих параметрам поиска
     * @var $search array() - параметры поиска
     * @var $page int - номер страницы
     * return array()
     */
    public static function getUsers($search, $page = 1)
    {
        $where = '';

        $lastname = '';
        $firstname = '';
        $middlename = '';
        $login = '';

        $str_segments = explode(' ', $search['fio_or_login']);

        if (count($str_segments) == 3)
        {
            $lastname = $str_segments[0];
            $firstname = $str_segments[1];
            $middlename = $str_segments[2];
            $where = ' AND (user.lastname LIKE ?
            AND user.firstname LIKE ?
            AND user.middlename LIKE ?) ';
        }
        if (count($str_segments) == 2)
        {
            $lastname = $str_segments[0];
            $firstname = $str_segments[1];
            $where = ' AND (user.lastname LIKE ?
            AND user.firstname LIKE ?) ';
        }
        if (count($str_segments) == 1)
        {
            $lastname = $search['fio_or_login'];
            $firstname = $search['fio_or_login'];
            $middlename = $search['fio_or_login'];
            $login = $search['fio_or_login'];
            $where = ' AND (user.lastname LIKE ? OR
            user.firstname LIKE ? OR
            user.middlename LIKE ? OR
            user.login LIKE ?) ';
        }

        $where .= ' AND user.company_address_id = ? ';

        $page = intval($page);
        if ($page < 1)
        {
            $page = 1;
        }

        $offset = ($page - 1) * self::SHOW_BY_DEFAULT;

        $sql = 'SELECT
          user.id,
          user.lastname,
          user.firstname,
          user.middlename,
          user.login,
          user.workpost,
          user.company_address_id,
          company.key_field AS c_key_field,
          company.is_mfc,
          user_role.name AS role_name,
          company_address.address_country,
          company_address.address_zip,
          company_address.address_region,
          company_address.address_area,
          company_address.address_city,
          company_address.address_town,
          company_address.address_street,
          company_address.address_home,
          company_address.address_case,
          company_address.address_build,
          company_address.address_apartment,
          company_address.is_transit,
          user.flag,
          company_address.local_place_id,
          local_place.name AS local_place_name
        FROM
          user
          INNER JOIN company_address ON (user.company_address_id = company_address.id)
          INNER JOIN company ON (company_address.company_id = company.id)
          INNER JOIN user_role ON (user.role_id = user_role.id)
          INNER JOIN local_place ON (company_address.local_place_id = local_place.id)
        WHERE
          user.flag > 0 '.$where
        .' ORDER BY user.lastname LIMIT '. self::SHOW_BY_DEFAULT .' OFFSET ' . $offset;

        $db = Database::getConnection();
        $result = $db->prepare($sql);


        $lastname = '%' .$lastname. '%';
        $firstname = '%' .$firstname. '%';
        $middlename = '%' .$middlename. '%';
        $login = '%' .$login. '%';

        if (count($str_segments) == 3)
        {
            $result->execute([$lastname, $firstname, $middlename, $search['office']]);
        }
        if (count($str_segments) == 2)
        {
            $result->execute([$lastname, $firstname, $search['office']]);
        }
        if (count($str_segments) == 1)
        {
            $result->execute([$lastname, $firstname, $middlename, $login, $search['office']]);
        }

        // Получение и возврат результатов
        $users = null;
        $i = 0;
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $users[$i] = $row;
            $i++;
        }
        return $users;
    }

    /*
     * Получаем пользователей, удовлетворяющих параметрам поиска
     * @var $search array() - параметры поиска
     * return array()
     */
    public static function getTotalUsers($search)
    {
        $where = '';

        $lastname = '';
        $firstname = '';
        $middlename = '';
        $login = '';

        $str_segments = explode(' ', $search['fio_or_login']);

        if (count($str_segments) == 3)
        {
            $lastname = $str_segments[0];
            $firstname = $str_segments[1];
            $middlename = $str_segments[2];
            $where = ' AND (user.lastname LIKE ?
            AND user.firstname LIKE ?
            AND user.middlename LIKE ?) ';
        }
        if (count($str_segments) == 2)
        {
            $lastname = $str_segments[0];
            $firstname = $str_segments[1];
            $where = ' AND (user.lastname LIKE ?
            AND user.firstname LIKE ?) ';
        }
        if (count($str_segments) == 1)
        {
            $lastname = $search['fio_or_login'];
            $firstname = $search['fio_or_login'];
            $middlename = $search['fio_or_login'];
            $login = $search['fio_or_login'];
            $where = ' AND (user.lastname LIKE ? OR
            user.firstname LIKE ? OR
            user.middlename LIKE ? OR
            user.login LIKE ?) ';
        }

        $where .= ' AND user.company_address_id = ? ';

        $sql = 'SELECT
          COUNT(*) AS row_count
        FROM
          user
        WHERE
          user.flag > 0 '.$where;

        $db = Database::getConnection();
        $result = $db->prepare($sql);


        $lastname = '%' .$lastname. '%';
        $firstname = '%' .$firstname. '%';
        $middlename = '%' .$middlename. '%';
        $login = '%' .$login. '%';

        if (count($str_segments) == 3)
        {
            $result->execute([$lastname, $firstname, $middlename, $search['office']]);
        }
        if (count($str_segments) == 2)
        {
            $result->execute([$lastname, $firstname, $search['office']]);
        }
        if (count($str_segments) == 1)
        {
            $result->execute([$lastname, $firstname, $middlename, $login, $search['office']]);
        }

        // Обращаемся к записи
        $count = $result->fetch(PDO::FETCH_ASSOC);

        if ($count) {
            return $count['row_count'];
        }
        return 0;
    }

    /*
     * Проверить существует ли такой логин
     * @var $login string - логин
     * return boolean
     */
    public static function checkUserLogin($login)
    {
        $sql = 'SELECT id FROM user WHERE login = :login';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':login', $login, PDO::PARAM_STR);
        $result->execute();

        $user = $result->fetch();

        if($user)
        {
            return true;
        }
        return false;
    }

    /*
     * Добавляем пользователя
     * @var $user arrray() - информация о пользователе
     * return int OR boolean
     */
    public static function addUser($user)
    {
        $sql = 'INSERT INTO user (lastname, firstname, middlename, login, password, workpost,
          company_address_id, role_id, group_id, created_datetime, created_user_id, flag)
          VALUES (:lastname, :firstname, :middlename, :login, :password, :workpost,
          :company_address_id, :role_id, :group_id, :created_datetime, :created_user_id, 1)';

        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':lastname', $user['lastname'], PDO::PARAM_STR);
        $result->bindParam(':firstname', $user['firstname'], PDO::PARAM_STR);
        $result->bindParam(':middlename', $user['middlename'], PDO::PARAM_STR);
        $result->bindParam(':login', $user['login'], PDO::PARAM_STR);
        $result->bindParam(':password', $user['password'], PDO::PARAM_STR);
        $result->bindParam(':workpost', $user['workpost'], PDO::PARAM_STR);
        $result->bindParam(':company_address_id', $user['company_address_id'], PDO::PARAM_INT);
        $result->bindParam(':role_id', $user['role_id'], PDO::PARAM_INT);
        $result->bindParam(':group_id', $user['group_id'], PDO::PARAM_INT);
        $result->bindParam(':created_datetime', $user['created_datetime'], PDO::PARAM_STR);
        $result->bindParam(':created_user_id', $user['created_user_id'], PDO::PARAM_INT);

        if($result->execute())
        {
            return $db->lastInsertId();
        }
        return false;
    }

    /*
     * Изменяем данные пользователя
     * @var $id int - ID пользователя
     * @var $user array() - информация о пользователе
     * @var $param int - параметр, указывающий, какие данные пользователя подлежат изменению
     *
     * Возможные вариант $param:
     * 1 - Информацию о пользователе
     * 2 - Пароль пользователя
     */
    public static function updateUser($id, $user, $param = 1)
    {
        $sql = '';
        $where = '';

        if ($user['changed_user_id'] != $id)
        {
            $where .= ' AND flag = 1 ';
        }
        if ($param == 1)
        {
            $sql = 'UPDATE user
            SET lastname = :lastname, firstname = :firstname, middlename = :middlename,
              login = :login, workpost = :workpost, company_address_id = :company_address_id,
              role_id = :role_id, group_id = :group_id, changed_datetime = :changed_datetime,
              changed_user_id = :changed_user_id
            WHERE id = :id '.$where;
        }
        elseif ($param == 2)
        {
            $sql = 'UPDATE user
            SET  password = :password, changed_datetime = :changed_datetime, changed_user_id = :changed_user_id
            WHERE id = :id '. $where;
        }
        else
        {
            return false;
        }

        $db = Database::getConnection();
        $result = $db->prepare($sql);

        $result->bindParam(':id', $id, PDO::PARAM_INT);

        if ($param == 1)
        {
            $result->bindParam(':lastname', $user['lastname'], PDO::PARAM_STR);
            $result->bindParam(':firstname', $user['firstname'], PDO::PARAM_STR);
            $result->bindParam(':middlename', $user['middlename'], PDO::PARAM_STR);
            $result->bindParam(':login', $user['login'], PDO::PARAM_STR);
            $result->bindParam(':workpost', $user['workpost'], PDO::PARAM_STR);
            $result->bindParam(':company_address_id', $user['company_address_id'], PDO::PARAM_INT);
            $result->bindParam(':role_id', $user['role_id'], PDO::PARAM_INT);
            $result->bindParam(':group_id', $user['group_id'], PDO::PARAM_INT);
        }
        elseif ($param == 2)
        {
            $result->bindParam(':password', $user['password'], PDO::PARAM_STR);
        }

        $result->bindParam(':changed_datetime', $user['changed_datetime'], PDO::PARAM_STR);
        $result->bindParam(':changed_user_id', $user['changed_user_id'], PDO::PARAM_INT);

        return $result->execute();
    }
}