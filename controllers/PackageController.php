<?php


class PackageController
{
    public function actionObjects()
    {
        $user = null;
        // Подключаем файл с проверками ролей пользователя
        require_once ROOT . '/config/role_ckeck.php';

        $track = null;
        $site_page = 1;
        $page = 1;
        $date_create = null;
        $package_type = 0;
        $office = OFFICE_NOW;
        $pid = null; // Id посылки


        if (!isset($_GET['track']))
        {
            header('Location: /site/index?track=&page=1&date_create=&package_type='.$package_type.'&office='.$office);
        }

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


        $package = null;

        if (isset($_POST['number']))
        {
            $package['number'] = htmlspecialchars(trim($_POST['number']));
        }

        if (isset($_POST['add']))
        {
            if (!Validate::checkStrCanEmpty($package['number'], 128))
            {
                $errors['number'] = 'Посылка не может быть такой длины';
            }

            if ($errors == false)
            {
                Package::outPackage();
                Package::memorizePackage($package['number']);

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