<?php
// Проверяем авторизовался ли пользователь
$user_id = User::checkLogged();
// Получаем информацию о пользователе
$user = User::getUser($user_id);

$is_admin = User_Role::checkAdmin($user['role_id']);
$is_create = User_Role::checkCreate($user['role_id']);
$is_change_proxy = User_Role::checkChangeProxy($user['role_id']);