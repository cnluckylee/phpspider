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
				elements::CATEGORY_LIST_PREG => '//span[@class="all"]/text()||/\/(\d+)/',
				elements::CATEGORY_LIST_MATCH => 1,
				elements::CATEGORY_PAGE_START => 1,
                elements::TRANSFORM => false,
                elements::TRANSFORMADDSPECIL => '',
				elements::CATEGORY_LIST_GOODS_PREG => '//ul[@class="district clearfix"]/li/a/@href||2',
				elements::CATEGORY_LIST_GOODS_Match => 1,
                elements::CATEGORY_MATCHING => 'xpath',
                elements::CATEGORY_ITEM_PREG => array(
                    elements::CATEGORY_ITEM_MATCHING =>'xpath',
                    elements::CATEGORY_ITEM_SKUID =>'//div[@id="sjina_C01_08"]//div[@class="con_cy fl ml20"][1]/a/@href||2',
                    elements::CATEGORY_ITEM_URL =>'//div[@id="sjina_C01_08"]//div[@class="con_cy fl ml20"][1]/a/@href||2',
                    elements::CATEGORY_ITEM_NAME =>'//div[@id="sjina_C01_08"]//div[@class="con_cy fl ml20"][1]/a/text()||2',

                )

		),
		// item config
		elements::ITEM_NAME => '//a[@class="fl"][1]/text()||1',
        elements::ITEM_SKUID =>'//a[@class="fl"][1]/text()||1',
        elements::ITEM_PROMOTION =>'//div[@id="sjina_C01_24"]/a/@href||2',
        elements::ITEM_CHARACTERS =>'//div[@id="sjina_C01_24"]/a/text()||2',
        elements::BASE_URL => 'http://sh.esf.leju.com/',
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
