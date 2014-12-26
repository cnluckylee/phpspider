<?php

$siteconfig = array(
		elements::TYPE => 'fulldata',
		elements::NAME => '搜房新房网',
		elements::DB => array(
				'mongodb' => array(
						elements::HOST => 'mongo.wcc.cc',
						elements::PORT => '27017',
						elements::TIMEOUT => 0,
						elements::DBNAME => 'soufun'
				)
		),
		elements::CATEGORY => array(
				elements::CATEGORY_URL => 'http://js.soufunimg.com/homepage/new/family/css/citys.js?v=421824131436',
				elements::CATEGORY_MATCH_PREG => '/"spell": "(\w+)", "url": "http:\/\/(.*)"/',
				elements::CATEGORY_MATCH_MATCH => array('name'=>1,'cid'=>2),
				elements::CATEGORY_GROUP_SIZE => 1,
				elements::CATEGORY_LIST_URL => '',
				elements::CATEGORY_LIST_PAGES_URL => '',
				elements::CATEGORY_LIST_PREG => '/totalPage\s+=\s+(\d+);/',
				elements::CATEGORY_LIST_MATCH => 1,
				elements::CATEGORY_PAGE_START => 1,
                elements::TRANSFORM => false,
                elements::TRANSFORMADDSPECIL => '/',
				elements::CATEGORY_LIST_GOODS_PREG => '//ul[@class="district clearfix"]/li/a/@href||2',
				elements::CATEGORY_LIST_GOODS_Match => 1,
                elements::CATEGORY_MATCHING => 'xpath',
                elements::CATEGORY_NO_ADD_PAGE => true,
                elements::CATEGORY_ITEM_PREG => array(
                    elements::CATEGORY_ITEM_MATCHING =>'xpath',
                    elements::CATEGORY_ITEM_SKUID =>'//h2/a/@href||2',
                    elements::CATEGORY_ITEM_URL =>'//h2/a/@href||2',
                    elements::CATEGORY_ITEM_NAME =>'//h2/a/text()||2',
                    elements::CATEGORY_ITEM_AREA =>'.//p[@class="mt15"]/font/@title',
                    elements::CATEGORY_ITEM_DPRICE =>'//div[@class="sslalone"]',
                    elements::CATEGORY_ITEM_OPRICE =>'//span[@class="adbox"]/@onclick||2',
                    elements::CATEGORYCOMMON => array(
                        'Name' =>'//h2/a/@href||2',
                    )


                )

		),
    /**
     * 不需要item
     */
    elements::STID => 121,
        elements::HTML_ZIP =>'gzip',
		elements::DATASOURCE => '1',
        elements::TOTALPAGES =>1,
		elements::COLLECTION_ITEM_NAME => 'Soufunnewhouse_Area_Items',
		elements::COLLECTION_CATEGORY_NAME => 'soufunnewhouse_area',
        elements::ITEMPAGECHARSET => 'gbk',
        elements::CHARSET => 'gbk',
		elements::MANAGER => 'living',
		elements::UPDATEDATA=>array(
				elements::ITEM_DPRICE,
				elements::ITEM_OPRICE,
		),
);
