<?php
class GenieWPMatrimonyController {

	protected $_matrimonyPageId;
	protected $links;

	function GenieWPMatrimonyController() {
		add_action('init', array (
			& $this,
			'gwpm_init'
		));
		add_action( 'admin_footer', array(
			& $this, 
			'gwpm_warning' 
		));
		add_action( 'admin_head', array(
			& $this, 
			'gwpm_admin_header' 
		));
		add_action('admin_menu', array (
			& $this,
			'gwpm_admin_action'
		));
		add_action('avatar_defaults', array (
			& $this,
			'gwpm_avatar_defaults'
		));
		add_action('get_avatar', array (
			& $this,
			'gwpm_get_avatar'
		), 10, 4);
		add_filter( 'wp_list_pages', array(
			& $this, 
			'gwpm_wp_list_pages'
		));
		add_filter( 'page_link', array(
			& $this, 
			'gwpm_link_page'
		), 20, 2 );
		add_filter( 'post_link', array( 
			& $this, 
			'gwpm_link_page'
		), 20, 2 );
		add_filter( 'post_type_link', array( 
			& $this, 
			'gwpm_link_page'
		), 20, 2 );
		add_filter('the_content', array (
			& $this,
			'page_route'
		), 200);
		add_action('wp_enqueue_scripts', array (
			& $this,
			'load_scripts'
		), 0);
		add_action('wp_ajax_gwpm_ajax_call', array (
			& $this,
			'gwpm_ajax_call_bootstrap'
		));
		add_action( 'wp_login' , array (
			& $this,
			'gwpm_update_access_log'
		), 10, 2);
		add_action( 'user_register', array (
			& $this,
			'gwpm_user_registered'
		));
		add_action( 'template_redirect', array(
			& $this, 
			'gwpm_template_redirect'   
		));
	}

	function gwpm_init() {
		global $wpdb;
		$this->_matrimonyPageId = $wpdb->get_var($wpdb->prepare("select post_id from $wpdb->postmeta where meta_key = '%s'", GWPM_META_KEY));
	}
	
	function gwpm_admin_header() {
		$siteurl = get_option('siteurl');
   		$url =  GWPM_PUBLIC_CSS_URL . URL_S . 'gwpm_admin_style.css';
    		echo "<link rel='stylesheet' type='text/css' href='$url' />\n";
	}

	function gwpm_admin_action() {
		if (current_user_can('edit_users')) {
			add_options_page('Genie WP Matrimony', 'Genie WP Matrimony', 'activate_plugins', 'gpwma', array (
				& $this,
				'gwpm_admin_page'
			));
		}
		add_menu_page('Manage Matrimonial Profile', 'Matrimony', 'edit_posts', 'gpwmp', array (
			& $this,
			'gwpm_admin_page_profiles'
		));
	}
	
	function gwpm_avatar_defaults($avatar_defaults) {
		$new_avatar_url = GWPM_PUBLIC_IMG_URL . URL_S . 'gwpm_avatar.png'  ;
		$avatar_defaults[$new_avatar_url] = __('Genie WP Matrimony Avatar');
		appendLog(print_r($avatar_defaults, true)) ;
		return $avatar_defaults;
	}
	
	function gwpm_get_avatar($avatar, $id_or_email, $size, $default) {
		global $wpdb ;
		
		appendLog("avatar: " . $avatar) ;
		appendLog("default: " . $default) ;
		appendLog( $id_or_email );
		
		if( strpos($default, GWPM_AVATAR) !== false ) {
			$imageURL = GWPM_PUBLIC_IMG_URL . URL_S . 'gwpm_icon.png' ;
			appendLog("isAdmin: " . is_admin()) ;
			if(!is_admin()) {
				appendLog("Not Admin page") ;
				if(is_object($id_or_email)){
					if($id_or_email->ID)
						$id_or_email = $id_or_email->ID;
					else if($id_or_email->user_id)
						$id_or_email = $id_or_email->user_id;
					else if($id_or_email->comment_author_email)
						$id_or_email = $id_or_email->comment_author_email;
				}
		
				if(is_numeric($id_or_email))
					$userid = (int)$id_or_email;
				else if(is_string($id_or_email))
					$userid = (int)$wpdb->get_var("SELECT ID FROM $wpdb->users WHERE user_email = '" . mysql_escape_string($id_or_email) . "'");
				
				$imageURL = getGravatarImageForUser($userid) ;
				
			}
			
			appendLog("imageURL: " . $imageURL) ;
			
			$doc = new DOMDocument();
			$doc->loadHTML($avatar);
			$imageTags = $doc->getElementsByTagName('img');	
			
			foreach($imageTags as $tag) {
		        appendLog ( $tag->getAttribute('src') );
		        $imgSrc = $tag->getAttribute('src') ;
		        $tag->setAttribute( "src", $imageURL );
		        $avatar = $tag->ownerDocument->saveXML( $tag ) ;
		        appendLog("altered avatar: ") ;
		    }
			
		}
	    
	    appendLog ("printxml: " .  $avatar   ) ; 
		return $avatar;
	}
	
	/**
	 * Incase if a warning is needed to be displayed in place.
	 */ 
	function gwpm_warning() {
		global $current_blog;
		if(isset($current_blog) && isset($current_blog->blog_id)) {
			$options = get_option( "gwpm_" . $current_blog->blog_id . "_". GWPM_META_KEY );
		} else {
			$options = get_option( "gwpm_" . GWPM_META_KEY );
		}
		if ( !isset( $options ) || empty( $options ) ) {
			echo "<div id='message' class='error'><p><strong>Genie WP Matrimony not setup yet.</strong> " .
					"<a href=" . admin_url( 'options-general.php?page=gpwma' . ">Setup Genie WP Matrimony now.</a></p></div>");
		}
	}
	
	/**
	 * Filters the list of pages to alter the links and targets
	 * @param string $pages the wp_list_pages() HTML block from WordPress
	 * @return string the modified HTML block
	 */
	function gwpm_wp_list_pages( $pages ) {
		$highlight = false;
		$links = $this->gwpm_get_links();

		if ( !$links )
			return $pages;

		$this_url = ( is_ssl() ? 'https' : 'http' ) . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		foreach ( (array) $links as $id => $page ) {
			if ( str_replace( 'http://www.', 'http://', $this_url ) == str_replace( 'http://www.', 'http://', $page ) || ( is_home() && str_replace( 'http://www.', 'http://', trailingslashit( get_bloginfo( 'url' ) ) ) == str_replace( 'http://www.', 'http://', trailingslashit( $page ) ) ) ) {
				$highlight = true;
				$current_page = esc_url( $page );
			}
		}

		if ( $highlight ) {
			$pages = preg_replace( '| class="([^"]+)current_page_item"|', ' class="$1"', $pages ); // Kill default highlighting
			$pages = preg_replace( '|<li class="([^"]+)"><a href="' . preg_quote( $current_page ) . '"|', '<li class="$1 current_page_item"><a href="' . $current_page . '"', $pages );
		}
		return $pages;
	}
	
	/**
	 * Performs a redirect, if appropriate
	 */
	function gwpm_template_redirect() {
		if ( !is_single() && !is_page() )
			return;
		global $wp_query;
		$link = get_post_meta( $wp_query->post->ID, 'gwpm_load_url', true );
		if ( !$link )
			return;
		wp_redirect( $link, 301 );
		exit;
	}
	
	/**
	 * Filter for post or page links
	 * @param string $link the URL for the post or page
	 * @param int|object $post Either a post ID or a post object
	 * @return string output URL
	 */
	function gwpm_link_page( $link, $post ) {
		$links = $this->gwpm_get_links();

		// Really strange, but page_link gives us an ID and post_link gives us a post object
		$id = ( is_object( $post ) && $post->ID ) ? $post->ID : $post;

		if ( isset( $links[$id] ) && $links[$id] )
			$link = esc_url( $links[$id] );

		return $link;
	}
	
	/**
	 * Returns all links for the current site
	 * @return array an array of links, keyed by post ID
	 */
	function gwpm_get_links() {
		global $wpdb, $blog_id;
		if ( !isset( $this->links[$blog_id] ) ) {
			$gwpm_links = $wpdb->get_results( $wpdb->prepare( "SELECT post_id, meta_value FROM $wpdb->postmeta WHERE meta_key = %s", 'gwpm_load_url' ) );
		}
		else
			return $this->links[$blog_id];

		if ( !$gwpm_links ) {
			$this->links[$blog_id] = false;
			return false;
		}

		foreach ( (array) $gwpm_links as $gwpm_link )
			$this->links[$blog_id][$gwpm_link->post_id] = $gwpm_link->meta_value;

		return $this->links[$blog_id];
	}

	function gwpm_admin_page_profiles() {
		include (GWPM_APPLICATION_URL . DS . 'views' . DS . 'gwpm_ctrl_profile.php');
	}

	function gwpm_admin_page() {
		include (GWPM_APPLICATION_URL . DS . 'views' . DS . 'gwpm_ctrl_admin.php');
	}
	
	function gwpm_update_access_log($username, $user) {
		global $gwpm_activity_model ;
		$gwpm_activity_model->addActivityLog("login", null, $user->ID) ;
	}
	
	function gwpm_user_registered($user_id) {
		global $gwpm_setup_model ;
		$gwpm_setup_model->sendRegistrationMail($user_id) ;
	}

	function load_scripts() {
		wp_enqueue_style('jquery-ui-all', GWPM_PUBLIC_CSS_URL . URL_S . 'jquery' . URL_S . 'ui.all.css', false, null, false);
		wp_enqueue_style('jquery-lightbox-all', GWPM_PUBLIC_CSS_URL . URL_S . 'jquery' . URL_S . 'jquery.lightbox.css', false, null, false);
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-widget' );
		wp_enqueue_script( 'jquery-ui-accordion' );
		wp_enqueue_script( 'jquery-ui-slider' );
		wp_enqueue_script( 'jquery-ui-tabs' );
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_register_script( 'jquery-ui-timepicker', GWPM_PUBLIC_JS_URL . URL_S . 'jquery-ui-timepicker.js', array('jquery-ui-datepicker'));
		wp_register_script( 'jquery-ui-lightbox', GWPM_PUBLIC_JS_URL . URL_S . 'jquery.lightbox-0.5.min.js');
		wp_enqueue_script('jquery-ui-timepicker', null, null, true);
		wp_enqueue_script('jquery-ui-lightbox', null, null, true);
		wp_register_style('GWPM_CSS', GWPM_PUBLIC_CSS_URL . URL_S . 'gwpm_style.css');
		wp_enqueue_style('GWPM_CSS', null, null, true);
		wp_register_script('GWPM_JS', GWPM_PUBLIC_JS_URL . URL_S . 'gwpm_common.js', array('jquery') );
		wp_enqueue_script('GWPM_JS', null, null, true);
		wp_localize_script('GWPM_JS', 'MyAjax', array (
			'action' => 'gwpm_ajax_call',
			'ajaxurl' => admin_url('admin-ajax.php'
		), 'gwpm_nounce' => wp_create_nonce('gwpm')));
	}

	function gwpm_ajax_call_bootstrap() {
		if (!wp_verify_nonce($_POST['gwpm_nounce'], 'gwpm'))
			die('Busted!');
		if (current_user_can('matrimony_user') || current_user_can('level_10')) {
			$ajaxController = new GwpmAjaxController();
			$ajaxController->processRequest($_POST) ;
		} else {
			echo 'Invalid request from Unauthorized User';
		}

		die(); // this is required to return a proper result
	}

	function page_route($content) {
		global $post;		
		if ($post->ID == $this->_matrimonyPageId) {
			$content = "" ;
			ob_start();
			try {
				if (current_user_can('level_1')) {
					//wp_register_style('GWPM_CSS', GWPM_PUBLIC_CSS_URL . URL_S . 'gwpm_style.css');
					//wp_enqueue_style('GWPM_CSS', null, null, true);
					$controller = null ;
					$action = null ;
					if (isset ($_GET['page'])) {
						$controller = $_GET['page'];
					} else
						$controller = 'index';
					if ($controller == 'admin') {
						if (!current_user_can('level_10')) {
							include (GWPM_APPLICATION_URL . DS . 'views' . DS . 'gwpm_pg_unauthorized.php');
							$content = ob_get_contents();
							ob_end_clean();
							return $content;
						}
					} elseif ($controller == 'test') {
						include (GWPM_APPLICATION_URL . DS . 'views' . DS . 'gwpm_pg_test.php');
						$content = ob_get_contents();
						ob_end_clean();
						return $content;
					}
					if (isset ($_GET['action'])) {
						$action = $_GET['action'];
					}
					$controllerName = 'Gwpm' . ucwords($controller) . 'Controller';
					$modelName = 'Gwpm' . ucwords($controller) . 'Model';
					$controllerURL = GWPM_APPLICATION_URL . DS . 'controllers' . DS . $controllerName . '.php';
					$modelURL = GWPM_APPLICATION_URL . DS . 'models' . DS . $modelName . '.php';
					if (!file_exists($controllerURL)) {
						$controller = 'index';
						$controllerName = 'Gwpm' . ucwords($controller) . 'Controller';
						$modelName = 'Gwpm' . ucwords($controller) . 'Model';
						$controllerURL = GWPM_APPLICATION_URL . DS . 'controllers' . DS . $controllerName . '.php';
						$modelURL = GWPM_APPLICATION_URL . DS . 'models' . DS . $modelName . '.php';
					}
					require_once ($controllerURL);
					require_once ($modelURL);
					if ($action == null || $action == '') {
						$action = 'view';
					}
					$queryVariables = $this->get_query_string_values($_SERVER['REQUEST_URI']);
					$dispatch = new $controllerName ($controller, $action, $queryVariables, $modelName);
	
					if ((int) method_exists($controllerName, $action)) {
						call_user_func_array(array ($dispatch, $action ), $queryVariables);
					} else {
						throw new GwpmCommonException("Method " . $action . ' not found in class ' . $controllerName);
					}
				} elseif (current_user_can('level_0')) {
					//if (isset ($_GET['page'])) {
					//	if ($_GET['page'] == 'subscribe') {
							include (GWPM_APPLICATION_URL . DS . 'views' . DS . 'gwpm_pg_subscribe.php');
							$content = ob_get_contents();
							ob_end_clean();
							return $content;
					//	}
					//}
				} else {
					include (GWPM_APPLICATION_URL . DS . 'views' . DS . 'gwpm_pg_login.php');
				}
			} catch (Exception $e) {
				$backUrl = '<a href="javascript:window.history.back();" rel="prev">Go Back</a>';
				echo "\r\n" . $e->getMessage() . " \r\n\r\n " . $backUrl . "\n" ;
				if(DEVELOPMENT_ENVIRONMENT == true) {
					throw $e;
				}
			}
			$content = ob_get_contents();
			ob_end_clean();
		}
		return $content;
	}

	function setGWPMPageId($pageId) {
		$this->_matrimonyPageId = $pageId;
	}

	function getGWPMPageId() {
		return $this->_matrimonyPageId;
	}

	function get_query_string_values($link) {
		$queryStrings = array ();
		$vars = explode('?', $link);
		$counter = 0;
		foreach ($vars as $var) {
			if ($counter != 0) {
				$qStrs = explode('&', $vars[$counter]);
				foreach ($qStrs as $qStr) {
					$pairs = explode('=', $qStr);
					if ($pairs[0] == '_wpnonce') {
						if (!check_admin_referer('gwpm')) {
							die("Invalid server request");
						}
					}
					$queryStrings[$pairs[0]] = $pairs[1];
				}
			}
			$counter++;
		}
		return $queryStrings;
	}

}