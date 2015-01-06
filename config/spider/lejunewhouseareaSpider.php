<?php

$siteconfig = array(
		elements::TYPE => 'fulldata',
		elements::NAME => '乐居新房网',
		elements::DB => array(
				'mongodb' => array(
						elements::HOST => 'mongo.salve1.cc',
						elements::PORT => '3376',
						elements::TIMEOUT => 0,
						elements::DBNAME => 'leju'
				)
		),
		elements::CATEGORY => array(
				elements::CATEGORY_URL => 'http://bj.house.sina.com.cn/cityguide/',
				elements::CATEGORY_MATCH_PREG => '/href="http:\/\/(\w+).house.sina.com.cn">(.*?)<\/a>/',
				elements::CATEGORY_MATCH_MATCH => array('name'=>2,'cid'=>1),
				elements::CATEGORY_GROUP_SIZE => 1,
				elements::CATEGORY_LIST_URL => 'http://www.leju.com/index.php?mod=sale_search&city=#job',
				elements::CATEGORY_LIST_PAGES_URL => 'http://www.leju.com/index.php?mod=sale_search&city=#job',
				elements::CATEGORY_LIST_PREG => '//span[@class="all"]/text()||/\/(\d+)/',
				elements::CATEGORY_LIST_MATCH => 1,
				elements::CATEGORY_PAGE_START => 1,
				elements::CATEGORY_LIST_GOODS_PREG => '//ul[@class="district clearfix"]/li/a/@href||2',
				elements::CATEGORY_LIST_GOODS_Match => 1,
                elements::CATEGORY_MATCHING => 'xpath',
                elements::CATEGORY_ITEM_PREG => array(
                    elements::CATEGORY_ITEM_MATCHING =>'xpath',
                    elements::CATEGORY_ITEM_SKUID =>'//div[@id="s_list"]/dl[2]/dd[2]/a/@href||2',
                    elements::CATEGORY_ITEM_URL =>'//div[@id="s_list"]/dl[2]/dd[2]/a/@href||2',
                    elements::CATEGORY_ITEM_NAME =>'//div[@id="s_list"]/dl[2]/dd[2]/a/text()||2',
                    elements::CATEGORYCOMMON =>array(
//                        'BaseUrl'=>'//div[@class="my_position"]/a[1]/@href||1',
                    ),
                )

		),
		// item config
		elements::ITEM_NAME => '//div[@class="area"]/a/text()||2',
        elements::ITEM_PRICE_URL => '//div[@class="area"]/a/@href||2',
        elements::ITEM_SKUID =>'//div[@class="line "]/div[@class="item"]/a[@class="hover"]/text()||1',
        elements::ITEM_OPRICE =>'//div[@class="item_jingji"]/a/text()||2',
        elements::ITEM_DPRICE =>'//div[@class="item_jingji"]/a/@href||2',
        elements::BASE_URL => 'http://sh.esf.leju.com/',

		elements::STID => 121,
        elements::HTML_ZIP =>'gzip',
		elements::DATASOURCE => '1',
        elements::TOTALPAGES =>1,
		elements::COLLECTION_ITEM_NAME => 'Lejunewhouse_Area_Items',
		elements::COLLECTION_CATEGORY_NAME => 'lejunewhouse_area',
        elements::ITEMPAGECHARSET => 'utf-8',
        elements::CHARSET => 'utf-8',
		elements::MANAGER => 'living',
		elements::UPDATEDATA=>array(
				elements::ITEM_DPRICE,
				elements::ITEM_OPRICE,
		),
);
