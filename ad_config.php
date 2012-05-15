<?php
/**
 */
$config['remote_tmz']   = 'UTC';
$config['hostname'] 	= '***'; 
$config['port'] 		= 389;															
$config['username'] 	= 'cn=*****, cn=***, dc=***, dc=***';			
$config['password'] 	= '*****';													
$config['base_dn'] 		= 'DC=*****,DC=*****';  									


$config['ad_user_object_class'] 	= 'organizationalPerson';
$config['ad_group_object_class'] 	= 'organizationalUnit';

$config['ad_createtime'] = 'whenCreated'; //whenCreated createtimestamp
$config['ad_updatetime'] = 'whenChanged'; //whenChanged modifytimestamp

$config['ad_user_listing_attributes'] = array('cn', 'sAMAccountName', 'whenCreated', 'whenChanged'); // AD attributes
$config['ad_group_listing_attribute'] = array('whenCreated', 'whenChanged'); // AD attributes

?>