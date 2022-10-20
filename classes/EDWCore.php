<?php
declare( strict_types = 1 );

class EDWCore {
	public static $positions = [];
	
	public $positionsClass = [
		'disabled'                                  => 'none',
		'woocommerce_after_add_to_cart_button'      => 'form.cart|inside',
		'woocommerce_before_add_to_cart_button'     => 'form.cart|before',
		'woocommerce_product_meta_end'              => 'div.product_meta|after',
		'woocommerce_before_single_product_summary' => 'div.woocommerce-notices-wrapper|after',
		'woocommerce_after_single_product_summary'  => 'div.woocommerce-tabs|after',
		'woocommerce_product_thumbnails'            => 'div.woocommerce-product-gallery|inside',
	];
	
	public function __construct() {
		add_action( 'admin_menu',     [ $this, 'registerOptionsMenuInWooCommerce' ] );
		add_action( 'plugins_loaded', [ $this, 'loadTextdomain' ] );
		
		add_action( 'wp_enqueue_scripts', [ $this, 'loadStyle' ] );
		add_action( 'save_post_product',  [ $this, 'saveProduct' ], 10, 3 ); // TODO use woocommerce_update_product instead
		add_action( 'add_meta_boxes',     [ $this, 'registerMetaboxInProductEdit' ] );
		add_action( 'init',               [ $this, 'addShortcode' ] );
		
		// Dokan Compatibility
		add_action( 'dokan_new_product_form',                [ $this, 'showDokanForm' ] );
		add_action( 'dokan_new_product_after_product_tags',  [ $this, 'showDokanForm' ] );
		add_action( 'dokan_product_edit_after_product_tags', [ $this, 'showDokanForm' ] );
		
		// WCMP Compatiblity
		add_filter( 'wcmp_product_data_tabs',      [ $this, 'registerFormInWcMpTabs' ] );
		add_action( 'wcmp_product_tabs_content',   [ $this, 'showWcMpForm' ], 10, 3 );
		add_action( 'wcmp_process_product_object', [ $this, 'saveProductData' ], 10, 2 );
		
		if ( EDW_USE_JS === '0' ) {
			add_action( EDW_POSITION_SHOW, [ $this, 'showEstimationMessage' ] );
		} else {
			add_action( 'wp_footer', [ $this, 'showJs' ], 99 );
		}
	}
	
	// Starting by the callable functions set up in __construct, in order, then the ones they call
	
	public function registerOptionsMenuInWooCommerce() {
		add_submenu_page( 'woocommerce',
			__( 'Estimated Delivery', 'estimated-delivery-for-woocommerce' ),
			__( 'Estimated Delivery', 'estimated-delivery-for-woocommerce' ),
			'manage_options', 'edw-options',
			[ $this, 'showOptionsPage' ] );
	}
	
	public function loadTextdomain() {
		load_plugin_textdomain( 'estimated-delivery-for-woocommerce', false, basename( __DIR__ ) . '/languages' );
	}
	
	public function loadStyle() {
		if ( ! is_product() ) {
			return;
		}
		
		wp_enqueue_script( 'edw-scripts', plugins_url( 'assets/edw_scripts.js?edw=true&v=' . EDW_VERSION, __FILE__ ), [ 'jquery' ] );
		
		wp_localize_script( 'edw-scripts', 'edwConfig', [ 'url' => admin_url( 'admin-ajax.php' ) ] );
	}
	
	public function saveProduct( $post_id, $post, $update ) {
		if ( isset( $_POST['_edw_max_days'] ) ) {
			if ( isset( $_POST['_edw_disabled_days'] ) && is_array( $_POST['_edw_disabled_days'] ) ) {
				// Sanitize disabled days
				$disabledDays = array_map( 'sanitize_text_field', $_POST['_edw_disabled_days'] );
				update_post_meta( $post_id, '_edw_disabled_days', $disabledDays );
			} else {
				update_post_meta( $post_id, '_edw_disabled_days', [] );
			}
			
			update_post_meta( $post_id, '_edw_max_days',            sanitize_text_field( $_POST['_edw_max_days'] ) );
			update_post_meta( $post_id, '_edw_days',                sanitize_text_field( $_POST['_edw_days'] ) );
			update_post_meta( $post_id, '_edw_days_outstock',       sanitize_text_field( $_POST['_edw_days_outstock'] ) );
			update_post_meta( $post_id, '_edw_max_days_outstock',   sanitize_text_field( $_POST['_edw_max_days_outstock'] ) );
			update_post_meta( $post_id, '_edw_mode',                sanitize_text_field( $_POST['_edw_mode'] ) );
			update_post_meta( $post_id, '_edw_mode_custom',         sanitize_text_field( $_POST['_edw_mode_custom'] ) );
			update_post_meta( $post_id, '_edw_days_backorders',     sanitize_text_field( $_POST['_edw_days_backorders'] ) );
			update_post_meta( $post_id, '_edw_max_days_backorders', sanitize_text_field( $_POST['_edw_max_days_backorders'] ) );
			
			if ( isset( $_POST['_edw_overwrite'] ) ) {
				update_post_meta( $post_id, '_edw_overwrite', '1' );
			} else {
				update_post_meta( $post_id, '_edw_overwrite', '0' );
			}
		}
	}
	
	public function registerMetaboxInProductEdit() {
		add_meta_box( 'edw_data_product',
			__( 'Estimated Delivery', 'estimated-delivery-for-woocommerce' ),
			[ $this, 'showMetaboxForm' ],
			'product', 'normal', 'high'
		);
	}
	
	public function addShortcode() {
		add_shortcode( 'estimate_delivery', [ $this, 'prepareShortcode' ] );
	}
	
	public function showDokanForm() {
		$dokanMpMetabox = new DokanMP_Metabox;
		$dokanMpMetabox->displayForm();
	}
	
	public function registerFormInWcMpTabs( $tabs ) {
		$tabs['edw_estimate_delivery'] = [
			'label'    => __( 'Estimated Delivery', 'estimated-delivery-for-woocommerce' ),
			'target'   => 'edw_estimate_delivery',
			'class'    => [],
			'priority' => 100,
		];
		
		return $tabs;
	}
	
	public function showWcMpForm( $pro_class_obj, $product, $post ) {
		$GLOBALS["product"] = $product;
		
		$wcMpMetabox = new WCMP_Metabox;
		$wcMpMetabox->displayForm();
	}
	
	public function saveProductData( $product, $post_data ) {
		if ( isset( $_POST['_edw_max_days'] ) ) {
			if ( isset( $_POST['_edw_disabled_days'] ) && is_array( $_POST['_edw_disabled_days'] ) ) {
				// Sanitize disabled days
				$disabledDays = array_map( 'sanitize_text_field', $_POST['_edw_disabled_days'] );
				update_post_meta( $post_data['post_ID'], '_edw_disabled_days', $disabledDays );
			} else {
				update_post_meta( $post_data['post_ID'], '_edw_disabled_days', [] );
			}
			
			update_post_meta( $post_data['post_ID'], '_edw_max_days',          sanitize_text_field( $_POST['_edw_max_days'] ) );
			update_post_meta( $post_data['post_ID'], '_edw_days',              sanitize_text_field( $_POST['_edw_days'] ) );
			update_post_meta( $post_data['post_ID'], '_edw_days_outstock',     sanitize_text_field( $_POST['_edw_days_outstock'] ) );
			update_post_meta( $post_data['post_ID'], '_edw_max_days_outstock', sanitize_text_field( $_POST['_edw_max_days_outstock'] ) );
			update_post_meta( $post_data['post_ID'], '_edw_mode',              sanitize_text_field( $_POST['_edw_mode'] ) );
			
			if ( isset( $_POST['_edw_overwrite'] ) ) {
				update_post_meta( $post_data['post_ID'], '_edw_overwrite', '1' );
			} else {
				update_post_meta( $post_data['post_ID'], '_edw_overwrite', '0' );
			}
		}
	}
	
	public function showJs() {
		if ( is_product() === true ) {
			global $post;
			
			$selectPosition = explode( '|', $this->positionsClass[ EDW_POSITION_SHOW ] );
			echo '<script>jQuery(document).ready(function($) { EDW.show('
			     . $post->ID . ',\'' . json_encode( $selectPosition )
			     . '\'); });</script>';
		}
	}
	
	public function prepareShortcode( $atts ) {
		global $product;
		
		$atts = shortcode_atts( [ 'product' => $product ], $atts, 'estimate_delivery' );
		
		return $this->showEstimationMessage( $product );
	}
	
	public function showMetaboxForm() {
		$metabox = new Metabox;
		$metabox->displayForm();
	}
	
	public function showOptionsPage() {
		self::$positions = [
			'disabled'                                  => __( 'Disabled, use shortcode',          'estimated-delivery-for-woocommerce' ),
			'woocommerce_after_add_to_cart_button'      => __( 'After cart button',                'estimated-delivery-for-woocommerce' ),
			'woocommerce_before_add_to_cart_button'     => __( 'Before cart button',               'estimated-delivery-for-woocommerce' ),
			'woocommerce_product_meta_end'              => __( 'After product meta',               'estimated-delivery-for-woocommerce' ),
			'woocommerce_before_single_product_summary' => __( 'Before product summary',           'estimated-delivery-for-woocommerce' ),
			'woocommerce_after_single_product_summary'  => __( 'After product summary',            'estimated-delivery-for-woocommerce' ),
			'woocommerce_product_thumbnails'            => __( 'Product Thumbnail (may not work)', 'estimated-delivery-for-woocommerce' ),
		];
		
		self::$positions = apply_filters( 'edw_positions', self::$positions );
		
		require_once( EDW_PATH . 'views/options.php' );
	}
	
	public function showEstimationMessage( $productParam = false ) : string {
		global $product;
		
		$returnResult    = false;
		$productOverride = '0';
		
		if ( $productParam ) {
			$product      = wc_get_product( $productParam );
			$returnResult = true;
		}
		
		if ( $product === null ) {
			return '';
		}
		
		if ( isset( $_POST['type'] ) && $_POST['type'] === 'variation' ) {
			$product_id = $product->get_parent_id();
		} else {
			if ( $product ) {
				$product_id = $product->get_id();
			} else {
				$product_id = false;
			}
		}
		
		if ( $product_id ) {
			$productOverride = get_post_meta( $product_id, '_edw_overwrite', true );
		}
		
		if ( $productOverride === '1' ) {
			$mode = get_post_meta( $product_id, '_edw_mode', true );
		} else {
			$mode = get_option( '_edw_mode' );
		}
		
		if ( ! $mode ) {
			return '';
		}
		
		/**
		 * Hide out stock products
		 *
		 * @since 1.0.3
		 */
		
		if ( $product_id && ! $product->is_in_stock() ) { // OUT OF STOCK
			if ( $productOverride === '1' ) {
				$days         = (int) get_post_meta( $product_id, '_edw_days_outstock',     true );
				$maxDays      = (int) get_post_meta( $product_id, '_edw_max_days_outstock', true );
				$disabledDays =       get_post_meta( $product_id, '_edw_disabled_days',     true );
			} else {
				$maxDays      = (int) get_option( '_edw_max_days_outstock' );
				$days         = (int) get_option( '_edw_days_outstock' );
				$disabledDays =       get_option( '_edw_disabled_days' );
			}
			
			// If days set is 0, return empty. Don't show any message
			if ( $days === 0 ) {
				return '<div class="edw_date" style="display:none"></div>';
			}
		} elseif ( $product_id && $product->is_on_backorder() ) { // ON BACKORDER
			if ( $productOverride === '1' ) {
				$days         = (int) get_post_meta( $product_id, '_edw_days_backorders',     true );
				$maxDays      = (int) get_post_meta( $product_id, '_edw_max_days_backorders', true );
				$disabledDays =       get_post_meta( $product_id, '_edw_disabled_days',       true );
			} else {
				$maxDays      = (int) get_option( '_edw_max_days_backorders' );
				$days         = (int) get_option( '_edw_days_backorders' );
				$disabledDays =       get_option( '_edw_disabled_days' );
			}
		} elseif ( $product_id && $productOverride === '1' ) { // AVAILABLE, check Product configuration
			$days         = (int) get_post_meta( $product_id, '_edw_days',          true );
			$maxDays      = (int) get_post_meta( $product_id, '_edw_max_days',      true );
			$disabledDays =       get_post_meta( $product_id, '_edw_disabled_days', true );
		} else {
			$days         = (int) get_option( '_edw_days' );
			$maxDays      = (int) get_option( '_edw_max_days' );
			$disabledDays =       get_option( '_edw_disabled_days' );
		}
		
		if ( $days === 0 && get_option( '_edw_same_day', '0' ) === '0' ) {
			return '';
		}
		
		$minDate = $this->getDateFromNow( $disabledDays, $days );
		$maxDate = $this->getDateFromNow( $disabledDays, $maxDays );
		
		if ( $minDate && $maxDate ) {
			$wpDateFormat      = get_option( 'date_format' );
			$useRelativeDates  = get_option( '_edw_relative_dates', false );
			$elon              = __( ' on', 'estimated-delivery-for-woocommerce' );
			$date              = date_i18n( (string) ( $wpDateFormat ), strtotime( $minDate ) );
			
			// Date format reference: https://www.php.net/manual/en/datetime.format.php
			
			if ( $maxDays > 0 ) {
				list( $d, $m, $y ) = $this->getDifferencesBetweenDates( $minDate, $maxDate );
				
				if ( ! $d && ! $m && ! $y ) {
					$thisWeek = date( 'W' ); // ISO 8601 week number of year
					
					if ( $useRelativeDates && $thisWeek === date( 'W', strtotime( $minDate ) ) ) {
						$elon = '';
						$date = sprintf(
							__( "this %s, %s", "estimated-delivery-for-woocommerce" ),
							date_i18n( 'l',   strtotime( $minDate ) ), // A full textual representation of the day of the week
							date_i18n( 'j F', strtotime( $minDate ) )  // Day of the month without leading zeros + full textual month
						);
					} elseif ( $useRelativeDates && ( $thisWeek + 1 ) == date( 'W', strtotime( $minDate ) ) ) {
						$elon = '';
						$date = sprintf(
							__( "the next %s, %s", "estimated-delivery-for-woocommerce" ),
							date_i18n( 'l',   strtotime( $minDate ) ),
							date_i18n( 'j F', strtotime( $minDate ) )
						);
					}
				} elseif ( $d && ! $m && ! $y ) {
					//00 - 00 MM, YYYY
					$date = date_i18n( 'j ', strtotime( $minDate ) ) . ' - ' . date_i18n( "j F, Y", strtotime( $maxDate ) );
				} elseif ( $d && $m && ! $y ) {
					// 00 MM - 00 MM, YYYY
					$date = date_i18n( 'j F', strtotime( $minDate ) ) . ' - ' . date_i18n( "j F, Y", strtotime( $maxDate ) );
				} elseif ( $d && $m && $y ) {
					// 00 MM YYYY - 00 MM YYYY
					$date = date_i18n( 'j F Y', strtotime( $minDate ) ) . ' - ' . date_i18n( "j F Y", strtotime( $maxDate ) );
				}
			} else {
				$thisWeek = date( 'W' );
				
				if ( $useRelativeDates && $thisWeek === date( 'W', strtotime( $minDate ) ) ) {
					$elon = '';
					$date = sprintf(
						__( "this %s, %s", "estimated-delivery-for-woocommerce" ),
						date_i18n( 'l',   strtotime( $minDate ) ),
						date_i18n( 'j F', strtotime( $minDate ) )
					);
				} elseif ( $useRelativeDates && ( $thisWeek + 1 ) == date( 'W', strtotime( $minDate ) ) ) {
					$elon = '';
					$date = sprintf(
						__( "the next %s, %s", "estimated-delivery-for-woocommerce" ),
						date_i18n( 'l',   strtotime( $minDate ) ),
						date_i18n( 'j F', strtotime( $minDate ) )
					);
				}
			}
			
			$string = '<div class="edw_date">';
			
			if ( $mode === '1' ) {
				$string .= sprintf( __( 'Estimated delivery%s %s', 'estimated-delivery-for-woocommerce' ), $elon, $date );
			} elseif ( $mode === '2' ) {
				$string .= sprintf( __( 'Guaranteed delivery%s %s', 'estimated-delivery-for-woocommerce' ), $elon, $date );
			} elseif ( $mode === '3' ) {
				$productModeCustom = get_option( '_edw_mode_custom', '' );
				
				$string .= $productModeCustom . ' ' . $date;
			} else {
				$string .= __( 'Unsupported mode', 'estimated-delivery-for-woocommerce' );
			}
			
			$string .= '</div>';
			
			if ( $returnResult ) {
				return $string;
			}
			
			echo $string;
		}
		
		return '';
	}
	
	private function getDateFromNow( $disabledDays, $daysEstimated, $dateCheck = false ) {
		if ( count( $disabledDays ) === 7 ) {
			return false;
		}
		
		if ( ! $dateCheck ) {
			$dateCheck = date( 'Y-m-d', strtotime( ' + ' . $daysEstimated . ' days' ) );
		} else {
			$dateCheck = date( 'Y-m-d', strtotime( $dateCheck . ' + 1 days' ) );
		}
		
		$filterDisabled = date( 'D', strtotime( $dateCheck ) );
		
		if ( in_array( $filterDisabled, $disabledDays, true ) ) {
			$dateCheck = $this->getDateFromNow( $disabledDays, $daysEstimated, $dateCheck );
		}
		
		return $dateCheck;
	}
	
	/**
	 * Checks if at least one element (day, month and/or year) changes between two dates
	 *
	 * @param  string  $date1
	 * @param  string  $date2
	 *
	 * @return array
	 * @since 1.0.2
	 */
	private function getDifferencesBetweenDates( $date1, $date2 ) : array {
		$month = false;
		$day   = false;
		$year  = false;
		
		if ( date( 'm', strtotime( $date1 ) ) !== date( 'm', strtotime( $date2 ) ) ) {
			$month = true;
		}
		
		if ( date( 'd', strtotime( $date1 ) ) !== date( 'd', strtotime( $date2 ) ) ) {
			$day = true;
		}
		
		if ( date( 'Y', strtotime( $date1 ) ) !== date( 'Y', strtotime( $date2 ) ) ) {
			$year = true;
		}
		
		return [ $day, $month, $year ];
	}
}
