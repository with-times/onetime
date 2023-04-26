# 与时同行

> 用一生的时间去抵达遥远的未来

## 安装
```shell
$ git clone https://github.com/with-times/onetime.git
```

```shell
$ cd onetime
$ composer install
$ cp .env.example .env
$ php artisan key:generate

```

## 配置数据库连接信息

在 .env 文件中配置数据库连接信息，包括数据库类型、数据库地址、数据库名称、数据库用户名和密码等。

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=database_name
DB_USERNAME=database_username
DB_PASSWORD=database_password
# Feed订阅ssl验证
FEED_VERIFY=false
# 全文搜索驱动以及是否开启队列
SCOUT_QUEUE=true
SCOUT_DRIVER=database
```

## 导入全文搜索索引
```shell
$ php artisan scout:import "App\Models\Web/Website"
$ php artisan scout:import "App\Models\Web/Subscribe"
```

## 配置job定时任务

```shell
#本地运行调度程序
$ php artisan schedule:work
#运行队列
$ php artisan queue:work

```
