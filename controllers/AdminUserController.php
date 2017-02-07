<?php

class AdminuserController
{
    public function actionIndex()
    {
        $is_admin = false;
        $admin_rights = null;
        $admin_menu_panel = Menu_Panel::getMenuPanel();
        // Подключаем файл с проверками ролей пользователя
        require_once ROOT . '/config/role_ckeck.php';

        $string_utility = new String_Utility();

        $search = null; // Параметры поиска
        $search['fio_or_login'] = null; // ФИО или Логин
        $search['office'] = 1; // Офис

        $page = 1; // Номер страницы
        $get_params = '';

        if (isset($_GET['fio_or_login']))
        {
            $search['fio_or_login'] = htmlspecialchars(trim($_GET['fio_or_login']));
        }

        if (isset($_GET['page']))
        {
            $page = htmlspecialchars($_GET['page']);
        }

        if ($page < 1)
        {
            $page = 1;
        }

        if (isset($_GET['office']))
        {
            $search['office'] = htmlspecialchars($_GET['office']);
        }


        $get_params = 'fio_or_login='.$search['fio_or_login'].'&page='.$page.'&office='.$search['office'];

        $companies = Company::getAllCompanies(2); // Организации и адреса организаций
        $only_companies = null; // Только организации
        $current = 0;

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

        $users = User::getUsers($search, $page);
        $total = User::getTotalUsers($search);
        $index_number = ($page - 1) * User::SHOW_BY_DEFAULT;
        $pagination = new Pagination($total, $page, User::SHOW_BY_DEFAULT, 'page=');

        if($is_admin)
        {
            require_once ROOT . '/views/admin/user/index.php';
            return true;
        }
        else
        {
            header('Location: /site/error');
        }
    }

    public function actionAdd()
    {
        $is_admin = false;
        $admin_rights = null;
        $admin_menu_panel = Menu_Panel::getMenuPanel();
        // Подключаем файл с проверками ролей пользователя
        require_once ROOT . '/config/role_ckeck.php';

        // Подключаем файл с символами, которые необходимо заменить
        $charsToReplace = include (ROOT . '/components/charsToReplace.php');
        $validator = new Validate();

        $string_utility = new String_Utility();

        $errors = false;

        $user = null; // Информация о пользователе
        $search = null; // Параметры поиска
        $search['fio_or_login'] = null; // ФИО или Логин
        $search['office'] = 1; // Офис

        $page = 1; // Номер страницы
        $get_params = '';

        if (isset($_GET['fio_or_login']))
        {
            $search['fio_or_login'] = htmlspecialchars(trim($_GET['fio_or_login']));
        }

        if (isset($_GET['page']))
        {
            $page = htmlspecialchars($_GET['page']);
        }

        if ($page < 1)
        {
            $page = 1;
        }

        if (isset($_GET['office']))
        {
            $search['office'] = htmlspecialchars($_GET['office']);
        }


        $get_params = 'fio_or_login='.$search['fio_or_login'].'&page='.$page.'&office='.$search['office'];


        if (isset($_POST['lastname']))
        {
            $user['lastname'] = htmlspecialchars(trim($validator->my_ucwords($_POST['lastname'])));

            $lnSegments = [];
            $lnSegments = explode("-", $user['lastname']);
            $toImplode = [];
            foreach ($lnSegments as $segments)
            {
                $toImplode[] = trim($validator->my_ucwords($segments));
            }
            $user['lastname'] = implode("-", $toImplode);
            $user['lastname'] = str_ireplace($charsToReplace, "", $user['lastname']);
        }

        if (isset($_POST['firstname']))
        {
            $user['firstname'] = htmlspecialchars(trim($validator->my_ucwords($_POST['firstname'])));
        }

        if (isset($_POST['middlename']))
        {
            $user['middlename'] = htmlspecialchars(trim($validator->my_ucwords($_POST['middlename'])));
        }


        if($admin_rights['can_create'])
        {
            require_once ROOT . '/views/admin/user/add.php';
            return true;
        }
        else
        {
            header('Location: /site/error');
        }
    }
}