<?php
/**
 * Provider base class
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Smart_Filters_Provider_Base' ) ) {

	/**
	 * Define Jet_Smart_Filters_Provider_Base class
	 */
	abstract class Jet_Smart_Filters_Provider_Base {

		/**
		 * Get provider name
		 *
		 * @return string
		 */
		abstract public function get_name();

		/**
		 * Get provider ID
		 *
		 * @return string
		 */
		abstract public function get_id();

		/**
		 * Get filtered provider content
		 *
		 * @return string
		 */
		abstract public function ajax_get_content();

		/**
		 * Get provider wrapper selector
		 *
		 * @return string
		 */
		abstract public function get_wrapper_selector();

		/**
		 * Action for wrapper selector - 'insert' into it or 'replace'
		 *
		 * @return string
		 */
		public function get_wrapper_action() {
			return 'insert';
		}

		/**
		 * If added unique ID this paramter will determine - search selector inside this ID, or is the same element
		 *
		 * @return bool
		 */
		public function in_depth() {
			return false;
		}

		/**
		 * Set prefix for unique ID selector. Mostly is default '#' sign, but sometimes class '.' sign needed
		 *
		 * @return bool
		 */
		public function id_prefix() {
			return '#';
		}

	}

}
