<?php

class SettingsPage extends AdminPage {

	function __construct( $id, $args = [] ) {
		parent::__construct( $id, $args );
	}

	function get_form() {

		//Вкладки
		return $this->get_subpages( [
			'subpages' => [
				'first' => [
					'name' => 'Имя вкладки 1',
					'data' => 'value'
				],
				'two'   => [
					'name' => 'Имя вкладки 2',
					'data' => 'value'
				]
			],
			'active'   => 'two'
		], false, function ( $subpage_id, $data, $post ) {

			switch($subpage_id){
				case 'first':
					return $this->get_content_first($data);
				case 'two':
					return $this->get_content_two($data);
			}

		} );

	}

	function get_content_first($data){

		//text
		$content = APF::setup( 'text', [
			'id'    => 'text_id',
			'title' => __( 'Текстовое поле' ),
			'width' => 'full',
			'value' => ''
		] )->get_html();

		//number
		$content .= APF::setup( 'number', [
			'id'         => 'number_id',
			'title'      => __( 'Число' ),
			'width'      => 'full',
			'value_min'  => 0,
			'value_max'  => 10,
			'value_step' => 0.1,
			'value'      => 5.6
		] )->get_html();

		//textarea
		$content .= APF::setup( 'textarea', [
			'id'    => 'textarea_id',
			'title' => __( 'Текстовое поле' ),
			'width' => 'full',
			'value' => ''
		] )->get_html();

		return $content;
	}

	function get_content_two($data){

		//select
		$content = APF::setup( 'select', [
			'id'          => 'select_id',
			'title'       => __( 'Выбор' ),
			'empty_first' => 'Ничего не выбрано',
			'values'      => [
				0 => 'Первое значение',
				1 => 'Второе значение',
				2 => 'Третье значение'
			],
			'value'       => 1
		] )->get_html();

		//checkbox
		$content .= APF::setup( 'checkbox', [
			'id'     => 'checkbox_id',
			'title'  => __( 'Чекбоксы' ),
			'values' => [
				0 => 'Первое значение',
				1 => 'Второе значение',
				2 => 'Третье значение'
			],
			'value'  => [ 0, 2 ]
		] )->get_html();

		//editor
		$content .= APF::setup( 'editor', [
			'id'           => 'editor_id',
			'title'        => __( 'Редактор' ),
			'media_button' => 1,
			'tynimce'      => 1,
			'width'        => 'full',
			'value'        => ''
		] )->get_html();

		return $content;
	}

	function update() {

		$keys = [
			//
		];

		$settings = [];
		foreach ( $keys as $key ) {

			$value = $_POST[ $key ];

			//сохранение репитера
			if(!empty(self::$groups[$key])){
				$value = $this->repeater($key)->setup_value( $value );
			}

			$settings[$key] = $value;
		}

		//update data
		update_option( 'settings', $settings );
	}

}
