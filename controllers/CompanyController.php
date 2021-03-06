<?php


class CompanyController
{

    /*****************************
     * Работа с орагнизациями НАЧАЛО
     *****************************/
    public function actionCompanyIndex()
    {
        $is_create = false;
        $is_change_company = false;
        $user = null;
        $user_id = null;

        // Подключаем файл с проверками ролей пользователя
        require_once ROOT . '/config/role_ckeck.php';

        $c_type = null; // Тип компании (получатель/отправитель)
        $search_param = null; // Искомое значение
        $page = 1; // Номер страницы

        if (isset($_GET['c_type']))
        {
            $c_type = htmlspecialchars($_GET['c_type']);
        }

        if (isset($_GET['search_value']))
        {
            $search_param['search_value'] = htmlspecialchars($_GET['search_value']);
        }

        if (isset($_GET['page']))
        {
            $page = htmlspecialchars($_GET['page']);
        }

        if (!isset($_GET['search_value']))
        {
            header('Location: /company/company_index?c_type='. $c_type .'&search_value='. $search_param['search_value'] .'&page=1');
        }

        if ($page < 1)
        {
            $page = 1;
        }

        $companies = Company::getCompanies($search_param, $page);
        $total_companies = Company::getTotalCompanies($search_param);

        $index_number = ($page - 1) * Company::SHOW_BY_DEFAULT;

        $pagination = new Pagination($total_companies, $page, Company::SHOW_BY_DEFAULT, 'page=');



        if ($is_create)
        {
            require_once ROOT . '/views/company/company/index.php';
            return true;
        }
        else
        {
            header('Location: /site/error');
        }
    }

    public function actionCompanyAdd()
    {
        $is_create = false;
        $is_change_company = false;
        $user = null;
        $user_id = null;

        // Подключаем файл с проверками ролей пользователя
        require_once ROOT . '/config/role_ckeck.php';

        $errors = false;

        $c_type = null; // Тип компании (получатель/отправитель)
        $search_param = null; // Искомое значение
        $page = 1; // Номер страницы

        $date_time = new DateTime();


        $company = null; // Информация об организации

        if (isset($_GET['c_type']))
        {
            $c_type = htmlspecialchars($_GET['c_type']);
        }

        if (isset($_GET['search_value']))
        {
            $search_param['search_value'] = htmlspecialchars($_GET['search_value']);
        }

        if (isset($_GET['page']))
        {
            $page = htmlspecialchars($_GET['page']);
        }

        if (isset($_POST['name']))
        {
            $company['name'] = htmlspecialchars(trim($_POST['name']));
        }

        if (isset($_POST['full_name']))
        {
            $company['full_name'] = htmlspecialchars(trim($_POST['full_name']));
        }

        if (isset($_POST['key_field']))
        {
            $company['key_field'] = htmlspecialchars(trim($_POST['key_field']));
        }

        if (isset($_POST['is_mfc']))
        {
            $company['is_mfc'] = htmlspecialchars(trim($_POST['is_mfc']));
        }

        if (isset($_POST['add']))
        {
            if (!Validate::checkStr($company['name'], 256))
            {
                $errors['name'] = 'Наименование организации не может быть такой длины';
            }

            if (!Validate::checkStr($company['full_name'], 512))
            {
                $errors['full_name'] = 'Полное наименование организации не может быть такой длины';
            }

            if (!Validate::checkStrEqualLength($company['key_field'], 10))
            {
                $errors['key_field'] = 'ИНН организации не может быть такой длины';
            }

            if ($company['is_mfc'] < 0 || !preg_match("|^[\d]+$|", $company['is_mfc']) || $company['is_mfc'] > 1)
            {
                $errors['is_mfc'] = 'Попытка ввести неверное значение провалилась!';
            }

            $check_key_field = Company::checkKeyFieldExists($company['key_field']);
            if ($check_key_field != false)
            {
                $errors['key_field'] = 'Организация с таким ИНН уже существует в базе';
            }

            if ($errors == false)
            {
                $company['created_datetime'] = $date_time->format('Y-m-d H:i:s');
                $company['created_user_id'] = $user_id;
                Company::addCompany($company);
                header('Location: /company/company_index?c_type='.$c_type.'&search_value='.$search_param['search_value']
                    .'&page='.$page);
            }

        }


        if ($is_create)
        {
            require_once ROOT . '/views/company/company/add.php';
            return true;
        }
        else
        {
            header('Location: /site/error');
        }
    }

    public function actionCompanyEdit()
    {
        $is_change_company = false;
        $user = null;
        $user_id = null;

        // Подключаем файл с проверками ролей пользователя
        require_once ROOT . '/config/role_ckeck.php';

        $errors = false;

        $c_type = null; // Тип компании (получатель/отправитель)
        $search_param = null; // Искомое значение
        $page = 1; // Номер страницы
        $cid = null; // ID организации

        $date_time = new DateTime();

        $company = null; // Информация об организации

        if (isset($_GET['c_type']))
        {
            $c_type = htmlspecialchars($_GET['c_type']);
        }

        if (isset($_GET['search_value']))
        {
            $search_param['search_value'] = htmlspecialchars($_GET['search_value']);
        }

        if (isset($_GET['page']))
        {
            $page = htmlspecialchars($_GET['page']);
        }

        if (isset($_GET['cid']))
        {
            $cid = htmlspecialchars($_GET['cid']);
        }

        $company = Company::getCompanyInfo($cid);

        if (isset($_POST['name']))
        {
            $company['name'] = htmlspecialchars(trim($_POST['name']));
        }

        if (isset($_POST['full_name']))
        {
            $company['full_name'] = htmlspecialchars(trim($_POST['full_name']));
        }

        if (isset($_POST['key_field']))
        {
            $company['key_field'] = htmlspecialchars(trim($_POST['key_field']));
        }

        if (isset($_POST['is_mfc']))
        {
            $company['is_mfc'] = htmlspecialchars(trim($_POST['is_mfc']));
        }

        if (isset($_POST['edit']))
        {
            if (!Validate::checkStr($company['name'], 256))
            {
                $errors['name'] = 'Наименование организации не может быть такой длины';
            }

            if (!Validate::checkStr($company['full_name'], 512))
            {
                $errors['full_name'] = 'Полное наименование организации не может быть такой длины';
            }

            if (!Validate::checkStrEqualLength($company['key_field'], 10))
            {
                $errors['key_field'] = 'ИНН организации не может быть такой длины';
            }

            if ($company['is_mfc'] < 0 || !preg_match("|^[\d]+$|", $company['is_mfc']) || $company['is_mfc'] > 1)
            {
                $errors['is_mfc'] = 'Попытка ввести неверное значение провалилась!';
            }

            $check_key_field = Company::checkKeyFieldExists($company['key_field']);

            if ($check_key_field != false)
            {
                if ($check_key_field != $cid)
                {
                    $errors['key_field'] = 'Организация с таким ИНН уже существует в базе';
                }
            }

            if ($cid == 0)
            {
                $errors['not_edit'] = 'Нельзя редактировать данную запись!!!';
            }

            if ($errors == false)
            {
                $company['changed_datetime'] = $date_time->format('Y-m-d H:i:s');
                $company['changed_user_id'] = $user_id;
                Company::updateCompany($cid, $company);
                header('Location: /company/company_index?c_type='.$c_type.'&search_value='.$search_param['search_value']
                    .'&page='.$page);
            }

        }


        if ($is_change_company)
        {
            require_once ROOT . '/views/company/company/edit.php';
            return true;
        }
        else
        {
            header('Location: /site/error');
        }
    }

    public function actionCompanyDelete()
    {
        $is_change_company = false;
        $user = null;
        $user_id = null;

        // Подключаем файл с проверками ролей пользователя
        require_once ROOT . '/config/role_ckeck.php';

        $errors = false;

        $c_type = null; // Тип компании (получатель/отправитель)
        $search_param = null; // Искомое значение
        $page = 1; // Номер страницы
        $cid = null; // ID организации

        $date_time = new DateTime();

        $company = null;

        if (isset($_GET['c_type']))
        {
            $c_type = htmlspecialchars($_GET['c_type']);
        }

        if (isset($_GET['search_value']))
        {
            $search_param['search_value'] = htmlspecialchars($_GET['search_value']);
        }

        if (isset($_GET['page']))
        {
            $page = htmlspecialchars($_GET['page']);
        }

        if (isset($_GET['cid']))
        {
            $cid = htmlspecialchars($_GET['cid']);
        }

        $company = Company::getCompanyInfo($cid);
        if ($cid == 0)
        {
            $errors['no_delete'] = 'Нельзя удалить эту запись!!!';
        }

        if (isset($_POST['yes']))
        {
            if ($errors == false)
            {
                $company['changed_datetime'] = $date_time->format('Y-m-d H:i:s');
                $company['changed_user_id'] = $user_id;
                Company::deleteCompany($cid, $company);

                $total_companies = Company::getTotalCompanies($search_param);
                if ($total_companies <= Company::SHOW_BY_DEFAULT)
                {
                    $page = 1;
                }

                header('Location: /company/company_index?c_type='.$c_type.'&search_value='.$search_param['search_value']
                    .'&page='.$page);
            }
        }

        if (isset($_POST['no']))
        {
            header('Location: /company/company_index?c_type='.$c_type.'&search_value='.$search_param['search_value']
                .'&page='.$page);
        }


        if ($is_change_company)
        {
            require_once ROOT . '/views/company/company/delete.php';
            return true;
        }
        else
        {
            header('Location: /site/error');
        }
    }

    public function actionCompanyIpAddress()
    {
        $is_change_company = false;
        $user = null;
        $user_id = null;

        // Подключаем файл с проверками ролей пользователя
        require_once ROOT . '/config/role_ckeck.php';

        $errors = false;

        $c_type = null; // Тип компании (получатель/отправитель)
        $search_param = null; // Искомое значение
        $page = 1; // Номер страницы
        $cid = null; // ID организации

        $date_time = new DateTime();

        $company = null; // Информация об организации

        if (isset($_GET['c_type']))
        {
            $c_type = htmlspecialchars($_GET['c_type']);
        }

        if (isset($_GET['search_value']))
        {
            $search_param['search_value'] = htmlspecialchars($_GET['search_value']);
        }

        if (isset($_GET['page']))
        {
            $page = htmlspecialchars($_GET['page']);
        }

        if (isset($_GET['cid']))
        {
            $cid = htmlspecialchars($_GET['cid']);
        }

        $company = Company::getCompanyInfo($cid);

        if (isset($_POST['ip_address']))
        {
            $company['ip_address'] = htmlspecialchars(trim($_POST['ip_address']));
        }

        if (isset($_POST['edit']))
        {
            if (!Validate::checkStrCanEmpty($company['ip_address'], 42))
            {
                $errors['ip_address'] = 'Ip-адрес не может быть такой длины';
            }

            if ($errors == false)
            {
                $company['changed_datetime'] = $date_time->format('Y-m-d H:i:s');
                $company['changed_user_id'] = $user_id;
                Company::setIpAddress($cid, $company);
                header('Location: /company/company_index?c_type='.$c_type.'&search_value='.$search_param['search_value']
                    .'&page='.$page);
            }
        }




        if ($is_change_company)
        {
            require_once ROOT . '/views/company/company/ip_address.php';
            return true;
        }
        else
        {
            header('Location: /site/error');
        }
    }

    /*****************************
     * Работа с орагнизациями КОНЕЦ
     *****************************/

    /*****************************
     * Работа с адресами орагнизации НАЧАЛО
     *****************************/

    public function actionCompanyAddressIndex()
    {
        $is_create = false;
        $is_change_company = false;
        $user = null;
        $user_id = null;

        // Подключаем файл с проверками ролей пользователя
        require_once ROOT . '/config/role_ckeck.php';

        $string_utility = new String_Utility();

        $total_adresses = 0;

        $errors = false;

        $c_type = null; // Тип компании (получатель/отправитель)
        $search_param = null; // Искомое значение
        $page = 1; // Номер страницы
        $cid = null; // ID организации
        $index_number = 0; // Порядковый номер

        $company = null; // Информация об организации
        $company_addresses = null; // Информация об адресах организации


        if (isset($_GET['c_type']))
        {
            $c_type = htmlspecialchars($_GET['c_type']);
        }

        if (isset($_GET['search_value']))
        {
            $search_param['search_value'] = htmlspecialchars($_GET['search_value']);
        }

        if (isset($_GET['page']))
        {
            $page = htmlspecialchars($_GET['page']);
        }

        if ($page < 1)
        {
            $page = 1;
        }

        if (isset($_GET['cid']))
        {
            $cid = htmlspecialchars($_GET['cid']);
        }

        $company = Company::getCompanyInfo($cid);
        if (count($company) > 0)
        {
            $company_addresses = Company::getCompanyAddressByCompany($cid, 1, 0);
            $total_adresses = count($company_addresses);
        }

        if (isset($_POST['continue']))
        {
            $caid = htmlspecialchars($_POST['continue']);

            // Проверяем принадлежит ли адрес организации указанной оргназиации
            $check_ca_belong_c = Company::checkCompanyAddressBelongCompany($caid, $cid);

            if (!$check_ca_belong_c)
            {
                $errors['company_address_not_belong_comapny'] = 'Адрес организации не соответствует организации';
            }

            if ($c_type != FROM_COMPANY && $c_type != TO_COMPANY)
            {
                $errors['c_type'] = 'Тип организации указан не верно';
            }

            if ($errors == false)
            {
                // Запоминаем отправителя
                Company::memorizeCompany($caid, $c_type);
                header('Location: /site/choose');
            }


        }


        if ($is_create)
        {
            require_once ROOT . '/views/company/company_address/index.php';
            return true;
        }
        else
        {
            header('Location: /site/error');
        }
    }

    public function actionCompanyAddressAdd()
    {
        $is_create = false;
        $is_change_company = false;
        $user = null;
        $user_id = null;

        // Подключаем файл с проверками ролей пользователя
        require_once ROOT . '/config/role_ckeck.php';

        $date_time = new DateTime();

        $errors = false;

        $company_address = null; // Адрес организации
        $c_type = null; // Тип компании (получатель/отправитель)
        $search_param = null; // Искомое значение
        $page = 1; // Номер страницы
        $cid = null; // ID организации


        if (isset($_GET['c_type']))
        {
            $c_type = htmlspecialchars($_GET['c_type']);
        }

        if (isset($_GET['search_value']))
        {
            $search_param['search_value'] = htmlspecialchars($_GET['search_value']);
        }

        if (isset($_GET['page']))
        {
            $page = htmlspecialchars($_GET['page']);
        }

        if ($page < 1)
        {
            $page = 1;
        }

        if (isset($_GET['cid']))
        {
            $cid = htmlspecialchars($_GET['cid']);
        }

        $check_company = Company::checkCompanyInDb($cid);

        if ($cid == 0)
        {
            $errors['no_company'] = 'Вы не сможете добавить адрес, для данной организации';
        }

        if (!$check_company)
        {
            $errors['no_company_db'] = 'Организация, которой Вы добавляете адрес, не существует';
        }



        $company_address['address_country'] = 'Россия';
        $company_address['address_region'] = 'Пензенская область';


        if (isset($_POST['address_country']))
        {
            $company_address['address_country'] = htmlspecialchars(trim($_POST['address_country']));
        }

        if (isset($_POST['address_zip']))
        {
            $company_address['address_zip'] = htmlspecialchars(trim($_POST['address_zip']));
        }

        if (isset($_POST['address_region']))
        {
            $company_address['address_region'] = htmlspecialchars(trim($_POST['address_region']));
        }

        if (isset($_POST['address_area']))
        {
            $company_address['address_area'] = htmlspecialchars(trim($_POST['address_area']));
        }

        if (isset($_POST['address_city']))
        {
            $company_address['address_city'] = htmlspecialchars(trim($_POST['address_city']));
        }
        if (isset($_POST['address_town']))
        {
            $company_address['address_town'] = htmlspecialchars(trim($_POST['address_town']));
        }

        if (isset($_POST['address_street']))
        {
            $company_address['address_street'] = htmlspecialchars(trim($_POST['address_street']));
        }

        if (isset($_POST['address_home']))
        {
            $company_address['address_home'] = htmlspecialchars(trim($_POST['address_home']));
        }

        if (isset($_POST['address_case']))
        {
            $company_address['address_case'] = htmlspecialchars(trim($_POST['address_case']));
        }

        if (isset($_POST['address_build']))
        {
            $company_address['address_build'] = htmlspecialchars(trim($_POST['address_build']));
        }

        if (isset($_POST['address_apartment']))
        {
            $company_address['address_apartment'] = htmlspecialchars(trim($_POST['address_apartment']));
        }

        if (isset($_POST['is_transit']))
        {
            $company_address['is_transit'] = htmlspecialchars(trim($_POST['is_transit']));
        }

        if (isset($_POST['add']))
        {

            if (!Validate::checkStr($company_address['address_country'], 128))
            {
                $errors['address_country'] = 'Страна не может быть такой длины';
            }

            if (!Validate::checkStr($company_address['address_region'], 256))
            {
                $errors['address_region'] = 'Регион не может быть такой длины';
            }

            if (!Validate::checkStr($company_address['address_street'], 256))
            {
                $errors['address_street'] = 'Улица не может быть такой длины';
            }

            if (!Validate::checkStrCanEmpty($company_address['address_zip'], 16))
            {
                $errors['address_zip'] = 'Почтовый индекс не может быть такой длины';
            }

            if (!Validate::checkStrCanEmpty($company_address['address_area'], 256))
            {
                $errors['address_area'] = 'Район не может быть такой длины';
            }

            if (!Validate::checkStrCanEmpty($company_address['address_city'], 128))
            {
                $errors['address_city'] = 'Город не может быть такой длины';
            }

            if (!Validate::checkStrCanEmpty($company_address['address_town'], 128))
            {
                $errors['address_town'] = 'Населенный пункт не может быть такой длины';
            }

            if (!Validate::checkStrCanEmpty($company_address['address_home'], 32))
            {
                $errors['address_home'] = 'Дом не может быть такой длины';
            }

            if (!Validate::checkStrCanEmpty($company_address['address_case'], 16))
            {
                $errors['address_case'] = 'Корпус не может быть такой длины';
            }

            if (!Validate::checkStrCanEmpty($company_address['address_build'], 16))
            {
                $errors['address_build'] = 'Строение не может быть такой длины';
            }

            if (!Validate::checkStrCanEmpty($company_address['address_apartment'], 16))
            {
                $errors['address_apartment'] = 'Квартира не может быть такой длины';
            }

            if ($company_address['is_transit'] < 0 || !preg_match("|^[\d]+$|", $company_address['is_transit']) || $company_address['is_transit'] > 1)
            {
                $errors['is_transit'] = 'Попытка ввести неверное значение провалилась!';
            }

            if ($company_address['address_area'] == null
                && $company_address['address_city'] == null
                && $company_address['address_town'] == null)
            {
                $errors['no_ACT'] = 'Не может быть одновременно отсутствие Района, Города и Населенного пункта';
            }

            $local_place = null; // Информация о локальной точке
            $local_place_name = ''; // Полное наименование локальной точки
            $local_place_control = true; // Контроль добавления строки к локальной точке

            if ($errors == false)
            {
                $local_place_name = $company_address['address_country'] . '|||' . $company_address['address_region'];

                if ($local_place_control)
                {
                    if ($company_address['address_area'] != null)
                    {
                        $local_place_name .= '|||'.$company_address['address_area'];
                        $local_place_control = false;
                    }
                }

                if ($local_place_control)
                {
                    if ($company_address['address_city'] != null)
                    {
                        $local_place_name .= '|||' .$company_address['address_city'];
                        $local_place_control = false;
                    }
                }

                if ($local_place_control)
                {
                    if ($company_address['address_town'] != null)
                    {
                        $local_place_name .= '|||'.$company_address['address_town'];
                        $local_place_control = false;
                    }
                }

                $check_local_place = Local_Place::checkLocalPlace($local_place_name);
                $local_place_id = 0;

                if (!$check_local_place)
                {
                    $local_place['created_datetime'] = $date_time->format('Y-m-d H:i:s');
                    $local_place['created_user_id'] = $user_id;
                    $local_place['name'] = $local_place_name;
                    $local_place_id = Local_Place::addLocalPlace($local_place);
                    if (!$local_place_id)
                    {
                        $local_place_id = 0;
                    }
                }
                else
                {
                    $local_place_id = $check_local_place;
                }

                $company_address['created_datetime'] = $date_time->format('Y-m-d H:i:s');
                $company_address['created_user_id'] = $user_id;
                $company_address['local_place_id'] = $local_place_id;

                Company::addCompanyAddress($cid,$company_address);
                header('Location: /company/company_address_index?c_type='.$c_type.'&search_value='.$search_param['search_value']
                    .'&page='.$page.'&cid='.$cid);
            }
        }

        if ($is_create)
        {
            require_once ROOT . '/views/company/company_address/add.php';
            return true;
        }
        else
        {
            header('Location: /site/error');
        }
    }

    public function actionCompanyAddressEdit()
    {
        $is_create = false;
        $is_change_company = false;
        $user = null;
        $user_id = null;

        // Подключаем файл с проверками ролей пользователя
        require_once ROOT . '/config/role_ckeck.php';

        $date_time = new DateTime();

        $errors = false;

        $company_address = null; // Адрес организации
        $c_type = null; // Тип компании (получатель/отправитель)
        $search_param = null; // Искомое значение
        $page = 1; // Номер страницы
        $cid = null; // ID организации
        $caid = null; // ID адреса организации


        if (isset($_GET['c_type']))
        {
            $c_type = htmlspecialchars($_GET['c_type']);
        }

        if (isset($_GET['search_value']))
        {
            $search_param['search_value'] = htmlspecialchars($_GET['search_value']);
        }

        if (isset($_GET['page']))
        {
            $page = htmlspecialchars($_GET['page']);
        }

        if ($page < 1)
        {
            $page = 1;
        }

        if (isset($_GET['cid']))
        {
            $cid = htmlspecialchars($_GET['cid']);
        }

        if (isset($_GET['caid']))
        {
            $caid = htmlspecialchars($_GET['caid']);
        }

        $company_address = Company::getCompanyAddressInfo($caid);


        $check_company = Company::checkCompanyInDb($cid);

        if ($cid == 0)
        {
            $errors['no_company'] = 'Вы не сможете изменить адрес, для данной организации [CID]';
        }

        if ($caid == 0)
        {
            $errors['no_company_address'] = ' Вы не сможете изменить адрес, для данной организации [CAID]';
        }

        if (!$check_company)
        {
            $errors['no_company_db'] = 'Организация, которой Вы добавляете адрес, не существует';
        }



        $company_address['address_country'] = 'Россия';
        $company_address['address_region'] = 'Пензенская область';


        if (isset($_POST['address_country']))
        {
            $company_address['address_country'] = htmlspecialchars(trim($_POST['address_country']));
        }

        if (isset($_POST['address_zip']))
        {
            $company_address['address_zip'] = htmlspecialchars(trim($_POST['address_zip']));
        }

        if (isset($_POST['address_region']))
        {
            $company_address['address_region'] = htmlspecialchars(trim($_POST['address_region']));
        }

        if (isset($_POST['address_area']))
        {
            $company_address['address_area'] = htmlspecialchars(trim($_POST['address_area']));
        }

        if (isset($_POST['address_city']))
        {
            $company_address['address_city'] = htmlspecialchars(trim($_POST['address_city']));
        }
        if (isset($_POST['address_town']))
        {
            $company_address['address_town'] = htmlspecialchars(trim($_POST['address_town']));
        }

        if (isset($_POST['address_street']))
        {
            $company_address['address_street'] = htmlspecialchars(trim($_POST['address_street']));
        }

        if (isset($_POST['address_home']))
        {
            $company_address['address_home'] = htmlspecialchars(trim($_POST['address_home']));
        }

        if (isset($_POST['address_case']))
        {
            $company_address['address_case'] = htmlspecialchars(trim($_POST['address_case']));
        }

        if (isset($_POST['address_build']))
        {
            $company_address['address_build'] = htmlspecialchars(trim($_POST['address_build']));
        }

        if (isset($_POST['address_apartment']))
        {
            $company_address['address_apartment'] = htmlspecialchars(trim($_POST['address_apartment']));
        }

        if (isset($_POST['is_transit']))
        {
            $company_address['is_transit'] = htmlspecialchars(trim($_POST['is_transit']));
        }

        if (isset($_POST['edit']))
        {

            if (!Validate::checkStr($company_address['address_country'], 128))
            {
                $errors['address_country'] = 'Страна не может быть такой длины';
            }

            if (!Validate::checkStr($company_address['address_region'], 256))
            {
                $errors['address_region'] = 'Регион не может быть такой длины';
            }

            if (!Validate::checkStr($company_address['address_street'], 256))
            {
                $errors['address_street'] = 'Улица не может быть такой длины';
            }

            if (!Validate::checkStrCanEmpty($company_address['address_zip'], 16))
            {
                $errors['address_zip'] = 'Почтовый индекс не может быть такой длины';
            }

            if (!Validate::checkStrCanEmpty($company_address['address_area'], 256))
            {
                $errors['address_area'] = 'Район не может быть такой длины';
            }

            if (!Validate::checkStrCanEmpty($company_address['address_city'], 128))
            {
                $errors['address_city'] = 'Город не может быть такой длины';
            }

            if (!Validate::checkStrCanEmpty($company_address['address_town'], 128))
            {
                $errors['address_town'] = 'Населенный пункт не может быть такой длины';
            }

            if (!Validate::checkStrCanEmpty($company_address['address_home'], 32))
            {
                $errors['address_home'] = 'Дом не может быть такой длины';
            }

            if (!Validate::checkStrCanEmpty($company_address['address_case'], 16))
            {
                $errors['address_case'] = 'Корпус не может быть такой длины';
            }

            if (!Validate::checkStrCanEmpty($company_address['address_build'], 16))
            {
                $errors['address_build'] = 'Строение не может быть такой длины';
            }

            if (!Validate::checkStrCanEmpty($company_address['address_apartment'], 16))
            {
                $errors['address_apartment'] = 'Квартира не может быть такой длины';
            }

            if ($company_address['is_transit'] < 0 || !preg_match("|^[\d]+$|", $company_address['is_transit']) || $company_address['is_transit'] > 1)
            {
                $errors['is_transit'] = 'Попытка ввести неверное значение провалилась!';
            }

            if ($company_address['address_area'] == null
                && $company_address['address_city'] == null
                && $company_address['address_town'] == null)
            {
                $errors['no_ACT'] = 'Не может быть одновременно отсутствие Района, Города и Населенного пункта';
            }

            $local_place = null; // Информация о локальной точке
            $local_place_name = ''; // Полное наименование локальной точки
            $local_place_control = true; // Контроль добавления строки к локальной точке

            if ($errors == false)
            {
                $local_place_name = $company_address['address_country'] . '|||' . $company_address['address_region'];

                if ($local_place_control)
                {
                    if ($company_address['address_area'] != null)
                    {
                        $local_place_name .= '|||'.$company_address['address_area'];
                        $local_place_control = false;
                    }
                }

                if ($local_place_control)
                {
                    if ($company_address['address_city'] != null)
                    {
                        $local_place_name .= '|||' .$company_address['address_city'];
                        $local_place_control = false;
                    }
                }

                if ($local_place_control)
                {
                    if ($company_address['address_town'] != null)
                    {
                        $local_place_name .= '|||'.$company_address['address_town'];
                        $local_place_control = false;
                    }
                }

                $check_local_place = Local_Place::checkLocalPlace($local_place_name);
                $local_place_id = 0;

                if (!$check_local_place)
                {
                    $local_place['created_datetime'] = $date_time->format('Y-m-d H:i:s');
                    $local_place['created_user_id'] = $user_id;
                    $local_place['name'] = $local_place_name;
                    $local_place_id = Local_Place::addLocalPlace($local_place);
                    if (!$local_place_id)
                    {
                        $local_place_id = 0;
                    }
                }
                else
                {
                    $local_place_id = $check_local_place;
                }

                $company_address['changed_datetime'] = $date_time->format('Y-m-d H:i:s');
                $company_address['changed_user_id'] = $user_id;
                $company_address['local_place_id'] = $local_place_id;

                Company::updateCompanyAddress($caid, $company_address);
                header('Location: /company/company_address_index?c_type='.$c_type.'&search_value='.$search_param['search_value']
                    .'&page='.$page.'&cid='.$cid);
            }
        }

        if ($is_change_company)
        {
            require_once ROOT . '/views/company/company_address/edit.php';
            return true;
        }
        else
        {
            header('Location: /site/error');
        }
    }

    public function actionCompanyAddressDelete()
    {
        $is_create = false;
        $is_change_company = false;
        $user = null;
        $user_id = null;

        // Подключаем файл с проверками ролей пользователя
        require_once ROOT . '/config/role_ckeck.php';

        $date_time = new DateTime();
        $string_utility = new String_Utility();

        $errors = false;

        $company_address = null; // Адрес организации
        $c_type = null; // Тип компании (получатель/отправитель)
        $search_param = null; // Искомое значение
        $page = 1; // Номер страницы
        $cid = null; // ID организации
        $caid = null; // ID адреса организации


        if (isset($_GET['c_type']))
        {
            $c_type = htmlspecialchars($_GET['c_type']);
        }

        if (isset($_GET['search_value']))
        {
            $search_param['search_value'] = htmlspecialchars($_GET['search_value']);
        }

        if (isset($_GET['page']))
        {
            $page = htmlspecialchars($_GET['page']);
        }

        if ($page < 1)
        {
            $page = 1;
        }

        if (isset($_GET['cid']))
        {
            $cid = htmlspecialchars($_GET['cid']);
        }

        if (isset($_GET['caid']))
        {
            $caid = htmlspecialchars($_GET['caid']);
        }

        $company_address = Company::getCompanyAddressInfo($caid);

        if ($caid == 0)
        {
            $errors['no_company_address'] = ' Вы не сможете изменить адрес, для данной организации [CAID]';
        }

        if (isset($_POST['yes']))
        {
            if ($errors == false)
            {
                $company_address['changed_datetime'] = $date_time->format('Y-m-d H:i:s');
                $company_address['changed_user_id'] = $user_id;
                Company::deleteCompanyAddress($caid, $company_address);

                header('Location: /company/company_address_index?c_type='.$c_type.'&search_value='.$search_param['search_value']
                    .'&page='.$page.'&cid='.$cid);
            }
        }

        if (isset($_POST['no']))
        {
            header('Location: /company/company_address_index?c_type='.$c_type.'&search_value='.$search_param['search_value']
                .'&page='.$page.'&cid='.$cid);
        }


        if ($is_change_company)
        {
            require_once ROOT . '/views/company/company_address/delete.php';
            return true;
        }
        else
        {
            header('Location: /site/error');
        }
    }

    /*****************************
     * Работа с адресами орагнизации КОНЕЦ
     *****************************/

}