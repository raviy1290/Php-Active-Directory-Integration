Php-Active-Directory-Integration
================================

a library to integrate (load/sync) group/user data of window servers active directory in php script

usage:
        
        $adhelper = new ADhelper();
		$now = get_nowstamp_db();
        $user_list = $adhelper->get_users_list($time=null, $dn=null);
		$this->adhelper->close_connection();