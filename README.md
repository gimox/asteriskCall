FOXBOX API WEB SERVICE
================================

A custom WebService for Asterisk Outgoing call queue.



DIRECTORY STRUCTURE
-------------------

      assets/             contains assets definition
      commands/           contains console commands (controllers)
      config/             contains application configurations
      controllers/        contains Web controller classes
      mail/               contains view files for e-mails
      models/             contains model classes
      runtime/            contains files generated during runtime
      tests/              contains various tests for the basic application
      vendor/             contains dependent 3rd-party packages
      views/              contains view files for the Web application
      web/                contains the entry script and Web resources



REQUIREMENTS
------------

Linux Machine with working asterisk

linux version: Centos 6.x (you can use debian or other distro but this guide is based on Centos);

apache 2.2x

php 5.4 >




INSTALLATION
------------


### DOWNLOAD VIA GIT

    https://github.com/gimox/foxboApi.git
    php composer.phar update



### ASTERISK CONFIGURATION


1. ensure you have mod_rewrite enabled in /etc/httpd/conf/httpd.conf -> uncoment LoadModule rewrite_module modules/mod_rewrite.so, you can check if enabled looking phpinfo(); inside loaded modules in CONFIGURATION->apache2handler, by default in centos is enabled
2. add virtualhost to apache virtualhost. Open directory
3. copy folder app named asteriskCall inside /var/www/ - now you have /var/www/asteriskCall, you can do it using git 
4. add right to folder:  chown -R 3asy:apache asteriskCall / chmod -R 777 asteriskCall/web/assets
5. ensure you have php mbstring installed ->install it with yum install php-mbstring :
 yum install php54w.x86_64 php54w-cli.x86_64 php54w-common.x86_64 php54w-gd.x86_64 php54w-ldap.x86_64 php54w-mbstring.x86_64 php54w-mcrypt.x86_64 php54w-mysql.x86_64 php54w-pdo.x86_64
6. restart apache service httpd restart
7. set permission to asterisk folder outgoing -> sudo chmod -R 777 /var/spool/asterisk/outgoing/   



### CHECK
http

http://foxboxip:8081


SSL

https://foxboxip:8082




### APPLICATION CONFIGURATION

before start using webservice, please configure app according to your hw.

Open /mnt/flash/foxboxApi/config/params.php and change the params:






IMPORTANT INFO
--------------
For security reason, asterisk can't be in WWW. It must be under firewall and accessible only by application server in Https.

this version is not in release yet, please don't use it now.