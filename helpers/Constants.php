<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace app\helpers;

class Constants{
    const website_name = "Price Tracker";
    
    const ADMIN         = 1;
    const USER          = 2;
    const GUEST         = 3;
    
    const TIKI          = 1;
    const LAZADA        = 2;
    const SHOPEE        = 3;
    const SENDO         = 4;
    const AMAZON        = 5;
    const EBAY          = 6;
    
    public static $aWebsiteDomain = [
        self::TIKI      => 'tiki.vn',
        self::LAZADA    => 'lazada.vn',
        self::SHOPEE    => 'shopee.vn',
        self::SENDO     => 'sendo.vn',
        self::AMAZON    => 'amazon.com',
        self::EBAY      => 'ebay.com',
    ];
    
    public static $aWebsiteName = [
        self::TIKI      => 'Tiki',
        self::LAZADA    => 'Lazada',
        self::SHOPEE    => 'Shopee',
        self::SENDO     => 'Sendo',
        self::AMAZON    => 'Aazon',
        self::EBAY      => 'Ebay',
    ];
    
    public static $aLanguage = [
        'en' => 'English',
        'vi' => 'Vietnamese',
    ];
}

