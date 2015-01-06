<?php
/**
 * 针对北京上海
 */
$siteconfig = array(
		elements::TYPE => 'fulldata',
		elements::NAME => '乐居网',
		elements::DB => array(
				'mongodb' => array(
						elements::HOST => 'mongo.salve1.cc',
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
		elements::ITEM_SKUID => '//div[@class="id"]/text()||1',
		elements::ITEM_PROMOTION => '//div[@class="about"][2]/div[@class="full"][3]/text()||1',
        elements::ITEMCOMMON=>array(
            'SourceUpdateTime'=>'//div[@class="time"]/text()||1',
        ),
		elements::STID => 123,
        elements::HTML_ZIP =>'gzip',
		elements::DATASOURCE => '1',
		elements::COLLECTION_ITEM_NAME => 'leju_zf_list_items',
		elements::COLLECTION_CATEGORY_NAME => 'leju_zf_list_category',
        elements::ITEMPAGECHARSET => 'gbk',
        elements::CHARSET => 'utf-8',
		elements::MANAGER => 'living',
		elements::UPDATEDATA=>array(
				elements::ITEM_DPRICE,
				elements::ITEM_OPRICE,
		),
);
