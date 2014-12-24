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
class lejuareaProductModel extends productXModel {


    public function getCategoryItemSkuid()
    {
        $data = parent::getCategoryItemSkuid();
        if(!$data)
        {
            $this->_category_item_skuid = array();
            $filter = '//li[@class="site-topsearch"][1]/a/@href||2';
            $this->_category_item_skuid = $this->_getRegexpInfo($filter,$this->getContent());
        }
        return $this->_category_item_skuid;
    }

    public function getCategoryItemUrl()
    {
        $data = parent::getCategoryItemUrl();
        $sourceurl = parent::getUrl();

        $arr = parse_url($sourceurl);
        $baseurl = $arr['scheme']."://".$arr['host'];
        if(!$data)
        {
            $this->_category_item_url = array();
            $filter = '//li[@class="site-topsearch"][1]/a/@href||2';
            $tmp = $this->_getRegexpInfo($filter,$this->getContent());
            foreach($tmp as $k=>$v)
            {
                if(strstr($v,'javascript'))
                    $v = $sourceurl;
                if(!strstr($v,"http"))
                {
                    $this->_category_item_url[$k] = $baseurl.$v;
                }else{
                    $this->_category_item_url[$k] = $v;
                }
            }
        }else{
            foreach($data as $k=>$v)
            {
                if(strstr($v,'javascript'))
                    $v = $sourceurl;
                if(!strstr($v,"http"))
                {
                    $this->_category_item_url[$k] = $baseurl.$v;
                }else{
                    $this->_category_item_url[$k] = $v;
                }
            }
        }
        return $this->_category_item_url;
    }

    public function getCategoryItemName()
    {
        $data = parent::getCategoryItemName();
        if(!$data)
        {
            $this->_category_item_name = array();
            $filter = '//li[@class="site-topsearch"][1]/a/text()||2';
            $this->_category_item_name = $this->_getRegexpInfo($filter,$this->getContent());
        }
        return $this->_category_item_name;
    }

    public function getProductID()
    {
        $str = parent::getProductID();
        if(!$str)
        {
            $filter = '//li[@class="site-topsearch"]/a[@class="current"]/text()||1';
            $str = $this->_getRegexpInfo($filter,$this->getContent());
        }

        if(!$str)
        {
            $filter2 = '//div[@class="list_jingji"]//div[@class="item"]/a[@class="hover"]/text()||1';
            $str = $this->_getRegexpInfo($filter2,$this->getContent());
        }
        $sourceurl = parent::getUrl();
        $arr = parse_url($sourceurl);
        $baseurl = $arr['host'];
        $p = '/(\w+).esf/';
        preg_match($p,$baseurl,$out);
        $bs = isset($out[1])?$out[1]:"";
        $this->_productID = $bs.'-'.$str;
        return $this->_productID;
    }

    public function getPriceUrl()
    {
        $sourceurl = parent::getUrl();
        $arr = parse_url($sourceurl);
        $baseurl = $arr['scheme']."://".$arr['host'];
        $data = parent::getPriceUrl();
        if(!$data)
        {
            $this->_priceUrl = array();
            $filter = '//div[@class="site-child-arae"]/a/@href||2';
            $tmp = $this->_getRegexpInfo($filter,$this->getContent());
            foreach($tmp as $k=>$v)
            {
                if(strstr($v,'javascript'))
                    $v = $sourceurl;
                if(!strstr($v,"http"))
                {
                    $this->_priceUrl[$k] = $baseurl.$v;
                }else{
                    $this->_priceUrl[$k] = $v;
                }
            }
        }else{
            foreach($data as $k=>$v)
            {
                if(strstr($v,'javascript'))
                    $v = '/';
                if(!strstr($v,"http"))
                {
                    $this->_priceUrl[$k] = $baseurl.$v;
                }else{
                    $this->_priceUrl[$k] = $v;
                }
            }
        }
        return $this->_priceUrl;
    }

    public function getOriginPrice()
    {
        $data = parent::getOriginPrice();
        $sourceurl = parent::getUrl();
        $arr = parse_url($sourceurl);
        $baseurl = $arr['scheme']."://".$arr['host'];
        if(!$data)
        {
            $this->_originPrice = array();
            $filter = '//li[@class="site-topsearch"][2]/a/text()||2';
            $this->_originPrice = $this->_getRegexpInfo($filter,$this->getContent());
        }
        return $this->_originPrice;
    }


    public function getPrice()
    {
        $data = parent::getPrice();
        $sourceurl = parent::getUrl();
        $arr = parse_url($sourceurl);
        $baseurl = $arr['scheme']."://".$arr['host'];
        if(!$data)
        {
            $this->_price = array();
            $filter = '//li[@class="site-topsearch"][2]/a/@href||2';
            $tmp = $this->_getRegexpInfo($filter,$this->getContent());
            $sourceurl = parent::getUrl();
            foreach($tmp as $k=>$v)
            {
                if(strstr($v,'javascript'))
                    $v = $sourceurl;
                if(!strstr($v,"http"))
                {
                    $this->_price[$k] = $baseurl.$v;
                }else{
                    $this->_price[$k] = $v;
                }
            }
        }else{
            foreach($data as $k=>$v)
            {
                if(strstr($v,'javascript'))
                    $v = $sourceurl;
                if(!strstr($v,"http"))
                {
                    $this->_price[$k] = $baseurl.$v;
                }else{
                    $this->_price[$k] = $v;
                }
            }
        }
        return $this->_price;
    }

    public function getName()
    {
        $data = parent::getName();
        if(!$data)
        {
            $filter = '//li[@class="site-topsearch"][1]/a/text()||2';
            $this->_name = $this->_getRegexpInfo($filter,$this->getContent());
        }
        return $this->_name;
    }
}