<?php


class PackageController
{
    public function actionObjects()
    {
        $user = null;
        // Подключаем файл с проверками ролей пользователя
        require_once ROOT . '/config/role_ckeck.php';


        $pid = null; // Id посылки

        if (isset($_GET['pid']))
        {
            $pid = htmlspecialchars($_GET['pid']);
        }

        $p_objects = Package::getPackageObjects($pid);

        require_once ROOT . '/views/package/objects.php';
        return true;
    }

    public function actionPackageAdd()
    {
        $user = null;
        $is_create = false;
        // Подключаем файл с проверками ролей пользователя
        require_once ROOT . '/config/role_ckeck.php';

        $errors = false;


        $package = Package::checkPackage();


        if (isset($_POST['note']))
        {
            $package['note'] = htmlspecialchars(trim($_POST['note']));
        }

        if (isset($_POST['comment']))
        {
            $package['comment'] = htmlspecialchars(trim($_POST['comment']));
        }

        if (isset($_POST['add']))
        {
            if (!Validate::checkStr($package['note'], 128))
            {
                $errors['note'] = 'Посылка не может быть такой длины';
            }

            if (!Validate::checkStrCanEmpty($package['comment'], 1024))
            {
                $errors['comment'] = 'Комментарий не может быть такой длины';
            }

            if ($errors == false)
            {
                Package::outPackage();
                $package_info['note'] = $package['note'];
                $package_info['comment'] = $package['comment'];
                Package::memorizePackage($package_info);

                header('Location: /site/choose');
            }
        }

        if ($is_create)
        {
            require_once ROOT . '/views/package/package/add.php';
            return true;
        }
        else
        {
            header('Location: /site/error');
        }
    }

    public function actionPackageDelete()
    {
        $user = null;
        $is_admin = false;
        // Подключаем файл с проверками ролей пользователя
        require_once ROOT . '/config/role_ckeck.php';

        $errors = false;

        $package = array();
        $pid = null;

        $link_get_param = '';
        $search = array();

        if (isset($_GET['search_type']))
        {
            $search['search_type'] = htmlspecialchars($_GET['search_type']);
        }
        if (isset($_GET['page']))
        {
            $search['page'] = htmlspecialchars($_GET['page']);
        }
        if (isset($_GET['track']))
        {
            $search['track'] = htmlspecialchars($_GET['track']);
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
        if (isset($_GET['search_package_state']))
        {
            $search['search_package_state'] = htmlspecialchars($_GET['search_package_state']);
        }
        if (isset($_GET['search_place_from_or_to']))
        {
            $search['search_place_from_or_to'] = htmlspecialchars($_GET['search_place_from_or_to']);
        }
        if (isset($_GET['search_place_to_or_from']))
        {
            $search['search_place_to_or_from'] = htmlspecialchars($_GET['search_place_to_or_from']);
        }
        if (isset($_GET['from_or_to']))
        {
            $search['from_or_to'] = htmlspecialchars($_GET['from_or_to']);
        }
        if (isset($_GET['to_or_from']))
        {
            $search['to_or_from'] = htmlspecialchars($_GET['to_or_from']);
        }
        if (isset($_GET['pid']))
        {
            $pid = htmlspecialchars($_GET['pid']);
        }


        $link_get_param = 'search_type='.$search['search_type'].'&page='.$search['page'].'&track='.$search['track']
            .'&package_type='.$search['package_type'].'&active_flag='.$search['active_flag']
            .'&search_relatively='. $search['search_relatively'] . '&search_package_state='. $search['search_package_state']
            .'&search_place_from_or_to='. $search['search_place_from_or_to'] .'&search_place_to_or_from=' . $search['search_place_to_or_from']
            .'&from_or_to='. $search['from_or_to'] .'&to_or_from='.$search['to_or_from'];


        $package = Package::getPackage($pid);


        if (isset($_POST['yes']))
        {
            Package::deletePackage($pid);
            header('Location: /site/index?'.$link_get_param);
        }

        if (isset($_POST['no']))
        {
            header('Location: /site/index?'.$link_get_param);
        }


        if ($is_admin)
        {
            require_once ROOT . '/views/package/package/delete.php';
            return true;
        }
        else
        {
            header('Location: /site/error');
        }
    }

    public function actionPackageObjectAdd()
    {
        $user = null;
        $is_create = false;
        // Подключаем файл с проверками ролей пользователя
        require_once ROOT . '/config/role_ckeck.php';

        $errors = false;


        $package_object = null;

        if (isset($_POST['name']))
        {
            $package_object['name'] = htmlspecialchars(trim($_POST['name']));
        }

        if (isset($_POST['add']))
        {
            if (!Validate::checkStrCanEmpty($package_object['name'], 512))
            {
                $errors['name'] = 'Объект посылки не может быть такой длины';
            }

            if ($errors == false)
            {
                Package::memorizePackageObject($package_object['name']);

                header('Location: /site/choose');
            }
        }

        if ($is_create)
        {
            require_once ROOT . '/views/package/object/add.php';
            return true;
        }
        else
        {
            header('Location: /site/error');
        }
    }
}