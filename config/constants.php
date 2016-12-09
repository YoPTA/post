<?php

// Пароль по умолчанию
define('USER_PASSWORD_DEFAULT', '!2qwerty');

// Роль администратора
define('USER_ROLE_CREATE', 1);

// Роль администратора
define('USER_ROLE_SPECIAL', 2);

// Роль по умолчанию
define('USER_ROLE_DEFAULT', 3);

// Откуда
define('FROM_COMPANY', 'from');

// Куда
define('TO_COMPANY', 'to');

// Адрес DNS/IP
define('SOURCE_SITE', 'http://10.10.10.100/site/?command=getlistdata&number=');

// Кодировка со строчной буквы
define('DEFAULT_ENCODING_LOWERCASE', 'utf8');

// Кодировка с прописной буквы
define('DEFAULT_ENCODING_UPPERCASE', 'UTF-8');

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