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
class lejubrokerProductModel extends productXModel {

    public function getCommon()
    {

        $filter = $this->_config[\elements::CATEGORY_ITEM_DPRICE];
        $nodes = $this->_xpath->query($filter);
        $commons = $this->_config[\elements::COMMON];
        foreach ($nodes as $k=>$node) {
            foreach($commons as $key=>$filter)
            {
                $this->_common[$key] = str_replace(array("服务区域：","所在门店："),"",$this->_getRegexpInfo($filter, $this->getContent()));
            }
        }

        return $this->_common;
    }

    public function getCategoryItemArea()
    {
        $filter = $this->_config[\elements::CATEGORY_ITEM_DPRICE];
        $nodes = $this->_xpath->query($filter);
        $filter2 = $this->_config[\elements::CATEGORY_ITEM_AREA];
        $this->_category_item_area = array();
        foreach ($nodes as $k=>$node) {
            foreach ($this->_xpath->query($filter2, $node) as $child) {

                $this->_category_item_area[$k] = explode(" ",trim(str_replace('服务楼盘：','',$child->nodeValue)));
            }
        }
       return $this->_category_item_area;
    }

    public function getCategoryItemDprice()
    {
        return "";
    }
    public function getCategoryItemMprice()
    {
        $filter = $this->_config[\elements::CATEGORY_ITEM_DPRICE];
        $nodes = $this->_xpath->query($filter);
        $filter2 = $this->_config[\elements::CATEGORY_ITEM_MPRICE];
        $this->_category_item_dprice = array();
        foreach ($nodes as $k=>$node) {
            foreach ($this->_xpath->query($filter2, $node) as $child) {
                $this->_category_item_dprice[$k] =$child->nodeValue?1:0;
            }
        }
        return $this->_category_item_dprice;
    }

    public function getCategoryItemOPrice()
    {
        $filter = $this->_config[\elements::CATEGORY_ITEM_DPRICE];
        $nodes = $this->_xpath->query($filter);
        $filter2 = $this->_config[\elements::CATEGORY_ITEM_OPRICE];
        $this->_category_item_oprice = array();
        foreach ($nodes as $k=>$node) {
            foreach ($this->_xpath->query($filter2, $node) as $child) {
                $this->_category_item_oprice[$k] =$child->nodeValue?1:0;
            }
        }
        return $this->_category_item_oprice;
    }

    public function getCategoryItemHot()
    {
        $filter = $this->_config[\elements::CATEGORY_ITEM_DPRICE];
        $nodes = $this->_xpath->query($filter);
        $filter2 = $this->_config[\elements::CATEGORY_ITEM_HOT];
        $this->_category_item_hot = array();
        $p = '/出租房(\d+?)套/';
        foreach ($nodes as $k=>$node) {
            foreach ($this->_xpath->query($filter2, $node) as $child) {
                preg_match($p,$child->nodeValue,$out);
                $this->_category_item_hot[$k] =isset($out[1])?$out[1]:"";
            }
            if($this->_category_item_hot[$k] == "")
            {
                $filter3 = './/div[@class="hall_people_house_font"][4]/text()';
                foreach ($this->_xpath->query($filter3, $node) as $child2) {
                    preg_match($p,$child2->nodeValue,$out2);
                    $this->_category_item_hot[$k] =isset($out2[1])?$out2[1]:0;
                }
            }
        }
        return $this->_category_item_hot;
    }

    public function getCategoryItemSale()
    {
        $filter = $this->_config[\elements::CATEGORY_ITEM_DPRICE];
        $nodes = $this->_xpath->query($filter);
        $filter2 = $this->_config[\elements::CATEGORY_ITEM_SALE];
        $this->_category_item_sale = array();
        $p = '/二手房(\d+?)套/';
        foreach ($nodes as $k=>$node) {
            foreach ($this->_xpath->query($filter2, $node) as $child) {
                preg_match($p,$child->nodeValue,$out);
                $this->_category_item_sale[$k] =isset($out[1])?$out[1]:0;
            }
            if($this->_category_item_sale[$k] == "")
            {
                $filter3 = './/div[@class="hall_people_house_font"][4]/text()';
                foreach ($this->_xpath->query($filter3, $node) as $child2) {
                    preg_match($p,$child2->nodeValue,$out2);
                    $this->_category_item_sale[$k] =isset($out2[1])?$out2[1]:0;
                }
            }
        }
        return $this->_category_item_sale;
    }
    public  function getIsbnCode()
    {
        $str = parent::getIsbnCode();
        $this->_isbnCode = str_replace("创建时间：","",$str);
        return $this->_isbnCode;
    }
    public function getProductID()
    {
        $str = parent::getUrl();
        $p = '/tshop\/(\d+)-/';
        preg_match($p,$str,$out);
        $this->_productID = isset($out[1])?$out[1]:"";
        return $this->_productID;
    }
    public function getBaseUrl()
    {
        $str = parent::getUrl();
        $this->_baseurl = parent::getBaseUrl().'/agentshop/'.$this->getProductID().'-1-n';
        return $this->_baseurl;
    }
}
