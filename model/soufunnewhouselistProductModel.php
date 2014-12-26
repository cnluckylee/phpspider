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
class soufunnewhouselistProductModel extends productXModel {

    public function getCategoryItemDprice()
    {
        $filter = $this->_config[\elements::CATEGORY_ITEM_DPRICE];

        $nodes = $this->_xpath->query($filter);
        $address = $this->_config[\elements::CATEGORY_ITEM_AREA];
        $tags = './/p[@class="sf_status"]/a/text()';
        $this->_category_item_area = array();
        $this->_category_item_dprice = array();
        foreach ($nodes as $k=>$node) {

            //address
            foreach ($this->_xpath->query($address, $node) as $child) {
                if($child->nodeValue)
                    $this->_category_item_area[$k] = $child->nodeValue;
            }
            if(!$this->_category_item_area[$k]){
                $address2 = './/div[@class="sclist_con fl ml15"]/p[1]/text()';
                $i=1;
                foreach ($this->_xpath->query($address2, $node) as $child) {
                    if(strlen($child->nodeValue)>0 && $i==1){
                        $this->_category_item_area[$k] = $child->nodeValue;
                        $i++;
                    }
                }
            }
            if(!$this->_category_item_area[$k]){
                $address3 = './/li[2]/font/@title';
                foreach ($this->_xpath->query($address3, $node) as $child) {
                    if(strlen($child->nodeValue)>0 && $i==1){
                        $this->_category_item_area[$k] = $child->nodeValue;
                    }
                }
            }

            //TAGS
            foreach ($this->_xpath->query($tags, $node) as $child) {

                if($child->nodeValue)
                    $this->_category_item_dprice[$k][] = $child->nodeValue;
            }
        }

        return $this->_category_item_dprice;
    }

    public function getCategoryCommon()
    {
        $data = parent::getCategoryItemOprice();
        $this->_categorycommon = array();
        if(!$data){
            $filter = '//span[@class="shoucang"]/@onclick||2';
            $data = $this->_getRegexpInfo($filter,$this->getContent());
        }
        foreach($data as $k=>$nodes)
        {
            $str = str_replace(array("PostSelect(",");","'"),"",$nodes);
            $arr = explode(",",$str);
            if($arr)
            {
                $this->_categorycommon['Price'][$k] = $arr[5] . $arr[6];
                $this->_categorycommon['Address'][$k] = $arr[3];
                $this->_categorycommon['Name'][$k] = $arr[2];
                $this->_categorycommon['Num'][$k] = $arr[1];
                $this->_categorycommon['Category_Item_Url'][$k] = intval($arr[1]);
                $this->_categorycommon['City'][$k] = $arr[4];
//                $this->_categorycommon['Category_Item_Url'][$k] = $arr[8];
            }
        }

        return  $this->_categorycommon;
    }
    public function getCategoryItemUrL()
    {
        $str = parent::getCategoryItemUrL();
        if(!$str)
        {
           $this->_category_item_skuid = array();
           $filter = '//strong[@class="f14px"]/a/@href||2';
            $this->_category_item_skuid = $this->_category_item_url = $this->_getRegexpInfo($filter,$this->getContent());
        }
        return $this->_category_item_url;
    }
    public function getCategoryItemName()
    {
        $data = parent::getCategoryItemName();
        if(!$data)
        {
            $this->_category_item_name = array();
            $filter = '//strong[@class="f14px"]/a/text()||2';
            $this->_category_item_name = $this->_getRegexpInfo($filter,$this->getContent());
        }
        return $this->_category_item_name;
    }
    /*
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


*/

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