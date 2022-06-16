<?php
/**
 *
 * The template part for Service Cancel reason
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
?>
<div class="wt-uploadimages modal fade wt-uploadrating wt-canceledinfo" id="wt-servicemodalbox" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="wt-modaldialog modal-dialog" role="document">
			<div class="wt-modalcontent modal-content">
				<div class="wt-boxtitle">
					<h2><?php esc_html_e('Rejection Reason','workreap');?> <i class=" wt-btncancel fa fa-times" data-dismiss="modal" aria-label="Close"></i></h2>
				</div>
				<div class="wt-modalbody modal-body">
					<div class="wt-description">
						<p id="wt-service-reason-text"></p>
					</div>
					<div class="wt-btnarea">
						<a class="wt-btn" href="#" onclick="event_preventDefault(event);" data-dismiss="modal" aria-label="Close"><?php esc_html_e('Ok','workreap');?></a>
					</div>
				</div>
			</div>
		</div>
	</div>