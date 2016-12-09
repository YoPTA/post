<?php
// Проверяем авторизовался ли пользователь
$user_id = User::checkLogged();
// Получаем информацию о пользователе
$user = User::getUser($user_id);
// Передаем информацию о роли пользователя
$user_role = new User_Role($user['role_id']);

// Права админа
$is_admin = User_Role::checkAdmin();

// Права создания
$is_create = User_Role::checkCreate();

// Права редактирования доверенностей и доверенных лиц
$is_change_proxy = User_Role::checkChangeProxy();

// Права отправления
$is_send = User_Role::checkSend();

// Права Получения
$is_receive = User_Role::checkReceive();