<?php

$siteconfig = array(
		elements::TYPE => 'fulldata',
		elements::NAME => '乐居经纪人',
		elements::DB => array(
				'mongodb' => array(
						elements::HOST => 'mongo.wcc.cc',
						elements::PORT => '27017',
						elements::TIMEOUT => 0,
						elements::DBNAME => 'leju'
				)
		),
		elements::CATEGORY => array(
				elements::CATEGORY_URL => '',
				elements::CATEGORY_MATCH_PREG => '',
				elements::CATEGORY_MATCH_MATCH => array(),
				elements::CATEGORY_GROUP_SIZE => 1,
				elements::CATEGORY_LIST_URL => 'http://esf.#job.fang.com/agenthome/',
				elements::CATEGORY_LIST_PAGES_URL => 'http://esf.#job.fang.com/agenthome/-i3#i/',
				elements::CATEGORY_LIST_PREG => '//div[@class="pages fr"]/span/text()||/(\d+)/',
				elements::CATEGORY_LIST_MATCH => 1,
				elements::CATEGORY_PAGE_START => 1,
				elements::CATEGORY_LIST_GOODS_PREG => '//p[@class="housetitle"]/a/@href',
                elements::TRANSFORM => false,
                elements::TRANSFORMADDSPECIL =>'/',
				elements::CATEGORY_LIST_GOODS_Match => 1,
                elements::CATEGORY_MATCHING => 'xpath',
                elements::CATEGORY_ITEM_PREG => array(
                    elements::CATEGORY_ITEM_MATCHING =>'xpath',
                    elements::CATEGORY_ITEM_DPRICE =>'//div[@class="broker-lists-item"]',
                    elements::CATEGORY_ITEM_URL => '//a[@class="c_default f14 mr5"]/@href||2',
                    elements::CATEGORY_ITEM_SKUID =>'//a[@class="c_default f14 mr5"]/@href||2',//昵称
                    elements::CATEGORY_ITEM_AREA =>'.//div[@class="hall_people_house_font"][2]',//服务楼盘
                    elements::CATEGORY_ITEM_MPRICE =>'.//img[contains(@src,"medal.png")]/@src',//金牌
                    elements::CATEGORY_ITEM_OPRICE =>'.//span[@class="ico-06"]/@class',//认证
                    elements::CATEGORY_ITEM_HOT =>'.//p[@class="mt2"]/a[1]/text()',//出售数量
                    elements::CATEGORY_ITEM_SALE =>'.//p[@class="mt2"]/a[2]/text()',//出租数量
                    elements::CATEGORY_ITEM_DISTRICT =>'.//dd[1]/p[3]/a/text()',//所属门店
                    elements::CATEGORY_ITEM_COMPANY=>'//li[@class="site-topsearch"]/a[@class="current"]/text()||1',//城市
                    elements::CATEGORYCOMMON =>array(
                        'UserName'=>'//a[@class="c_default f14 mr5"]/text()||2',//姓名
                        'ServiceArea'=>'//div[@class="broker-lists-item"]//dd/p[2]/text()||2',//区域

                        'Tel'=>'//span[@class="bold c_red"]/text()||2',

                    ),
                )

		),
		// item config
        elements::ITEM_SKUID => '//input[@id="talkPageValue"]/@value||1',//昵称
        elements::ITEM_NAME=>'//meta[@name="keywords"]/@content||1',//名称
        elements::ITEM_BARCODE=>'//span[@class="c_red bold"]/text()||1',//Tel
        elements::ITEM_TITLE=>'//a[@class="c_default"]/text()||1',//所属公司
        elements::ITEM_IMAGE_URL => '//a[@class="c_default"]/@href||1',//所属公司url
        elements::ITEM_ISBN=>'//div[@class="broker-mod broker-mod-infos mt10"]//div[@class="broker-infos-list"]/dl[2]/dd/text()||1',//注册时间
        elements::ITEM_SALES=>'//div[@class="icon"]/img[contains(@src,"active0")]||2',//热度
        elements::ITEMCOMMON => array(
            'Tel' => '//span[@class="c_red bold"]/text()||1',
            'Store' =>'//dd[@class="lh24"]/p[5]/text()||1',//所属门店

        ),
		elements::STID => 111,
        elements::HTML_ZIP =>'gzip',
		elements::DATASOURCE => '1',
		elements::COLLECTION_ITEM_NAME => 'lejubroker_Items',
		elements::COLLECTION_CATEGORY_NAME => 'lejubroker_category',
        elements::ITEMPAGECHARSET => 'utf-8',
        elements::CHARSET => '',
		elements::MANAGER => 'living',
		elements::UPDATEDATA=>array(
				elements::ITEM_DPRICE,
				elements::ITEM_OPRICE,
		),
);
