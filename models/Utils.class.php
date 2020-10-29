<?php

final class Utils
{
    # Method that replace \n by <br> in any string
    public static function html_replace_enter_by_br($string)
    {
        return str_replace("\n", '<br>', $string);
    }

}