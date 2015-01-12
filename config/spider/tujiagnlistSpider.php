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
				elements::CATEGORY_LIST_URL => 'http://www.tujia.com/#job/ajaxunitsearch/',
				elements::CATEGORY_LIST_PAGES_URL => 'http://www.tujia.com/#job/ajaxunitsearch/#i/?_=',
				elements::CATEGORY_LIST_PREG => '//div[@class="total-house-amount"]/span/||/\/(\d+)/',

                elements::TRANSFORMADDSPECIL => '',
				elements::CATEGORY_LIST_MATCH => 1,
				elements::CATEGORY_PAGE_START => 1,
				elements::CATEGORY_LIST_GOODS_PREG => '//div[@class="qxName"]/a/@href||2',
				elements::CATEGORY_LIST_GOODS_Match => 1,
                elements::CATEGORY_MATCHING => 'xpath',
                elements::CATEGORY_NO_ADD_PAGE => true,
                elements::CATEGORY_ITEM_PREG => array(
                    elements::CATEGORY_ITEM_MATCHING =>'xpath',
                    elements::CATEGORY_ITEM_NAME =>'//div[@class="house-name“]/h2/a/text()||2',
                    elements::CATEGORY_ITEM_URL =>'//div[@class="house-name"]/h2/a/@href||2',
                    elements::CATEGORY_ITEM_SKUID =>'//div[@class="house-name"]/h2/a/@href||2',
                    elements::CATEGORY_ITEM_HOT =>'/_(\d+).htm/',
                    elements::CATEGORY_ITEM_DPRICE =>'//span[@class="house-price"]/text()||2',
                    elements::CATEGORY_ITEM_OPRICE =>'.//div[@class="price-info-item"][2]/span/@data-money',
                    elements::CATEGORYCOMMON=>array(
                        'Type'=>'.//div[@class="house-datelist"]/span/@title',
                        'Book'=>'/近期预订(\d+)晚/',
                        'Reco'=>'/(.*)推荐/',
                        'Review'=>'/(\d+)条点评/',
                        'Score'=>'/(.*)\/5分/',
                    ),
                    elements::CATEGORY_ITEM_DISTRICT=>'//div[@class="house-htladdress"]/a[1]/text()||2',
                )

		),
		// item config
		elements::ITEM_TITLE => '//div[@class="house-info-group"]/h1/text()||1',
        elements::ITEM_SKUID =>'//strong[@class="number-box"]/text()||1',
		elements::ITEMCOMMON =>array(
            'Voucher'=>'//p[@id="bestProductvouchers"]/span/text()||1',
            'HotleName'=>'//a[@class="hotle-name"]/text()||1',
            'HotleUrl'=>'//a[@class="hotle-name"]/@href||1',
        ),

		elements::STID => 114,
        elements::HTML_ZIP =>'gzip',
		elements::DATASOURCE => '1',
        elements::TOTALPAGES =>1,
        elements::BASE_URL => '//div[@id="esfsh_71"]//a[contains(@href,"links.htm")]/@href||1',
		elements::COLLECTION_ITEM_NAME => 'Tujia_GN_Items',
		elements::COLLECTION_CATEGORY_NAME => 'tujia_gn_category',
        elements::ITEMPAGECHARSET => 'utf-8',
        elements::CHARSET => 'utf-8',
		elements::MANAGER => 'living',
		elements::UPDATEDATA=>array(
				elements::ITEM_DPRICE,
				elements::ITEM_OPRICE,
		),
);
