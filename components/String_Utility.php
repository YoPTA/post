<?php


class String_Utility
{
    /*
     * Возвращает адрес по входному параметру
     * @var $parameter int - числовое значение, которое определяет в каком виде
     * вернуть адрес
     *
     * Возможные варианты $parameter:
     * 1 - Полный адрес
     * 2 - Без страны, почтового индекса и региона (области)
     *
     *
     * @var $address array() - массив с адресом
     * @var $address_prefix string - префикс перед адресом
     * return string
     */
    public function getAddressToView($parameter = 1, $address, $address_prefix = '')
    {
        $address_to_view = null;

        if ($parameter == 1)
        {
            $address_to_view = $this->insertToString($address_to_view, $address[$address_prefix.'ca_country'], 0);
            $address_to_view = $this->insertToString($address_to_view, $address[$address_prefix.'ca_region']);
            $address_to_view = $this->insertToString($address_to_view, $address[$address_prefix.'ca_area']);
            $address_to_view = $this->insertToString($address_to_view, $address[$address_prefix.'ca_city']);
            $address_to_view = $this->insertToString($address_to_view, $address[$address_prefix.'ca_town']);
            $address_to_view = $this->insertToString($address_to_view, $address[$address_prefix.'ca_street']);
            $address_to_view = $this->insertToString($address_to_view, $address[$address_prefix.'ca_home']);
            $address_to_view = $this->insertToString($address_to_view, $address[$address_prefix.'ca_case']);
            $address_to_view = $this->insertToString($address_to_view, $address[$address_prefix.'ca_build']);
            $address_to_view = $this->insertToString($address_to_view, $address[$address_prefix.'ca_apartment']);
        }
        if ($parameter == 2)
        {
            $address_to_view = $this->insertToString($address_to_view, $address[$address_prefix.'ca_area'], 0);
            $address_to_view = $this->insertToString($address_to_view, $address[$address_prefix.'ca_city']);
            $address_to_view = $this->insertToString($address_to_view, $address[$address_prefix.'ca_town']);
            $address_to_view = $this->insertToString($address_to_view, $address[$address_prefix.'ca_street']);
            $address_to_view = $this->insertToString($address_to_view, $address[$address_prefix.'ca_home']);
            $address_to_view = $this->insertToString($address_to_view, $address[$address_prefix.'ca_case']);
            $address_to_view = $this->insertToString($address_to_view, $address[$address_prefix.'ca_build']);
            $address_to_view = $this->insertToString($address_to_view, $address[$address_prefix.'ca_apartment']);
        }

        return $address_to_view;
    }

    /*
     * Возвращает строку, если она не пустая
     * @var $current_str string - текущая строка
     * @var $str string - строка, которую прибавляет к текущей, если она не пустая
     * @var $flag int - нужна ли запятая перед строкой (1 - да, 0 - нет)
     * return string
     */
    private function insertToString($current_str, $str, $flag = 1)
    {
        if ($str != null)
        {
            if ($flag == 1 && $current_str != null)
            {
                $str = ', '.$str;
            }
            return $current_str . $str;
        }
        return $current_str;
    }
}