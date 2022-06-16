<?php
/**
 * page payouts 
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Elevator
 * @subpackage Workreap/includes
 * @author     Amentotech <theamentotech@gmail.com>
 */
class Workreap_payouts {

	// class instance
	static $instance;

	// customer WP_List_Table object
	public $payouts_obj;

	// class constructor
	public function __construct() {
		add_action( 'admin_menu', array(&$this, 'Payouts_menu' ) );
		
	}
	
	/**
	 * Payout Menu
	 *
	 * @throws error
	 * @author Amentotech <theamentotech@gmail.com>
	 * @return 
	 */
	public function Payouts_menu() {
		if ( function_exists( 'fw_get_db_settings_option' ) ) {
			$allow_freelancers_withdraw 	= fw_get_db_settings_option( 'allow_freelancers_withdraw', $default_value = null );
		}
		
		if(!empty($allow_freelancers_withdraw) && $allow_freelancers_withdraw === 'admin'){
			$hook = add_submenu_page('edit.php?post_type=freelancers', 
								 esc_html__('Payouts','workreap_core'), 
								 esc_html__('Payouts','workreap_core'), 
								 'manage_options', 
								 'payouts',
								array( &$this, 'Payouts_settings_page' )
							 );
			add_action( "load-$hook", array(&$this, 'screen_option' ) );
		}
	}
	
	/**
	 * Screen
	 *
	 * @throws error
	 * @author Amentotech <theamentotech@gmail.com>
	 * @return 
	 */
	public function Payouts_settings_page() {
		if( function_exists('workreap_list_month') ) {
			$months	= workreap_list_month();
		} else {
			$months	= array();
		}
		
		$years = array_combine(range(date("Y"), 1970), range(date("Y"), 1970));
		?>
		<div class="wrap">
			<h2><?php esc_html_e('Payouts','workreap_core');?></h2>
			<div id="poststuff">
				<div id="post-body" class="metabox-holder">
					<div id="post-body-content">
						<div class="meta-box-sortables ui-sortable">
							<form method="post">
								<div class="tablenav top">
									<div class="alignright">
										<select name="months" id="bulk-action-selector-top">
											<option value=""><?php esc_html_e('Select month','workreap_core');?></option>
											<?php if( !empty( $months ) ) {?>
												<?php foreach ( $months as $key	=> $val ) {?>
													<option value="<?php echo intval($key);?>"><?php echo esc_attr($val);?></option>
												<?php } ?>
											<?php } ?>
										</select>
										<select name="years" id="bulk-action-selector-top">
											<option value=""><?php echo esc_html__('Select year','workreap_core');?></option>
											<?php if( !empty( $years ) ) {?>
												<?php foreach ( $years as $key	=> $val ) {?>
													<option value="<?php echo intval($key);?>"><?php echo esc_attr($val);?></option>
												<?php } ?>
											<?php } ?>
										</select>
										<input type="submit" class="button" value="<?php esc_html_e('Download','workreap_core');?>">
									</div>
									<?php
										$this->payouts_obj->prepare_items();
										$this->payouts_obj->display();
									?>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php
	}

	/**
	 * Screen ption
	 *
	 * @throws error
	 * @author Amentotech <theamentotech@gmail.com>
	 * @return 
	 */
	public function screen_option() {

		$option = 'per_page';
		$args   = array(
			'label'   => esc_html__('Payouts','workreap_core'),
			'default' => 20,
			'option'  => 'payout_per_page'
		);

		add_screen_option( $option, $args );

		$this->payouts_obj = new Payouts_List();
	}

	/**
	 * Singleton instance
	 *
	 * @throws error
	 * @author Amentotech <theamentotech@gmail.com>
	 * @return 
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

}

$payouts	= new Workreap_payouts();
