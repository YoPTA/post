<?php

class SiteController
{
    public function actionIndex()
    {
        $user = null;
        // Подключаем файл с проверками ролей пользователя
        require_once ROOT . '/config/role_ckeck.php';
        $string_utility = new String_Utility();

        $date_converter = new Date_Converter();

        $errors = false;

        $track = null;
        $page = 1;
        $date_create = null;
        $package_type = 0;
        $office = OFFICE_ALL;

        if (!isset($_GET['track']))
        {
            header('Location: /site/index?track=&page=1&date_create=&package_type='.$package_type.'&office='.$office);
        }

        if (isset($_GET['track']))
        {
            $track = htmlspecialchars($_GET['track']);
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

        $packages = Package::getPackages($track, $page, $date_converter->stringToDate($date_create), $package_type, $user['company_address_id'], $office);

        $total_packages = Package::getTotalPackages($track, $date_converter->stringToDate($date_create), $package_type, $user['company_address_id'], $office);


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

    public function actionSelectcompany()
    {
        $is_create = false;
        $user = null;

        // Подключаем файл с проверками ролей пользователя
        require_once ROOT . '/config/role_ckeck.php';

        $company_type = null; // Организация

        $companies = Company::getAllCompanies(); // Получаем все организации

        if(isset($_GET['c_t']))
        {
            $company_type = Company::determineCompanyType($_GET['c_t']);
        }


        $select_company = null;
        if(Company::checkCompanyInMemory($company_type) != null)
        {
            $select_company = Company::checkCompanyInMemory($company_type);
        }

        if(isset($_POST['select']))
        {
            if(isset($_POST['company']))
            {
                $select_company = $_POST['company'];
                if($company_type != null)
                {
                    Company::memorizeCompany($select_company, $company_type);
                    header('Location: /site/create');
                }
            }
        }

        if($is_create)
        {
            require_once ROOT . '/views/site/selectcompany.php';
            return true;
        }
        else
        {
            header('Location: /site/error');
        }
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
        }


        $package_list = Package::checkPackage(); // Номер ведомости
        $package_objects = Package::checkPackageObjects(); // Объекты посылки
        $package = null; // Посылка

        $route_without_send = null; // Id маршрута без отправки
        $receive_values = null; // Информация о получении

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
            if(isset($_POST['delivery_type']) && $_POST['delivery_type'] != null)
            {
                $delivery_type = htmlspecialchars($_POST['delivery_type']);
            }
            else
            {
                $errors[] = "Не выбран способ доставки";
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

                Route::receive($route_without_send, $receive_values);

                Company::outCompanyFromMemory(FROM_COMPANY);
                Company::outCompanyFromMemory(TO_COMPANY);
                Package::outPackage();
                Package::outPackageObjects();

                header('Location: /site/index?track='. $track .'&page=1&date_create=&package_type='.PACKAGE_ALL.'&office='.OFFICE_ALL);
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

        $track = null;
        $site_page = 1;
        $page = 1;
        $date_create = null;
        $package_type = 0;
        $office = OFFICE_NOW;
        $pid = null; // Id посылки

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


        $package_info = Package::getPackageInfo($pid);
        $barcode = null;

        if ($package_info['p_number'] != null)
        {
            $file_path = $abs_file_path;
            //echo $file_path;
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
                $errors['package_list'] = 'Посылка не создана';
            }

            if ($package_objects == null)
            {
                $errors['package_objects'] = 'Объекты посылки не найдены';
            }



            if ($errors == false)
            {

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

        $abs_file_path = $_SERVER['DOCUMENT_ROOT'].'/temp/users/'.$user_id;
        $file_path = $abs_file_path;
        $barcode_filename = $file_path.'barcode';
        $png = 'png';
        $barcode_filetype = 'PNG';
        $barcode_file = $barcode_filename.'.'.$png;

        require_once ROOT . '/views/site/test.php';
        return true;
    }
}