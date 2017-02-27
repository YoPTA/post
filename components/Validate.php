<?php

class Validate
{
    /*
     * Проверяет является ли значение датой
     * @var $value string - дата
     * @var $format string - формат даты
     * return boolean
     */
    public function checkDate($value, $format = 'Y-m-d')
    {
        if (strlen($value) == 10 && $format == 'Y-m-d')
        {
            $segments = explode('-', $value);
            if (count($segments) == 3)
            {
                if (($segments[2] > 0 && $segments[2] < 32) &&
                    ($segments[1] > 0 && $segments[1] < 13) &&
                    ($segments[0] > 999 && $segments[0] < 10000))
                {
                    return true;
                }
            }
        }
        return false;
    }

    /*
     * Проверка пароля
     * @var $password string - пароль
     * return boolean
     */
    public static function checkPassword($password)
    {
        if(strlen($password) >= 6 && strlen($password)<=20)
        {
            return true;
        }
        return false;
    }

    /*
     * Проверяет email
     * @var $email string - email
     * return boolean
     */
    public static function checkEmail($email)
    {
        if(filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            return true;
        }
        return false;
    }

    /*
     * Проверка строки на то, чтобы значение было не меньше значения
     * @var $str string - строка, которую необходимо проверить
     * @var $value integer - ограничение символов
     * return boolean
     */
    public static function checkStrEqualLength($str, $value)
    {
        if (strlen($str) == $value)
        {
            return true;
        }
        return false;
    }

    /*
     * Првоерка строки на не превышение допустимого количества символов
     * @var $str string - строка, которую необходимо проверить
     * @var $value integer - ограничение символов
     * return boolean
     */
    public static function checkStr($str, $value)
    {
        if(strlen($str) <= $value && !empty($str))
        {
            return true;
        }
        return false;
    }

    /*
     * Првоерка текстового поля
     * @var $str string - строка, которую необходимо проверить
     * return boolean
     */
    public static function checkStrCanEmpty($str, $value)
    {
        if(strlen($str) <= $value)
        {
            return true;
        }
        return false;
    }

    /*
     * Проверка целого числа
     * @var $value int - числовое значение
     * return boolean
     */
    public static function checkNumber($value)
    {
        if(is_numeric($value) || $value == 0)
        {
            return true;
        }
        return false;
    }

    /*
     * Проверка времени на формат 00:00:00
     * @var $str string - время
     * return boolean
     */
    public static function checkTime($str)
    {
        $timeSegments = explode(':', $str);
        if(count($timeSegments) == 3)
        {
            if(((integer)$timeSegments[0] >= 0 || (integer)$timeSegments[1] >= 0 || (integer)$timeSegments[2] >= 0))
            {
                return true;
            }
        }
        return false;
    }

    /*
     * Првоерка целого числа, разрешить 0
     * @var $value int - числовое значение
     * return boolean
     */
    public static function checkNumberCanZero($value)
    {
        if(is_int($value) || $value == 0)
        {
            return true;
        }
        return false;
    }

    /*
     * Проверяет доступен ли URL
     * @var $url string - URL
     * return boolean
     */
    public function checkUrl($url)
    {
        ini_set('default_socket_timeout', '10');
        $fp = fopen($url, "r");
        $res = fread($fp, 500);
        fclose($fp);
        if (strlen($res) > 0)
            return true;
        else
            return false;
    }

    /*
     * Возвращает строку с переводом первый символ в верхний регистр
     * @var $string string - строка, которую нужно преобразовать
     * @var $enc string - кодировка
     */
    private function my_ucfirst($string, $enc = DEFAULT_ENCODING_LOWERCASE)
    {
        return mb_strtoupper(mb_substr($string, 0, 1, $enc), $enc) .
        mb_substr($string, 1, mb_strlen($string, $enc), $enc);
    }

    /*
     * Возвращает строку с переводом всех первых символов в верхний регистр
     * @var $string string - строка, которую нужно преобразовать
     */
    public function my_ucwords($string)
    {
        $segments = explode(" ", $string);
        $newString = "";
        foreach($segments as $key => $value)
        {
            $newString .= " " ./* Validate::my_ucfirst($value, DEFAULT_ENCODING_LOWERCASE);//*/$this->my_ucfirst(mb_strtolower($value, DEFAULT_ENCODING_LOWERCASE));
        }
        return $newString;
    }

    /*
     * Возвращает строку, обрезав ее и вставив символы, из параметров в указанной кодировке
     * @var $str string - строка
     * @var $value int - ограничитель
     * @var $end_line string - концовка строки, в случае превышения
     * @var $enc string - кодировка
     */
    public function my_strCut($str, $value, $end_line, $enc = DEFAULT_ENCODING_LOWERCASE)
    {
        if (strlen($str) > $value)
        {
            $str = mb_substr($str, 0, $value-strlen($end_line), $enc);
            $str .= $end_line;
        }
        return $str;
    }
}