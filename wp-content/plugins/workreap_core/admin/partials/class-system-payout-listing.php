<?php
/**
 * page payouts listing
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Elevator
 * @subpackage Workreap/includes
 * @author     Amentotech <theamentotech@gmail.com>
 */
require WorkreapGlobalSettings::get_plugin_path() . 'libraries/dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;

if( !class_exists('Payouts_List') ){
	class Payouts_List extends WP_List_Table {

		public function __construct() {
			
			parent::__construct( [
				'singular' => esc_html__( 'Payout', 'workreap_core' ), 
				'plural'   => esc_html__( 'Payouts', 'workreap_core' ),
				'ajax'     => false 
			] );

		}

		/**
		 * Retrieve payouts data from the database
		 *
		 * @throws error
		 * @author Amentotech <theamentotech@gmail.com>
		 * @return 
		 */
		public static function get_payouts( $per_page = 5, $page_number = 1 ) {

			global $wpdb;

			$sql = "SELECT * FROM {$wpdb->prefix}wt_payouts_history";

			if ( ! empty( $_REQUEST['orderby'] ) ) {
				$sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
				$sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' DESC';
			}
			
			if ( empty( $_REQUEST['orderby'] ) ) {
				$sql .= ' ORDER BY id DESC';
			}
			
			if( ! empty( $_REQUEST['s'] ) ){
				$search = esc_sql( $_REQUEST['s'] );
				$sql .= " WHERE card LIKE '%{$search}%'";
			}
			
			$sql .= " LIMIT $per_page";
			$sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;


			$result = $wpdb->get_results( $sql, 'ARRAY_A' );

			return $result;
		}

		/**
		 * Delete Payout
		 *
		 * @throws error
		 * @author Amentotech <theamentotech@gmail.com>
		 * @return 
		 */
		public static function delete_payouts( $id ) {
		  global $wpdb;
		  $wpdb->delete(
			"{$wpdb->prefix}wt_payouts_history",
			[ 'id' => $id ],
			[ '%d' ]
		  );
		}

		/**
		 * Change Payout Status
		 *
		 * @throws error
		 * @author Amentotech <theamentotech@gmail.com>
		 * @return 
		 */
		public static function change_status_payouts( $id ,$status) {
		  global $wpdb;
			if( !empty( $id ) && !empty( $status ) ) {
				$data			= array('status'	=> $status );
				$where			= array('id'		=> $id );
				$updated 		= $wpdb->update( "{$wpdb->prefix}wt_payouts_history", $data, $where );
				$payout_query 	= $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}wt_payouts_history where id =%d", $id );
				$payout			= $wpdb->get_row( $payout_query );

				// Send payouts notification to freelancer
				if(class_exists('Workreap_Email_helper') && $status == 'completed') {
					if (class_exists('WorkreapSendPayoutsNotification')) {
						$linked_profile 	= workreap_get_linked_profile_id($payout->user_id);
						$email_helper 		= new WorkreapSendPayoutsNotification();
						$emailData 			= array();
						$emailData['total_amount']  	= workreap_price_format($payout->amount, 'return');
						$emailData['freelancer_name']  	= get_the_title($linked_profile);
						$emailData['freelancer_email']  = get_userdata($payout->user_id)->user_email;
						$email_helper->send_notification_to_freelancer($emailData);
						
						//Push notification
						$push	= array();
						$push['freelancer_id']		= $payout->user_id;
						$push['type']				= 'earning_calculation';

						$push['%freelancer_name%']		= $emailData['freelancer_name'];
						$push['%total_amount%']			= $emailData['total_amount'];
						$push['%replace_total_amount%']	= $emailData['total_amount'];

						do_action('workreap_user_push_notify',array($payout->user_id),'','pusher_fr_earning_content',$push);
						
					}
				}
			}
		}

		/**
		 * Returns the count of records in the database.
		 *
		 * @throws error
		 * @author Amentotech <theamentotech@gmail.com>
		 * @return 
		 */
		public static function record_count() {
			global $wpdb;

			$sql = "SELECT COUNT(*) FROM {$wpdb->prefix}wt_payouts_history";

			return $wpdb->get_var( $sql );
		}

		/**
		 * Text displayed when no Payouts data is available
		 *
		 * @throws error
		 * @author Amentotech <theamentotech@gmail.com>
		 * @return 
		 */
		public function no_items() {
			esc_html_e( 'No Payouts avaliable.', 'workreap_core' );
		}

		/**
		 * Render a column when no column specific method exist.
		 *
		 * @throws error
		 * @author Amentotech <theamentotech@gmail.com>
		 * @return 
		 */
		public function column_default( $item, $column_name ) {
			$date_formate	= get_option('date_format');		
			$payrols 	= '';

			if( function_exists('workreap_get_payouts_lists') ){
				$payrols	= workreap_get_payouts_lists();
			}

			switch ( $column_name ) {
				case 'user_id':
					$user_name	= workreap_get_username($item[ $column_name ]);
					
					$this_user			= !empty( $item[ $column_name ] ) ? get_userdata($item[ $column_name ]) : '';
					$user_profile_url	= !empty( $this_user ) ? admin_url('users.php').'?s='.$this_user->user_email : '';
					
					// create a nonce
					$delete_nonce = wp_create_nonce( 'sp_delete_payout' );
					$title = esc_html__( 'Delete', 'workreap_core' );
					
					if(!empty($user_profile_url)){
						$user_name	= '<a href="'.$user_profile_url.'">'.$user_name.'</a>';
					}

					$actions = [
						'delete' => sprintf( '<a href="?post_type=freelancers&page=%s&action=%s&payout=%s&_wpnonce=%s">'.$title.'</a>', esc_attr( $_REQUEST['page'] ), 'delete', absint( $item['id'] ), $delete_nonce )
					];

					return $user_name . $this->row_actions( $actions );
				case 'amount':
					return workreap_price_format($item[ $column_name ]);
				case 'payment_method':
					$payrols = !empty( $payrols[$item[ $column_name ]]['title'] ) ? $payrols[$item[ $column_name ]]['title'] : '';
					return $payrols;
				case 'processed_date':
					return date_i18n($date_formate,strtotime($item[ $column_name ]));
				case 'status':
					$status_nonce = wp_create_nonce( 'sp_delete_payout' );
					$status	= !empty( $item[ $column_name ]) ? ($item[ $column_name ] === 'completed') ? 'inprogress' : 'completed' : '';
					$actions = [
						'change_status' => sprintf( '<a href="?post_type=freelancers&page=%s&action=%s&payout=%s&status=%s&_wpnonce=%s">'.ucwords($status).'</a>', esc_attr( $_REQUEST['page'] ), 'change_status', absint( $item['id'] ),$status, $status_nonce )
					];
					return ucwords($item[ $column_name ]).$this->row_actions( $actions );
			}
		}

		/**
		 * Associative array of columns
		 *
		 * @throws error
		 * @author Amentotech <theamentotech@gmail.com>
		 * @return 
		 */
		function get_columns() {
			$columns = array(
				'user_id' 			=> esc_html__( 'User Name', 'workreap_core' ),
				'amount'    		=> esc_html__( 'Amount', 'workreap_core' ),
				'payment_method'    => esc_html__( 'Payment Method', 'workreap_core' ),
				'processed_date'    => esc_html__( 'Processing Date', 'workreap_core' ),
				'status'   			=> esc_html__( 'Status', 'workreap_core' ),
			);

			return $columns;
		}

		/**
		 * Sortable
		 *
		 * @throws error
		 * @author Amentotech <theamentotech@gmail.com>
		 * @return 
		 */
		public function get_sortable_columns() {
			$sortable_columns = array(
				'user_id' 			=> array( 'user_id', true ),
				'processed_date' 	=> array( 'processed_date', false ),
				'amount' 			=> array( 'amount', false )
			);

			return $sortable_columns;
		}

		/**
		 * Download PDF
		 *
		 * @throws error
		 * @author Amentotech <theamentotech@gmail.com>
		 * @return 
		 */
		public function download_pdf($months='',$years='') {
			global $wpdb;

			ob_clean();
			ob_flush();
			$sql = "SELECT * FROM {$wpdb->prefix}wt_payouts_history";
			if( !empty( $months ) && !empty( $years ) ) {
				$list_months	= workreap_list_month();

				$sql .= " where	month=".$months." and year=".$years;
				$results 	= $wpdb->get_results( $sql, 'ARRAY_A' );
				$pdf_html = '';
				$dompdf = new Dompdf();
				$pdf_html .= $this->renderheader($months,$years);
				$pdf_html .= $this->renderPdfHtml($results);
				$pdf_html .= $this->renderFooter();
				$dompdf->set_option('isHtml5ParserEnabled', true);
				$dompdf->loadHtml($pdf_html);
				$dompdf->setPaper('A4', 'portrait');
				$dompdf->render();
				$dompdf->stream($months."-".$years."-payouts".".pdf");
			}

		}
		
		/**
		 * Render header
		 *
		 * @throws error
		 * @author Amentotech <theamentotech@gmail.com>
		 * @return 
		 */
		public function renderheader($months,$years){
			$border_image = get_template_directory() . '/images/border.jpg';
			$html = '<html>
			<head>
				<style>
					@page {
						margin: 10px 0px 50px 0px;
					}
		
					header {
						position: fixed;
						top: -20px;
						left: 0px;
						right: 0px;
						height: 50px;
						font-family: sans-serif;
						background: url('.$border_image.');
						background-size:1px;
						background-size: 100% 2px;
						background-repeat: no-repeat;
					}
		
					footer {
						position: fixed; 
						bottom: -60px; 
						left: 0px; 
						right: 0px;
						height: 50px; 
					}
					table { border-collapse: collapse; }
				</style>
			</head>
			<body style="font-family: sans-serif;">
				<header >
				<div style="width:100%; display: inline-block; text-align:center; font-family: sans-serif;">
					<table style="width:96%; margin:80px auto 0;">
						<tr style="text-align:left;">
							<td width="70%">
								<h1 style="font-size: 26px;line-height: 26px;margin: 0 0 10px; font-weight: 500; color: #3d4461;">'.esc_html__('Payouts','workreap_core').'</h1>
								<span style="font-size:16px;line-height: 20px;display: block; color: #3d4461;">'.esc_html__('Payout history of the month','workreap_core').' '.date_i18n('F', mktime(0, 0, 0, $months, 10)).','.$years.'</span>
							</td>
						</tr>
					</table>
				</div>
				</header>
		
				<footer style="border-top: 1px solid #eee; text-align: center;margin-top: 80px;padding: 20px 0;">
					<span style="display: block;font-size: 16px;color: #3d4461;line-height: 20px;">
						'.esc_html__('This is a computer generated payout slip','workreap_core').'
					</span>
				</footer>';
			return $html;
		}
		
		/**
		 * Render Footer
		 *
		 * @throws error
		 * @author Amentotech <theamentotech@gmail.com>
		 * @return 
		 */
		public function renderFooter(){
			$html = '</body></html>';
			return $html;
		}
		
		/**
		 * Render table
		 *
		 * @throws error
		 * @author Amentotech <theamentotech@gmail.com>
		 * @return 
		 */
		public function renderPdfHtml($results){
			$payrols_list_data	= workreap_get_payouts_lists();
			$html = '';
			$html .= '<main>
			<table style="width: 95%; margin: 150px auto 0;font-family: sans-serif;">';
				$html .= '<thead>
					<tr style="text-align: left; border-radius:5px 0 0;">
						<th style="width:5%; padding: 15px 20px;background: #f5f5f5; font-size:14px;">'.esc_html__('ID','workreap_core').'</th>
						<th style="width:15%; padding: 15px 20px;background: #f5f5f5; font-size:14px;">'.esc_html__('Name','workreap_core').'</th>
						<th style="width:10%; padding: 15px 20px;background: #f5f5f5; font-size:14px;">'.esc_html__('Amount','workreap_core').'</th>
						<th style="width:10%; padding: 15px 20px;background: #f5f5f5; font-size:14px;word-wrap:break-word;">'.esc_html__('Method','workreap_core').'</th>
						<th style="width:30%; padding: 15px 20px;background: #f5f5f5; font-size:14px;word-wrap:break-word;">'.esc_html__('Details','workreap_core').'</th>
						<th style="width:15%; padding: 15px 20px;background: #f5f5f5; font-size:14px;">'.esc_html__('Status','workreap_core').'</th>
					</tr>
				</thead>
				<tbody>';
			
			$counter	= 0;
			if( !empty( $results ) ){
				foreach($results as $result ) { 
					$counter++;
					$user_name = '';
					$paymentdetails	 = '';
					if( function_exists('workreap_get_username') ){
						$user_name	= workreap_get_username($result['user_id']);
					}
					
					$amounts = '0.0';
					if( function_exists('workreap_price_format') ){
						$amounts	= esc_attr(html_entity_decode($result['currency_symbol']).$result['amount']);
					}

					$payrol_title 	= !empty( $payrols_list_data[$result['payment_method']]['title'] ) ? $payrols_list_data[$result['payment_method']]['title'] : '';

					if( !empty( $result['payment_method'] ) && $result['payment_method'] === 'paypal' ){
						$paymentdetails	.= $result['paypal_email'];
					} elseif( !empty( $result['payment_method'] ) && $result['payment_method'] === 'bacs' ){
						$payrols_list	= !empty( $payrols_list_data['bacs']['fields'] ) ? $payrols_list_data['bacs']['fields'] : array();
						$bank_detail	= !empty( $result['payment_details'] ) ? maybe_unserialize($result['payment_details']) : array();

						if( !empty( $payrols_list ) ){
							foreach( $payrols_list as $key => $pay ){
								if( !empty( $bank_detail[$key] ) ){
									$paymentdetails	.= '<span style="display: block;">'.$pay['placeholder'].': <em style="font-style: normal;">'.$bank_detail[$key].'</em></span>';
								}
							}
						}	
					}else{
						$payrols_list	= !empty( $payrols_list_data[$result['payment_method']]['fields'] ) ? $payrols_list_data[$result['payment_method']]['fields'] : array();
						$bank_detail	= !empty( $result['payment_details'] ) ? maybe_unserialize($result['payment_details']) : array();
						
						if( !empty( $payrols_list ) ){
							foreach( $payrols_list as $key => $pay ){
								if( !empty( $bank_detail[$key] ) ){
									$title	= !empty($pay['title']) ?  $pay['title'] : $pay['placeholder'];
									$paymentdetails	.= '<span style="display: block;">'.$title.': <em style="font-style: normal;">'.$bank_detail[$key].'</em></span>';
								}
							}
						}	
					}

					$html .= '<tr>
								<td style="padding: 15px 20px; border-top: 1px solid #e2e2e2; font-size:14px;">'.$counter.'</td>
								<td style="padding: 15px 20px;border-top: 1px solid #e2e2e2; font-size:14px;">'.$user_name.'</td>
								<td style="padding: 15px 20px;border-top: 1px solid #e2e2e2; font-size:14px;">'.$amounts.'</td>
								<td style="padding: 15px 20px;border-top: 1px solid #e2e2e2; font-size:14px;">'.$payrol_title.'</td>
								<td style="padding: 15px 20px;border-top: 1px solid #e2e2e2;word-wrap:break-word; font-size:14px;">'.$paymentdetails.'</td>
								<td style="padding: 15px 20px;border-top: 1px solid #e2e2e2; font-size:14px;">'.$result['status'].'</td>
							 </tr>';
				}
			}
			
			$html .= '</tbody></table></main>';

			return $html;
		}

		/**
		 * Handles data query and filter, sorting, and pagination.
		 *
		 * @throws error
		 * @author Amentotech <theamentotech@gmail.com>
		 * @return 
		 */
		public function prepare_items() {

			$this->_column_headers = $this->get_column_info();

			$per_page     = $this->get_items_per_page( 'payout_per_page', 20 );
			$current_page = $this->get_pagenum();
			$total_items  = self::record_count();

			if( !empty($_POST['months']) && !empty($_POST['years'])) {
				$this->download_pdf($_POST['months'],$_POST['years']);	
			}

			if( !empty( $_GET['action'] ) && !empty( $_GET['payout'] ) && $_GET['action'] === 'delete' ){
				//delete action
				self::delete_payouts($_GET['payout']);
			}

			if( !empty( $_GET['action'] ) && !empty( $_GET['payout'] ) && $_GET['action'] === 'change_status' ){
				//change status action
				self::change_status_payouts($_GET['payout'],$_GET['status']);
			}

			$this->set_pagination_args( [
				'total_items' => $total_items, 
				'per_page'    => $per_page 
			] );

			$this->items = self::get_payouts( $per_page, $current_page );
		}
	}
}