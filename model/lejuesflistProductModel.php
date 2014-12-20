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
class lejuesflistProductModel extends productXModel {

    public function  getCategoryItemSale()
    {
        $nodes = parent::getCategoryItemSale();
        $this->_category_item_sale = array();
        foreach($nodes as $k=>$str)
        {
           $p = '/(\d+)/';
            preg_match($p,$str,$out);
            $num = intval($out[0]);
            $c = '';
            if(strpos($str,'天'))
            {
                $c = 'day';
            }else  if(strpos($str,'时'))
            {
                $c = 'hour';
            }else  if(strpos($str,'月'))
            {
                $c = 'month';
            }
            $this->_category_item_sale[$k] = date('Y-m-d H:i:s',strtotime("-".$num.$c));
        }
        return $this->_category_item_sale;
    }


    public  function getCategoryItemUrL()
    {

        $data = parent::getCategoryItemUrL();
        $this->_category_item_url = array();
        $baseurl = parent::getBaseUrl();
        foreach($data as $k=>$url)
        {
            $this->_category_item_url[$k] = $baseurl.$url;
        }
        return $this->_category_item_url;
    }

    public function getProductID()
    {
        $str = parent::getProductID();
        $p = '/房源编号：(\d+) /';
        preg_match($p,$str,$out);
        $this->_productID = isset($out[1])?$out[1]:"";
        return $this->_productID;
    }

    public function getPromotion()
    {
        $str = parent::getPromotion();
        $p = '/更新时间：(.*)/';
        preg_match($p,$str,$out);
        $this->_promotion = isset($out[1])?$out[1]:"";
        return $this->_promotion;
    }

    public function getSourceBrandName()
    {
        $str = parent::getSourceBrandName();
        $arr = explode("（",$str);
        $this->_sourceBrandName = isset($arr[0])?$arr[0]:"";
        return $this->_sourceBrandName;
    }
}
