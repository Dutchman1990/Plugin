<?php
/**
*@package CSV Data Uploader
*Plugin Name: CSV Uploader
*Plugin URI: https://github.com/Dutchman1990/Plugin-SharePrice
*Description: An Custom Pluging for upload Dividend Data,Share Price and NAV Data via CSV File.
*Version: 1.0.0
*Author: DutchMan1990
*Author URI: https://github.com/Dutchman1990/
*Licence:
*Text Domain: csvdata-updater
*/

if ( ! defined( 'ABSPATH')) {
	die('Humans aren\t supposed to be here.');
}

/**
* Class
*/
class DividendUpdater{
	//construct

	function init(){
		add_action( 'admin_menu', array($this,'DividendUpdateMenu'));
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
	}
	
	function activate(){
		flush_rewrite_rules();
	}

	function deactivate(){
		flush_rewrite_rules();

	}

	function uninstall(){

	}

	//register menu for sidebar
	public function DividendUpdateMenu(){
    	add_menu_page('Upload Data in CSV format', 'CSV Uploader', 'manage_options', 'upload_data', array($this,'mainadmin'),'dashicons-tickets',100 );
    	add_submenu_page( 'upload_data', 'Dividend Data Upload', 'Dividend Upload', 'manage_options', 'dividend_upload', array($this,'dividend_index'));
    	add_submenu_page( 'upload_data', 'Share Price Upload', 'Share Price Upload', 'manage_options', 'share_upload', array($this,'share_index'));
    	add_submenu_page( 'upload_data', 'Nav Data Upload', 'NAV Upload', 'manage_options', 'nav_upload', array($this,'nav_index'));
	}

	public function mainadmin(){
			require_once plugin_dir_path( __FILE__ ). 'template/admin.php';
		}
	public function dividend_index(){
		require_once plugin_dir_path( __FILE__ ). 'template/navdata.php';
	}

	public function share_index(){
		require_once plugin_dir_path( __FILE__ ). 'template/shares.php';
	}

	public function nav_index(){
		require_once plugin_dir_path( __FILE__ ). 'template/nav.php';
	}

	function enqueue() {
		wp_enqueue_style( 'maincss', plugins_url( '/assets/css/maincss.css', __FILE__ ) );
		wp_enqueue_script( 'jquery', plugins_url( '/assets/js/jquery.js', __FILE__ ) );
		    //wp_enqueue_script( 'my_custom_script', plugin_dir_url() . '/SharePrice/assets/js/jquery-3.3.1.js' );

	}
}

if(class_exists('DividendUpdater')){
	$DividendUpdater = new DividendUpdater();
	$DividendUpdater->init();
}

/**
*activation
*/
register_activation_hook( __FILE__, array($DividendUpdater,'activate') );


register_deactivation_hook( __FILE__, array($DividendUpdater,'deactivate') );


 