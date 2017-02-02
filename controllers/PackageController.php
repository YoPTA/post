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