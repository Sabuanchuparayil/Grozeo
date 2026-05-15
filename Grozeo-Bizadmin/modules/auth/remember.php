<?php

/* Used for the remember me settings
 * Created on 13-Aug-08
 * app.rgcb
 * Created by niju
 *
 */
/* Modified by sreeram on 5/4/2010
 * reason - Introduced encryption for cookie value
 */
	$remember	= 0;
	 if(isset($_COOKIE['remember_uidnr_admin'])){
	   
                  $cookie_id_time=array();
                  // Cookie contains user id and microtime in encrypted format
                  $cookie_val = $_COOKIE['remember_uidnr_admin'];
                  //echo $cookie_val = encrypt_decrypt($cookie_val);
                  $cookie_val = decrypt($cookie_val);
                   // split it
                  $cookie_id_time=explode(':',$cookie_val);
               if(is_numeric($cookie_id_time['0'])){

                   $admin_id = $cookie_id_time['0'];
                   $password = $cookie_id_time['1'];
                    //$query 		= sprintf('select admin_username, admin_password, admin_active from ' .
                    ///						'admin_users where admin_active=%d and uidnr_admin =%d',1,$admin_id);

                    $query 		= sprintf('select UserName, Passwd, IsActive from ' . FINASCOP_DB . 'finascop_usr_master where IsActive=%d and UserId =%d and Passwd="%s"',1,$admin_id,$password);

                    $login		= $db->getFromDB($query);
                    if(count($login)>0){
                            $remember	= 1;
                            $rememberMe = 1;
                            $loginUsername	=  isset($login[0])?$login[0]:'';
                            $loginPassword	=  isset($login[1])?$login[1]:'';
                            $op = 'authenticate';
                    }
               }
	 }


