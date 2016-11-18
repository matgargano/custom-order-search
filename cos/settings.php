<?php

namespace cos;


class Settings {

	const NONCE = 'cost-settings';
	const PAGE_SLUG = 'cos-settings';
	const OPTION = 'cos-option';
	const SETTING_GROUP = 'cos-options';
	const SETTING_SECTION = 'cos-post-type-json';
	private $options;

	public function init() {

		$this->attach_hooks();

	}

	public function attach_hooks() {

		add_action( 'admin_menu', array( $this, 'add_menu_item' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue' ) );
		add_action( 'admin_init', array( $this, 'add_settings' ) );
	}

	public function add_settings() {
		register_setting(
			self::SETTING_GROUP,
			self::OPTION,
			array( $this, 'sanitize' )
		);

		add_settings_section(
			self::SETTING_SECTION,
			'',
			null,
			self::PAGE_SLUG
		);

		add_settings_field(
			'search_post_type_order',
			'',
			array( $this, 'post_type_order' ),
			self::PAGE_SLUG,
			self::SETTING_SECTION
		);

		add_settings_field(
			'active',
			'Enable Plugin',
			array( $this, 'active' ),
			self::PAGE_SLUG,
			self::SETTING_SECTION
		);

	}


	public function sanitize( $input ) {
		$new_input = array( 'active' => false );
		if ( isset( $input['post_type_order'] ) ) {
			if ( self::is_json( $input['post_type_order'] ) ) {
				$new_input['post_type_order'] = $input['post_type_order'];
			}

		}

		if ( isset( $input['active'] ) && 1 === (int) $input['active'] ) {
			$new_input['active'] = true;
		}

		return $new_input;
	}

	public function post_type_order() {
		$this->options = get_option( self::OPTION );
		$option_value  = $this->options['post_type_order'];
		$option_name = 'post_type_order';
		$option_group = self::OPTION;
		require __DIR__ . '/views/hidden-input.php';
	}

	public function active() {
		$this->options = get_option( self::OPTION );
		$option_name   = 'active';
		$option_value  = $this->options[ $option_name ];
		$option_group  = self::OPTION;
		require __DIR__ . '/views/checkbox.php';

	}

	public function print_section_info() {
		print 'Enter your settings below:';
	}

	function add_menu_item() {

		$page_title = 'Search Order Post Type';
		$menu_title = 'Search Order Post Type';
		$capability = 'manage_options';
		$menu_slug  = self::PAGE_SLUG;
		$function   = array( $this, 'output_screen' );
		add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function );

	}

	function output_screen() {

		$page_slug = self::PAGE_SLUG;
		$section = self::SETTING_GROUP;
		$nonce = self::NONCE;
		$page_title = 'Custom Order Search';
		$post_types = get_post_types([
			'exclude_from_search' => false,
			'_builtin'            => false,
			'public'              => true
		], 'objects');

		require __DIR__ . '/views/settings.php';


	}

	public function admin_enqueue( $hook ) {
		if ( strpos( $hook, self::PAGE_SLUG ) !== false ) {
			wp_enqueue_style( self::PAGE_SLUG, plugins_url( '/css/custom-order-search.css', __FILE__ ) );
			wp_enqueue_script( self::PAGE_SLUG, plugins_url( '/js/custom-order-search.js', __FILE__ ), array(
				'jquery',
				'jquery-ui-sortable'
			) );
			wp_localize_script( self::PAGE_SLUG, 'cos', array(
				'nonce' => wp_create_nonce( self::NONCE ),
			) );

		}

	}


	public static function is_json( $string ) {
		json_decode( $string );

		return ( json_last_error() == JSON_ERROR_NONE );
	}

	public static function get_option() {
		return get_option( self::OPTION );

	}


}