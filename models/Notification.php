<?php


class Notification
{
    /*
     * Получаем все записи из таблицы notification
     * return array()
     */
    public static function getNotifications()
    {
        $sql = 'SELECT
            *
          FROM
            notification
          WHERE
            notification.flag >= 0 ';

        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->execute();
        $notifications = null;
        $i = 0;
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $notifications[$i] = $row;
            $i++;
        }
        return $notifications;
    }

    /*
     * Проверка уведомления на существование
     * @var $name string - наименование уведомления
     * return int OR boolean
     */
    public static function checkNotification($name)
    {
        $sql = 'SELECT notification.id FROM notification WHERE notification.name = :name';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':name', $name, PDO::PARAM_STR);
        $result->execute();

        // Обращаемся к записи
        $notification = $result->fetch();

        if ($notification) {
            return $notification['id'];
        }
        return false;
    }


    /*
     * Добавить новое уведомление
     * @var $notification array() - Уведомление
     * return int OR boolean
     */
    public static function addNotification($notification)
    {
        $sql = 'INSERT INTO notification (name, created_datetime, created_user_id, flag)
          VALUES (:name, :created_datetime, :created_user_id, 1)';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':name', $notification['name'], PDO::PARAM_STR);
        $result->bindParam(':created_datetime', $notification['created_datetime'], PDO::PARAM_STR);
        $result->bindParam(':created_user_id', $notification['created_user_id'], PDO::PARAM_INT);
        if($result->execute())
        {
            return $db->lastInsertId();
        }
        return false;
    }


}