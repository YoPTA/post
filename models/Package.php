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

    private static function createSQLWhere
    (
        $package_type = PACKAGE_INPUT,

        $relatively_type = SEARCH_RELATIVELY_FROM_OR_TO,

        $from_place_type = SEARCH_PLACE_ADDRESS,
        $from_company_address_id_min = 1,
        $from_company_address_id_max = PHP_INT_MAX,

        $to_place_type = SEARCH_PLACE_ADDRESS,
        $to_company_address_id_min = 1,
        $to_company_address_id_max = PHP_INT_MAX
    )
    {
        $company = new Company();
        $result = '';

        /*
         * package_type
         *
         * 1 - Входящие
         * 2 - Исходящие
        */

        if ($package_type == PACKAGE_OUTPUT)
        {
            $company_address_package_type_prefix_a = 'from_';
            $company_address_package_type_prefix_b = 'to_';
        }else{
            $company_address_package_type_prefix_a = 'to_';
            $company_address_package_type_prefix_b = 'from_';
        }

        /*
         * relatively_type
         *
         * 1 - Отправитель/Получатель
         * 2 - Текущее местоположение
        */

        if ($relatively_type == SEARCH_RELATIVELY_FROM_OR_TO)
        {
            $company_address_relatively_type_prefix = '';
        }else{
            $company_address_relatively_type_prefix = 'now_';
        }

        /*
         * ..._place_type
         *
         * 1 - Адрес
         * 2 - Регион
        */

        if ($from_place_type == SEARCH_PLACE_ADDRESS)
        {
            $result = $result.'
                AND
                package.'.$company_address_relatively_type_prefix.$company_address_package_type_prefix_a.'company_address_id >= '.$from_company_address_id_min.'
                AND
                package.'.$company_address_relatively_type_prefix.$company_address_package_type_prefix_a.'company_address_id <= '.$from_company_address_id_max.'
            ';
        }
        else
        {
            if ($from_company_address_id_min == $from_company_address_id_max)
            {
                $from_local_place_id_min = $company->getLocalPlaceIdFromCompanyAddressId($from_company_address_id_min);
                $from_local_place_id_max = $from_local_place_id_min;
            }
            else
            {
                $from_local_place_id_min = 1;
                $from_local_place_id_max = PHP_INT_MAX;
            }

            $result = $result.'
                AND
                '.$company_address_relatively_type_prefix.$company_address_package_type_prefix_a.'company_address.local_place_id >= '.$from_local_place_id_min.'
                AND
                '.$company_address_relatively_type_prefix.$company_address_package_type_prefix_a.'company_address.local_place_id <= '.$from_local_place_id_max.'
            ';
        }

        if ($to_place_type == SEARCH_PLACE_ADDRESS)
        {
            $result = $result.'
                AND
                package.'.$company_address_relatively_type_prefix.$company_address_package_type_prefix_b.'company_address_id >= '.$to_company_address_id_min.'
                AND
                package.'.$company_address_relatively_type_prefix.$company_address_package_type_prefix_b.'company_address_id <= '.$to_company_address_id_max.'
            ';
        }
        else
        {
            if ($to_company_address_id_min == $to_company_address_id_max)
            {
                $to_local_place_id_min = $company->getLocalPlaceIdFromCompanyAddressId($to_company_address_id_min);
                $to_local_place_id_max = $to_local_place_id_min;
            }
            else
            {
                $to_local_place_id_min = 1;
                $to_local_place_id_max = PHP_INT_MAX;
            }

            $result = $result.'
                AND
                '.$company_address_relatively_type_prefix.$company_address_package_type_prefix_b.'company_address.local_place_id >= '.$to_local_place_id_min.'
                AND
                '.$company_address_relatively_type_prefix.$company_address_package_type_prefix_b.'company_address.local_place_id <= '.$to_local_place_id_max.'
            ';
        }

        return $result;
    }

    /*
     * Получаем все посылки по критериям поиска
     * @var $search array() - Параметры поиска
     * @var $page int - Номер страницы
     * return array()
     */
    public static function getPackages($search, $page = 1)
    {
        if (count($search) < 1)
        {
            return false;
        }

        $date_converter = new Date_Converter();
        $from_caid_min = 1;
        $from_caid_max = PHP_INT_MAX;
        $to_caid_min = 1;
        $to_caid_max = PHP_INT_MAX;
        $where = ' WHERE ';
        $page = intval($page);
        if ($page < 1) $page = 1;
        $offset = ($page - 1) * self::SHOW_BY_DEFAULT;

        if ($search['search_type'] == SEARCH_TYPE_COMMON)
        {
            $where .= ' package.number = ? ';
        }
        elseif ($search['search_type'] == SEARCH_TYPE_SPECIAL)
        {
            $where .= ' package.number LIKE ? ';
            $search['track'] = '%' . $search['track'] . '%';

            // Если тип не Входящие/Исходящие, тогда выходим
            if ($search['package_type'] != PACKAGE_INPUT && $search['package_type'] != PACKAGE_OUTPUT)
            {
                return false;
            }

            // Если состояние посылки не В архиве/Активные, тогда выходим
            if ($search['active_flag'] != ACTIVE_FLAG_ACTIVE && $search['active_flag'] != ACTIVE_FLAG_ARCHIVE)
            {
                return false;
            }

            // Если поиск посылки отностиельно не Отправителя/Получателя /Текущего местоположения, тогда выходим
            if ($search['search_relatively'] != SEARCH_RELATIVELY_FROM_OR_TO && $search['search_relatively'] != SEARCH_RELATIVELY_CURRENT)
            {
                return false;
            }

            if ($search['search_place_from_or_to'] != SEARCH_PLACE_LOCAL && $search['search_place_from_or_to'] != SEARCH_PLACE_ADDRESS)
            {
                return false;
            }

            if ($search['search_place_to_or_from'] != SEARCH_PLACE_LOCAL && $search['search_place_to_or_from'] != SEARCH_PLACE_ADDRESS)
            {
                return false;
            }

            // Проверяем даты
            $d_c_begin = $date_converter->dateSplit($search['d_begin'], 1);
            $d_c_end = $date_converter->dateSplit($search['d_end'], 1);
            $check_d_begin = checkdate($d_c_begin['month'], $d_c_begin['day'], $d_c_begin['year']);
            $check_d_end = checkdate($d_c_end['month'], $d_c_end['day'], $d_c_end['year']);

            // Если дата с-по не является датой, тогда выходим
            if (!$check_d_begin || !$check_d_end)
            {
                return false;
            }

            $search['search_package_state'] = intval($search['search_package_state']);
            if ($search['search_package_state'] == PACKAGE_STATE_SEND || $search['search_package_state'] == PACKAGE_STATE_RECEIVE)
            {
                $where .= ' AND package.package_state = '. $search['search_package_state'].' ';
            }

            if ($search['active_flag'] == ACTIVE_FLAG_ARCHIVE)
            {
                $where .= ' AND (package.creation_datetime >= "'. $search['d_begin']
                    .'" AND package.creation_datetime <= "'. $search['d_end']
                    .'" + INTERVAL 1 DAY) AND package.flag = 2 ';
            }
            if ($search['active_flag'] == ACTIVE_FLAG_ACTIVE)
            {
                $where .= ' AND package.flag = 1 ';
            }

            $search['from_or_to'] = intval($search['from_or_to']);
            if ($search['from_or_to'] != 0)
            {
                $from_caid_min = $search['from_or_to'];
                $from_caid_max = $from_caid_min;
            }
            $search['to_or_from'] = intval($search['to_or_from']);
            if ($search['to_or_from'] != 0)
            {
                $to_caid_min = $search['to_or_from'];
                $to_caid_max = $to_caid_min;
            }

            $search['search_place_from_or_to'] = intval($search['search_place_from_or_to']);
            $search['search_place_to_or_from'] = intval($search['search_place_to_or_from']);

            $where .= self::createSQLWhere($search['package_type'], $search['search_relatively'],
                $search['search_place_from_or_to'], $from_caid_min, $from_caid_max,
                $search['search_place_to_or_from'], $to_caid_min, $to_caid_max);
        }
        else
        {
            return false;
        }

        $sql = 'SELECT
          to_company.name AS to_company_name,
          from_company.name AS from_company_name,
          to_company.full_name AS to_company_full_name,
          to_company.key_field AS to_company_key_field,
          from_company.full_name AS from_company_full_name,
          from_company.key_field AS from_company_key_field,
          from_company_address.address_country AS from_ca_country,
          from_company_address.address_zip AS from_ca_zip,
          from_company_address.address_region AS from_ca_region,
          from_company_address.address_area AS from_ca_area,
          from_company_address.address_city AS from_ca_city,
          from_company_address.address_town AS from_ca_town,
          from_company_address.address_street AS from_ca_street,
          from_company_address.address_home AS from_ca_home,
          from_company_address.address_case AS from_ca_case,
          from_company_address.address_build AS from_ca_build,
          from_company_address.address_apartment AS from_ca_apartment,
          from_company_address.is_transit AS from_is_transit,
          to_company_address.address_country AS to_ca_country,
          to_company_address.address_zip AS to_ca_zip,
          to_company_address.address_region AS to_ca_region,
          to_company_address.address_area AS to_ca_area,
          to_company_address.address_city AS to_ca_city,
          to_company_address.address_town AS to_ca_town,
          to_company_address.address_street AS to_ca_street,
          to_company_address.address_home AS to_ca_home,
          to_company_address.address_case AS to_ca_case,
          to_company_address.address_build AS to_ca_build,
          to_company_address.address_apartment AS to_ca_apartment,
          to_company_address.is_transit AS to_is_transit,
          package.number AS package_number,
          package.note AS package_note,
          package.id AS package_id,
          package.package_state,
          package.flag,
          package.creation_datetime AS package_creation_datetime,
          now_from_company.name AS now_from_company_name,
          now_from_company.full_name AS now_from_company_full_name,
          now_from_company.key_field AS now_from_company_key_field,
          now_from_company_address.address_country AS now_from_ca_country,
          now_from_company_address.is_transit AS now_from_is_transit,
          now_from_company_address.address_zip AS now_from_ca_zip,
          now_from_company_address.address_region AS now_from_ca_region,
          now_from_company_address.address_area AS now_from_ca_area,
          now_from_company_address.address_city AS now_from_ca_city,
          now_from_company_address.address_town AS now_from_ca_town,
          now_from_company_address.address_street AS now_from_ca_street,
          now_from_company_address.address_home AS now_from_ca_home,
          now_from_company_address.address_case AS now_from_ca_case,
          now_from_company_address.address_build AS now_from_ca_build,
          now_from_company_address.address_apartment AS now_from_ca_apartment,
          now_to_company_address.address_country AS now_to_ca_country,
          now_to_company_address.address_zip AS now_to_ca_zip,
          now_to_company_address.address_region AS now_to_ca_region,
          now_to_company_address.address_area AS now_to_ca_area,
          now_to_company_address.address_city AS now_to_ca_city,
          now_to_company_address.address_town AS now_to_ca_town,
          now_to_company_address.address_street AS now_to_ca_street,
          now_to_company_address.address_home AS now_to_ca_home,
          now_to_company_address.address_case AS now_to_ca_case,
          now_to_company_address.address_build AS now_to_ca_build,
          now_to_company_address.address_apartment AS now_to_ca_apartment,
          now_to_company_address.is_transit AS now_to_is_transit,
          now_to_company.name AS now_to_company_name,
          now_to_company.full_name AS now_to_company_full_name,
          now_to_company.key_field AS now_to_company_key_field
        FROM
          package
          INNER JOIN company_address from_company_address ON (package.from_company_address_id = from_company_address.id)
          INNER JOIN company_address to_company_address ON (package.to_company_address_id = to_company_address.id)
          INNER JOIN company from_company ON (from_company_address.company_id = from_company.id)
          INNER JOIN company to_company ON (to_company_address.company_id = to_company.id)
          INNER JOIN company_address now_from_company_address ON (package.now_from_company_address_id = now_from_company_address.id)
          INNER JOIN company now_from_company ON (now_from_company_address.company_id = now_from_company.id)
          INNER JOIN company_address now_to_company_address ON (package.now_to_company_address_id = now_to_company_address.id)
          INNER JOIN company now_to_company ON (now_to_company_address.company_id = now_to_company.id) '
            . $where .
            ' ORDER BY package.number LIMIT '. self::SHOW_BY_DEFAULT .' OFFSET ' . $offset;

        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->execute([$search['track']]);
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
     * @var $search array() - Параметры поиска
     * return int
     */
    public static function getTotalPackages($search)
    {
        if (count($search) < 1)
        {
            return false;
        }

        $date_converter = new Date_Converter();
        $from_caid_min = 1;
        $from_caid_max = PHP_INT_MAX;
        $to_caid_min = 1;
        $to_caid_max = PHP_INT_MAX;
        $where = ' WHERE ';

        if ($search['search_type'] == SEARCH_TYPE_COMMON)
        {
            $where .= ' package.number = ? ';
        }
        elseif ($search['search_type'] == SEARCH_TYPE_SPECIAL)
        {
            $where .= ' package.number LIKE ? ';
            $search['track'] = '%' . $search['track'] . '%';

            // Если тип не Входящие/Исходящие, тогда выходим
            if ($search['package_type'] != PACKAGE_INPUT && $search['package_type'] != PACKAGE_OUTPUT)
            {
                return false;
            }

            // Если состояние посылки не В архиве/Активные, тогда выходим
            if ($search['active_flag'] != ACTIVE_FLAG_ACTIVE && $search['active_flag'] != ACTIVE_FLAG_ARCHIVE)
            {
                return false;
            }

            // Если поиск посылки отностиельно не Отправителя/Получателя /Текущего местоположения, тогда выходим
            if ($search['search_relatively'] != SEARCH_RELATIVELY_FROM_OR_TO && $search['search_relatively'] != SEARCH_RELATIVELY_CURRENT)
            {
                return false;
            }

            if ($search['search_place_from_or_to'] != SEARCH_PLACE_LOCAL && $search['search_place_from_or_to'] != SEARCH_PLACE_ADDRESS)
            {
                return false;
            }

            if ($search['search_place_to_or_from'] != SEARCH_PLACE_LOCAL && $search['search_place_to_or_from'] != SEARCH_PLACE_ADDRESS)
            {
                return false;
            }

            // Проверяем даты
            $d_c_begin = $date_converter->dateSplit($search['d_begin'], 1);
            $d_c_end = $date_converter->dateSplit($search['d_end'], 1);
            $check_d_begin = checkdate($d_c_begin['month'], $d_c_begin['day'], $d_c_begin['year']);
            $check_d_end = checkdate($d_c_end['month'], $d_c_end['day'], $d_c_end['year']);

            // Если дата с-по не является датой, тогда выходим
            if (!$check_d_begin || !$check_d_end)
            {
                return false;
            }

            if ($search['active_flag'] == ACTIVE_FLAG_ARCHIVE)
            {
                $where .= ' AND (package.creation_datetime >= "'. $search['d_begin']
                    .'" AND package.creation_datetime <= "'. $search['d_end']
                    .'" + INTERVAL 1 DAY) AND package.flag = 2 ';
            }
            if ($search['active_flag'] == ACTIVE_FLAG_ACTIVE)
            {
                $where .= ' AND package.flag = 1 ';
            }

            $search['from_or_to'] = intval($search['from_or_to']);
            if ($search['from_or_to'] != 0)
            {
                $from_caid_min = $search['from_or_to'];
                $from_caid_max = $from_caid_min;
            }
            $search['to_or_from'] = intval($search['to_or_from']);
            if ($search['to_or_from'] != 0)
            {
                $to_caid_min = $search['to_or_from'];
                $to_caid_max = $to_caid_min;
            }
            $search['search_place_from_or_to'] = intval($search['search_place_from_or_to']);
            $search['search_place_to_or_from'] = intval($search['search_place_to_or_from']);

            $where .= self::createSQLWhere($search['package_type'], $search['search_relatively'],
                $search['search_place_from_or_to'], $from_caid_min, $from_caid_max,
                $search['search_place_to_or_from'], $to_caid_min, $to_caid_max);
        }
        else
        {
            return false;
        }

        $sql = 'SELECT
          COUNT(*) AS row_count
        FROM
          package
          INNER JOIN company_address from_company_address ON (package.from_company_address_id = from_company_address.id)
          INNER JOIN company_address to_company_address ON (package.to_company_address_id = to_company_address.id)
          INNER JOIN company from_company ON (from_company_address.company_id = from_company.id)
          INNER JOIN company to_company ON (to_company_address.company_id = to_company.id)
          INNER JOIN company_address now_from_company_address ON (package.now_from_company_address_id = now_from_company_address.id)
          INNER JOIN company now_from_company ON (now_from_company_address.company_id = now_from_company.id)
          INNER JOIN company_address now_to_company_address ON (package.now_to_company_address_id = now_to_company_address.id)
          INNER JOIN company now_to_company ON (now_to_company_address.company_id = now_to_company.id) '
        . $where;

        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->execute([$search['track']]);
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
     * Удаляем один объект посылки из сессии
     * @var $id int - ID посылки
     */
    public static function outPackageObject($id)
    {
        session_start();
        if(isset($_SESSION['p_object']))
        {
            foreach ($_SESSION['p_object'] as $p_key => $p_value)
            {
                if ($p_key == $id)
                {
                    unset($_SESSION['p_object'][$p_key]);
                    break;
                }
            }
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
     * Устанавливаем посылку в режим - доставлено
     * @var $id int - id посылки
     * @var $receipt_datetime string - Дата и время получения
     */
    public static function setDelivered($id, $receipt_datetime)
    {
        $sql = 'UPDATE package
          SET receipt_datetime = :receipt_datetime, flag = 2
          WHERE id = :id';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':receipt_datetime', $receipt_datetime, PDO::PARAM_STR);
        $result->execute();
    }

    /*
     * Устанавливаем состояние посылки
     * @var $id int - id посылки
     * @var $package_state int - состояние посылки
     *
     * Возможные варианты $package_state:
     * 1 - Получено
     * 2 - Отправлено
     */
    public static function setPackageState($id, $package_state)
    {
        $sql = 'UPDATE package
          SET package_state = :package_state
          WHERE id = :id';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':package_state', $package_state, PDO::PARAM_INT);
        $result->execute();
    }

    /*
     * Обновить текущие точки от кого и кому
     * @var $id int - Id посылки
     * @var $now_points array() - текущие адреса
     */
    private static function updateNowAddresses($id, $now_points)
    {
        $sql = 'UPDATE package
          SET
          now_from_company_address_id = :now_from_company_address_id,
          now_to_company_address_id = :now_to_company_address_id
          WHERE id = :id';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':now_from_company_address_id', $now_points['now_from_company_address_id'], PDO::PARAM_INT);
        $result->bindParam(':now_to_company_address_id', $now_points['now_to_company_address_id'], PDO::PARAM_INT);
        $result->execute();
    }

    /*
     * Определяем текущие точки (от кого и кому) и обновляем  их
     * @var $pid int - Id посылки
     * return boolean
     */
    public static function setNowAddresses($pid)
    {
        // Поулучаем полный маршрут посылки
        $routes = Route::getPackageRoute($pid, 2);

        if (count($routes) < 2)
        {
            return false;
        }

        $now_points['now_from_company_address_id'] = 0;
        $now_points['now_to_company_address_id'] = 0;

        for($i = count($routes); $i >= 0; $i--)
        {
            if ($routes[$i]['is_receive'] == 1)
            {
                $now_points['now_from_company_address_id'] = $routes[$i]['company_address_id'];
                $now_points['now_to_company_address_id'] = $routes[$i+1]['company_address_id'];
                break;
            }
        }

        if ($now_points['now_from_company_address_id'] == 0)
        {
            return false;
        }

        // Обновляем текущие точки
        self::updateNowAddresses($pid, $now_points);
        return true;
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