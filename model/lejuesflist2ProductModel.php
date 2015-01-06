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
class lejuesflist2ProductModel extends productXModel {

    public  function getImageUrl()
    {
        $str = parent::getImageUrl();
        if($str){
            $this->_category_item_url = str_replace(" ","",$str);
        }else{
            $filter = '//span[@class="telbg"]/strong/text()||1';

            $this->_category_item_img = $this->_getRegexpInfo($filter,$this->getContent());
        }
        return $this->_category_item_url;
    }


    public function getSourceSellerID()
    {
        if(is_null($this->_sourceSellerID))
        {
            $str = parent::getSourceSellerID();
            $p = '/com\/(.*)\/shop\/(\d+)/';
            preg_match($p,$str,$out);
            if(isset($out[1]) && isset($out[2]) &&  $out[2] && $out[1])
                $this->_sourceSellerID = $out[1].'-'.$out[2];
        }
        return $this->_sourceSellerID;
    }

    public function getProductID()
    {
        if(is_null($this->_productID))
        {
            $this->_productID = parent::getUrl();
        }
        return $this->_productID;
    }

    public function getPromotion()
    {
        if(is_null($this->_promotion))
        {
            $filter = $this->_config[\elements::ITEM_PROMOTION];
            $nodes = $this->_xpath->query($filter);
            $key1 = './/td[1]/text()';
            $key2 = './/td[2]/text()';
            $key3 = './/td[3]/text()';
            $key4 = './/td[4]/text()';
            $val1 = $val2 = $val3 = $val4 = array();
            $filter2 = './/a/text()';
            foreach ($nodes as $k=>$node) {
                //address
                foreach ($this->_xpath->query($key1, $node) as $child) {
                    if($child->nodeValue)
                        $val1[] = str_replace(array(" ","　　","："),"",$child->nodeValue);
                }

                foreach ($this->_xpath->query($key2, $node) as $child2) {
                    if($child2->nodeValue)
                        $val2[] = $child2->nodeValue;
                }

                foreach ($this->_xpath->query($key3, $node) as $child3) {
                    if($child3->nodeValue)
                        $val1[] = str_replace(array(" ","："),"",$child3->nodeValue);
                }
                foreach ($this->_xpath->query($key4, $node) as $child4) {
                    if($child4->nodeValue)
                        $val2[] = $child4->nodeValue;
                }
            }
            unset($val1[0],$val1[1],$val2[0],$val2[1],$val2[2]);
            $this->_promotion = array_combine($val1,$val2);
        }
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
