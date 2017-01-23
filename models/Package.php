<?php


class Package
{
    /*******************************************************
     ********************* Поля класса *********************
     *******************************************************/

    const SHOW_BY_DEFAULT = 20;

    /***********************************************************
     ********************** Методы класса **********************
     ***********************************************************/

    /*
     * Получаем все посыкли офиса
     * @var $track string - трек-номер
     * @var $page int - номер страницы
     * @var $date_create string - дата создания
     * @var $package_type int - тип посылки (входящие/исходящие)
     * @var $user_office int - офис пользователя
     * @var $office int - выбор офиса
     * return array()
     */
    public static function getPackages($track, $page, $date_create, $package_type, $user_office, $office)
    {
        $track = '%'.$track.'%';
        $page = intval($page);
        if ($page < 1)
        {
            $page = 1;
        }

        $offset = ($page - 1) * self::SHOW_BY_DEFAULT;

        $where = ' WHERE
            package.number LIKE ? ';
        if (!empty($date_create) && $date_create != null)
        {
            $where .= ' AND (package.creation_datetime >= ? AND package.creation_datetime <= ? + INTERVAL 1 DAY ) ';
        }

        if ($office == OFFICE_NOW)
        {
            if ($package_type == PACKAGE_INPUT)
            {
                $where .= ' AND package.to_company_address_id = ? ';
            }

            if ($package_type == PACKAGE_OUTPUT)
            {
                $where .= ' AND package.from_company_address_id = ? ';
            }

            if ($package_type == PACKAGE_ALL)
            {
                $where .= ' AND (package.from_company_address_id = ? OR package.to_company_address_id = ?) ';
            }
        }

        $sql = 'SELECT
          company1.name AS to_company_name,
          company.name AS from_company_name,
          company1.full_name AS to_company_full_name,
          company1.key_field AS to_company_key_field,
          company.full_name AS from_company_full_name,
          company.key_field AS from_company_key_field,
          company_address.address_country AS from_ca_country,
          company_address.address_zip AS from_ca_zip,
          company_address.address_region AS from_ca_region,
          company_address.address_area AS from_ca_area,
          company_address.address_city AS from_ca_city,
          company_address.address_town AS from_ca_town,
          company_address.address_street AS from_ca_street,
          company_address.address_home AS from_ca_home,
          company_address.address_case AS from_ca_case,
          company_address.address_build AS from_ca_build,
          company_address.address_apartment AS from_ca_apartment,
          company_address1.address_country AS to_ca_country,
          company_address1.address_zip AS to_ca_zip,
          company_address1.address_region AS to_ca_region,
          company_address1.address_area AS to_ca_area,
          company_address1.address_city AS to_ca_city,
          company_address1.address_town AS to_ca_town,
          company_address1.address_street AS to_ca_street,
          company_address1.address_home AS to_ca_home,
          company_address1.address_case AS to_ca_case,
          company_address1.address_build AS to_ca_build,
          company_address1.address_apartment AS to_ca_apartment,
          user.lastname AS user_lastname,
          user.firstname AS user_firstname,
          user.middlename AS user_middlename,
          package.number AS package_number,
          package.note AS package_note,
          package.id AS package_id,
          package.creation_datetime AS package_creation_datetime
        FROM
          package
          INNER JOIN company_address ON (package.from_company_address_id = company_address.id)
          INNER JOIN company_address company_address1 ON (package.to_company_address_id = company_address1.id)
          INNER JOIN company ON (company_address.company_id = company.id)
          INNER JOIN company company1 ON (company_address1.company_id = company1.id)
          INNER JOIN user ON (package.user_id = user.id) '
        . $where .
        ' ORDER BY package.number LIMIT '. self::SHOW_BY_DEFAULT .' OFFSET ' . $offset;

        $db = Database::getConnection();
        $result = $db->prepare($sql);

        if ($office == OFFICE_NOW)
        {
            if (($package_type == PACKAGE_INPUT || $package_type == PACKAGE_OUTPUT) && !empty($date_create) && $date_create != null)
            {
                $result->execute([$track, $date_create, $date_create, $user_office]);
            }
            if ($package_type == PACKAGE_ALL  && !empty($date_create) && $date_create != null)
            {
                $result->execute([$track, $date_create, $date_create, $user_office, $user_office]);
            }
            if (($package_type == PACKAGE_INPUT || $package_type == PACKAGE_OUTPUT) && (empty($date_create) || $date_create == null))
            {
                $result->execute([$track, $user_office]);
            }
            if ($package_type == PACKAGE_ALL && (empty($date_create) || $date_create == null))
            {
                $result->execute([$track, $user_office, $user_office]);
            }
        }
        else
        {
            if (!empty($date_create) && $date_create != null)
            {
                $result->execute([$track, $date_create, $date_create]);
            }
            if (!empty($date_create) && $date_create != null)
            {
                $result->execute([$track, $date_create, $date_create]);
            }
            if (empty($date_create) || $date_create == null)
            {
                $result->execute([$track]);
            }
        }


        // Получение и возврат результатов
        $packages = null;
        $i = 0;
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $packages[$i] = $row;
            $i++;
        }
        return $packages;
    }

    /*
     * Получаем объекты посылки
     * @var $package_id int - ID посылки
     * return array()
     */
    public static function getPackageObjects($package_id)
    {
        $sql = 'SELECT
              package_object.name,
              package.number,
              package.note
            FROM
              package_or_package_object
              INNER JOIN package_object ON (package_or_package_object.package_object_id = package_object.id)
              INNER JOIN package ON (package_or_package_object.package_id = package.id)
            WHERE
              package_or_package_object.package_id = :package_id AND
              package_object.flag > 0';
        $db = Database::getConnection();
        $result = $db->prepare($sql);

        $result->bindParam(':package_id', $package_id, PDO::PARAM_INT);
        $result->execute();

        // Получение и возврат результатов
        $objects = null;
        $i = 0;
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $objects[$i] = $row;
            $i++;
        }
        return $objects;
    }

    /*
     * Получаем количество посылок по искомым параметрам
     * @var $track string - трек-номер
     * @var $date_create string - дата создания
     * @var $package_type int - тип посылки
     * @var $user_office int - офис пользователя
     * @var $office int - выбор офиса
     * return int
     */
    public static function getTotalPackages($track, $date_create, $package_type, $user_office, $office)
    {
        $track = '%'.$track.'%';


        $where = ' WHERE
            package.number LIKE ? ';
        if (!empty($date_create) && $date_create != null)
        {
            $where .= ' AND (package.creation_datetime >= ? AND package.creation_datetime <= ? + INTERVAL 1 DAY ) ';
        }

        if ($office == OFFICE_NOW)
        {
            if ($package_type == PACKAGE_INPUT)
            {
                $where .= ' AND package.to_company_address_id = ? ';
            }

            if ($package_type == PACKAGE_OUTPUT)
            {
                $where .= ' AND package.from_company_address_id = ? ';
            }

            if ($package_type == PACKAGE_ALL)
            {
                $where .= ' AND (package.from_company_address_id = ? OR package.to_company_address_id = ?) ';
            }
        }

        $sql = 'SELECT
          COUNT(*) AS row_count
        FROM
          package '
        . $where;

        $db = Database::getConnection();
        $result = $db->prepare($sql);

        if ($office == OFFICE_NOW)
        {
            if (($package_type == PACKAGE_INPUT || $package_type == PACKAGE_OUTPUT) && !empty($date_create) && $date_create != null)
            {
                $result->execute([$track, $date_create, $date_create, $user_office]);
            }
            if ($package_type == PACKAGE_ALL  && !empty($date_create) && $date_create != null)
            {
                $result->execute([$track, $date_create, $date_create, $user_office, $user_office]);
            }
            if (($package_type == PACKAGE_INPUT || $package_type == PACKAGE_OUTPUT) && (empty($date_create) || $date_create == null))
            {
                $result->execute([$track, $user_office]);
            }
            if ($package_type == PACKAGE_ALL && (empty($date_create) || $date_create == null))
            {
                $result->execute([$track, $user_office, $user_office]);
            }
        }
        else
        {
            if (!empty($date_create) && $date_create != null)
            {
                $result->execute([$track, $date_create, $date_create]);
            }
            if (!empty($date_create) && $date_create != null)
            {
                $result->execute([$track, $date_create, $date_create]);
            }
            if (empty($date_create) || $date_create == null)
            {
                $result->execute([$track]);
            }
        }

        // Обращаемся к записи
        $count = $result->fetch(PDO::FETCH_ASSOC);

        if ($count) {
            return $count['row_count'];
        }
        return 0;
    }



    /*
     * Запоминаем ведомость в сессию
     * @var $list string - номер ведомости
     */
    public static function memorizePackage($list)
    {
        session_start();
        $_SESSION['package'] = $list;
    }

    /*
     * Запоминаем объекты посылки в сессию
     * @var $p_objects array() - массив дел
     */
    public static function memorizePackageObjects($p_objects)
    {
        session_start();
        if($p_objects != null)
        {
            foreach($p_objects as $p_object)
            {
                $_SESSION['p_object'][] = $p_object;
            }
        }
    }

    /*
     * Запоминаем объект посылки в сессию
     * @var $p_object array() - объект посылки
     */
    public static function memorizePackageObject($p_object)
    {
        session_start();
        $_SESSION['p_object'][] = $p_object;
    }

    /*
     * Проверяем ведомость в сессии
     * return int OR null
     */
    public static function checkPackage()
    {
        session_start();
        if(isset($_SESSION['package']))
        {
            return $_SESSION['package'];
        }
        return null;
    }

    /*
     * Проверяем объекты посылки в сессии
     * return array() OR boolean
     */
    public static function checkPackageObjects()
    {
        session_start();
        if(isset($_SESSION['p_object']))
        {
            return $_SESSION['p_object'];
        }
        return null;
    }

    /*
     * Удаляем ведомость из сессии
     */
    public static function outPackage()
    {
        session_start();
        if(isset($_SESSION['package']))
        {
            unset($_SESSION['package']);
        }
    }

    /*
     * Удаляем объекты посылки из сессии
     */
    public static function outPackageObjects()
    {
        session_start();
        if(isset($_SESSION['p_object']))
        {
            unset($_SESSION['p_object']);
        }
    }

    /*
     * Добавить посылку
     * @var $package array() - информация о посылке
     * return int OR boolean
     */
    public static function addPackage($package)
    {
        $creation_datetime = date('Y-m-d H:i:s');
        $sql = 'INSERT INTO package (note, from_company_address_id, to_company_address_id, user_id, creation_datetime, flag)
          VALUES (:note, :from_company_address_id, :to_company_address_id, :user_id, :creation_datetime, 1)';

        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':note', $package['note'], PDO::PARAM_STR);
        $result->bindParam(':from_company_address_id', $package['from_company_id'], PDO::PARAM_INT);
        $result->bindParam(':to_company_address_id', $package['to_company_id'], PDO::PARAM_INT);
        $result->bindParam(':user_id', $package['user_id'], PDO::PARAM_INT);
        $result->bindParam(':creation_datetime', $creation_datetime, PDO::PARAM_INT);

        if($result->execute())
        {
            return $db->lastInsertId();
        }
        return false;
    }

    /*
     * Обновить номер посылки
     * @var $id int - id посылки
     */
    public static function updatePackageNumber($id)
    {
        $count = iconv_strlen($id, DEFAULT_ENCODING_UPPERCASE);
        $number = null; // Номер
        if ($count == 1) {
            $number = '00000' . $id;
        }
        if ($count == 2) {
            $number = '0000' . $id;
        }
        if ($count == 3) {
            $number = '000' . $id;
        }
        if ($count == 4) {
            $number = '00' . $id;
        }
        if ($count == 5) {
            $number = '0' . $id;
        }
        if ($count >= 6) {
            $number = $id;
        }

        $sql = 'UPDATE
          package
          SET
            number = :number
          WHERE id = :id';

        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':number', $number, PDO::PARAM_STR);
        $result->execute();
    }

    /*
     * Обновить дату получения посылки
     * @var $id int - id посылки
     * @var $receipt_datetime string - Дата и время получения
     */
    public static function updateReceiptDatetime($id, $receipt_datetime)
    {
        $sql = 'UPDATE package
          SET receipt_datetime = :receipt_datetime
          WHERE id = :id';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':receipt_datetime', $receipt_datetime, PDO::PARAM_STR);
        $result->execute();
    }

    /*
     * Добавляем объекты посылки
     * @var $p_objects array() - элементы посыллки
     */
    public static function addPackageObjects($p_objects)
    {
        $flag = 1;
        $values = "";
        $i = 0; // Счетчик
        if(count($p_objects) > 1)
        {
            foreach($p_objects as $p_object)
            {
                $i++;
                $values .= "(NULL, '". $p_object . "', '" . $flag . "')";
                if($i < count($p_objects))
                {
                    $values .= ",";
                }
                else
                {
                    $values .= ";";
                }

            }
        }
        if(count($p_objects) == 1)
        {

            foreach($p_objects as $p_object)
            {
                $i++;
                $values .= "(NULL, '". $p_object . "', '" . $flag . "')";
                $values .= ";";
            }
        }

        $sql = "INSERT INTO `package_object` (`id`, `name`, `flag`)
          VALUES " . $values;
        $db = Database::getConnection();
        $db->query($sql);
    }

    /*
     * Добавить объект посылки
     * @var $p_object int - id объекта посылки
     * return int OR boolean
     */
    public static function addPackageObject($p_object)
    {
        $sql = 'INSERT INTO package_object (name, flag) VALUES (:name, 1)';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':name', $p_object, PDO::PARAM_STR);
        if($result->execute())
        {
            return $db->lastInsertId();
        }
        return false;
    }

    /*
     * Добавить посылку и объект посылки
     * @var $package_id int - id посылки
     * @var $package_object_id int - id объекта посылки
     */
    public static function addPackageOrPackageObject($package_id, $package_object_id)
    {
        $sql = 'INSERT INTO package_or_package_object (package_id, package_object_id) VALUES (:package_id, :package_object_id)';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':package_id', $package_id, PDO::PARAM_INT);
        $result->bindParam(':package_object_id', $package_object_id, PDO::PARAM_INT);
        $result->execute();
    }

    /*
     * Получаем трэк-номер посылки по ID
     * @var $id int - ID посылки
     * return string OR boolean
     */
    public static function getTrackNumber($id)
    {
        $sql = 'SELECT
          package.number
        FROM
          package
        WHERE
          package.id = :id ';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->execute();

        // Обращаемся к записи
        $package = $result->fetch(PDO::FETCH_ASSOC);

        if($package)
        {
            return $package['number'];
        }
        return false;
    }

    /*
     * Получаем все поля из таблицы package
     * @var $id int - ID посылки
     * return array() OR boolean
     */
    public static function getPackage($id)
    {
        $sql = 'SELECT
          *
        FROM
          package
        WHERE
          package.id = :id ';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->execute();

        // Обращаемся к записи
        $package = $result->fetch(PDO::FETCH_ASSOC);

        if($package)
        {
            return $package;
        }
        return false;
    }

    /*
     * Получаем инфомрацию о посылке
     * @var $package_id int - id посылки
     * return array() OR boolean
     */
    public static function getPackageInfo($package_id)
    {
        $sql = 'SELECT
          package.id AS p_id,
          package.number AS p_number,
          package.note AS p_note,
          package.from_company_address_id AS p_from_company_address_id,
          package.to_company_address_id AS p_to_company_address_id,
          package.user_id AS p_user_id,
          package.creation_datetime AS p_creation_datetime,
          package.receipt_datetime AS p_receipt_datetime,
          package.flag AS p_flag,
          company_address.id AS caf_id,
          company_address.company_id AS caf_company_id,
          company_address.address_country AS caf_address_country,
          company_address.address_zip AS caf_address_zip,
          company_address.address_region AS caf_address_region,
          company_address.address_area AS caf_address_area,
          company_address.address_city AS caf_address_city,
          company_address.address_town AS caf_address_town,
          company_address.address_street AS caf_address_street,
          company_address.address_home AS caf_address_home,
          company_address.address_case AS caf_address_case,
          company_address.address_build AS caf_address_build,
          company_address.address_apartment AS caf_address_apartment,
          company_address.flag AS caf_flag,
          company.id AS cf_id,
          company.name AS cf_name,
          company.full_name AS cf_full_name,
          company.key_field AS cf_key_field,
          company.flag AS cf_flag,
          company_address1.id AS cat_id,
          company_address1.company_id AS cat_company_id,
          company_address1.address_country AS cat_address_country,
          company_address1.address_zip AS cat_address_zip,
          company_address1.address_region AS cat_address_region,
          company_address1.address_area AS cat_address_area,
          company_address1.address_city AS cat_address_city,
          company_address1.address_town AS cat_address_town,
          company_address1.address_street AS cat_address_street,
          company_address1.address_home AS cat_address_home,
          company_address1.address_case AS cat_address_case,
          company_address1.address_build AS cat_address_build,
          company_address1.address_apartment AS cat_address_apartment,
          company_address1.flag AS cat_flag,
          company1.id AS ct_id,
          company1.name AS ct_name,
          company1.full_name AS ct_full_name,
          company1.key_field AS ct_key_field,
          company1.flag AS ct_flag,
          user.id AS u_id,
          user.lastname AS u_lastname,
          user.firstname AS u_firstname,
          user.middlename AS u_middlename,
          user.company_address_id AS u_company_address_id,
          company_address2.id AS uca_id,
          company_address2.company_id AS uca_company_id,
          company_address2.address_country AS uca_address_country,
          company_address2.address_zip AS uca_address_zip,
          company_address2.address_region AS uca_address_region,
          company_address2.address_area AS uca_address_area,
          company_address2.address_city AS uca_address_city,
          company_address2.address_town AS uca_address_town,
          company_address2.address_street AS uca_address_street,
          company_address2.address_home AS uca_address_home,
          company_address2.address_case AS uca_address_case,
          company_address2.address_build AS uca_address_build,
          company_address2.address_apartment AS uca_address_apartment,
          company_address2.flag AS uca_flag,
          company2.id AS uc_id,
          company2.name AS uc_name,
          company2.full_name AS uc_full_name,
          company2.key_field AS uc_key_field,
          company2.flag AS uc_flag
        FROM
          package
          INNER JOIN company_address ON (package.from_company_address_id = company_address.id)
          INNER JOIN company ON (company_address.company_id = company.id)
          INNER JOIN company_address company_address1 ON (package.to_company_address_id = company_address1.id)
          INNER JOIN company company1 ON (company_address1.company_id = company1.id)
          INNER JOIN user ON (package.user_id = user.id)
          INNER JOIN company_address company_address2 ON (user.company_address_id = company_address2.id)
          INNER JOIN company company2 ON (company_address2.company_id = company2.id)
        WHERE
          package.id = :id';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':id', $package_id, PDO::PARAM_INT);
        $result->execute();

        // Обращаемся к записи
        $package = $result->fetch(PDO::FETCH_ASSOC);

        if($package)
        {
            return $package;
        }
        return false;
    }
}