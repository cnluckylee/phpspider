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
				elements::CATEGORY_MATCH_PREG => '/"name": "(.*)", "spell": "(.*)", "url": "(.*)"/',
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
                    elements::CATEGORY_ITEM_URL =>'//p[@class="housetitle"]/a/@href||2',
                    elements::CATEGORY_ITEM_OPRICE =>'//dd[@class="money"]/text()||2',
                    elements::CATEGORY_ITEM_DPRICE =>'//div[@class="house"]',
                    elements::CATEGORY_ITEM_SALE =>'//dd[@class="pfm"]/text()||2',
                    elements::CATEGORY_ITEM_REVIEWS =>'',
                    elements::CATEGORY_ITEM_HOT =>'//p[@class="grey"]/text()||2',//更新时间
                    elements::CATEGORY_ITEM_SKUID =>'//p[@class="housetitle"]/a/@href||2',
                    elements::CATEGORY_ITEM_AREA =>'.//dl/dt/p[2]/text()',//小区地址
                    elements::CATEGORYCOMMON =>array(
                        'imgurl' =>'//div[@class="pic"]/a/img/@src||2',
                    ),
                )

		),
		// item config
		elements::ITEM_TITLE => '//div[@class="title"]/h1/text()||1',
		elements::ITEM_SKUID => '//input[@id="HouseID"]/@value||1',
		elements::ITEM_SALES => '//ul[@class="cont02 mb10"]/li[2]/a/text()||1',
		elements::ITEM_PRICE_URL => '//div[@class="imgWP"]//a[@class="mr10"][1]/@href||1',
		elements::ITEM_STATUS => '',
        elements::ITEM_COMMENT_NUMBER_ALL =>'//div[@class="title"]/p',
        elements::ITEM_COMMENT_NUMBER_DISSATISFY=>'//div[@class="title"]/p',
        elements::ITEM_COMMENT_NUMBER_DISSATISFY=>'//div[@class="title"]/p',
        elements::ITEM_PROMOTION=>'//dl[@class="mt10"]/dd',
        elements::ITEMCOMMON=>array(
            'City'=>'//input[@id="talkCity"]/@value||1',
            'Price'=>'//span[@class="yellow21b"]/text()||1',
            'FirstPrice'=>'//dd[@class="gray6"][1]/span/text()||1',
            'Property'=>'//div[@class="inforTxt"]/dl[2]/dd[7]/text()||1',
            'Name'=>'//input[@id="talkProjName"]/@value||1',
            'BuildYear'=>'//div[@class="inforTxt"]/dl[2]/dd[1]/text()||1',
            'Face'=>'//div[@class="inforTxt"]/dl[2]/dd[2]/text()||1',
            'Size'=>'//input[@id="talkMianJi"]/@value||1',//户型
            'Decoration'=>'//div[@class="inforTxt"]/dl[2]/dd[5]/text()||1',//装修
            'AgentID'=>'//input[@id="talkAgentID"]/@value||1',
            'AgentTel'=>'//input[@id="talkAgentPhone"]/@value||1',
            'AgentName'=>'//input[@id="talkAgentName"]/@value||1',
            'Apartment'=>'//input[@id="talkHuXing"]/@value||1',//户型
            'Floor'=>'//div[@class="inforTxt"]/dl[2]/dd[3]/text()||1',//楼层
            'Structure'=>'//div[@class="inforTxt"]/dl[2]/dd[4]/text()||1',//结构
            'Facilities'=>'//span[@class="sheshi"]/text()||1',//配套设施
            'BuildCategory'=>'//div[@class="inforTxt"]/dl[2]/dd[6]/text()||1',//建筑类别
            'LookTime'=>'//div[@class="inforTxt"]/dl[2]/dd[9]/text()||1',//看房时间
        ),

		elements::STID => 113,
        elements::HTML_ZIP =>'gzip',
		elements::DATASOURCE => '1',
		elements::COLLECTION_ITEM_NAME => 'soufunesflist2_items',
		elements::COLLECTION_CATEGORY_NAME => 'soufunesflist2_category',
        elements::ITEMPAGECHARSET => 'gbk',
        elements::CHARSET => 'utf-8',
		elements::MANAGER => 'living',
		elements::UPDATEDATA=>array(
				elements::ITEM_DPRICE,
				elements::ITEM_OPRICE,
		),
);
