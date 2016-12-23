<?php

class AdminUserController
{
    public function actionIndex()
    {
        $is_admin = false;
        $admin_rights = null;
        $admin_menu_panel = Menu_Panel::getMenuPanel();
        // Подключаем файл с проверками ролей пользователя
        require_once ROOT . '/config/role_ckeck.php';




        if($is_admin && $admin_rights['can_change_user'])
        {
            require_once ROOT . '/views/admin/user/index.php';
            return true;
        }
        else
        {
            header('Location: /site/error');
        }
    }
}