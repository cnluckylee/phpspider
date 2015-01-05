<?php
/**
 * Created by PhpStorm.
 * User: living
 * Date: 15-1-5
 * Time: 上午11:16
 */

class tools {
    /**
     * 获取原始数据的更新时间 如: 10秒前更新
     * @param $str
     * @return bool|string
     */
    public function getSourceUpdateTime($str)
    {
        $p='/(\d+)/';
        preg_match($p,$str,$out);

        $num = intval($out[1]);
        $c = '';
        if(strpos($str,'天'))
        {
            $c = ' day';
        }else  if(strpos($str,'时'))
        {
            $c = ' hour';
        }else  if(strpos($str,'月'))
        {
            $c = ' month';
        }else  if(strpos($str,'秒'))
        {
            $c = ' seconds';
        }
        return date('Y-m-d H:i:s',strtotime("-".$num.$c));
    }

} 