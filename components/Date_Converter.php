<?php

/*
 * ��������������� �����, ��� �������������� ���
 */
class Date_Converter
{
    /*
     * ����������� ���� � ������ (����-��-�� � ��.��.����)
     * @var $date_to_convert date - ����
     * return string
     */
    public function dateToString($date_to_convert)
    {
        $segments = explode('-', $date_to_convert);
        if (count($segments) == 3)
        {
            return $segments[2].'.'.$segments[1].'.'.$segments[0];
        }
        return '00.00.0000';
    }

    /*
     * ����������� ������ � ���� (��.��.���� � ����-��-��)
     * @var $string_to_convert string - ����
     * return string OR boolean
     */
    public function stringToDate($string_to_convert)
    {
        $segments = explode('.', $string_to_convert);
        if (count($segments) == 3)
        {
            if (strlen($segments[0]) != 2 || (int)$segments[0] > 31 || $segments[0] < 1)
            {
                return false;
            }

            if (strlen($segments[1]) != 2 || $segments[1] > 12 || $segments[1] < 1)
            {
                return false;
            }

            if (strlen($segments[2]) != 4 || $segments < 1000)
            {
                return false;
            }
            return $segments[2].'-'.$segments[1].'-'.$segments[0];
        }
        return false;
    }
}