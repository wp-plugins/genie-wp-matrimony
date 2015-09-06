<?php

/*
 * Created on Apr 28, 2012
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

class GwpmSetupModel {

	function checkSetupStatus() {
		if(get_role('matrimony_user')) return true ;
		return false;
	}

	private function createMatrimonyUserRole() {
		$caps = get_role('contributor')->capabilities;
		$caps['matrimony'] = 1 ;
		return add_role(GWPM_USER_ROLE, 'Matrimony User', $caps);
	}

	private function removeMatrimonyUserRole() {
		remove_role(GWPM_USER_ROLE);
	}

	private function createMatrimonyPage() {
		return wp_insert_post(array (
			'post_title' => GWPM_PAGE_TITLE,
			'post_name' => 'matrimony',
			'post_type' => 'page',
			'post_status' => 'publish',
			'comment_status' => 'closed',
			'ping_status' => 'closed',
			
		));
	}

	private function createMenuItems($postId) {
		$actionUrl = '?page_id=' . $postId . '&action=view&page=' ;
		$count = 0;
		$menu_id = 0 ;

		if ( ! is_nav_menu( 'gwpm-primary-menu' ) ) {
			$menu_id = wp_create_nav_menu ( 'gwpm-primary-menu' );
		} else {
			$menu_slug = 'gwpm-primary-menu';
			$locations = get_nav_menu_locations();

			if (isset($locations[$args->theme_location])) {
				$menu_id = $locations[$args->theme_location];
			}
		}

		appendLog('createMenuItems - primary_menu_item_oid: ' . $menu_id ) ;

		$parentMenuOid = addMenuItem($menu_id, 'Matrimony', $postId, $menu_id, $count)	;

		$menuPages = array (
			'profile' => 'Account',
			'activity' => 'Activity',
			'messages' => 'Messages',
			'gallery' => 'Gallery',
			'search' => 'Search',
		) ;

		foreach ($menuPages as $pageId => $pageTitle) {
			$count++ ;
			$newId = wp_insert_post(array (
				'post_title' => $pageTitle,
				'post_name' => 'gwpm_matrimony_' . $pageId,
				'post_type' => 'page',
				'post_status' => 'publish',
				'comment_status' => 'closed',
				'ping_status' => 'closed',
				'post_parent' => $postId, // Comment incase if the option not to be shown as Sub menu
				'menu_order' => $count,

			));
			$upVal = update_post_meta($newId, 'gwpm_load_url', $actionUrl . $pageId, true);

			appendLog('createMenuItems : ' . $newId . '-' . $pageTitle . '-' . $upVal) ;
			addMenuItem($menu_id, $pageTitle, $newId, $parentMenuOid, $count)	;

		}

		// Lower case theme_name
		$theme = strtolower ( str_replace ( ' ', '', wp_get_theme() ) );

		// Get the theme options
		$theme_mods = get_option ( 'theme_mods_' . $theme );

		// Set the location of the primary menu
		$theme_mods['nav_menu_locations'] = array ( 'primary' => $menu_id );

		// Update the theme options
		update_option ( 'theme_mods_' . $theme, $theme_mods );

	}

	private function removeMatrimonyPage($postId) {
		wp_delete_post($postId, true);
		clean_post_cache($postId);
	}

	private function removeMenuItems($postid) {
		global $wpdb ;
		$post_ids = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_parent = %d and post_name like %s", $postid, "gwpm_matrimony_%" ));
		if ( ! empty($post_ids) ) {
			foreach ( $post_ids as $post_id ) {
				wp_delete_post( $post_id, true );
				clean_post_cache($post_id);
			}
		}
	}

	private function removeDynamicFields() {
		global $wpdb ;
		$deletedRows = $wpdb->query( $wpdb->prepare( "DELETE  FROM $wpdb->options WHERE OPTION_NAME LIKE %s", "gwpm_dyna_field_%"));
		echo 'Deleted rows: ' . $deletedRows ;
	}

	private function updateMatrimonyPageMeta($postId) {
		return add_post_meta($postId, GWPM_META_KEY, GWPM_META_VALUE, true);
	}

	function setupGWPMDetails($init_request) {
		global $current_blog;

		$user_pref = $init_request[GWPM_USER_LOGIN_PREF] ;
		update_option(GWPM_USER_LOGIN_PREF, $user_pref);

		if ($this->createMatrimonyUserRole()) {
			$this->install_gwpm_db();
			$postId = $this->createMatrimonyPage();
			$postMetaId = $this->updateMatrimonyPageMeta($postId);
			$this->createMenuItems($postId);
				
			appendLog('from setupGWPMDetails : ' . $user_pref) ;
			if(isset($current_blog) && isset($current_blog->blog_id)) {
				update_option("gwpm_" . $current_blog->blog_id . "_". GWPM_META_KEY, true);
			} else {
				update_option("gwpm_" . GWPM_META_KEY, true);
			}
			return true;
		}
	}

	function removeGWPMDetails() {
		global $genieWPMatrimonyController;
		global $current_blog;
		$postId = $genieWPMatrimonyController->getGWPMPageId();
		$this->removeMatrimonyUserRole();
		$this->removeMenuItems($postId);
		$this->removeDynamicFields($postId);
		$this->removeMatrimonyPage($postId);
		if(isset($current_blog) && isset($current_blog->blog_id)) {
			delete_option("gwpm_" . $current_blog->blog_id . "_". GWPM_META_KEY);
		} else {
			delete_option("gwpm_" . GWPM_META_KEY);
		}
	}

	function install_gwpm_db() {
		global $gwpm_db_version;
		global $wpdb;
		require_once (ABSPATH . 'wp-admin/includes/upgrade.php');

		$activity_sql = "CREATE TABLE " . $wpdb->prefix . "gwpm_activity (
		  uid mediumint(9) NOT NULL,
		  act_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		  act_type tinytext NOT NULL,
		  act_text text
		);";

		$message_sql = "CREATE TABLE " . $wpdb->prefix . "gwpm_messages (
			mid int (11) NOT NULL,
			pmid int (11) NOT NULL,
			user_id int (11) NOT NULL,
			recipient_id int (11) NOT NULL,
			user_message blob,
			unread tinyint (1),
			folder varchar (30),
			created datetime 
		);";

		dbDelta($activity_sql);
		dbDelta($message_sql);
	}

	function update_gwpm_db() {
		echo 'Update method called';
		throw new exception('update method called');
	}

	function gwpm_update_db_check() {
		global $gwpm_db_version;
		$gwpm_dbver_in_db = get_option('gwpm_db_version', false);
		if ($gwpm_dbver_in_db == false) {
			$this->install_gwpm_db();
			add_option("gwpm_db_version", $gwpm_db_version);
		} elseif (get_site_option('gwpm_db_version') < $gwpm_db_version) {
			$this->update_gwpm_db();
			update_option("gwpm_db_version", $gwpm_db_version);
		}
	}

	function sendRegistrationMail($user_id) {
		global $wpdb;
		global $gwpm_activity_model ;
		$email = $wpdb->get_var($wpdb->prepare("select user_email from $wpdb->users where ID = '%d'", $user_id));
		$from = get_option('admin_email');
		$headers = 'From: '.$from . "\r\n";
		$subject = "Registration successful";
		//$msg = "Registration successful.\nYour login details\nUsername: $username\nPassword: $random_password";
		$msg = "Thanks for registering with " . get_option('blogname') . ". \n\nIf you wish to convert your account to " .
        		"matrimonial account click the following link and make a request.\n\n"  ;
		$msg .= "Request for Matrimonial account: " . get_option("siteurl") . '/?page_id=' . $this->getMatrimonialId() . '&page=subscribe' ;
		$msg .= "\n\nRegards\n\nAdmin." ;
		wp_mail( $email, $subject, $msg, $headers );
		$gwpm_activity_model->addActivityLog("register", "Joined " . get_option('blogname'), $user_id) ;
	}

	function getMatrimonialId(){
		global $wpdb;
		return $wpdb->get_var($wpdb->prepare("select post_id from $wpdb->postmeta where meta_key = '%s'", GWPM_META_KEY));
	}

}