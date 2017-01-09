<?php


class Company
{
    /*******************************************************
     ********************* Поля класса *********************
     *******************************************************/



    /***********************************************************
     ********************** Методы класса **********************
     ***********************************************************/

    /*
     * Получаем информацию о адресе организации
     * @var $company_id int - id компании
     * return array() OR boolean
     */
    public static function getCompanyAddressByCompany($company_id)
    {
        $sql = 'SELECT
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
          company_address.flag
        FROM
          company_address
        WHERE
          company_address.company_id = :company_id AND
          company_address.is_transit = 0 AND
          company_address.flag >= 0';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':company_id', $company_id, PDO::PARAM_INT);
        $result->execute();
        $company_address =  null;
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
          company.name AS c_name,
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

        if($company)
        {
            return $company;
        }
        return false;
    }

    /*
     * Получаем все организации
     * return array()
     */
    public static function getAllCompanies()
    {
        $sql = 'SELECT
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
          company.name AS c_name,
          company.full_name AS c_full_name,
          company.key_field AS c_key_field,
          company.flag AS c_flag
        FROM
          company_address
          INNER JOIN company ON (company_address.company_id = company.id)
        WHERE
          (company_address.flag = 0 OR company_address.flag = 1) AND
          (company.flag = 0 OR company.flag = 1)';

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
     * return boolean
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
     * @var $company array - организация
     * return int OR boolean
     */
    public static function addCompany($company)
    {
        $sql = 'INSERT INTO company (name, full_name, key_field, flag)
          VALUES (:name, :full_name, :key_field, 1)';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':name', $company['name'], PDO::PARAM_STR);
        $result->bindParam(':full_name', $company['full_name'], PDO::PARAM_STR);
        $result->bindParam(':key_field', $company['key_field'], PDO::PARAM_STR);
        if($result->execute())
        {
            return $db->lastInsertId();
        }
        return false;
    }

    /*
     * Добавление адреса для организации
     * @var $company_id int - id организации
     * @var $company array - организация
     * return int or boolean
     */
    public static function addCompanyAddress($company_id, $company)
    {
        $sql = 'INSERT INTO company_address (company_id, address_country, address_zip, address_region, address_area,
          address_city, address_town, address_street, address_home, address_case, address_build, address_apartment, flag)
          VALUES (:company_id, :address_country, :address_zip, :address_region, :address_area,
          :address_city, :address_town, :address_street, :address_home, :address_case, :address_build, :address_apartment, 1)';

        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':company_id', $company_id, PDO::PARAM_INT);
        $result->bindParam(':address_country', $company['address_country'], PDO::PARAM_STR);
        $result->bindParam(':address_zip', $company['address_zip'], PDO::PARAM_STR);
        $result->bindParam(':address_region', $company['address_region'], PDO::PARAM_STR);
        $result->bindParam(':address_area', $company['address_area'], PDO::PARAM_STR);
        $result->bindParam(':address_city', $company['address_city'], PDO::PARAM_STR);
        $result->bindParam(':address_town', $company['address_town'], PDO::PARAM_STR);
        $result->bindParam(':address_street', $company['address_street'], PDO::PARAM_STR);
        $result->bindParam(':address_home', $company['address_home'], PDO::PARAM_STR);
        $result->bindParam(':address_case', $company['address_case'], PDO::PARAM_STR);
        $result->bindParam(':address_build', $company['address_build'], PDO::PARAM_STR);
        $result->bindParam(':address_apartment', $company['address_apartment'], PDO::PARAM_STR);
        if($result->execute())
        {
            return $db->lastInsertId();
        }
        return false;
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
          company_address.is_mfc
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
}