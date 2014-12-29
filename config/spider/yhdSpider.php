<?php

$siteconfig = array(
    elements::TYPE => 'fulldata',
    elements::NAME => '一号店',
    elements::DB => array(
        'mongodb' => array(
            elements::HOST => 'mongo.wcc.cc',
            elements::PORT => '3376',
            elements::TIMEOUT => 0,
            elements::DBNAME => 'wcc_online_data',
        )
    ),
    elements::CATEGORY => array(
        elements::CATEGORY_URL => 'http://www.yhd.com/marketing/allproduct.html',
        elements::CATEGORY_MATCH_PREG => '/<em><span><a\s+href\s*=\s*"http:\/\/(?:w{3}|search)\.yhd\.com\/ctg\/s2\/([^\/]+)[^"]*"[^>]*>([^<]+)<\/a>/',
        elements::CATEGORY_MATCH_MATCH => 1,
        elements::CATEGORY_GROUP_SIZE => 10,
         
    	/**
    	* 如果不用api分类，则使用该正则匹配
    	*/
//         elements::CATEGORY_LIST_PAGES_URL => 'http://www.yhd.com/ctg/s2/#job/b/a-s1-v0-p#i-price-d0-f0-m1-rt0-pid0-mid0-k/', 
//     	elements::CATEGORY_LIST_URL => 'http://www.yhd.com/ctg/s2/#job/',
    	/**
    	* 如果用api分类，则使用该正则匹配
    	*/
   			elements::CATEGORY_LIST_PAGES_URL => 'http://www.yhd.com/ctg/s2/c#job-0/b/a-s1-v0-p#i-price-d0-f0-m1-rt0-pid0-mid0-k/',
    		elements::CATEGORY_LIST_URL => 'http://www.yhd.com/ctg/s2/c#job-0/',
        elements::CATEGORY_LIST_PREG => '/<input\s+id\s*=\s*"pageCountPage"\s+type\s*=\s*"hidden"\s+value\s*=\s*"(\d+)"\/>/',
        elements::CATEGORY_LIST_MATCH => 1,
    	elements::CATEGORY_PAGE_START =>1,
        elements::CATEGORY_LIST_GOODS_PREG => '/<div\s+class\s*=\s*"item_promotion_text"\s+id\s*=\s*"promostyle_([^"]+)"\s+title/',
        elements::CATEGORY_LIST_GOODS_Match => 1,
    	elements::CATEGORY_RUN =>0,
    ),
    elements::ITEM_TITLE => '/<h1\s+class\s*=\s*"prod_title"\s+id\s*=\s*"productMainName">([^<]+)<\/h1>/||1',
    elements::ITEM_SOURCE_CATEGORY_ID => '/var categoryIds = \[(.*)\]/||1',
    elements::ITEM_SOURCE_CATEGORY_NAME => '/detail_BreadcrumbNav_cat[^\']+\'\);">([^<]+)<i>/',
    elements::ITEM_IMAGE_URL => '/<img\s+id\s*=\s*"J_prodImg"\s+src\s*=\s*"([^"]+)"/||1',
    elements::ITEM_OPRICE => '/class\s*=\s*"oldprice"><del><em>[^<]+<\/em>([^<]+)<\/del><\/span>/||1',
    elements::ITEM_DPRICE => '/id\s*=\s*"current_price"><em>[^<]+<\/em>([^<]+)<em><\/em><\/span>/||1',
    //elements::ITEM_SALES => "/<div id=\"maypromationshow\"(.*)<strong>(\d+)<\/strong>/iUs", //???
    elements::ITEM_STATUS => '/hasStockNum"\s+value\s*=\s*"(\d+)">/||1',
    elements::ITEM_SOURCE_BRAND_ID => '/id\s*=\s*"brandID"\s+value\s*=\s*"([^"]+)"\/>/||1',
    elements::ITEM_SOURCE_BRAND_NAME => '/id\s*=\s*"brandName"\s+value\s*=\s*"([^"]+)">/||1',
    elements::ITEM_SOURCE_SELLER_ID => '/productMerchantId"\s+value\s*=\s*"([^"]+)"\/>/||1',
    elements::ITEM_CHARACTERS => '/detailparams\s*=\s*\{.*?pmId:([^,]+),.*?productId:([^,]+),.*?merchantId:([^,]+),.*?isYiHaoDian:([^,]+),.*?paramSignature:"([^"]+)",/s',
    elements::ITEM_DESCRIPTION => '/<tbody>(.*?)<\\\\\/tbody>/',
        
    elements::BASE_URL => 'http://www.yhd.com/',
    elements::STID => 3,
    elements::DATASOURCE => '1',
    elements::COLLECTION_ITEM_NAME => 'wcc_online_data',
    elements::COLLECTION_CATEGORY_NAME => 'wcc_yhd_category',
    elements::CHARSET => 'utf-8',
    elements::ITEMPAGECHARSET =>'utf-8',
    elements::MANAGER => 'living'
);
