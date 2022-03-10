<?php

class APF_Posts extends APF_Field {

	public $post_types = [];
	public $field;

	function __construct( $args ) {

		$args['type'] = 'posts';

		parent::__construct( $args );
	}

	function get_input() {
		global $wpdb;

		$posts = $wpdb->get_results("SELECT ID, post_title FROM $wpdb->posts WHERE post_type IN ('".implode("','", $this->post_types)."') AND post_status IN ('publish', 'draft') ORDER BY post_title ASC");

		$values = [];
		foreach($posts as $post){
			$values[$post->ID] = $post->post_title;
		}

		$this->field->set_prop('values', $values);

		return $this->field->get_input();

	}

}
