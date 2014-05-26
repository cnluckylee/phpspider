<?php
/**
 * Created by PhpStorm.
 * User: Dream <dream_liu@wochacha.com>
 * Date: 14-5-4
 * Time: 下午1:15
 */

//namespace config;

/**
 * factory class for spider config.
 *
 * you should name your spider config file as nameSpider.php
 * @example jd spider config file name should be jdSpider.php
 *
 * @package config
 */
class spiderConfigFactory {

    /**
     * get config array by spider name
     *
     * @param $spider spider name eg 'jd'
     * @return array
     */
    public static function getConfig($spider){
        $configPath = '/spider/'.$spider.'Spider.php';
        require($configPath);
        if ($siteconfig){
            return $siteconfig;
        }else{
            return null;
        }
    }

} 