-- phpMyAdmin SQL Dump
-- version 4.4.15.5
-- http://www.phpmyadmin.net
--
-- Хост: 10.10.10.155:3306
-- Время создания: Фев 22 2017 г., 08:50
-- Версия сервера: 5.5.48
-- Версия PHP: 5.4.45

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `db_post`
--

-- --------------------------------------------------------

--
-- Структура таблицы `company`
--

CREATE TABLE IF NOT EXISTS `company` (
  `id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `full_name` varchar(512) NOT NULL,
  `key_field` varchar(64) NOT NULL,
  `is_mfc` int(1) NOT NULL DEFAULT '0' COMMENT 'Является ли организация мфц',
  `created_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Дата и время создания',
  `created_user_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Пользователь, создавший',
  `changed_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Дата и время изменения',
  `changed_user_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Пользователь, изменивший',
  `flag` int(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `company`
--

INSERT INTO `company` (`id`, `name`, `full_name`, `key_field`, `is_mfc`, `created_datetime`, `created_user_id`, `changed_datetime`, `changed_user_id`, `flag`) VALUES
(0, 'Нет', 'Нет', '0', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, 0),
(1, 'ГАУ "МФЦ"', 'ГАУ Пензенской области "Многофункциональный центр предоставления государственных и муниципальных услуг"', '5835080816', 1, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `company_address`
--

CREATE TABLE IF NOT EXISTS `company_address` (
  `id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL COMMENT 'Связь с таблицей company',
  `local_place_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Связь с таблицей local_place',
  `address_country` varchar(128) NOT NULL COMMENT 'Страна',
  `address_zip` varchar(12) NOT NULL COMMENT 'Почтовый индекс',
  `address_region` varchar(256) NOT NULL COMMENT 'Область',
  `address_area` varchar(256) NOT NULL COMMENT 'Район',
  `address_city` varchar(128) NOT NULL COMMENT 'Город',
  `address_town` varchar(128) NOT NULL COMMENT 'Поселок',
  `address_street` varchar(256) NOT NULL COMMENT 'Улица',
  `address_home` varchar(32) NOT NULL COMMENT 'Дом',
  `address_case` varchar(16) NOT NULL COMMENT 'Корпус',
  `address_build` varchar(16) NOT NULL COMMENT 'Строение',
  `address_apartment` varchar(16) NOT NULL COMMENT 'Квартира',
  `is_transit` int(1) NOT NULL DEFAULT '0' COMMENT 'Является ли компания транзитной точкой',
  `created_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Дата и время создания',
  `created_user_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Пользователь, создавший',
  `changed_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Дата и время изменения',
  `changed_user_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Пользователь, изменивший',
  `flag` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `company_address`
--

INSERT INTO `company_address` (`id`, `company_id`, `local_place_id`, `address_country`, `address_zip`, `address_region`, `address_area`, `address_city`, `address_town`, `address_street`, `address_home`, `address_case`, `address_build`, `address_apartment`, `is_transit`, `created_datetime`, `created_user_id`, `changed_datetime`, `changed_user_id`, `flag`) VALUES
(0, 0, 0, '0', '', '', '', '', '', '', '', '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, 0),
(1, 1, 1, 'Россия', '440039', 'Пензенская область', '', 'г. Пенза', '', 'ул. Шмидта', '4', '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, 2),
(2, 1, 1, 'Россия', '440039', 'Пензенская область', '', 'г. Пенза', '', 'ул. Шмидта', '4', '', '', '', 1, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `document_type`
--

CREATE TABLE IF NOT EXISTS `document_type` (
  `id` int(11) NOT NULL,
  `name` varchar(512) NOT NULL COMMENT 'Название документа',
  `is_series` int(1) NOT NULL COMMENT 'Серия',
  `is_number` int(1) NOT NULL COMMENT 'Номер',
  `is_date_issued` int(1) NOT NULL COMMENT 'Дата выдачи',
  `is_date_expired` int(1) NOT NULL COMMENT 'Дата истечения',
  `is_place_name` int(1) NOT NULL COMMENT 'Место выдачи',
  `is_place_code` int(1) NOT NULL COMMENT 'Код места',
  `mask_series` varchar(128) NOT NULL COMMENT 'Маска серии',
  `mask_number` varchar(128) NOT NULL COMMENT 'Маска номера',
  `mask_place_name` varchar(128) NOT NULL COMMENT 'Маска места выдачи',
  `mask_place_code` varchar(128) NOT NULL COMMENT 'Маска кода места выдачи'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `document_type`
--

INSERT INTO `document_type` (`id`, `name`, `is_series`, `is_number`, `is_date_issued`, `is_date_expired`, `is_place_name`, `is_place_code`, `mask_series`, `mask_number`, `mask_place_name`, `mask_place_code`) VALUES
(0, 'Нет', 0, 0, 0, 0, 0, 0, '', '', '', ''),
(1, 'Паспорт', 1, 1, 1, 0, 1, 1, '', '', '', ''),
(2, 'Доверенность', 0, 1, 1, 1, 1, 0, '', '', '', '');

-- --------------------------------------------------------

--
-- Структура таблицы `local_place`
--

CREATE TABLE IF NOT EXISTS `local_place` (
  `id` int(11) NOT NULL,
  `name` varchar(646) NOT NULL,
  `created_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Дата и время создания',
  `created_user_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Пользователь, создавший',
  `changed_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Дата и время изменения',
  `changed_user_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Пользователь, изменивший',
  `flag` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `local_place`
--

INSERT INTO `local_place` (`id`, `name`, `created_datetime`, `created_user_id`, `changed_datetime`, `changed_user_id`, `flag`) VALUES
(0, 'Нет', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, 0),
(1, 'Россия|||Пензенская область|||г. Пенза', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `menu_panel`
--

CREATE TABLE IF NOT EXISTS `menu_panel` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL COMMENT 'Наименование кнопки',
  `title` varchar(128) NOT NULL COMMENT 'Заглавие кнопки',
  `description` varchar(256) NOT NULL COMMENT 'Описание кнопки',
  `url_address` varchar(128) NOT NULL COMMENT 'УРЛ адрес',
  `parent_menu_panel_id` int(11) NOT NULL COMMENT 'Родитель кнопки (Ссылка на эту же таблицу)',
  `menu_index` int(11) NOT NULL COMMENT 'Порядковый номер в панели меню',
  `member` int(11) NOT NULL COMMENT 'Член группы',
  `flag` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `menu_panel`
--

INSERT INTO `menu_panel` (`id`, `name`, `title`, `description`, `url_address`, `parent_menu_panel_id`, `menu_index`, `member`, `flag`) VALUES
(1, 'Пользователи', 'Пользователи', 'Страница предназначена для работы с пользователями системы', '/admin/user_index?fio_or_login=&page=1&office=1', 0, 1, 8, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `notification`
--

CREATE TABLE IF NOT EXISTS `notification` (
  `id` int(11) NOT NULL,
  `name` varchar(512) NOT NULL DEFAULT 'Для вас есть посылка' COMMENT 'Заголовок сообщения',
  `text_message` varchar(1024) NOT NULL DEFAULT 'Вам необходимо забрать посылку' COMMENT 'Короткий текст сообщения',
  `detail_text_message` varchar(4096) NOT NULL DEFAULT 'Заберите посылку' COMMENT 'Полный текст сообщения',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Связь с таблицей user',
  `created_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Дата и время создания',
  `created_user_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Пользователь, создавший',
  `changed_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Дата и время изменения',
  `changed_user_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Пользователь, изменивший',
  `flag` int(1) NOT NULL DEFAULT '1' COMMENT '0 - Нельзя изменить; 1 - Не прочитано; 2 - Прочитано; 3 - Удалено.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `notification`
--

INSERT INTO `notification` (`id`, `name`, `text_message`, `detail_text_message`, `user_id`, `created_datetime`, `created_user_id`, `changed_datetime`, `changed_user_id`, `flag`) VALUES
(0, 'Нет', 'Нет', 'Нет', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `package`
--

CREATE TABLE IF NOT EXISTS `package` (
  `id` int(11) NOT NULL,
  `number` varchar(128) NOT NULL,
  `note` varchar(512) NOT NULL COMMENT 'Примечание(ведомость)',
  `from_company_address_id` int(11) NOT NULL,
  `to_company_address_id` int(11) NOT NULL,
  `now_from_company_address_id` int(11) NOT NULL DEFAULT '0' COMMENT 'От какого адреса направляется посылка в данный момент',
  `now_to_company_address_id` int(11) NOT NULL DEFAULT '0' COMMENT 'В какой адрес направляется посылка в данный момент',
  `package_state` int(1) NOT NULL DEFAULT '0' COMMENT 'Состояние посылки: 0 - Не определено; 1 - Получено; 2 - Отправлено',
  `user_id` int(11) NOT NULL,
  `creation_datetime` datetime NOT NULL,
  `receipt_datetime` datetime NOT NULL,
  `flag` int(1) NOT NULL COMMENT '1 - Создано. 2 - Доставлено.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `package_object`
--

CREATE TABLE IF NOT EXISTS `package_object` (
  `id` int(11) NOT NULL,
  `name` varchar(512) NOT NULL,
  `flag` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `package_or_package_object`
--

CREATE TABLE IF NOT EXISTS `package_or_package_object` (
  `id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `package_object_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `proxy`
--

CREATE TABLE IF NOT EXISTS `proxy` (
  `id` int(11) NOT NULL,
  `number` varchar(32) NOT NULL DEFAULT '' COMMENT 'Номер',
  `document_type_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Тип документа',
  `date_issued` date NOT NULL DEFAULT '0000-00-00' COMMENT 'Дата выдачи',
  `date_expired` date NOT NULL DEFAULT '0000-00-00' COMMENT 'Дата истечения',
  `authority_issued` varchar(512) NOT NULL COMMENT 'Орган выдачи',
  `created_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Дата и время создания',
  `created_user_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Пользователь, создавший',
  `changed_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Дата и время изменения',
  `changed_user_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Пользователь, изменивший',
  `flag` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `proxy`
--

INSERT INTO `proxy` (`id`, `number`, `document_type_id`, `date_issued`, `date_expired`, `authority_issued`, `created_datetime`, `created_user_id`, `changed_datetime`, `changed_user_id`, `flag`) VALUES
(0, '0', 0, '0000-00-00', '0000-00-00', 'Нет', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `proxy_or_proxy_person`
--

CREATE TABLE IF NOT EXISTS `proxy_or_proxy_person` (
  `id` int(11) NOT NULL,
  `proxy_id` int(11) NOT NULL DEFAULT '0',
  `proxy_person_id` int(11) NOT NULL DEFAULT '0',
  `created_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Дата и время создания',
  `created_user_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Пользователь, создавший',
  `changed_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Дата и время изменения',
  `changed_user_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Пользователь, изменивший'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `proxy_or_proxy_person`
--

INSERT INTO `proxy_or_proxy_person` (`id`, `proxy_id`, `proxy_person_id`, `created_datetime`, `created_user_id`, `changed_datetime`, `changed_user_id`) VALUES
(0, 0, 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `proxy_person`
--

CREATE TABLE IF NOT EXISTS `proxy_person` (
  `id` int(11) NOT NULL,
  `lastname` varchar(128) NOT NULL,
  `firstname` varchar(64) NOT NULL,
  `middlename` varchar(128) NOT NULL,
  `document_type_id` int(11) NOT NULL COMMENT 'Тип документа',
  `document_series` varchar(32) NOT NULL COMMENT 'Серия документа',
  `document_number` varchar(32) NOT NULL COMMENT 'Номер документа',
  `date_issued` date NOT NULL COMMENT 'Дата выдачи',
  `date_expired` date NOT NULL COMMENT 'Дата истечения',
  `place_name` varchar(256) NOT NULL COMMENT 'Место выдачи',
  `place_code` varchar(16) NOT NULL COMMENT 'Код выдачи',
  `phone_number` varchar(128) NOT NULL COMMENT 'Номер телефона',
  `created_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Дата и время создания',
  `created_user_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Пользователь, создавший',
  `changed_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Дата и время изменения',
  `changed_user_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Пользователь, изменивший',
  `flag` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `proxy_person`
--

INSERT INTO `proxy_person` (`id`, `lastname`, `firstname`, `middlename`, `document_type_id`, `document_series`, `document_number`, `date_issued`, `date_expired`, `place_name`, `place_code`, `phone_number`, `created_datetime`, `created_user_id`, `changed_datetime`, `changed_user_id`, `flag`) VALUES
(0, 'Нет', '', '', 1, '0000', '000000', '0000-00-00', '0000-00-00', 'Нет', '000-000', '', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `route`
--

CREATE TABLE IF NOT EXISTS `route` (
  `id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `company_address_id` int(11) NOT NULL,
  `is_receive` int(1) NOT NULL DEFAULT '0' COMMENT 'Получено',
  `is_send` int(1) NOT NULL DEFAULT '0' COMMENT 'Отправлено',
  `receive_proxy_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Доверенность получатель',
  `receive_proxy_person_id` int(11) NOT NULL COMMENT 'Доверенное лицо получатель',
  `receive_user_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Пользователь получатель',
  `send_user_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Пользователь отправитель',
  `send_proxy_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Доверенность отправитель',
  `send_proxy_person_id` int(11) NOT NULL COMMENT 'Доверенное лицо отправитель',
  `datetime_receive` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Дата и время получения',
  `datetime_send` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Дата и время отправления',
  `relation_package_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Отношение к посылке'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL,
  `lastname` varchar(128) NOT NULL,
  `firstname` varchar(64) NOT NULL,
  `middlename` varchar(128) NOT NULL,
  `login` varchar(64) NOT NULL,
  `password` varchar(256) NOT NULL,
  `company_address_id` int(11) NOT NULL,
  `workpost` varchar(128) NOT NULL DEFAULT 'Специалист' COMMENT 'Должность',
  `role_id` int(11) NOT NULL DEFAULT '0',
  `group_id` int(11) NOT NULL DEFAULT '0' COMMENT 'ID группы',
  `created_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Дата и время создания',
  `created_user_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Пользователь, создавший',
  `changed_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Дата и время изменения',
  `changed_user_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Пользователь, изменивший',
  `flag` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `lastname`, `firstname`, `middlename`, `login`, `password`, `company_address_id`, `workpost`, `role_id`, `group_id`, `created_datetime`, `created_user_id`, `changed_datetime`, `changed_user_id`, `flag`) VALUES
(0, 'Нет', '', '', 'Нет', '3f7faf4ebca01338fb295fa4374d48aa', 0, '', 0, 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, 0),
(1, 'Романов', 'Сергей', 'Сергеевич', 'romanovss', 'd83ddc93ad840a68d5cff02d5773a07c', 1, 'Супер админ', 2, 1, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, 2),
(2, 'Поцалов', 'Сергей', 'Алексеевич', 'pocalovsa', 'd83ddc93ad840a68d5cff02d5773a07c', 1, 'Супер админ', 2, 1, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `user_group`
--

CREATE TABLE IF NOT EXISTS `user_group` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `description` varchar(512) NOT NULL,
  `member` int(11) NOT NULL COMMENT 'член групп',
  `flag` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `user_group`
--

INSERT INTO `user_group` (`id`, `name`, `description`, `member`, `flag`) VALUES
(0, 'Нет', 'Нет', 0, 0),
(1, 'Работа с пользователями', 'CAN_CREATE, CAN_DELETE_ALL, CANE_EDIT_ALL', 15, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `user_role`
--

CREATE TABLE IF NOT EXISTS `user_role` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `is_create` int(1) NOT NULL DEFAULT '0' COMMENT 'Может ли создавать',
  `is_change_proxy` int(1) NOT NULL DEFAULT '0' COMMENT 'Может ли изменять доверенности или доверенные лица',
  `is_change_company` int(1) NOT NULL DEFAULT '0' COMMENT 'Может ли изменять организацию и адрес организации',
  `is_receive` int(1) NOT NULL DEFAULT '0' COMMENT 'Может ли получать посылки',
  `is_send` int(1) NOT NULL DEFAULT '0' COMMENT 'Может ли отправлять посылки',
  `is_notification` int(1) NOT NULL DEFAULT '0' COMMENT 'Может ли получать уведомления',
  `is_admin` int(1) NOT NULL DEFAULT '0' COMMENT 'Обладает ли правами администратора',
  `flag` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `user_role`
--

INSERT INTO `user_role` (`id`, `name`, `is_create`, `is_change_proxy`, `is_change_company`, `is_receive`, `is_send`, `is_notification`, `is_admin`, `flag`) VALUES
(0, 'Нет', 0, 0, 0, 0, 0, 0, 0, 0),
(1, 'Специалист', 1, 1, 1, 1, 1, 1, 0, 0),
(2, 'Администратор', 1, 1, 1, 1, 1, 1, 1, 0);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key_field` (`key_field`);

--
-- Индексы таблицы `company_address`
--
ALTER TABLE `company_address`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `document_type`
--
ALTER TABLE `document_type`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `local_place`
--
ALTER TABLE `local_place`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `menu_panel`
--
ALTER TABLE `menu_panel`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `package`
--
ALTER TABLE `package`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `number` (`number`);

--
-- Индексы таблицы `package_object`
--
ALTER TABLE `package_object`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `package_or_package_object`
--
ALTER TABLE `package_or_package_object`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `proxy`
--
ALTER TABLE `proxy`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `proxy_or_proxy_person`
--
ALTER TABLE `proxy_or_proxy_person`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `proxy_person`
--
ALTER TABLE `proxy_person`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `route`
--
ALTER TABLE `route`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`);

--
-- Индексы таблицы `user_group`
--
ALTER TABLE `user_group`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `company`
--
ALTER TABLE `company`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `company_address`
--
ALTER TABLE `company_address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `document_type`
--
ALTER TABLE `document_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `local_place`
--
ALTER TABLE `local_place`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `menu_panel`
--
ALTER TABLE `menu_panel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `package`
--
ALTER TABLE `package`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `package_object`
--
ALTER TABLE `package_object`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `package_or_package_object`
--
ALTER TABLE `package_or_package_object`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `proxy`
--
ALTER TABLE `proxy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `proxy_or_proxy_person`
--
ALTER TABLE `proxy_or_proxy_person`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `proxy_person`
--
ALTER TABLE `proxy_person`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `route`
--
ALTER TABLE `route`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `user_group`
--
ALTER TABLE `user_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `user_role`
--
ALTER TABLE `user_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
