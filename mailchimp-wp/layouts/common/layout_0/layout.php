<?php

function fca_eoi_layout_descriptor_0( $layout_id, $texts ) {
	require_once FCA_EOI_PLUGIN_DIR . 'includes/eoi-layout.php';
	$layout_helper = new EasyOptInsLayout( $layout_id );
	$class = $layout_helper->layout_class;

	switch( $layout_helper->layout_type ) {
		
		case 'widget':
			$width = '300px';
			break;
		
		case 'postbox':
			$width = '580px';
			break;
		
		case 'lightbox':
			$width = '650px';
			break;
		
		default:
			$width = '100%';
	}

	return array(

		'editables' => array(

			// Added to the fieldset "Form Background"
			'form' => array(
				'.fca_eoi_layout_0.' . $class => array(
					'background-color' => array( 'Form Background Color', '#FFF' ),
					'border-color' => array( 'Border Color', '#000' ),
					'width' => array( 'Width', $width ),
					'text-align' => array( 'Alignment', 'center' ),
				),
			),

			// Added to the fieldset "Headline"
			'headline' => array(
				'.fca_eoi_layout_0.' . $class . ' div.fca_eoi_layout_headline_copy_wrapper div' => array(
					'font-size' => array( 'Font Size', '25px'),
					'color' => array( 'Font Color', '#000'),
				),
			),
			'description' => array(
				'.fca_eoi_layout_0.' . $class . ' div.fca_eoi_layout_description_copy_wrapper p, ' .
				'.fca_eoi_layout_0.' . $class . ' div.fca_eoi_layout_description_copy_wrapper div' => array(
					'font-size' => array( 'Font Size', '14px'),
					'color' => array( 'Font Color', '#000'),
				),
			),
			'name_field' => array(
				'.fca_eoi_layout_0.' . $class . ' div.fca_eoi_layout_name_field_wrapper, ' .
				'.fca_eoi_layout_0.' . $class . ' div.fca_eoi_layout_name_field_wrapper input' => array(
					'color' => array( 'Font Color', '#000' ),
					'font-size' => array( 'Font Size', '14px'),
					
				),
				'.fca_eoi_layout_0.' . $class . ' div.fca_eoi_layout_name_field_wrapper' => array(
					'border-color' => array( 'Border Color', '#000'),
					'width' => array( 'Width', '100%'),
				),
			),
			'email_field' => array(
				'.fca_eoi_layout_0.' . $class . ' div.fca_eoi_layout_email_field_wrapper, ' .
				'.fca_eoi_layout_0.' . $class . ' div.fca_eoi_layout_email_field_wrapper input' => array(
					'color' => array( 'Font Color', '#000' ),
					'font-size' => array( 'Font Size', '14px'),
					
				),
				'.fca_eoi_layout_0.' . $class . ' div.fca_eoi_layout_email_field_wrapper' => array(
					'border-color' => array( 'Border Color', '#000'),
					'width' => array( 'Width', '100%'),
				),
			),
			'button' => array(
				'.fca_eoi_layout_0.' . $class . ' div.fca_eoi_layout_submit_button_wrapper input' => array(
					'font-size' => array( 'Font Size', '14px'),
					'color' => array( 'Font Color', '#FFF' ),
					'background-color' => array( 'Button Background Color', '#000' ),
					'hover-color' => array( 'Button Hover Color', '#933B00' ),
					'width' => array( 'Width', '100%'),
				),
			),
			'privacy' => array(
				'.fca_eoi_layout_0.' . $class . ' div.fca_eoi_layout_privacy_copy_wrapper div' => array(
					'font-size' => array( 'Font Size', '14px'),
					'color' => array( 'Font Color', '#FFF'),
				),
			),
			'fatcatapps' => array(
				'.fca_eoi_layout_0.' . $class . ' div.fca_eoi_layout_fatcatapps_link_wrapper a, ' .
				'.fca_eoi_layout_0.' . $class . ' div.fca_eoi_layout_fatcatapps_link_wrapper a:hover' => array(
					'color' => array( 'Font Color', '#3197e1'),
				),
			),
		)
	);
}