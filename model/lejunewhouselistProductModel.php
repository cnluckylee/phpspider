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
class lejunewhouselistProductModel extends productXModel {

    public function getBarcode()
    {
        $str = parent::getBarcode();
        $this->_barcode = date('Y-m-d',strtotime("+".$str."second"));
        return  $this->_barcode;
    }

    public function getSales2()
    {
        $str = parent::getSales2();
        $this->_sales = 0;
        if(strstr($str,'e_ico6'))
            $this->_sales = 1;
        return  $this->_sales;
    }

    public function getSales()
    {
        $str = parent::getSales();
        if($str)
        {
            $p = '/(\d+)/';
            preg_match($p,$str,$out);

            $this->_sales = isset($out[1])?$out[1]:0;
        }
        return  $this->_sales;
    }

    public function getAllCommentNumber()
    {
        $str = parent::getAllCommentNumber();
        if($str)
        {
            $p = '/(\d+)/';
            preg_match($p,$str,$out);

            $this->_allCommentNumber = isset($out[1])?$out[1]:0;
        }
        return  $this->_allCommentNumber;
    }

    public function getCategoryItemHot()
    {
        $data = parent::getCategoryItemHot();
        $filter = './/span[@class="corner"]/@class';
        $this->_category_item_hot = array();
        foreach($data as $k=>$v)
        {
            foreach ($this->_xpath->query('.//dl/dt/p[2]/text()', $filter) as $child) {
                $this->_category_item_hot[$k] = $child?1:0;
            }
        }
        return $this->_category_item_hot;
    }

    public function getCategoryItemUrl()
    {
        $data = parent::getCategoryItemUrl();
//        $sourceurl = parent::getUrl();
//
//        $arr = parse_url($sourceurl);
//        $baseurl = $arr['scheme']."://".$arr['host'];
        $baseurl = 'http://www.leju.com/?mod=api_projectlist&type=foucs_equan&aid=';

        foreach($data as $k=>$v)
        {
            $tmp = parse_url($v);
            parse_str($tmp['query'],$parr);
            $this->_category_item_url[$k] = $baseurl.$parr['aid'].'&city='.$parr['hsite'].'&hid='.$parr['hid'];
        }
        return $this->_category_item_url;
    }
    public function getCategoryItemArea()
    {
        $str = parent::getCategoryItemArea();
        $data = parent::getCategoryItemUrL();
        $this->_category_item_area = array();
        foreach($data as $k=>$v)
        {
            $this->_category_item_area[$k] = $str;
        }
        return $this->_category_item_area;
    }

    public function getProductID()
    {
        $str = parent::getUrl();
        if($str)
        {
            $tmp = parse_url($str);
            parse_str($tmp['query'],$parr);
            $this->_productID = $parr['hid'];
        }
        return $this->_productID;
    }


/*
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
            $this->_productID = $this->_getRegexpInfo($filter,$this->getContent());
        }
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
*/
}