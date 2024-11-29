<?php

class EasyOptInsLayout {
	public $layout_number;
	public $layout_type;
	public $layout_class;
	public $layout_id;

	private $plugin_dir;
	private $plugin_url;

	public static function uses_new_css() {
		return true;
	}

	public function __construct( $layout_id ) {
		$this->plugin_dir = FCA_EOI_PLUGIN_DIR;
		$this->plugin_url = FCA_EOI_PLUGIN_URL . '/';

		list( $layout_type, $layout_number ) = explode( '_', $layout_id );

		$this->layout_number = (int) $layout_number;
		$this->layout_type   = $layout_type == 'layout' ? 'widget' : $layout_type;
		$this->layout_class  = self::generate_layout_class( $this->layout_type );
		$this->layout_id     = $layout_id;
	}

	public function layout_name( $id = '') {
		
		if ( empty ( $id ) ) {
			$id = $this->layout_id;
		}
		
		$layout_names = array(
			0 => 'No CSS',
			1 => 'Classic',
			2 => 'Ribbon',
			3 => 'Chevron',
			4 => 'Modern',
			5 => 'Light',
			6 => 'Dark',
			7 => 'Natural',
			8 => 'Elegant',
			9 => 'Bubble',
			10 => 'Optin Bar Blue',
			11 => 'Optin Bar Orange',
			12 => 'Optin Bar White',
			13 => 'Slide In Light',
			14 => 'Slide In Dark',
			15 => 'Rounded',
			16 => 'Flat',
			17 => 'Content Upgrade',
			18 => 'Image',
			19 => 'Padded Image',
			20 => 'Wide Image',
			21 => 'Content Upgrade - Image',
			22 => 'Content Upgrade - Wide Image',
		);
		
		return $layout_names[ $this->layout_number( $id ) ];
	}
	
	public function layout_number( $id = '') {
		
		if ( empty ( $id ) ) {
			$id = $this->layout_id;
		}
		
		return preg_replace( '/[^0-9]/', '', $id );
		
	}
	
	public function screenshot_src( $id = '') {
		
		if ( empty ( $id ) ) {
			$id = $this->layout_id;
		}
		
		if ( $this->layout_type === 'widget' ) {
			$id = str_replace( 'widget', 'layout', $id );
		}
		
		if ( file_exists( FCA_EOI_PLUGIN_DIR . "/layouts/screenshots/$id.png" ) ) {
			return FCA_EOI_PLUGIN_URL . "/layouts/screenshots/$id.png";
		}

		return FCA_EOI_PLUGIN_URL . "/assets/admin/no_image.png";
	}	
	
	public function layout_enabled() {
		
		$path = FCA_EOI_PLUGIN_DIR . '/layouts/' . $this->layout_type . '/' . $this->layout_id;
		
		if ( $this->layout_type === 'widget' ) {
			$path = FCA_EOI_PLUGIN_DIR . '/layouts/' . $this->layout_type . '/layout_' . $this->layout_number;
		}
		
		return file_exists( $path );
		
	}
	
	public function layout_order( $id = '') {
		
		if ( empty ( $id ) ) {
			$id = $this->layout_id;
		}
		
		//LAYOUT ID => LAYOUT ORDER
		$layout_order = array(
			16, // 'Flat',
			15, // 'Rounded',
			2, // 'Ribbon',
			5, // 'Light',
			1, // 'Classic',
			9, // 'Bubble',
			18, // 'Image',
			19, // 'Padded Image',
			20, // 'Wide Image',
			17, // 'Content Upgrade',
			21, // 'Content Upgrade - Image',
			22, // 'Content Upgrade - Wide Image',
			3, // 'Chevron',
			4, // 'Modern',
			6, // 'Dark',
			7, // 'Natural',
			8, // 'Elegant',
			10, // 'Optin Bar Blue',
			11, // 'Optin Bar Orange',
			12, // 'Optin Bar White',
			13, // 'Slide In Light',
			14, // 'Slide In Dark',
			0, //'No CSS',
		);
		
		return array_search ( $this->layout_number( $id ), $layout_order );
		
	}
	
	public function path_to_html_wrapper() {
		return $this->plugin_dir . $this->common_path() . $this->layout_type . '.html';
	}

	public function path_to_resource( $resource_name, $resource_type ) {
		return $this->plugin_dir . $this->subpath_to_resource( $resource_name, $resource_type );
	}

	public function url_to_resource( $resource_name, $resource_type ) {
		return $this->plugin_url . $this->subpath_to_resource( $resource_name, $resource_type );
	}

	private static function generate_layout_class( $layout_type ) {
		if ( $layout_type == 'lightbox' ) {
			return 'fca_eoi_layout_popup';
		} elseif ( $layout_type == 'postbox' ) {
			return 'fca_eoi_layout_postbox';
		} elseif ( $layout_type == 'widget' ) {
			return 'fca_eoi_layout_widget';
		} elseif ( $layout_type == 'banner' ) {
			return 'fca_eoi_layout_banner';
		} elseif ( $layout_type == 'overlay' ) {
			return 'fca_eoi_layout_overlay';
		}
		return '';
	}

	private function subpath_to_resource( $resource_name, $resource_type ) {
		if ( self::uses_new_css() ) {
			if ( $resource_name == 'layout' && $resource_type == 'html' ) {
				$new_path =
					$this->common_path() .
					'layout_' . $this->layout_number . '/' .
					$resource_name . '.' . $resource_type;

				if ( file_exists( $this->plugin_dir . $new_path ) ) {
					return $new_path;
				}
			}

			$new_path = $this->subpath() . $resource_name . '-new.' . $resource_type;
			if ( file_exists( $this->plugin_dir . $new_path ) ) {
				return $new_path;
			}
		}

		$path = $this->subpath();

		if ( $resource_type == 'scss' ) {
			$resource_type = 'css';
		}

		return $path . $resource_name . '.' . $resource_type;
	}

	private function subpath() {
		return 'layouts/' . $this->layout_type . '/' . $this->layout_id . '/';
	}

	private function common_path() {
		return 'layouts/common/';
	}
}
