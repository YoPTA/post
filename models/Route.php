<?php


class Route
{
    /*******************************************************
     ********************* Поля класса *********************
     *******************************************************/



    /***********************************************************
     ********************** Методы класса **********************
     ***********************************************************/

    /*
     * Добавляем маршрут
     * @var $routes array() - информация о маршруте
     * @var $package_id int - id посылки
     */
    public static function addRoutes($routes, $package_id)
    {
        $values = "";
        $i = 0; // Счетчик
        if(count($routes) > 1)
        {
            foreach($routes as $route)
            {
                $i++;
                $values .= "('". $package_id . "',  '" . $route . "')";
                if($i < count($routes))
                {
                    $values .= ",";
                }
                else
                {
                    $values .= ";";
                }

            }
        }
        if(count($routes) == 1)
        {

            foreach($routes as $route)
            {
                $i++;
                $values .= "('". $package_id . "',  '" . $route . "')";
                $values .= ";";
            }
        }

        $sql = "INSERT INTO `route` (`package_id`, `company_address_id`)
          VALUES " . $values;
        $db = Database::getConnection();
        $db->query($sql);
    }

    /*
     * Получаем маршрут, где не состоялось отправление
     * @var $package_id int - id посылки
     * return int OR boolean
     */
    public static function getRouteWithoutSend($package_id)
    {
        $sql = 'SELECT id FROM route WHERE is_send = 0 AND package_id = :package_id';

        $db = Database::getConnection();

        $result = $db->prepare($sql);
        $result->bindParam(':package_id', $package_id, PDO::PARAM_INT);
        $result->execute();

        // Получение и возврат результатов
        $route = $result->fetch();

        if($route)
        {
            return $route['id'];
        }
        return false;
    }

    /*
     * Получаем маршруты, где состоялось отправление
     * @var $package_id int - id посылки
     * return array() OR null
     */
    public static function getSendRoutes($package_id)
    {
        $sql = 'SELECT * FROM route WHERE is_send = 1 AND package_id = :package_id';

        $db = Database::getConnection();

        $result = $db->prepare($sql);
        $result->bindParam(':package_id', $package_id, PDO::PARAM_INT);
        $result->execute();

        // Получение и возврат результатов
        $route = null;
        $i = 0;
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $route[$i] = $row;
            $i++;
        }
        return $route;
    }

    /*
     * Получение посылки
     * @var $id int - id маршрута
     * @var $receive_values array() - данные о получении
     * return boolean
     */
    public static function receive($id, $receive_values)
    {
        $datetime_receive = date('Y-m-d H:i:s');
        $sql = 'UPDATE route
          SET
          is_receive = 1,
          receive_proxy_id = :receive_proxy_id,
          receive_proxy_person_id = :receive_proxy_person_id,
          receive_user_id = :receive_user_id,
          datetime_receive = :datetime_receive
          WHERE id = :id';
        $db = Database::getConnection();

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':receive_proxy_id', $receive_values['receive_proxy_id'], PDO::PARAM_INT);
        $result->bindParam(':receive_proxy_person_id', $receive_values['receive_proxy_person_id'], PDO::PARAM_INT);
        $result->bindParam(':receive_user_id', $receive_values['receive_user_id'], PDO::PARAM_INT);
        $result->bindParam(':datetime_receive', $datetime_receive, PDO::PARAM_STR);
        if($result->execute())
        {
            return true;
        }
        return false;
    }

    /*
     * Отправление посылки
     * @var $id int - id маршрута
     * @var $send_values array() - данные о отправлении
     * return boolean
     */
    public static function send($id, $send_values)
    {
        $datetime_send = date('Y-m-d H:i:s');
        $sql = 'UPDATE route
          SET
          is_send = 1,
          send_proxy_id = :send_proxy_id,
          send_proxy_person_id = :send_proxy_person_id,
          send_user_id = :send_user_id,
          datetime_send = :datetime_send
          WHERE id = :id';
        $db = Database::getConnection();

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':send_proxy_id', $send_values['send_proxy_id'], PDO::PARAM_INT);
        $result->bindParam(':send_proxy_person_id', $send_values['send_proxy_person_id'], PDO::PARAM_INT);
        $result->bindParam(':send_user_id', $send_values['send_user_id'], PDO::PARAM_INT);
        $result->bindParam(':datetime_send', $datetime_send, PDO::PARAM_STR);
        if($result->execute())
        {
            return true;
        }
        return false;
    }

    /*
     * Получить маршрут
     * @var $id int - id
     * return array() OR boolean

    public static function getRoute($id)
    {
        $sql = 'SELECT
          route.id AS r_id,
          route.package_id AS r_package_id,
          route.company_address_id AS r_company_address_id,
          route.is_receive AS r_is_receive,
          route.is_send AS r_is_send,
          route.receive_proxy_id AS r_receive_proxy_id,
          route.receive_user_id AS r_receive_user_id,
          route.send_user_id AS r_send_user_id,
          route.send_proxy_id AS r_send_proxy_id,
          route.datetime_receive AS r_datetime_receive,
          route.datetime_send AS r_datetime_send,
          route.relation_package_id AS r_relation_package_id,
          package.id AS p_id,
          package.number AS p_number,
          package.note AS p_note,
          package.from_company_address_id AS p_from_company_address_id,
          package.to_company_address_id AS p_to_company_address_id,
          package.user_id AS p_user_id,
          package.creation_datetime AS p_creation_datetime,
          package.receipt_datetime AS p_receipt_datetime,
          package.flag AS p_flag,
          company_address.id AS ca_id,
          company_address.company_id AS ca_company_id,
          company_address.address_country AS ca_address_country,
          company_address.address_zip AS ca_address_zip,
          company_address.address_region AS ca_address_region,
          company_address.address_area AS ca_address_area,
          company_address.address_city AS ca_address_city,
          company_address.address_town AS ca_address_town,
          company_address.address_street AS ca_address_street,
          company_address.address_home AS ca_address_home,
          company_address.address_case AS ca_address_case,
          company_address.address_build AS ca_address_build,
          company_address.address_apartment AS ca_address_apartment,
          company_address.flag AS ca_flag,
          company.id AS c_id,
          company.name AS c_name,
          company.full_name AS c_full_name,
          company.key_field AS c_key_field,
          company.flag AS c_flag
        FROM
          route
          INNER JOIN package ON (route.package_id = package.id)
          INNER JOIN company_address ON (route.company_address_id = company_address.id)
          INNER JOIN company ON (company_address.company_id = company.id)
        WHERE
          route.id = :id';
        $db = Database::getConnection();

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->execute();

        // Обращаемся к записи
        $route = $result->fetch(PDO::FETCH_ASSOC);

        if($route)
        {
            return $route;
        }
        return false;
    }*/

    /*
     * Получить маршрут посылки
     * @var $package_id int - id посылки
     * return array()
     */
    public static function getPackageRoute($package_id)
    {
        $sql = 'SELECT
          route.id,
          route.package_id,
          route.company_address_id,
          route.is_receive,
          route.is_send,
          route.receive_proxy_id,
          route.receive_user_id,
          route.send_user_id,
          route.send_proxy_id,
          route.datetime_receive,
          route.datetime_send,
          route.relation_package_id,
          package.number,
          package.note,
          package.from_company_address_id,
          package.to_company_address_id,
          package.user_id,
          package.creation_datetime,
          package.receipt_datetime,
          company_address.company_id,
          company_address.address_country AS ca_country,
          company_address.address_zip AS ca_zip,
          company_address.address_region AS ca_region,
          company_address.address_area AS ca_area,
          company_address.address_city AS ca_city,
          company_address.address_town AS ca_town,
          company_address.address_street AS ca_street,
          company_address.address_home AS ca_home,
          company_address.address_case AS ca_case,
          company_address.address_build AS ca_build,
          company_address.address_apartment AS ca_apartment,
          company_address.is_transit
        FROM
          route
          INNER JOIN package ON (route.package_id = package.id)
          INNER JOIN company_address ON (route.company_address_id = company_address.id)
        WHERE
          route.package_id = :package_id
        ORDER BY route.id';

        $db = Database::getConnection();

        $result = $db->prepare($sql);
        $result->bindParam(':package_id', $package_id, PDO::PARAM_INT);
        $result->execute();

        // Получение и возврат результатов
        $route = null;
        $i = 0;
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $route[$i] = $row;
            $i++;
        }
        return $route;
    }


    /*
     * Получить всю информацию о маршруте
     * @var $id int - id маршрута
     * return array()
     */
    public static function getRouteInfo($id)
    {
        $sql = 'SELECT
            *
          FROM
            route
          WHERE
            route.id = :id';
        $db = Database::getConnection();

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->execute();

        // Обращаемся к записи
        $route = $result->fetch(PDO::FETCH_ASSOC);

        if($route)
        {
            return $route;
        }
        return false;
    }

}