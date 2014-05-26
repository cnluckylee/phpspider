<?php

$siteconfig = array(
    elements::TYPE => 'fulldata',
    elements::NAME => '苏宁电器',
   
    elements::DB => array(
      'mongodb' => array(
	            elements::HOST => 'mongo.wcc.cc',
	            elements::PORT => '27017',
	            elements::TIMEOUT => 0,
	            elements::DBNAME => 'wcc_online_data',
       		)
    ),
    elements::CATEGORY => array(
        elements::CATEGORY_URL => 'http://www.suning.com/emall/pgv_10052_10051_1_.html',
        elements::CATEGORY_MATCH_PREG => '/class="searchCity"\sid="(\d+)"\s+href="http:\/\/list\.suning\.com\/(.*)\.html" title="(.*)"/iUs',
        elements::CATEGORY_MATCH_MATCH => array('name'=>3,'cid'=>1),
        elements::CATEGORY_GROUP_SIZE => 6,
        elements::CATEGORY_LIST_URL => 'http://search.suning.com/emall/strd.do?ci=#job&cityId=9264',
        elements::CATEGORY_LIST_PAGES_URL => 'http://list.suning.com/0-#job-#i-1-0-9264.html',//苏宁大脑有病，经常变动showProductList，有时需要改成showProductListNew								 
    	elements::CATEGORY_LIST_PREG => '/"pageNumbers":"(.*)"/iUs',
        elements::CATEGORY_LIST_MATCH => 1,
        elements::CATEGORY_LIST_GOODS_PREG => '/http:\/\/product\.suning\.com\/(.*)\.html/iUs',
        elements::CATEGORY_LIST_GOODS_Match => 0
    ),
    elements::ITEM_SOURCE_CATEGORY_ID => '/"category3":"(\d+)?"/||1',
    elements::ITEM_SOURCE_CATEGORY_NAME => '/{"category1":"(.*)"uuid"/||1',
    elements::ITEM_SKUID => '/"partNumber":"(\d+)"/iUs||1',
    elements::ITEM_TITLE => '/class="pro-name">(.*?)<\/p>/||1',
    elements::ITEM_IMAGE_URL => '/data-src="(.*?)"\s+alt="图片说明"/||1',
    elements::ITEM_SOURCE_BRAND_NAME => '',
    elements::ITEM_OPRICE => '/"currPrice":\'(.*?)\'/||1',
    elements::ITEM_DPRICE => '/"currPrice":\'(.*?)\'/||1',
    elements::ITEM_WAPURL => '/',
    elements::ITEM_STATUS => '/inventoryText">([^<]+)<\/div>/||1',
    elements::ITEM_CHARACTERS => '/class="Imgpip">\s<span>(.*)：<\/span>\s<\/div>\s<\/td>\s+<td width="72%" class="td1">(.*)<\/td>/iUs||2||{"1":"key","2":"val"}',
    elements::ITEM_DESCRIPTION => '/class="Imgpip">\s<span>(.*)：<\/span>\s<\/div>\s<\/td>\s+<td width="72%" class="td1">(.*)<\/td>/iUs||2||{"1":"key","2":"val"}',
	elements::ITEM_ISBN =>'/sn.isbn = \'(.*?)\';/||1',
        
    elements::BASE_URL => 'http://www.suning.cn/',
    elements::STID => 6,
    elements::DATASOURCE => '1',
    elements::COLLECTION_ITEM_NAME => 'wcc_online_data',
    elements::COLLECTION_CATEGORY_NAME => 'wcc_suning_category',
    elements::CHARSET => '',
	elements::ITEMPAGECHARSET =>'',
    elements::MANAGER => 'bill',
		elements::UPDATEDATA=>array(
		elements::ITEM_DPRICE,
		elements::ITEM_OPRICE
		),
);
