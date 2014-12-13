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
class soufunesflistProductModel extends productXModel {
    public function getCategoryItemArea()
    {
        $str = parent::getCategoryItemArea();
        $this->_category_item_area = str_replace("/links.htm","",$str);
        return $this->_category_item_area;
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

    public function getCategoryItemDprice()
    {
        $filter = $this->_config[\elements::CATEGORY_ITEM_DPRICE];
        $nodes = $this->_xpath->query($filter);
        $this->_category_item_dprice = array();
        foreach ($nodes as $k=>$node) {
            foreach ($this->_xpath->query('.//dl/dt/p[2]/text()', $node) as $child) {
                $this->_category_item_dprice[$k] = trim(str_replace(array(" ","\r\n"),array(""," "),$child->nodeValue));
            }
        }
        return $this->_category_item_dprice;
    }

    public function getCategoryItemUrl()
    {
        $arrs = parent::getCategoryItemUrl();
        $baseurl = trim($this->getCategoryItemArea());
        $this->_category_item_url = array();
        foreach($arrs as $k=>$v)
        {
            $this->_category_item_url[$k] = $baseurl.trim($v);
        }
        return $this->_category_item_url;
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

        public function CategoryToArray()
        {
            $arr = parent::CategoryToArray();
            $result = array();
            //数据重新瓶装
            foreach($arr[elements::CATEGORY_ITEM_NAME] as $k=>$v)
            {
                $result[$k][elements::CATEGORY_ITEM_NAME] = $v;
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

    public function  getAllCommentNumber()
    {

        $filter = $this->_config[\elements::ITEM_COMMENT_NUMBER_ALL];
        $nodes = $this->_xpath->query($filter);

        foreach($nodes as $node)
        {
            $str = $node->textContent;
            $arr = explode("发布时间：",$str);
            $arr = explode("(",$arr[1]);
            $this->_allCommentNumber = date('Y-m-d H:i:s',strtotime($arr[0]));
        }
        return $this->_allCommentNumber;
    }

    public function  getDissatisfyCommentNumber()
    {
        $filter = $this->_config[\elements::ITEM_COMMENT_NUMBER_ALL];
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
                $c = 'day';
            }else  if(strpos($arr[1],'时'))
            {
                $c = 'hour';
            }else  if(strpos($arr[1],'月'))
            {
                $c = 'month';
            }
            $this->_dissatisfyCommentNumber = date('Y-m-d H:i:s',strtotime("-".$num.$c));
        }

        return $this->_dissatisfyCommentNumber;
    }

    public function  getGeneralCommentNumber()
    {
        $filter = $this->_config[\elements::ITEM_COMMENT_NUMBER_ALL];
        $nodes = $this->_xpath->query($filter);
        foreach($nodes as $node)
        {
            $str = $node->textContent;
            $arr = explode("发布时间：",$str);
            $this->_generalCommentNumber = $arr[1];
        }
        return $this->_generalCommentNumber;
    }

    public function getTitle()
    {
        $str = parent::getTitle();
        $this->_title = trim(str_replace(array("\r\n"),"",$str));
        return $this->_title;
    }

    public function getMPrice()
    {

        if (is_null($this->_mprice)) {
            $filter = $this->_config[\elements::ITEM_MPRICE];
            $this->_mprice = $this->_getRegexpInfo($filter, $this->getContent());
        }
        return $this->_mprice;

    }

}
