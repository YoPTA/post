<?php


class DokaController
{
    static function check_domain_availible($domain)
    {
        $curlInit = curl_init($domain);
        curl_setopt($curlInit, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($curlInit, CURLOPT_HEADER, true);
        curl_setopt($curlInit, CURLOPT_NOBODY, true);
        curl_setopt($curlInit, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curlInit);
        curl_close($curlInit);

        if ($response)
            return true;
        return false;
    }

    public function actionPackage()
    {
        $is_create = false;
        $user = null;
        $user_id = null;

        // Подключаем файл с проверками ролей пользователя
        require_once ROOT . '/config/role_ckeck.php';
        $date_time = new DateTime();

        $errors = false;

        $from_company = null; // Откуда
        $to_company = null; // Куда
        $p_object = null; // Объекты посылки

        $is_c_in_db = false; // Имеется ли запись в бд
        $new_company = null; // Новая организация
        $request_list = null; // Дела ведомости

        $flag_to_add_CA = true; // Флаг на добавление адреса организации (по умолчанию добавить)

        $get_company_address_by_company_id = null; // Массив адресов организации

        $xml = new DOMDocument();

        $package = null; // Данные о посылке

        if(isset($_POST['package']) && !empty($_POST['package']))
        {
            $package = htmlspecialchars($_POST['package']);

            $url = SOURCE_SITE.$package;

            if ($this->check_domain_availible($url))
            {
                if($xml->load(SOURCE_SITE.$package) === false)
                {
                    $errors['load_xml'] = 'Невозможно загрузить. Проверьте правильность ввода номера ведомости.';
                }
                else
                {
                    $xml_list = Doka::GetXMLNode($xml, 'List');
                    if($xml_list == false)
                    {
                        $errors['tag_list'] = 'Не удалось найти тэг List';
                    }
                    $xml_from = Doka::GetXMLNode($xml_list, 'From');
                    if($xml_from == false)
                    {
                        $errors['tag_from'] = 'Не удалось найти тэг From';
                    }
                    $from_company['name'] = Doka::GetXMLValue($xml_from, 'Name');
                    $from_company['full_name'] = Doka::GetXMLValue($xml_from, 'FullName');
                    $from_company['key_field'] = Doka::GetXMLValue($xml_from, 'INN');
                    $from_company['address_country'] = Doka::GetXMLValue($xml_from, 'AddressCountry');
                    $from_company['address_zip'] = Doka::GetXMLValue($xml_from, 'AddressZip');
                    $from_company['address_region'] = Doka::GetXMLValue($xml_from, 'AddressRegion');
                    $from_company['address_area'] = Doka::GetXMLValue($xml_from, 'AddressArea');
                    $from_company['address_city'] = Doka::GetXMLValue($xml_from, 'AddressCity');
                    $from_company['address_town'] = Doka::GetXMLValue($xml_from, 'AddressTown');
                    $from_company['address_street'] = Doka::GetXMLValue($xml_from, 'AddressStreet');
                    $from_company['address_home'] = Doka::GetXMLValue($xml_from, 'AddressHome');
                    $from_company['address_case'] = Doka::GetXMLValue($xml_from, 'AddressCase');
                    $from_company['address_build'] = Doka::GetXMLValue($xml_from, 'AddressBuild');
                    $from_company['address_apartment'] = Doka::GetXMLValue($xml_from, 'AddressApatment');

                    $notification = null; // Информация об уведомлении
                    $notification_name = ''; // Полное наименование уведомления
                    $notification_control = true; // Контроль добавления строки к уведомлению
                    $notification_id = 0; // ID уведомления

                    $notification_name = $from_company['address_country'] . '|||' . $from_company['address_region'];
                    if ($notification_control)
                    {
                        if ($from_company['address_area'] != null)
                        {
                            $notification_name .= '|||'.$from_company['address_area'];
                            $notification_control = false;
                        }
                    }

                    if ($notification_control)
                    {
                        if ($from_company['address_city'] != null)
                        {
                            $notification_name .= '|||' .$from_company['address_city'];
                            $notification_control = false;
                        }
                    }

                    if ($notification_control)
                    {
                        if ($from_company['address_town'] != null)
                        {
                            $notification_name .= '|||'.$from_company['address_town'];
                            $notification_control = false;
                        }
                    }

                    if ($notification_control)
                    {

                        $notification_name .= '|||'.'ИзДокиПришлоПустоеПоле';
                        $notification_control = false;
                    }


                    $check_notification = Notification::checkNotification($notification_name);


                    if (!$check_notification)
                    {
                        $notification['created_datetime'] = $date_time->format('Y-m-d H:i:s');
                        $notification['created_user_id'] = $user_id;
                        $notification['name'] = $notification_name;
                        $notification_id = Notification::addNotification($notification);
                        if (!$notification_id)
                        {
                            $notification_id = 0;
                        }
                    }
                    else
                    {
                        $notification_id = $check_notification;
                    }

                    $from_company['notification_id'] = $notification_id;

                    if(!isset($errors['tag_from']))
                    {
                        $is_c_in_db = Company::checkKeyFieldExists($from_company['key_field']);

                        // Проверка организации по ключевому полю
                        if($is_c_in_db != false)
                        {

                            // Если есть такая организация, то смотрим имеется ли такой адрес
                            $get_company_address_by_company_id = Company::getCompanyAddressByCompany($is_c_in_db, 0, 1);
                            if($get_company_address_by_company_id != null)
                            {
                                foreach($get_company_address_by_company_id as $g_c_a_b_c_i)
                                {
                                    if($from_company['address_country'] == $g_c_a_b_c_i['address_country'] &&
                                        $from_company['address_zip'] == $g_c_a_b_c_i['address_zip'] &&
                                        $from_company['address_region'] ==  $g_c_a_b_c_i['address_region'] &&
                                        $from_company['address_area'] ==  $g_c_a_b_c_i['address_area'] &&
                                        $from_company['address_city'] ==  $g_c_a_b_c_i['address_city'] &&
                                        $from_company['address_town'] ==  $g_c_a_b_c_i['address_town'] &&
                                        $from_company['address_street'] ==  $g_c_a_b_c_i['address_street'] &&
                                        $from_company['address_home'] ==  $g_c_a_b_c_i['address_home'] &&
                                        $from_company['address_case'] ==  $g_c_a_b_c_i['address_case'] &&
                                        $from_company['address_build'] ==  $g_c_a_b_c_i['address_build'] &&
                                        $from_company['address_apartment'] ==  $g_c_a_b_c_i['address_apartment'])
                                    {
                                        // Если есть адрес организация, то запрещаем добавление организации
                                        $from_company['id'] = $g_c_a_b_c_i['id'];
                                        $flag_to_add_CA = false;
                                        break;
                                    }
                                }
                            }
                            if($flag_to_add_CA)
                            {
                                $from_company['created_datetime'] = $date_time->format('Y-m-d H:i:s');
                                $from_company['created_user_id'] = $user_id;
                                $from_company['id'] = Company::addCompanyAddress($is_c_in_db, $from_company);
                            }

                        }

                        if($is_c_in_db == false)
                        {
                            $from_company['created_datetime'] = $date_time->format('Y-m-d H:i:s');
                            $from_company['created_user_id'] = $user_id;
                            // Добавляем новую компанию
                            $new_company = Company::addCompany($from_company);
                            if($new_company != false)
                            {
                                $from_company['created_datetime'] = $date_time->format('Y-m-d H:i:s');
                                $from_company['created_user_id'] = $user_id;
                                // Добавляем адрес для компании
                                $from_company['id'] = Company::addCompanyAddress($new_company, $from_company);
                                if($from_company['id'] == false)
                                {
                                    $errors['from_c'] = 'Не удалось добавить организацию в БД (О)';
                                }
                            }
                        }
                    }

                    $xml_to = Doka::GetXMLNode($xml_list, 'To');
                    if($xml_to == false)
                    {
                        $errors['tag_to'] = 'Не удалось найти тэг To';
                    }
                    $to_company['name'] = Doka::GetXMLValue($xml_to, 'Name');
                    $to_company['full_name'] = Doka::GetXMLValue($xml_to, 'FullName');
                    $to_company['key_field'] = Doka::GetXMLValue($xml_to, 'INN');
                    $to_company['address_country'] = Doka::GetXMLValue($xml_to, 'AddressCountry');
                    $to_company['address_zip'] = Doka::GetXMLValue($xml_to, 'AddressZip');
                    $to_company['address_region'] = Doka::GetXMLValue($xml_to, 'AddressRegion');
                    $to_company['address_area'] = Doka::GetXMLValue($xml_to, 'AddressArea');
                    $to_company['address_city'] = Doka::GetXMLValue($xml_to, 'AddressCity');
                    $to_company['address_town'] = Doka::GetXMLValue($xml_to, 'AddressTown');
                    $to_company['address_street'] = Doka::GetXMLValue($xml_to, 'AddressStreet');
                    $to_company['address_home'] = Doka::GetXMLValue($xml_to, 'AddressHome');
                    $to_company['address_case'] = Doka::GetXMLValue($xml_to, 'AddressCase');
                    $to_company['address_build'] = Doka::GetXMLValue($xml_to, 'AddressBuild');
                    $to_company['address_apartment'] = Doka::GetXMLValue($xml_to, 'AddressApatment');

                    $notification = null; // Информация об уведомлении
                    $notification_name = ''; // Полное наименование уведомления
                    $notification_control = true; // Контроль добавления строки к уведомлению
                    $notification_id = 0; // ID уведомления

                    $notification_name = $to_company['address_country'] . '|||' . $to_company['address_region'];
                    if ($notification_control)
                    {
                        if ($to_company['address_area'] != null)
                        {
                            $notification_name .= '|||'.$to_company['address_area'];
                            $notification_control = false;
                        }
                    }

                    if ($notification_control)
                    {
                        if ($to_company['address_city'] != null)
                        {
                            $notification_name .= '|||' .$to_company['address_city'];
                            $notification_control = false;
                        }
                    }

                    if ($notification_control)
                    {
                        if ($to_company['address_town'] != null)
                        {
                            $notification_name .= '|||'.$to_company['address_town'];
                            $notification_control = false;
                        }
                    }

                    if ($notification_control)
                    {

                        $notification_name .= '|||'.'ИзДокиПришлоПустоеПоле';
                        $notification_control = false;
                    }

                    $check_notification = Notification::checkNotification($notification_name);


                    if (!$check_notification)
                    {
                        $notification['created_datetime'] = $date_time->format('Y-m-d H:i:s');
                        $notification['created_user_id'] = $user_id;
                        $notification['name'] = $notification_name;
                        $notification_id = Notification::addNotification($notification);
                        if (!$notification_id)
                        {
                            $notification_id = 0;
                        }
                    }
                    else
                    {
                        $notification_id = $check_notification;
                    }

                    $to_company['notification_id'] = $notification_id;

                    $flag_to_add_CA = true;
                    if(!isset($errors['tag_to']))
                    {
                        $is_c_in_db = Company::checkKeyFieldExists($to_company['key_field']);

                        // Проверка организации по ключевому полю
                        if($is_c_in_db != false)
                        {
                            // Если есть такая организация, то смотрим имеется ли такой адрес
                            $get_company_address_by_company_id = Company::getCompanyAddressByCompany($is_c_in_db, 0, 1);

                            if($get_company_address_by_company_id != null)
                            {
                                foreach($get_company_address_by_company_id as $g_c_a_b_c_i)
                                {
                                    if($to_company['address_country'] == $g_c_a_b_c_i['address_country'] &&
                                        $to_company['address_zip'] == $g_c_a_b_c_i['address_zip'] &&
                                        $to_company['address_region'] ==  $g_c_a_b_c_i['address_region'] &&
                                        $to_company['address_area'] ==  $g_c_a_b_c_i['address_area'] &&
                                        $to_company['address_city'] ==  $g_c_a_b_c_i['address_city'] &&
                                        $to_company['address_town'] ==  $g_c_a_b_c_i['address_town'] &&
                                        $to_company['address_street'] ==  $g_c_a_b_c_i['address_street'] &&
                                        $to_company['address_home'] ==  $g_c_a_b_c_i['address_home'] &&
                                        $to_company['address_case'] ==  $g_c_a_b_c_i['address_case'] &&
                                        $to_company['address_build'] ==  $g_c_a_b_c_i['address_build'] &&
                                        $to_company['address_apartment'] ==  $g_c_a_b_c_i['address_apartment'])
                                    {
                                        // Если есть адрес организация, то запрещаем добавление организации
                                        $to_company['id'] = $g_c_a_b_c_i['id'];
                                        $flag_to_add_CA = false;
                                        break;
                                    }
                                }
                            }

                            if($flag_to_add_CA)
                            {
                                $to_company['created_datetime'] = $date_time->format('Y-m-d H:i:s');
                                $to_company['created_user_id'] = $user_id;
                                $to_company['id'] = Company::addCompanyAddress($is_c_in_db, $to_company);
                            }

                        }

                        if($is_c_in_db == false)
                        {
                            $to_company['created_datetime'] = $date_time->format('Y-m-d H:i:s');
                            $to_company['created_user_id'] = $user_id;
                            // Добавляем новую компанию
                            $new_company = Company::addCompany($to_company);
                            if($new_company != false)
                            {
                                $to_company['created_datetime'] = $date_time->format('Y-m-d H:i:s');
                                $to_company['created_user_id'] = $user_id;
                                // Добавляем адрес для компании
                                $to_company['id'] = Company::addCompanyAddress($new_company, $to_company);
                                if($to_company['id'] == false)
                                {
                                    $errors['to_c'] = 'Не удалось добавить организацию в БД (П)';
                                }
                            }
                        }
                    }

                    $xml_request_list = Doka::GetXMLNode($xml_list, 'RequestList');
                    if($xml_request_list == false)
                    {
                        $errors['tag_request'] = 'Не удалось найти тэг RequestList';
                    }
                    foreach($xml_request_list->getElementsByTagName('Request') as $xml_request)
                    {
                        if(Doka::GetXMLValue($xml_request, 'Number'))
                        {
                            $p_object[] = Doka::GetXMLValue($xml_request, 'Number');
                        }
                        else
                        {
                            $errors['tag_number'] = 'Не удалось найти тэг Number';
                        }
                    }

                }
            }
            else
            {
                $errors['doka_error'] = 'АИС "ДОКА" не доступна. Обратитесь к администратору за помощью';
            }


        }
        if(isset($_POST['yes']))
        {
            Company::outCompanyFromMemory(FROM_COMPANY);
            if(!isset($errors['from_c']))
            {
                // Запоминаем отправителя
                Company::memorizeCompany($from_company['id'], FROM_COMPANY);
            }
            Company::outCompanyFromMemory(TO_COMPANY);
            if(!isset($errors['to_c']))
            {
                // Запоминаем получателя
                Company::memorizeCompany($to_company['id'], TO_COMPANY);
            }
            Package::outPackage();
            if(!isset($errors['tag_list']))
            {
                // Запоминаем посылку
                Package::memorizePackage($package);
            }
            Package::outPackageObjects();
            if(!isset($errors['tag_number']))
            {
                // Запоминаем дела
                Package::memorizePackageObjects($p_object);
            }
            header('Location: /site/choose');
        }
        if(isset($_POST['no']))
        {
            Company::outCompanyFromMemory(FROM_COMPANY);
            Company::outCompanyFromMemory(TO_COMPANY);
            Package::outPackage();
            Package::outPackageObjects();
            $errors = false;
            $from_company = null; // Откуда
            $to_company = null; // Куда
            $p_object = null; // Объекты посылки
            if(isset($_POST['package']))
            {
                unset($_POST['package']);
            }
            $package = null;
        }



        if($is_create)
        {
            require_once ROOT . '/views/doka/package.php';
            return true;
        }
        else
        {
            header('Location: /site/error');
        }
    }
}