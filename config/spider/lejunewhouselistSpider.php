<?php

$siteconfig = array(
		elements::TYPE => 'fulldata',
		elements::NAME => '乐居新房列表网',
		elements::DB => array(
				'mongodb' => array(
						elements::HOST => 'mongo.wcc.cc',
						elements::PORT => '27017',
						elements::TIMEOUT => 0,
						elements::DBNAME => 'leju'
				)
		),
		elements::CATEGORY => array(
				elements::CATEGORY_URL => 'http://bj.house.sina.com.cn/cityguide/',
				elements::CATEGORY_MATCH_PREG => '/href="http:\/\/(\w+).house.sina.com.cn">(.*?)<\/a>/',
				elements::CATEGORY_MATCH_MATCH => array('name'=>2,'cid'=>1),
				elements::CATEGORY_GROUP_SIZE => 1,
				elements::CATEGORY_LIST_URL => 'http://data.house.sina.com.cn/#job/search/',
				elements::CATEGORY_LIST_PAGES_URL => 'http://data.house.sina.com.cn/#job/search/?&charset=utf8',
				elements::CATEGORY_LIST_PREG => '//form[@id="filterPageForm"]/text()||/共(\d+)页/',
				elements::CATEGORY_LIST_MATCH => 1,
				elements::CATEGORY_PAGE_START => 0,
                elements::TRANSFORM=>false,
                elements::TRANSFORMADDSPECIL =>'',
				elements::CATEGORY_LIST_GOODS_PREG => '//ul[@class="district clearfix"]/li/a/@href||2',
				elements::CATEGORY_LIST_GOODS_Match => 1,
                elements::CATEGORY_MATCHING => 'xpath',
                elements::CATEGORY_ITEM_PREG => array(
                    elements::CATEGORY_ITEM_MATCHING =>'xpath',
                    elements::CATEGORY_ITEM_SKUID =>'//ul[@class="results_list"]//a[@class="pic"]/@href||2',
                    elements::CATEGORY_ITEM_URL =>'//ul[@class="results_list"]//p[@class="link clearfix"]/a[1]/@mapurl||2',
                    elements::CATEGORY_ITEM_NAME =>'//ul[@class="results_list"]//a[@class="pic"]/@title||2',
                    elements::CATEGORY_ITEM_AREA =>'//div[@id="s_list"]//dd[2]/a[@class="cur"]/text()||1',
                    elements::CATEGORY_ITEM_HOT=>'//div[@class="show_inner"]||2',//E基金
                    elements::CATEGORYCOMMON =>array(
                        'Address'=>'//div[@class="txt"]/h3/p[1]/text()||2',
                        'Price'=>'//div[@class="txt_r"]/p[1]/text()||2',
                        'OpenDate'=>'//div[@class="txt_r"]/p[2]/text()||2',
                        'ComeDate'=>'//div[@class="txt_r"]/p[3]/text()||2',
                        'PropertyType'=>'//div[@class="txt"]/h3/p[2]/text()||2',
                    ),
                )

		),
		// item config
		elements::ITEM_NAME => '//div[@class="area"]/a/text()||2',
        elements::ITEM_PRICE_URL => '//div[@class="area"]/a/@href||2',
        elements::ITEM_SKUID =>'//div[@class="line "]/div[@class="item"]/a[@class="hover"]/text()||1',
        elements::ITEM_OPRICE =>'//div[@class="item_jingji"]/a/text()||2',
        elements::ITEM_DPRICE =>'//div[@class="item_jingji"]/a/@href||2',
        elements::ITEM_BARCODE=>'//span[@id="ejuEndTime"]/@seconds||1',
        elements::ITEM_PROMOTION=>'//p[@class][2]/span[@class="e_wbk e_jz1"]/text()||1',
        elements::ITEM_SALES =>'//div[@class="d_menu fl mr10"]//li[3]//p[2]/a/text()||1',
        elements::ITEM_COMMENT_NUMBER_ALL =>'//div[@class="d_menu fl mr10"]//li[4]//p[2]/text()||1',//关注数

        elements::BASE_URL => 'http://sh.esf.leju.com/',
//        elements::ITEM_SALES=>'//i[@class="e_ico6 mt3"]/@class||1',//是否E金券
		elements::STID => 121,
        elements::HTML_ZIP =>'gzip',
		elements::DATASOURCE => '1',
		elements::COLLECTION_ITEM_NAME => 'Lejunewhouse_List_Items',
		elements::COLLECTION_CATEGORY_NAME => 'lejunewhouselist_category',
        elements::ITEMCOMMON =>array(
            'Preferential'=>'//div[@id="scroll_link3"]//h3/text()||1',//优惠
            'Price'=>'//span[@class="e_item2 fr"]/em/text()||1',
            'PropertyType'=>'//span[@class="e_jz2 e_wbk"]/text()||1',
            'OpenDate'=>'//a[contains(@href,"kaipan")]/text()||1',//开盘时间
            'Construction'=>'//span[@class="e_wbk e_jz3"]/text()||1',//建筑类别
            'IssuedTime'=>'//div[@id="scroll_link3"]//p/strong/text()||1',//发行时间
            'Sold'=>'//div[@id="scroll_link3"]//p/em[1]/text()||1',//已售出
            'TotalSold'=>'//div[@id="scroll_link3"]//p/em[2]/text()||1',//发行量
            'Ecoupon'=>'//div[@class="d_menu fl mr10"]//li[1]//p[2]/text()||1',//Ecoupon
            'ActiveTime'=>'//div[@class="d_menu fl mr10"]//li[2]//p[2]/text()||1',//活动时间

        ),
        elements::ITEMPAGECHARSET => 'utf-8',
        elements::CHARSET => 'utf-8',
		elements::MANAGER => 'living',
		elements::UPDATEDATA=>array(
				elements::ITEM_DPRICE,
				elements::ITEM_OPRICE,
		),
);
