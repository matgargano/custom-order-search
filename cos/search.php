<?php

namespace cos;

class Search {

	public function init() {
		$this->attach_hooks();
	}

	public function attach_hooks() {
		add_filter( 'posts_orderby', array( $this, 'edit_posts_orderby' ), 10 );
	}


	function edit_posts_orderby( $orderby_statement ) {
		if ( ! is_admin() && is_search() && is_main_query() ) {
			$options = Settings::get_option();

			if ( ! isset( $options['active'] ) || ! $options['active'] ) {
				return $orderby_statement;
			}

			$post_types_order = $options['post_type_order'];
			if ( Settings::is_json( $post_types_order ) ) {
				$post_types_order = json_decode( $post_types_order );
				$when_clause      = '';
				$counter          = 0;
				foreach ( $post_types_order as $post_type ) {
					$when_clause .= sprintf( " when '%s' then %s", $post_type->value, $counter );
					$counter ++;
				}
				global $wpdb;
				$orderby_statement = sprintf( '(case %sposts.post_type %s end )', $wpdb->prefix, $when_clause );
			}
		}

		return $orderby_statement;


	}


}