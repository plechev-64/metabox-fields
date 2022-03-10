<?php

class AdminPage extends MetaBoxAbstract {

	public $title = '';
	public $right = '';
	public $submenu = '';

	function __construct( $id, $args = [] ) {

		$args = wp_parse_args( $args, [
			'title'   => '',
			'right'   => 'manage_options',
			'submenu' => ''
		] );

		$this->id      = $id;
		$this->title   = $args['title'];
		$this->right   = $args['right'];
		$this->submenu = $args['submenu'];

		add_action( 'admin_menu', [ $this, 'init_page' ], 10 );
		add_action( 'admin_init', [ $this, 'update_data' ], 10 );

	}

	function init_scripts() {
		wp_enqueue_style( 'apl-fields', APF_URL . '/custom-fields/assets/style.css', false, time() );
		wp_enqueue_script( 'apl-fields', APF_URL . '/custom-fields/assets/scripts.js', false, time() );
	}

	function get_chosen_scripts() {

		wp_enqueue_script( 'chosen-jquery', APF_URL . '/custom-fields/assets/chosen.min.js' );
		wp_enqueue_style( 'chosen-jquery', APF_URL . '/custom-fields/assets/chosen.min.css' );

		return '<script>
			jQuery(document).ready(function($) {
				$("select[multiple]").chosen({
					no_results_text: "Ничего не найдено",
					width: "100%",
				});
				$("select.chosen-select").chosen({
					no_results_text: "Ничего не найдено"
				});
			});
		</script>';
	}

	function init_page() {
		if ( $this->submenu ) {
			add_submenu_page( $this->submenu, $this->title, $this->title, $this->right, $this->id, [
				$this,
				'page_content'
			] );
		} else {
			add_menu_page( $this->title, $this->title, $this->right, $this->id, [ $this, 'page_content' ] );
		}
	}

	function get_fields_content( $fields ) {
		$content = '';
		foreach ( $fields as $field ) {
			$content .= $field->get_html();
		}

		return $content;
	}

	function page_content() {

		$this->init_scripts();

		$content = '<div class="wrap pkr-page">';
		$content .= '<h1>' . $this->title . '</h1>';

		$content .= '<div class="postbox" style="padding: 20px;margin:20px 0;">';
		$content .= $this->get_content();
		$content .= '</div>';

		$content .= '</div>';
		echo $content;
	}

	function get_content() {
		return $this->get_form_content();
	}

	function get_form_content(){
		$content = '<form method="post">';
		$content .= $this->get_form();
		$content .= $this->get_nonce_field();
		$content .= '<div class="submit-wrap">' . get_submit_button() . '</div>';
		$content .= '</form>';
		return $content;
	}

	function get_form(){

	}

	function get_nonce_field() {
		return '<input type="hidden" name="' . $this->id . '_nonce" value="' . wp_create_nonce( __FILE__ ) . '" />';
	}

	function update_data() {

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return false;
		}

		if ( ! isset( $_POST[ $this->id . '_nonce' ] ) || ! wp_verify_nonce( $_POST[ $this->id . '_nonce' ], __FILE__ ) ) {
			return false;
		}

		if ( ! current_user_can( $this->right ) ) {
			return false;
		}

		$this->update();

	}

	function update() {

	}

}
