<?php
class suningProductModel extends productModel
{
	public function getSourceCategoryName()
	{
		$tmp = parent::getSourceCategoryName();
		$tmp = substr($tmp,0,-1);
		$str = "{\"category1\":\"".$tmp."}";
		$cate = json_decode ( $str, true );
		$this->_sourceBrandName = $cate ['categoryName1'] . '-' . $cate ['categoryName2'] . '-' . $cate ['categoryName3'];
		return $this->_sourceBrandName;
	}
	
	public function getSourceSellerID()
	{
		$shopinfo = $this->getShopInfo();
		if($shopinfo)
		{
			$shop_pages = $shopinfo['shoppage'];
			$shoptmp = json_decode ( $shop_pages, true );
			$shoptmp = $shoptmp['shopList'][0];
			$this->_sourceSellerID = $shoptmp['shopCode'];
		}
		return $this->_sourceSellerID;
	}
	
	public function getSourceSellerName()
	{
		$shopinfo = $this->getShopInfo();
		if($shopinfo)
		{
			$shop_pages = $shopinfo['shoppage'];
			$shoptmp = json_decode ( $shop_pages, true );
			$shoptmp = $shoptmp['shopList'][0];
			$this->_sourceSellerName = $shoptmp['shopName'];
		}
		return $this->_sourceSellerName;
	}
	
	public function getPriceUrl()
	{
		$shopinfo = $this->getShopInfo();
		if($shopinfo)
		{
			$shop_pages = $shopinfo['shoppage'];
			$shoptmp = json_decode ( $shop_pages, true );
			$shoptmp = $shoptmp['shopList'][0];
			$source_seller_id = $shoptmp['shopCode'];
			$this->_priceUrl = 'http://product.suning.com/SNProductStatusView?storeId=' . $shopinfo ['storeId'] . '&catalogId=' . $shopinfo ['catalogId'] . '&partNumber=' . $shopinfo ['partNumber'] . '&cityId=9264&vendorCode='.$source_seller_id.'&_=1397115921106';
			return $this->_priceUrl;
		}	
	}
	
	public function getOriginPrice()
	{
		$tmp = parent::getOriginPrice();
		if(!$tmp)
		{
			$this->_originPrice = $this->getPrice();
			return $this->_originPrice;
		}
	}
	
	public function getPrice()
	{
		$tmp = parent::getPrice();
		if(!$tmp)
		{
			$shopinfo = $this->getShopInfo();
			if($shopinfo)
			{
				$shop_pages = $shopinfo['shoppage'];
				$shoptmp = json_decode ( $shop_pages, true );
				$shoptmp = $shoptmp['shopList'][0];
				$source_seller_id = $shoptmp['shopCode'];
				$this->_price =  $shoptmp['productPrice'];
				return $this->_price;
			}
		}
	}
	
	public function getImageUrl()
	{
		$this->_imageUrl = parent::getImageUrl();
		if(!$this->_imageUrl)
		{
			$preg = '/class="view-img"\s+src="(.*?)"/||1';
			$this->_imageUrl = $this->_getRegexpInfo($preg, $this->getContent());
			return $this->_imageUrl;
		}
		return $this->_imageUrl;
	}
	public function getWapUrl()
	{
		$this->_wapUrl = 'http://m.suning.com/'.$this->_productID.'.html';
		return $this->_wapUrl;
	}
	public function getPromotion(){
		$shopinfo = $this->getShopInfo();
		if($shopinfo)
		{
			$shop_pages = $shopinfo['shoppage'];
			$shoptmp = json_decode ( $shop_pages, true );
			$shoptmp = $shoptmp['shopList'][0];
			$source_seller_id = $shoptmp['shopCode'];
			$promotion_url = 'http://product.suning.com/snprdpromonser_' . $shopinfo ['storeId'] . '_' . $shopinfo ['catalogId'] . '_' . $shopinfo ['productId'] . '_' . $shopinfo ['partNumber'] .'_'.$source_seller_id. '_.html';
			$promotion_pages = file_get_contents( $promotion_url);
			if($promotion_pages)
			{
				$promotiontmp = json_decode ( $promotion_pages [0], true );
				$this->_promotion = $promotiontmp ['promotionDesc'];
			}
			
			return $this->_promotion;
		}
	}
	public function getShopInfo()
	{
		$preg = '/"snShopMainPh":(.*)com\'}/iUs';
		preg_match ( $preg, $this->getContent(), $match_out );
		if($match_out)
		{
			$str = str_replace ( "'", '"', '{'.$match_out [0] );
			$tmp = json_decode ( $str, true );
			$tmp['shop_url'] = 'http://product.suning.com/emall/csl_'.$tmp ['storeId'].'_'.$tmp ['catalogId'].'_'.$tmp ['productId'].'_'.$tmp ['partNumber'].'_9264_.html';
			$shoppage = file_get_contents( $tmp['shop_url']);
			$tmp['shoppage'] = isset($shoppage)?$shoppage:"";
			return $tmp;
		}
	}
	public function getDescription()
	{
		
			$this->_description = $this->_characters;
		return $this->_description;
	}
	public function getCharacters()
	{
		$tmp = parent::getCharacters();
		if(isset($tmp['key']) && $tmp['key'] && isset($tmp['val']) && $tmp['val'])
			$this->_characters = array_combine($tmp['key'],$tmp['val']);
		return $this->_characters;
	}
}