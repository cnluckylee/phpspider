<?php
/**
 * 针对北京上海
 */
$siteconfig = array(
		elements::TYPE => 'fulldata',
		elements::NAME => '乐居网',
		elements::DB => array(
				'mongodb' => array(
						elements::HOST => 'mongo.wcc.cc',
						elements::PORT => '3376',
						elements::TIMEOUT => 0,
						elements::DBNAME => 'leju'
				)
		),
		elements::CATEGORY => array(
                elements::CATEGORY_URL => 'http://esf.baidu.com/',
                elements::CATEGORY_MATCH_PREG => '/href="http:\/\/esf.baudy.com/(\w+)">(.*?)<\/a>/',
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
                    elements::BASE_URL => '//div[@id="esfsh_71"]//a[contains(@href,"links.htm")]/@href||1',

                )

		),
		// item config
		elements::ITEM_TITLE => '//div[@class="titlebg"]/span/text()||1',
        elements::ITEMCOMMON =>array(
        ),
		elements::ITEM_SOURCE_CATEGORY_ID => '//div[@class="hsource-intro-infos pt10 pl20 fl"]//li[2]/text()||1',//户型
		elements::ITEM_SOURCE_CATEGORY_NAME=> '//div[@class="hsource-intro-infos pt10 pl20 fl"]//li[3]/span[2]/text()||1',//楼层
		elements::ITEM_SKUID => '//div[@class="bread"]/div[@class="f"]/text()||1',
		elements::ITEM_NAME =>'//div[@class="hsource-intro-infos pt10 pl20 fl"]//li[4]/text()||1',//朝向
		elements::ITEM_SOURCE_BRAND_ID =>'//div[@class="hsource-intro-infos pt10 pl20 fl"]//li[5]/text()||1',//类型
		elements::ITEM_SOURCE_BRAND_NAME =>'//li[@class="CommunityName"]/a/text()||1',
		elements::ITEM_SOURCE_SELLER_ID =>'//a[@rel="external nofollow"]/@href||1',
		elements::ITEM_SOURCE_SELLER_NAME => '//p[@class="mt5 bold c_000 tc"]/a[@rel="external nofollow"]/text()||1',
		elements::ITEM_IMAGE_URL => '//span[@class="phone-num"]/text()||1',//Tel
		elements::ITEM_PROMOTION => '//table[@class="new-community-info-table"]//tr',
		elements::STID => 123,
        elements::HTML_ZIP =>'gzip',
		elements::DATASOURCE => '1',
		elements::COLLECTION_ITEM_NAME => 'leju_esf_list2_items',
		elements::COLLECTION_CATEGORY_NAME => 'leju_esf_list2_category',
        elements::ITEMPAGECHARSET => 'utf-8',
        elements::CHARSET => 'utf-8',
		elements::MANAGER => 'living',
		elements::UPDATEDATA=>array(
				elements::ITEM_DPRICE,
				elements::ITEM_OPRICE,
		),
);
