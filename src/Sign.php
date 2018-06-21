<?php
namespace Sodalevy\Sign;

class Sign
{

    /**
     * 验证签名
     * @param $sign
     * @param $values
     * @param $app_secret
     * @param int $timeout
     * @return bool
     */
    public static function checkSign($sign,$values,$app_secret,$timeout=10){
        $make_sign=self::makeSign($values,$app_secret);
        if($make_sign!=$sign){
            return false;
        }

        if(isset($values['nonce_str'])){
            if(time()-$values['nonce_str']>$timeout){
                return false;
            }
        }
        return true;
    }

    /**
     * 生成签名
     * @param $values
     * @param $app_secret
     * @return string
     */
    public static function makeSign($values,$app_secret)
    {
        //签名步骤一：按字典序排序参数
        ksort($values);
        $string = self::toUrlParams($values);
        //签名步骤二：在string后加入KEY
        $string = $string . "&key=".$app_secret;
        //签名步骤三：MD5加密
        $string = md5($string);
        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);
        return $result;
    }

    /**
     * 格式化参数格式化成url参数
     */
    public static function toUrlParams($values)
    {
        $buff = "";
        foreach ($values as $k => $v)
        {
            if($k != "sign" && $v != "" && !is_array($v)){
                $buff .= $k . "=" . $v . "&";
            }
        }

        $buff = trim($buff, "&");
        return $buff;
    }
}
