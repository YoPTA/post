<?php

class User_Role
{
    /*
     * Получаем информацию о роли
     * @var $id int - id
     * return array() OR boolean
     */
    public static function getRole($id)
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
     * Проверяем является ли пользователь администратором
     * @var $role_id int - id роли
     * return boolean
     */
    public static function checkAdmin($role_id)
    {
        $role = User_Role::getRole($role_id);
        if($role['is_admin'] == 1)
        {
            return true;
        }
        return false;
    }

    /*
     * Проверяем может ли пользователь создавать посылки
     * @var $role_id int - id роли
     * return boolean
     */
    public static function checkCreate($role_id)
    {
        $role = User_Role::getRole($role_id);
        if($role['is_create'] == 1)
        {
            return true;
        }
        return false;
    }

    /*
     * Проверяем является может ли пользователь изменять доверенности и
     * доверенные лица.
     * @var $role_id int - id роли
     * return boolean
     */
    public static function checkChangeProxy($role_id)
    {
        $role = User_Role::getRole($role_id);
        if($role['is_change_proxy'] == 1)
        {
            return true;
        }
        return false;
    }
}