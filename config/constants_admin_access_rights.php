<?php
/*
 * Права администраторов (ИСКЛЮЧИТЕЛЬНО).
 * Если здесь появятся и другие права, то отметку (ИСКЛЮЧИТЕЛЬНО) следует убрать и
 * данный комментарий переписать.
 */

// Может создавать
define('CAN_CREATE', 1 << 0); // 0000 0001              1

// Может редактировать
define('CAN_EDIT', 1 << 1); // 0000 0010                2

// Может удалять
define('CAN_DELETE', 1 << 2); // 0000 0100              4

// Может изменять пользователей
define('CAN_CHANGE_USER', 1 << 3); // 0000 1000         8

// Может изменять фигню
define('CAN_CHANGE_STUFF', 1 << 4); // 0001 0000               16






/*

define('CAN_EDIT_ALL', 1 << 5); // 0010 0000            32

*/