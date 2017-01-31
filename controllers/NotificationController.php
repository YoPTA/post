<?php

class NotificationController
{
    public function actionIndex()
    {
        $is_notification = false;
        $user = null;
        $user_id = null;
        // Подключаем файл с проверками ролей пользователя
        require_once ROOT . '/config/role_ckeck.php';


        $date_converter = new Date_Converter();

        Notification::changeFlagNotification($user_id);
        Notification::deleteNotifications();

        $notifications = Notification::getNotificationsByUser($user_id);

        if ($is_notification)
        {
            require_once ROOT . '/views/notification/index.php';
            return true;
        }
        else
        {
            header('Location: /site/error');
        }
    }
}