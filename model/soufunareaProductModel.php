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

    public function CategoryToArray()
    {
        $names = $this->getCategoryItemName();
        $urls = $this->getCategoryItemUrL();
        $city = $this->getCategoryItemOprice();
        $baseurl = $this->getCategoryItemUrL();
        $result = array();
        foreach($names as $k=>$v)
        {
            if($v != "不限")
            {
                $result[] = array('name'=>$v,'url'=>$urls[$k],'city'=>$city,'baseurl'=>$baseurl);
            }
        }
        return $result;
    }

    public function  getPrice()
    {
        $arr = parent::getPrice();
        $result = array();
        foreach($arr as $k=>$v)
        {
            if($v != '不限')
            {
                if($v)
                    $result[] = $v;
            }
        }
        $arr2 = parent::getCharacters();
        foreach($arr2 as $kk=>$vv)
        {
            if($vv)
                $result[] = $vv;
        }

        return $result;
    }

    public function  getOriginPrice()
    {
        $arr = parent::getOriginPrice();
        $result = array();
        foreach($arr as $k=>$v)
        {
            if($v != '不限')
            {
                $result[] = $v;
            }
        }
        return $result;
    }

    public function  getTitle()
    {
        $arr = parent::getTitle();
        $result = array();
        foreach($arr as $k=>$v)
        {
            if($v != '不限')
            {
                $result[] = $v;
            }
        }
        return $result;
    }



//    public function getCharacters()
//    {
//
//            $filter = $this->_config[\elements::ITEM_CHARACTERS];
//
//            $events = $this->_xpath->query("*");
//        print_r($events);exit;
//
//        return $this->_promotion;
//    }


}
