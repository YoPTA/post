<?php

class ProfileController
{
    public function actionIndex($id = null)
    {
        // Подключаем файл с проверками ролей пользователя
        require_once ROOT . '/config/role_ckeck.php';

        require_once ROOT . '/views/profile/index.php';
        return true;
    }
}