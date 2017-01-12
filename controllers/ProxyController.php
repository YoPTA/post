<?php

class ProxyController
{

    /*****************************
     * Работа с доверенными лицами НАЧАЛО
     *****************************/

    public function actionPersonIndex()
    {
        $user = null;
        $is_create = false; // Может ли создавать
        $is_change_proxy = false; // Может ли изменять доверенности и доверенные лица
        // Подключаем файл с проверками ролей пользователя
        require_once ROOT . '/config/role_ckeck.php';
        $string_utility = new String_Utility();

        $errors = false;
        $track = null;
        $site_page = 1;
        $page = 1;
        $date_create = null;
        $package_type = 0;
        $office = OFFICE_NOW;
        $pid = null; // Id посылки
        $rid = null; // Id маршрута
        $user_ref = null; // Откуда "пришел" пользователь
        $wow = 0; // С лицом или без
        $proxy_persons = null; // Доверенные лица
        $total_proxy_person = 0; // Общее кол-во доверенных лиц

        $search = null;

        if (isset($_GET['track']))
        {
            $track = htmlspecialchars($_GET['track']);
        }

        if (isset($_GET['site_page']))
        {
            $site_page = htmlspecialchars($_GET['site_page']);
        }
        if ($site_page < 1)
        {
            $site_page = 1;
        }

        if (isset($_GET['page']))
        {
            $page = htmlspecialchars($_GET['page']);
        }
        if ($page < 1)
        {
            $page = 1;
        }

        if (isset($_GET['date_create']))
        {
            $date_create = htmlspecialchars($_GET['date_create']);
        }

        if (isset($_GET['package_type']))
        {
            $package_type = htmlspecialchars($_GET['package_type']);
        }

        if ($package_type < 0)
        {
            $package_type = 0;
        }

        if (isset($_GET['office']))
        {
            $office = htmlspecialchars($_GET['office']);
        }

        if ($office < 0)
        {
            $office = 0;
        }

        if ($office == OFFICE_ALL)
        {
            $package_type = PACKAGE_ALL;
        }

        if (isset($_GET['pid']))
        {
            $pid = htmlspecialchars($_GET['pid']);
        }

        if (isset($_GET['rid']))
        {
            $rid = htmlspecialchars($_GET['rid']);
        }

        if (isset($_GET['user_ref']))
        {
            $user_ref = htmlspecialchars($_GET['user_ref']);
        }

        $page_name = 'site';
        if ($user_ref == USER_REFERENCE_SEND)
        {
            $page_name = 'send';
        }

        if ($user_ref == USER_REFERENCE_RECEIVE)
        {
            $page_name = 'receive';
        }

        if (isset($_GET['wow']))
        {
            $wow = htmlspecialchars($_GET['wow']);
        }

        if ($wow <= 0 || !is_numeric($wow) || $wow > 2)
        {
            $wow = 0;
        }


        if (isset($_GET['search']))
        {
            $search = htmlspecialchars($_GET['search']);
        }

        if (!empty($search))
        {
            $proxy_persons = Proxy::getProxyPersons($search);
            $total_proxy_person = count($proxy_persons);
        }


        if($is_create)
        {
            require_once ROOT . '/views/proxy/person/index.php';
            return true;
        }
        else
        {
            header('Location: /site/error');
        }
    }

    public function actionPersonAdd()
    {
        $user = null;
        $user_id = null;
        $is_create = false; // Может ли создавать
        // Подключаем файл с проверками ролей пользователя
        require_once ROOT . '/config/role_ckeck.php';
        $date_converter = new Date_Converter();

        // Подключаем файл с символами, которые необходимо заменить
        $charsToReplace = include (ROOT . '/components/charsToReplace.php');
        $validator = new Validate();

        $errors = false;

        $track = null;
        $site_page = 1;
        $page = 1;
        $date_create = null;
        $package_type = 0;
        $office = OFFICE_NOW;
        $pid = null; // Id посылки
        $rid = null; // Id маршрута
        $user_ref = null; // Откуда "пришел" пользователь
        $proxy_person = null; // Информация о доверенном лице
        $wow = 0; // С лицом или без
        $search = null; // Искомое значение


        if (isset($_GET['track']))
        {
            $track = htmlspecialchars($_GET['track']);
        }

        if (isset($_GET['site_page']))
        {
            $site_page = htmlspecialchars($_GET['site_page']);
        }
        if ($site_page < 1)
        {
            $site_page = 1;
        }

        if (isset($_GET['page']))
        {
            $page = htmlspecialchars($_GET['page']);
        }
        if ($page < 1)
        {
            $page = 1;
        }

        if (isset($_GET['date_create']))
        {
            $date_create = htmlspecialchars($_GET['date_create']);
        }

        if (isset($_GET['package_type']))
        {
            $package_type = htmlspecialchars($_GET['package_type']);
        }

        if ($package_type < 0)
        {
            $package_type = 0;
        }

        if (isset($_GET['office']))
        {
            $office = htmlspecialchars($_GET['office']);
        }

        if ($office < 0)
        {
            $office = 0;
        }

        if ($office == OFFICE_ALL)
        {
            $package_type = PACKAGE_ALL;
        }

        if (isset($_GET['pid']))
        {
            $pid = htmlspecialchars($_GET['pid']);
        }

        if (isset($_GET['rid']))
        {
            $rid = htmlspecialchars($_GET['rid']);
        }

        if (isset($_GET['user_ref']))
        {
            $user_ref = htmlspecialchars($_GET['user_ref']);
        }

        if (isset($_GET['wow']))
        {
            $wow = htmlspecialchars($_GET['wow']);
        }

        if ($wow <= 0 || !is_numeric($wow) || $wow > 2)
        {
            $wow = 0;
        }

        if (isset($_GET['search']))
        {
            $search = htmlspecialchars($_GET['search']);
        }

        if (isset($_POST['lastname']))
        {
            $proxy_person['lastname'] = htmlspecialchars(trim($validator->my_ucwords($_POST['lastname'])));

            $lnSegments = [];
            $lnSegments = explode("-", $proxy_person['lastname']);
            $toImplode = [];
            foreach($lnSegments as $segments)
            {
                $toImplode[] = trim($validator->my_ucwords($segments));
            }
            $proxy_person['lastname'] = implode("-", $toImplode);
            $proxy_person['lastname'] = str_ireplace($charsToReplace, "", $proxy_person['lastname']);
        }

        if (isset($_POST['firstname']))
        {
            $proxy_person['firstname'] = htmlspecialchars(trim($validator->my_ucwords($_POST['firstname'])));
        }

        if (isset($_POST['middlename']))
        {
            $proxy_person['middlename'] = htmlspecialchars(trim($validator->my_ucwords($_POST['middlename'])));
        }

        if (isset($_POST['document_number']))
        {
            $proxy_person['document_number'] = htmlspecialchars(trim($_POST['document_number']));
        }

        if (isset($_POST['document_series']))
        {
            $proxy_person['document_series'] = htmlspecialchars(trim($_POST['document_series']));
        }

        if (isset($_POST['date_issued']))
        {
            $proxy_person['date_issued'] = htmlspecialchars(trim($_POST['date_issued']));
        }

        if (isset($_POST['place_name']))
        {
            $proxy_person['place_name'] = htmlspecialchars(trim($_POST['place_name']));
        }

        if (isset($_POST['place_code']))
        {
            $proxy_person['place_code'] = htmlspecialchars(trim($_POST['place_code']));
        }

        if (isset($_POST['phone_number']))
        {
            $proxy_person['phone_number'] = htmlspecialchars(trim($_POST['phone_number']));
        }

        if (isset($_POST['add']))
        {
            if (!Validate::checkStr($proxy_person['lastname'], 128))
            {
                $errors['lastname'] = 'Фамилия не может быть такой длины';
            }

            if (!Validate::checkStr($proxy_person['firstname'], 64))
            {
                $errors['firstname'] = 'Имя не может быть такой длины';
            }

            if (!Validate::checkStrCanEmpty($proxy_person['middlename'], 128))
            {
                $errors['middlename'] = 'Отчество не может быть такой длины';
            }

            if (!Validate::checkNumber($proxy_person['document_series']) || $proxy_person['document_series'] == 0)
            {
                $errors['document_series'] = 'Серия паспорта должна быть четырехзначным числом формате ХХХХ';
            }
            if (!Validate::checkStrEqualLength($proxy_person['document_series'], 4))
            {
                $errors['document_series'] = 'Серия паспорта не может быть такой длины';
            }

            if (!Validate::checkNumber($proxy_person['document_number']) || $proxy_person['document_number'] == 0)
            {
                $errors['document_number'] = 'Номер паспорта должен быть шестизначным числом формате ХХХХХХ';
            }
            if (!Validate::checkStrEqualLength($proxy_person['document_number'], 6))
            {
                $errors['document_number'] = 'Номер паспорта не может быть такой длины';
            }

            if (!Validate::checkStr($proxy_person['date_issued'], 10))
            {
                $errors['date_issued'] = 'Дата выдачи не может быть такой длины. Формат ДД.ММ.ГГГГ';
            }

            if (!Validate::checkStr($proxy_person['place_name'], 256))
            {
                $errors['place_name'] = 'Место выдачи не может быть такой длины';
            }

            if (!Validate::checkNumber($proxy_person['place_code']) || $proxy_person['place_code'] == 0)
            {
                $errors['place_code'] = 'Код выдачи должен быть шестизначным числом формате ХХХХХХ';
            }
            if (!Validate::checkStrEqualLength($proxy_person['place_code'], 6))
            {
                $errors['place_code'] = 'Код выдачи не может быть такой длины';
            }

            if (!Validate::checkStr($proxy_person['phone_number'], 128))
            {
                $errors['phone_number'] = 'Номер телефона не может быть такой длины';
            }

            if ($errors == false)
            {
                $proxy_person['date_issued'] = $date_converter->stringToDate($proxy_person['date_issued']);
                $proxy_person['date_expired'] = '0000-00-00';
                $proxy_person['document_type_id'] = DOCUMENT_TYPE_PASSPORT;
                $proxy_person['created_datetime'] = date('Y-m-d H:i:s');
                $proxy_person['created_user_id'] = $user_id;
                Proxy::addProxyPerson($proxy_person);

                unset($proxy_person);

                header('Location: /proxy/person_index?track='.$track.'&site_page='.$site_page.'&date_create='.$date_create
                    .'&package_type='.$package_type.'&office='.$office.'&pid='.$pid.'&rid='.$rid.'&user_ref='.$user_ref
                    .'&wow='.$wow.'&search='.$search);
            }

        }

        if($is_create)
        {
            require_once ROOT . '/views/proxy/person/add.php';
            return true;
        }
        else
        {
            header('Location: /site/error');
        }
    }

    public function actionPersonView()
    {
        $user = null;
        $is_create = false; // Может ли создавать
        $is_change_proxy = false; // Может ли изменять доверенности и доверенные лица
        // Подключаем файл с проверками ролей пользователя
        require_once ROOT . '/config/role_ckeck.php';
        $date_converter = new Date_Converter();
        $validate = new Validate();

        $errors = false;

        $track = null;
        $site_page = 1;
        $page = 1;
        $date_create = null;
        $package_type = 0;
        $office = OFFICE_NOW;
        $pid = null; // Id посылки
        $rid = null; // Id маршрута
        $user_ref = null; // Откуда "пришел" пользователь
        $search = null; // Искомое значение
        $wow = 0; // С лицом или без
        $p_pid = null; // Доверенное лицо
        $search_date_issued = null; // Искомая дата выдачи
        $p_id = null; // Доверенность


        if (isset($_GET['track']))
        {
            $track = htmlspecialchars($_GET['track']);
        }

        if (isset($_GET['site_page']))
        {
            $site_page = htmlspecialchars($_GET['site_page']);
        }
        if ($site_page < 1)
        {
            $site_page = 1;
        }

        if (isset($_GET['page']))
        {
            $page = htmlspecialchars($_GET['page']);
        }
        if ($page < 1)
        {
            $page = 1;
        }

        if (isset($_GET['date_create']))
        {
            $date_create = htmlspecialchars($_GET['date_create']);
        }

        if (isset($_GET['package_type']))
        {
            $package_type = htmlspecialchars($_GET['package_type']);
        }

        if ($package_type < 0)
        {
            $package_type = 0;
        }

        if (isset($_GET['office']))
        {
            $office = htmlspecialchars($_GET['office']);
        }

        if ($office < 0)
        {
            $office = 0;
        }

        if ($office == OFFICE_ALL)
        {
            $package_type = PACKAGE_ALL;
        }

        if (isset($_GET['pid']))
        {
            $pid = htmlspecialchars($_GET['pid']);
        }

        if (isset($_GET['rid']))
        {
            $rid = htmlspecialchars($_GET['rid']);
        }

        if (isset($_GET['user_ref']))
        {
            $user_ref = htmlspecialchars($_GET['user_ref']);
        }

        if (isset($_GET['wow']))
        {
            $wow = htmlspecialchars($_GET['wow']);
        }

        if ($wow <= 0 || !is_numeric($wow) || $wow > 2)
        {
            $wow = 0;
        }

        if (isset($_GET['search']))
        {
            $search = htmlspecialchars($_GET['search']);
        }

        if (isset($_GET['p_pid']))
        {
            $p_pid = htmlspecialchars($_GET['p_pid']);
        }

        if (isset($_GET['search_date_issued']))
        {
            $search_date_issued = htmlspecialchars($_GET['search_date_issued']);
        }
        $search_date_issued_sql_format = $date_converter->stringToDate($search_date_issued);

        $proxy_person = Proxy::getProxyPersonInfo($p_pid);

        $var_is_date = $validate->checkDate($search_date_issued_sql_format, 'Y-m-d');

        if (!$var_is_date)
            $search_date_issued_sql_format = null;

        $proxy_list = Proxy::getProxyList($p_pid, $search_date_issued_sql_format);

        $total_proxy = count($proxy_list);

        if (isset($_POST['continue']))
        {
            $p_id = htmlspecialchars($_POST['continue']);
            Proxy::outProxy();
            Proxy::outProxyPerson();

            /*if (isset($_POST['selected_proxy']))
            {
                $p_id =  htmlspecialchars($_POST['selected_proxy']);
            }*/
            Proxy::memorizeProxy($p_id);
            Proxy::memorizeProxyPerson($p_pid);
            $page_name = null;
            if ($user_ref == USER_REFERENCE_SEND)
            {
                $page_name = 'send';
            }
            if ($user_ref == USER_REFERENCE_RECEIVE)
            {
                $page_name = 'receive';
            }

            header('Location: /route/'.$page_name.'?track='.$track.'&site_page='.$site_page.'&date_create='.$date_create.
                '&package_type='.$package_type.'&office='.$office.'&pid='.$pid.'&rid='.$rid.'&wow='.$wow);

        }

        unset($_POST);

        if($is_create)
        {
            require_once ROOT . '/views/proxy/person/view.php';
            return true;
        }
        else
        {
            header('Location: /site/error');
        }
    }

    public function actionPersonEdit()
    {
        $user = null;
        $user_id = null;
        $is_create = false; // Может ли создавать
        $is_change_proxy = false; // Может ли изменять доверенности или доверенные лица
        // Подключаем файл с проверками ролей пользователя
        require_once ROOT . '/config/role_ckeck.php';
        $date_converter = new Date_Converter();

        // Подключаем файл с символами, которые необходимо заменить
        $charsToReplace = include (ROOT . '/components/charsToReplace.php');
        $validator = new Validate();

        $errors = false;

        $track = null;
        $site_page = 1;
        $page = 1;
        $date_create = null;
        $package_type = 0;
        $office = OFFICE_NOW;
        $pid = null; // Id посылки
        $rid = null; // Id маршрута
        $user_ref = null; // Откуда "пришел" пользователь
        $wow = 0; // С лицом или без
        $proxy_person = null; // Информация о доверенном лице
        $search = null; // Искомое значение
        $p_pid = null; // Доверенное лицо

        if (isset($_GET['track']))
        {
            $track = htmlspecialchars($_GET['track']);
        }

        if (isset($_GET['site_page']))
        {
            $site_page = htmlspecialchars($_GET['site_page']);
        }
        if ($site_page < 1)
        {
            $site_page = 1;
        }

        if (isset($_GET['page']))
        {
            $page = htmlspecialchars($_GET['page']);
        }
        if ($page < 1)
        {
            $page = 1;
        }

        if (isset($_GET['date_create']))
        {
            $date_create = htmlspecialchars($_GET['date_create']);
        }

        if (isset($_GET['package_type']))
        {
            $package_type = htmlspecialchars($_GET['package_type']);
        }

        if ($package_type < 0)
        {
            $package_type = 0;
        }

        if (isset($_GET['office']))
        {
            $office = htmlspecialchars($_GET['office']);
        }

        if ($office < 0)
        {
            $office = 0;
        }

        if ($office == OFFICE_ALL)
        {
            $package_type = PACKAGE_ALL;
        }

        if (isset($_GET['pid']))
        {
            $pid = htmlspecialchars($_GET['pid']);
        }

        if (isset($_GET['rid']))
        {
            $rid = htmlspecialchars($_GET['rid']);
        }

        if (isset($_GET['user_ref']))
        {
            $user_ref = htmlspecialchars($_GET['user_ref']);
        }

        if (isset($_GET['wow']))
        {
            $wow = htmlspecialchars($_GET['wow']);
        }

        if ($wow <= 0 || !is_numeric($wow) || $wow > 2)
        {
            $wow = 0;
        }

        if (isset($_GET['search']))
        {
            $search = htmlspecialchars($_GET['search']);
        }

        if (isset($_GET['p_pid']))
        {
            $p_pid = htmlspecialchars($_GET['p_pid']);
        }

        $proxy_person = Proxy::getProxyPerson($p_pid);
        $proxy_person['date_issued'] = $date_converter->dateToString($proxy_person['date_issued']);

        if (isset($_POST['lastname']))
        {
            $proxy_person['lastname'] = htmlspecialchars(trim($validator->my_ucwords($_POST['lastname'])));

            $lnSegments = [];
            $lnSegments = explode("-", $proxy_person['lastname']);
            $toImplode = [];
            foreach($lnSegments as $segments)
            {
                $toImplode[] = trim($validator->my_ucwords($segments));
            }
            $proxy_person['lastname'] = implode("-", $toImplode);
            $proxy_person['lastname'] = str_ireplace($charsToReplace, "", $proxy_person['lastname']);
        }

        if (isset($_POST['firstname']))
        {
            $proxy_person['firstname'] = htmlspecialchars(trim($validator->my_ucwords($_POST['firstname'])));
        }

        if (isset($_POST['middlename']))
        {
            $proxy_person['middlename'] = htmlspecialchars(trim($validator->my_ucwords($_POST['middlename'])));
        }

        if (isset($_POST['document_number']))
        {
            $proxy_person['document_number'] = htmlspecialchars(trim($_POST['document_number']));
        }

        if (isset($_POST['document_series']))
        {
            $proxy_person['document_series'] = htmlspecialchars(trim($_POST['document_series']));
        }

        if (isset($_POST['date_issued']))
        {
            $proxy_person['date_issued'] = htmlspecialchars(trim($_POST['date_issued']));
        }

        if (isset($_POST['place_name']))
        {
            $proxy_person['place_name'] = htmlspecialchars(trim($_POST['place_name']));
        }

        if (isset($_POST['place_code']))
        {
            $proxy_person['place_code'] = htmlspecialchars(trim($_POST['place_code']));
        }

        if (isset($_POST['phone_number']))
        {
            $proxy_person['phone_number'] = htmlspecialchars(trim($_POST['phone_number']));
        }

        if (isset($_POST['edit']))
        {
            if (!Validate::checkStr($proxy_person['lastname'], 128))
            {
                $errors['lastname'] = 'Фамилия не может быть такой длины';
            }

            if (!Validate::checkStr($proxy_person['firstname'], 64))
            {
                $errors['firstname'] = 'Имя не может быть такой длины';
            }

            if (!Validate::checkStrCanEmpty($proxy_person['middlename'], 128))
            {
                $errors['middlename'] = 'Отчество не может быть такой длины';
            }

            if (!Validate::checkNumber($proxy_person['document_series']) || $proxy_person['document_series'] == 0)
            {
                $errors['document_series'] = 'Серия паспорта должна быть четырехзначным числом формате ХХХХ';
            }
            if (!Validate::checkStrEqualLength($proxy_person['document_series'], 4))
            {
                $errors['document_series'] = 'Серия паспорта не может быть такой длины';
            }

            if (!Validate::checkNumber($proxy_person['document_number']) || $proxy_person['document_number'] == 0)
            {
                $errors['document_number'] = 'Номер паспорта должен быть шестизначным числом формате ХХХХХХ';
            }
            if (!Validate::checkStrEqualLength($proxy_person['document_number'], 6))
            {
                $errors['document_number'] = 'Номер паспорта не может быть такой длины';
            }

            if (!Validate::checkStr($proxy_person['date_issued'], 10))
            {
                $errors['date_issued'] = 'Дата выдачи не может быть такой длины. Формат ДД.ММ.ГГГГ';
            }

            if (!Validate::checkStr($proxy_person['place_name'], 256))
            {
                $errors['place_name'] = 'Место выдачи не может быть такой длины';
            }

            if (!Validate::checkNumber($proxy_person['place_code']) || $proxy_person['place_code'] == 0)
            {
                $errors['place_code'] = 'Код выдачи должен быть шестизначным числом формате ХХХХХХ';
            }
            if (!Validate::checkStrEqualLength($proxy_person['place_code'], 6))
            {
                $errors['place_code'] = 'Код выдачи не может быть такой длины';
            }

            if (!Validate::checkStr($proxy_person['phone_number'], 128))
            {
                $errors['phone_number'] = 'Номер телефона не может быть такой длины';
            }

            if ($errors == false)
            {
                $proxy_person['date_issued'] = $date_converter->stringToDate($proxy_person['date_issued']);
                $proxy_person['date_expired'] = '0000-00-00';
                $proxy_person['document_type_id'] = DOCUMENT_TYPE_PASSPORT;
                $proxy_person['changed_datetime'] = date('Y-m-d H:i:s');
                $proxy_person['changed_user_id'] = $user_id;
                Proxy::updateProxyPerson($p_pid, $proxy_person);
                unset($proxy_person);

                header('Location: /proxy/person_index?track='.$track.'&site_page='.$site_page.'&date_create='.$date_create
                    .'&package_type='.$package_type.'&office='.$office.'&pid='.$pid.'&rid='.$rid.'&user_ref='.$user_ref
                    .'&wow='.$wow.'&search='.$search);
            }

        }

        if($is_change_proxy)
        {
            require_once ROOT . '/views/proxy/person/edit.php';
            return true;
        }
        else
        {
            header('Location: /site/error');
        }
    }

    public function actionPersonDelete()
    {
        $user = null;
        $user_id = null;
        $is_create = false; // Может ли создавать
        $is_change_proxy = false; // Может ли изменять доверенные лица и доверенности
        // Подключаем файл с проверками ролей пользователя
        require_once ROOT . '/config/role_ckeck.php';

        $track = null;
        $site_page = 1;
        $page = 1;
        $date_create = null;
        $package_type = 0;
        $office = OFFICE_NOW;
        $pid = null; // Id посылки
        $rid = null; // Id маршрута
        $user_ref = null; // Откуда "пришел" пользователь
        $wow = 0; // С лицом или без
        $proxy_person = null; // Информация о доверенном лице
        $search = null; // Искомое значение
        $p_pid = null; // Доверенное лицо

        if (isset($_GET['track']))
        {
            $track = htmlspecialchars($_GET['track']);
        }

        if (isset($_GET['site_page']))
        {
            $site_page = htmlspecialchars($_GET['site_page']);
        }
        if ($site_page < 1)
        {
            $site_page = 1;
        }

        if (isset($_GET['page']))
        {
            $page = htmlspecialchars($_GET['page']);
        }
        if ($page < 1)
        {
            $page = 1;
        }

        if (isset($_GET['date_create']))
        {
            $date_create = htmlspecialchars($_GET['date_create']);
        }

        if (isset($_GET['package_type']))
        {
            $package_type = htmlspecialchars($_GET['package_type']);
        }

        if ($package_type < 0)
        {
            $package_type = 0;
        }

        if (isset($_GET['office']))
        {
            $office = htmlspecialchars($_GET['office']);
        }

        if ($office < 0)
        {
            $office = 0;
        }

        if ($office == OFFICE_ALL)
        {
            $package_type = PACKAGE_ALL;
        }

        if (isset($_GET['pid']))
        {
            $pid = htmlspecialchars($_GET['pid']);
        }

        if (isset($_GET['rid']))
        {
            $rid = htmlspecialchars($_GET['rid']);
        }

        if (isset($_GET['user_ref']))
        {
            $user_ref = htmlspecialchars($_GET['user_ref']);
        }

        if (isset($_GET['wow']))
        {
            $wow = htmlspecialchars($_GET['wow']);
        }

        if ($wow <= 0 || !is_numeric($wow) || $wow > 2)
        {
            $wow = 0;
        }

        if (isset($_GET['search']))
        {
            $search = htmlspecialchars($_GET['search']);
        }

        if (isset($_GET['p_pid']))
        {
            $p_pid = htmlspecialchars($_GET['p_pid']);
        }

        $proxy_person = Proxy::getProxyPersonInfo($p_pid);

        if (isset($_POST['yes']))
        {
            $proxy_person['changed_datetime'] = date('Y-m-d H:i:s');
            $proxy_person['changed_user_id'] = $user_id;
            Proxy::deleteProxyPerson($p_pid, $proxy_person);

            header('Location: /proxy/person_index?track='.$track.'&site_page='.$site_page.'&date_create='.$date_create
                .'&package_type='.$package_type.'&office='.$office.'&pid='.$pid.'&rid='.$rid.'&user_ref='.$user_ref
                .'&wow='.$wow.'&search='.$search);
        }
        if (isset($_POST['no']))
        {
            header('Location: /proxy/person_index?track='.$track.'&site_page='.$site_page.'&date_create='.$date_create
                .'&package_type='.$package_type.'&office='.$office.'&pid='.$pid.'&rid='.$rid.'&user_ref='.$user_ref
                .'&wow='.$wow.'&search='.$search);
        }

        if($is_change_proxy)
        {
            require_once ROOT . '/views/proxy/person/delete.php';
            return true;
        }
        else
        {
            header('Location: /site/error');
        }
    }


    /*****************************
     * Работа с доверенными лицами КОНЕЦ
     *****************************/

    /*****************************
     * Работа с доверенностями НАЧАЛО
     *****************************/

    public function actionProxyAdd()
    {
        $user = null;
        $user_id = null;
        $is_create = false; // Может ли создавать
        $is_change_proxy = false; // Может ли изменять доверенности и доверенные лица
        // Подключаем файл с проверками ролей пользователя
        require_once ROOT . '/config/role_ckeck.php';
        $date_converter = new Date_Converter();

        $errors = false;

        $track = null;
        $site_page = 1;
        $page = 1;
        $date_create = null;
        $package_type = 0;
        $office = OFFICE_NOW;
        $pid = null; // Id посылки
        $rid = null; // Id маршрута
        $user_ref = null; // Откуда "пришел" пользователь
        $wow = 0; // С лицом или без
        $search = null; // Искомое значение
        $p_pid = null; // Доверенное лицо
        $search_date_issued = null; // Искомая дата выдачи


        if (isset($_GET['track']))
        {
            $track = htmlspecialchars($_GET['track']);
        }

        if (isset($_GET['site_page']))
        {
            $site_page = htmlspecialchars($_GET['site_page']);
        }
        if ($site_page < 1)
        {
            $site_page = 1;
        }

        if (isset($_GET['page']))
        {
            $page = htmlspecialchars($_GET['page']);
        }
        if ($page < 1)
        {
            $page = 1;
        }

        if (isset($_GET['date_create']))
        {
            $date_create = htmlspecialchars($_GET['date_create']);
        }

        if (isset($_GET['package_type']))
        {
            $package_type = htmlspecialchars($_GET['package_type']);
        }

        if ($package_type < 0)
        {
            $package_type = 0;
        }

        if (isset($_GET['office']))
        {
            $office = htmlspecialchars($_GET['office']);
        }

        if ($office < 0)
        {
            $office = 0;
        }

        if ($office == OFFICE_ALL)
        {
            $package_type = PACKAGE_ALL;
        }

        if (isset($_GET['pid']))
        {
            $pid = htmlspecialchars($_GET['pid']);
        }

        if (isset($_GET['rid']))
        {
            $rid = htmlspecialchars($_GET['rid']);
        }

        if (isset($_GET['user_ref']))
        {
            $user_ref = htmlspecialchars($_GET['user_ref']);
        }

        if (isset($_GET['wow']))
        {
            $wow = htmlspecialchars($_GET['wow']);
        }

        if ($wow <= 0 || !is_numeric($wow) || $wow > 2)
        {
            $wow = 0;
        }

        if (isset($_GET['search']))
        {
            $search = htmlspecialchars($_GET['search']);
        }

        if (isset($_GET['p_pid']))
        {
            $p_pid = htmlspecialchars($_GET['p_pid']);
        }

        $proxy = null;

        if (isset($_POST['number']))
        {
            $proxy['number'] = htmlspecialchars(trim($_POST['number']));
        }

        if (isset($_POST['date_issued']))
        {
            $proxy['date_issued'] = htmlspecialchars(trim($_POST['date_issued']));
        }

        if (isset($_POST['date_expired']))
        {
            $proxy['date_expired'] = htmlspecialchars(trim($_POST['date_expired']));
        }

        if (isset($_POST['authority_issued']))
        {
            $proxy['authority_issued'] = htmlspecialchars(trim($_POST['authority_issued']));
        }

        if (isset($_POST['add']))
        {
            // УТОЧНИТЬ ТИП ПОЛЯ В БД
            if (!Validate::checkStrCanEmpty($proxy['number'], 128))
            {
                $errors['number'] = 'Номер доверенности не может быть такой длины';
            }

            if (!Validate::checkStr($proxy['date_issued'], 10))
            {
                $errors['date_issued'] = 'Дата выдачи не может быть такой длины. Формат ДД.ММ.ГГГГ';
            }

            if (!Validate::checkStr($proxy['date_expired'], 10))
            {
                $errors['date_expired'] = 'Дата истечения не может быть такой длины. Формат ДД.ММ.ГГГГ';
            }

            if (!Validate::checkStr($proxy['authority_issued'], 512))
            {
                $errors['authority_issued'] = 'Орган выдачи не может быть такой длины';
            }

            if ($errors == false)
            {
                $proxy['date_issued'] = $date_converter->stringToDate($proxy['date_issued']);
                $proxy['date_expired'] = $date_converter->stringToDate($proxy['date_expired']);
                $proxy['document_type_id'] = DOCUMENT_TYPE_PROXY;
                $proxy['created_datetime'] = date('Y-m-d H:i:s');
                $proxy['created_user_id'] = $user_id;


                $proxy_or_proxy_person = null;

                $proxy_or_proxy_person['proxy_id'] = Proxy::addProxy($proxy);
                $proxy_or_proxy_person['proxy_person_id'] = $p_pid;
                $proxy_or_proxy_person['created_datetime'] = $proxy['created_datetime'];
                $proxy_or_proxy_person['created_user_id'] = $proxy['created_user_id'];
                Proxy::addProxyOrProxyPerson($proxy_or_proxy_person);

                unset($proxy);
                unset($proxy_or_proxy_person);
                header('Location: /proxy/person_view?track='.$track.'&site_page='.$site_page.'&date_create='.$date_create
                    .'&package_type='.$package_type.'&office='.$office.'&pid='.$pid.'&rid='.$rid.'&user_ref='.$user_ref
                    .'&wow='.$wow.'&search='.$search.'&p_pid='.$p_pid.'&search_date_issued='.$search_date_issued);
            }
        }

        if($is_create)
        {
            require_once ROOT . '/views/proxy/proxy/add.php';
            return true;
        }
        else
        {
            header('Location: /site/error');
        }
    }

    public function actionProxyEdit()
    {
        $user = null;
        $user_id = null;
        $is_create = false; // Может ли создавать
        $is_change_proxy = false; // Может ли изменять доверенности и доверенные лица
        // Подключаем файл с проверками ролей пользователя
        require_once ROOT . '/config/role_ckeck.php';
        $date_converter = new Date_Converter();

        $errors = false;

        $track = null;
        $site_page = 1;
        $page = 1;
        $date_create = null;
        $package_type = 0;
        $office = OFFICE_NOW;
        $pid = null; // Id посылки
        $rid = null; // Id маршрута
        $user_ref = null; // Откуда "пришел" пользователь
        $wow = 0; // С лицом или без
        $search = null; // Искомое значение
        $p_pid = null; // Доверенное лицо
        $p_id = null; // Доверенность
        $search_date_issued = null; // Искомая дата выдачи


        if (isset($_GET['track']))
        {
            $track = htmlspecialchars($_GET['track']);
        }

        if (isset($_GET['site_page']))
        {
            $site_page = htmlspecialchars($_GET['site_page']);
        }
        if ($site_page < 1)
        {
            $site_page = 1;
        }

        if (isset($_GET['page']))
        {
            $page = htmlspecialchars($_GET['page']);
        }
        if ($page < 1)
        {
            $page = 1;
        }

        if (isset($_GET['date_create']))
        {
            $date_create = htmlspecialchars($_GET['date_create']);
        }

        if (isset($_GET['package_type']))
        {
            $package_type = htmlspecialchars($_GET['package_type']);
        }

        if ($package_type < 0)
        {
            $package_type = 0;
        }

        if (isset($_GET['office']))
        {
            $office = htmlspecialchars($_GET['office']);
        }

        if ($office < 0)
        {
            $office = 0;
        }

        if ($office == OFFICE_ALL)
        {
            $package_type = PACKAGE_ALL;
        }

        if (isset($_GET['pid']))
        {
            $pid = htmlspecialchars($_GET['pid']);
        }

        if (isset($_GET['rid']))
        {
            $rid = htmlspecialchars($_GET['rid']);
        }

        if (isset($_GET['user_ref']))
        {
            $user_ref = htmlspecialchars($_GET['user_ref']);
        }

        if (isset($_GET['wow']))
        {
            $wow = htmlspecialchars($_GET['wow']);
        }

        if ($wow <= 0 || !is_numeric($wow) || $wow > 2)
        {
            $wow = 0;
        }

        if (isset($_GET['search']))
        {
            $search = htmlspecialchars($_GET['search']);
        }

        if (isset($_GET['p_pid']))
        {
            $p_pid = htmlspecialchars($_GET['p_pid']);
        }

        if (isset($_GET['search_date_issued']))
        {
            $search_date_issued = htmlspecialchars($_GET['search_date_issued']);
        }

        if (isset($_GET['p_id']))
        {
            $p_id = htmlspecialchars($_GET['p_id']);
        }

        $proxy = Proxy::getProxy($p_id);

        $proxy['date_issued'] = $date_converter->dateToString($proxy['date_issued']);
        $proxy['date_expired'] = $date_converter->dateToString($proxy['date_expired']);

        if (isset($_POST['number']))
        {
            $proxy['number'] = htmlspecialchars(trim($_POST['number']));
        }

        if (isset($_POST['date_issued']))
        {
            $proxy['date_issued'] = htmlspecialchars(trim($_POST['date_issued']));
        }

        if (isset($_POST['date_expired']))
        {
            $proxy['date_expired'] = htmlspecialchars(trim($_POST['date_expired']));
        }

        if (isset($_POST['authority_issued']))
        {
            $proxy['authority_issued'] = htmlspecialchars(trim($_POST['authority_issued']));
        }

        if (isset($_POST['edit']))
        {
            // УТОЧНИТЬ ТИП ПОЛЯ В БД
            if (!Validate::checkStrCanEmpty($proxy['number'], 128))
            {
                $errors['number'] = 'Номер доверенности не может быть такой длины';
            }

            if (!Validate::checkStr($proxy['date_issued'], 10))
            {
                $errors['date_issued'] = 'Дата выдачи не может быть такой длины. Формат ДД.ММ.ГГГГ';
            }

            if (!Validate::checkStr($proxy['date_expired'], 10))
            {
                $errors['date_expired'] = 'Дата истечения не может быть такой длины. Формат ДД.ММ.ГГГГ';
            }

            if (!Validate::checkStr($proxy['authority_issued'], 512))
            {
                $errors['authority_issued'] = 'Орган выдачи не может быть такой длины';
            }

            if ($errors == false)
            {
                $proxy['date_issued'] = $date_converter->stringToDate($proxy['date_issued']);
                $proxy['date_expired'] = $date_converter->stringToDate($proxy['date_expired']);
                $proxy['document_type_id'] = DOCUMENT_TYPE_PROXY;
                $proxy['changed_datetime'] = date('Y-m-d H:i:s');
                $proxy['changed_user_id'] = $user_id;

                Proxy::updateProxy($p_id, $proxy);

                unset($proxy);

                header('Location: /proxy/person_view?track='.$track.'&site_page='.$site_page.'&date_create='.$date_create
                    .'&package_type='.$package_type.'&office='.$office.'&pid='.$pid.'&rid='.$rid.'&user_ref='.$user_ref
                    .'&wow='.$wow.'&search='.$search.'&p_pid='.$p_pid.'&search_date_issued='.$search_date_issued);
            }
        }

        if($is_change_proxy)
        {
            require_once ROOT . '/views/proxy/proxy/edit.php';
            return true;
        }
        else
        {
            header('Location: /site/error');
        }
    }

    public function actionProxyDelete()
    {
        $user = null;
        $user_id = null;
        $is_create = false; // Может ли создавать
        $is_change_proxy = false; // Может ли изменять доверенности и доверенные лица
        // Подключаем файл с проверками ролей пользователя
        require_once ROOT . '/config/role_ckeck.php';
        $date_converter = new Date_Converter();

        $errors = false;

        $track = null;
        $site_page = 1;
        $page = 1;
        $date_create = null;
        $package_type = 0;
        $office = OFFICE_NOW;
        $pid = null; // Id посылки
        $rid = null; // Id маршрута
        $user_ref = null; // Откуда "пришел" пользователь
        $wow = 0; // С лицом или без
        $search = null; // Искомое значение
        $p_pid = null; // Доверенное лицо
        $p_id = null; // Доверенность
        $search_date_issued = null; // Искомая дата выдачи


        if (isset($_GET['track']))
        {
            $track = htmlspecialchars($_GET['track']);
        }

        if (isset($_GET['site_page']))
        {
            $site_page = htmlspecialchars($_GET['site_page']);
        }
        if ($site_page < 1)
        {
            $site_page = 1;
        }

        if (isset($_GET['page']))
        {
            $page = htmlspecialchars($_GET['page']);
        }
        if ($page < 1)
        {
            $page = 1;
        }

        if (isset($_GET['date_create']))
        {
            $date_create = htmlspecialchars($_GET['date_create']);
        }

        if (isset($_GET['package_type']))
        {
            $package_type = htmlspecialchars($_GET['package_type']);
        }

        if ($package_type < 0)
        {
            $package_type = 0;
        }

        if (isset($_GET['office']))
        {
            $office = htmlspecialchars($_GET['office']);
        }

        if ($office < 0)
        {
            $office = 0;
        }

        if ($office == OFFICE_ALL)
        {
            $package_type = PACKAGE_ALL;
        }

        if (isset($_GET['pid']))
        {
            $pid = htmlspecialchars($_GET['pid']);
        }

        if (isset($_GET['rid']))
        {
            $rid = htmlspecialchars($_GET['rid']);
        }

        if (isset($_GET['user_ref']))
        {
            $user_ref = htmlspecialchars($_GET['user_ref']);
        }

        if (isset($_GET['wow']))
        {
            $wow = htmlspecialchars($_GET['wow']);
        }

        if ($wow <= 0 || !is_numeric($wow) || $wow > 2)
        {
            $wow = 0;
        }

        if (isset($_GET['search']))
        {
            $search = htmlspecialchars($_GET['search']);
        }

        if (isset($_GET['p_pid']))
        {
            $p_pid = htmlspecialchars($_GET['p_pid']);
        }

        if (isset($_GET['search_date_issued']))
        {
            $search_date_issued = htmlspecialchars($_GET['search_date_issued']);
        }

        if (isset($_GET['p_id']))
        {
            $p_id = htmlspecialchars($_GET['p_id']);
        }

        $proxy = Proxy::getProxy($p_id);

        if (isset($_POST['yes']))
        {
            $proxy['changed_datetime'] = date('Y-m-d H:i:s');
            $proxy['changed_user_id'] = $user_id;
            Proxy::deleteProxy($p_id, $proxy);

            header('Location: /proxy/person_view?track='.$track.'&site_page='.$site_page.'&date_create='.$date_create
                .'&package_type='.$package_type.'&office='.$office.'&pid='.$pid.'&rid='.$rid.'&user_ref='.$user_ref
                .'&wow='.$wow.'&search='.$search.'&search_date_issued='.$search_date_issued.'&p_pid='.$p_pid);
        }

        if (isset($_POST['no']))
        {
            header('Location: /proxy/person_view?track='.$track.'&site_page='.$site_page.'&date_create='.$date_create
                .'&package_type='.$package_type.'&office='.$office.'&pid='.$pid.'&rid='.$rid.'&user_ref='.$user_ref
                .'&wow='.$wow.'&search='.$search.'&search_date_issued='.$search_date_issued.'&p_pid='.$p_pid);
        }

        if($is_change_proxy)
        {
            require_once ROOT . '/views/proxy/proxy/delete.php';
            return true;
        }
        else
        {
            header('Location: /site/error');
        }
    }

    /*****************************
     * Работа с доверенностями КОНЕЦ
     *****************************/
}