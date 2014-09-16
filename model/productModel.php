<?php
/**
 * Created by PhpStorm.
 * User: Dream <dream_liu@wochacha.com>
 * Date: 14-4-30
 * Time: 下午4:55
 */

//namespace model;

//use config\spiderConfigFactory;

/**
 * base class for product model,you should subclass this model for your real product
 *
 * @package model
 */
abstract class productModel
{

    //property

    /**
     *html content of product url
     * @var string
     */
    protected $_content = null;

    /**
     * source website id
     * @var int
     */
    protected $_sourceID = null;
    /**
     * source category id
     * @var string
     */
    protected $_sourceCategoryID = null;
    /**
     * source category name
     * @var string
     */
    protected $_sourceCategoryName = null;
    /**
     * category id of WoChaCha
     * @var string
     */
    protected $_wccCategoryID = null;
    /**
     * the id of seller that sell product on the source website
     * @var string
     */
    protected $_sourceSellerID = null;
    /**
     * the name of seller that sell product on the source website
     * @var string
     */
    protected $_sourceSellerName = null;
    /**
     * the id of the product
     * @var string
     */
    protected $_productID = null;
    /**
     * the title of the product
     * @var string
     */
    protected $_title = null;
    /**
     * the promotion information of the product
     * @var string
     */
    protected $_promotion = null;
    /**
     * the sales of the product
     * @var int
     */
    protected $_sales = null;
    /**
     * the name of the product, not same of the title of product
     * @var string
     */
    protected $_name = null;
    /**
     * the image url of the product
     * @var string
     */
    protected $_imageUrl = null;
    /**
     * the brand id of the product
     * @var string
     */
    protected $_sourceBrandID = null;
    /**
     * the brand name of the product
     * @var string
     */
    protected $_sourceBrandName = null;
    /**
     * the current price of the product
     * @var float
     */
    protected $_price = null;
    /**
     * the original price of the product
     * @var float
     */
    protected $_originPrice = null;
    /**
     * the url of of the product
     * @var string
     */
    protected $_url = null;
    /**
     * the wap url of the product
     * @var string
     */
    protected $_wapUrl = null;
    /**
     * the price url of the product
     * @var string
     */
    protected $_priceUrl = null;
    /**
     * the status of product
     * @var int
     */
    protected $_status = null;
    /**
     * the characters of product
     * @var string
     */
    protected $_characters = null;
    /**
     * the type of source
     * @var string
     */
    protected $_sourceType = null;
    /**
     * the description of product
     * @var string
     */
    protected $_description = null;
    /**
     * the barcode of product
     * @var string
     */
    protected $_barcode = null;
    /**
     * the ISBN barcode for book product
     * @var string
     */
    protected $_isbnCode = null;

    /**
     * the config array of the product spider
     * @var array
     */
    protected $_config = null;
    
    /**
     * the create_date of product
     * @var string
     */
    protected $_createdate = null;
    
    /**
     * the update_date of product
     * @var string
     */
    protected $_updatedate = null;

    /**
     * the number of all comments
     * @var int
     */
    protected $_allCommentNumber = null;
    /**
     * the number of satisfy comments
     * @var int
     */
    protected $_satisfyCommentNumber = null;
    /**
     * the number of general comment
     * @var int
     */
    protected $_generalCommentNumber = null;

    /**
     * the number of dissatisfy comment
     * @var int
     */
    protected $_dissatisfyCommentNumber = null;
    /**
     * the mprice_url
     * @var string
     */
    protected $_mprice_url = null;
	 /**
     * the mobile price of the product
     * @var float
     */
    protected $_mprice = null;

    /**
     * @param string $spider spider name
     * @param string $url the url of the product
     * @param string $content the html content of the product
     */
    function __construct($spider, $url, $content = null,$config=null)
    {
        //TODO: deal with exception
        if(!$config)
       	 	$this->_config = spiderConfigFactory::getConfig($spider);
        else 
        	$this->_config = $config;
        $this->_url = $url;
        $this->_content = $content;
        $this->_sourceID = $this->_config[\elements::STID];
        $this->_sourceType = $this->_config[\elements::DATASOURCE];
    }

    /**
     *
     * protected method
     *
     */

    /**
     * The _getRegexpInfo function aims to extract the required items from
     * the target using regular expression.
     *
     * @param string $pattern
     * @param string $source
     * @param integer $match_offset
     * @return mixed|boolean
     */
    protected function _getRegexpInfo($pattern, $source, $match_offset = NULL)
    {
        if (!strlen($pattern)){
            return null;
        }
        $arr = explode("||", $pattern);
        $preg = $arr [0];
        $op = $arr [1];
        if ($op == 1) {
            $match = array();
            if (is_null($match_offset)) {
                $ret = preg_match($preg, $source, $matches);
            } else {
                $ret = preg_match($preg, $source, $matches, PREG_OFFSET_CAPTURE, $match_offset);
            }
            if ($ret == 1) {
                if (2 == count($matches)) {
                    $result = $matches[1];
                } elseif (count($matches) > 2) {
                    foreach ($matches as $key => $value) {
                        if ($key >= 1) {
                            $match[] = $value;
                        }
                    }
                    $result = $match;
                } else {
                    $result = $matches[0];
                }
            } else {
                $result = '';
            }
            return $result;
        }else{
            $mutil = json_decode ( $arr [2], true );
            preg_match_all ( $preg, $source, $match_out );
            $result = array ();
            foreach ( $mutil as $mnum => $vname ) {
                if($match_out [$mnum])
                    $result[$vname] = $match_out [$mnum];
            }
            if(count($result))
                return $result;
        }
    }

    /**
     * getter method
     */

    /**
     * get the barcode from html content.
     * if the base method can't satisfy you ,you should override this method.
     *
     * @return string|null
     */
    public function getBarcode()
    {
        if (is_null($this->_barcode)) {
            $filter = $this->_config[\elements::ITEM_BARCODE];
            $this->_barcode = $this->_getRegexpInfo($filter, $this->getContent());
        }
        return $this->_barcode;
    }

    /**
     * get the characters from html content.
     * if the base method can't satisfy you ,you should override this method.
     *
     * @return string|null
     */
    public function getCharacters()
    {
        if (is_null($this->_characters)) {
            $filter = $this->_config[\elements::ITEM_CHARACTERS];
            $this->_characters = $this->_getRegexpInfo($filter, $this->getContent());
        }
        return $this->_characters;
    }

    /**
     * get the html content of product,if _content if null then get the content from the url.
     *
     * @return string
     */
    public function getContent()
    {
        if (is_null($this->_content)) {
            //TODO: cURL may be better
            $this->_content = file_get_contents($this->_url);
        }

        return $this->_content;
    }

    /**
     * get the description from html content.
     * if the base method can't satisfy you ,you should override this method.
     *
     * @return string|null
     */
    public function getDescription()
    {
        if (is_null($this->_description)) {
            $filter = $this->_config[\elements::ITEM_DESCRIPTION];
            $this->_description = $this->_getRegexpInfo($filter, $this->getContent());
        }
        return $this->_description;
    }

    /**
     * get image url from html content, we need only one picture.
     * if the base method can't satisfy you ,you should override this method.
     *
     * @return string|null
     */
    public function getImageUrl()
    {
        if (is_null($this->_imageUrl)) {
            $filter = $this->_config[\elements::ITEM_IMAGE_URL];
            $this->_imageUrl = $this->_getRegexpInfo($filter, $this->getContent());
        }
        return $this->_imageUrl;
    }

    /**
     * get the ISBN barcode from html content, this property is only for book product.
     * if the base method can't satisfy you ,you should override this method.
     *
     * @return string|null
     */
    public function getIsbnCode()
    {
        if (is_null($this->_isbnCode)) {
            $filter = $this->_config[\elements::ITEM_ISBN];
            $this->_isbnCode = $this->_getRegexpInfo($filter, $this->getContent());
        }
        return $this->_isbnCode;
    }

    /**
     * get name from html content.
     * if the base method can't satisfy you ,you should override this method.
     *
     * @return string|null
     */
    public function getName()
    {
        if (is_null($this->_name)) {
            $filter = $this->_config[\elements::ITEM_NAME];
            $this->_name = $this->_getRegexpInfo($filter, $this->getContent());
        }
        return $this->_name;
    }

    /**
     * get original price from html content.
     * if the base method can't satisfy you ,you should override this method.
     *
     * @return mixed
     */
    public function getOriginPrice()
    {
        if (is_null($this->_originPrice)) {
            $filter = $this->_config[\elements::ITEM_OPRICE];
            $this->_originPrice = $this->_getRegexpInfo($filter, $this->getContent());
        }
        return $this->_originPrice;
    }

    /**
     * get current price from html content.
     * if the base method can't satisfy you ,you should override this method.
     *
     * @return mixed
     */
    public function getPrice()
    {
        if (is_null($this->_price)) {
            $filter = $this->_config[\elements::ITEM_DPRICE];
            $this->_price = $this->_getRegexpInfo($filter, $this->getContent());
        }
        return $this->_price;
    }
    
    /**
     * get current mprice from html content.
     * if the base method can't satisfy you ,you should override this method.
     *
     * @return mixed
     */
    public function getMPrice($wapurl=null)
    {
    	if (is_null($this->_mprice) && $this->_config[\elements::ITEM_MPRICE_PREG]) {
    		$opts = array(
    				'http'=>array(
    						'method'=>"GET",
    						'timeout'=>1,//单位秒
						'user_agent' => 'Mozilla/5.0 (Linux; Android 4.2.1; en-us; '.
						    'Nexus 5 Build/JOP40D) AppleWebkit/535.19 (KHTML, link Gecko)'.
						    ' Chrome/18.0.1025.166 Mobile Safari/535.19'
    				)
    		);
    		$pages =file_get_contents($wapurl, false, stream_context_create($opts));
    		$mcharset = $this->_config[\elements::ITEM_MPAGE_CHARSET];	
    		if (isset($mcharset) && $mcharset != 'utf-8') {
    		    $pages = mb_convert_encoding($pages, 'utf-8', $mcharset);
    		}
    		$filter =  $this->_config[\elements::ITEM_MPRICE_PREG];
    		$result = $this->_getRegexpInfo($filter, $pages);
    		if (is_array($result)) {
    	 	    foreach($result as $key => $value) {
    		        $this->_mprice += $value;
    		    }
    		}else {
    		    $this->_mprice = $result;
    		}
        }else{
        	$this->_mprice = $this->_price;
	   } 
    	return $this->_mprice;
    }

    /**
     * get price url from html content if necessary.
     *
     * @return string|null
     */
    public function getPriceUrl()
    {
        if (is_null($this->_priceUrl)) {
            $filter = $this->_config[\elements::ITEM_PRICE_URL];
            $this->_priceUrl = $this->_getRegexpInfo($filter, $this->getContent());
        }
        return $this->_priceUrl;
    }

    /**
     * get the id of product from html content
     *
     * @return string
     */
    public function getProductID()
    {
        if (is_null($this->_productID)) {
            $filter = $this->_config[\elements::ITEM_SKUID];
            $this->_productID = $this->_getRegexpInfo($filter, $this->getContent());
        }
        return $this->_productID;
    }

    /**
     * get promotion information from html content.
     * if the base method can't satisfy you ,you should override this method.
     *
     * @return string|null
     */
    public function getPromotion()
    {
        if (is_null($this->_promotion)) {
            $filter = $this->_config[\elements::ITEM_PROMOTION];
            $this->_promotion = $this->_getRegexpInfo($filter, $this->getContent());
        }
        return $this->_promotion;
    }

    /**
     * get the sales number from html content.
     * if the base method can't satisfy you ,you should override this method.
     *
     * @return int
     */
    public function getSales()
    {
        if (is_null($this->_sales)) {
            $filter = $this->_config[\elements::ITEM_SALES];
            $this->_sales = $this->_getRegexpInfo($filter, $this->getContent());
        }
        return $this->_sales;
    }

    /**
     * get brand id from html content
     *
     * @return string|null
     */
    public function getSourceBrandID()
    {
        if (is_null($this->_sourceBrandID)) {
            $filter = $this->_config[\elements::ITEM_SOURCE_BRAND_ID];
            $this->_sourceBrandID = $this->_getRegexpInfo($filter, $this->getContent());
        }
        return $this->_sourceBrandID;
    }

    /**
     * get brand name from html content
     *
     * @return string|null
     */
    public function getSourceBrandName()
    {
        if (is_null($this->_sourceBrandName)) {
            $filter = $this->_config[\elements::ITEM_SOURCE_BRAND_NAME];
            $this->_sourceBrandName = $this->_getRegexpInfo($filter, $this->getContent());
            $this->_sourceBrandName = $this->filterbrandname($this->_sourceBrandName);
        }
        return $this->_sourceBrandName;
    }

    /**
     * get source category id from html content
     *
     * @return string
     */
    public function getSourceCategoryID()
    {
        if (is_null($this->_sourceCategoryID)) {
            $filter = $this->_config[\elements::ITEM_SOURCE_CATEGORY_ID];
            $this->_sourceCategoryID = $this->_getRegexpInfo($filter, $this->getContent());
        }
        return $this->_sourceCategoryID;
    }

    /**
     * get source category name from html content
     *
     * @return string
     */
    public function getSourceCategoryName()
    {
        if (is_null($this->_sourceCategoryName)) {
            $filter = $this->_config[\elements::ITEM_SOURCE_CATEGORY_NAME];
            $this->_sourceCategoryName = $this->_getRegexpInfo($filter, $this->getContent());
        }
        return $this->_sourceCategoryName;
    }

    /**
     * get source id base on spider
     *
     * @return int
     */
    public function getSourceID()
    {
        return $this->_sourceID;
    }

    /**
     * get seller id from html content if necessary
     *
     * @return string|null
     */
    public function getSourceSellerID()
    {
        if (is_null($this->_sourceSellerID)) {
            $filter = $this->_config[\elements::ITEM_SOURCE_SELLER_ID];
            $this->_sourceSellerID = $this->_getRegexpInfo($filter, $this->getContent());
        }
        return $this->_sourceSellerID;
    }

    /**
     * get seller name from html content if necessary
     *
     * @return string|null
     */
    public function getSourceSellerName()
    {
        if (is_null($this->_sourceSellerName)) {
            $filter = $this->_config[\elements::ITEM_SOURCE_SELLER_NAME];
            $this->_sourceSellerName = $this->_getRegexpInfo($filter, $this->getContent());
        }
        return $this->_sourceSellerName;
    }

    /**
     * get source type of product.you can set value in constructor
     *
     * @return string
     */
    public function getSourceType()
    {

        return $this->_sourceType;
    }

    /**
     * get status of product from html content
     *
     * @return int
     */
    public function getStatus()
    {
        if (is_null($this->_status)) {
            $filter = $this->_config[\elements::ITEM_STATUS];
            $this->_status = $this->_getRegexpInfo($filter, $this->getContent());
        }
        return $this->_status;
    }

    /**
     * get title of product from html content.
     * if the base method can't satisfy you ,you should override this method.
     *
     * @return string|null
     */
    public function getTitle()
    {
        if (is_null($this->_title)) {
            $filter = $this->_config[\elements::ITEM_TITLE];
            $this->_title = $this->_getRegexpInfo($filter, $this->getContent());
        }
        return $this->_title;
    }

    /**
     * get the url of the product.you should set this value in constructor
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->_url;
    }

    /**
     * get the wap url base on url.
     * you should override this method and generate the wap url base on url and some rules.
     *
     * @return string|null
     */
    public function getWapUrl()
    {
        return $this->_wapUrl;
    }

    /**
     * get category id of WoChaCha base on source category id and category map
     *
     * @return int
     */
    public function getWccCategoryID()
    {
        return $this->_wccCategoryID;
    }
    
    /**
     * create product create time
     */
    public function getCreateDate()
    {
    	$this->_createdate = time();
    	return $this->_createdate;
    }
    
    /**
     * update product update time
     */
    public function getUpdateDate()
    {
    	$this->_updatedate = time();
    	return $this->_updatedate;
    }

    /**
     * get number of all comments
     * @return int
     */
    public function getAllCommentNumber()
    {
        if (is_null($this->_allCommentNumber)){
            $filter = $this->_config[\elements::ITEM_COMMENT_NUMBER_ALL];
            $this->_allCommentNumber = intval($this->_getRegexpInfo($filter, $this->getContent()));
        }
        return $this->_allCommentNumber;
    }

    /**
     * get number of dissatisfy comments
     * @return int
     */
    public function getDissatisfyCommentNumber()
    {
        if (is_null($this->_dissatisfyCommentNumber)){
            $filter = $this->_config[\elements::ITEM_COMMENT_NUMBER_DISSATISFY];
            $this->_dissatisfyCommentNumber = intval($this->_getRegexpInfo($filter,$this->getContent()));
        }
        return $this->_dissatisfyCommentNumber;
    }

    /**
     * get number of general comments
     * @return int
     */
    public function getGeneralCommentNumber()
    {
        if (is_null($this->_generalCommentNumber)){
            $filter = $this->_config[\elements::ITEM_COMMENT_NUMBER_GENERAL];
            $this->_generalCommentNumber = intval($this->_getRegexpInfo($filter,$this->getContent()));
        }
        return $this->_generalCommentNumber;
    }

    /**
     * get number of satisfy comments
     * @return int
     */
    public function getSatisfyCommentNumber()
    {
        if (is_null($this->_satisfyCommentNumber)){
            $filter = $this->_config[\elements::ITEM_COMMENT_NUMBER_SATISFY];
            $this->_satisfyCommentNumber = intval($this->_getRegexpInfo($filter,$this->getContent()));
        }
        return $this->_satisfyCommentNumber;
    }


    /**
     * export the product model's properties to array
     *
     * @return array
     */
    public function exportToArray($updateconfig = null,$item=null)
    {
    	require_once 'system/lib/lib_tools.php';
		$tools = new tools();
        $arrData = $item?$item:array();
        if($item)
        	$this->_productID = $item['skuid'];
        if(($item && in_array(elements::STID,$updateconfig)) || !$item)
      	  	$arrData[\elements::STID] = $this->getSourceID();
        if(($item && in_array(elements::ITEM_SOURCE_CATEGORY_ID,$updateconfig))|| !$item)
       		$arrData[\elements::ITEM_SOURCE_CATEGORY_ID] = $this->getSourceCategoryID();
        if(($item && in_array(elements::ITEM_SOURCE_CATEGORY_NAME,$updateconfig))|| !$item)
        	$arrData[\elements::ITEM_SOURCE_CATEGORY_NAME] = $this->getSourceCategoryName();
        if(($item && in_array(elements::ITEM_CID,$updateconfig))|| !$item)
        	$arrData[\elements::ITEM_CID] = $this->getWccCategoryID();
        if(($item && in_array(elements::ITEM_SOURCE_SELLER_NAME,$updateconfig))|| !$item)
        	$arrData[\elements::ITEM_SOURCE_SELLER_NAME] = $this->getSourceSellerName();
        if(($item && in_array(elements::ITEM_SOURCE_SELLER_ID,$updateconfig))|| !$item)
        	$arrData[\elements::ITEM_SOURCE_SELLER_ID] = $this->getSourceSellerID();
        if(($item && in_array(elements::ITEM_SKUID,$updateconfig))|| !$item)
        	$arrData[\elements::ITEM_SKUID] = $this->getProductID();
        if(($item && in_array(elements::ITEM_TITLE,$updateconfig))|| !$item)
        	$arrData[\elements::ITEM_TITLE] = $this->getTitle();
        if(($item && in_array(elements::ITEM_PROMOTION,$updateconfig))|| !$item)
        	$arrData[\elements::ITEM_PROMOTION] = $this->getPromotion();
        if(($item && in_array(elements::ITEM_SALES,$updateconfig))|| !$item)
       		$arrData[\elements::ITEM_SALES] = $this->getSales();
        if(($item && in_array(elements::ITEM_NAME,$updateconfig))|| !$item)
        	$arrData[\elements::ITEM_NAME] = $this->getName();
        if(($item && in_array(elements::ITEM_IMAGE_URL,$updateconfig))|| !$item)
        	$arrData[\elements::ITEM_IMAGE_URL] = $this->getImageUrl();
        if(($item && in_array(elements::ITEM_SOURCE_BRAND_ID,$updateconfig))|| !$item)
        	$arrData[\elements::ITEM_SOURCE_BRAND_ID] = $this->getSourceBrandID();
        if(($item && in_array(elements::ITEM_SOURCE_BRAND_NAME,$updateconfig))|| !$item)
        {
        	$arrData[\elements::ITEM_SOURCE_BRAND_NAME] = $tools->getBrandName($this->getSourceBrandName());
        }
        	
        if(($item && in_array(elements::ITEM_DPRICE,$updateconfig))|| !$item)
        {
        	$arrData[\elements::ITEM_DPRICE] = $this->getPrice();
//         if(($item && in_array(elements::ITEM_OPRICE,$updateconfig))|| !$item)
//         {
        	$oprice = $this->getOriginPrice();
        	$arrData[\elements::ITEM_OPRICE] = $oprice?$oprice:$arrData[\elements::ITEM_DPRICE];
        	$wapurl = $item?$item['wapurl']:$this->getWapUrl();
        	$arrData[\elements::ITEM_MPRICE] = $this->getMPrice($wapurl);
        	$arrData[\elements::ITEM_STATUS] = $arrData[\elements::ITEM_DPRICE]>0?1:0;
         }
        	
        	
       
        	
        if(($item && in_array(elements::ITEM_SOURCE_URL,$updateconfig))|| !$item)
        	$arrData[\elements::ITEM_SOURCE_URL] = $this->getUrl();
        if(($item && in_array(elements::ITEM_WAPURL,$updateconfig))|| !$item)
        	$arrData[\elements::ITEM_WAPURL] = $this->getWapUrl();
        if(($item && in_array(elements::ITEM_PRICE_URL,$updateconfig))|| !$item)
        	$arrData[\elements::ITEM_PRICE_URL] = $this->getPriceUrl();
//         if(($item && in_array(elements::ITEM_STATUS,$updateconfig))|| !$item)
//         	$arrData[\elements::ITEM_STATUS] = $this->getStatus();
        if(($item && in_array(elements::ITEM_CHARACTERS,$updateconfig))|| !$item)
        {
        	$arrData[\elements::ITEM_CHARACTERS] = $tools->filtercharacters($this->getCharacters());
        }
        
        
        
        
        
        if(($item && in_array(elements::ITEM_DATA_SOURCE,$updateconfig))|| !$item)
        	$arrData[\elements::ITEM_DATA_SOURCE] = $this->getSourceType();
        if(($item && in_array(elements::ITEM_DESCRIPTION,$updateconfig))|| !$item)
        	$arrData[\elements::ITEM_DESCRIPTION] = $this->getDescription();
        if(($item && in_array(elements::ITEM_ISBN,$updateconfig))|| !$item)
        	$arrData[\elements::ITEM_ISBN] = $this->getIsbnCode();
        if(($item && in_array(elements::ITEM_BARCODE,$updateconfig))|| !$item)
        	$arrData[\elements::ITEM_BARCODE] = $this->getBarcode();
        if(($item && in_array(elements::ITEM_COMMENT_NUMBER_ALL,$updateconfig))|| !$item)
            $arrData[\elements::ITEM_COMMENT_NUMBER_ALL] = $this->getAllCommentNumber();
        if(($item && in_array(elements::ITEM_COMMENT_NUMBER_SATISFY,$updateconfig))|| !$item)
            $arrData[\elements::ITEM_COMMENT_NUMBER_SATISFY] = $this->getSatisfyCommentNumber();
        if(($item && in_array(elements::ITEM_COMMENT_NUMBER_GENERAL,$updateconfig))|| !$item)
            $arrData[\elements::ITEM_COMMENT_NUMBER_GENERAL] = $this->getGeneralCommentNumber();
        if(($item && in_array(elements::ITEM_COMMENT_NUMBER_DISSATISFY,$updateconfig))|| !$item)
            $arrData[\elements::ITEM_COMMENT_NUMBER_DISSATISFY] = $this->getDissatisfyCommentNumber();
        if(!$item)
        	$arrData[\elements::ITEM_CREATE_TIME] = $this->getCreateDate();
//         if($item)
        	$arrData[\elements::ITEM_UPDATE_TIME] = $this->getUpdateDate();
        
//         $arrData['content'] = $this->getContent();
        return $arrData;
    }
    /**
     * filterbrandname
     * @param unknown $str
     * @return mixed|Ambigous <string, mixed, unknown>
     */
    public function filterbrandname($str) {
    	$str = rtrim($str);
    	$str = ltrim($str);
    	$str = strtoupper($str);
    	$str = preg_replace("/\s+/"," ",$str);
    	$str = str_replace(array("(",")","宏 ?"),array("（","）","宏基"),$str);
    	$str_lower = strtolower($str);
    	$preg1 = "/(.*)[\x{4e00}-\x{9fa5}](.*)[\x{4e00}-\x{9fa5}]([a-z]|\.|-|[0-9]|の)/u";
    	$preg2 = "/([a-z]|\.|-|[0-9]|の)[\x{4e00}-\x{9fa5}](.*)[\x{4e00}-\x{9fa5}]/u";
    	$preg3 = "/(.*)[\x{4e00}-\x{9fa5}]([a-z]|\.|-|[0-9]|の)/u";
    	$preg4 = "/([a-z]|\.|-|[0-9]|の)[\x{4e00}-\x{9fa5}]/u";
    	 
    	if(preg_match($preg1, $str_lower, $match) || preg_match($preg2, $str_lower, $match) || preg_match($preg3, $str_lower, $match) || preg_match($preg4, $str_lower, $match)){
    		return $str;
    	}else{
    		$qian=array("°","…","｜","……","－","、","￥","—","#","$","%","(",")","[","]","{","}",",","@","^","*","!","~"."&","?","？","。","，","：","（","）","_","-","'","‘","’","“","”","`");
    		$hou=array("","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","");
    		$after_str = str_replace($qian,$hou,$str);
    		$str = str_replace(array("(",")","（","）"),array("","","",""),$str);
    		if(!preg_match("/([\x81-\xfe][\x40-\xfe])/", $after_str, $match)){
    	   
    			return $str;
    		}else{
    			$preg = "/[\x{4e00}-\x{9fa5}](.*)[\x{4e00}-\x{9fa5}]+/u";
    			preg_match($preg,$str,$matches);
    			if(!$matches){
    				$preg = "/[\x{4e00}-\x{9fa5}]+/u";
    				preg_match($preg,$str,$matches);
    			}
    			if(isset($matches[0]) && $matches[0])
    			{
    				$str_chinese = $matches[0];
    				$str_englist = str_replace($str_chinese,"",$str);
    				$str_englist = str_replace("/","",$str_englist);
    				$str_englist = rtrim($str_englist);
    				$str_englist = ltrim($str_englist);
    				if($str_englist){
    					$str = $str_chinese."（".$str_englist."）";
    				}else{
    					$str = $str_chinese;
    				}
    			}
    		}
    		return $str;
    	}
    }
}
