<?php

// Пароль по умолчанию
define('USER_PASSWORD_DEFAULT', '!2qwerty');

// Роль администратора
define('USER_ROLE_CREATE', 1);

// Роль администратора
define('USER_ROLE_SPECIAL', 2);

// Флаг пользователя по умолчанию из ДОКИ
define('USER_FLAG_DOKA_DEFAULT', 3);

// Откуда
define('FROM_COMPANY', 'from');

// Куда
define('TO_COMPANY', 'to');

// Адрес DNS/IP
define('SOURCE_SITE', 'http://10.10.10.100/site/?command=getlistdata&number='); // В боевом режиме использовать адрес - 10.8.0.122

// Кодировка со строчной буквы
define('DEFAULT_ENCODING_LOWERCASE', 'utf8');

// Кодировка с прописной буквы
define('DEFAULT_ENCODING_UPPERCASE', 'UTF-8');

// Размеры и местоположение окна, при открытии его в новой вкладке
define('DEFAULT_WINDOW', 'width=1100,height=800,top=50,left=50');

// Доверенность по умолчанию
define('PROXY_DEFAULT', 0);

// Доверенное лицо по умолчанию
define('PROXY_PERSON_DEFAULT', 0);

// Тип посылки
define('PACKAGE_ALL', 0); // Все
define('PACKAGE_INPUT', 1); // Входящие
define('PACKAGE_OUTPUT', 2); // Исходящие

// Офисы
define('OFFICE_ALL', 0); // Все
define('OFFICE_NOW', 1); // Текущий

// Типы документов
define('DOCUMENT_TYPE_PASSPORT', 1); // Паспорт
define('DOCUMENT_TYPE_PROXY', 2); // Доверенность

// Дата и время по умолчанию
define('DEFAULT_DATETIME', '0000-00-00 00:00:00');

// Ссылки откуда пришел пользователь
define('USER_REFERENCE_SEND', 1);
define('USER_REFERENCE_RECEIVE', 2);

define('USER_BARCODE', 'barcode');

// Способы поиска посылки
define('SEARCH_TYPE_COMMON', 1); // Общий
define('SEARCH_TYPE_SPECIAL', 2); // Частный

// Поиск по адресу относительно
define('SEARCH_RELATIVELY_FROM_OR_TO', 1); // Отправителя/Получателя
define('SEARCH_RELATIVELY_CURRENT', 2); // Текущего местоположения

// Поиск по месту
define('SEARCH_PLACE_ADDRESS', 1); //
define('SEARCH_PLACE_LOCAL', 2);

// Флаг активности
define('ACTIVE_FLAG_ACTIVE', 1); // Активные
define('ACTIVE_FLAG_ARCHIVE', 2); // В архиве

// Состояние посылки
define('PACKAGE_STATE_ALL', 0); // Все
define('PACKAGE_STATE_RECEIVE', 1); // Получено
define('PACKAGE_STATE_SEND', 2); // Отправлено
