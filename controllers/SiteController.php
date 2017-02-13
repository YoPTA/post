<?php

class SiteController
{
    public function actionIndex()
    {
        $is_admin = false;
        $user = null;
        // Подключаем файл с проверками ролей пользователя
        require_once ROOT . '/config/role_ckeck.php';
        $string_utility = new String_Utility();

        $date_converter = new Date_Converter();
        $date_time = new DateTime();

        $errors = false;

        $companies = Company::getAllCompanies(1); // Организации и адреса организаций
        $only_companies = null; // Только организации
        $user_company = Company::getCompany($user['company_address_id']); // Организация пользователя
        $current = 0;

        $link_get_param = '';

        if (count($companies) > 0)
        {
            for ($i = 0; $i < count($companies); $i++)
            {
                if (count($only_companies) < 1)
                {
                    $only_companies[] = $companies[$i];
                }
                foreach ($only_companies as $oc)
                {
                    if ($oc['company_id'] == $companies[$i]['company_id'])
                    {
                        $current = 1;
                    }
                }
                if ($current == 0)
                {
                    $only_companies[] = $companies[$i];
                }
                $current = 0;
            }
        }

        $search = null; // Параметры поиска

        $search['search_type'] = SEARCH_TYPE_TRACK; // Параметр поиска
        $page = 1; // Номер страницы
        $search['track'] = null; // Трек-номер

        $search['package_type'] = PACKAGE_INPUT; // Тип посылки (Входящие/Исходящие)

        $search['active_flag'] = ACTIVE_FLAG_ACTIVE; // Состояние посылки
        $search['date_create_begin'] = $date_time->format('01.m.Y'); // Период поиска с
        $search['date_create_end'] = $date_time->format('t.m.Y'); // Период поиска по

        $search['d_begin'] = null; // Дата С для поиска в БД
        $search['d_end'] = null; // Дата По для поиска в БД


        $search['search_relatively'] = SEARCH_RELATIVELY_FROM_OR_TO; // Относительное местоположение
        $search['from_or_to'] = null; // От кого/Для кого
        $search['to_or_from'] = null; // Для кого/От кого


        if (isset($_GET['search_type']))
        {
            $search['search_type'] = htmlspecialchars($_GET['search_type']);
        }

        if (isset($_GET['page']))
        {
            $page = htmlspecialchars($_GET['page']);
        }
        if ($page < 1)
        {
            $page = 1;
        }

        if (isset($_GET['track']))
        {
            $search['track'] = htmlspecialchars(trim($_GET['track']));
        }

        if (isset($_GET['package_type']))
        {
            $search['package_type'] = htmlspecialchars($_GET['package_type']);
        }

        if (isset($_GET['active_flag']))
        {
            $search['active_flag'] = htmlspecialchars($_GET['active_flag']);
        }

        if (isset($_GET['search_relatively']))
        {
            $search['search_relatively'] = htmlspecialchars($_GET['search_relatively']);
        }

        if (isset($_GET['date_create_begin']))
        {
            $search['date_create_begin'] = htmlspecialchars(trim($_GET['date_create_begin']));
        }

        $search['d_begin'] = $date_converter->stringToDate($search['date_create_begin']);

        if (isset($_GET['date_create_end']))
        {
            $search['date_create_end'] = htmlspecialchars(trim($_GET['date_create_end']));
        }

        $search['d_end'] = $date_converter->stringToDate($search['date_create_end']);

        if (isset($_GET['from_or_to']))
        {
            $search['from_or_to'] = htmlspecialchars($_GET['from_or_to']);
        }

        if (!$is_admin)
        {
            $search['from_or_to'] = $user['company_address_id'];;
        }

        if (isset($_GET['to_or_from']))
        {
            $search['to_or_from'] = htmlspecialchars($_GET['to_or_from']);
        }

        if ($search['search_type'] == SEARCH_TYPE_TRACK)
        {
            $link_get_param .= 'search_type='.$search['search_type'].'&page='.$page.'&track='.$search['track'];
        }
        elseif ($search['search_type'] == SEARCH_TYPE_ADDRESS)
        {
            $link_get_param .= 'search_type='.$search['search_type'].'&page='.$page.'&package_type='. $search['package_type']
                .'&active_flag='. $search['active_flag'] .'&date_create_begin='. $search['date_create_begin']
                .'&date_create_end='. $search['date_create_end'] .'&search_relatively='. $search['search_relatively']
                .'&from_or_to='. $search['from_or_to'] .'&to_or_from='.$search['to_or_from'];
        }
        else
        {
            $link_get_param .= 'search_type='.SEARCH_TYPE_TRACK.'&page=1';
        }

        $packages = Package::getPackages($search, $page);
        $total_packages = Package::getTotalPackages($search);

        $index_number = ($page - 1) * Package::SHOW_BY_DEFAULT;
        $pagination = new Pagination($total_packages, $page, Package::SHOW_BY_DEFAULT, 'page=');

        require_once ROOT . '/views/site/index.php';
        return true;
    }

    public function actionError()
    {
        require_once ROOT . '/views/site/error.php';
        return true;
    }

    public function actionCreate()
    {
        $is_create = false;
        $user = null;
        $user_id = null;

        $string_utility = new String_Utility();

        // Подключаем файл с проверками ролей пользователя
        require_once ROOT . '/config/role_ckeck.php';

        $errors = null;
        if(isset($_POST['clear']))
        {
            Company::outCompanyFromMemory(FROM_COMPANY);
            Company::outCompanyFromMemory(TO_COMPANY);
            Package::outPackage();
            Package::outPackageObjects();
            header('Location: /site/choose');
        }
        if (isset($_POST['no']))
        {
            header('Location: /site/choose');
        }


        $package_list = Package::checkPackage(); // Номер ведомости
        $package_objects = Package::checkPackageObjects(); // Объекты посылки
        $package = null; // Посылка

        $route_without_send = null; // Id маршрута без отправки
        $receive_values = null; // Информация о получении

        $to_route_view = 0; // Перейти к просмотрю маршрута

        $c_from = null;
        $c_to = null;
        $delivery_type = null; // Способ доставки (Через транзит/Напрямую)

        $from_company_id = Company::checkCompanyInMemory(FROM_COMPANY); // Откуда
        $to_company_id = Company::checkCompanyInMemory(TO_COMPANY); // Кому

        // Получаем транзитные точки
        $transit_points = Company::getTransits($from_company_id, $to_company_id);

        $company_from = null;
        if($from_company_id != null)
        {
            $company_from = Company::getCompany($from_company_id);
            $c_from['ca_id'] = $company_from['ca_id'];
            $c_from['ca_company_id'] = $company_from['ca_company_id'];
            $c_from['c_name'] = $company_from['c_name'];
            $c_from['c_full_name'] = $company_from['c_full_name'];
            $c_from['c_key_field'] = $company_from['c_key_field'];
            $c_from['ca_country'] = $company_from['ca_country'];
            $c_from['ca_zip'] = $company_from['ca_zip'];
            $c_from['ca_region'] = $company_from['ca_region'];
            $c_from['ca_area'] = $company_from['ca_area'];
            $c_from['ca_city'] = $company_from['ca_city'];
            $c_from['ca_town'] = $company_from['ca_town'];
            $c_from['ca_street'] = $company_from['ca_street'];
            $c_from['ca_home'] = $company_from['ca_home'];
            $c_from['ca_case'] = $company_from['ca_case'];
            $c_from['ca_build'] = $company_from['ca_build'];
            $c_from['ca_apartment'] = $company_from['ca_apartment'];
        }

        $company_to = null;
        if($to_company_id != null)
        {
            $company_to = Company::getCompany($to_company_id);
            $c_to['ca_id'] = $company_to['ca_id'];
            $c_to['ca_company_id'] = $company_to['ca_company_id'];
            $c_to['c_name'] = $company_to['c_name'];
            $c_to['c_full_name'] = $company_to['c_full_name'];
            $c_to['c_key_field'] = $company_to['c_key_field'];
            $c_to['ca_country'] = $company_to['ca_country'];
            $c_to['ca_zip'] = $company_to['ca_address_zip'];
            $c_to['ca_region'] = $company_to['ca_region'];
            $c_to['ca_area'] = $company_to['ca_area'];
            $c_to['ca_city'] = $company_to['ca_city'];
            $c_to['ca_town'] = $company_to['ca_town'];
            $c_to['ca_street'] = $company_to['ca_street'];
            $c_to['ca_home'] = $company_to['ca_home'];
            $c_to['ca_case'] = $company_to['ca_case'];
            $c_to['ca_build'] = $company_to['ca_build'];
            $c_to['ca_apartment'] = $company_to['ca_apartment'];
        }

        if($to_company_id == $from_company_id && $to_company_id != null)
        {
            $errors[] = 'Откуда и Куда не должны совпадать!';
        }

        if(isset($_POST['create']))
        {
            if (isset($_POST['to_route_view']))
            {
                $to_route_view = htmlspecialchars($_POST['to_route_view']);
            }
            if (isset($_POST['delivery_type']))
            {
                $delivery_type = htmlspecialchars($_POST['delivery_type']);
            }
            else
            {
                $errors[] = "Не выбран способ доставки";
            }

            $from_company_id = Company::checkCompanyInMemory(FROM_COMPANY); // Откуда
            $to_company_id = Company::checkCompanyInMemory(TO_COMPANY); // Кому
            $package_list = Package::checkPackage(); // Посылка
            $package_objects = Package::checkPackageObjects(); // Объекты посылки

            if ($from_company_id == null)
            {
                $errors['from_company'] = 'Не выбран отправитель';
            }

            if ($to_company_id == null)
            {
                $errors['to_company'] = 'Не выбран получатель';
            }

            if ($package_list == null)
            {
                $errors['package_list'] = 'Посылка не найдена';
            }

            if ($package_objects == null)
            {
                $errors['package_objects'] = 'Объекты посылки не найдены';
            }

            if ($delivery_type != 0)
            {
                if (!Company::checkTransit($delivery_type))
                {
                    $errors['hacker'] = 'Да ты знаешь HTML. Это здорово!';
                }
            }

            if($errors == false)
            {
                $package['note'] = $package_list;
                $package['from_company_id'] = $from_company_id;
                $package['to_company_id'] = $to_company_id;
                $package['user_id'] = $user_id;

                //Добавляем посылку в БД
                $package_last_id = Package::addPackage($package);

                // Обновляем номер посылки
                Package::updatePackageNumber($package_last_id);
                $track = Package::getTrackNumber($package_last_id);

                foreach($package_objects as $p_o)
                {
                    $package_object_id = Package::addPackageObject($p_o);
                    Package::addPackageOrPackageObject($package_last_id, $package_object_id);
                }


                $del_points = null; // Точки маршрута
                if($delivery_type == 0)
                {
                    $del_points[0] = $from_company_id;
                    $del_points[1] = $to_company_id;
                }
                if($delivery_type > 0)
                {
                    $del_points[0] = $from_company_id;
                    $del_points[1] = $delivery_type;
                    $del_points[2] = $to_company_id;
                }

                // Добавляем маршруты
                Route::addRoutes($del_points, $package_last_id);
                $route_without_send = Route::getRouteWithoutSend($package_last_id);

                $receive_values['receive_proxy_id'] = PROXY_DEFAULT;
                $receive_values['receive_proxy_person_id'] = PROXY_PERSON_DEFAULT;
                $receive_values['receive_user_id'] = $user_id;

                $receive_stat = Route::receive($route_without_send, $receive_values);
                Package::setNowAddresses($package_last_id);

                if ($receive_stat)
                {
                    Notification::launchNotification($package_last_id);
                }

                Company::outCompanyFromMemory(FROM_COMPANY);
                Company::outCompanyFromMemory(TO_COMPANY);
                Package::outPackage();
                Package::outPackageObjects();
                if ($to_route_view == 1)
                {
                    header('Location: /route/view?search_type='. SEARCH_TYPE_TRACK .'&page=1&track='. $track.'&pid='.$package_last_id);
                }
                else
                {
                    header('Location: /site/index?search_type='. SEARCH_TYPE_TRACK .'&page=1&track='. $track);
                }
            }
        }


        if($is_create)
        {
            require_once ROOT . '/views/site/create.php';
            return true;
        }
        else
        {
            header('Location: /site/error');
        }

    }

    public function actionLogin()
    {
        $login_name = null;
        $password = null;

        $errors = false;

        $dir_path = '/temp/users';
        $clean_utility = new Clean_Utility();

        $abs_root = $_SERVER['DOCUMENT_ROOT'];
        $temp_user_dir = null;
        if(isset($_POST['login']))
        {
            $login_name = htmlspecialchars($_POST['login_name']);
            $password = htmlspecialchars($_POST['password']);
            if(!Validate::checkStr($login_name, 32))
            {
                $errors[] = 'С логином что-то не так...';
            }
            if(!Validate::checkPassword($password))
            {
                $errors[] = 'С паролем что-то не так...';
            }
            $u_id = User::checkUserData($login_name, md5($password));
            if($u_id == false)
            {
                $errors[] = 'Данные для входа заданы не верно';
            }
            else
            {
                User::auth($u_id);
                $temp_user_dir = $abs_root.$dir_path.'/'.$u_id;
                // Удаляем директорию, если она есть
                $clean_utility->removeDirectory($temp_user_dir);

                if (!mkdir($abs_root.$dir_path.'/'.$u_id, 0777, true))
                {
                    $errors['not_dir'] = 'Не удалось создать временную директорию пользователя';
                }
                header('Location: /site/index');
            }
        }

        require_once ROOT . '/views/site/login.php';
        return true;
    }

    public function actionLogout()
    {
        // Стартуем сессию
        session_start();
        $_SESSION = array();
        session_destroy ();
        // Перенаправляем пользователя на главную страницу
        header("Location: /");
    }

    public function actionBarcode39()
    {
        $is_create = false;
        $user = null;
        $user_id = null;


        // Подключаем файл с проверками ролей пользователя
        require_once ROOT . '/config/role_ckeck.php';

        $abs_file_path = ROOT.'/temp/users/'.$user_id.'/';

        $string_utility = new String_Utility();
        $date_converter = new Date_Converter();

        $errors = false;

        $pid = null; // Id посылки

        if (isset($_GET['pid']))
        {
            $pid = htmlspecialchars($_GET['pid']);
        }


        $package_info = Package::getPackageInfo($pid);
        $barcode = null;

        if ($package_info['p_number'] != null)
        {
            $file_path = $abs_file_path;
            $barcode_filename = $file_path.USER_BARCODE;
            $barcode_filetype = 'png';
            $barcode_file = $barcode_filename.'.'.$barcode_filetype;

            if (is_file($barcode_file))
            {
                unlink($barcode_file);
            }

            $barcode= new Barcode();

            if ($barcode)
            {
                $barcode->setFont('Tahoma', true);
                $barcode->setSymblogy('code39');
                $barcode->setHeight(35);
                $barcode->setScale(2);
                $barcode->setFontScale(0);
                $barcode->setHexColor('#000000', '#FFFFFF');

                $barcode->genBarCode($package_info['p_number'], $barcode_filetype, $barcode_filename);
            }
        }
        else
        {
            $errors['p_number']= 'Не удалось определить трек-номер';
        }
        $package_objects_count = 0;

        $package_objects = Package::getPackageObjects($pid);
        $package_objects_count = count($package_objects);

        if ($is_create)
        {
            require_once ROOT . '/views/site/barcode_39.php';
            return true;
        }
        else
        {
            header('Location: /site/error');
        }
    }

    public function actionChoose()
    {
        $is_create = false;
        $is_admin = false;
        $user = null;
        $user_id = null;

        // Подключаем файл с проверками ролей пользователя
        require_once ROOT . '/config/role_ckeck.php';

        $string_utility = new String_Utility();

        $errors = false;

        if (!$is_admin)
        {
            Company::memorizeCompany($user['company_address_id'], FROM_COMPANY);
        }

        $from_company_id = Company::checkCompanyInMemory(FROM_COMPANY); // Откуда
        $to_company_id = Company::checkCompanyInMemory(TO_COMPANY); // Кому

        $company_from = null; // Компания отправитель
        $company_to = null; // Компания получатель

        if (isset($_POST['package_object_delete']))
        {
            Package::outPackageObject($_POST['package_object_delete']);
        }

        $package_list = Package::checkPackage(); // Посылка
        $package_objects = Package::checkPackageObjects(); // Объекты посылки

        if($from_company_id != null)
        {
            $company_from = Company::getCompany($from_company_id);
        }

        if ($to_company_id != null)
        {
            $company_to = Company::getCompany($to_company_id);
        }

        if ($from_company_id == $to_company_id && $from_company_id != null)
        {
            $errors['equals_companies'] = 'Отправитель и получатель должны быть разными';
        }

        if (isset($_POST['package_object_delete']))
        {
            Package::outPackageObject($_POST['package_object_delete']);
        }

        if (isset($_POST['yes']))
        {
            if ($from_company_id == null)
            {
                $errors['from_company'] = 'Не выбран отправитель';
            }

            if ($to_company_id == null)
            {
                $errors['to_company'] = 'Не выбран получатель';
            }

            if ($package_list == null)
            {
                $errors['package_list'] = 'Посылка не найдена';
            }

            if ($package_objects == null)
            {
                $errors['package_objects'] = 'Объекты посылки не найдены';
            }

            if ($errors == false)
            {
                header('Location: /site/create');
            }
        }
        if (isset($_POST['no']))
        {
            Company::outCompanyFromMemory(FROM_COMPANY);
            Company::outCompanyFromMemory(TO_COMPANY);
            Package::outPackage();
            Package::outPackageObjects();
            header('Location: /site/index');
        }

        if ($is_create)
        {
            require_once ROOT . '/views/site/choose.php';
            return true;
        }
        else
        {
            header('Location: /site/error');
        }
    }

    public function actionTest()
    {
        $user = null;
        $user_id = null;


        // Подключаем файл с проверками ролей пользователя
        require_once ROOT . '/config/role_ckeck.php';

        require_once ROOT . '/views/site/test.php';
        return true;
    }

    public function actionNotification()
    {
        $user = null;
        $user_id = null;
        $is_notification = false;
        // Подключаем файл с проверками ролей пользователя
        require_once ROOT . '/config/role_ckeck.php';

        $check_notification = Notification::checkNotification($user_id, 1);

        if ($check_notification)
        {
            echo '1';
        }
        else
        {
            echo '2';
        }

        return true;
    }


}