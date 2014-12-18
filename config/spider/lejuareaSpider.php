<?php

$siteconfig = array(
		elements::TYPE => 'fulldata',
		elements::NAME => '乐居网',
		elements::DB => array(
				'mongodb' => array(
						elements::HOST => 'mongo.wcc.cc',
						elements::PORT => '27017',
						elements::TIMEOUT => 0,
						elements::DBNAME => 'leju'
				)
		),
		elements::CATEGORY => array(
				elements::CATEGORY_URL => 'http://sh.esf.leju.com/agent/',
				elements::CATEGORY_MATCH_PREG => '/href="http:\/\/(\w+?).esf.eju.com\/" target="_blank">(.*)<\/a>/',
				elements::CATEGORY_MATCH_MATCH => array('name'=>2,'cid'=>1),
				elements::CATEGORY_GROUP_SIZE => 1,
				elements::CATEGORY_LIST_URL => 'http://sh.esf.leju.com/agent/',
				elements::CATEGORY_LIST_PAGES_URL => 'http://sh.esf.leju.com/agent/m3-n#i/',
				elements::CATEGORY_LIST_PREG => '//span[@class="all"]/text()||/\/(\d+)/',
				elements::CATEGORY_LIST_MATCH => 1,
				elements::CATEGORY_PAGE_START => 1,
				elements::CATEGORY_LIST_GOODS_PREG => '//div[@class="item"]/a/@href||2',
				elements::CATEGORY_LIST_GOODS_Match => 1,
                elements::CATEGORY_MATCHING => 'xpath',
                elements::CATEGORY_ITEM_PREG => array(
                    elements::CATEGORY_ITEM_MATCHING =>'xpath',
                    elements::CATEGORY_ITEM_SKUID =>'//div[@class="item"]/a/text()||2',
                    elements::CATEGORY_ITEM_IMG =>'',
                    elements::CATEGORY_ITEM_URL =>'//div[@class="item"]/a/@href||2',
                    elements::CATEGORY_ITEM_OPRICE =>'',
                    elements::CATEGORY_LIST_GOODS_PREG =>'',
                    elements::CATEGORY_ITEM_DPRICE =>'',
                    elements::CATEGORY_ITEM_SALE =>'',
                    elements::COMMON =>array(
//                        'BaseUrl'=>'//div[@class="my_position"]/a[1]/@href||1',
                    ),
                )

		),
		// item config
		elements::ITEM_TITLE => '//div[@class="area"]/a/text()||2',
        elements::ITEM_PROMOTION =>   '//div[@class="area"]/a/@href||2',
        elements::ITEM_SKUID =>'//div[@class="line "]/div[@class="item"]/a[@class="hover"]/text()||1',
		elements::BASE_URL => 'http://sh.esf.leju.com/',
        elements::ITEM_BARCODE =>'',
        elements::ITEM_OPRICE =>'//div[@class="item_jingji"]/a/text()||2',
        elements::ITEM_DPRICE =>'//div[@class="item_jingji"]/a/@href||2',
        elements::ITEM_CHARACTERS =>'',
		elements::STID => 121,
        elements::HTML_ZIP =>'gzip',
		elements::DATASOURCE => '1',
        elements::TOTALPAGES =>1,
		elements::COLLECTION_ITEM_NAME => 'Leju_Area_Items',
		elements::COLLECTION_CATEGORY_NAME => 'leju_area',
        elements::ITEMPAGECHARSET => 'gb2312',
        elements::CHARSET => 'gb2312',
		elements::MANAGER => 'living',
		elements::UPDATEDATA=>array(
				elements::ITEM_DPRICE,
				elements::ITEM_OPRICE,
		),
);
