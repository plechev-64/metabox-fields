<?php

class MetaBox extends MetaBoxAbstract {

	public $context = '';
	public $priority = '';
	public $title = '';
	public $post_types = [];
	public $post_ids = [];
	public $taxonomies = [];
	public $is_term = false;
	public $is_post = false;

	function __construct( $id, $args = [] ) {

		$args = wp_parse_args( $args, [
			'post_types' => [],
			'post_ids'   => [],
			'taxonomies' => [],
			'context'    => 'normal',
			'title'      => '',
			'priority'   => 'default'
		] );

		$this->id         = $id;
		$this->title      = $args['title'];
		$this->post_types = $args['post_types'];
		$this->context    = $args['context'];
		$this->priority   = $args['priority'];
		$this->taxonomies = $args['taxonomies'];
		$this->post_ids   = $args['post_ids'];

		if ( $this->post_types ) {

			if ( ! $this->post_ids || $this->post_ids && isset( $_GET['action'] ) && isset( $_GET['post'] ) && $_GET['action'] == 'edit' && in_array( $_GET['post'], $this->post_ids ) ) {
				add_action( 'add_meta_boxes', [ $this, 'init_post_metabox' ], 10 );
			}

			add_action( 'save_post', [ $this, 'update_data' ], 10 );

		}

		if ( $this->taxonomies ) {
			add_action( 'admin_init', [ $this, 'init_taxonomy_metabox' ], 10 );
			add_action( 'create_term', [ $this, 'update_data' ], 10 );
			add_action( 'edit_term', [ $this, 'update_data' ], 10 );
		}

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

	function init_taxonomy_metabox() {

		if ( isset( $_GET['taxonomy'] ) && taxonomy_exists( $_GET['taxonomy'] ) ) {
			$taxonomy = $_GET['taxonomy'];
		} else {
			return;
		}

		if ( is_array( $this->taxonomies ) && ! in_array( $taxonomy, $this->taxonomies ) ) {
			return false;
		}

		$this->is_term = true;

		$this->init_scripts();

		add_action( $taxonomy . '_add_form_fields', [ $this, 'metabox_content' ], 10 );
		add_action( $taxonomy . '_edit_form_fields', [ $this, 'metabox_content' ], 10 );

	}

	function init_post_metabox( $post_type ) {

		if ( is_array( $this->post_types ) && ! in_array( $post_type, $this->post_types ) ) {
			return false;
		}

		$this->is_post = true;

		$this->init_scripts();

		add_meta_box( $this->id, $this->title, [
			$this,
			'metabox_content'
		], $post_type, $this->context, $this->priority );

	}

	function get_nonce_input() {
		return '<input type="hidden" name="' . $this->id . '_nonce" value="' . wp_create_nonce( __FILE__ ) . '" />';
	}

	function verify_update( $item_id ) {

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return false;
		}

		if ( ! isset( $_POST[ $this->id . '_nonce' ] ) || ! wp_verify_nonce( $_POST[ $this->id . '_nonce' ], __FILE__ ) ) {
			return false;
		}

		if ( isset( $_POST['post_type'] ) && $this->post_types ) {

			if ( is_array( $this->post_types ) && ! in_array( $_POST['post_type'], $this->post_types ) ) {
				return false;
			}

			if ( ! current_user_can( 'edit_post', $item_id ) ) {
				return false;
			}

			$this->is_post = 1;

		} else if ( isset( $_POST['taxonomy'] ) && $this->taxonomies ) {

			if ( is_array( $this->taxonomies ) && ! in_array( $_POST['taxonomy'], $this->taxonomies ) ) {
				return false;
			}
			if ( ! current_user_can( 'manage_categories' ) ) {
				return false;
			}

			$this->is_term = 1;

		}

		return true;
	}

	static function get_fields_box( $fields ) {
		$content = '';
		foreach ( $fields as $field ) {
			if ( is_array( $field ) ) {
				$childHtml = '';
				foreach ( $field as $k => $f ) {
					if ( ! $k ) {
						continue;
					}
					$childHtml .= $f->get_input();
				}
				$content .= $field[0]->get_html( $childHtml );
			} else {
				$content .= $field->get_html();
			}
		}

		return $content;
	}

	function get_fields_content( $fields ) {
		$content = '';
		foreach ( $fields as $field ) {
			if ( is_array( $field ) ) {
				$childHtml = '';
				foreach ( $field as $k => $f ) {
					if ( ! $k ) {
						continue;
					}
					$childHtml .= $f->get_input();
				}
				$content .= $field[0]->get_html( $childHtml );
			} else {
				$content .= $field->get_html();
			}
		}

		return $content;
	}

	function get_taxonomy_table( $fields ) {

		if ( empty( $_GET['tag_ID'] ) ) {
			$content = '<h3>' . $this->title . '</h3>';
			$content .= $this->get_fields_content( $fields );
		} else {
			$content = '<table class="form-table" role="presentation">';

			$content .= '<tr>';
			$content .= '<th>' . $this->title . '</th>';
			$content .= '<td>';

			$content .= $this->get_fields_content( $fields );

			$content .= '</td>';
			$content .= '<tr/>';

			$content .= '</table>';
		}

		return $content;

	}

	function term_wrapper( $content ) {
		return '<tr class="form-field"><td colspan="2">' . $content . '</td></tr>';
	}

	function metabox_content( $itemObject ) {

		$content = $this->get_content( $itemObject );

		$content .= $this->get_nonce_input();

		echo $content;

	}

	function update_data( $item_id ) {

		if ( ! $this->verify_update( $item_id ) ) {
			return false;
		}

		$this->update( $item_id );
	}

	function get_content( $itemObject ) {

	}

	function update( $item_id ) {

	}


}
