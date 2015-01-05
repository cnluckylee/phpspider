<?php

$siteconfig = array(
		elements::TYPE => 'fulldata',
		elements::NAME => '搜房新房网',
		elements::DB => array(
				'mongodb' => array(
						elements::HOST => 'mongo.wcc.cc',
						elements::PORT => '3376',
						elements::TIMEOUT => 0,
						elements::DBNAME => 'soufun'
				)
		),
		elements::CATEGORY => array(
				elements::CATEGORY_URL => 'http://js.soufunimg.com/homepage/new/family/css/citys.js?v=421824131436',
				elements::CATEGORY_MATCH_PREG => '/"spell": "(\w+)", "url": "http:\/\/(.*)"/',
				elements::CATEGORY_MATCH_MATCH => array('name'=>1,'cid'=>2),
				elements::CATEGORY_GROUP_SIZE => 1,
				elements::CATEGORY_LIST_URL => '',
				elements::CATEGORY_LIST_PAGES_URL => '',
				elements::CATEGORY_LIST_PREG => '/totalPage\s+=\s+(\d+);/',
				elements::CATEGORY_LIST_MATCH => 1,
				elements::CATEGORY_PAGE_START => 1,
                elements::TRANSFORM => false,
                elements::TRANSFORMADDSPECIL => '/',
				elements::CATEGORY_LIST_GOODS_PREG => '//ul[@class="district clearfix"]/li/a/@href||2',
				elements::CATEGORY_LIST_GOODS_Match => 1,
                elements::CATEGORY_MATCHING => 'xpath',
                elements::CATEGORY_ITEM_PREG => array(
                    elements::CATEGORY_ITEM_MATCHING =>'xpath',
                    elements::CATEGORY_ITEM_SKUID =>'//h2/a/@href||2',
                    elements::CATEGORY_ITEM_URL =>'//h2/a/@href||2',
                    elements::CATEGORY_ITEM_NAME =>'//h2/a/text()||2',
                    elements::CATEGORY_ITEM_AREA =>'.//p[@class="mt15"]/font/@title',
                    elements::CATEGORY_ITEM_DPRICE =>'//div[@class="sslalone"]',
                    elements::CATEGORY_ITEM_OPRICE =>'//span[@class="adbox"]/@onclick||2',
                    elements::CATEGORYCOMMON => array(
                        'Name' =>'//h2/a/@href||2',
                    )
                )

		),
        elements::ITEM_SKUID =>'//a[@id="xfxq_C04_01"]/@href||1',
        elements::ITEM_ISBN=>'/	(\d+)人报名团购/',//参团人数
        elements::ITEM_SALES=>'/可售(\d+)套/',//可销售数量
        elements::ITEM_BARCODE=>'/newcode\s+=\s+"(\d+)";/',
        elements::ITEM_PROMOTION=>'//div[@class="besic_inform"]//tr',
        elements::ITEM_CHARACTERS=>'',//ajax获取评论
        elements::ITEM_COMMENT_NUMBER_ALL =>'',//评论数
        elements::ITEM_DESCRIPTION=>'//p[@id="qdds_bendtime"]/text()||1',
        elements::ITEMCOMMON =>array(
            'Name'=>'//a[@class="ts_linear"]/text()||1',//楼盘名称
            'RegistrTime'=>'//p[@id="qdds_bendtime"]/text()||1',//注册时间
            'Discount'=>'//p[@class="ad_text"]/text()||1',//优惠情况
            'LatestOpen'=>'//a[@id="xfxq_C03_09"]/text()||1',//最近开盘时间
            'Address'=>'//input[@id="txt_developer"]@value||1',//地址
            'Apartment' =>'//div[@id="xfxq_C03_12"]/p/a/text()||2',//
            'Launchtime' =>'//a[@id="xfxq_B04_18"]/text()||1',//推出时间
            'OpeningTime' =>'//a[@id="xfxq_B04_17"]/text()||1',//开盘时间
            'PropertyType' =>'//ul[@class="information"]/li[2]/div[@class="infow2"][1]/text()||1',//物业类型
            'ConstCategory' =>'//ul[@class="information"]/li[2]/div[@class="infow2"][2]/text()||1',//建筑类别
            'Developer' =>'//input[@id="txt_developer"]/@value||1',//开发商
            'FixStatus' =>'//input[@id="txt_fix_status"]/@value||1',//装修状况
            'SalesStatus'=>'//input[@id="txt_sale_date"]/@value||1',//销售状况
            'SaleRate'=>'//input[@id="txt_sale_rate"]/@value||1',//是否在售
            'AvePrice'=>'//input[@id="txt_aveprice"]/@value||1',//均价
            'MinPrice'=>'//input[@id="txt_minprice"]/@value||1',//最低价
            'MaxPrice'=>'//input[@id="txt_maxprice"]/@value||1',//最低价
        ),

    /**
     * 不需要item
     */
    elements::STID => 121,
        elements::HTML_ZIP =>'gzip',
		elements::DATASOURCE => '1',
        elements::TOTALPAGES =>1,
		elements::COLLECTION_ITEM_NAME => 'Soufunnewhouse_List_Items',
		elements::COLLECTION_CATEGORY_NAME => 'soufunnewhouse_list',
        elements::ITEMPAGECHARSET => 'gbk',
        elements::CHARSET => 'gbk',
		elements::MANAGER => 'living',
		elements::UPDATEDATA=>array(
				elements::ITEM_DPRICE,
				elements::ITEM_OPRICE,
		),
);
