<?php

$siteconfig = array(
		elements::TYPE => 'fulldata',
		elements::NAME => '蛮便宜',
		elements::DB => array(
				'mongodb' => array(
						elements::HOST => 'mongo.wcc.cc',
						elements::PORT => '27017',
						elements::TIMEOUT => 0,
						elements::DBNAME => 'manpianyi'
				)
		),
		elements::CATEGORY => array(
				elements::CATEGORY_URL => 'http://www.manpianyi.com/',
				elements::CATEGORY_MATCH_PREG => '/<a href="(\w+)\.html" target="_blank">(.*?)<\/a>/',
				elements::CATEGORY_MATCH_MATCH => array('name'=>2,'cid'=>1),
				elements::CATEGORY_GROUP_SIZE => 10,
				elements::CATEGORY_LIST_URL => 'http://www.manpianyi.com/#job.html',
				elements::CATEGORY_LIST_PAGES_URL => 'http://www.manpianyi.com/#job_#i.html',
				elements::CATEGORY_LIST_PREG => '/\_(\d+)\.html">尾页<\/a>/iUs',
				elements::CATEGORY_LIST_MATCH => 1,
				elements::CATEGORY_PAGE_START => 1,
				elements::CATEGORY_LIST_GOODS_PREG => '/key="(\d+?)"/iUs',
				elements::CATEGORY_LIST_GOODS_Match => 1
		),
		// item config
		elements::ITEM_TITLE => '/<div class="p-name">(.*)<\/div>/||1',
		elements::ITEM_SOURCE_CATEGORY_ID => '/\[(.*)\],/||1',
		elements::ITEM_SOURCE_CATEGORY_NAME=> '',
		elements::ITEM_SKUID => '/skuid: (\d+),/||1',
		elements::ITEM_NAME =>'/<li title="(.*)">/||1',
		elements::ITEM_SOURCE_BRAND_ID =>'/brand: (\d+),/||1',
		elements::ITEM_SOURCE_BRAND_NAME =>'/class="breadcrumb".*?pinpai.*?>(.*?)<\/a>/si||1',
		elements::ITEM_SOURCE_SELLER_ID =>'/<a href="http:\/\/mall\.jd\.com\/index-(\d+).html" target="_blank">(.*)<\/a>/||1',
		elements::ITEM_SOURCE_SELLER_NAME => '',
		elements::ITEM_IMAGE_URL => '/jqimg="(.*?)"\/>/||1',
		elements::ITEM_PROMOTION => '',
		elements::ITEM_SALES => '',
		elements::ITEM_DPRICE => '',
		elements::ITEM_OPRICE => '',
		elements::ITEM_PRICE_URL => '',
		elements::ITEM_STATUS => '',
		elements::ITEM_DESCRIPTION =>'/<td class="tdTitle">(.*)<\/td><td>(.*)<\/td>/iUs||2||{"1":"key","2":"val"}',
		elements::ITEM_CHARACTERS =>'/class="detail-list"(.*?)<\/ul>/si||1',
		elements::ITEM_ISBN => '',
		elements::ITEM_BARCODE => '',
		elements::BASE_URL => 'http://www.manpianyi.com/',
		elements::STID => 111,
		elements::DATASOURCE => '1',
		elements::COLLECTION_ITEM_NAME => 'manpianyi',
		elements::COLLECTION_CATEGORY_NAME => 'wcc_manpianyi_category',
        elements::ITEMPAGECHARSET => '',
        elements::CHARSET => '',
		elements::MANAGER => 'living',
		elements::UPDATEDATA=>array(
				elements::ITEM_DPRICE,
				elements::ITEM_OPRICE,
				elements::ITEM_COMMENT_NUMBER_ALL,
				elements::ITEM_COMMENT_NUMBER_GENERAL,
				elements::ITEM_COMMENT_NUMBER_SATISFY,
				elements::ITEM_COMMENT_NUMBER_DISSATISFY
		),
);
