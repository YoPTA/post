<?php


class RouteController
{
    public function actionView()
    {
        $user = null;

        $is_send = false; // Права отправления
        $is_receive = false; // Права получения

        // Подключаем файл с проверками ролей пользователя
        require_once ROOT . '/config/role_ckeck.php';
        $string_utility = new String_Utility();
        $date_converter = new Date_Converter();


        $errors = false;

        $track = null;
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

        $package_route = Route::getPackageRoute($pid, 1); // Поулчаем маршрут посылки

        require_once ROOT . '/views/route/view.php';
        return true;
    }

    public function actionSend()
    {
        $user = null;
        $user_id = null;

        $is_send = false; // Права отправления

        // Подключаем файл с проверками ролей пользователя
        require_once ROOT . '/config/role_ckeck.php';
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
        $rid = null; // Id маршрута
        $total_proxy_person = 0; // Общее кол-во доверенных лиц

        $with_or_without = 0; // С доверенным лицом или без него


        $send_values = null; // Данные об отправлении
        $route = null; // Информация о маршруте

        $search = null;

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

        if (isset($_GET['wow']))
        {
            $with_or_without = htmlspecialchars($_GET['wow']);
        }

        if ($with_or_without <= 0 || !is_numeric($with_or_without) || $with_or_without > 2)
        {
            $with_or_without = 0;
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

        if (isset($_GET['rid']))
        {
            $rid = htmlspecialchars($_GET['rid']);
        }

        $package_objects = Package::getPackageObjects($pid);
        $p_note = null; // Примечание (ведомость)
        $p_number = null; // Номер

        if (is_array($package_objects) && $package_objects != null)
        {
            foreach ($package_objects as $p_obj)
            {
                $p_note = $p_obj['note'];
                $p_number = $p_obj['number'];
            }
        }

		$proxy = null;
		$proxy_person = null;
		
        $proxy_id = Proxy::checkProxy(); // Доверенность
        $proxy_person_id = Proxy::checkProxyPerson(); // Доверреное лицо
        $proxy_person_proxies = Proxy::getProxyList($proxy_person_id, null);
		
		if ($proxy_id != null)
		{
			$proxy = Proxy::getProxy($proxy_id);
		}
		if ($proxy_person_id != null)
		{
			$proxy_person = Proxy::getProxyPerson($proxy_person_id);
		}

        if (isset($_POST['with_or_without']))
        {
            $with_or_without = htmlspecialchars($_POST['with_or_without']);
        }

        if (isset($_POST['send']))
        {
            $route = Route::getRouteInfo($rid);

            if ($route['package_id'] != $pid)
            {
                // Если посылка не соответствует маршруту
                $errors['package_id'] = 'Да ты хакер... :-)';
            }

            if ((int)$route['is_send'] != 0)
            {
                // Если посылку уже отправляли, либо, если ее нельзя отправить
                $errors['is_send'] = 'Отправление невозможно';
            }

            if ($route['datetime_send'] != DEFAULT_DATETIME)
            {
                // Если дата отправления уже стоит, значит посылку уже отправляли, либо взлом
                $errors['datetime_send'] = 'Ошибка с датой отправления';
            }

            if ($route['datetime_receive'] == DEFAULT_DATETIME)
            {
                // Если посылка еще не была получена
                $errors['datetime_receive'] = 'Попытка отправить посылку, которая еще не получалась';
            }


            if ($with_or_without == 1)
            {
                $proxy_id = 0;
                $proxy_person_id = 0;
            }
            else if ($with_or_without == 2)
            {

                if ($proxy_person_id == null || $proxy_person_id == 0)
                {
                    // Если не выбрано доверенное лицо
                    $errors['proxy_person_id'] = 'Не выбрано доверенное лицо';
                }
                if ($proxy_id == null || $proxy_id == 0)
                {
                    // Если не выбрана доверенность
                    $errors['proxy_id'] = 'Не выбрана доверенность';
                }

                $is_match = false; // Совпадение доверенности и доверенного лица

                if ($proxy_person_proxies != null && is_array($proxy_person_proxies))
                {
                    foreach ($proxy_person_proxies as $ppp_item)
                    {
                        if ($ppp_item['id'] == $proxy_id)
                        {
                            $is_match = true;
                            break;
                        }
                    }
                }
                if (!$is_match)
                {
                    $errors['proxy_or_proxy_person'] = 'Доверенность не принадлежит доверенному лицу. Выберите другую.';
                }
            }
            else
            {
                $errors['nothing'] = 'Не выбран тип передачи';
            }

            // Если ошибок не оказалось
            if ($errors == false)
            {
                $send_values['send_proxy_id'] = $proxy_id;
                $send_values['send_proxy_person_id'] = $proxy_person_id;
                $send_values['send_user_id'] = $user_id;
                $send_values['datetime_send'] = date('Y-m-d H:i:s');
                Route::send($rid, $send_values);
                Proxy::outProxy();
                Proxy::outProxyPerson();
                header('Location: /site/index');
            }
        }

        if ($is_send)
        {
            require_once ROOT . '/views/route/send.php';
            return true;
        }
        else
        {
            header('Location: /site/error');
        }
    }

    public function actionReceive()
    {
        $user = null;
        $user_id = null;

        $is_receive = false; // Права получения

        // Подключаем файл с проверками ролей пользователя
        require_once ROOT . '/config/role_ckeck.php';
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
        $rid = null; // Id маршрута
        $total_proxy_person = 0; // Общее кол-во доверенных лиц

        $with_or_without = 0; // С доверенным лицом или без него

        $receive_values = null; // Данные об отправлении
        $route = null; // Информация о маршруте

        $search = null;

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

        if (isset($_GET['wow']))
        {
            $with_or_without = htmlspecialchars($_GET['wow']);
        }

        if ($with_or_without <= 0 || !is_numeric($with_or_without) || $with_or_without > 2)
        {
            $with_or_without = 0;
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

        if (isset($_GET['rid']))
        {
            $rid = htmlspecialchars($_GET['rid']);
        }


        $package_objects = Package::getPackageObjects($pid);
        $p_note = null; // Примечание (ведомость)
        $p_number = null; // Номер

        if (is_array($package_objects) && $package_objects != null)
        {
            foreach ($package_objects as $p_obj)
            {
                $p_note = $p_obj['note'];
                $p_number = $p_obj['number'];
            }
        }

        $proxy = null;
        $proxy_person = null;

        $proxy_id = Proxy::checkProxy(); // Доверенность
        $proxy_person_id = Proxy::checkProxyPerson(); // Доверреное лицо
        $proxy_person_proxies = Proxy::getProxyList($proxy_person_id, null);

        if ($proxy_id != null)
        {
            $proxy = Proxy::getProxy($proxy_id);
        }
        if ($proxy_person_id != null)
        {
            $proxy_person = Proxy::getProxyPerson($proxy_person_id);
        }

        if (isset($_POST['with_or_without']))
        {
            $with_or_without = htmlspecialchars($_POST['with_or_without']);
        }

        if (isset($_POST['receive']))
        {
            $route = Route::getRouteInfo($rid);

            if ($route['package_id'] != $pid)
            {
                // Если посылка не соответствует маршруту
                $errors['package_id'] = 'Да ты хакер... :-)';
            }

            if ((int)$route['is_receive'] != 0)
            {
                // Если посылку уже подтвердили, либо, если ее нельзя подтвердить
                $errors['is_receive'] = 'Подтверждение невозможно';
            }

            if ($route['datetime_receive'] != DEFAULT_DATETIME)
            {
                // Если дата подтверждения уже стоит, значит посылку уже подтверждали, либо взлом
                $errors['datetime_receive'] = 'Ошибка с датой подтверждения';
            }

            if ($with_or_without == 1)
            {
                $proxy_id = 0;
                $proxy_person_id = 0;
            }
            else if ($with_or_without == 2)
            {

                if ($proxy_person_id == null || $proxy_person_id == 0)
                {
                    // Если не выбрано доверенное лицо
                    $errors['proxy_person_id'] = 'Не выбрано доверенное лицо';
                }
                if ($proxy_id == null || $proxy_id == 0)
                {
                    // Если не выбрана доверенность
                    $errors['proxy_id'] = 'Не выбрана доверенность';
                }

                $is_match = false; // Совпадение доверенности и доверенного лица

                if ($proxy_person_proxies != null && is_array($proxy_person_proxies))
                {
                    foreach ($proxy_person_proxies as $ppp_item)
                    {
                        if ($ppp_item['id'] == $proxy_id)
                        {
                            $is_match = true;
                            break;
                        }
                    }
                }
                if (!$is_match)
                {
                    $errors['proxy_or_proxy_person'] = 'Доверенность не принадлежит доверенному лицу. Выберите другую.';
                }
            }
            else
            {
                $errors['nothing'] = 'Не выбран тип передачи';
            }

            // Если ошибок не оказалось
            if ($errors == false)
            {
                $receive_values['receive_proxy_id'] = $proxy_id;
                $receive_values['receive_proxy_person_id'] = $proxy_person_id;
                $receive_values['receive_user_id'] = $user_id;
                $receive_values['datetime_receive'] = date('Y-m-d H:i:s');

                $package_info = Package::getPackage($pid);

                if ($route['company_address_id'] == $package_info['to_company_address_id'])
                {
                    Package::updateReceiptDatetime($pid, $receive_values['datetime_receive']);
                }


                Route::receive($rid, $receive_values);
                Proxy::outProxy();
                Proxy::outProxyPerson();
                header('Location: /site/index');
            }
        }


        if ($is_receive)
        {
            require_once ROOT . '/views/route/receive.php';
            return true;
        }
        else
        {
            header('Location: /site/error');
        }
    }



    public function actionClearProxy()
    {
        Proxy::outProxy();
        Proxy::outProxyPerson();

        return true;
    }
}