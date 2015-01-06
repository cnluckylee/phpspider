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
class soufunnewhouselistProductModel extends productXModel {

    public function getCategoryItemDprice()
    {
        $filter = $this->_config[\elements::CATEGORY_ITEM_DPRICE];

        $nodes = $this->_xpath->query($filter);
        $address = $this->_config[\elements::CATEGORY_ITEM_AREA];
        $tags = './/p[@class="sf_status"]/a/text()';
        $this->_category_item_area = array();
        $this->_category_item_dprice = array();
        foreach ($nodes as $k=>$node) {

            //address
            foreach ($this->_xpath->query($address, $node) as $child) {
                if($child->nodeValue)
                    $this->_category_item_area[$k] = $child->nodeValue;
            }
            if(!$this->_category_item_area[$k]){
                $address2 = './/div[@class="sclist_con fl ml15"]/p[1]/text()';
                $i=1;
                foreach ($this->_xpath->query($address2, $node) as $child) {
                    if(strlen($child->nodeValue)>0 && $i==1){
                        $this->_category_item_area[$k] = $child->nodeValue;
                        $i++;
                    }
                }
            }
            if(!$this->_category_item_area[$k]){
                $address3 = './/li[2]/font/@title';
                foreach ($this->_xpath->query($address3, $node) as $child) {
                    if(strlen($child->nodeValue)>0 && $i==1){
                        $this->_category_item_area[$k] = $child->nodeValue;
                    }
                }
            }

            //TAGS
            foreach ($this->_xpath->query($tags, $node) as $child) {

                if($child->nodeValue)
                    $this->_category_item_dprice[$k][] = $child->nodeValue;
            }
        }

        return $this->_category_item_dprice;
    }

    public function getCategoryCommon()
    {
        $data = parent::getCategoryItemOprice();
        $this->_categorycommon = array();
        if(!$data){
            $filter = '//span[@class="shoucang"]/@onclick||2';
            $data = $this->_getRegexpInfo($filter,$this->getContent());
        }
        foreach($data as $k=>$nodes)
        {
            $str = str_replace(array("PostSelect(",");","'"),"",$nodes);
            $arr = explode(",",$str);
            if($arr)
            {
                $this->_categorycommon['Price'][$k] = $arr[5] . $arr[6];
                $this->_categorycommon['Address'][$k] = $arr[3];
                $this->_categorycommon['Name'][$k] = $arr[2];
                $this->_categorycommon['Num'][$k] = $arr[1];
                $this->_categorycommon['City'][$k] = $arr[4];
//                $this->_categorycommon['Category_Item_Url'][$k] = $arr[8];
            }
        }

        return  $this->_categorycommon;
    }
    public function getCategoryItemUrL()
    {
        $str = parent::getCategoryItemUrL();
        if(!$str)
        {
           $this->_category_item_url = array();
           $filter = '//strong[@class="f14px"]/a/@href||2';
            $this->_category_item_url = $this->_category_item_url = $this->_getRegexpInfo($filter,$this->getContent());
        }
        return $this->_category_item_url;
    }

    public function getCategoryItemSkuid()
    {
        $str = parent::getCategoryItemSkuid();
        if(!$str)
        {
            $this->_category_item_skuid = array();
            $filter = '//strong[@class="f14px"]/a/@href||2';
            $this->_category_item_skuid = $this->_category_item_url = $this->_getRegexpInfo($filter,$this->getContent());
        }
        return $this->_category_item_skuid;
    }

    public function getCategoryItemName()
    {
        $data = parent::getCategoryItemName();
        if(!$data)
        {
            $this->_category_item_name = array();
            $filter = '//strong[@class="f14px"]/a/text()||2';
            $this->_category_item_name = $this->_getRegexpInfo($filter,$this->getContent());
        }
        return $this->_category_item_name;
    }

    public function getSales()
    {
        $p =$this->_config[elements::ITEM_SALES];
        preg_match($p,$this->_content,$out);
        $this->_sales = isset($out[1])?$out[1]:0;
        return $this->_sales;
    }

    public function getIsbnCode()
    {
        $p =$this->_config[elements::ITEM_ISBN];
        preg_match($p,$this->_content,$out);
        $this->_isbnCode = isset($out[1])?$out[1]:0;
        return $this->_isbnCode;
    }


    public function getBarcode()
    {
        if(is_null($this->_barcode))
        {
            $p =$this->_config[elements::ITEM_BARCODE];
            preg_match($p,$this->_content,$out);
            $this->_barcode = isset($out[1])?$out[1]:0;
        }
        return $this->_barcode;
    }

    public function getPromotion()
    {
        $source_url = parent::getUrl();
        $url = $source_url."/house/".$this->getBarcode()."/housedetail.htm";
        $xpath = $this->getXpathByUrl($url);
        $filter = $this->_config[\elements::ITEM_PROMOTION];
        $nodes = $xpath->query($filter);
        $key = './/td[1]/strong/text()';
        $val = './/td[1]/text()';
        $key2 = './/td[2]/strong/text()';
        $val2 = './/td[2]/text()';
        $this->_promotion = $this->_promotion2 =array();
        $filter2 = './/a/text()';
        foreach ($nodes as $k=>$node) {

            $key_v2 = $val_v2="";
            //address
            foreach ($xpath->query($key2, $node) as $child) {
                if($child->nodeValue)
                    $key_v2 = trim($child->nodeValue);
            }
            foreach ($xpath->query($val2, $node) as $child) {
                if($child->nodeValue)
                    $val_v2 = trim($child->nodeValue);
                }

            if($key_v2 && !$val_v2)
            {
                foreach ($xpath->query($filter2, $node) as $child2) {
                    if($child2->nodeValue)
                        $val_v2 = trim($child2->nodeValue);
                }
            }
            if($key_v2 && $val_v2)
             $this->_promotion[$key_v2] = $val_v2;

        }
        foreach ($nodes as $k=>$node) {
            $key_v = $val_v="";
            foreach ($xpath->query($key, $node) as $child) {
                if($child->nodeValue)
                    $key_v = trim($child->nodeValue);
                else{
                    foreach ($xpath->query($filter2, $node) as $child) {
                        if($child->nodeValue)
                            $key_v = trim($child->nodeValue);
                    }
                }
            }
            foreach ($xpath->query($val, $node) as $child) {
                if($child->nodeValue)
                    $val_v = trim($child->nodeValue);
            }
            if($key_v && (empty($val_v) || $key_v=="开 发 商"))
            {
                foreach ($xpath->query($filter2, $node) as $child2) {
                    if($child2->nodeValue)
                        $val_v = trim($child2->nodeValue);
                }
            }
            if($key_v && $val_v)
                $this->_promotion[$key_v] = $val_v;
            if(strstr($key_v,"交通状况")){
                break;
            }
        }
        return $this->_promotion;
    }

    public  function getCharacters()
    {
        if(is_null($this->_characters))
        {
            $source_url = parent::getUrl();
            $url = $source_url."/house/ajaxrequest/dianpingList.php?&newcode=".$this->getBarcode()."&order=n&pagesize=10&page=";
            $str = $this->getContentByUrl($url);
            $arr = json_decode($str,true);
            $comments = array();
            $list = $arr['list'];
            foreach($list as $k=>$v)
            {
                $comments[] = $v;
            }
            $totalpages = ceil($arr['count']/10);
            for($i=2;$i<=$totalpages;$i++)
            {
                $url = $source_url."/house/ajaxrequest/dianpingList.php?&newcode=".$this->getBarcode()."&order=n&pagesize=10&page=".$i;
                $str = $this->getContentByUrl($url);
                $arr = json_decode($str,true);
                $list = $arr['list'];
                foreach($list as $k=>$v)
                {
                    $comments[] = $v;
                }
            }
            $this->_characters = $comments;
        }
        return $this->_characters;
    }

    public function  getDescription()
    {
        $str = parent::getDescription();
        $this->_description = str_replace(" ","",$str);
        return $this->_description;
    }
    public  function getAllCommentNumber()
    {
        $this->_allCommentNumber = count($this->_characters);
        return  $this->_allCommentNumber;
    }
    /*
        public function getSales2()
        {
            $str = parent::getSales2();
            $this->_sales = 0;
            if(strstr($str,'e_ico6'))
                $this->_sales = 1;
            return  $this->_sales;
        }



        public function getAllCommentNumber()
        {
            $str = parent::getAllCommentNumber();
            if($str)
            {
                $p = '/(\d+)/';
                preg_match($p,$str,$out);

                $this->_allCommentNumber = isset($out[1])?$out[1]:0;
            }
            return  $this->_allCommentNumber;
        }

        public function getCategoryItemHot()
        {
            $data = parent::getCategoryItemHot();
            $filter = './/span[@class="corner"]/@class';
            $this->_category_item_hot = array();
            foreach($data as $k=>$v)
            {
                foreach ($this->_xpath->query('.//dl/dt/p[2]/text()', $filter) as $child) {
                    $this->_category_item_hot[$k] = $child?1:0;
                }
            }
            return $this->_category_item_hot;
        }

        public function getCategoryItemUrl()
        {
            $data = parent::getCategoryItemUrl();
    //        $sourceurl = parent::getUrl();
    //
    //        $arr = parse_url($sourceurl);
    //        $baseurl = $arr['scheme']."://".$arr['host'];
            $baseurl = 'http://www.leju.com/?mod=api_projectlist&type=foucs_equan&aid=';

            foreach($data as $k=>$v)
            {
                $tmp = parse_url($v);
                parse_str($tmp['query'],$parr);
                $this->_category_item_url[$k] = $baseurl.$parr['aid'].'&city='.$parr['hsite'].'&hid='.$parr['hid'];
            }
            return $this->_category_item_url;
        }
        public function getCategoryItemArea()
        {
            $str = parent::getCategoryItemArea();
            $data = parent::getCategoryItemUrL();
            $this->_category_item_area = array();
            foreach($data as $k=>$v)
            {
                $this->_category_item_area[$k] = $str;
            }
            return $this->_category_item_area;
        }


    */

/*
    public function getCategoryItemSkuid()
    {
        $data = parent::getCategoryItemSkuid();
        if(!$data)
        {
            $this->_category_item_skuid = array();
            $filter = '//li[@class="site-topsearch"][1]/a/@href||2';
            $this->_category_item_skuid = $this->_getRegexpInfo($filter,$this->getContent());
        }
        return $this->_category_item_skuid;
    }

    public function getCategoryItemUrl()
    {
        $data = parent::getCategoryItemUrl();
        $sourceurl = parent::getUrl();

        $arr = parse_url($sourceurl);
        $baseurl = $arr['scheme']."://".$arr['host'];
        if(!$data)
        {
            $this->_category_item_url = array();
            $filter = '//li[@class="site-topsearch"][1]/a/@href||2';
            $tmp = $this->_getRegexpInfo($filter,$this->getContent());
            foreach($tmp as $k=>$v)
            {
                if(strstr($v,'javascript'))
                    $v = $sourceurl;
                if(!strstr($v,"http"))
                {
                    $this->_category_item_url[$k] = $baseurl.$v;
                }else{
                    $this->_category_item_url[$k] = $v;
                }
            }
        }else{
            foreach($data as $k=>$v)
            {
                if(strstr($v,'javascript'))
                    $v = $sourceurl;
                if(!strstr($v,"http"))
                {
                    $this->_category_item_url[$k] = $baseurl.$v;
                }else{
                    $this->_category_item_url[$k] = $v;
                }
            }
        }
        return $this->_category_item_url;
    }



    public function getProductID()
    {
        $str = parent::getProductID();
        if(!$str)
        {
            $filter = '//li[@class="site-topsearch"]/a[@class="current"]/text()||1';
            $this->_productID = $this->_getRegexpInfo($filter,$this->getContent());
        }
        return $this->_productID;
    }

    public function getPriceUrl()
    {
        $sourceurl = parent::getUrl();
        $arr = parse_url($sourceurl);
        $baseurl = $arr['scheme']."://".$arr['host'];
        $data = parent::getPriceUrl();
        if(!$data)
        {
            $this->_priceUrl = array();
            $filter = '//div[@class="site-child-arae"]/a/@href||2';
            $tmp = $this->_getRegexpInfo($filter,$this->getContent());
            foreach($tmp as $k=>$v)
            {
                if(strstr($v,'javascript'))
                    $v = $sourceurl;
                if(!strstr($v,"http"))
                {
                    $this->_priceUrl[$k] = $baseurl.$v;
                }else{
                    $this->_priceUrl[$k] = $v;
                }
            }
        }else{
            foreach($data as $k=>$v)
            {
                if(strstr($v,'javascript'))
                    $v = '/';
                if(!strstr($v,"http"))
                {
                    $this->_priceUrl[$k] = $baseurl.$v;
                }else{
                    $this->_priceUrl[$k] = $v;
                }
            }
        }
        return $this->_priceUrl;
    }

    public function getOriginPrice()
    {
        $data = parent::getOriginPrice();
        $sourceurl = parent::getUrl();
        $arr = parse_url($sourceurl);
        $baseurl = $arr['scheme']."://".$arr['host'];
        if(!$data)
        {
            $this->_originPrice = array();
            $filter = '//li[@class="site-topsearch"][2]/a/text()||2';
            $this->_originPrice = $this->_getRegexpInfo($filter,$this->getContent());
        }
        return $this->_originPrice;
    }


    public function getPrice()
    {
        $data = parent::getPrice();
        $sourceurl = parent::getUrl();
        $arr = parse_url($sourceurl);
        $baseurl = $arr['scheme']."://".$arr['host'];
        if(!$data)
        {
            $this->_price = array();
            $filter = '//li[@class="site-topsearch"][2]/a/@href||2';
            $tmp = $this->_getRegexpInfo($filter,$this->getContent());
            $sourceurl = parent::getUrl();
            foreach($tmp as $k=>$v)
            {
                if(strstr($v,'javascript'))
                    $v = $sourceurl;
                if(!strstr($v,"http"))
                {
                    $this->_price[$k] = $baseurl.$v;
                }else{
                    $this->_price[$k] = $v;
                }
            }
        }else{
            foreach($data as $k=>$v)
            {
                if(strstr($v,'javascript'))
                    $v = $sourceurl;
                if(!strstr($v,"http"))
                {
                    $this->_price[$k] = $baseurl.$v;
                }else{
                    $this->_price[$k] = $v;
                }
            }
        }
        return $this->_price;
    }

    public function getName()
    {
        $data = parent::getName();
        if(!$data)
        {
            $filter = '//li[@class="site-topsearch"][1]/a/text()||2';
            $this->_name = $this->_getRegexpInfo($filter,$this->getContent());
        }
        return $this->_name;
    }
*/
}