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
        $result = array();
        foreach($names as $k=>$v)
        {
            if($v != "不限")
            {
                $result[] = array('name'=>$v,'url'=>$urls[$k],'city'=>$city);
            }
        }
        return $result;
    }
}
