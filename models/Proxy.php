<?php


class Proxy
{
    /*******************************************************
     ********************* Поля класса *********************
     *******************************************************/



    /***********************************************************
     ********************** Методы класса **********************
     ***********************************************************/

    /*
     * Возвращает все доверенные лица
     * @var $search string - искомое значение
     * return array()
     */
    public static function getProxyPersons($search)
    {
        $where = null;// Параметры поиска

        $lastname = null;
        $firstname = null;
        $middlename = null;

        $str_segments = explode(' ', $search);

        if (count($str_segments) == 3)
        {
            $lastname = $str_segments[0];
            $firstname = $str_segments[1];
            $middlename = $str_segments[2];
            $where = " WHERE proxy_person.flag > 0 AND proxy_person.lastname LIKE ?
            AND proxy_person.firstname LIKE ?
            AND proxy_person.middlename LIKE ? ";
        }
        if (count($str_segments) == 2)
        {
            $lastname = $str_segments[0];
            $firstname = $str_segments[1];
            $where = " WHERE proxy_person.flag > 0 AND (proxy_person.lastname LIKE ?
            AND proxy_person.firstname LIKE ?) ";
        }
        if (count($str_segments) == 1)
        {
            $lastname = $search;
            $firstname = $search;
            $middlename = $search;
            $where = " WHERE proxy_person.flag > 0 AND (proxy_person.lastname LIKE ? OR
            proxy_person.firstname LIKE ? OR
            proxy_person.middlename LIKE ?) ";
        }

        $sql = "SELECT
          proxy_person.id,
          proxy_person.lastname,
          proxy_person.firstname,
          proxy_person.middlename
        FROM
          proxy_person " . $where . "
        ORDER BY proxy_person.lastname";

        $db = Database::getConnection();
        $result = $db->prepare($sql);


        $lastname = '%' .$lastname. '%';
        $firstname = '%' .$firstname. '%';
        $middlename = '%' .$middlename. '%';

        if (count($str_segments) == 3)
        {
            $result->execute([$lastname, $firstname, $middlename]);
        }
        if (count($str_segments) == 2)
        {
            $result->execute([$lastname, $firstname]);
        }
        if (count($str_segments) == 1)
        {
            $result->execute([$lastname, $firstname, $middlename]);
        }

        // Получение и возврат результатов
        $proxy_persons = null;
        $i = 0;
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $proxy_persons[$i] = $row;
            $i++;
        }
        return $proxy_persons;
    }

    /*
     * Получаем все поля о доверенном лице по ID
     * @var $id int - ID доверенного лица
     * return array() OR bool
     */
    public static function getProxyPerson($id)
    {
        $sql = 'SELECT
          *
        FROM
          proxy_person
        WHERE
          proxy_person.flag > 0 AND
          proxy_person.id = :id';

        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->execute();

        // Обращаемся к записи
        $proxy_person = $result->fetch(PDO::FETCH_ASSOC);

        if ($proxy_person) {
            return $proxy_person;
        }
        return false;
    }

    /*
     * Получаем доверенное лицо по ID
     * @var $id int - ID доверенного лица
     * return array() OR bool
     */
    public static function getProxyPersonInfo($id)
    {
        $sql = 'SELECT
          proxy_person.lastname,
          proxy_person.firstname,
          proxy_person.middlename,
          proxy_person.document_type_id,
          proxy_person.document_series,
          proxy_person.document_number,
          proxy_person.date_issued,
          proxy_person.date_expired,
          proxy_person.place_name,
          proxy_person.place_code,
          proxy_person.phone_number,
          document_type.name AS document_name
        FROM
          proxy_person
          INNER JOIN document_type ON (proxy_person.document_type_id = document_type.id)
        WHERE
          proxy_person.flag > 0 AND
          proxy_person.id = :id';

        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->execute();

        // Обращаемся к записи
        $proxy_person = $result->fetch(PDO::FETCH_ASSOC);

        if ($proxy_person) {
            return $proxy_person;
        }
        return false;
    }

    /*
     * Обновить данные доверенные лица
     * @var $id int - ID доверенного лица
     * @var $proxy_person array() - данные о доверенном лице
     */
    public static function updateProxyPerson($id, $proxy_person)
    {
        $sql = 'UPDATE proxy_person
          SET lastname = :lastname, firstname = :firstname, middlename = :middlename, document_type_id = :document_type_id,
          document_series = :document_series, document_number = :document_number, date_issued = :date_issued,
          date_expired = :date_expired, place_name = :place_name, place_code = :place_code, phone_number = :phone_number,
          changed_datetime = :changed_datetime, changed_user_id = :changed_user_id
          WHERE id = :id AND flag = 1';

        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':lastname', $proxy_person['lastname'], PDO::PARAM_STR);
        $result->bindParam(':firstname', $proxy_person['firstname'], PDO::PARAM_STR);
        $result->bindParam(':middlename', $proxy_person['middlename'], PDO::PARAM_STR);
        $result->bindParam(':document_type_id', $proxy_person['document_type_id'], PDO::PARAM_INT);
        $result->bindParam(':document_series', $proxy_person['document_series'], PDO::PARAM_STR);
        $result->bindParam(':document_number', $proxy_person['document_number'], PDO::PARAM_STR);
        $result->bindParam(':date_issued', $proxy_person['date_issued'], PDO::PARAM_STR);
        $result->bindParam(':date_expired', $proxy_person['date_expired'], PDO::PARAM_STR);
        $result->bindParam(':place_name', $proxy_person['place_name'], PDO::PARAM_STR);
        $result->bindParam(':place_code', $proxy_person['place_code'], PDO::PARAM_STR);
        $result->bindParam(':phone_number', $proxy_person['phone_number'], PDO::PARAM_STR);
        $result->bindParam(':changed_datetime', $proxy_person['changed_datetime'], PDO::PARAM_STR);
        $result->bindParam(':changed_user_id', $proxy_person['changed_user_id'], PDO::PARAM_INT);

        return $result->execute();
    }

    /*
     * Удалить доверенное лицо
     * @var $id int - ID доверенного лица
     * @var $proxy_person array() - информация о доверенном лице
     */
    public static function deleteProxyPerson($id, $proxy_person)
    {
        $sql = 'UPDATE proxy_person
          SET
          changed_datetime = :changed_datetime, changed_user_id = :changed_user_id, flag = -1
          WHERE id = :id AND flag = 1';

        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':changed_datetime', $proxy_person['changed_datetime'], PDO::PARAM_STR);
        $result->bindParam(':changed_user_id', $proxy_person['changed_user_id'], PDO::PARAM_INT);

        return $result->execute();
    }

    /*
     * Получить доверенность
     * @var $id int - id доверенности
     * return array()
     */
    public static function getProxy($id)
    {
        $sql = 'SELECT
          *
        FROM
          proxy
        WHERE
          proxy.flag > 0 AND
          proxy.id = :id';

        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->execute();

        // Обращаемся к записи
        $proxy = $result->fetch(PDO::FETCH_ASSOC);

        if ($proxy) {
            return $proxy;
        }
        return false;
    }

    /*
     * Обновляет доверенность
     * @var $id int - id доверенности
     * @var $proxy array() - информация о доверенности
     */
    public static function updateProxy($id, $proxy)
    {
        $sql = 'UPDATE proxy
          SET number = :number, document_type_id = :document_type_id, date_issued = :date_issued, date_expired = :date_expired,
          authority_issued = :authority_issued, changed_datetime = :changed_datetime, changed_user_id = :changed_user_id
          WHERE id = :id AND flag = 1';

        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':number', $proxy['number'], PDO::PARAM_STR);
        $result->bindParam(':document_type_id', $proxy['document_type_id'], PDO::PARAM_INT);
        $result->bindParam(':date_issued', $proxy['date_issued'], PDO::PARAM_STR);
        $result->bindParam(':date_expired', $proxy['date_expired'], PDO::PARAM_STR);
        $result->bindParam(':authority_issued', $proxy['authority_issued'], PDO::PARAM_STR);
        $result->bindParam(':changed_datetime', $proxy['changed_datetime'], PDO::PARAM_STR);
        $result->bindParam(':changed_user_id', $proxy['changed_user_id'], PDO::PARAM_INT);

        return $result->execute();
    }

    /*
     * Удаляет доверенность
     * @var $id int - id доверенность
     * @var $proxy array() - информация о доверенности
     */
    public static function deleteProxy($id, $proxy)
    {
        $sql = 'UPDATE proxy
          SET changed_datetime = :changed_datetime, changed_user_id = :changed_user_id, flag = -1
          WHERE id = :id AND flag = 1';

        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':changed_datetime', $proxy['changed_datetime'], PDO::PARAM_STR);
        $result->bindParam(':changed_user_id', $proxy['changed_user_id'], PDO::PARAM_INT);

        return $result->execute();
    }

    /*
     * Получить доверенности
     * @var $proxy_person_id int - ID доверенного лица
     * @var $search_date_issued string - искомая дата выдачи
     * return array()
     */
    public static function getProxyList($proxy_person_id, $search_date_issued)
    {
        $where = '';

        if ($search_date_issued != null)
        {
            $where .= ' AND proxy.date_issued = ?';
        }

        $sql = 'SELECT
          proxy.id,
          proxy.number,
          proxy.document_type_id,
          proxy.date_issued,
          proxy.date_expired,
          proxy.authority_issued,
          proxy.created_datetime,
          proxy.created_user_id,
          proxy.changed_datetime,
          proxy.changed_user_id,
          proxy.flag
        FROM
          proxy_or_proxy_person
          INNER JOIN proxy ON (proxy_or_proxy_person.proxy_id = proxy.id)
        WHERE
          proxy_or_proxy_person.proxy_person_id = ? AND
          proxy.flag > 0 '.$where .' ORDER BY proxy.date_issued DESC ';

        $db = Database::getConnection();
        $result = $db->prepare($sql);

        if ($search_date_issued == null)
        {
            $result->execute([$proxy_person_id]);
        }
        if ($search_date_issued != null)
        {
            $result->execute([$proxy_person_id, $search_date_issued]);
        }

        // Получение и возврат результатов
        $proxy_list = null;
        $i = 0;
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $proxy_list[$i] = $row;
            $i++;
        }
        return $proxy_list;
    }

    /*
     * Добавить доверенность
     * @var $proxy array() - информация о доверенности
     * return int OR boolean
     */
    public static function addProxy($proxy)
    {
        $sql = 'INSERT INTO `proxy` (`number`, `document_type_id`, `date_issued`, `date_expired`, `authority_issued`,
        `created_datetime`, `created_user_id`, `flag`)
        VALUES (:number, :document_type_id, :date_issued, :date_expired, :authority_issued,
        :created_datetime, :created_user_id, 1)';

        $db = Database::getConnection();
        $result = $db->prepare($sql);

        $result->bindParam(':number', $proxy['number'], PDO::PARAM_STR);
        $result->bindParam(':document_type_id', $proxy['document_type_id'], PDO::PARAM_INT);
        $result->bindParam(':date_issued', $proxy['date_issued'], PDO::PARAM_STR);
        $result->bindParam(':date_expired', $proxy['date_expired'], PDO::PARAM_STR);
        $result->bindParam(':authority_issued', $proxy['authority_issued'], PDO::PARAM_STR);
        $result->bindParam(':created_datetime', $proxy['created_datetime'], PDO::PARAM_STR);
        $result->bindParam(':created_user_id', $proxy['created_user_id'], PDO::PARAM_INT);

        if($result->execute())
        {
            return $db->lastInsertId();
        }
        return false;
    }

    /*
     * Добавить доверенное лицо
     * @var $proxy_person array() - информация о доверенном лице
     * return int OR boolean
     */
    public static function addProxyPerson($proxy_person)
    {
        $sql = 'INSERT INTO proxy_person (lastname, firstname, middlename, document_type_id, document_series,
          document_number, date_issued, place_name, place_code, phone_number,
          created_datetime, created_user_id, flag)
          VALUES (:lastname, :firstname, :middlename, :document_type_id, :document_series,
          :document_number, :date_issued, :place_name, :place_code, :phone_number,
          :created_datetime, :created_user_id, 1)';

        $db = Database::getConnection();
        $result = $db->prepare($sql);

        $result->bindParam(':lastname', $proxy_person['lastname'], PDO::PARAM_STR);
        $result->bindParam(':firstname', $proxy_person['firstname'], PDO::PARAM_STR);
        $result->bindParam(':middlename', $proxy_person['middlename'], PDO::PARAM_STR);
        $result->bindParam(':document_type_id', $proxy_person['document_type_id'], PDO::PARAM_INT);
        $result->bindParam(':document_series', $proxy_person['document_series'], PDO::PARAM_STR);
        $result->bindParam(':document_number', $proxy_person['document_number'], PDO::PARAM_STR);
        $result->bindParam(':date_issued', $proxy_person['date_issued'], PDO::PARAM_STR);
        $result->bindParam(':place_name', $proxy_person['place_name'], PDO::PARAM_STR);
        $result->bindParam(':place_code', $proxy_person['place_code'], PDO::PARAM_STR);
        $result->bindParam(':phone_number', $proxy_person['phone_number'], PDO::PARAM_STR);
        $result->bindParam(':created_datetime', $proxy_person['created_datetime'], PDO::PARAM_STR);
        $result->bindParam(':created_user_id', $proxy_person['created_user_id'], PDO::PARAM_INT);

        if($result->execute())
        {
            return $db->lastInsertId();
        }
        return false;
    }

    /*
     * Добавить доверенность доаверенному лицу
     * @var $proxy_or_proxy_person array() - информация о доверенности
     */
    public static function addProxyOrProxyPerson($proxy_or_proxy_person)
    {
        $sql = 'INSERT INTO proxy_or_proxy_person (proxy_id, proxy_person_id, created_datetime, created_user_id)
        VALUES (:proxy_id, :proxy_person_id, :created_datetime, :created_user_id)';


        $db = Database::getConnection();
        $result = $db->prepare($sql);

        $result->bindParam(':proxy_id', $proxy_or_proxy_person['proxy_id'], PDO::PARAM_INT);
        $result->bindParam(':proxy_person_id', $proxy_or_proxy_person['proxy_person_id'], PDO::PARAM_INT);
        $result->bindParam(':created_datetime', $proxy_or_proxy_person['created_datetime'], PDO::PARAM_STR);
        $result->bindParam(':created_user_id', $proxy_or_proxy_person['created_user_id'], PDO::PARAM_INT);

        $result->execute();

    }

    /*
     * Запомниаем доверенное лицо в сессию
     * @var $proxy_person int - ID доверренного лица
     */
    public static function memorizeProxyPerson($proxy_person)
    {
        session_start();
        $_SESSION['proxy_person'] = $proxy_person;
    }

    /*
     * Запомниаем доверенность лицо в сессию
     * @var $proxy int - ID доверенности
     */
    public static function memorizeProxy($proxy)
    {
        session_start();
        $_SESSION['proxy'] = $proxy;
    }

    /*
     * Проверка выбрано ли доверенное лицо
     * return int OR null
     */
    public static function checkProxyPerson()
    {
        session_start();
        if(isset($_SESSION['proxy_person']))
        {
            return $_SESSION['proxy_person'];
        }
        return null;
    }

    /*
     * Проверка выбрана ли доверенность
     * return int OR null
     */
    public static function checkProxy()
    {
        session_start();
        if(isset($_SESSION['proxy']))
        {
            return $_SESSION['proxy'];
        }
        return null;
    }

    /*
     * Удаляем доверенное лицо из сессии
     */
    public static function outProxyPerson()
    {
        session_start();
        if(isset($_SESSION['proxy_person']))
        {
            unset($_SESSION['proxy_person']);
        }
    }

    /*
     * Удаляем доверенность из сессии
     */
    public static function outProxy()
    {
        session_start();
        if(isset($_SESSION['proxy']))
        {
            unset($_SESSION['proxy']);
        }
    }

    /*
     * Установить proxy_flag
     * @var $flag int - флаг доверенности
     */
    public static function setProxyFlag($flag)
    {
        session_start();
        $_SESSION['proxy_flag'] = $flag;
    }

    /*
     * Проверка выбрана ли доверенность
     * return int OR null
     */
    public static function checkProxyFlag()
    {
        session_start();
        if(isset($_SESSION['proxy_flag']))
        {
            return $_SESSION['proxy_flag'];
        }
        return null;
    }
}