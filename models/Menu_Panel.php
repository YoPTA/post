<?php

// Панель меню в администраторской


class Menu_Panel
{
    /*
     * Получаем все кнопки из базы данных
     * return array()
     */
    public static function getMenuPanel()
    {
        $sql = 'SELECT
          *
          FROM menu_panel
          WHERE menu_panel.flag > 0
          ORDER BY menu_panel.menu_index';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->execute();

        $menu_panel = null;
        $i = 0;
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $menu_panel[$i] = $row;
            $i++;
        }
        return $menu_panel;
    }

}