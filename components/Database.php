<?php

/**
 * Компонент для работы с базой данных
 */
class Database
{

    /**
     * Устанавливает соединение с базой данных
     * @return \PDO <p>Объект класса PDO для работы с БД</p>
     */
    public static function getConnection()
    {
        // Получаем параметры подключения из файла
        $paramsPath = ROOT . '/config/db.php';
        $params = include($paramsPath);

        // Устанавливаем соединение
        $dsn = "mysql:host={$params['host']};port={$params['port']};dbname={$params['database']}";
        $db = new PDO($dsn, $params['user'], $params['password']);

        // Задаем кодировку
        $db->exec("set names ". DEFAULT_ENCODING_LOWERCASE);

        return $db;
    }

}
