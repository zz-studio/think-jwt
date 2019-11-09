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

/**
 * Class Jwt  Jwt类
 * @package think\Jwt
 * @method static \Firebase\JWT\JWT::encode
 * @method static \Firebase\JWT\JWT::decode
 * @method static \Firebase\JWT\JWT::sign
 */
class Jwt
{
    /**
     * When checking nbf, iat or expiration times,
     * we want to provide some extra leeway time to
     * account for clock skew.
     */
    public static $leeway = 0;

    /**
     * Allow the current timestamp to be specified.
     * Useful for fixing a value within unit testing.
     *
     * Will default to PHP time() value if null.
     */
    public static $timestamp = null;

    public static $supported_algs = array(
        'HS256' => array('hash_hmac', 'SHA256'),
        'HS512' => array('hash_hmac', 'SHA512'),
        'HS384' => array('hash_hmac', 'SHA384'),
        'RS256' => array('openssl', 'SHA256'),
        'RS384' => array('openssl', 'SHA384'),
        'RS512' => array('openssl', 'SHA512'),
    );

    /**
     * 动态调用魔术方法
     * @param $name
     * @param $arguments
     * @return mixed
     */
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
                if (!isset($arguments[2])) {
                    $arguments[2] = array_keys(self::$supported_algs);
                }
                break;
        }

        // 赋值属性
        JwtBase::$leeway = self::$leeway;
        JwtBase::$supported_algs = self::$supported_algs;
        JwtBase::$timestamp = self::$timestamp;

        return call_user_func_array([JwtBase::class, $name], $arguments);
    }
}