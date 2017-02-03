<?php


class Company
{
    /*******************************************************
     ********************* Поля класса *********************
     *******************************************************/

    const SHOW_BY_DEFAULT = 20;

    /***********************************************************
     ********************** Методы класса **********************
     ***********************************************************/

    /*
     * Получаем информацию о адресе организации
     * @var $company_id int - id компании
     * @var $address_type int - тип адреса
     * @var $transit_param int - параметр транзита (если этот параметр = 1,
     * тогда к выборке будут добавлены отбор по транзитным точкам. В противном
     * выборки по транзитным точкам не будет).
     * return array() OR boolean
     */
    public static function getCompanyAddressByCompany($company_id, $address_type = 0, $transit_param = 1)
    {
        $select = '';
        $where = '';

        if ($address_type == 1)
        {
            $select = '
              company_address.id,
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
              company_address.is_transit,
              company_address.flag
            ';
        }
        else
        {
            $select = '
              company_address.id,
              company_address.address_country,
              company_address.address_zip,
              company_address.address_region,
              company_address.address_area,
              company_address.address_city,
              company_address.address_town,
              company_address.address_street,
              company_address.address_home,
              company_address.address_case,
              company_address.address_build,
              company_address.address_apartment,
              company_address.is_transit,
              company_address.flag ';
        }

        if ($transit_param == 1)
        {
            $where .= ' AND company_address.is_transit = 0 ';
        }

        $sql = 'SELECT '
            . $select .'
        FROM
          company_address
        WHERE
          company_address.company_id = :company_id AND
          company_address.flag >= 0 '. $where;

        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':company_id', $company_id, PDO::PARAM_INT);
        $result->execute();
        $company_address = null;
        $i = 0;
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $company_address[$i] = $row;
            $i++;
        }
        return $company_address;
    }

    /*
     * Получаем ифнормацию о организации
     * @var $id int - id компании
     * return array() OR boolean
     */
    public static function getCompany($id)
    {
        $sql = 'SELECT
          company_address.id AS ca_id,
          company_address.company_id AS ca_company_id,
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
          company_address.is_transit,
          company_address.flag AS ca_flag,
          company.name AS c_name,
          company.is_mfc,
          company.full_name AS c_full_name,
          company.key_field AS c_key_field,
          company.flag AS c_flag
        FROM
          company_address
          INNER JOIN company ON (company_address.company_id = company.id)
        WHERE
          company_address.id = :id';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->execute();

        // Обращаемся к записи
        $company = $result->fetch(PDO::FETCH_ASSOC);

        if ($company) {
            return $company;
        }
        return false;
    }

    /*
     * Получаем информацию о организации (все поля)
     * @var $search_param array() - искомые значения
     * @var $page int - номер страницы
     * return array() OR boolean
     */
    public static function getCompanies($search_param = null, $page = 1)
    {
        $search_param['search_value'] = '%' . $search_param['search_value'] . '%';
        $page = intval($page);
        if ($page < 1) {
            $page = 1;
        }
        $offset = ($page - 1) * self::SHOW_BY_DEFAULT;
        $sql = 'SELECT
          company.id,
          company.name,
          company.full_name,
          company.key_field,
          company.is_mfc,
          company.flag
        FROM
          company
        WHERE
          (company.name LIKE ? OR
          company.full_name LIKE ? OR
          company.key_field LIKE ?) AND
          company.flag > 0 AND
          company.id > 0
        ORDER BY company.name LIMIT ' . self::SHOW_BY_DEFAULT . ' OFFSET ' . $offset;
        $db = Database::getConnection();
        $result = $db->prepare($sql);

        $result->execute([$search_param['search_value'], $search_param['search_value'], $search_param['search_value']]);

        // Получение и возврат результатов
        $companies = null;
        $i = 0;
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $companies[$i] = $row;
            $i++;
        }
        return $companies;
    }

    /*
     * Получить все поля из таблицы company по id
     * @var $id int - ID организации
     * return array()
     */
    public static function getCompanyInfo($id)
    {
        $sql = 'SELECT
          *
        FROM
          company
        WHERE
          company.id = :id';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->execute();

        // Обращаемся к записи
        $company = $result->fetch(PDO::FETCH_ASSOC);

        if ($company) {
            return $company;
        }
        return false;
    }

    /*
     * Получить все поля из таблицы company_address по id
     * @var $id int - ID адреса организации
     * return array()
     */
    public static function getCompanyAddressInfo($id)
    {
        $sql = 'SELECT
          *
        FROM
          company_address
        WHERE
          company_address.id = :id';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->execute();

        // Обращаемся к записи
        $company_address = $result->fetch(PDO::FETCH_ASSOC);

        if ($company_address) {
            return $company_address;
        }
        return false;
    }

    /*
     * Получаем общее количество организаций
     * @var $search_param array() - искомые значения
     * return int
     */
    public static function getTotalCompanies($search_param)
    {
        $search_param['search_value'] = '%' . $search_param['search_value'] . '%';
        $sql = 'SELECT
          COUNT(*) AS row_count
        FROM
          company
        WHERE
          (company.name LIKE ? OR
          company.full_name LIKE ? OR
          company.key_field LIKE ?) AND
          company.flag > 0 AND
          company.id > 0';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->execute([$search_param['search_value'], $search_param['search_value'], $search_param['search_value']]);
        // Обращаемся к записи
        $count = $result->fetch(PDO::FETCH_ASSOC);

        if ($count) {
            return $count['row_count'];
        }
        return 0;
    }




    /*
     * Получаем все организации
     * return array()
     */
    public static function getAllCompanies()
    {
        $sql = 'SELECT
          company_address.id,
          company_address.company_id,
          company_address.local_place_id,
          company_address.address_country,
          company_address.address_zip,
          company_address.address_region,
          company_address.address_area,
          company_address.address_city,
          company_address.address_town,
          company_address.address_street,
          company_address.address_home,
          company_address.address_case,
          company_address.address_build,
          company_address.address_apartment,
          company_address.is_transit,
          company_address.created_datetime,
          company_address.created_user_id,
          company_address.changed_datetime,
          company_address.changed_user_id,
          company.name,
          company.key_field,
          company.is_mfc,
          company.full_name
        FROM
          company_address
          INNER JOIN company ON (company_address.company_id = company.id)
        WHERE
          company_address.flag > 0 AND
          company.flag > 0
        ORDER BY
          company_address.company_id';

        $db = Database::getConnection();

        $result = $db->query($sql);

        // Получение и возврат результатов
        $companies = null;
        $i = 0;
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $companies[$i] = $row;
            $i++;
        }
        return $companies;
    }

    /*
     * Проверка имеется данная запись в БД (поле и значение)
     * @var $key_field string - поле
     * return boolean OR int
     */
    public static function checkKeyFieldExists($key_field)
    {
        $sql = 'SELECT id FROM company WHERE key_field =  :key_field';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':key_field', $key_field, PDO::PARAM_STR);
        $result->execute();

        $company = $result->fetch();

        if($company)
        {
            return $company['id'];
        }
        return false;
    }

    /*
     * Добавление новой организации
     * @var $company array() - организация
     * return int OR boolean
     */
    public static function addCompany($company)
    {
        $sql = 'INSERT INTO company (name, full_name, key_field, is_mfc, created_datetime, created_user_id, flag)
          VALUES (:name, :full_name, :key_field, :is_mfc, :created_datetime, :created_user_id, 1)';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':name', $company['name'], PDO::PARAM_STR);
        $result->bindParam(':full_name', $company['full_name'], PDO::PARAM_STR);
        $result->bindParam(':key_field', $company['key_field'], PDO::PARAM_INT);
        $result->bindParam(':is_mfc', $company['is_mfc'], PDO::PARAM_INT);
        $result->bindParam(':created_datetime', $company['created_datetime'], PDO::PARAM_STR);
        $result->bindParam(':created_user_id', $company['created_user_id'], PDO::PARAM_INT);
        if($result->execute())
        {
            return $db->lastInsertId();
        }
        return false;
    }

    /*
     * Обновление организации
     * @var $id int - ID организации
     * @var $company array() - организация
     */
    public static function updateCompany($id, $company)
    {
        $sql = 'UPDATE company
          SET name = :name, full_name = :full_name, key_field = :key_field, is_mfc = :is_mfc,
          changed_datetime = :changed_datetime, changed_user_id = :changed_user_id
          WHERE id = :id AND flag = 1';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':name', $company['name'], PDO::PARAM_STR);
        $result->bindParam(':full_name', $company['full_name'], PDO::PARAM_STR);
        $result->bindParam(':key_field', $company['key_field'], PDO::PARAM_INT);
        $result->bindParam(':is_mfc', $company['is_mfc'], PDO::PARAM_INT);
        $result->bindParam(':changed_datetime', $company['changed_datetime'], PDO::PARAM_STR);
        $result->bindParam(':changed_user_id', $company['changed_user_id'], PDO::PARAM_INT);
        $result->execute();
    }

    /*
     * Удаляем организацию
     * @var $id int - ID организации
     * @var $company array() - организация
     */
    public static function deleteCompany($id, $company)
    {
        $sql = 'UPDATE company
          SET
            changed_datetime = :changed_datetime, changed_user_id = :changed_user_id, flag = -1
          WHERE id = :id AND flag = 1';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':changed_datetime', $company['changed_datetime'], PDO::PARAM_STR);
        $result->bindParam(':changed_user_id', $company['changed_user_id'], PDO::PARAM_INT);
        $result->execute();
    }



    /*
     * Добавление адреса для организации
     * @var $company_id int - id организации
     * @var $company_address array() - организация
     * return int or boolean
     */
    public static function addCompanyAddress($company_id, $company_address)
    {
        if ($company_address['is_transit'] == null || !isset($company_address['is_transit']))
        {
            $company_address['is_transit'] = 0;
        }

        $sql = 'INSERT INTO company_address (company_id, address_country, address_zip, address_region, address_area,
          address_city, address_town, address_street, address_home, address_case, address_build, address_apartment,
          local_place_id, is_transit,	created_datetime, created_user_id, flag)
          VALUES (:company_id, :address_country, :address_zip, :address_region, :address_area,
          :address_city, :address_town, :address_street, :address_home, :address_case, :address_build, :address_apartment,
          :local_place_id, :is_transit,	:created_datetime, :created_user_id, 1)';

        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':company_id', $company_id, PDO::PARAM_INT);
        $result->bindParam(':address_country', $company_address['address_country'], PDO::PARAM_STR);
        $result->bindParam(':address_zip', $company_address['address_zip'], PDO::PARAM_STR);
        $result->bindParam(':address_region', $company_address['address_region'], PDO::PARAM_STR);
        $result->bindParam(':address_area', $company_address['address_area'], PDO::PARAM_STR);
        $result->bindParam(':address_city', $company_address['address_city'], PDO::PARAM_STR);
        $result->bindParam(':address_town', $company_address['address_town'], PDO::PARAM_STR);
        $result->bindParam(':address_street', $company_address['address_street'], PDO::PARAM_STR);
        $result->bindParam(':address_home', $company_address['address_home'], PDO::PARAM_STR);
        $result->bindParam(':address_case', $company_address['address_case'], PDO::PARAM_STR);
        $result->bindParam(':address_build', $company_address['address_build'], PDO::PARAM_STR);
        $result->bindParam(':address_apartment', $company_address['address_apartment'], PDO::PARAM_STR);
        $result->bindParam(':local_place_id', $company_address['local_place_id'], PDO::PARAM_INT);
        $result->bindParam(':is_transit', $company_address['is_transit'], PDO::PARAM_INT);
        $result->bindParam(':created_datetime', $company_address['created_datetime'], PDO::PARAM_STR);
        $result->bindParam(':created_user_id', $company_address['created_user_id'], PDO::PARAM_INT);
        if($result->execute())
        {
            return $db->lastInsertId();
        }
        return false;
    }

    /*
     * Обновление адреса организации
     * @var $id int - ID адреса организации
     * @var $company_address array() - адрес организации
     */
    public static function updateCompanyAddress($id, $company_address)
    {
        $sql = 'UPDATE company_address
          SET company_id = :company_id, local_place_id = :local_place_id, address_country = :address_country,
          address_zip = :address_zip, address_region = :address_region, address_area = :address_area,
          address_city = :address_city, address_town = :address_town, address_street = :address_street,
          address_home = :address_home, address_case = :address_case, address_build = :address_build,
          address_apartment = :address_apartment, is_transit = :is_transit,
          changed_datetime = :changed_datetime, changed_user_id = :changed_user_id
          WHERE id = :id AND flag = 1 ';
        $db = Database::getConnection();
        $result = $db->prepare($sql);

        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':company_id', $company_address['company_id'], PDO::PARAM_INT);
        $result->bindParam(':local_place_id', $company_address['local_place_id'], PDO::PARAM_INT);
        $result->bindParam(':address_country', $company_address['address_country'], PDO::PARAM_STR);
        $result->bindParam(':address_zip', $company_address['address_zip'], PDO::PARAM_STR);
        $result->bindParam(':address_region', $company_address['address_region'], PDO::PARAM_STR);
        $result->bindParam(':address_area', $company_address['address_area'], PDO::PARAM_STR);
        $result->bindParam(':address_city', $company_address['address_city'], PDO::PARAM_STR);
        $result->bindParam(':address_town', $company_address['address_town'], PDO::PARAM_STR);
        $result->bindParam(':address_street', $company_address['address_street'], PDO::PARAM_STR);
        $result->bindParam(':address_home', $company_address['address_home'], PDO::PARAM_STR);
        $result->bindParam(':address_case', $company_address['address_case'], PDO::PARAM_STR);
        $result->bindParam(':address_build', $company_address['address_build'], PDO::PARAM_STR);
        $result->bindParam(':address_apartment', $company_address['address_apartment'], PDO::PARAM_STR);
        $result->bindParam(':is_transit', $company_address['is_transit'], PDO::PARAM_INT);
        $result->bindParam(':changed_datetime', $company_address['changed_datetime'], PDO::PARAM_STR);
        $result->bindParam(':changed_user_id', $company_address['changed_user_id'], PDO::PARAM_INT);

        $result->execute();
    }

    /*
     * Удаляем адрес организации
     * @var $id int - ID организации
     * @var $company_address array() - организация
     */
    public static function deleteCompanyAddress($id, $company_address)
    {
        $sql = 'UPDATE company_address
          SET
            changed_datetime = :changed_datetime, changed_user_id = :changed_user_id, flag = -1
          WHERE id = :id AND flag = 1 ';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':changed_datetime', $company_address['changed_datetime'], PDO::PARAM_STR);
        $result->bindParam(':changed_user_id', $company_address['changed_user_id'], PDO::PARAM_INT);
        $result->execute();
    }

    /*
     * Получаем все транзитные точки
     * @var $from int - id организации отправителя
     * @var $to int - id организации получателя
     * return array()
     */
    public static function getTransits($from, $to)
    {
        $sql = 'SELECT
          company_address.id AS ca_id,
          company_address.company_id AS ca_company_id,
          company.name,
          company.full_name,
          company.key_field,
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
          company_address.is_transit,
          company.is_mfc
        FROM
          company_address
          INNER JOIN company ON (company_address.company_id = company.id)
        WHERE
          company.flag >= 0 AND
          company_address.flag >= 0 AND
          company_address.is_transit = 1 AND
          company_address.id <> :from_c AND
          company_address.id <> :to_c ';

        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':from_c', $from, PDO::PARAM_INT);
        $result->bindParam(':to_c', $to, PDO::PARAM_INT);
        $result->execute();

        // Получение и возврат результатов
        $transits = null;
        $i = 0;
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $transits[$i] = $row;
            $i++;
        }
        return $transits;

    }

    /*
     * Проверка: принадлежит ли адрес организации данной организации
     * @var $caid int - ID адреса организации
     * @var $cid int - ID организации
     * return boolean
     */
    public static function checkCompanyAddressBelongCompany($caid, $cid)
    {
        $sql = 'SELECT company_id FROM company_address WHERE id = :id';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':id', $caid, PDO::PARAM_INT);
        $result->execute();

        $company_address = $result->fetch();

        if($company_address)
        {
            if ($company_address['company_id'] == $cid)
            {
                return true;
            }
        }
        return false;
    }

    /*
     * Запоминаем организацию в сессию
     * @var $id int - id
     * @var $c_type string - Откуда/Куда
     */
    public static function memorizeCompany($id, $c_type)
    {
        session_start();
        $_SESSION['company'][$c_type] = $id;
    }

    /*
     * Проверяем организацию в сессии
     * @var $c_type string - Откуда/Куда
     * return int OR null
     */
    public static function checkCompanyInMemory($c_type)
    {
        session_start();
        if(isset($_SESSION['company'][$c_type]))
        {
            return $_SESSION['company'][$c_type];
        }
        return null;
    }

    /*
     * Удаляем организацию из сессии
     * @var $c_type string - Откуда/Куда
     */
    public static function outCompanyFromMemory($c_type)
    {
        session_start();
        if(isset($_SESSION['company'][$c_type]))
        {
            unset($_SESSION['company'][$c_type]);
        }
    }

    /*
     * Определить тип организации
     */
    public static function determineCompanyType($c_type)
    {
        if($c_type == 'f')
        {
            return 'from';
        }
        if($c_type == 't')
        {
            return 'to';
        }
    }

    /*
     * Проверяем есть ли в базе данных такая организация
     * @var $id int - ID
     * return boolean
     */
    public static function checkCompanyInDb($id)
    {
        $sql = 'SELECT name FROM company WHERE id = :id AND flag > 0';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->execute();

        $company = $result->fetch();

        if($company)
        {
            return true;
        }
        return false;
    }

    /*
     * Проверяем действительно ли данный адрес организации транзит
     * @var $id int - Id
     * return boolean
     */
    public static function checkTransit($id)
    {
        $sql = 'SELECT id FROM company_address WHERE id = :id AND flag > 0 AND is_transit = 1 ';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->execute();

        $company_address = $result->fetch();

        if($company_address)
        {
            return true;
        }
        return false;
    }
}