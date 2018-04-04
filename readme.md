车险电销管理系统 v1.2
===============

# 安装
## 必需的软件包
	php version  5.2.17
	PHP extension APC-3.3.13 
	PHP extension php_zip
	PHP extension php_xml 
	PHP extension php_gd2
	PHP extension php_mcrypt
	PHP extension php_mbstring 
	PHP extension php_soap 
	PHP extension php_mysql
	PHP extension php_ldap

## 服务器配置
### (1)新建一个系统用户  
	 adduser zswitch
	 usermod -G root zswitch
	 passwd zswitch 
	 
###	(2)修改apache执行用户,重启apache
	 /etc/httpd/conf/httpd.conf
	        
### (3)修改目录权限
	chown root:zswitch /var/lib/php/session
	chown -R zswitch:zswitch /var/www/html