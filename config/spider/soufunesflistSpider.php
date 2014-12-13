<?php

$siteconfig = array(
		elements::TYPE => 'fulldata',
		elements::NAME => '搜房网',
		elements::DB => array(
				'mongodb' => array(
						elements::HOST => 'mongo.wcc.cc',
						elements::PORT => '27017',
						elements::TIMEOUT => 0,
						elements::DBNAME => 'soufun5'
				)
		),
		elements::CATEGORY => array(
				elements::CATEGORY_URL => 'http://img1.soufun.com/secondhouse/image/esfnew/scripts/citys.js?v=3.201412041',
				elements::CATEGORY_MATCH_PREG => '/"name": "(.*)", "spell": "(.*)", "url": "http:\/\/esf.(\w+)\.soufun\.com\/"/',
				elements::CATEGORY_MATCH_MATCH => array('name'=>1,'cid'=>3),
				elements::CATEGORY_GROUP_SIZE => 10,
				elements::CATEGORY_LIST_URL => 'http://esf.#job.fang.com/agenthome/',
				elements::CATEGORY_LIST_PAGES_URL => 'http://esf.#job.fang.com/agenthome/-i3#i/',
				elements::CATEGORY_LIST_PREG => '/\/(.*?)\n页/',
				elements::CATEGORY_LIST_MATCH => 1,
				elements::CATEGORY_PAGE_START => 1,
				elements::CATEGORY_LIST_GOODS_PREG => '//div[@class="pic"]/a/@href||2',
                elements::TRANSFORM => false,
                elements::TRANSFORMADDSPECIL =>'',
				elements::CATEGORY_LIST_GOODS_Match => 1,
                elements::CATEGORY_MATCHING => '',
                elements::CATEGORY_ITEM_PREG => array(
                    elements::CATEGORY_ITEM_MATCHING =>'xpath',
                    elements::CATEGORY_ITEM_NAME =>'//p[@class="housetitle"]/a/text()||2',
                    elements::CATEGORY_ITEM_IMG =>'//div[@class="pic"]/a/img/@src||2',
                    elements::CATEGORY_ITEM_URL =>'//p[@class="housetitle"]/a/@href||2',
                    elements::CATEGORY_ITEM_OPRICE =>'//dd[@class="money"]/text()||2',
                    elements::CATEGORY_ITEM_DPRICE =>'//div[@class="house"]',
                    elements::CATEGORY_ITEM_SALE =>'//dd[@class="pfm"]/text()||2',
                    elements::CATEGORY_ITEM_REVIEWS =>'',
                    elements::CATEGORY_ITEM_HOT =>'//p[@class="grey"]/text()||2',
                    elements::CATEGORY_ITEM_SKUID =>'//input[@id="talkPageValue"]/@value||1',
                    elements::CATEGORY_ITEM_AREA =>'//div[@id="esfsh_71"]//a[contains(@href,"links.htm")]/@href||1',
                )

		),
		// item config
		elements::ITEM_TITLE => '//div[@class="title"]/h1/text()||1',
		elements::ITEM_SOURCE_CATEGORY_ID => '//input[@id="talkAgentPhone"]/@value||1',
		elements::ITEM_SOURCE_CATEGORY_NAME=> '//div[@class="inforTxt"]/dl[2]/dd[9]/text()||1',
		elements::ITEM_SKUID => '//input[@id="HouseID"]/@value||1',
		elements::ITEM_NAME =>'//div[@class="inforTxt"]/dl[2]/dd[8]/text()||1',
		elements::ITEM_SOURCE_BRAND_ID =>'//div[@class="inforTxt"]/dl[2]/dd[7]/text()||1',
		elements::ITEM_SOURCE_BRAND_NAME =>'//div[@class="inforTxt"]/dl[2]/dd[6]/text()||1',
		elements::ITEM_SOURCE_SELLER_ID =>'//input[@id="AgentID"]/@value||1',
		elements::ITEM_SOURCE_SELLER_NAME => '//div[@class="inforTxt"]/dl[2]/dd[4]/text()||1',
		elements::ITEM_IMAGE_URL => '//div[@class="inforTxt"]/dl[2]/dd[3]/text()||1',
		elements::ITEM_PROMOTION => '//dd[@class="gray6"][3]/text()||1',
		elements::ITEM_SALES => '//ul[@class="cont02 mb10"]/li[2]/a/text()||1',
		elements::ITEM_DPRICE => '//span[@class="yellow21b"]/text()||1',
		elements::ITEM_OPRICE => '//dd[@class="gray6"][1]/span/text()||1',
		elements::ITEM_PRICE_URL => '//div[@class="imgWP"]//a[@class="mr10"][1]/@href||1',
		elements::ITEM_STATUS => '',
        elements::ITEM_MPRICE => '//div[@class="inforTxt"]/dl[2]/dd[5]/text()||1',
		elements::ITEM_DESCRIPTION =>'//dd[@class="gray6"][4]/span/text()||1',
		elements::ITEM_CHARACTERS =>'//input[@id="talkProjName"]/@value||1',
		elements::ITEM_ISBN => '//div[@class="inforTxt"]/dl[2]/dd[1]/text()||1',
		elements::ITEM_BARCODE => '//div[@class="inforTxt"]/dl[2]/dd[2]/text()||1',
        elements::ITEM_COMMENT_NUMBER_ALL =>'//div[@class="title"]/p',
        elements::ITEM_COMMENT_NUMBER_DISSATISFY=>'//div[@class="title"]/p',
        elements::ITEM_COMMENT_NUMBER_DISSATISFY=>'//div[@class="title"]/p',
        elements::ITEM_SOURCE_CATEGORY_ID =>'//span[@class="sheshi"]/text()||1',
		elements::BASE_URL => 'http://sh.soufun.com/',
		elements::STID => 113,
        elements::HTML_ZIP =>'gzip',
		elements::DATASOURCE => '1',
		elements::COLLECTION_ITEM_NAME => 'soufun_esf_list_items',
		elements::COLLECTION_CATEGORY_NAME => 'soufun_esf_list_category',
        elements::ITEMPAGECHARSET => 'gbk',
        elements::CHARSET => '',
		elements::MANAGER => 'living',
		elements::UPDATEDATA=>array(
				elements::ITEM_DPRICE,
				elements::ITEM_OPRICE,
		),
);
