���յ�������ϵͳ v1.2
===============

# ��װ
## ����������
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

## ����������
### (1)�½�һ��ϵͳ�û�  
	 adduser zswitch
	 usermod -G root zswitch
	 passwd zswitch 
	 
###	(2)�޸�apacheִ���û�,����apache
	 /etc/httpd/conf/httpd.conf
	        
### (3)�޸�Ŀ¼Ȩ��
	chown root:zswitch /var/lib/php/session
	chown -R zswitch:zswitch /var/www/html