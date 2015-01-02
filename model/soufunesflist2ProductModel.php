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
class soufunesflist2ProductModel extends productXModel {
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

    public  function getCategoryItemUrL()
    {
        $sourceurl = parent::getUrl();
        $data = parent::getCategoryItemUrL();
        $arr = parse_url($sourceurl);
        $baseurl = $arr['scheme']."://".$arr['host'];
        $this->_category_item_url = array();
        foreach($data as $k=>$v)
        {
            $url = trim($v);
            $skuid = str_replace("/a/","",$v);
            $this->_category_item_url[$k] = $baseurl.'/agent/Agentnew/AloneService.aspx?managername='.$skuid;
        }
        return  $this->_category_item_url;
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
