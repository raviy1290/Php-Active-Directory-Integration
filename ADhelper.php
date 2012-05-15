<?php

/**
 * AD helper library --ravi
 *
 * Authentication/ User/Group listing
 */
require_once('ad_config.php');

class ADhelper {
	//connection params
	public $hostname;
	public $port;
	
	// AD base DN
	public $base_dn;

	// credentials 
	public $username;
	public $password;
	
	//ldap connection
	//ldapp binding
	public $ldapconn;
	public $ldapbinding;
	
	function __construct(){
		
		$this->hostname 	= $config['hostname'];
		$this->port 		= $config['port'];
		$this->username 	= $config['username'];
		$this->password 	= $config['password'];
		$this->base_dn 		= $config['base_dn'];;
		
		$this->ldapconn = ldap_connect($this->hostname, $this->port) or die("Could not connect to $hostname");
		
		// compatibility setting/ fill DIT search
		ldap_set_option($this->ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
		ldap_set_option($this->ldapconn, LDAP_OPT_REFERRALS, 0);
		
		$this->ldapbinding = ldap_bind($this->ldapconn, $this->username, $this->password) or die("Could not bind to connection $ldapconn credentials $this->username, $this->password");
	}
	
	function get_users_list($time=null, $dn=null){
		
		if(!empty($dn)) $this->base_dn = $dn;
		
		$user_listing_filter = "(objectClass={$config['ad_user_object_class']})";
		if($time){
			$user_listing_filter = "(&(objectClass={$config['ad_user_object_class']})(|({$config['ad_createtime']}>=$time)({$config['ad_updatetime']}>=$time)))";
		}
		
		$user_listing_attributes = ($config['ad_user_listing_attributes']) ? $config['ad_user_listing_attributes'] : array('*');
		
		$user_group_search = ldap_search($this->ldapconn, $this->base_dn, $user_listing_filter, $user_listing_attributes); 
		
		
		$users_info = array();
		if(ldap_count_entries($this->ldapconn, $user_group_search)){
			$users_info = ldap_get_entries($this->ldapconn, $user_group_search);
		}
		return $users_info;
	}
	
	function get_group_list($time=null){
		
		$group_listing_filter = "(objectClass={$config['ad_group_object_class']})";
		if($time){
			$group_listing_filter = "(&(objectClass={$config['ad_group_object_class']})(|({$config['ad_createtime']}>=$time)({$config['ad_updatetime']}>=$time)))";
		}

		$group_listing_attributes = ($config['ad_group_listing_attributes']) ? $config['ad_group_listing_attributes'] : array('*');
		
		$group_search = ldap_search($this->ldapconn, $this->base_dn, $group_listing_filter, $group_listing_attributes); 
			
		$changed_groups = array();
		if(ldap_count_entries($this->ldapconn, $group_search)){
			$info = ldap_get_entries($this->ldapconn, $group_search);
			foreach($info as $elm){
				if(!empty($elm['dn'])) 
					$changed_groups[] = $elm['dn'];
			}
		}
		return $changed_groups;
	}
	
	function close_connection(){
		ldap_close($this->ldapconn);
	}
	
	function authenticate($user_dn, $password){
		$this->close_connection(); // close previous connection
		$this->username = $user_dn;
		$this->password = $password;
		
		$this->ldapconn = ldap_connect($this->hostname, $this->port) or die("Could not connect to $hostname");
		// compatibility setting/ fill DIT search
		ldap_set_option($this->ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
		ldap_set_option($this->ldapconn, LDAP_OPT_REFERRALS, 0);
		
		$error = False;
		try{
			$this->ldapbinding = ldap_bind($this->ldapconn, $this->username, $this->password);
		}
		catch(Exception $e){
			$error = True;
		}
		if($this->ldapbinding && !$error){
			$this->close_connection();
			return true;
		}else
			return false;
	}

}
/* End of file ADhelper.php */
