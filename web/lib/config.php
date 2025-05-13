<?php
/*
说明:
	(*) Required | 必填
	(#) Optional - if omitted, it works in most cases| 选填，不乱动的话，保持默认可以运行
	(?) Do Not Modify - unless you know what you are doing | 不要动，除非你通过阅读代码知道它在系统中如何作用
*/
if (!defined('VALID')){
?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="refresh" content="15;URL=<?php echo "https://www.mps.gov.cn/";?>" />
		<title>傻逼来了?</title>
		<link rel="stylesheet" type="text/css" href="style/remind.css" />
	</head>
	<body>
		<div class="notice">
			<div style="margin:20px auto;text-align:center;">
				<h1 style="font-size:40px">滚，你妈生你的时候逼眼儿被我的鸡巴堵死了，你是从屁眼儿拉出来的！</h1>
				<h1 style="font-size:40px">拉出来的时候全身都是屎！味道现在还没洗干净！</h1>
			</div>
		</div>
	</body>
</html>
<?php
exit();
}
header('content-type:text/html;charset=utf-8');
const DB_HOST = ''; // (*) MySQL host - typically 'localhost' or '127.0.0.1'. | MySQL 数据库主机名，一般就是 'localhost'
const DB_USER = ''; // (*) MySQL auth user name - typically 'root'.(NOT 'root'@'localhost') | MySQL 登录用户名，一般就填 'root'，不要填 'root@localhost'，因为认证的 localhost 是由 MySQL 自动识别
const DB_PASS = ''; // (*) MySQL auth password - you should have set up a password while configuing MySQL DBMS. | MySQL 登录密码，需要自己设置一下，注意，只需要给 User=root,Host=localhost 的账户设置密码即可
const DB_NAME = 'type'; // (?) MySQL database name, the provided file type.sql creates a db after this name. | MySQL 针对该项目的数据库名，这个名字在建库文件 type.sql 中被固定，所以最好不修改这个配置
const DB_PORT = 3306; // (#) MySQL transport layer listen port - It is highly recommended to listen to unixsocket and set this to 0 | MySQL 的网络监听端口，如果 MySQL 和 PHP 运行在同一台服务器上，那么应该把这个值设置为 0，然后正确配置下方 DB_SOCK 的值。这需要在设置 MySQL 时修改 MySQL 的配置文件并非重载，最高效的方法是下面被注释的这一行，MySQL 默认会监听在下面那行 '/var/run/mysql/mysqld.sock' 的位置。
// const DB_SOCK = '/dev/shm/mysqld.sock';
const DB_SOCK = '/var/run/mysql/mysqld.sock'; // (#) 建议把 MySQL 配置为监听 '/dev/shm/mysqld.sock'，然后注释这行，解开上面那行