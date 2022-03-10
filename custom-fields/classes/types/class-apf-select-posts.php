<?php

class APF_SelectPosts extends APF_Field {

	public $post_types = [];
	public $number = 20;
	public $values;

	function __construct( $args ) {

		$args['type'] = 'select-posts';

		parent::__construct( $args );
	}

	function get_input() {
		global $wpdb;

		$posts = $wpdb->get_results("SELECT ID, post_title FROM $wpdb->posts WHERE post_type IN ('".implode("','", $this->post_types)."') AND post_status='publish' ORDER BY ID DESC LIMIT $this->number");

		$content = '<div id="select-posts-'.$this->id.'" class="select-posts">';
		$content .= '<input type="text" class="select-posts__search-field" name="" placeholder="Поиск">';
		$content .= '<div class="select-posts__sides">';
		$content .= '<div class="select-posts__items select-posts__side">';

		if($posts){
			foreach($posts as $post){
				$content .= '<a href="#" class="select-posts__item">';
				$content .= '<span>'.$post->post_title.'</span>';
				$content .= '<input type="hidden" name="selected-posts['.$this->id.'][]" value="'.$post->ID.'">';
				$content .= '</a>';
			}
		}else{
			$content .= '<p>Ничего не найдено</p>';
		}

		$content .= '</div>';
		$content .= '<div class="select-posts__selected select-posts__side">';

		$content .= '</div>';
		$content .= '</div>';
		$content .= '</div>';

		return $content;
	}

}
