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
				),
                'mongodbsec' => array(
                    elements::HOST => 'master.mongo.cc',
                    elements::PORT => '27017',
                    elements::TIMEOUT => 0,
                    elements::DBNAME => 'soufun5'
                )
		),
		elements::CATEGORY => array(
				elements::CATEGORY_URL => '',
				elements::CATEGORY_MATCH_PREG => '',
				elements::CATEGORY_MATCH_MATCH => array(),
				elements::CATEGORY_GROUP_SIZE => 2,
				elements::CATEGORY_LIST_URL => 'http://esf.#job.fang.com/agenthome/',
				elements::CATEGORY_LIST_PAGES_URL => 'http://esf.#job.fang.com/agenthome/-i3#i/',
				elements::CATEGORY_LIST_PREG => '//span[@class="all"]/text()||/(\d+)/',
				elements::CATEGORY_LIST_MATCH => 1,
				elements::CATEGORY_PAGE_START => 1,
				elements::CATEGORY_LIST_GOODS_PREG => '//p[@class="housetitle"]/a/@href',
                elements::TRANSFORM => false,
                elements::TRANSFORMADDSPECIL =>'/',
				elements::CATEGORY_LIST_GOODS_Match => 1,
                elements::CATEGORY_MATCHING => 'xpath',
                elements::CATEGORY_ITEM_PREG => array(
                    elements::CATEGORY_ITEM_MATCHING =>'xpath',
                    elements::CATEGORY_ITEM_DPRICE =>'//div[@class="hall_people_house"]',
                    elements::CATEGORY_ITEM_SKUID =>'//div[@class="hall_people_house_name_l"]/a/@href||2',//昵称
                    elements::CATEGORY_ITEM_AREA =>'.//div[@class="hall_people_house_font"][2]',//服务楼盘
                    elements::CATEGORY_ITEM_MPRICE =>'.//img[contains(@src,"medal.png")]/@src',//金牌
                    elements::CATEGORY_ITEM_OPRICE =>'.//img[contains(@src,"renzheng.jpg")]/@src',//认证
                    elements::CATEGORY_ITEM_HOT =>'.//div[@class="hall_people_house_font"][5]/text()',//出售数量
                    elements::CATEGORY_ITEM_SALE =>'.//div[@class="hall_people_house_font"][5]/text()',//出租数量
                    elements::CATEGORYCOMMON =>array(
                        'UserName'=>'//div[@class="hall_people_house_name_l"]/a/text()||2',//姓名
                        'ServiceArea'=>'.//div[@class="hall_people_house_font"][1]/text()||2',//区域
                        'Stores' => './/div[@class="hall_people_house_font"][3]/text()||2',//服务楼盘
                        'Tel'=>'.//span[@class="hall_people_red"]/text()||2',

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
		elements::COLLECTION_ITEM_NAME => 'Soufun_Items',
		elements::COLLECTION_CATEGORY_NAME => 'soufun_category',
        elements::ITEMPAGECHARSET => 'gbk',
        elements::CHARSET => '',
		elements::MANAGER => 'living',
		elements::UPDATEDATA=>array(
				elements::ITEM_DPRICE,
				elements::ITEM_OPRICE,
		),
);
