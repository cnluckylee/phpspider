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
class soufunzflistProductModel extends productXModel {

    public function getCategoryItemArea()
    {
        $filter = $this->_config[\elements::CATEGORY_ITEM_DPRICE];
        $nodes = $this->_xpath->query($filter);
        $this->_category_item_area = array();
        $filter2 = $this->_config[\elements::CATEGORY_ITEM_AREA];
        foreach ($nodes as $k=>$node) {
            foreach ($this->_xpath->query($filter2, $node) as $child) {
                $tmp = trim(str_replace(array(" ","\r\n"),array(""," "),$child->nodeValue));
                $arr = explode(" ",$tmp);
                $this->_category_item_area[$k] = trim($arr[0]);
            }
        }
        return $this->_category_item_area;
    }

    public function getCategoryItemDprice()
    {
        $filter = $this->_config[\elements::CATEGORY_ITEM_DPRICE];
        $nodes = $this->_xpath->query($filter);
        $this->_category_item_dprice = array();

        foreach ($nodes as $k=>$node) {
            foreach ($this->_xpath->query('.//dl/dt/p[2]/text()', $node) as $child) {
                $tmp = trim(str_replace(array("   ","\r\n"),array(""," "),$child->nodeValue));
                $arr = explode(" ",trim($child->nodeValue));
                $this->_category_item_dprice[$k] = $arr[0];
            }
        }
        return $this->_category_item_dprice;
    }

    public function getCategoryItemOprice()
    {
        $arrs = parent::getCategoryItemOprice();
        $this->_category_item_oprice = array();
        foreach($arrs as $k=>$v)
        {
            $this->_category_item_oprice[$k] = str_replace(array(" ","\r\n"),"",$v);
        }
        return $this->_category_item_oprice;
    }

    public function getCategoryItemSale()
    {
        $arrs = parent::getCategoryItemSale();
        $this->_category_item_sale = array();
        foreach($arrs as $k=>$v)
        {
            $this->_category_item_sale[$k] = str_replace(array(" ","\r\n"),"",$v);
        }
        return $this->_category_item_sale;
    }

    public function getCategoryItemHot()
    {
        $arrs = parent::getCategoryItemHot();
        $this->_category_item_hot = array();
        foreach($arrs as $k=>$v)
        {
            $this->_category_item_hot[$k] = str_replace("更新时间：","",$v);
        }
        return $this->_category_item_hot;
    }
    public function  getDissatisfyCommentNumber()
    {
        $filter = $this->_config[\elements::ITEM_COMMENT_NUMBER_DISSATISFY];
        $nodes = $this->_xpath->query($filter);

        foreach($nodes as $node)
        {
            $str = $node->textContent;
            $arr = explode("发布时间：",$str);
            $arr = explode("(",$arr[1]);

            $num = intval($arr[1]);
            $c = '';
            if(strpos($arr[1],'天'))
            {
                $c = ' day';
            }else  if(strpos($arr[1],'时'))
            {
                $c = ' hour';
            }else  if(strpos($arr[1],'月'))
            {
                $c = ' month';
            }else  if(strpos($arr[1],'分'))
            {
                $c = ' minutes';
            }
            else  if(strpos($arr[1],'秒'))
            {
                $c = ' seconds';
            }

            $this->_dissatisfyCommentNumber = date('Y-m-d H:i:s',strtotime("-".$num.$c,strtotime($arr[0])));
        }
        return $this->_dissatisfyCommentNumber;
    }

    public function getTitle()
    {
        $str = parent::getTitle();
        $this->_title = trim(str_replace(array("\r\n"),"",$str));
        return $this->_title;
    }



    public function getPromotion()
    {
        $filter = $this->_config[\elements::ITEM_PROMOTION];
        $nodes = $this->_xpath->query($filter);
        $key = './/p[@class="type"]';
        $val = './/p[@class="info"]';
        foreach($nodes as $node)
        {
            foreach ($this->_xpath->query($key, $node) as $child) {
                $k = str_replace("：","",$child->nodeValue);
            }

            foreach ($this->_xpath->query($val, $node) as $child2) {
                $v= trim($child2->nodeValue);
            }
            $this->_promotion[$k]=$v;
        }
        return $this->_promotion;
    }

    public function getProductID()
    {
        $url = parent::getUrl();
        $arr = explode("_",$url);
        $this->_productID = trim(isset($arr[1])?$arr[1]:"");
        return $this->_productID;
    }
}
