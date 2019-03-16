<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace app\helpers;

class MyFormat{
    
    const str_max_length    = 30;
    const date_format       = "d/m/Y";
    const datetime_format   = "d/m/Y H:i";
    
    public static function formatCurrency($price){
        return number_format($price , 0 , '.' , ',') . '₫';
    }
    
    public static function shortenName($name){
        return (strlen($name) > self::str_max_length) ? mb_substr($name, 0, self::str_max_length,"utf-8")."..." : $name;
    }
    
    /*
     * format date to d/m/Y
     */
    public static function formatDate($date){
        return empty($date) ? "" : date(self::date_format, strtotime($date));
    }
    
    /*
     * format date time to d/m/Y
     */
    public static function formatDatetime($datetime){
        return date(self::datetime_format, strtotime($datetime));
    }
    
    /*
     * create slug from string ("Đồng hồ [chính hãng]" => "dong-ho-chinh-hang")
     */
    public static function slugify($str){
        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
        $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
        $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
        $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
        $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
        $str = preg_replace("/(đ)/", 'd', $str);
        $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
        $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
        $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
        $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
        $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
        $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
        $str = preg_replace("/(Đ)/", 'D', $str);
        $str = str_replace(" ", "-", str_replace("&*#39;","",$str));
        $str = strtolower($str);
        return preg_replace('/[^A-Za-z0-9\-]/', '', $str);
    } 

    /**
     *  cộng hoặc trừ thêm ngày
     * @param: $date: 2013-05-26
     * @param: $day_add: 16
     * @param: $operator: + or - default is +
     * @param: $amount_of_days: day, month, year, week default is "day"
     * @param: $format: default "Y-m-d"
     * @return: $format: default "Y-m-d"
     */
    public static function modifyDays($date, $day_add, $operator = '+', $amount_of_days = 'day', $format = 'Y-m-d')
    {
        if ($day_add == 0 || empty($day_add)) {
            return $date;
        }
        return date($format, strtotime($operator.$day_add." ".$amount_of_days));
    }
    
}

