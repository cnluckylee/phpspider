<?php

$siteconfig = array(
		elements::TYPE => 'fulldata',
		elements::NAME => '搜房网',
		elements::DB => array(
				'mongodb' => array(
						elements::HOST => 'mongo.wcc.cc',
						elements::PORT => '3376',
						elements::TIMEOUT => 0,
						elements::DBNAME => 'soufun'
				)
		),
		elements::CATEGORY => array(
				elements::CATEGORY_URL => 'http://img1.soufun.com/secondhouse/image/esfnew/scripts/citys.js?v=3.201412041',
				elements::CATEGORY_MATCH_PREG => '/"name": "(.*)", "spell": "(.*)", "url": "http:\/\/esf.(\w+)\.soufun\.com\/"/',
				elements::CATEGORY_MATCH_MATCH => array('name'=>1,'cid'=>3),
				elements::CATEGORY_GROUP_SIZE => 2,
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
                    elements::CATEGORY_ITEM_SKUID =>'//p[@class="housetitle"]/a/@href||2',
                    elements::CATEGORY_ITEM_OPRICE =>'//dd[@class="money"]/text()||2',
                    elements::CATEGORY_ITEM_DPRICE =>'//div[@class="house"]',
                    elements::CATEGORY_ITEM_SALE =>'//dd[@class="pfm"]/text()||2',
                    elements::CATEGORY_ITEM_HOT =>'//p[@class="grey"]/text()||2',
                    elements::CATEGORY_ITEM_AREA =>'.//dl/dt/p[2]/text()',
                ),
                elements::CATEGORYCOMMON=>array(
                    "Price"=>'//strong[@class="redfont"]/text()||2',

                )

		),
		// item config
		elements::ITEM_TITLE => '//input[@id="talkTitle"]/@value||1',
		elements::ITEM_SKUID => '//input[@id="HouseID"]/@value||1',
		elements::ITEM_PROMOTION => '//ul[@class="Huxing floatl"]/li',
        elements::ITEM_CHARACTERS =>'//p[@class="gray9"]/text()||1',
        elements::ITEM_COMMENT_NUMBER_DISSATISFY=>'//p[@class="gray9"]/text()',
         elements::ITEMCOMMON=>array(
            'City'=>'//input[@id="talkCity"]/@value||1',
            'Price'=>'//input[@id="talkPrice"]/@value||1',
            'Property'=>'//div[@class="inforTxt"]/dl[2]/dd[7]/text()||1',
            'Name'=>'//input[@id="talkProjName"]/@value||1',
            'Size'=>'//input[@id="talkMianJi"]/@value||1',//户型
            'AgentID'=>'//input[@id="talkAgentID"]/@value||1',
            'AgentTel'=>'//input[@id="talkAgentPhone"]/@value||1',
            'AgentName'=>'//input[@id="talkAgentName"]/@value||1',
            'Apartment'=>'//input[@id="talkHuXing"]/@value||1',//户型
        ),
		elements::STID => 113,
        elements::HTML_ZIP =>'gzip',
		elements::DATASOURCE => '1',
		elements::COLLECTION_ITEM_NAME => 'soufunzflist_items',
		elements::COLLECTION_CATEGORY_NAME => 'soufunzflist_category',
        elements::ITEMPAGECHARSET => 'gbk',
        elements::CHARSET => '',
		elements::MANAGER => 'living',
		elements::UPDATEDATA=>array(
				elements::ITEM_DPRICE,
				elements::ITEM_OPRICE,
		),
);
