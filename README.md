# 自封装 yaf 框架

## 环境要求

* php7.0+
    * yaf
* composer

## 安装

composer create-project --prefer-dist test-lin/yaf blog

## 配置

1、nginx 站点

```conf
server {
    listen 80;
    root   /var/www/yaf-framework/public;
    server_name demo.me www.demo.me;
    index  index.php;

    charset utf-8;
    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }
    error_log  /var/log/nginx/yaf.log error;

    location / {
        if (!-e $request_filename) {
            rewrite ^/(.*)$  /index.php?$1 last;
        }
    }

    location ~ \.php$ {
        fastcgi_pass   127.0.0.1:9000;
        fastcgi_index  index.php;
        fastcgi_param  SCRIPT_FILENAME  $document_root$fastcgi_script_name;
        include        fastcgi_params;
    }
}
```

2、框架配置

conf/application.ini 不存在则复制 conf/application-dev.ini 并进行配置

## 开发规范

* 基础规范 RSP-4
* 文件夹命名：全英文小写
* 类文件及类名命名：大驼峰
* 变量、类属性命名：小驼峰
* 常量命名：大写英文 + 下划线
* 类方法命名：小驼峰
* 函数命名：小写英文 + 下划线
* 无任何关联的函数全部放置在公共函数中
