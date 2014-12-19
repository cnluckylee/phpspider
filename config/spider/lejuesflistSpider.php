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
				elements::CATEGORY_URL => 'http://img1.soufun.com/secondhouse/image/esfnew/scripts/citys.js?v=3.201412041',
				elements::CATEGORY_MATCH_PREG => '/"name": "(.*)", "spell": "(.*)", "url": "http:\/\/esf.(\w+)\.soufun\.com\/"/',
				elements::CATEGORY_MATCH_MATCH => array('name'=>1,'cid'=>3),
				elements::CATEGORY_GROUP_SIZE => 2,
				elements::CATEGORY_LIST_URL => 'http://#job.esf.fang.com/agenthome/',
				elements::CATEGORY_LIST_PAGES_URL => 'http://#job.esf.fang.com/agenthome/-i3#i/',
				elements::CATEGORY_LIST_PREG => '//span[@class="all"]/text()||/(\d+)/',
				elements::CATEGORY_LIST_MATCH => 1,
				elements::CATEGORY_PAGE_START => 1,
				elements::CATEGORY_LIST_GOODS_PREG => '//div[@class="text_current"]/a/@href||2',
                elements::TRANSFORM => false,
                elements::TRANSFORMADDSPECIL =>'',
				elements::CATEGORY_LIST_GOODS_Match => 1,
                elements::CATEGORY_MATCHING => 'xpath',
                elements::CATEGORY_ITEM_PREG => array(
                    elements::CATEGORY_ITEM_MATCHING =>'xpath',
                    elements::CATEGORY_ITEM_NAME =>'//div[@class="text_current"]/a/text()||2',//title
                    elements::CATEGORY_ITEM_IMG =>'//div[@class="text_current_left"]/a/text()||2',//小区名称
                    elements::CATEGORY_ITEM_URL =>'//div[@class="text_current"]/a/@href||2',//房源链接
                    elements::CATEGORY_ITEM_OPRICE =>'//div[@class="text_current_right"]/text()||2',//售价
                    elements::CATEGORY_ITEM_DPRICE =>'//div[@class="text_current_right2"]/text()||2',//大小
                    elements::CATEGORY_ITEM_SALE =>'//div[@class="text_current_left2"]/span[2]/text()||2',//更新时间
                    elements::CATEGORY_ITEM_REVIEWS =>'//div[@class="text_current_left2"]/span[2]/text()||2',//更新时间
                    elements::CATEGORY_ITEM_HOT =>'',
                    elements::CATEGORY_ITEM_SKUID =>'//div[@class="text_current"]/a/@href||2',
                    elements::CATEGORY_ITEM_AREA =>'//div[@class="text_current_left"]/span[2]/text()||2',//小区地址
                    elements::BASE_URL => '//div[@class="favorite"]/a[@class="shouye"]/@href||1',

                )

		),
		// item config
		elements::ITEM_TITLE => '//div[@class="titlebg"]/span/text()||1',
        elements::ITEMCOMMON =>array(
            'Price'=>'//span[@class="f12br hIPrice"]/text()/||1',//总价
            'DPrice'=>'//div[@class="HouseInfo_T"]//li[2]/strong/text()||1',//单价
            'Area'=>'//div[@class="HouseInfo_T"]//li[3]/strong/text()||1',//面积
            'SaleName'=>'//span[@class="Tel_n"]/a/text()||1',
            'Apartment' =>'//li[@class="CommunityIntr"][1]/span[@class="hs"]/text()||1',//户型
            'Decoration' =>'//li[@class="CommunityIntr02"][1]/span[@class="hs"]/text()||1',//装修
            'Direction' =>'//li[@class="CommunityIntr"][2]/span[@class="hs"]/text()||1',//朝向
            'BuildYear' =>'//li[@class="CommunityIntr02"][2]/span[@class="hs"]/text()||1',
            'floor' =>'//li[@class="CommunityIntr"][3]/span[@class="hs"]/text()||1',//朝向
            'Habitude' =>'//li[@class="CommunityIntr02"][3]/span[@class="hs"]/text()||1',//性质
            'Situation' =>'//li[@class="CommunityIntr"][4]/span[@class="hs"]/text()||1',//房屋现状
            'Property' =>'//li[@class="CommunityIntr02"][4]/span[@class="hs"]/text()||1',//产权
            'Case' => '//li[@class="CommunityIntr"][5]/span[@class="hs"]/text()||1',//看房情况
            'City' =>'//div[@class="bread"]//a[2]/text()||1',
            'District'=>'//div[@class="bread"]//a[3]/text()||1',
        ),
		elements::ITEM_SOURCE_CATEGORY_ID => '',
		elements::ITEM_SOURCE_CATEGORY_NAME=> '',
		elements::ITEM_SKUID => '//div[@class="bread"]/div[@class="f"]/text()||1',
		elements::ITEM_NAME =>'',
		elements::ITEM_SOURCE_BRAND_ID =>'',
		elements::ITEM_SOURCE_BRAND_NAME =>'//li[@class="CommunityName"]/a/text()||1',
		elements::ITEM_SOURCE_SELLER_ID =>'',
		elements::ITEM_SOURCE_SELLER_NAME => '',
		elements::ITEM_IMAGE_URL => '//li[@class="CommunityName"]/a/@href||1',
		elements::ITEM_PROMOTION => '//div[@class="bread"]/div[@class="f"]/text()||1',
		elements::STID => 123,
        elements::HTML_ZIP =>'gzip',
		elements::DATASOURCE => '1',
		elements::COLLECTION_ITEM_NAME => 'leju_esf_list_items',
		elements::COLLECTION_CATEGORY_NAME => 'leju_esf_list_category',
        elements::ITEMPAGECHARSET => 'gbk',
        elements::CHARSET => '',
		elements::MANAGER => 'living',
		elements::UPDATEDATA=>array(
				elements::ITEM_DPRICE,
				elements::ITEM_OPRICE,
		),
);
