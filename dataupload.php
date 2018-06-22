<?php
/**
*@package CSV Data Uploader
*Plugin Name: CSV Uploader
*Plugin URI: https://github.com/Dutchman1990/Plugin-SharePrice
*Description: An Custom Plugin for upload Dividend Data,Share Price and NAV Data via CSV File.
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
	public function __construct(){
		add_action('wp_ajax_my_action', array($this,'my_action'));
	add_action('wp_ajax_nopriv_my_action', array($this,'my_action'));
	}

	function init(){
		add_action( 'admin_menu', array($this,'DividendUpdateMenu'));
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
		add_action('admin_enqueue_scripts', 'admin_load_js');
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
    	add_submenu_page( 'upload_data', 'Share Price', 'Share Price','manage_options', 'shareprice', array($this,'shareprice_index'));
	}

	public function mainadmin(){
			require_once plugin_dir_path( __FILE__ ). 'template/admin.php';
		}
	public function dividend_index(){
		require_once plugin_dir_path( __FILE__ ). 'template/dividend.php';
	}

	public function share_index(){
		require_once plugin_dir_path( __FILE__ ). 'template/shares.php';
	}

	public function nav_index(){
		require_once plugin_dir_path( __FILE__ ). 'template/nav.php';
	}
	public function shareprice_index(){
		require_once plugin_dir_path( __FILE__ ). 'template/sharepriceshow.php';
	}

	function enqueue() {
		wp_enqueue_style( 'uicss', plugins_url( '/assets/css/uicss.css', __FILE__ ) );
		wp_enqueue_style( 'maincss', plugins_url( '/assets/css/maincss.css', __FILE__ ) );
		wp_enqueue_script('jquery', plugin_dir_url(__FILE__) . 'assets/js/jquery.js', array('jquery'));
		wp_enqueue_script('ui', plugin_dir_url(__FILE__) . 'assets/js/jqueryui.js');
		//wp_localize_script( 'ajax-test', 'the_ajax_script', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );	

	}

	
	function my_action(){
		$a=$_POST['date'];
		$b=$_POST['date2'];?> 
			<table>
		  <tr><?php //echo $a; ?>
		  	<th>ID</th>
		    <th>Ticker</th>
		    <th>Date</th>
		    <th>High</th>
		    <th>Open</th>
		    <th>Low</th>
		    <th>Close</th>
		    <th>Volume</th>
		    <th>Adjclose</th>
		  </tr>

				<?php 
				global $wpdb;
				$daysdiffernce = date_diff(date_create($a),date_create($b));
				$diff=$daysdiffernce->format("%R%a");
				if($diff<0){
					echo '</table><div style="text-align:center;margin-top: 10px;color: red;font-weight: 600;">Start date cannot be greater than end date.</div>';
				}else{
						$sharepricedata=$wpdb->get_results("SELECT * FROM share_prices WHERE DATE BETWEEN '$a' AND '$b' ");
						if(empty($sharepricedata)){
						    echo '</table><div style="text-align:center;margin-top: 10px;color: red;font-weight: 600;">No Data found.</div>';
						    //echo $daysdiffernce->format("a");
						}
						else{
						$count=1;
						foreach ($sharepricedata as $price) { //$count=1; //print_r($price);?>
							<tr>
						    <td><?php echo $count; //echo $daysdiffernce->format("a");?></td>
						    <td><?php echo $price->TICKER;  ?></td>
						    <td><?php echo $price->DATE;  ?></td>
						    <td><?php echo $price->HIGH;  ?></td>
						    <td><?php echo $price->OPEN;  ?></td>
						    <td><?php echo $price->LOW;  ?></td>
						    <td><?php echo $price->CLOSE;  ?></td>
						    <td><?php echo $price->VOLUME;  ?></td>
						    <td><?php echo $price->ADJCLOSE;  ?></td>
						  </tr>
						<?php $count++; } }?>
			</table>   	
			<?php    die();
					}
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


 