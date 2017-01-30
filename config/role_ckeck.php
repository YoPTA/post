<?php
// Проверяем авторизовался ли пользователь
$user_id = User::checkLogged();
User::checkSessionTime();
// Получаем информацию о пользователе
$user = User::getUser($user_id);
// Получаем информацию о роли пользователя
$user_role = new User_Role($user['role_id']);
// Получаем информацию о группе пользователя
$user_group = new User_Group($user['group_id']);


// Права админа
$is_admin = User_Role::checkAdmin();

// Права создания
$is_create = User_Role::checkCreate();

// Права редактирования доверенностей и доверенных лиц
$is_change_proxy = User_Role::checkChangeProxy();

// Права редактирования организаций и адреса организации
$is_change_company = User_Role::checkChangeCompany();

// Права отправления
$is_send = User_Role::checkSend();

// Права Получения
$is_receive = User_Role::checkReceive();

// Права получать уведомления
$is_notification = User_Role::checkNotification();


// Проверка прав администратора

$admin_rights = null;
// Права создания
$admin_rights['can_create'] = $user_group::isCanCreate();
$admin_rights['can_edit'] = $user_group::isCanEdit();
$admin_rights['can_delete'] = $user_group::isCanDelete();
$admin_rights['can_change_user'] = $user_group::isCanChangeUser();
$admin_rights['can_change_stuff'] = $user_group::isCanChangeStuff();
