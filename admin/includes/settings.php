<?php
class post_type_requirements_checklist_settings {
	/**
	 * Unique identifier for your plugin.
	 *
	 *
	 * Call $plugin_slug from public plugin class later.
	 *
	 * @since    1.0
	 * @var      string
	 */
	protected $plugin_slug = null;
	/**
	 * Instance of this class.
	 *
	 * @since    1.0
	 * @var      object
	 */
	protected static $instance = null;
	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     1.0
	 */
	private function __construct() {
		$plugin = post_type_requirements_checklist::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();
		// Add settings page
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'admin_print_styles', array( $this, 'is_settings_page' ) );
	}
	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}
	/**
	 * enqueue styles
	 *
	 * @param  string $content HTML string
	 *
	 * @since 1.0
	 */
	public function is_settings_page(){

		wp_enqueue_style('aptrc-settings-style', plugins_url( '../css/aptrc-settings.css', __FILE__ ) );

	} // end is_settings_page

	/**
	 * Registering the Sections, Fields, and Settings.
	 *
	 * This function is registered with the 'admin_init' hook.
	 */
	public function admin_init() {
		$plugin = post_type_requirements_checklist::get_instance();
		$post_types = $plugin->supported_post_types();
		$defaults = array(
				// order defined by Parameters reference at http://codex.wordpress.org/Function_Reference/post_type_supports
				'title' => '',
				'editor' => '',
				'thumbnail' => '',
				'excerpt' => '',
				// 'custom-fields' => '',
				'categories' => '',
				'tags' => '',
				// 'post-formats' => '',
			);

		foreach ( $post_types as $pt ) {
			$post_object = get_post_type_object( $pt );
			$section = $this->plugin_slug . '_' . $pt;
			if ( false == get_option( $section ) ) {
				add_option( $section, apply_filters( $section . '_default_settings', $defaults ) );
			}
			$args = array( $section, get_option( $section ) );
			add_settings_section(
				$pt,
				sprintf( __( 'Set content check to requirements for all %s', $this->plugin_slug ), $post_object->labels->name ),
				'',
				$section
			);

			if ( post_type_supports( $pt, 'title' )) {
				add_settings_field(
					'title_check',
					__( 'Title:', $this->plugin_slug ),
					array( $this, 'title_check_callback' ),
					$section,
					$pt,
					$args
				);
			}

			if ( post_type_supports( $pt, 'editor' )) {
				add_settings_field(
					'editor_check',
					__( 'WYSIWYG Editor:', $this->plugin_slug ),
					array( $this, 'editor_check_callback' ),
					$section,
					$pt,
					$args
				);
			}

			if ( post_type_supports( $pt, 'thumbnail' )) {
				add_settings_field(
					'thumbnail_check',
					__( 'Featured Image:', $this->plugin_slug ),
					array( $this, 'thumbnail_check_callback' ),
					$section,
					$pt,
					$args
				);
			}

			if ( post_type_supports( $pt, 'excerpt' )) {
				add_settings_field(
					'excerpt_check',
					__( 'Excerpt:', $this->plugin_slug ),
					array( $this, 'excerpt_check_callback' ),
					$section,
					$pt,
					$args
				);
			}

/*			if ( post_type_supports( $pt, 'custom-fields' )) {
				add_settings_field(
					'customfields_check',
					__( 'Custom Fields:', $this->plugin_slug ),
					array( $this, 'customfields_check_callback' ),
					$section,
					$pt,
					$args
				);
			}			*/

			if ( !($pt == 'page') ) {

				if ( is_object_in_taxonomy( $pt, 'category' ) ) {
					add_settings_field(
						'categories_check',
						__( 'Categories:', $this->plugin_slug ),
						array( $this, 'categories_check_callback' ),
						$section,
						$pt,
						$args
					);

					add_settings_field(
						'categories_dropdown',
						__( '', $this->plugin_slug ),
						array( $this, 'categories_dropdown_callback' ),
						$section,
						$pt,
						$args
					);
				}

				if ( is_object_in_taxonomy( $pt, 'post_tag' ) ) {
					add_settings_field(
						'tags_check',
						__( 'Tags Metabox:', $this->plugin_slug ),
						array( $this, 'tags_check_callback' ),
						$section,
						$pt,
						$args
					);

					add_settings_field(
						'tags_dropdown',
						__( '', $this->plugin_slug ),
						array( $this, 'tags_dropdown_callback' ),
						$section,
						$pt,
						$args
					);
				}			

/*				if ( post_type_supports( $pt, 'post-formats' )) {
					add_settings_field(
						'postformats_check',
						__( 'Post Format:', $this->plugin_slug ),
						array( $this, 'postformats_check_callback' ),
						$section,
						$pt,
						$args
					);
				}		*/
				
			}

			register_setting(
				$section,
				$section
			);
		}
	} // end admin_init

	public function title_check_callback( $args ) {

		$output = $args[0].'[title_check]';
		$value  = isset( $args[1]['title_check'] ) ? $args[1]['title_check'] : '';

		$checkhtml = '<input type="checkbox" id="title_check" name="' . $output . '" value="1"' . checked( 1, $value, false ) . ' />';
		$checkhtml .= '<label for="title_check"> ' . __( 'check to require', $this->plugin_slug ) . '</label>';
		// $checkhtml .= '<p class="description">' . __( 'A title must be set in order to publish.', $this->plugin_slug ) . '</p><hr>';

		echo $checkhtml;

	} // end title_check_callback

	public function editor_check_callback( $args ) {

		$output = $args[0].'[editor_check]';
		$value  = isset( $args[1]['editor_check'] ) ? $args[1]['editor_check'] : '';

		$checkhtml = '<input type="checkbox" id="editor_check" name="' . $output . '" value="1"' . checked( 1, $value, false ) . ' />';
		$checkhtml .= '<label for="editor_check"> ' . __( 'check to require', $this->plugin_slug ) . '</label>';
		// $checkhtml .= '<p class="description">' . __( 'WYSIWYG editor must have content in order to publish.', $this->plugin_slug ) . '</p><hr>';

		echo $checkhtml;

	} // end editor_check_callback

	public function thumbnail_check_callback( $args ) {

		$output = $args[0].'[thumbnail_check]';
		$value  = isset( $args[1]['thumbnail_check'] ) ? $args[1]['thumbnail_check'] : '';

		$checkhtml = '<input type="checkbox" id="thumbnail_check" name="' . $output . '" value="1"' . checked( 1, $value, false ) . ' />';
		$checkhtml .= '<label for="thumbnail_check"> ' . __( 'check to require', $this->plugin_slug ) . '</label>';
		// $checkhtml .= '<p class="description">' . __( 'A featured image must be added in order to publish.', $this->plugin_slug ) . '</p><hr>';

		echo $checkhtml;

	} // end thumbnail_check_callback

	public function excerpt_check_callback( $args ) {

		$output = $args[0].'[excerpt_check]';
		$value  = isset( $args[1]['excerpt_check'] ) ? $args[1]['excerpt_check'] : '';

		$checkhtml = '<input type="checkbox" id="excerpt_check" name="' . $output . '" value="1"' . checked( 1, $value, false ) . ' />';
		$checkhtml .= '<label for="excerpt_check"> ' . __( 'check to require', $this->plugin_slug ) . '</label>';
		// $checkhtml .= '<p class="description">' . __( 'A custom excerpt must be added in order to publish.', $this->plugin_slug ) . '</p><hr>';

		echo $checkhtml;

	} // end excerpt_check_callback

	public function customfields_check_callback( $args ) {

		$output = $args[0].'[customfields_check]';
		$value  = isset( $args[1]['customfields_check'] ) ? $args[1]['customfields_check'] : '';

		$checkhtml = '<input type="checkbox" id="customfields_check" name="' . $output . '" value="1"' . checked( 1, $value, false ) . ' />';
		$checkhtml .= '<label for="customfields_check"> ' . __( 'check to require', $this->plugin_slug ) . '</label>';
		// $checkhtml .= '<p class="description">' . __( 'Something in order to publish.', $this->plugin_slug ) . '</p><hr>';

		echo $checkhtml;

	} // end customfields_check_callback

	public function categories_check_callback( $args ) {

		$output = $args[0].'[categories_check]';
		$value  = isset( $args[1]['categories_check'] ) ? $args[1]['categories_check'] : '';

		$checkhtml = '<input type="checkbox" id="categories_check" name="' . $output . '" value="1"' . checked( 1, $value, false ) . ' />';
		$checkhtml .= '<label for="categories_check"> ' . __( 'check to require', $this->plugin_slug ) . '</label>';
		// $checkhtml .= '<p class="description">' . __( 'A category/categories must be set in order to publish.', $this->plugin_slug ) . '</p>';

		echo $checkhtml;

	} // end categories_check_callback

		public function categories_dropdown_callback( $args ) {

			$output = $args[0].'[categories_dropdown]';
			$value  = isset( $args[1]['categories_dropdown'] ) ? $args[1]['categories_dropdown'] : '';

			$html = '<select id="categories_dropdown" name="' . $output . '">';
	        $html .= '<option value="1"' . selected( 1, $value, false) . '>1</option>';
	        $html .= '<option value="2"' . selected( 2, $value, false) . '>2</option>';
	        $html .= '<option value="3"' . selected( 3, $value, false) . '>3</option>';
	    	$html .= '</select>';

	    	$html .= '<label id="dropdown" for="categories_dropdown"> ' . __( 'set minimum number of required categories', $this->plugin_slug ) . '</label>';
	     
	    	echo $html;

		} // end categories_dropdown_callback

	public function tags_check_callback( $args ) {

		$output = $args[0].'[tags_check]';
		$value  = isset( $args[1]['tags_check'] ) ? $args[1]['tags_check'] : '';

		$checkhtml = '<input type="checkbox" id="tags_check" name="' . $output . '" value="1"' . checked( 1, $value, false ) . ' />';
		$checkhtml .= '<label for="tags_check"> ' . __( 'check to require', $this->plugin_slug ) . '</label>';
		// $checkhtml .= '<p class="description">' . __( 'A tag/tags must be set in order to publish.  Select the minimum check to required number of tags to be set.', $this->plugin_slug ) . '</p><hr>';

		echo $checkhtml;

	} // end tags_check_callback

		public function tags_dropdown_callback( $args ) {

			$output = $args[0].'[tags_dropdown]';
			$value  = isset( $args[1]['tags_dropdown'] ) ? $args[1]['tags_dropdown'] : '';

			$html = '<select id="tags_dropdown" name="' . $output . '">';
	        $html .= '<option value="1"' . selected( 1, $value, false) . '>1</option>';
	        $html .= '<option value="2"' . selected( 2, $value, false) . '>2</option>';
	        $html .= '<option value="3"' . selected( 3, $value, false) . '>3</option>';
	        $html .= '<option value="4"' . selected( 4, $value, false) . '>4</option>';
	        $html .= '<option value="5"' . selected( 5, $value, false) . '>5</option>';
	    	$html .= '</select>';

	    	$html .= '<label id="dropdown" for="tags_dropdown"> ' . __( 'set minimum number of required tags', $this->plugin_slug ) . '</label>';
	     
	    	echo $html;

		} // end tags_dropdown_callback

/*	public function postformats_check_callback( $args ) {

		$output = $args[0].'[postformats_check]';
		$value  = isset( $args[1]['postformats_check'] ) ? $args[1]['postformats_check'] : '';

		$checkhtml = '<input type="checkbox" id="postformats_check" name="' . $output . '" value="1"' . checked( 1, $value, false ) . ' />';
		$checkhtml .= '<label for="postformats_check"> ' . __( 'check to require', $this->plugin_slug ) . '</label>';
		// $checkhtml .= '<p class="description">' . __( 'A post format must be set in order to publish.  This option will unselect all post format radio fields by default.', $this->plugin_slug ) . '</p><hr>';

		echo $checkhtml;

	} // end postformats_check_callback		*/

	//slug

}
post_type_requirements_checklist_settings::get_instance();
