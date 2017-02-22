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
        $user_id = null;
        $is_admin = false;
        $admin_rights = null;
        $admin_menu_panel = Menu_Panel::getMenuPanel();
        // Подключаем файл с проверками ролей пользователя
        require_once ROOT . '/config/role_ckeck.php';

        // Подключаем файл с символами, которые необходимо заменить
        $charsToReplace = include (ROOT . '/components/charsToReplace.php');
        $validator = new Validate();

        $date_time = new DateTime();

        $string_utility = new String_Utility();

        $user_roles = User_Role::getRoles(); // Получаем роли, которые есть в базе
        $user_groups = User_Group::getGroups(); // Получаем группы, которые есть в базе
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

        if (isset($_POST['login']))
        {
            $user['login'] = htmlspecialchars(trim($_POST['login']));
        }

        if (isset($_POST['password']))
        {
            $user['password'] = htmlspecialchars(trim($_POST['password']));
        }

        if (isset($_POST['password_confirm']))
        {
            $user['password_confirm'] = htmlspecialchars(trim($_POST['password_confirm']));
        }

        if (isset($_POST['workpost']))
        {
            $user['workpost'] = htmlspecialchars(trim($_POST['workpost']));
        }

        if (isset($_POST['company_address_id']))
        {
            $user['company_address_id'] = htmlspecialchars($_POST['company_address_id']);
        }

        if (isset($_POST['role_id']))
        {
            $user['role_id'] = htmlspecialchars($_POST['role_id']);
        }

        if (isset($_POST['group_id']))
        {
            $user['group_id'] = htmlspecialchars($_POST['group_id']);
        }

        if (isset($_POST['add']))
        {
            if (!Validate::checkStr($user['lastname'], 128))
            {
                $errors['lastname'] = 'Фамилия не может быть такой длины';
            }

            if (!Validate::checkStr($user['firstname'], 64))
            {
                $errors['firstname'] = 'Имя не может быть такой длины';
            }

            if (!Validate::checkStrCanEmpty($user['middlename'], 128))
            {
                $errors['middlename'] = 'Отчество не может быть такой длины';
            }

            if (!Validate::checkStr($user['login'], 64))
            {
                $errors['login'] = 'Логин не может быть такой длины';
            }

            if (User::checkUserLogin($user['login']))
            {
                $errors['login'] = 'Указанный логин уже существует';
            }

            if (!Validate::checkStrCanEmpty($user['workpost'], 128))
            {
                $errors['workpost'] = 'Должность не может быть такой длины';
            }

            if (!Validate::checkPassword($user['password']))
            {
                $errors['password'] = 'Пароль от 6 до 20 английских символов или цифр';
            }

            if ($user['password'] !== $user['password_confirm'])
            {
                $errors['password_confirm'] = 'Пароли не совпадают';
            }

            if (!Company::checkCompanyAddress($user['company_address_id']))
            {
                $errors['company_address_id'] = 'Попытка указать не существующий адрес';
            }

            if (!User_Role::checkUserRole($user['role_id']))
            {
                $errors['role_id'] = 'Попытка указать не существующую роль';
            }

            if (!User_Group::checkUserGroup($user['group_id']))
            {
                $errors['group_id'] = 'Попытка указать не существующую группу';
            }

            if ($errors == false)
            {
                $user['password'] = md5($user['password']);
                $user['created_datetime'] = $date_time->format('Y-m-d H:i:s');
                $user['created_user_id'] = $user_id;

                User::addUser($user);
                header('Location: /admin/user_index?'.$get_params);
            }
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

    public function actionEdit()
    {
        $user_id = null;
        $is_admin = false;
        $admin_rights = null;
        $admin_menu_panel = Menu_Panel::getMenuPanel();
        // Подключаем файл с проверками ролей пользователя
        require_once ROOT . '/config/role_ckeck.php';

        // Подключаем файл с символами, которые необходимо заменить
        $charsToReplace = include (ROOT . '/components/charsToReplace.php');
        $validator = new Validate();

        $date_time = new DateTime();

        $string_utility = new String_Utility();

        $user_roles = User_Role::getRoles(); // Получаем роли, которые есть в базе
        $user_groups = User_Group::getGroups(); // Получаем группы, которые есть в базе
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

        $errors = false;

        $user = null; // Информация о пользователе
        $search = null; // Параметры поиска
        $uid = null; // Id пользователя
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

        if (isset($_GET['uid']))
        {
            $uid = htmlspecialchars($_GET['uid']);
        }

        $user = User::getUser($uid);


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

        if (isset($_POST['login']))
        {
            $user['login'] = htmlspecialchars(trim($_POST['login']));
        }

        if (isset($_POST['workpost']))
        {
            $user['workpost'] = htmlspecialchars(trim($_POST['workpost']));
        }

        if (isset($_POST['company_address_id']))
        {
            $user['company_address_id'] = htmlspecialchars($_POST['company_address_id']);
        }

        if (isset($_POST['role_id']) && $user['flag'] != 2)
        {
            $user['role_id'] = htmlspecialchars($_POST['role_id']);
        }

        if (isset($_POST['group_id']))
        {
            $user['group_id'] = htmlspecialchars($_POST['group_id']);
        }

        if ($user['flag'] == 2 && $uid != $user_id)
        {
            $errors['flag'] = 'Вы не сможете редактировать информацию данного пользователя';
        }

        if (isset($_POST['edit']))
        {
            if (!Validate::checkStr($user['lastname'], 128))
            {
                $errors['lastname'] = 'Фамилия не может быть такой длины';
            }

            if (!Validate::checkStr($user['firstname'], 64))
            {
                $errors['firstname'] = 'Имя не может быть такой длины';
            }

            if (!Validate::checkStrCanEmpty($user['middlename'], 128))
            {
                $errors['middlename'] = 'Отчество не может быть такой длины';
            }

            if (!Validate::checkStr($user['login'], 64))
            {
                $errors['login'] = 'Логин не может быть такой длины';
            }

            $user_info = User::getUser($uid);

            if (User::checkUserLogin($user['login']) && $user_info['login'] != $user['login'])
            {
                $errors['login'] = 'Указанный логин уже существует';
            }

            if (!Validate::checkStrCanEmpty($user['workpost'], 128))
            {
                $errors['workpost'] = 'Должность не может быть такой длины';
            }

            if (!Company::checkCompanyAddress($user['company_address_id']))
            {
                $errors['company_address_id'] = 'Попытка указать не существующий адрес';
            }

            if (!User_Role::checkUserRole($user['role_id']))
            {
                $errors['role_id'] = 'Попытка указать не существующую роль';
            }

            if (!User_Group::checkUserGroup($user['group_id']))
            {
                $errors['group_id'] = 'Попытка указать не существующую группу';
            }

            if ($errors == false)
            {
                $user['changed_datetime'] = $date_time->format('Y-m-d H:i:s');
                $user['changed_user_id'] = $user_id;
                User::updateUser($uid, $user, 1);
                header('Location: /admin/user_index?'.$get_params);

            }
        }

        if($admin_rights['can_change_user'] && $admin_rights['can_edit'])
        {
            require_once ROOT . '/views/admin/user/edit.php';
            return true;
        }
        else
        {
            header('Location: /site/error');
        }
    }

    public function actionPassword()
    {
        $user_id = null;
        $is_admin = false;
        $admin_rights = null;
        $admin_menu_panel = Menu_Panel::getMenuPanel();
        // Подключаем файл с проверками ролей пользователя
        require_once ROOT . '/config/role_ckeck.php';

        // Подключаем класс со склонениями имен
        require_once ROOT.'/components/NameCaseLib/NCLNameCaseRu.php';
        $name_case = new NCLNameCaseRu();

        $validator = new Validate();

        $date_time = new DateTime();

        $errors = false;

        $user = null; // Информация о пользователе
        $search = null; // Параметры поиска
        $uid = null; // Id пользователя
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

        if (isset($_GET['uid']))
        {
            $uid = htmlspecialchars($_GET['uid']);
        }

        $user = User::getUser($uid);

        if (isset($_POST['password']))
        {
            $user['password'] = htmlspecialchars(trim($_POST['password']));
        }

        if (isset($_POST['password_confirm']))
        {
            $user['password_confirm'] = htmlspecialchars(trim($_POST['password_confirm']));
        }

        if ($user['flag'] == 2 && $uid != $user_id)
        {
            $errors['flag'] = 'Вы не сможете редактировать информацию данного пользователя';
        }

        if (isset($_POST['edit']))
        {
            if (!Validate::checkPassword($user['password']))
            {
                $errors['password'] = 'Пароль от 6 до 20 английских символов или цифр';
            }

            if ($user['password'] !== $user['password_confirm'])
            {
                $errors['password_confirm'] = 'Пароли не совпадают';
            }

            if ($errors == false)
            {
                $user['password'] = md5($user['password']);
                $user['changed_datetime'] = $date_time->format('Y-m-d H:i:s');
                $user['changed_user_id'] = $user_id;
                User::updateUser($uid, $user, 2);
                header('Location: /admin/user_index?'.$get_params);
            }
        }


        if($admin_rights['can_change_user'] && $admin_rights['can_edit'])
        {
            require_once ROOT . '/views/admin/user/password.php';
            return true;
        }
        else
        {
            header('Location: /site/error');
        }
    }
}