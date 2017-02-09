<?php

class User_Role
{

    private static $roles;

    public function __construct($id)
    {
        self::$roles = self::getRole($id);
    }

    /*
     * Получаем информацию о роли
     * @var $id int - id
     * return array() OR boolean
     */
    private static function getRole($id)
    {
        $sql = 'SELECT
          *
          FROM user_role
          WHERE id = :id';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->execute();

        $user_role = $result->fetch(PDO::FETCH_ASSOC);
        if($user_role)
        {
            return $user_role;
        }
        return false;
    }

    /*
     * Получаем все роли
     * return array()
     */
    public static function getRoles()
    {
        $sql = 'SELECT * FROM user_role';
        $db = Database::getConnection();
        $result = $db->prepare($sql);

        $result->execute();

        // Получение и возврат результатов
        $roles = null;
        $i = 0;
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $roles[$i] = $row;
            $i++;
        }
        return $roles;
    }

    /*
     * Проверяем запись на существование
     * @var $id int - ID роли
     * return boolean
     */
    public static function checkUserRole($id)
    {
        $sql = 'SELECT id FROM user_role WHERE id = :id ';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->execute();

        $user_role = $result->fetch();

        if($user_role)
        {
            return true;
        }
        return false;
    }

    /*
     * Проверяем является ли пользователь администратором
     * Работает со статическим приватным полем $roles.
     * return boolean
     */
    public static function checkAdmin()
    {
        if(self::$roles['is_admin'] == 1)
        {
            return true;
        }
        return false;
    }

    /*
     * Проверяем может ли пользователь создавать посылки
     * Работает со статическим приватным полем $roles.
     * return boolean
     */
    public static function checkCreate()
    {
        if(self::$roles['is_create'] == 1)
        {
            return true;
        }
        return false;
    }

    /*
     * Проверяем является может ли пользователь изменять доверенности и
     * доверенные лица.
     * Работает со статическим приватным полем $roles.
     * return boolean
     */
    public static function checkChangeProxy()
    {
        if(self::$roles['is_change_proxy'] == 1)
        {
            return true;
        }
        return false;
    }

    /*
     * Проверяем является может ли пользователь изменять организации и
     * адрес организации.
     * Работает со статическим приватным полем $roles.
     * return boolean
     */
    public static function checkChangeCompany()
    {
        if(self::$roles['is_change_company'] == 1)
        {
            return true;
        }
        return false;
    }

    /*
     * Проверяем может ли пользователь отправлять посылки
     * Работает со статическим приватным полем $roles.
     * return boolean
     */
    public static function checkSend()
    {
        if(self::$roles['is_send'] == 1)
        {
            return true;
        }
        return false;
    }

    /*
     * Проверяем может ли пользователь получать посылки
     * Работает со статическим приватным полем $roles.
     * return boolean
     */
    public static function checkReceive()
    {
        if(self::$roles['is_receive'] == 1)
        {
            return true;
        }
        return false;
    }

    /*
     * Проверяем может ли пользователь получать уведомления
     * Работает со статическим приватным полем $roles.
     * return boolean
     */
    public static function checkNotification()
    {
        if(self::$roles['is_notification'] == 1)
        {
            return true;
        }
        return false;
    }
}