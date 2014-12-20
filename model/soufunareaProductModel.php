<?php
/**
 * Created by PhpStorm.
 * User: Dream 
 * Date: 14-5-4
 * Time: 下午5:39
 */

//namespace model;


/**
 * product model for jin dong website.
 *
 * @package model
 */
class soufunareaProductModel extends productXModel {

    public function  getBaseUrl()
    {
        $baseurl = parent::getBaseUrl();
        $this->_baseurl= str_replace("/links.htm","",$baseurl);
        return $this->_baseurl;
    }

    public  function getCategoryItemUrL()
    {
        $sourceurl = parent::getUrl();
        $data = parent::getCategoryItemUrL();
        $arr = parse_url($sourceurl);
        $baseurl = $arr['scheme']."://".$arr['host'];
        $this->_category_item_url = array();
        foreach($data as $k=>$v)
        {
            $url = trim($v);
            $this->_category_item_url[$k] = $baseurl.$url;
        }
        return  $this->_category_item_url;
    }

    public function getPriceUrl()
    {
        $sourceurl = parent::getUrl();
        $data = parent::getPriceUrl();
        $arr = parse_url($sourceurl);
        $baseurl = $arr['scheme']."://".$arr['host'];
        $this->_priceUrl = array();
        foreach($data as $k=>$v)
        {
            $url = trim($v);
            $url = str_replace("-i31-j310/","-j310-i31/",$url);
            $this->_priceUrl[$k] = $baseurl.$url;
        }
        return  $this->_priceUrl;
    }

}
