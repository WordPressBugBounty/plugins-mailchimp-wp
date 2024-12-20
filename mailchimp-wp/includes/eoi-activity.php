<?php

class EasyOptInsActivity {
	private static $instance;

	public $settings;

	private $text;
	private $table_name;
	private $form_stats;
	private $daily_stats;

	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function __construct() {
		global $wpdb;

		$this->table_name = $wpdb->prefix . "fca_eoi_activity";

		$this->text = array(
			'impressions' => array(
				'total' => 'Total Impressions',
				'form'  => 'Form Impressions'
			),
			'conversions' => array(
				'total' => 'Total Conversions',
				'form'  => 'Form Conversions'
			),
			'conversion_rate' => array(
				'total' => 'Conversion Rate',
				'form'  => 'Conversion Rate',
			),
			'period' => 'Last %d days',
			'all_time' => 'All time'
		);
		
		if ( !defined ( 'FCA_EOI_DISABLE_STATS_TRACKING' )) {
			add_action( 'wp_ajax_fca_eoi_activity', array( $this, 'track_activity' ) );
			add_action( 'wp_ajax_nopriv_fca_eoi_activity', array( $this, 'track_activity' ) );
		}	
	}

	public function get_text( $name, $category = null, $parameters = array() ) {
		$plain_text = $this->text[ $name ];
		$text = $category ? $plain_text[ $category ] : $plain_text;

		if ( ! empty( $parameters ) ) {
			$text = call_user_func_array( 'sprintf', array_merge( array( $text ), $parameters ) );
		}

		if ( $name == 'period' && empty( $parameters[0] ) ) {
			$text = $this->text['all_time'];
		}

		return $text;
	}

	public function setup() {
		$sql = "CREATE TABLE $this->table_name (
			form_id INT(11) NOT NULL,
			type ENUM('impression', 'conversion') NOT NULL,
			timestamp TIMESTAMP NOT NULL,
			day DATE NOT NULL,
			KEY form_id (form_id),
			KEY timestamp (timestamp),
			KEY day (day)
		);";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}

	public function track_activity() {
		
		$nonce = empty( $_REQUEST['nonce'] ) ? '' : sanitize_text_field( wp_unslash( $_REQUEST['nonce'] ) );
		$form_id = empty( $_REQUEST['form_id'] ) ? '' : intval( $_REQUEST['form_id'] );

		$nonceVerified = ( wp_verify_nonce( $nonce, 'fca_eoi_activity') OR wp_verify_nonce( $nonce, 'fca_eoi_submit_form') );
		$idVerified = is_int( $form_id ) && $form_id > 0;
		
		if ( $nonceVerified && $idVerified ) {

			require_once FCA_EOI_PLUGIN_DIR . 'includes/classes/RobotDetector/RobotDetector.php';
			$robot_detector = new RobotDetector();
			
			if ( get_post( $form_id ) && !is_user_logged_in() && !$robot_detector->is_robot() ) {
				$this->add_impression( $form_id );
				wp_send_json_success( $form_id );
			}
		}	
		wp_send_json_error();
		
	}

	public function format_column_text( $column_name, $value ) {
		if ( $column_name == 'conversion_rate' ) {
			return number_format( $value * 100, 2 ) . '%';
		} else {
			return $value;
		}
	}

	public function get_daily_stats( $day_interval ) {
		if ( empty( $this->daily_stats ) ) {
			global $wpdb;

			$days  = array();
			$stats = array(
				'impressions' => array(),
				'conversions' => array(),
				'totals' => array(
					'impressions' => 0,
					'conversions' => 0,
					'conversion_rate' => 0
				)
			);

			$now = time();
			for ( $i = $day_interval - 1; $i >= 0; $i -- ) {
				$time   = $now - ( 86400 * $i );
				$day    = gmdate( 'Y-m-d', $time );
				$days[] = $day;

				$stats['impressions'][ $day ] = 0;
				$stats['conversions'][ $day ] = 0;
			}

			foreach ( array( 'impression', 'conversion' ) as $activity_type ) {
				$query = $this->get_daily_stats_query( $activity_type, $day_interval );
				foreach ( $wpdb->get_results( $query ) as $result ) {
					$activity_type_plural = $activity_type . 's';

					if ( ! array_key_exists( $result->day, $stats[ $activity_type_plural ] ) ) {
						continue;
					}

					$total = (int) $result->total;

					$stats[ $activity_type_plural ][ $result->day ] = $total;
					$stats['totals'][ $activity_type_plural ] += $total;
				}
			}

			$stats['totals']['conversion_rate'] = $this->calculate_conversion_rate(
				$stats['totals']['impressions'],
				$stats['totals']['conversions']
			);

			$this->daily_stats = $stats;
		}

		return $this->daily_stats;
	}

	public function get_form_stats( $day_interval ) {
		if ( empty( $this->form_stats ) ) {
			global $wpdb;

			$stats = array();
			$ids = array();

			foreach ( array( 'impression', 'conversion' ) as $activity_type ) {
				$stat = array();
				$query = $this->get_form_stats_query( $activity_type, $day_interval );
				foreach ( $wpdb->get_results( $query ) as $result ) {
					$ids[ $result->form_id ] = true;
					$stat[ $result->form_id ] = (int) $result->total;
				}
				$stats[ $activity_type . 's' ] = $stat;
			}

			foreach ( array_keys( $ids ) as $form_id ) {
				$stats['conversion_rate'][ $form_id ] = $this->calculate_conversion_rate(
					floatval( empty( $stats['impressions'][ $form_id ] ) ? 0 : $stats['impressions'][ $form_id ] ),
					floatval( empty( $stats['conversions'][ $form_id ] ) ? 0 : $stats['conversions'][ $form_id ] )
				);
			}

			$this->form_stats = $stats;
		}

		return $this->form_stats;
	}

	private function calculate_conversion_rate( $impressions, $conversions ) {
		if ( $impressions < 1 ) {
			return 0.0;
		} else {
			return $conversions / $impressions;
		}
	}

	public function add_impression( $form_id ) {
		$this->add_activity( $form_id, 'impression' );
	}

	public function add_conversion( $form_id ) {
		$this->add_activity( $form_id, 'conversion' );
	}

	public function reset_stats( $form_id ) {
		global $wpdb;

		$wpdb->delete( $this->table_name, array( 'form_id' => $form_id ), '%d' );
	}

	private function add_activity( $form_id, $activity_type ) {
	
		if ( !defined ( 'FCA_EOI_DISABLE_STATS_TRACKING' )) {
		
			global $wpdb;

			$time = current_time( 'mysql', 1 );
			$wpdb->insert( $this->table_name, array(
				'form_id'   => $form_id,
				'type'      => $activity_type,
				'timestamp' => $time,
				'day'       => $time
			), array( '%d', '%s', '%s', '%s' ) );
		
		}
	}

	private function get_form_stats_query( $activity_type, $day_interval ) {
		return $this->get_stats_query( 'form_id', $activity_type, $day_interval );
	}

	private function get_daily_stats_query( $activity_type, $day_interval ) {
		return $this->get_stats_query( 'day', $activity_type, $day_interval );
	}

	private function get_stats_query( $field, $activity_type, $day_interval ) {
		global $wpdb;
		
		if( $day_interval ) {
			return $wpdb->prepare(
			"SELECT %i, COUNT(*) AS `total` " .
			"FROM %i " .
			"WHERE `type` = %s " .
				"AND `timestamp` >= DATE_SUB(NOW(), INTERVAL %d DAY) " .
			"GROUP BY %i", $field, $this->table_name, $activity_type, $day_interval, $field );
		}
		
		return $wpdb->prepare(
			"SELECT %i, COUNT(*) AS `total` " .
			"FROM %i " .
			"WHERE `type` = %s " .
			"GROUP BY %i", $field, $this->table_name, $activity_type, $field );
	}
}
