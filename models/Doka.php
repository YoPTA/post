<?php

class Doka
{
    public static function GetXMLNode(&$src, $name)
    {
        $result = false;

        if (!is_object($src))
        {
            return $result;
        }

        foreach ($src->getElementsByTagName($name) as $element)
        {
            $result = $element;
            break;
        }
        return $result;
    }

    public static function GetXMLValue(&$src, $name)
    {
        $result = false;

        if (!is_object($src))
        {
            return $result;
        }

        if ($name == null)
        {
            $node = $src;
        }
        else
        {
            $node = self::GetXMLNode($src, $name);
            if ($node == false)
            {
                return $result;
            }
        }

        $result = $node->nodeValue;
        return $result;
    }


}