<?php
/**
 * Post Type Requirements Checklist.
 *
 * Help Clients Help Themselves
 *
 * @package   Post_Type_Requirements_Checklist
 * @author    Dave Winter (dave@dauid.us)
 * @license   GPL-2.0+
 * @link      http://dauid.us
 * @copyright 2014 dauid.us
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * administrative side of the WordPress site.
 *
 * If you're interested in introducing public-facing
 * functionality, then refer to `class-custom-featured-image-metabox.php`
 *
 * @package Post_Type_Requirements_Checklist_Admin
 */
class Post_Type_Requirements_Checklist_Admin {

	/**
	 * Unique identifier for your plugin.
	 *
	 *
	 * Call $plugin_slug from public plugin class later.
	 *
	 * @since    1.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = null;

	/**
	 * Instance of this class.
	 *
	 * @since    1.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since     1.0
	 */
	private function __construct() {

		/*
		 * Call $plugin_slug from public plugin class.
		 *
		 */
		$plugin = post_type_requirements_checklist::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();

		// Add the options page
		require_once( plugin_dir_path( __FILE__ ) . 'includes/settings.php' );

		// Add the menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( realpath( dirname( __FILE__ ) ) ) . 'post-type-requirements-checklist.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );

		// Fire functions
			add_action( 'admin_enqueue_scripts', array( $this, 'is_edit_page' ) );
			add_action( 'post_submitbox_misc_actions', array( $this, 'insert_publish_metabox_checklist' ) );

	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0
	 *
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
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0
	 */
	public function add_plugin_admin_menu() {

		/*
		 * Add a settings page for this plugin to the Settings menu.
		 *
		 * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
		 *
		 *        Administration Menus: http://codex.wordpress.org/Administration_Menus
		 *
		 */
		$this->plugin_screen_hook_suffix = add_options_page(
			__( 'Post Type Requirements', $this->plugin_slug ),
			__( 'Post Type Requirements', $this->plugin_slug ),
			'manage_options',
			$this->plugin_slug,
			array( $this, 'display_plugin_admin_page' )
		);

	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0
	 */
	public function display_plugin_admin_page() {
		include_once( 'views/admin.php' );
	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0
	 */
	public function add_action_links( $links ) {

		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_slug ) . '">' . __( 'Settings' ) . '</a>'
			),
			$links
		);

	}

	/**
	 * Get post type
	 *
	 * @return string Post type
	 *
	 * @since 1.0
	 */
	public function get_post_type() {

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			if ( isset( $_REQUEST['post_id'] ) ) {
				$post = get_post( $_REQUEST['post_id'] );
				return $post->post_type;
			}
		}

		$screen = get_current_screen();

		return $screen->post_type;

	} // end get_post_type

	/**
	 * enqueue styles
	 *
	 * @since 1.0
	 */
	public function is_edit_page($new_edit = null){

	    global $current_screen;  // Makes the $current_screen object available           
		if ($current_screen && ($current_screen->base == "edit" || $current_screen->base == "post")) {

			wp_enqueue_style('aptrc-style', plugins_url( '/css/aptrc.css', __FILE__ ) );

		}

	} // end is_edit_page

	/**
	 * Insert Publish Metabox Checklist
	 *
	 * @since 1.0
	 */
	public function insert_publish_metabox_checklist() {

		$post_type = $this->get_post_type();
		$options = get_option( $this->plugin_slug . '_' . $post_type );

		// checkbox title
		
		echo '<div id="requirements_list"><span id="rltop">' . __( 'Requirements Checklist:', $this->plugin_slug ) . '</span>';


		/**
		 * Title
		 *
		 * @since 1.0
		 */
		if ( isset( $options['title_check'] ) && ! empty( $options['title_check'] ) ) {	

			echo '<span class="reqcb">';
			echo '<input name="title_checkbox" type="checkbox" onclick="return false;" onkeydown="return false;" /><label for="title_checkbox"><span></span> ' . __( 'Title', $this->plugin_slug ) . '</label><br/>';
			echo '</span>'; 
			?>

			<script>

				function checkHeadline() {
		
					var titleElement = jQuery( "#title" );
					var title = titleElement.val();

					if ( title.length < 1 ) {
						jQuery( "input[type='checkbox'][name='title_checkbox']").prop('checked', false);
					}	
					else {
						jQuery( "input[type='checkbox'][name='title_checkbox']").prop('checked', true);
					}

				}

				// run when page first loads
				checkHeadline();
				// run on title input
				setInterval(checkHeadline,500);
				
			</script>

			<?php
			// should this also check against slug?
		}


		/**
		 * Editor
		 *
		 * @since 1.0
		 */
// rethink how this updates - every 10 seconds now
		if ( isset( $options['editor_check'] ) && ! empty( $options['editor_check'] ) ) {				
			echo '<span class="reqcb">';
			echo '<input name="editor_checkbox" type="checkbox" onclick="return false;" onkeydown="return false;" /><label for="editor_checkbox"><span></span> ' . __( 'WYSIWYG Editor', $this->plugin_slug ) . '</label><br/>';
			echo '</span>';
			?>

			<script>

				function checkEditor() {
		
					var editorElement = jQuery( ".wp-editor-area" );
					var editor = editorElement.val();

					if ( editor.length < '1' ) {		
						jQuery( "input[type='checkbox'][name='editor_checkbox']").prop('checked', false);
					} else {
						jQuery( "input[type='checkbox'][name='editor_checkbox']").prop('checked', true);
					}

				}

				// run when page first loads
				checkEditor();
				// run on editor input
				setInterval(checkEditor,500);
				
			</script>

			<?php
		}


		/**
		 * Featured Image
		 *
		 * @since 1.0
		 */
		if ( isset( $options['thumbnail_check'] ) && ! empty( $options['thumbnail_check'] ) ) {				
			echo '<span class="reqcb">';
			echo '<input name="thumbnail_checkbox" type="checkbox" onclick="return false;" onkeydown="return false;" /><label for="thumbnail_checkbox"><span></span> ' . __( 'Featured Image', $this->plugin_slug ) . '</label><br/>';
			echo '</span>';
			?>

			<script>

				function checkThumbnail() {

					if (jQuery("#postimagediv img").length) {
						jQuery( "input[type='checkbox'][name='thumbnail_checkbox']").prop('checked', true);
					}	
					else {
						jQuery( "input[type='checkbox'][name='thumbnail_checkbox']").prop('checked', false);
					}

				}

				// run when page first loads
				checkThumbnail();
				// set check time
				setInterval(checkThumbnail,500);
				
			</script>

			<?php
		}


		/**
		 * Excerpt
		 *
		 * @since 1.0
		 */
		if ( isset( $options['excerpt_check'] ) && ! empty( $options['excerpt_check'] ) ) {				
			echo '<span class="reqcb">';
			echo '<input name="excerpt_checkbox" type="checkbox" onclick="return false;" onkeydown="return false;" /><label for="excerpt_checkbox"><span></span> ' . __( 'Excerpt', $this->plugin_slug ) . '</label><br/>';
			echo '</span>';
			?>

			<script>

				function checkExcerpt() {

					var excerptElement = jQuery( "#excerpt" );
					var excerpt = excerptElement.val();

					if ( excerpt == '' ) {
						jQuery( "input[type='checkbox'][name='excerpt_checkbox']").prop('checked', false);
					}	
					else {
						jQuery( "input[type='checkbox'][name='excerpt_checkbox']").prop('checked', true);
					}

				}

				// run when page first loads
				checkExcerpt();
				// run on excerpt input
				setInterval(checkExcerpt,500);
				
			</script>

			<?php
		}


		/**
		 * Custom Fields
		 *
		 * @since 1.0
		 */
		if ( isset( $options['customfields_check'] ) && ! empty( $options['customfields_check'] ) ) {				
			echo '<span class="reqcb">';
			echo '<input name="customfields_checkbox" id="customfields_checkbox" type="checkbox" onclick="return false;" onkeydown="return false;" /><label for="customfields_checkbox"><span></span> ' . __( 'Custom Fields', $this->plugin_slug ) . '</label><br/>';
			echo '</span>';
			// logic to auto fill name of custom field(s) added in settings 
		}


		/**
		 * Post Format
		 *
		 * @since 1.0
		 */
/*		if ( isset( $options['postformats_check'] ) && ! empty( $options['postformats_check'] ) ) {				
			echo '<span class="reqcb">';
			echo '<input name="postformats_checkbox" id="postformats_checkbox" type="checkbox" onclick="return false;" onkeydown="return false;" /><label for="postformats_checkbox"><span></span> ' . __( 'Format', $this->plugin_slug ) . '</label><br/>';
			echo '</span>';
			?>

			<script>

				function checkPostFormat() {

					var excerptElement = jQuery( "#excerpt" );
					var excerpt = excerptElement.val();

					if ( excerpt == '' ) {
						jQuery( "input[type='checkbox'][name='postformats_checkbox']").prop('checked', false);
					}	
					else {
						jQuery( "input[type='checkbox'][name='postformats_checkbox']").prop('checked', true);
					}

				}

				// run when page first loads
				checkPostFormat();
				// run on excerpt input
				setInterval(checkPostFormat,500);
				
			</script>

			<?php
			// by default, no radio boxes should be selected
		}		*/


		/**
		 * Categories
		 *
		 * @since 1.0
		 */
		if ( isset( $options['categories_check'] ) && ! empty( $options['categories_check'] ) ) {				
			echo '<span class="reqcb">';
			echo '<input name="categories_checkbox" id="categories_checkbox" type="checkbox" onclick="return false;" onkeydown="return false;" /><label for="categories_checkbox"><span></span> ' . __( 'Categories', $this->plugin_slug . '');

			$cat_num = $options['categories_dropdown'];

			if ( $cat_num == '1' ) {
			}
			else {
				$cat_num_html = ' (' . __( 'minimum', $this->plugin_slug) . ' ' . $cat_num . ')';
				echo $cat_num_html;
			}

			echo '</label><br/>';
			echo '</span>';
			?>

			<script>

				function checkCategories() {

					var cat_num_check = '<?php echo $cat_num; ?>';
					var catschecked = jQuery("#categorychecklist input[type='checkbox']:checked").length;

					if ( ( catschecked == cat_num_check ) || ( catschecked > cat_num_check ) ) {
						jQuery( "input[type='checkbox'][name='categories_checkbox']").prop('checked', true);
					}
					else {
						jQuery( "input[type='checkbox'][name='categories_checkbox']").prop('checked', false);
					}

				}

				// run when page first loads
				checkCategories();
				// run on excerpt input
				setInterval(checkCategories,500);
				
			</script>

			<?php
			// logic for minimum number of categories
		}


		/**
		 * Tags
		 *
		 * @since 1.0
		 */
		if ( isset( $options['tags_check'] ) && ! empty( $options['tags_check'] ) ) {				
			echo '<span class="reqcb">';
			echo '<input name="tags_checkbox" id="tags_checkbox" type="checkbox" onclick="return false;" onkeydown="return false;" /><label for="tags_checkbox"><span></span> ' . __( 'Tags', $this->plugin_slug . '');
			
			$tag_num = $options['tags_dropdown'];

			if ( $tag_num == '1' ) {
			}
			else {
				$tag_num_html = ' (' . __( 'minimum', $this->plugin_slug) . ' ' . $tag_num . ')';
				echo $tag_num_html;
			}

			echo '</label><br/>';
			echo '</span>';
			?>

			<script>

				function checkTags() {

					var tag_num_check = '<?php echo $tag_num; ?>';
					var tagschecked = jQuery(".ntdelbutton").length;

					if ( ( tagschecked == tag_num_check ) || ( tagschecked > tag_num_check ) ) {
						jQuery( "input[type='checkbox'][name='tags_checkbox']").prop('checked', true);
					}
					else {
						jQuery( "input[type='checkbox'][name='tags_checkbox']").prop('checked', false);
					}

				}

				// run when page first loads
				checkTags();
				// run on excerpt input
				setInterval(checkTags,500);
				
			</script>

			<?php
			// logic for minimum number of tags
		}


		echo '<span id="rlbot">' . __( 'Publishing disabled until requirements met', $this->plugin_slug ) . '</span>';


		/**
		 * Hide/Enable Publish Button
		 *
		 * @since 1.0
		 */
		?>
		<script>
			function hideShowPublish() {

				//hide or shows publish box based on whether all the boxes on the page are checked
				var number = jQuery("#requirements_list input[type='checkbox']");
				var numberchecked = jQuery("#requirements_list input[type='checkbox']:checked");

				if ( number.length == numberchecked.length ) {
					jQuery( "#publish" ).show();
					jQuery( "#rlbot" ).hide();
					jQuery( "#requirements_list" ).css( "background-color", "transparent" );
				} else {
					jQuery( "#publish" ).hide();
					jQuery( "#rlbot" ).show();
					jQuery( "#requirements_list" ).css( "background-color", "#ffffe6" );
				}

				if ( number.length == 0 ) {
					jQuery( "#requirements_list" ).hide();
				} else {
					jQuery( "#requirements_list" ).show();
				}

			}

			// hide by default
			jQuery( "#publish" ).hide();
			// check every second
			setInterval(hideShowPublish,500);
		</script>
		<?php


		echo '</div>';

	} // end insert_publish_metabox_checklist



}
