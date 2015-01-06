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
class lejuzflistProductModel extends productXModel {

    public  function  getProductID()
    {
        $str = parent::getUrl();
        $p = '/detail\/(\d+)/';
        preg_match($p,$str,$out);
        $this->_productID = isset($out[1])?$out[1]:"";
        return $this->_productID;
    }

    public function getItemCommon()
    {
        if (is_null($this->_itemcommon)) {
            $commons = $this->_config[\elements::ITEMCOMMON];
            foreach($commons as $key=>$filter)
            {
                $this->_itemcommon[$key] = str_replace(array("更新时间："),"",$this->_getRegexpInfo($filter, $this->getContent()));
            }
        }
        return $this->_itemcommon;
    }

    public function getPromotion()
    {
        $str = parent::getPromotion();
        $p = '/基本情况：<\/span>(.*)<\/div>/';
        preg_match($p,$this->_content,$out);
        $this->_promotion = isset($out[1])?$out[1]:"";
        $this->_promotion = $this->_promotion." ".$str;
        return $this->_promotion;
    }

    public  function getCharacters()
    {
        $p = '/(付：(.*)押：(.*))<\/div>/';
        preg_match($p,$this->_content,$out);
        if(isset($out[1]) && $out[1])
        {
            $str = str_replace(array(")","押","："),array(""," 押",":"),$out[1]);
            $this->_characters = $str;
            return $this->_characters;
        }
        return "";
    }
}