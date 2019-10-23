<?php

/**
 * 公共函数方法
 *
 * 这里的函数不涉及任何逻辑、格式化、项目标准
 */

if (!function_exists('get_guid')) {
    //生成全局唯一标识符
    function get_guid() {
        if (function_exists('com_create_guid')) {
            return com_create_guid();
        } else {
            mt_srand((double) microtime() * 10000);
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45); // "-"
            $uuid = substr($charid, 0, 8) . $hyphen
            . substr($charid, 8, 4) . $hyphen
            . substr($charid, 12, 4) . $hyphen
            . substr($charid, 16, 4) . $hyphen
            . substr($charid, 20, 12);
            return $uuid;
        }
    }
}

if (!function_exists('fromat_html_data')) {
    function fromat_html_data($string) {
        if (strpos($string, '&lt;') !== false) {
            return $string;
        } else {
            $string = preg_replace('/<iframe[\s\S]*?<\/iframe>/i', '', $string);
            return htmlspecialchars($string);
        }
    }
}

if (!function_exists('get_empty_obj')) {
    function get_empty_obj() {
        return new stdClass();
    }
}

if (!function_exists('get_random_str')) {
    //获取随机字符串
    function get_random_str($num = 4, $type = 0) {
        $code = '';
        $decimal = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9);
        $letter = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');
        if ($type == 0) {
            $codes = array_merge($decimal, $letter);
        } else if ($type == 1) {
            $codes = $decimal;
        } else {
            $codes = $letter;
        }
        for ($i = 0; $i < $num; $i++) {
            $code .= $codes[array_rand($codes)];
        }
        return $code;
    }
}

if (!function_exists('get_real_ip')) {
    function get_real_ip() {
        $real_ip = getenv('HTTP_X_REAL_IP');

        if ($real_ip) {
            return $real_ip;
        }

        if (getenv('HTTP_X_FORWARDED_FOR')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_CLIENT_IP')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } else {
            $ip = getenv('REMOTE_ADDR');
        }
        return $ip;
    }
}
