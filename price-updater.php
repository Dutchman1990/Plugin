<?php
/**
*@package Share Price Updater
*Plugin Name: Share Price Uploader
*Plugin URI: https://github.com/Dutchman1990/Plugin-SharePrice
*Description: An Custom Pluging for Share Price Updater via CSV File.
*Version: 1.0.0
*Author: DutchMan1990
*Author URI: https://github.com/Dutchman1990/
*Licence:
*Text Domain: price-updater
*/

if ( ! defined( 'ABSPATH')) {
	die('Humans aren\t supposed to be here.');
}

/**
* Class
*/
class PriceUpdater{
	//construct

	function init(){
		add_action( 'admin_menu', array($this,'PriceUpdateMenu'));
		//add_action( 'admin_init', array($this,'menu_output'));
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
	public function PriceUpdateMenu(){
    	add_menu_page('Upload Share Prices in CSV format', 'Price Upload', 'manage_options', 'shareprice_upload', array($this,'admin_index'),'dashicons-tickets',100 );
	}

	public function admin_index()
	{
		require_once plugin_dir_path( __FILE__ ). 'template/admin.php';
	}
}

if(class_exists('PriceUpdater')){
	$priceUpdater = new PriceUpdater();
	$priceUpdater->init();
}

/**
*activation
*/
register_activation_hook( __FILE__, array($priceUpdater,'activate') );


register_deactivation_hook( __FILE__, array($priceUpdater,'deactivate') );


 