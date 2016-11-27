<?php

class AdminController
{
    public function actionIndex($id = null)
    {
        $is_admin = false;

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