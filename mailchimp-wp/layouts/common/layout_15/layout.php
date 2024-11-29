<?php
function fca_eoi_layout_descriptor_15( $layout_id, $texts ) {
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
            'form' => array(
                '.fca_eoi_layout_15.' . $class => array(
                    'background-color' => array( 'Form Background', '#99d7d9' ),
                    'border-color' => array( 'Border Color', '#d4d8d8' ),
                    'width' => array( 'Width', $width ),
                    'text-align' => array( 'Alignment', 'center' ),
                ),
            ),
            'headline' => array(
                '.fca_eoi_layout_15.' . $class . ' div.fca_eoi_layout_headline_copy_wrapper div' => array(
                    'font-size' => array( 'Font Size', '24px' ),
                    'color' => array( 'Font Color', '#3b3b3b' ),
                ),
            ),
            'description' => array(
                '.fca_eoi_layout_15.' . $class . ' div.fca_eoi_layout_description_copy_wrapper p, ' .
                '.fca_eoi_layout_15.' . $class . ' div.fca_eoi_layout_description_copy_wrapper div' => array(
                    'font-size' => array( 'Font Size', '16px' ),
                    'color' => array( 'Font Color', '#3b3b3b' ),
                ),
            ),
            'name_field' => array(
                '.fca_eoi_layout_15.' . $class . ' div.fca_eoi_layout_name_field_wrapper, ' .
                '.fca_eoi_layout_15.' . $class . ' div.fca_eoi_layout_name_field_wrapper input' => array(
                    'font-size' => array( 'Font Size', '16px' ),
                    'color' => array( 'Font Color', '#595252' ),
                    'background-color' => array( 'Background Color', '#ffffff' ),
                ),
                '.fca_eoi_layout_15.' . $class . ' div.fca_eoi_layout_name_field_wrapper' => array(
                    'border-color' => array( 'Border Color', '#bec2c2' ),
                    'width' => array( 'Width', '100%' ),
                ),
            ),
            'email_field' => array(
                '.fca_eoi_layout_15.' . $class . ' div.fca_eoi_layout_email_field_wrapper, ' .
                '.fca_eoi_layout_15.' . $class . ' div.fca_eoi_layout_email_field_wrapper input' => array(
                    'font-size' => array( 'Font Size', '16px' ),
                    'color' => array( 'Font Color', '#595252' ),
                    'background-color' => array( 'Background Color', '#ffffff' ),
                ),
                '.fca_eoi_layout_15.' . $class . ' div.fca_eoi_layout_email_field_wrapper' => array(
                    'border-color' => array( 'Border Color', '#bec2c2' ),
                    'width' => array( 'Width', '100%' ),
                ),
            ),
            'button' => array(
                '.fca_eoi_layout_15.' . $class . ' div.fca_eoi_layout_submit_button_wrapper input' => array(
                    'font-size' => array( 'Font Size', '16px' ),
                    'color' => array( 'Font Color', '#FFF' ),
                    'background-color' => array( 'Button Color', '#de7f0b' ),
                    'border-bottom-color' => array( 'Border Color', '#b96701' ),
                    'hover-color' => array( 'Button Hover Color', '#b96701' ),
                ),
                '.fca_eoi_layout_15.' . $class . ' div.fca_eoi_layout_submit_button_wrapper' => array(
                    'width' => array( 'Width', '100%' ),
                ),
            ),
            'privacy' => array(
                '.fca_eoi_layout_15.' . $class . ' div.fca_eoi_layout_privacy_copy_wrapper' => array(
                    'font-size' => array( 'Font Size', '14px' ),
                    'color' => array( 'Font Color', '#949494' ),
                ),
            ),
            'fatcatapps' => array(
                '.fca_eoi_layout_15.' . $class . ' div.fca_eoi_layout_fatcatapps_link_wrapper a, ' .
                '.fca_eoi_layout_15.' . $class . ' div.fca_eoi_layout_fatcatapps_link_wrapper a:hover' => array(
                    'color' => array( 'Font Color', '#949494' ),
                ),
            ),
        ),
    );
}
?>
