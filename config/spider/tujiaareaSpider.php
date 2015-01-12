<?php

$siteconfig = array(
		elements::TYPE => 'fulldata',
		elements::NAME => '途家 地区',
		elements::DB => array(
				'mongodb' => array(
						elements::HOST => 'mongo.wcc.cc',
						elements::PORT => '3376',
						elements::TIMEOUT => 0,
						elements::DBNAME => 'tujia'
				)
		),
		elements::CATEGORY => array(
				elements::CATEGORY_URL => 'http://www.tujia.com/',
				elements::CATEGORY_MATCH_PREG => '/"spell": "(\w+)", "url": "(.*)"/',
				elements::CATEGORY_MATCH_MATCH => array('name'=>1,'cid'=>2),
				elements::CATEGORY_GROUP_SIZE => 1,
				elements::CATEGORY_LIST_URL => 'http://esf.#job.fang.com/agenthome/',
				elements::CATEGORY_LIST_PAGES_URL => 'http://esf.#job.fang.com/agenthome/-i3#i/',
				elements::CATEGORY_LIST_PREG => '//span[@class="fy_text"]||/\/(\d+)/',
                elements::TRANSFORM => false,
                elements::TRANSFORMADDSPECIL => '',
				elements::CATEGORY_LIST_MATCH => 1,
				elements::CATEGORY_PAGE_START => 1,
				elements::CATEGORY_LIST_GOODS_PREG => '//div[@class="qxName"]/a/@href||2',
				elements::CATEGORY_LIST_GOODS_Match => 1,
                elements::CATEGORY_MATCHING => 'xpath',
                elements::CATEGORY_NO_ADD_PAGE => true,
                elements::CATEGORY_ITEM_PREG => array(
                    elements::CATEGORY_ITEM_MATCHING =>'xpath',
                    elements::CATEGORY_ITEM_NAME =>'//div[@class="qxName"]/a/text()||2',
                    elements::CATEGORY_ITEM_URL =>'//div[@class="qxName"]/a/@href||2',
                    elements::CATEGORY_ITEM_SKUID =>'//div[@class="qxName"]/a/@href||2',
                    elements::BASE_URL => '//div[@id="esfsh_71"]//a[contains(@href,"links.htm")]/@href||1',
                )

		),
		// item config
		elements::ITEM_TITLE => '//p[@id="shangQuancontain"]/a/text()||2',
        elements::ITEM_PRICE_URL =>   '//p[@id="shangQuancontain"]/a/@href||2',//详情url
        elements::ITEM_SKUID =>'//div[@class="bread"]/a[3]/text()||1',
		elements::BASE_URL => 'http://sh.soufun.com/',
        elements::ITEM_BARCODE =>'//div[@id="dsy_H01_04"]/div/a/@href||1',
        elements::ITEM_OPRICE =>'//ul[@class="info ml25"]/li[1]/p/a/text()||2',
        elements::ITEM_DPRICE =>'//ul[@class="info ml25"]/li[2]/a/text()||2',
        elements::ITEM_CHARACTERS =>'//li[@id="dsinfrom"]/a/text()||2',
		elements::STID => 114,
        elements::HTML_ZIP =>'gzip',
		elements::DATASOURCE => '1',
        elements::TOTALPAGES =>1,
        elements::BASE_URL => '//div[@id="esfsh_71"]//a[contains(@href,"links.htm")]/@href||1',
		elements::COLLECTION_ITEM_NAME => 'Tujia_Area_Items',
		elements::COLLECTION_CATEGORY_NAME => 'tujia_area',
        elements::ITEMPAGECHARSET => 'utf-8',
        elements::CHARSET => 'utf-8',
		elements::MANAGER => 'living',
		elements::UPDATEDATA=>array(
				elements::ITEM_DPRICE,
				elements::ITEM_OPRICE,
		),
);