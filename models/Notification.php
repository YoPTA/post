<?php

class Notification
{
    /*******************************************************
     ********************* Поля класса *********************
     *******************************************************/

    const SHOW_BY_DEFAULT = 20;

    /***********************************************************
     ********************** Методы класса **********************
     ***********************************************************/

    /*
     * Обновить flag сообщений
     * @var $user_id int - ID пользователя
     */
    public static function changeFlagNotification($user_id)
    {
        $sql = 'UPDATE
            notification
          SET
            flag = 2
          WHERE user_id = :user_id AND flag <> 0';

        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $result->execute();
    }


    /*
     * Проверяем имеются ли у пользователя сообщения с определенным флагом
     * @var $user_id int - ID пользователя
     * @var $flag int - флаг
     *
     * Возможные варианты $flag:
     * 1 - Не прочитанные сообщения
     * 2 - Прочитанные сообщения
     */
    public static function checkNotification($user_id, $flag)
    {
        $sql = 'SELECT id FROM notification WHERE user_id = :user_id AND flag = :flag';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $result->bindParam(':flag', $flag, PDO::PARAM_INT);
        $result->execute();

        // Получение и возврат результатов
        $notification = $result->fetch();

        if($notification)
        {
            return true;
        }
        return false;
    }

    /*
     * Получаем уведомления
     * @var $user_id int - ID пользователя
     * return array()
     */
    public static function getNotificationsByUser($user_id)
    {
        $sql = 'SELECT
          name, text_message, detail_text_message, created_datetime
        FROM
          notification
        WHERE
          user_id = :user_id AND
          flag > 0
        ORDER BY created_datetime ';

        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $result->execute();

        // Получение и возврат результатов
        $notifications = null;
        $i = 0;
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $notifications[$i] = $row;
            $i++;
        }
        return $notifications;
    }

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
     * Добавляем уведомления
     * @var $notification array() - информация уведомлений
     * @var $users array() - пользователи, для которых уведомления
     */
    public static function addNotifications($notification, $users)
    {
        if (count($users) == 0)
        {
            return false;
        }

        $db = Database::getConnection();
        if (count($users) > 0)
        {
            foreach ($users as $user)
            {
                $sql = 'INSERT INTO notification (name, text_message, detail_text_message, user_id, created_datetime)
                  VALUES (:name, :text_message, :detail_text_message, :user_id, :created_datetime) ';

                $result = $db->prepare($sql);
                $result->bindParam(':name', $notification['name'], PDO::PARAM_STR);
                $result->bindParam(':text_message', $notification['text_message'], PDO::PARAM_STR);
                $result->bindParam(':detail_text_message', $notification['detail_text_message'], PDO::PARAM_STR);
                $result->bindParam(':user_id', $user['id'], PDO::PARAM_INT);
                $result->bindParam(':created_datetime', $notification['created_datetime'], PDO::PARAM_STR);
                $result->execute();
            }
        }
    }

    /*
     * Запуск уведомлений
     * @var $package_id int - ID посылки
     *
     */
    public static function launchNotification($package_id)
    {
        $next_route = null; // Следующий маршрут
        $now_route = null; // Текущий маршрут
        $counter = 0; // Счетчик, для подсчета точек маршрута
        $users = null; // Которые будут уведомлены
        $package = null; // Посылка
        $notification = null; // Уведомления
        $from_company = null; // Организация откуда
        $to_company = null; // Организация куда


        $string_utility = new String_Utility();
        $validate = new Validate();
        $datetime = new DateTime();

        // Получаем маршрут посылки
        $package_route = Route::getPackageRoute($package_id, 3);

        // Если записей меньше двух, то выходим из функции
        if (count($package_route) < 2)
        {
            return false;
        }

        for ($i = count($package_route) - 1; $i >= 0; $i--)
        {
            if ($package_route[$i]['is_receive'] == 1 && $package_route[$i]['is_send'] == 0)
            {
                if ($package_route[$i]['is_transit'] == 1 && $counter > 0)
                {
                    $next_route = $package_route[$i + 1];
                    $now_route = $package_route[$i];
                    break;
                }
            }
            $counter++;
        }

        if ($next_route == null || $now_route == null)
        {
            return false;
        }


        if ($next_route['is_mfc'] == 1)
        {
            $users = User::getUsersCompanyAddressOrLocalPlace(1, $next_route['company_address_id']);
        }
        else
        {
            $users = User::getUsersCompanyAddressOrLocalPlace(2, $next_route['local_place_id']);
        }

        if (count($users) < 1)
        {
            return false;
        }

        $package = Package::getPackage($package_id);

        if (count($package) < 1)
        {
            return false;
        }

        $from_company = Company::getCompany($package['from_company_address_id']);
        $now_route = Company::getCompany($now_route['company_address_id']);

        if (count($from_company) < 1 || count($now_route) < 1)
        {
            return false;
        }

        $notification['name'] = 'Посылка от ' . $from_company['c_name'];
        $notification['text_message'] = 'Вам необходимо забрать посылку с трек-номером ' . $package['number'];
        $notification['detail_text_message'] = 'Необходимо прислать курьера за посылкой с трек-номером: ' . $package['number'] .".<br /><br /> ";
        $notification['detail_text_message'] .= 'Посылка от: ' . $from_company['c_full_name'].".<br /><br /> ";

        $transit_text = '';
        if ($now_route['is_transit'] == 1)
        {
            $transit_text = 'в транзитной точке ';
        }

        $notification['detail_text_message'] .= 'В данный момент посылка находится '.$transit_text.'в организации: ' . $now_route['c_full_name'] . ' ';

        $notification['detail_text_message'] .= 'по адресу: ' . $now_route['ca_zip'] . ', ' . $string_utility->getAddressToView(1, $now_route, '') . '.';

        $end_line = '...';
        $notification['detail_text_message'] = $validate->my_strCut($notification['detail_text_message'], 4096, $end_line);
        $notification['created_datetime'] = $datetime->format('Y-m-d H:i:s');

        self::addNotifications($notification, $users);

        return true;
    }

}