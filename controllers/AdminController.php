<?php

class AdminController
{
    public function actionIndex()
    {
        $is_admin = false;
        $admin_rights = null;
        $admin_menu_panel = Menu_Panel::getMenuPanel();
        // Подключаем файл с проверками ролей пользователя
        require_once ROOT . '/config/role_ckeck.php';



        if($is_admin)
        {
            require_once ROOT . '/views/admin/index.php';
            return true;
        }
        else
        {
            header('Location: /site/error');
        }
    }
}