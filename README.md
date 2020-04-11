CMTopThink V6.0
===============

> 运行环境要求PHP7.1+。

## 主要功能

* 权限控制
* 双后台
* 角色/用户管理
* 资金余额管理
* 刷单平台

## 技术
* ThinkPHP v6.0
* LayUI v2.5.5

## 安装

~~~
git clone https://github.com/chenvle/CMTopThink.git
cd CMTopThink
composer update
cp .example.env .env
~~~
##### 配置数据库（.env 配置文件）
~~~
APP_DEBUG = true
key = ksfjalksnfksldfksldjfklsdjflkasdf

[APP]
DEFAULT_TIMEZONE = Asia/Shanghai


[DATABASE]
TYPE = mysql
HOSTNAME = 127.0.0.1
DATABASE = test
USERNAME = username
PASSWORD = password
HOSTPORT = 3306
CHARSET = utf8
DEBUG = true

[LANG]
default_lang = zh-cn
~~~

##### 迁移/填充数据
~~~
php think migrate:run
php think seed:run
~~~

##### 账号
~~~
http://localhost/admin
管理员
账号：cmtime
密码：cmtime

会员
账号：chenvle
密码：chenvle
~~~

##### Demo
~~~
https://top.cmtime.cn/
~~~