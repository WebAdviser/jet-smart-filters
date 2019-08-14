<?php
/**
 * Query manager class
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Smart_Filters_Query_Manager' ) ) {

	/**
	 * Define Jet_Smart_Filters_Query_Manager class
	 */
	class Jet_Smart_Filters_Query_Manager {

		private $_query          = array();
		private $_default_query  = array();
		private $_query_settings = array();
		private $_active_filters = array();
		private $_props          = array();

		/**
		 * Constructor for the class
		 */
		public function __construct() {

			add_filter( 'the_posts', array( $this, 'query_props_handler' ), 999, 2 );
			add_filter( 'posts_pre_query', array( $this, 'set_found_rows' ), 10, 2 );

		}

		/**
		 * Set no_found_rows to false
		 */
		public function set_found_rows( $posts, $query ) {

			if ( $query->get( 'jet_smart_filters' ) ) {
				$query->set( 'no_found_rows', false );
			}

			return $posts;

		}

		/**
		 * Store default query for passed provider
		 *
		 * @return [type] [description]
		 */
		public function store_provider_default_query( $provider_id, $query_args, $query_id = false ) {

			if ( ! $query_id ) {
				$query_id = 'default';
			}

			if ( empty( $this->_default_query[ $provider_id ] ) ) {
				$this->_default_query[ $provider_id ] = array();
			}

			if ( isset( $this->_default_query[ $provider_id ][ $query_id ] ) ) {
				return;
			}

			$this->_default_query[ $provider_id ][ $query_id ] = $query_args;

		}

		/**
		 * Return default queries array
		 *
		 * @return [type] [description]
		 */
		public function get_default_queries() {
			return $this->_default_query;
		}

		/**
		 * Returns query settings
		 *
		 * @return array
		 */
		public function get_query_settings() {
			return $this->_query_settings;
		}

		/**
		 * Query vars
		 */
		public function query_vars() {
			return apply_filters( 'jet-smart-filters/query/vars', array(
				'tax_query',
				'meta_query',
				'date_query',
				'_s',
			) );
		}

		/**
		 * Return parsed query arguments
		 *
		 * @return void
		 */
		public function get_query_args() {
			if ( $this->is_ajax_filter() && ! empty( $this->_default_query ) ) {
				return array_merge( $this->_default_query, $this->_query );
			} else {
				return $this->_query;
			}
		}

		/**
		 * Check if is ajax filter processed
		 *
		 * @return boolean [description]
		 */
		public function is_ajax_filter() {

			if ( ! wp_doing_ajax() ) {
				return false;
			}

			$allowed_actions = apply_filters( 'jet-smart-filters/query/allowed-ajax-actions', array(
				'jet_smart_filters',
				'jet_smart_filters_refresh_controls',
				'jet_smart_filters_refresh_controls_reload',
			) );

			if ( ! isset( $_REQUEST['action'] ) || ! in_array( $_REQUEST['action'], $allowed_actions ) ) {
				return false;
			}

			return true;

		}

		/**
		 * Store query properties
		 *
		 * @param  [type] $posts [description]
		 * @param  [type] $query [description]
		 * @return [type]        [description]
		 */
		public function query_props_handler( $posts, $query ) {

			if ( $query->get( 'jet_smart_filters' ) ) {
				$this->store_query_props( $query );
			}

			return $posts;

		}

		/**
		 * Store query properites
		 *
		 * @param  WP_Query $query WP_Query object.
		 * @return void
		 */
		public function store_query_props( $query ) {

			$provider_data = $this->decode_provider_data( $query->get( 'jet_smart_filters' ) );
			$provider      = $provider_data['provider'];
			$query_id      = $provider_data['query_id'];

			if ( empty( $this->_props[ $provider ] ) ) {
				$this->_props[ $provider ] = array();
			}
			do_action( 'jet-smart-filters/query/store-query-props/' . $provider, $this, $query_id );
			$this->_props[ $provider ][ $query_id ] = array(
				'found_posts'   => $query->found_posts,
				'max_num_pages' => $query->max_num_pages,
				'page'          => $query->get( 'paged' ),
			);

		}

		/**
		 * Encode provider data
		 *
		 * @param  [type] $provider [description]
		 * @param  string $query_id [description]
		 * @return [type]           [description]
		 */
		public function encode_provider_data( $provider, $query_id = 'default' ) {

			if ( ! $query_id ) {
				$query_id = 'default';
			}

			return $provider . '/' . $query_id;
		}

		/**
		 * Decode provider data
		 *
		 * @param  [type] $provider [description]
		 * @param  string $query_id [description]
		 * @return [type]           [description]
		 */
		public function decode_provider_data( $provider ) {

			$data   = explode( '/', $provider );
			$result = array();

			if ( empty( $data ) ) {
				$result['provider'] = $provider;
				$result['query_id'] = 'default';
			} elseif ( ! empty( $data[0] ) && empty( $data[1] ) ) {
				$result['provider'] = $data[0];
				$result['query_id'] = 'default';
			} else {
				$result['provider'] = $data[0];
				$result['query_id'] = $data[1];
			}

			return $result;

		}

		/**
		 * Store properties array for provider
		 *
		 * @param [type] $props [description]
		 */
		public function set_props( $provider, $props, $query_id = 'default' ) {

			if ( ! $query_id ) {
				$query_id = 'default';
			}

			if ( empty( $this->_props[ $provider ] ) ) {
				$this->_props[ $provider ] = array();
			}

			$this->_props[ $provider ][ $query_id ] = $props;
		}
		/**
		 * Store properties array for provider
		 *
		 * @param [type] $props [description]
		 */
		public function add_prop( $provider, $prop, $value, $query_id = 'default' ) {
			if ( ! $query_id ) {
				$query_id = 'default';
			}
			if ( empty( $this->_props[ $provider ] ) ) {
				$this->_props[ $provider ] = array();
			}
			$this->_props[ $provider ][ $query_id ][ $prop ] = $value;

		}
		/**
		 * Query properties provider
		 *
		 * @param  string $provider Provider ID.
		 * @return array
		 */
		public function get_query_props( $provider = null, $query_id = 'default' ) {

			if ( ! $query_id ) {
				$query_id = 'default';
			}

			if ( ! $provider ) {
				return $this->_props;
			}

			return isset( $this->_props[ $provider ][ $query_id ] ) ? $this->_props[ $provider ][ $query_id ] : array();

		}

		/**
		 * Get current provider ID.
		 *
		 * @return string
		 */
		public function get_current_provider( $return = null ) {

			if ( $this->is_ajax_filter() ) {
				$provider = $_REQUEST['provider'];
			} else {
				$provider = isset( $_REQUEST['jet-smart-filters'] ) ? $_REQUEST['jet-smart-filters'] : false;

				if ( ! $provider ) {
					$provider_data = get_query_var( 'jet_smart_filters' );
				}

			}

			if ( ! $provider ) {
				return false;
			}

			if ( 'raw' === $return ) {
				return $provider;
			}

			$data = $this->decode_provider_data( $provider );

			if ( ! $return ) {
				return $data;
			} else {
				return isset( $data[ $return ] ) ? $data[ $return ] : false;
			}

		}

		/**
		 * Return properties for current query
		 *
		 * @return array
		 */
		public function get_current_query_props() {
			$data = $this->get_current_provider();
			return $this->get_query_props( $data['provider'], $data['query_id'] );
		}

		/**
		 * Query
		 */
		public function get_query_from_request() {

			$this->_query = array(
				'jet_smart_filters' => $this->get_current_provider( 'raw' ),
				'suppress_filters'  => false,
			);

			if ( $this->is_ajax_filter() ) {
				$this->_default_query  = ! empty( $_REQUEST['defaults'] ) ? $_REQUEST['defaults'] : array();
				$this->_query_settings = ! empty( $_REQUEST['settings'] ) ? $_REQUEST['settings'] : array();
			}

			foreach ( $this->query_vars() as $var ) {

				if ( $this->is_ajax_filter() ) {
					$data = isset( $_REQUEST['query'] ) ? $_REQUEST['query'] : array();
				} else {
					$data = $_REQUEST;
				}

				if ( ! $data ) {
					$data = array();
				}

				array_walk( $data, function( $value, $key ) use ( $var ) {

					if ( strpos( $key, $var ) ) {

						$this->_active_filters[ $this->raw_key( $key, $var ) ] = $value;

						switch ( $var ) {

							case 'tax_query':

								$this->add_tax_query_var( $value, $this->clear_key( $key, $var ) );

								break;

							case 'date_query':

								$this->add_date_query_var( $value );

								break;

							case 'meta_query':

								$key         = $this->clear_key( $key, $var );
								$with_suffix = explode( '|', $key );
								$suffix      = false;

								if ( isset( $with_suffix[1] ) ) {
									$key    = $with_suffix[0];
									$suffix = $with_suffix[1];
									$value  = $this->apply_suffix( $suffix, $value );
								}

								$this->add_meta_query_var( $value, $key, $suffix );

								break;

							case '_s':

								if ( false !== strpos( $key, '__s_query' ) ) {
									$this->_query['s'] = $value;
								}

								break;

							default:

								$this->_query[ $var ] = apply_filters(
									'jet-smart-filters/query/add-var',
									$value,
									$key,
									$var,
									$this
								);

								break;
						}
					}

				} );

			}

			if ( isset( $_REQUEST['paged'] ) && 'false' !== $_REQUEST['paged'] ) {
				$paged = absint( $_REQUEST['paged'] );
			} elseif (  isset( $_REQUEST['jet_paged'] ) ) {

				$paged = absint( $_REQUEST['jet_paged'] );
			} else {
				$paged = false;
			}

			if ( $paged ) {
				$this->_query['paged'] = $paged;
			}

			$this->_query = apply_filters( 'jet-smart-filters/query/final-query', $this->_query );

		}

		/**
		 * Returns active filters array
		 *
		 * @return [type] [description]
		 */
		public function get_active_filters_array() {

			$request_filters = isset( $_REQUEST['filters'] ) ?  $_REQUEST['filters'] : array();
			$active_filters  = $this->_active_filters;

			if ( empty( $request_filters ) ) {
				return array();
			}

			$result = array();

			foreach ( $request_filters as $filter_id => $filter_data ) {

				$value = null;

				if ( isset( $active_filters[ $filter_data['query_var'] ] ) ) {
					$value = $active_filters[ $filter_data['query_var'] ];
				} elseif ( ! empty( $filter_data['value'] ) ) {
					$value = $filter_data['value'];
				}

				if ( null !== $value ) {
					$result[] = array(
						'id'       => $filter_id,
						'type'     => $filter_data['filter'],
						'value'    => $value,
						'queryVar' => $filter_data['query_var'],
					);
				}
			}

			return $result;

		}

		/**
		 * Clear key from varaible prefix
		 *
		 * @param  [type] $key       [description]
		 * @param  [type] $query_var [description]
		 * @return [type]            [description]
		 */
		public function clear_key( $key, $query_var ) {
			return str_replace( '_' . $query_var . '_', '', $key );
		}

		/**
		 * Return raw key
		 *
		 * @param  [type] $key       [description]
		 * @param  [type] $query_var [description]
		 * @return [type]            [description]
		 */
		public function raw_key( $key, $query_var ) {

			$key        = str_replace( '_' . $query_var . '_', '', $key );
			$has_filter = explode( '|', $key );

			if ( isset( $has_filter[1] ) ) {
				return $has_filter[0];
			} else {
				return $key;
			}

		}

		/**
		 * Add tax query varibales
		 */
		public function add_tax_query_var( $value, $key ) {

			$tax_query = isset( $this->_query['tax_query'] ) ? $this->_query['tax_query'] : array();

			if ( ! isset( $tax_query[ $key ] ) ) {
				$tax_query[ $key ] = array(
					'taxonomy' => $key,
					'field'    => 'term_id',
					'terms'    => $value,
				);
			} else {

				if ( ! is_array( $value ) ) {
					$value = array( $value );
				}

				if ( ! is_array( $tax_query[ $key ]['terms'] ) ) {
					$tax_query[ $key ]['terms'] = array( $tax_query[ $key ]['terms'] );
				}

				$tax_query[ $key ]['terms'] = array_merge( $tax_query[ $key ]['terms'], $value );

			}

			if ( !empty( $this->_default_query['tax_query'] ) ) {
				$this->_query['tax_query'] = array_merge( $this->_default_query['tax_query'], $tax_query );
			} else {
				$this->_query['tax_query'] = $tax_query;
			}

		}

		/**
		 * Add date query varibales
		 */
		public function add_date_query_var( $value ) {

			$date_query = isset( $this->_query['date_query'] ) ? $this->_query['date_query'] : array();
			$value      = explode( ':', $value );

			if ( 2 !== count( $value ) ) {
				return;
			}

			$after  = $value[0];
			$before = $value[1];
			$after  = explode( '/', $after );
			$before = explode( '/', $before );

			$after_query = array(
				'year'  => isset( $after[2] ) ? $after[2] : false,
				'month' => isset( $after[0] ) ? $after[0] : false,
				'day'   => isset( $after[1] ) ? $after[1] : false,
			);

			$before_query = array(
				'year'  => isset( $before[2] ) ? $before[2] : false,
				'month' => isset( $before[0] ) ? $before[0] : false,
				'day'   => isset( $before[1] ) ? $before[1] : false,
			);

			$after_query   = array_filter( $after_query );
			$before_query  = array_filter( $before_query );
			$current_query = array();

			if ( ! empty( $after_query ) ) {
				$current_query['after'] = $after_query;
			}

			if ( ! empty( $before_query ) ) {
				$current_query['before'] = $before_query;
			}

			if ( ! empty( $current_query ) ) {
				$date_query[] = $current_query;
			}

			if ( !empty( $this->_default_query['date_query'] ) ) {
				$this->_query['date_query'] = array_merge( $this->_default_query['date_query'], $date_query );
			} else {
				$this->_query['date_query'] = $date_query;
			}

		}

		/**
		 * Apply value suffix
		 *
		 * @param  [type] $suffix [description]
		 * @param  [type] $value  [description]
		 * @return [type]         [description]
		 */
		public function apply_suffix( $suffix, $value ) {

			switch ( $suffix ) {
				case 'range':
					return explode( ':', $value );

				case 'date_range':
					return array_map( 'strtotime', explode( ':', $value ) );

				case 'multi_range':

					$result = array();
					foreach ( $value as $row ) {
						$result[] = explode( ':', $row );
					}
					return $result;

				default:
					return apply_filters( 'jet-smart-filters/apply-suffix/' . $suffix, $value, $this );
			}

		}

		/**
		 * Add tax query varibales
		 */
		public function add_meta_query_var( $value, $key, $suffix = false ) {

			$meta_query = isset( $this->_query['meta_query'] ) ? $this->_query['meta_query'] : array();

			if ( 'multi_range' === $suffix || 'multi_select' === $suffix || ( 'is_custom_checkbox' === $suffix && is_array( $value ) ) ) {

				if ( 'multi_select' === $suffix ) {
					$relation = 'AND';
				} else {
					$relation = 'OR';
				}

				$nested_query = array(
					'relation' => $relation,
				);

				foreach ( $value as $value_row ) {
					$nested_query[] = $this->prepare_meta_query_row( $value_row, $key, $suffix );
				}

				$meta_query[] = $nested_query;

			} else {
				$meta_query[] = $this->prepare_meta_query_row( $value, $key, $suffix );
			}

			if ( !empty( $this->_default_query['meta_query'] ) ) {
				$this->_query['meta_query'] = array_merge( $this->_default_query['meta_query'], $meta_query );
			} else {
				$this->_query['meta_query'] = $meta_query;
			}

		}

		/**
		 * Preapre single meta query item
		 *
		 * @param  mixed  $value  [description]
		 * @param  string $key    [description]
		 * @param  mixed  $suffix [description]
		 * @return array
		 */
		public function prepare_meta_query_row( $value, $key, $suffix = false ) {

			$compare    = '=';

			$suffix_with_operand = explode( '::', $suffix );
			$suffix = $suffix_with_operand[0];

			if( isset($suffix_with_operand[1]) ){
				$operand = $suffix_with_operand[1];
			} else {
				$operand = false;
			}

			if ( is_array( $value ) ) {
				$compare = 'IN';
			}

			if ( $operand ){
				switch ( $operand ){
					case 'greater' :
						$compare = '>=';
						break;
					case 'less' :
						$compare = '<=';
						break;
					case 'equal' :
						$compare = '=';
						break;
				}
			}

			$current_row = array(
				'key'     => $key,
				'value'   => $value,
				'compare' => $compare,
			);

			if ( $suffix && ( 'date_range' === $suffix ) ) {
				$current_row['compare'] = 'BETWEEN';
				$current_row['type']    = 'NUMERIC';
			} elseif ( $suffix && in_array( $suffix, array( 'range', 'multi_range' ) ) ) {
				$current_row['compare'] = 'BETWEEN';
				$current_row['type']    = 'DECIMAL(16,4)';
			} elseif ( $suffix && 'search' === $suffix ) {
				$current_row['compare'] = 'LIKE';
				$current_row['type']    = 'CHAR';
			}  elseif ( $operand && $suffix && 'rating' === $suffix ) {
				$current_row['type']    = 'DECIMAL(16,4)';
			} elseif( $suffix && ( 'is_custom_checkbox' === $suffix ) ){
				$current_row['value']   = $value . '["]?;s:4:"true"';
				$current_row['compare'] = 'REGEXP';
			}

			return apply_filters( 'jet-smart-filters/query/meta-query-row', $current_row, $this, $suffix );

		}

	}

}
