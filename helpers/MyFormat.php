<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace app\helpers;

class MyFormat{
    
    const str_max_length = 30;
    
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
        return date("d/m/Y", strtotime($date));
    }
    
    /*
     * format date time to d/m/Y
     */
    public static function formatDatetime($datetime){
        return date("d/m/Y H:i", strtotime($datetime));
    }

}

