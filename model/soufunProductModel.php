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
class soufunProductModel extends productXModel {

    public function getCategoryItemDprice()
    {
        $filter = $this->_config[\elements::CATEGORY_ITEM_DPRICE];
        $nodes = $this->_xpath->query($filter);
        $this->_category_item_dprice = array();
        foreach ($nodes as $k=>$node) {
            foreach ($this->_xpath->query('.//p[@class="blue"][1]', $node) as $child) {
                $this->_category_item_dprice[$k] = explode(" ",trim(str_replace('服务商圈：','',$child->nodeValue)));
            }
        }
        return $this->_category_item_dprice;
    }

    public function getCategoryItemUrl()
    {
        $arr = parent::getCategoryItemUrL();
        $this->_category_item_url = array();
        foreach ($arr as $k=>$item) {
            $this->_category_item_url[$k] = str_replace(array('(ID:',')'),"",$item);
        }
        return $this->_category_item_url;
    }
    public function getCategoryItemHot()
    {

        $filter = $this->_config[\elements::CATEGORY_ITEM_DPRICE];
        $filter2 = $this->_config[\elements::CATEGORY_ITEM_HOT];
        $nodes = $this->_xpath->query($filter);
        $this->_category_item_hot = array();
        foreach ($nodes as $k=>$node) {
            foreach ($this->_xpath->query($filter2, $node) as $child) {
                $this->_category_item_hot[$k][] = $child->nodeValue;
            }
        }
        return $this->_category_item_hot;
    }

    public function getCategoryItemArea()
    {
        $filter = $this->_config[\elements::CATEGORY_ITEM_DPRICE];
        $filter2 = $this->_config[\elements::CATEGORY_ITEM_AREA];
        $nodes = $this->_xpath->query($filter);
        $this->_category_item_area = array();
        foreach ($nodes as $k=>$node) {
            foreach ($this->_xpath->query($filter2, $node) as $child) {
                $this->_category_item_area[$k] += 1;
            }
        }
        return $this->_category_item_area;
    }

    public function getCategoryItemReviews()
    {

        $filter = $this->_config[\elements::CATEGORY_ITEM_DPRICE];
        $filter2 = $this->_config[\elements::CATEGORY_ITEM_REVIEWS];
        $nodes = $this->_xpath->query($filter);
        $this->_category_item_reviews = array();
        foreach ($nodes as $k=>$node) {
            foreach ($this->_xpath->query($filter2, $node) as $child) {
                $this->_category_item_reviews[$k][] = $child->nodeValue;
            }
        }
        return $this->_category_item_reviews;
    }


    public function getCategoryItemSale()
    {

        $filter = $this->_config[\elements::CATEGORY_ITEM_SALE];
        $filter2 = './/div[@class="zhuanjia"]';
        $nodes = $this->_xpath->query($filter);
        $this->_category_item_sale = array();
        foreach ($nodes as $k=>$node) {
            foreach ($this->_xpath->query($filter2, $node) as $child) {
                $this->_category_item_sale[$k] = 'zhuanjia';
            }
        }
        return $this->_category_item_sale;
    }

    public function getCategoryItemSkuid()
    {
        $arr = parent::getCategoryItemSkuid();
        $this->_category_item_skuid = array();
        foreach($arr as $k=>$str)
        {
           $out =  explode("/",$str);
            $this->_category_item_skuid[$k] = $out[2];
        }
           return $this->_category_item_skuid;
    }

    public function CategoryToArray()
    {
        $arr = parent::CategoryToArray();
        $result = array();

        //数据重新瓶装

        foreach($arr[elements::CATEGORY_ITEM_SKUID] as $k=>$v)
        {
            $result[$k][elements::CATEGORY_ITEM_SKUID] = $v;
            if(isset($arr[elements::CATEGORY_ITEM_URL][$k]))
                $result[$k][elements::CATEGORY_ITEM_URL] = $arr[elements::CATEGORY_ITEM_URL][$k];
            if(isset($arr[elements::CATEGORY_ITEM_IMG][$k]))
                $result[$k][elements::CATEGORY_ITEM_IMG] = $arr[elements::CATEGORY_ITEM_IMG][$k];
            if(isset($arr[elements::CATEGORY_ITEM_NAME][$k]))
                $result[$k][elements::CATEGORY_ITEM_NAME] = $arr[elements::CATEGORY_ITEM_NAME][$k];
            if(isset($arr[elements::CATEGORY_ITEM_DPRICE][$k]))
                $result[$k][elements::CATEGORY_ITEM_DPRICE] = $arr[elements::CATEGORY_ITEM_DPRICE][$k];
            if(isset($arr[elements::CATEGORY_ITEM_OPRICE][$k]))
                $result[$k][elements::CATEGORY_ITEM_OPRICE] = $arr[elements::CATEGORY_ITEM_OPRICE][$k];
            if(isset($arr[elements::CATEGORY_ITEM_SALE][$k]))
                $result[$k][elements::CATEGORY_ITEM_SALE] = $arr[elements::CATEGORY_ITEM_SALE][$k];
            if(isset($arr[elements::CATEGORY_ITEM_HOT][$k]))
                $result[$k][elements::CATEGORY_ITEM_HOT] = $arr[elements::CATEGORY_ITEM_HOT][$k];
            if(isset($arr[elements::CATEGORY_ITEM_REVIEWS][$k]))
                $result[$k][elements::CATEGORY_ITEM_REVIEWS] = $arr[elements::CATEGORY_ITEM_REVIEWS][$k];
            if(isset($arr[elements::CATEGORY_ITEM_AREA][$k]))
                $result[$k][elements::CATEGORY_ITEM_AREA] = $arr[elements::CATEGORY_ITEM_AREA][$k];
        }
       return $result;
    }
    public function getSourceCategoryName()
    {
        $str = parent::getSourceCategoryName();
        $this->_sourceCategoryName = str_replace("：","",$str);
        return $this->_sourceCategoryName;
    }

    public function getOriginPrice()
    {
        $str = parent::getOriginPrice();
        $this->_originPrice = str_replace("：","",$str);
        return $this->_originPrice;
    }

    public function getPrice()
    {
        $str = parent::getPrice();
        $this->_price = str_replace("门店名称：","",$str);
        return $this->_price;
    }

    public function getSourceSellerID()
    {
        $str = parent::getSourceSellerID();
        $this->_sourceSellerID = str_replace("注册时间：","",$str);
        return $this->_sourceSellerID;
    }

    public function getPriceUrl()
    {
        $str = parent::getPriceUrl();
        $this->_priceUrl = trim(str_replace("服务热线：","",$str));
        return $this->_priceUrl;
    }

    public function getDescription()
    {
        $str = parent::getDescription();
        $this->_description = trim(str_replace("门店店长：","",$str));
        return $this->_description;
    }

    public function getIsbnCode()
    {
        $str = parent::getIsbnCode();
        $this->_isbnCode = trim(str_replace("开店时间：","",$str));
        return $this->_isbnCode;
    }

    public function getPromotion()
    {
        $str = parent::getPromotion();
        if(!$str)
        {
            $filter = '//ul[@class="cont02 mb10"]/li[4]/text()||1';
            $str =  $this->_getRegexpInfo($filter,$this->getContent());

        }
        $this->_promotion = trim(str_replace("注册时间：","",$str));
        return $this->_promotion;
    }
}
