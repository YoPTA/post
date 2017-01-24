<?php


class Local_Place
{
    /*
     * Получаем все записи из таблицы local_place
     * return array()
     */
    public static function getLocalPlaces()
    {
        $sql = 'SELECT
            *
          FROM
            local_place
          WHERE
            local_place.flag >= 0 ';

        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->execute();
        $local_places = null;
        $i = 0;
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $local_places[$i] = $row;
            $i++;
        }
        return $local_places;
    }

    /*
     * Проверка локального места на существование
     * @var $name string - наименование локального места
     * return int OR boolean
     */
    public static function checkLocalPlace($name)
    {
        $sql = 'SELECT local_place.id FROM local_place WHERE local_place.name = :name';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':name', $name, PDO::PARAM_STR);
        $result->execute();

        // Обращаемся к записи
        $local_place = $result->fetch();

        if ($local_place) {
            return $local_place['id'];
        }
        return false;
    }


    /*
     * Добавить новое локальное место
     * @var $local_place array() - локальное место
     * return int OR boolean
     */
    public static function addLocalPlace($local_place)
    {
        $sql = 'INSERT INTO local_place (name, created_datetime, created_user_id, flag)
          VALUES (:name, :created_datetime, :created_user_id, 1)';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':name', $local_place['name'], PDO::PARAM_STR);
        $result->bindParam(':created_datetime', $local_place['created_datetime'], PDO::PARAM_STR);
        $result->bindParam(':created_user_id', $local_place['created_user_id'], PDO::PARAM_INT);
        if($result->execute())
        {
            return $db->lastInsertId();
        }
        return false;
    }


}