<?php

class Notification
{
    /*
     * Добавляем уведомление
     * @var $notification array() - информация об уведомлении
     * return int OR boolean
     */
    public static function addNotification($notification)
    {
        $sql = 'INSERT INTO notification (text_message, user_id, created_datetime, created_user_id, flag)
          VALUES (:text_message, :user_id, :created_datetime, :created_user_id, 1)';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':text_message', $notification['text_message'], PDO::PARAM_STR);
        $result->bindParam(':user_id', $notification['user_id'], PDO::PARAM_INT);
        $result->bindParam(':created_datetime', $notification['created_datetime'], PDO::PARAM_STR);
        $result->bindParam(':created_user_id', $notification['created_user_id'], PDO::PARAM_INT);
        if($result->execute())
        {
            return $db->lastInsertId();
        }
        return false;
    }

    /*
     * Запуск уведомлений
     * @var $company_address array() - информация об адресе организации
     *
     */
    public static function launchNotification($company_address)
    {

    }

}