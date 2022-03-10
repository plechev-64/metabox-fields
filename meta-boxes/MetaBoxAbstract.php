<?php


class MetaBoxAbstract {

	public $id = '';
	static $groups = [];

	function init_repeater($id, $group){
		global $repeaters;

		if(!isset($repeaters[$this->id])){
			$repeaters[$this->id] = $this;
		}

		$group->metabox_id = $this->id;
		$group->id = $id;
		self::$groups[$id] = $group;
	}

	function repeater($id){

		if(empty(self::$groups[$id])){
			return false;
		}

		return self::$groups[$id];

	}

	function repeater_content($id, $args = []){

		$args = wp_parse_args($args, [
			'title' => '',
			'notice' => '',
			'values' => [],
			'button' => 'Добавить еще',
			'item_id' => 0
		]);

		$content = '<div class="apf-fields">';

		if(!empty($args['title'])){
			$content .= '<h3 class="apf-field__title">'.$args['title'].'</h3>';
		}

		if(!empty($args['notice'])){
			$content .= '<small class="apf-field__notice">'.$args['notice'].'</small>';
		}

		$content .= $this->repeater($id)->get_content($args['values'], $args['item_id'], $args['button']);
		$content .= '</div>';

		return $content;

	}

	function get_subpages($subpagesData, $item, $callback){

		$content = '<div class="apf-fields apf-wrapper">';

		$content .= '<div class="apf-fields__menu">';
		$i = 0;
		foreach ( $subpagesData['subpages'] as $id => $data ) {
			$content .= '<input type="button" data-id="' . $id . '" class="apf-fields__menu-button button' . ( !empty($subpagesData['active']) && $subpagesData['active'] == $id ? ' active' : (empty($subpagesData['active']) && !$i? ' active': '') ) . '" onclick="APFSubPages.menuClick(this);" value="' . $data['name'] . '" />';
			$i++;
		}
		$content .= '</div>';

		$content .= '<div class="apf-fields__subpages">';

		if(!empty($subpagesData['notice'])){
			$content .= $subpagesData['notice'];
		}

		$i = 0;
		foreach ( $subpagesData['subpages'] as $id => $data ) {

			$content .= '<div class="apf-fields apf-fields__subpage' . ( !empty($subpagesData['active']) && $subpagesData['active'] == $id ? ' active' : (empty($subpagesData['active']) && !$i? ' active': '') ) . '" data-id="' . $id . '">';
			$content .= $callback($id, $data, $item);
			$content .= '</div>';

			$i++;

		}

		$content .= '</div>';

		$content .= '</div>';

		return $content;

	}

}