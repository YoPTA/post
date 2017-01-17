<?php


class CompanyController
{
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
}