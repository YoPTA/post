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

            $check_key_field = Company::checkKeyFieldExists($company['key_field']);

            if ($check_key_field != false)
            {
                if ($check_key_field != $cid)
                {
                    $errors['key_field'] = 'Организация с таким ИНН уже существует в базе';
                }
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

        if (isset($_POST['yes']))
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

    /*****************************
     * Работа с орагнизациями КОНЕЦ
     *****************************/

    /*****************************
     * Работа с адресами орагнизации НАЧАЛО
     *****************************/


    /*****************************
     * Работа с адресами орагнизации КОНЕЦ
     *****************************/

}