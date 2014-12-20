<?php

$siteconfig = array(
		elements::TYPE => 'fulldata',
		elements::NAME => '搜房网',
		elements::DB => array(
				'mongodb' => array(
						elements::HOST => 'mongo.wcc.cc',
						elements::PORT => '27017',
						elements::TIMEOUT => 0,
						elements::DBNAME => 'soufun'
				)
		),
		elements::CATEGORY => array(
				elements::CATEGORY_URL => '',
				elements::CATEGORY_MATCH_PREG => '',
				elements::CATEGORY_MATCH_MATCH => '',
				elements::CATEGORY_GROUP_SIZE => 2,
				elements::CATEGORY_LIST_URL => 'http://esf.#job.fang.com/agenthome/',
				elements::CATEGORY_LIST_PAGES_URL => 'http://esf.#job.fang.com/agenthome/-i3#i/',
				elements::CATEGORY_LIST_PREG => '//span[@class="txt"]/text()||/(\d+)/',
				elements::CATEGORY_LIST_MATCH => 1,
				elements::CATEGORY_PAGE_START => 1,
				elements::CATEGORY_LIST_GOODS_PREG => '//p[@class="housetitle"]/a/@href',
                elements::TRANSFORM => false,
                elements::TRANSFORMADDSPECIL =>'/',
				elements::CATEGORY_LIST_GOODS_Match => 1,
                elements::CATEGORY_MATCHING => 'xpath',
                elements::CATEGORY_ITEM_PREG => array(
                    elements::CATEGORY_ITEM_MATCHING =>'xpath',
                    elements::CATEGORY_ITEM_NAME =>'//p[@class="housetitle"]/span[@class=" gray9"]/text()||2',//姓名
                    elements::CATEGORY_ITEM_IMG =>'.//p[@class="gray6"]/span[@class="blue"][1]/text()',//委托
                    elements::CATEGORY_ITEM_URL =>'//p[@class="housetitle"]/a/@href',//url
                    elements::CATEGORY_ITEM_OPRICE =>'//p[@class="black"]/span/text()||2',//所属公司
                    elements::CATEGORY_ITEM_DPRICE =>'//div[@class="house"]',
                    elements::CATEGORY_ITEM_SALE =>'//div[@class="pic"]',//专家
                    elements::CATEGORY_ITEM_REVIEWS =>'.//p[@class="mt10"]/a/@title',//认证
                    elements::CATEGORY_ITEM_HOT =>'.//div[@class="techang"]/span',//特长
                    elements::CATEGORY_ITEM_SKUID =>'.//div[@class="pic"]/a/@href',//昵称
                    elements::CATEGORY_ITEM_AREA =>'.//p[@class="housetitle"]//img[contains(@src,"digest_1.gif")]',//钻
                    elements::CATEGORY_ITEM_MPRICE =>'.//p[@class="housetitle"]//img[contains(@src,"star.jpg")]',//星
                    elements::CATEGORY_ITEM_MPRICE_URL =>'//p[@class="gray6"]/span[@class="blue"][2]/text()||2',//已成交数
                    elements::CATEGORY_ITEM_SHOP_NAME =>'//p[@class="gray6"]/span[@class="blue"][3]/text()||2',//好评数
                    elements::CATEGORY_ITEM_SHOP_URL =>'.//p[@class="black"][3]/text()',//开店时间
                    elements::CATEGORY_ITEM_SHOP_ID =>'.//p[@class="blue"][2]/a/text()',
                    elements::CATEGORY_ITEM_COMPANY =>'//div[@class="qxName"]/a[@class="orange"]/text()||1',
                    elements::CATEGORY_ITEM_DISTRICT =>'//ul[@class="info ml25"]/li[2]/a[@class="orange"]/text()||1',
                    elements::CATEGORYCOMMON =>array(

                        'UserName' =>'//p[@class="housetitle"]/a/text()||2',
                    ),
                )

		),
		// item config
		elements::ITEM_TITLE => '//div[@class="rzname floatl"]/text()||1',//名称
		elements::ITEM_SOURCE_CATEGORY_ID => '//input[@id="talkAgentPhone"]/@value||1',//手机
		elements::ITEM_SOURCE_CATEGORY_NAME=> '//ul[@class="cont02 mb10"]/li[1]/text()||1',//所属公司
		elements::ITEM_SKUID => '//input[@id="talkPageValue"]/@value||1',//昵称
		elements::ITEM_NAME =>'',
		elements::ITEM_SOURCE_BRAND_ID =>'//ul[@class="cont02 mb10"]/li[3]/a/text()||1',//所属区县
		elements::ITEM_SOURCE_BRAND_NAME =>'//input[@id="talkComerce"]/@value||1',//服务区域
		elements::ITEM_SOURCE_SELLER_ID =>'//input[@id="talkPageValue"]/@value||1',//昵称
		elements::ITEM_SOURCE_SELLER_NAME => '//input[@id="talkAgentName"]/@value||1',//姓名
		elements::ITEM_IMAGE_URL => '',
		elements::ITEM_PROMOTION => '//ul[@class="cont02 mb10"]/li[5]/text()||1',//注册时间
		elements::ITEM_SALES => '//ul[@class="cont02 mb10"]/li[2]/a/text()||1',//销量
		elements::ITEM_DPRICE => '//ul[@class="cont03"]/li[2]/text()||1',//所属门店
		elements::ITEM_OPRICE => '//ul[@class="cont03"]/li[3]/text()||1',//门店地址
		elements::ITEM_PRICE_URL => '//ul[@class="cont03"]/li[5]/text()||1',//服务热线
		elements::ITEM_STATUS => '',
		elements::ITEM_DESCRIPTION =>'//ul[@class="cont03"]/li[7]/text()||1',//门店店长
		elements::ITEM_CHARACTERS =>'',
		elements::ITEM_ISBN => '//ul[@class="cont03"]/li[8]/text()||1',//开店时间
		elements::ITEM_BARCODE => '//input[@id="talkAgentID"]/@value||1',//ID
		elements::BASE_URL => 'http://sh.soufun.com/',
		elements::STID => 111,
        elements::HTML_ZIP =>'gzip',
		elements::DATASOURCE => '1',
		elements::COLLECTION_ITEM_NAME => 'soufunbroker_Items',
		elements::COLLECTION_CATEGORY_NAME => 'soufunbroker_category',
        elements::ITEMPAGECHARSET => 'gbk',
        elements::CHARSET => '',
		elements::MANAGER => 'living',
		elements::UPDATEDATA=>array(
				elements::ITEM_DPRICE,
				elements::ITEM_OPRICE,
		),
);
