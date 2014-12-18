<?php

class elements {
    /* website type of the crawling journey in the start page */

    /**
     * 
     * @var TYPE
     * 		fulldata:全部跑
     * 	
     */
	const NAME = 'name';//网站中文名称
    const TYPE = 'type';  //任务类型 fulldata:全量新增 fullupdate:全量更新 incrdata:增量新增 incrupdate:增量更新
    const CATEGORY = 'Category';
    const Run_Type = 'normal'; //normal 分类－>分类列表－>商品详情 special query=*->全部商品列表－>商品详情
    const CATEGORY_URL = 'Category_URL'; //全部分类url
    const CHARSET = 'charset'; //网站编码
    const ITEMPAGECHARSET = 'item_page_charset'; //网站编码
    const CATEGORY_MATCH_PREG = 'Category_Match_Preg'; //全部分类url正则
    const CATEGORY_MATCH_MATCH = 'Category_Match_Match'; //全部分类url正则后取值
    const CATEGORY_GROUP_SIZE = 'Category_Group_Size'; //分类列表页 每次跑的页面数
    const CATEGORY_LIST_URL = 'Category_List_URL'; //分类列表页url模版
    const CATEGORY_LIST_PREG = 'Category_List_Preg'; //分类列表页url模版正则
    const CATEGORY_LIST_MATCH = 'Category_List_Match'; //分类列表页url模版正则后取值
    const CATEGORY_LIST_GOODS_PREG = 'Category_List_Goods_Preg'; //分类列表页商品url正则
    const CATEGORY_LIST_GOODS_Match = 'Category_List_Goods_Match'; //分类列表页商品url正则后取值
    const CATEGORY_LIST_PAGES_URL = 'CATEGORY_LIST_Pages_URL'; //分类列表页翻页url
    const CATEGORY_PAGE_START = 'Category_Page_Start';//分类列表页起始地址，0或1
    const CATEGORY_RUN = 'Category_Run';//本次是否重新抓取目标网站分类
    const CATEGORY_ITEM_PREG = 'Category_Item_Preg';//列表页商品正则
    const CATEGORY_ITEM_MATCHING = 'Category_Item_Matching';//列表页商品正则
    const CATEGORY_ITEM_NAME = 'Category_Item_Name';//列表页商品名称
    const CATEGORY_ITEM_URL = 'Category_Item_Url';//列表页商品url
    const CATEGORY_ITEM_IMG = 'Category_Item_Img';//列表页图片
    const CATEGORY_ITEM_OPRICE = 'Category_Item_OPrice';//列表页商品原价
    const CATEGORY_ITEM_DPRICE = 'Category_Item_DPrice';//列表页商品折扣价
    const CATEGORY_ITEM_HOT = 'Category_Item_Hot';//列表页是否热卖
    const CATEGORY_ITEM_SALE = 'Category_Item_Sale';//列表页是否销售
    const CATEGORY_ITEM_REVIEWS = 'Category_Item_Reviews';//列表页论数
    const CATEGORY_ITEM_AREA = 'Category_Item_Area';//销售地区
    const CATEGORY_ITEM_SKUID = 'Category_Item_Skuid';//销售地区
    const CATEGORY_ITEM_MPRICE = 'Category_Item_Mprice';//销售地区
    const CATEGORY_ITEM_MPRICE_URL = 'Category_Item_Mprice_Url';//销售地区
    const CATEGORY_ITEM_SHOP_URL = 'Category_Item_Shop_Url';//销售地区
    const CATEGORY_ITEM_SHOP_NAME = 'Category_Item_Shop_Name';//销售地区
    const CATEGORY_ITEM_SHOP_ID = 'Category_Item_Shop_ID';//销售地区
    const CATEGORY_ITEM_DISTRICT = 'Category_Item_District';//区域
    const CATEGORY_ITEM_COMPANY = 'Category_Item_Company';//区域


    const DB_TYPE = 'Db_Type';
    /* 商品相关参数 */
    const ITEM = 'Item';
    const ITEM_SOURCE_CATEGORY_ID = 'source_category_id'; //目标网站分类id 1-2-3
    const ITEM_SOURCE_CATEGORY_NAME = 'source_category_name'; //目标网站分类名称 一级－二级－三级
    const ITEM_CID = 'cid'; //本地三级分类ID
    const ITEM_SOURCE_SELLER_ID = 'source_seller_id'; //目标网站销售ID
    const ITEM_SOURCE_SELLER_NAME = 'source_seller_name'; //目标网站销售名称
    const ITEM_SKUID = 'skuid'; //商品id
    const ITEM_TITLE = 'title'; //商品标题
    const ITEM_PROMOTION = 'promotion'; //商品促销信息
    const ITEM_SALES = 'sales'; //商品销量
    const ITEM_NAME = 'item_name'; //商品名称
    const ITEM_IMAGE_URL = 'image_url'; //商品图片
    const ITEM_SOURCE_BRAND_ID = 'brand_id'; //品牌id
    const ITEM_SOURCE_BRAND_NAME = 'brand_name'; //品牌名称
    const ITEM_DPRICE = 'dprice'; //现价
    const ITEM_OPRICE = 'oprice'; //原价
    const ITEM_PRICE_URL = 'price_url';//价格连接
    const ITEM_SOURCE_URL = 'source_url'; //商品来源url
    const ITEM_WAPURL = 'wapurl'; //wapurl
    const ITEM_STATUS = 'status'; //商品状态 1:在售,有价格 ⒉:无价格，有商品2:无价格无商品
    const ITEM_CHARACTERS = 'characters'; //商品特征
    const ITEM_DATA_SOURCE = 'data_source'; //数据来源 1:采集 2:api 3:导入
    const ITEM_DESCRIPTION = 'description'; //商品描述 
    const ITEM_ISBN = 'isbn'; //isbn
    const ITEM_BARCODE = 'barcode'; //Barcode
    const ITEM_CREATE_TIME = 'create_time'; //新增时间
    const ITEM_UPDATE_TIME = 'update_time'; //更新时间
    const ITEM_COMMENT_NUMBER_ALL = 'all_comment_number';//所有评论
    const ITEM_COMMENT_NUMBER_SATISFY = 'satisfy_comment_number';//好评
    const ITEM_COMMENT_NUMBER_GENERAL = 'general_comment_number';//中平
    const ITEM_COMMENT_NUMBER_DISSATISFY = 'dissatisfy_comment_number';//差评
    const ITEM_MPRICE = 'item_mprice';
    const ITEM_MPRICE_PREG = 'item_mprice_preg';

    /* basic items of the particular market */
    const MANAGER = 'manager'; //脚本负责人
    const BASE_URL = 'baseurl'; //网站地址
    const COMMON = 'Common';//通用方法
    const STID = 'stid'; //网站编号

    /* 系统其他参数说明 */
    const DB = 'db'; //数据库选项
    const TIMEOUT = 'timeout';
    const HOST = 'host';
    const PORT = 'port';
    const DBNAME = 'dbname';  //数据库名称
    const DBUSER = 'dbuser';
    const DBPWD = 'dbpwd';
    const COLLECTION_ITEM_NAME='collection_item_name';//商品集合名称
    const COLLECTION_CATEGORY_NAME ='collection_category_name';//分类集合名称
    const DATASOURCE='datasource';//数据来源
    const CATEGORY_MATCHING ='category_matching';//匹配方式 xpath,regular
    const ITEM_MATCHING ='item_matching';//匹配方式 xpath,regular
    const TOTALPAGES = 'totalpages';
    const TRANSFORM = 'transform';//URL是否需要转换
    const TRANSFORMADDSPECIL = 'transformaddspecil';
    const CATEGORY_SOURCE_URL = 'category_source_url';

    const HTML_ZIP = 'html_zip';//网页压缩格式
    /**
     * 批量更新数据
     */
    const UPDATEDATA = 'updatedata';
}
