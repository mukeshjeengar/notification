# Notification

Setup steps:

# clone the project
git clone https://github.com/mukeshjeengar/notification.git

#change directory
cd  notification

#set nginx push stream module path
NGINX_PUSH_STREAM_MODULE_PATH=$PWD/nginx-push-stream-module

#install libpcre3, libpcre3-dev
sudo apt-get install libpcre3 libpcre3-dev

cd nginx-1.2.0

./configure --add-module=../nginx-push-stream-module

make

# install and finish
sudo make install

# test configuration
sudo /usr/local/nginx/sbin/nginx -c $NGINX_PUSH_STREAM_MODULE_PATH/misc/nginx.conf -t
 	
 	#it will show below message if everything is ok
	the configuration file $NGINX_PUSH_STREAM_MODULE_PATH/misc/nginx.conf syntax is ok
	configuration file $NGINX_PUSH_STREAM_MODULE_PATH/misc/nginx.conf test is successful

# run
sudo /usr/local/nginx/sbin/nginx -c $NGINX_PUSH_STREAM_MODULE_PATH/misc/nginx.conf

Now nginx push stream module setup done.


Now Setup database

Create database test;
use test;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `message` varchar(1024) NOT NULL,
  `icon` varchar(30) NOT NULL,
  `is_read` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;



Now run cron to send random notifications via pub/sub

#insure the file should be executable if it does not then make it executable
chmod +x backend/RandomNotificaton.php

crontab -e * * * * * /usr/local/bin/php $NGINX_PUSH_STREAM_MODULE_PATH/backend/RandomNotificaton.php

#open crontab file
sudo crontab -e 

#add this line and save
* * * * * php /home/mj/notification/backend/RandomNotificaton.php

#now open index.html page. Here can see every minute random notification on this page.