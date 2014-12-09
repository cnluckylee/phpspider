<?php 

class yhdProductModel extends productModel
{
    public function getCharacters()
    {
        $match_count = preg_match($this->_config[elements::ITEM_CHARACTERS], $this->_content, $matches);
        if ($match_count > 0)
        {
            $url_query = array(
                'productID' => $matches[2], 
                'merchantId' => $matches[3], 
                'isYiHaoDian' => $matches[4], 
                'uid' => $matches[5], 
                'pmId' => $matches[1]
            );
            
            $url = 'http://item-home.yhd.com/item/ajax/ajaxProdDescTabView.do?callback=detailProdDesc.prodDescCallback&' . http_build_query($url_query);
            $url_content = file_get_contents($url);
            if ($url_content !== false)
            {
                $url_content = str_replace('\\"', '"', $url_content);
                $preg_character = '/<dd\s+title[^>]+>([^<]+)<\\\\\/dd>/';
                $match_count = preg_match_all($preg_character, $url_content, $matches);
                if ($match_count > 0)
                {
                    $split_func = function($str) { return explode('：', $str); };
                    $split_arr = array_map($split_func, $matches[1]);
                    
                    $characters = array();
                    foreach ($split_arr as $value)
                    {
                        $characters[$value[0]] = $value[1];
                    }
                    $this->_characters = $characters;
                    return $this->_characters;
                }
            }
        }
        
        return null;
    }
    public function getSourceCategoryID()
    {
    	$tmp = parent::getSourceCategoryID();
    	$carr = explode(',', $tmp);
    	if(count($carr)>=4){
    	 $ctmp = array_slice($carr,3,1);
    	 $this->_sourceCategoryID = $ctmp[0];
    	}
    	return $this->_sourceCategoryID;
    }
    public function getDescription()
    {
    	$this->_description =  $this->_characters;
        return $this->_description;
    }
    
    private function _replaceNewLine($str)
    {
        return str_replace(array('\r', '\n', '\r\n'), '', $str);
    }
    
    public function getPriceUrl()
    {
        return $this->_url;
    }
    
    public function getProductID()
    {
        return str_replace('http://item.yhd.com/item/', '', $this->_url);
    }
    
    public function getSourceSellerID()
    {
        $preg_seller = '/isYiHaoDian"\s+value\s*=\s*(\d+)">/';
        $match_count = preg_match($preg_seller, $this->_content, $matches);
        if ($match_count > 0)
        {
            if ($matches[1] == 0)
            {
                $match_count = preg_match($this->_config[elements::ITEM_SOURCE_SELLER_ID], $this->_content, $matches);
                if ($match_count > 0)
                {
                    return $matches[1];
                }
            }
        }
        
        return null;
    }
    
    public function getStatus()
    {
        $stock_num = parent::getStatus();
        if ($stock_num > 0)
        {
            return 1;
        }
        
        return 0;
    }
    
    public function getWapUrl()
    {
        return  str_replace('http://item', 'http://m', $this->_url);
    }
    
    public function getSourceCategoryName()
    {
        $match_count = preg_match_all($this->_config[elements::ITEM_SOURCE_CATEGORY_NAME], $this->_content, $matches);
        if ($match_count > 0)
        {
            return join('-', $matches[1]);
        }
        
        return null;
    }
    
    public function getBarcode()
    {
        return null;
    }
    
    public function getIsbnCode()
    {
        return null;
    }
    
    public function getName()
    {
        return null;
    }
    
    public function getPromotion()
    {
        return null;//
    }
    
    public function getSales()
    {
        return null;
    }
    
    public function getSourceSellerName()
    {
        return null;
    }
}