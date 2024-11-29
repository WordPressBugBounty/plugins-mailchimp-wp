<?php
//DEACTIVATION SURVEY
function fca_eoi_admin_deactivation_survey( $hook ) {
	if ( $hook === 'plugins.php' ) {
		
		ob_start(); ?>
		
		<div id="fca-deactivate" style="position: fixed; left: 232px; top: 191px; border: 1px solid #979797; background-color: white; z-index: 9999; padding: 12px; max-width: 669px;">
			<h3 style="font-size: 14px; border-bottom: 1px solid #979797; padding-bottom: 8px; margin-top: 0;">Sorry to see you go</h3>
			<p>Hi, this is David, the creator of Optin Cat. Thanks so much for giving my plugin a try. I’m sorry that you didn’t love it.</p>
			<p>I have a quick question that I hope you’ll answer to help us make Optin Cat better: what made you deactivate?</p>
			<p>You can leave me a message below. I’d really appreciate it.</p>
			<p><b>If you're upgrading to Optin Cat Premium and have questions or need help, click <a href='https://fatcatapps.com/article-categories/gen-getting-started/' target="_blank">here</a></b></p>

			<p><textarea style='width: 100%;' id='fca-eoi-deactivate-textarea' placeholder='What made you deactivate?'></textarea></p>
			
			<div style='float: right;' id='fca-deactivate-nav'>
				<button style='margin-right: 5px;' type='button' class='button button-secondary' id='fca-eoi-deactivate-skip'>Skip</button>
				<button type='button' class='button button-primary' id='fca-eoi-deactivate-send'>Send Feedback</button>
			</div>
		
		</div>
		
		<?php
			
		$html = ob_get_clean();
		
		$data = array(
			'html' => $html,
			'nonce' => wp_create_nonce( 'fca_eoi_uninstall_nonce' ),
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
		);
					
		wp_enqueue_script('fca_eoi_deactivation_js', FCA_EOI_PLUGIN_URL . '/includes/deactivation.min.js', false, FCA_EOI_VER, true );
		wp_localize_script( 'fca_eoi_deactivation_js', "fca_eoi", $data );
	}
	
	
}	
add_action( 'admin_enqueue_scripts', 'fca_eoi_admin_deactivation_survey' );

//UNINSTALL ENDPOINT
function fca_eoi_uninstall_ajax() {
	
	$msg = empty( $_REQUEST['msg'] ) ? '' : sanitize_text_field( wp_unslash( $_REQUEST['msg'] ) );
	$nonce = empty( $_REQUEST['nonce'] ) ? '' : sanitize_text_field( wp_unslash( $_REQUEST['nonce'] ) );
	$nonceVerified = wp_verify_nonce( $nonce, 'fca_eoi_uninstall_nonce') == 1;

	if ( $nonceVerified && !empty( $msg ) ) {
		
		$url =  "https://api.fatcatapps.com/api/feedback.php";
				
		$body = array(
			'product' => 'optincat',
			'msg' => $msg,		
		);
		
		$args = array(
			'timeout'     => 15,
			'redirection' => 15,
			'body' => wp_json_encode( $body ),	
			'blocking'    => true,
			'sslverify'   => false
		); 		
		
		$return = wp_remote_post( $url, $args );
		
		wp_send_json_success( $msg );

	}
	wp_send_json_error( $msg );

}
add_action( 'wp_ajax_fca_eoi_uninstall', 'fca_eoi_uninstall_ajax' );
