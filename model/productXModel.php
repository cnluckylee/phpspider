<?php
/**
 * Created by PhpStorm.
 * User: Dream <cnluckylee@gmail.com>
 * Date: 14-11-10
 * Time: 下午4:55
 */

//namespace model;

//use config\spiderConfigFactory;

/**
 * base class for product model,you should subclass this model for your real product
 *
 * @package model
 */
abstract class productXModel
{

    //property

    /**
     *html content of product url
     * @var string
     */
    protected $_content = null;

    /**
     *html xpath of product contect
     * @var string
     */
    protected $_xpath = null;

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
     * the item name of category list
     * @var string
     */
    protected $_category_item_name = null;

    /**
     * the item img of category list
     * @var string
     */
    protected $_category_item_url = null;

    /**
     * the item img of category list
     * @var string
     */
    protected $_category_item_img = null;


    /**
     * the item oprice of category list
     * @var string
     */
    protected $_category_item_oprice = null;


    /**
     * the item dprice of category list
     * @var string
     */
    protected $_category_item_dprice = null;


    /**
     * the item hot of category list
     * @var string
     */
    protected $_category_item_hot = null;


    /**
     * the item sale of category list
     * @var string
     */
    protected $_category_item_sale = null;


    /**
     * the item reviews of category list
     * @var string
     */
    protected $_category_item_reviews = null;


    /**
     * the item area of category list
     * @var string
     */
    protected $_category_item_area = null;

    /**
     * the item skuid of category list
     * @var string
     */
    protected $_category_item_skuid = null;

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
        $dom = new DOMDocument('1.0','utf-8');
//        $content = html_entity_decode(mb_convert_encoding($texttmp, 'gb2312','UTF-8'), ENT_QUOTES, 'gb2312');
        $content = $this->loadNprepare($content,'utf-8');
        @$dom->loadHTML($content);
        $dom->encoding = 'utf8';
        $this->_xpath = new DOMXPath($dom);
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
    protected function _getRegexpInfo($pattern, $xpath, $key = NULL)
    {
        if (!strlen($pattern)){
            return null;
        }

        $arr = explode("||", $pattern);
        $preg = $arr [0];
        $op = $arr [1];

        if ($op == 1) {
            $qdata = $xpath->query($preg);
            foreach($qdata as $i)
            {
                if(!$key)
                     $result = $i->nodeValue;
                else
                    $result[] = $i;
            }
            return $result;
        }else{

            $mutil = json_decode ( $arr [2], true );
            $events = $xpath->query($preg);
            $result = array();
            for($i = 0; $i < ($events->length); $i++) {
                $event = $events->item($i);
                if($mutil)
                {
//                    foreach($event->attributes as $k=>$v)
//                    {
//
//                        if(in_array($v->name,$mutil)){
//                            $result[$v->name] = $v->nodeValue;
//                        }
//                    }
                }else{
                    $result[$i] = trim($event->nodeValue);
                }
            }
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
        if (is_null($this->_xpath)) {
            //TODO: cURL may be better
            $this->_content = file_get_contents($this->_url);
        }

        return $this->_xpath;
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
     * get item name of product from html content.
     * if the base method can't satisfy you ,you should override this method.
     *
     * @return string|null
     */
    public function getCategoryItemName()
    {

        if (is_null($this->_category_item_name)) {
            $filter = $this->_config[\elements::CATEGORY_ITEM_NAME];
            $this->_category_item_name = $this->_getRegexpInfo($filter, $this->getContent());

        }
        return $this->_category_item_name;
    }

    /**
     * get item name of product from html content.
     * if the base method can't satisfy you ,you should override this method.
     *
     * @return string|null
     */
    public function getCategoryItemImg()
    {

        if (is_null($this->_category_item_img)) {
            $filter = $this->_config[\elements::CATEGORY_ITEM_IMG];
            $this->_category_item_img = $this->_getRegexpInfo($filter, $this->getContent());
        }
        return $this->_category_item_img;
    }

    /**
     * get item url of product from html content.
     * if the base method can't satisfy you ,you should override this method.
     *
     * @return string|null
     */
    public function getCategoryItemUrL()
    {
        if (is_null($this->_category_item_url)) {
            $filter = $this->_config[\elements::CATEGORY_ITEM_URL];
            $this->_category_item_url = $this->_getRegexpInfo($filter, $this->getContent());
        }
        return $this->_category_item_url;
    }

    /**
     * get title of product from html content.
     * if the base method can't satisfy you ,you should override this method.
     *
     * @return string|null
     */
    public function getCategoryItemOprice()
    {

        if (is_null($this->_category_item_oprice)) {
            $filter = $this->_config[\elements::CATEGORY_ITEM_OPRICE];
            $this->_category_item_oprice = $this->_getRegexpInfo($filter, $this->getContent());
        }
        return $this->_category_item_oprice;
    }

    /**
     * get title of product from html content.
     * if the base method can't satisfy you ,you should override this method.
     *
     * @return string|null
     */
    public function getCategoryItemDprice()
    {
        if (is_null($this->_category_item_dprice)) {
            $filter = $this->_config[\elements::CATEGORY_ITEM_DPRICE];
            $this->_category_item_dprice = $this->_getRegexpInfo($filter, $this->getContent());
        }
        return $this->_category_item_dprice;
    }

    /**
     * get title of product from html content.
     * if the base method can't satisfy you ,you should override this method.
     *
     * @return string|null
     */
    public function getCategoryItemHot()
    {
        if (is_null($this->_category_item_hot)) {
            $filter = $this->_config[\elements::CATEGORY_ITEM_HOT];
            $this->_category_item_hot = $this->_getRegexpInfo($filter, $this->getContent());
        }
        return $this->_category_item_hot;
    }

    /**
     * get title of product from html content.
     * if the base method can't satisfy you ,you should override this method.
     *
     * @return string|null
     */
    public function getCategoryItemSale()
    {
        if (is_null($this->_category_item_sale)) {
            $filter = $this->_config[\elements::CATEGORY_ITEM_SALE];
            $this->_category_item_sale = $this->_getRegexpInfo($filter, $this->getContent());
        }
        return $this->_category_item_sale;
    }


    /**
     * get title of product from html content.
     * if the base method can't satisfy you ,you should override this method.
     *
     * @return string|null
     */
    public function getCategoryItemReviews()
    {
        if (is_null($this->_category_item_reviews)) {
            $filter = $this->_config[\elements::CATEGORY_ITEM_REVIEWS];
            $this->_category_item_reviews = $this->_getRegexpInfo($filter, $this->getContent());
        }
        return $this->_category_item_reviews;
    }

    /**
     * get title of product from html content.
     * if the base method can't satisfy you ,you should override this method.
     *
     * @return string|null
     */
    public function getCategoryItemArea()
    {
        if (is_null($this->_category_item_area)) {
            $filter = $this->_config[\elements::CATEGORY_ITEM_AREA];
            $this->_category_item_area = $this->_getRegexpInfo($filter, $this->getContent());
        }
        return $this->_category_item_area;
    }

    /**
     * get title of product from html content.
     * if the base method can't satisfy you ,you should override this method.
     *
     * @return string|null
     */
    public function getCategoryItemSkuid()
    {
        if (is_null($this->_category_item_skuid)) {
            $filter = $this->_config[\elements::CATEGORY_ITEM_SKUID];
            $this->_category_item_skuid = $this->_getRegexpInfo($filter, $this->getContent());
        }
        return $this->_category_item_skuid;
    }

    /**
     * export the product model's properties to array
     *
     * @return array
     */
    public function exportToArray($updateconfig = null,$item=null)
    {

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
        	$arrData[\elements::ITEM_SOURCE_BRAND_NAME] = $this->getSourceBrandName();
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
        	$arrData[\elements::ITEM_CHARACTERS] = $this->getCharacters();
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

    function loadNprepare($content,$encod='') {
        if (!empty($content)) {
            if (empty($encod))
                $encod = mb_detect_encoding($content);
            $headpos = mb_strpos($content,'<head>');
            if (FALSE=== $headpos)
                $headpos= mb_strpos($content,'<HEAD>');
            if (FALSE!== $headpos) {
                $headpos+=6;
                $content = mb_substr($content,0,$headpos) . '<meta http-equiv="Content-Type" content="text/html; charset='.$encod.'">' .mb_substr($content,$headpos);
            }
            $content=mb_convert_encoding($content, 'HTML-ENTITIES', $encod);
        }
        return $content;
    }
    /**
     * 加入列表页获取数据
     * 2014.12.11
     */
    /**
     * export the product model's properties to array
     *
     * @return array
     */
    public function CategoryToArray()
    {
        $fetchconfig = $this->_config;
        $result = array();
        if(isset($fetchconfig[elements::CATEGORY_ITEM_NAME]) && $fetchconfig[elements::CATEGORY_ITEM_NAME]){
            $result[elements::CATEGORY_ITEM_NAME] = $this->getCategoryItemName();
        }

        if(isset($fetchconfig[elements::CATEGORY_ITEM_IMG]) && $fetchconfig[elements::CATEGORY_ITEM_IMG])
            $result[elements::CATEGORY_ITEM_IMG] = $this->getCategoryItemImg();

        if(isset($fetchconfig[elements::CATEGORY_ITEM_URL]) && $fetchconfig[elements::CATEGORY_ITEM_URL])
            $result[elements::CATEGORY_ITEM_URL] = $this->getCategoryItemUrL();



        if(isset($fetchconfig[elements::CATEGORY_ITEM_OPRICE]) && $fetchconfig[elements::CATEGORY_ITEM_OPRICE])
            $result[elements::CATEGORY_ITEM_OPRICE] = $this->getCategoryItemOprice();

        if(isset($fetchconfig[elements::CATEGORY_ITEM_DPRICE]) && $fetchconfig[elements::CATEGORY_ITEM_DPRICE])
            $result[elements::CATEGORY_ITEM_DPRICE] = $this->getCategoryItemDprice();

        if(isset($fetchconfig[elements::CATEGORY_ITEM_HOT]) && $fetchconfig[elements::CATEGORY_ITEM_HOT])
            $result[elements::CATEGORY_ITEM_HOT] = $this->getCategoryItemHot();

          if(isset($fetchconfig[elements::CATEGORY_ITEM_SALE]) && $fetchconfig[elements::CATEGORY_ITEM_SALE])
            $result[elements::CATEGORY_ITEM_SALE] = $this->getCategoryItemSale();

        if(isset($fetchconfig[elements::CATEGORY_ITEM_REVIEWS]) && $fetchconfig[elements::CATEGORY_ITEM_REVIEWS])
            $result[elements::CATEGORY_ITEM_REVIEWS] = $this->getCategoryItemReviews();

        if(isset($fetchconfig[elements::CATEGORY_ITEM_AREA]) && $fetchconfig[elements::CATEGORY_ITEM_AREA])
            $result[elements::CATEGORY_ITEM_AREA] = $this->getCategoryItemArea();

        if(isset($fetchconfig[elements::CATEGORY_ITEM_SKUID]) && $fetchconfig[elements::CATEGORY_ITEM_SKUID])
            $result[elements::CATEGORY_ITEM_SKUID] = $this->getCategoryItemSkuid();
        return $result;
    }
}
