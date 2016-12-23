<?php

/**
 * Группы пользователей
 */
class User_Group
{
    private static $group;

    public function __construct($id)
    {
        self::$group = self::getGroup($id);
    }

    /*
     * Получаем информацию о группах
     * @var $id int - id
     * return array() OR boolean
     */
    private static function getGroup($id)
    {
        $sql = 'SELECT
          *
          FROM user_group
          WHERE id = :id';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->execute();

        $user_group = $result->fetch(PDO::FETCH_ASSOC);
        if($user_group)
        {
            return $user_group;
        }
        return false;
    }

    /*
     * Право создания
     * return boolean
     */
    public static function isCanCreate()
    {
        return (self::$group['member'] & CAN_CREATE) ? true : false;
    }

    /*
     * Право редактирования
     * return boolean
     */
    public static function isCanEdit()
    {
        return (self::$group['member'] & CAN_EDIT) ? true : false;
    }

    /*
     * Право удаления
     * return boolean
     */
    public static function isCanDelete()
    {
        return (self::$group['member'] & CAN_DELETE) ? true : false;
    }

    /*
     * Право на изменение пользователей
     * return boolean
     */
    public static function isCanChangeUser()
    {
        return (self::$group['member'] & CAN_CHANGE_USER) ? true : false;
    }

    /*
     * Право на изменение фигни
     * return boolean
     */
    public static function isCanChangeStuff()
    {
        return (self::$group['member'] & CAN_CHANGE_STUFF) ? true : false;
    }

}