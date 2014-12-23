<?php

$siteconfig = array(
		elements::TYPE => 'fulldata',
		elements::NAME => '乐居经纪人',
		elements::DB => array(
				'mongodb' => array(
						elements::HOST => 'mongo.wcc.cc',
						elements::PORT => '27017',
						elements::TIMEOUT => 0,
						elements::DBNAME => 'lejutt'
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
		elements::ITEM_TITLE => '//div[@class="shop"]/span/text()||1',//名称
		elements::ITEM_SOURCE_CATEGORY_ID => '//div[@class="tel"]/span[@class="num"]/text()||1',//手机
		elements::ITEM_SOURCE_CATEGORY_NAME=> '//div[@class="aa"]/p[@class="p2"]/text()||1',//所属公司
		elements::ITEM_SKUID => '//input[@id="talkPageValue"]/@value||1',//昵称
		elements::ITEM_NAME =>'',
		elements::ITEM_SOURCE_BRAND_ID =>'',//所属区县
		elements::ITEM_SOURCE_BRAND_NAME =>'',//服务区域
		elements::ITEM_SOURCE_SELLER_ID =>'',//昵称
		elements::ITEM_SOURCE_SELLER_NAME => '',//姓名
		elements::ITEM_IMAGE_URL => '',
		elements::ITEM_PROMOTION => '',//注册时间
		elements::ITEM_SALES => '',//销量
		elements::ITEM_DPRICE => '',//所属门店
		elements::ITEM_OPRICE => '',//门店地址
		elements::ITEM_PRICE_URL => '',//服务热线
		elements::ITEM_STATUS => '',
		elements::ITEM_DESCRIPTION =>'',//门店店长
		elements::ITEM_CHARACTERS =>'',
		elements::ITEM_ISBN => '//div[@class="main_xiangqing_left"]/p[1]/text()||1',//开店时间
		elements::ITEM_BARCODE => '',//ID
		elements::BASE_URL => '//div[@class="favorite"]/a[@class="shouye"]/@href||1',
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
