<?php
/**
 * +----------------------------------------------------------------------
 * | think-jwt [基于 firebase/php-jwt]
 * +----------------------------------------------------------------------
 *  .--,       .--,             | FILE: config.php
 * ( (  \.---./  ) )            | AUTHOR: byron sampson
 *  '.__/o   o\__.'             | EMAIL: xiaobo.sun@qq.com
 *     {=  ^  =}                | QQ: 150093589
 *      >  -  <                 | WECHAT: wx5ini99
 *     /       \                | DATETIME: 2019/10/29
 *    //       \\               |
 *   //|   .   |\\              |
 *   "'\       /'"_.-~^`'-.     |
 *      \  _  /--'         `    |
 *    ___)( )(___               |-----------------------------------------
 *   (((__) (__)))              | 高山仰止,景行行止.虽不能至,心向往之。
 * +----------------------------------------------------------------------
 * | Copyright (c) 2019 http://www.zzstudio.net All rights reserved.
 * +----------------------------------------------------------------------
 */
namespace think;

use Firebase\JWT\JWT AS JwtBase;
use think\facade\Config;

class Jwt
{
    public static function __callStatic($name, $arguments)
    {
        $options = Config::get('jwt');
        if (!$options) {
            throw new \InvalidArgumentException("missing jwt config");
        }
        switch ($name) {
            case 'encode':
                if (!isset($arguments[0])) {
                    $arguments[0] = $options['token'];
                }
                if (!isset($arguments[1])) {
                    $arguments[1] = $options['key'];
                }
                $arguments[0] = array_merge($options['token'], $arguments[0]);
                break;
            case 'decode':
                if (!isset($arguments[1])) {
                    $arguments[1] = $options['key'];
                }
                break;
            case 'verify':
                if (!isset($arguments[2])) {
                    $arguments[2] = $options['key'];
                }
                break;
        }

        return call_user_func_array([JwtBase::class, $name], $arguments);
    }
}