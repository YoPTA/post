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
        Proxy::setProxyFlag(1);

        $errors = false;

        $index_search = null; // Параметры поиска

        $index_search['search_type'] = SEARCH_TYPE_COMMON; // Параметр поиска
        $page = 1; // Номер страницы
        $index_search['track'] = null; // Трек-номер

        $index_search['package_type'] = PACKAGE_INPUT; // Тип посылки (Входящие/Исходящие)
        $index_search['active_flag'] = ACTIVE_FLAG_ACTIVE; // Состояние посылки
        $index_search['date_create_begin'] = null; // Период поиска с
        $index_search['date_create_end'] = null; // Период поиска по

        $index_search['search_relatively'] = SEARCH_RELATIVELY_FROM_OR_TO; // Относительное местоположение
        $index_search['search_package_state'] = PACKAGE_STATE_ALL; // Состояние посылки
        $index_search['search_place_from_or_to'] = SEARCH_PLACE_ADDRESS; // Поиск по месту От кого/Для кого
        $index_search['search_place_to_or_from'] = SEARCH_PLACE_ADDRESS; // Поиск по месту Для кого/От кого
        $index_search['from_or_to'] = null; // От кого/Для кого
        $index_search['to_or_from'] = null; // Для кого/От кого

        $link_to_back = '';


        if (isset($_GET['search_type']))
        {
            $index_search['search_type'] = htmlspecialchars($_GET['search_type']);
        }

        if (isset($_GET['page']))
        {
            $page = htmlspecialchars($_GET['page']);
        }
        if ($page < 1)
        {
            $page = 1;
        }

        if (isset($_GET['track']))
        {
            $index_search['track'] = htmlspecialchars(trim($_GET['track']));
        }

        if (isset($_GET['package_type']))
        {
            $index_search['package_type'] = htmlspecialchars($_GET['package_type']);
        }

        if (isset($_GET['active_flag']))
        {
            $index_search['active_flag'] = htmlspecialchars($_GET['active_flag']);
        }

        if (isset($_GET['date_create_begin']))
        {
            $index_search['date_create_begin'] = htmlspecialchars(trim($_GET['date_create_begin']));
        }

        if (isset($_GET['date_create_end']))
        {
            $index_search['date_create_end'] = htmlspecialchars(trim($_GET['date_create_end']));
        }

        if (isset($_GET['search_relatively']))
        {
            $index_search['search_relatively'] = htmlspecialchars($_GET['search_relatively']);
        }

        if (isset($_GET['search_package_state']))
        {
            $index_search['search_package_state'] = htmlspecialchars($_GET['search_package_state']);
        }

        if (isset($_GET['search_place_from_or_to']))
        {
            $index_search['search_place_from_or_to'] = htmlspecialchars($_GET['search_place_from_or_to']); // Поиск по месту От кого/Для кого
        }

        if (isset($_GET['search_place_to_or_from']))
        {
            $index_search['search_place_to_or_from'] = htmlspecialchars($_GET['search_place_to_or_from']); // Поиск по месту Для кого/От кого
        }

        if (isset($_GET['from_or_to']))
        {
            $index_search['from_or_to'] = htmlspecialchars($_GET['from_or_to']);
        }

        if (isset($_GET['to_or_from']))
        {
            $index_search['to_or_from'] = htmlspecialchars($_GET['to_or_from']);
        }

        $link_to_back = 'search_type='.$index_search['search_type'].'&track='.$index_search['track'];

        if ($index_search['search_type'] == SEARCH_TYPE_SPECIAL)
        {
            $link_to_back .= '&package_type=' . $index_search['package_type'] .'&active_flag='. $index_search['active_flag'];

            if ($index_search['active_flag'] == ACTIVE_FLAG_ARCHIVE)
            {
                $link_to_back .= '&date_create_begin='.$index_search['date_create_begin'] .'&date_create_end='. $index_search['date_create_end'];
            }

            $link_to_back .= '&search_relatively='. $index_search['search_relatively'] . '&search_package_state='. $index_search['search_package_state']
                .'&search_place_from_or_to='. $index_search['search_place_from_or_to'] .'&search_place_to_or_from=' . $index_search['search_place_to_or_from']
                .'&from_or_to='. $index_search['from_or_to'] .'&to_or_from='.$index_search['to_or_from'];
        }

        $pid = null; // Id посылки

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

        $is_admin = false; // Права администратора
        $is_send = false; // Права отправления

        // Подключаем файл с проверками ролей пользователя
        require_once ROOT . '/config/role_ckeck.php';
        $string_utility = new String_Utility();
        $date_converter = new Date_Converter();
        $date_time = new DateTime();

        $errors = false;

        $site_page = 1;
        $pid = null; // Id посылки
        $rid = null; // Id маршрута
        $total_proxy_person = 0; // Общее кол-во доверенных лиц

        $with_or_without = 0; // С доверенным лицом или без него


        $send_values = null; // Данные об отправлении
        $route = null; // Информация о маршруте

        $index_search = null; // Параметры поиска

        $index_search['search_type'] = SEARCH_TYPE_COMMON; // Параметр поиска
        $page = 1; // Номер страницы
        $index_search['track'] = null; // Трек-номер

        $index_search['package_type'] = PACKAGE_INPUT; // Тип посылки (Входящие/Исходящие)
        $index_search['active_flag'] = ACTIVE_FLAG_ACTIVE; // Состояние посылки
        $index_search['date_create_begin'] = null; // Период поиска с
        $index_search['date_create_end'] = null; // Период поиска по

        $index_search['search_relatively'] = SEARCH_RELATIVELY_FROM_OR_TO; // Относительное местоположение
        $index_search['search_package_state'] = PACKAGE_STATE_ALL; // Состояние посылки
        $index_search['search_place_from_or_to'] = SEARCH_PLACE_ADDRESS; // Поиск по месту От кого/Для кого
        $index_search['search_place_to_or_from'] = SEARCH_PLACE_ADDRESS; // Поиск по месту Для кого/От кого
        $index_search['from_or_to'] = null; // От кого/Для кого
        $index_search['to_or_from'] = null; // Для кого/От кого

        $link_to_back = '';

        if (isset($_GET['search_type']))
        {
            $index_search['search_type'] = htmlspecialchars($_GET['search_type']);
        }

        if (isset($_GET['track']))
        {
            $index_search['track'] = htmlspecialchars(trim($_GET['track']));
        }

        if (isset($_GET['package_type']))
        {
            $index_search['package_type'] = htmlspecialchars($_GET['package_type']);
        }

        if (isset($_GET['active_flag']))
        {
            $index_search['active_flag'] = htmlspecialchars($_GET['active_flag']);
        }

        if (isset($_GET['date_create_begin']))
        {
            $index_search['date_create_begin'] = htmlspecialchars(trim($_GET['date_create_begin']));
        }

        if (isset($_GET['date_create_end']))
        {
            $index_search['date_create_end'] = htmlspecialchars(trim($_GET['date_create_end']));
        }

        if (isset($_GET['search_relatively']))
        {
            $index_search['search_relatively'] = htmlspecialchars($_GET['search_relatively']);
        }

        if (isset($_GET['search_package_state']))
        {
            $index_search['search_package_state'] = htmlspecialchars($_GET['search_package_state']);
        }

        if (isset($_GET['search_place_from_or_to']))
        {
            $index_search['search_place_from_or_to'] = htmlspecialchars($_GET['search_place_from_or_to']); // Поиск по месту От кого/Для кого
        }

        if (isset($_GET['search_place_to_or_from']))
        {
            $index_search['search_place_to_or_from'] = htmlspecialchars($_GET['search_place_to_or_from']); // Поиск по месту Для кого/От кого
        }

        if (isset($_GET['from_or_to']))
        {
            $index_search['from_or_to'] = htmlspecialchars($_GET['from_or_to']);
        }

        if (isset($_GET['to_or_from']))
        {
            $index_search['to_or_from'] = htmlspecialchars($_GET['to_or_from']);
        }

        $link_to_back = 'search_type='.$index_search['search_type'].'&track='.$index_search['track'];

        if ($index_search['search_type'] == SEARCH_TYPE_SPECIAL)
        {
            $link_to_back .= '&package_type=' . $index_search['package_type'] .'&active_flag='. $index_search['active_flag'];

            if ($index_search['active_flag'] == ACTIVE_FLAG_ARCHIVE)
            {
                $link_to_back .= '&date_create_begin='.$index_search['date_create_begin'] .'&date_create_end='. $index_search['date_create_end'];
            }

            $link_to_back .= '&search_relatively='. $index_search['search_relatively'] . '&search_package_state='. $index_search['search_package_state']
                .'&search_place_from_or_to='. $index_search['search_place_from_or_to'] .'&search_place_to_or_from=' . $index_search['search_place_to_or_from']
                .'&from_or_to='. $index_search['from_or_to'] .'&to_or_from='.$index_search['to_or_from'];
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

        if (isset($_GET['wow']))
        {
            $with_or_without = htmlspecialchars($_GET['wow']);
        }

        if ($with_or_without <= 0 || !is_numeric($with_or_without) || $with_or_without > 2)
        {
            $with_or_without = 0;
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
        $route = Route::getRouteInfo($rid);
        if ($route['local_place_id'] != $user['local_place_id'])
        {
            if (!$is_admin)
            {
                header('Location: /site/error');
            }
        }

        if (isset($_POST['send']))
        {
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

            $date_expired = '0000-00-00';

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
                            $date_expired = $ppp_item['date_expired'];
                            break;
                        }
                    }
                }
                if (!$is_match)
                {
                    $errors['proxy_or_proxy_person'] = 'Доверенность не принадлежит доверенному лицу. Выберите другую.';
                }

                $date_now = $date_time->format('Y-m-d');

                if ($date_expired < $date_now)
                {
                    $errors['date_expired_over'] = 'Истекла доверенность';
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
                Package::setPackageState($pid, 2);
                Proxy::outProxy();
                Proxy::outProxyPerson();
                header('Location: /site/index?'.$link_to_back.'&page='.$site_page);
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

        $is_admin = false; // Права администратора
        $is_receive = false; // Права получения

        // Подключаем файл с проверками ролей пользователя
        require_once ROOT . '/config/role_ckeck.php';
        $string_utility = new String_Utility();
        $date_converter = new Date_Converter();
        $date_time = new DateTime();

        $errors = false;

        $site_page = 1;
        $pid = null; // Id посылки
        $rid = null; // Id маршрута
        $total_proxy_person = 0; // Общее кол-во доверенных лиц

        $with_or_without = 0; // С доверенным лицом или без него

        $receive_values = null; // Данные о получении
        $route = null; // Информация о маршруте

        $index_search = null; // Параметры поиска

        $index_search['search_type'] = SEARCH_TYPE_COMMON; // Параметр поиска
        $page = 1; // Номер страницы
        $index_search['track'] = null; // Трек-номер

        $index_search['package_type'] = PACKAGE_INPUT; // Тип посылки (Входящие/Исходящие)
        $index_search['active_flag'] = ACTIVE_FLAG_ACTIVE; // Состояние посылки
        $index_search['date_create_begin'] = null; // Период поиска с
        $index_search['date_create_end'] = null; // Период поиска по

        $index_search['search_relatively'] = SEARCH_RELATIVELY_FROM_OR_TO; // Относительное местоположение
        $index_search['search_package_state'] = PACKAGE_STATE_ALL; // Состояние посылки
        $index_search['search_place_from_or_to'] = SEARCH_PLACE_ADDRESS; // Поиск по месту От кого/Для кого
        $index_search['search_place_to_or_from'] = SEARCH_PLACE_ADDRESS; // Поиск по месту Для кого/От кого
        $index_search['from_or_to'] = null; // От кого/Для кого
        $index_search['to_or_from'] = null; // Для кого/От кого

        $link_to_back = '';


        if (isset($_GET['search_type']))
        {
            $index_search['search_type'] = htmlspecialchars($_GET['search_type']);
        }

        if (isset($_GET['track']))
        {
            $index_search['track'] = htmlspecialchars(trim($_GET['track']));
        }

        if (isset($_GET['package_type']))
        {
            $index_search['package_type'] = htmlspecialchars($_GET['package_type']);
        }

        if (isset($_GET['active_flag']))
        {
            $index_search['active_flag'] = htmlspecialchars($_GET['active_flag']);
        }

        if (isset($_GET['date_create_begin']))
        {
            $index_search['date_create_begin'] = htmlspecialchars(trim($_GET['date_create_begin']));
        }

        if (isset($_GET['date_create_end']))
        {
            $index_search['date_create_end'] = htmlspecialchars(trim($_GET['date_create_end']));
        }

        if (isset($_GET['search_relatively']))
        {
            $index_search['search_relatively'] = htmlspecialchars($_GET['search_relatively']);
        }

        if (isset($_GET['search_package_state']))
        {
            $index_search['search_package_state'] = htmlspecialchars($_GET['search_package_state']);
        }

        if (isset($_GET['search_place_from_or_to']))
        {
            $index_search['search_place_from_or_to'] = htmlspecialchars($_GET['search_place_from_or_to']); // Поиск по месту От кого/Для кого
        }

        if (isset($_GET['search_place_to_or_from']))
        {
            $index_search['search_place_to_or_from'] = htmlspecialchars($_GET['search_place_to_or_from']); // Поиск по месту Для кого/От кого
        }

        if (isset($_GET['from_or_to']))
        {
            $index_search['from_or_to'] = htmlspecialchars($_GET['from_or_to']);
        }

        if (isset($_GET['to_or_from']))
        {
            $index_search['to_or_from'] = htmlspecialchars($_GET['to_or_from']);
        }

        $link_to_back = 'search_type='.$index_search['search_type'].'&track='.$index_search['track'];

        if ($index_search['search_type'] == SEARCH_TYPE_SPECIAL)
        {
            $link_to_back .= '&package_type=' . $index_search['package_type'] .'&active_flag='. $index_search['active_flag'];

            if ($index_search['active_flag'] == ACTIVE_FLAG_ARCHIVE)
            {
                $link_to_back .= '&date_create_begin='.$index_search['date_create_begin'] .'&date_create_end='. $index_search['date_create_end'];
            }

            $link_to_back .= '&search_relatively='. $index_search['search_relatively'] . '&search_package_state='. $index_search['search_package_state']
                .'&search_place_from_or_to='. $index_search['search_place_from_or_to'] .'&search_place_to_or_from=' . $index_search['search_place_to_or_from']
                .'&from_or_to='. $index_search['from_or_to'] .'&to_or_from='.$index_search['to_or_from'];
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

        if (isset($_GET['wow']))
        {
            $with_or_without = htmlspecialchars($_GET['wow']);
        }

        if ($with_or_without <= 0 || !is_numeric($with_or_without) || $with_or_without > 2)
        {
            $with_or_without = 0;
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

        $route = Route::getRouteInfo($rid);
        if ($route['local_place_id'] != $user['local_place_id'])
        {
            if (!$is_admin)
            {
                header('Location: /site/error');
            }
        }
        if (isset($_POST['with_or_without']))
        {
            $with_or_without = htmlspecialchars($_POST['with_or_without']);
        }


        $check_proxy = Proxy::checkProxy();
        $check_proxy_person = Proxy::checkProxyPerson();


        if (Proxy::checkProxyFlag() == 1)
        {
            $package_route = Route::getPackageRoute($route['package_id'], 2);
            if (count($package_route) > 1)
            {
                if ($with_or_without == 2)
                {
                    for ($i = count($package_route) - 1; $i >= 0; $i--)
                    {
                        if ($package_route[$i+1]['is_receive'] == 0 && $package_route[$i]['is_send'] == 1)
                        {
                            if ($package_route[$i+1]['id'] == $rid)
                            {
                                if ($check_proxy == null)
                                {
                                    Proxy::memorizeProxy($package_route[$i]['send_proxy_id']);
                                }
                                if ($check_proxy_person == null)
                                {
                                    Proxy::memorizeProxyPerson($package_route[$i]['send_proxy_person_id']);
                                }
                            }
                        }
                    }
                }
            }
        }

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

        if (isset($_POST['receive']))
        {
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

            $date_expired = '0000-00-00';

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
                            $date_expired = $ppp_item['date_expired'];
                            break;
                        }
                    }
                }
                if (!$is_match)
                {
                    $errors['proxy_or_proxy_person'] = 'Доверенность не принадлежит доверенному лицу. Выберите другую.';
                }

                $date_now = $date_time->format('Y-m-d');

                if ($date_expired < $date_now)
                {
                    $errors['date_expired_over'] = 'Истекла доверенность';
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
                    Package::setDelivered($pid, $receive_values['datetime_receive']);
                }

                $receive_stat = Route::receive($rid, $receive_values);
                Package::setNowAddresses($pid);
                Package::setPackageState($pid, 1);

                if ($receive_stat)
                {
                    Notification::launchNotification($pid);
                }

                Proxy::outProxy();
                Proxy::outProxyPerson();
                header('Location: /site/index?'.$link_to_back.'&page='.$site_page);
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
        Proxy::setProxyFlag(2);

        return true;
    }
}