<?php
/*
	login users
	make pages and posts
	add media
	create a blog
	let users reply
	delete content
	add categories and tags
	add widgets
	embed a video.

 * Bootstraps the Theme.
 *
 * @package Aquila
 */

namespace AQUILA_THEME\Inc;

use AQUILA_THEME\Inc\Traits\Singleton;


class AQUILA_THEME
{
	use Singleton;

	protected function __construct()
	{

		// load class.
		Assets::get_instance();
		Menu::get_instance();
		$this->set_hooks();
	}

	protected function set_hooks()
	{
		// actions and filters
		add_action('after_setup_theme', [$this, 'setup_theme']);

		add_action('add_meta_boxes', [$this, 'add_custom_meta_box']);
		add_action('save_post', [$this, 'save_post_meta_data']);
	}

	public function setup_theme()
	{

		add_theme_support('title-tag');
		$defaults = array(
			'height'               => 100,
			'width'                => 400,
			'flex-height'          => true,
			'flex-width'           => true,
			'header-text'          => array('site-title', 'site-description'),
			'unlink-homepage-logo' => true,
		);
		add_theme_support('custom-logo', $defaults);

		// $another_args = array(
		// 	'default-color'      => '0000ff',
		// 	//'default-image'      => get_template_directory_uri() . '/images/wapuu.jpg',
		// 	'default-position-x' => 'right',
		// 	'default-position-y' => 'top',
		// 	'default-repeat'     => 'no-repeat',
		// );
		// add_theme_support('custom-background', $another_args);


		//https://developer.wordpress.org/reference/functions/add_theme_support/#post-thumbnails
		add_theme_support('post-thumbnails');
		add_theme_support('automatic-feed-links');
		add_theme_support('html5', array('comment-list', 'comment-form', 'search-form', 'gallery', 'caption', 'style', 'script'));
		add_editor_style();
		add_theme_support('wp-block-styles');
		add_theme_support('align-wide');
	}

	/**
	 * Add custom meta box.
	 *
	 * @return void
	 */
	public function add_custom_meta_box()
	{
		$screens = ['post'];
		foreach ($screens as $screen) {
			add_meta_box(
				'hide-page-title',           // Unique ID
				__('Hide page title', 'aquila'),  // Box title
				[$this, 'custom_meta_box_html'],  // Content callback, must be of type callable
				$screen,                   // Post type
				'side' // context
			);
		}
	}

	/**
	 * Custom meta box HTML( for form )
	 *
	 * @param object $post Post.
	 *
	 * @return void
	 */
	public function custom_meta_box_html($post)
	{

		$value = get_post_meta($post->ID, '_hide_page_title', true);

		/**
		 * Use nonce for verification.
		 * This will create a hidden input field with id and name as
		 * 'hide_title_meta_box_nonce_name' and unique nonce input value.
		 */
		wp_nonce_field(plugin_basename(__FILE__), 'hide_title_meta_box_nonce_name');

?>
		<label for="aquila-field"><?php esc_html_e('Hide the page title', 'aquila'); ?></label>
		<select name="aquila_hide_title_field" id="aquila-field" class="postbox">
			<option value=""><?php esc_html_e('Select', 'aquila'); ?></option>
			<option value="yes" <?php selected($value, 'yes'); ?>>
				<?php esc_html_e('Yes', 'aquila'); ?>
			</option>
			<option value="no" <?php selected($value, 'no'); ?>>
				<?php esc_html_e('No', 'aquila'); ?>
			</option>
		</select>
<?php
	}

	/**
	 * Save post meta into database
	 * when the post is saved.
	 *
	 * @param integer $post_id Post id.
	 *
	 * @return void
	 */
	public function save_post_meta_data($post_id)
	{

		/**
		 * When the post is saved or updated we get $_POST available
		 * Check if the current user is authorized
		 */
		if (!current_user_can('edit_post', $post_id)) {
			return;
		}

		/**
		 * Check if the nonce value we received is the same we created.
		 */
		if (!isset($_POST['hide_title_meta_box_nonce_name']) || !wp_verify_nonce($_POST['hide_title_meta_box_nonce_name'], plugin_basename(__FILE__))) {
			return;
		}

		if (array_key_exists('aquila_hide_title_field', $_POST)) {
			update_post_meta($post_id, '_hide_page_title', $_POST['aquila_hide_title_field']);
		}
	}
}
