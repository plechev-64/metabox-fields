<?php

class PostMetaBox extends MetaBox {

	private $values = [];

	function __construct( $id, $args = [] ) {
		parent::__construct( $id, $args );

		//инициализация репитера
		$this->init_repeater( 'repeater_id', new APF_Repeater( [
			APF::setup( 'image', [
				'id' => 'image_id'
			] ),
			APF::setup( 'text', [
				'id' => 'text_id'
			] ),
		] ) );
	}

	function get_content( $post ) {

		$this->values = get_post_meta( $post->ID, 'meta_values', 1 );

		//Вкладки
		$content = $this->get_subpages( [
			'subpages' => [
				'simple'   => [
					'name' => 'Простые поля'
				],
				'children' => [
					'name' => 'Дочерние поля'
				],
				'taxonomy' => [
					'name' => 'Таксономии'
				],
				'repeater' => [
					'name' => 'Репитер'
				],
				'subpages' => [
					'name' => 'Вкладки'
				]
			]
		], $post, function ( $id, $data, $post ) {

			switch ( $id ) {
				case 'simple':
					return $this->getSimpleFields( $data, $post );
				case 'children':
					return $this->getChildrenFields( $data, $post );
				case 'taxonomy':
					return $this->getTaxonomyFields( $data, $post );
				case 'repeater':
					return $this->getRepeaterField( $data, $post );
				case 'subpages':
					return $this->getSubpagesField( $data, $post );
			}

		} );

		return $content;

	}

	function getSimpleFields( $data, $post ) {

		$content = '<div class="apf-fields apf-fields_flex">';
		//text
		$content .= APF::setup( 'text', [
			'id'    => 'text_field',
			'title' => __( 'Текстовое поле' ),
			'width' => 'full',
			'value' => ! empty( $this->values['text_field'] ) ? $this->values['text_field'] : ''
		] )->get_html();

		//number
		$content .= APF::setup( 'number', [
			'id'         => 'number_field',
			'title'      => __( 'Число' ),
			'width'      => 'full',
			'value_min'  => 0,
			'value_max'  => 10,
			'value_step' => 0.1,
			'value'      => ! empty( $this->values['number_field'] ) ? $this->values['number_field'] : 0
		] )->get_html();
		$content .= '</div>';

		//textarea
		$content .= APF::setup( 'textarea', [
			'id'    => 'textarea_field',
			'title' => __( 'Текстовое поле' ),
			'width' => 'full',
			'value' => ! empty( $this->values['textarea_field'] ) ? $this->values['textarea_field'] : ''
		] )->get_html();

		$content .= '<div class="apf-fields apf-fields_flex">';
		//select
		$content .= APF::setup( 'select', [
			'id'          => 'select_field',
			'title'       => __( 'Выбор' ),
			'empty_first' => 'Ничего не выбрано',
			'values'      => [
				1 => 'Один',
				2 => 'Два',
				3 => 'Три',
			],
			'value'       => ! empty( $this->values['select_field'] ) ? $this->values['select_field'] : ''
		] )->get_html();

		//select
		$content .= APF::setup( 'radio', [
			'id'          => 'radio_field',
			'title'       => __( 'Выбор' ),
			'empty_first' => 'Ничего не выбрано',
			'values'      => [
				1 => 'Один',
				2 => 'Два',
				3 => 'Три',
			],
			'value'       => ! empty( $this->values['radio_field'] ) ? $this->values['radio_field'] : ''
		] )->get_html();

		//checkbox
		$content .= APF::setup( 'checkbox', [
			'id'     => 'checkbox_field',
			'title'  => __( 'Чекбоксы' ),
			'values' => [
				1 => 'Один',
				2 => 'Два',
				3 => 'Три',
			],
			'value'  => ! empty( $this->values['checkbox_field'] ) ? $this->values['checkbox_field'] : []
		] )->get_html();
		$content .= '</div>';

		//multi-text
		$content .= APF::setup( 'multi-text', [
			'id'    => 'multitext_field',
			'title' => __( 'Множественные текстовые поля' ),
			'width' => 'half',
			'value' => ! empty( $this->values['multitext_field'] ) ? $this->values['multitext_field'] : 0
		] )->get_html();

		//image
		$content .= APF::setup( 'image', [
			'id'          => 'image_field',
			'title'       => __( 'Изображение' ),
			'output_size' => [ 100, 100 ],
			'value'       => ! empty( $this->values['image_field'] ) ? $this->values['image_field'] : 0
		] )->get_html();

		//editor
		$content .= APF::setup( 'editor', [
			'id'           => 'editor_field',
			'title'        => __( 'Редактор' ),
			'media_button' => 1,
			'tynimce'      => 1,
			'width'        => 'full',
			'value'        => ! empty( $this->values['editor_field'] ) ? $this->values['editor_field'] : ''
		] )->get_html();

		return $content;

	}

	function getChildrenFields( $data, $post ) {

		//select
		$content = APF::setup( 'select', [
			'id'          => 'select_parent',
			'title'       => __( 'Выбор' ),
			'empty_first' => 'Ничего не выбрано',
			'values'      => [
				1 => 'Дочернее поле 1',
				2 => 'Дочернее поле 2',
			],
			'value'       => ! empty( $this->values['select_parent'] ) ? $this->values['select_parent'] : 0
		] )->get_html();

		//text
		$content .= APF::setup( 'text', [
			'id'     => 'child_field1',
			'parent' => [ 'select_parent' => [ 1 ] ],
			'title'  => __( 'Дочернее поле 1' ),
			'width'  => 'full',
			'value'  => ! empty( $this->values['child_field1'] ) ? $this->values['child_field1'] : ''
		] )->get_html();

		//text
		$content .= APF::setup( 'text', [
			'id'     => 'child_field2',
			'parent' => [ 'select_parent' => [ 2 ] ],
			'title'  => __( 'Дочернее поле 2' ),
			'width'  => 'full',
			'value'  => ! empty( $this->values['child_field2'] ) ? $this->values['child_field2'] : ''
		] )->get_html();

		//select
		$content .= APF::setup( 'radio', [
			'id'          => 'radio_parent',
			'title'       => __( 'Выбор' ),
			'empty_first' => 'Ничего не выбрано',
			'values'      => [
				1 => 'Дочернее поле 3',
				2 => 'Дочернее поле 4'
			],
			'value'       => ! empty( $this->values['radio_parent'] ) ? $this->values['radio_parent'] : ''
		] )->get_html();

		//text
		$content .= APF::setup( 'text', [
			'id'     => 'child_field3',
			'parent' => [ 'radio_parent' => [ 1 ] ],
			'title'  => __( 'Дочернее поле 3' ),
			'width'  => 'full',
			'value'  => ! empty( $this->values['child_field3'] ) ? $this->values['child_field3'] : ''
		] )->get_html();

		//text
		$content .= APF::setup( 'text', [
			'id'     => 'child_field4',
			'parent' => [ 'radio_parent' => [ 2 ] ],
			'title'  => __( 'Дочернее поле 4' ),
			'width'  => 'full',
			'value'  => ! empty( $this->values['child_field4'] ) ? $this->values['child_field4'] : ''
		] )->get_html();

		//checkbox
		$content .= APF::setup( 'checkbox', [
			'id'     => 'checkbox_parent',
			'title'  => __( 'Чекбоксы' ),
			'values' => [
				'child_field5' => 'Дочернее поле 5',
				'child_field6' => 'Дочернее поле 6',
				'child_field7' => 'Дочернее поле 7'
			],
			'value'  => ! empty( $this->values['checkbox_parent'] ) ? $this->values['checkbox_parent'] : []
		] )->get_html();

		//text
		$content .= APF::setup( 'text', [
			'id'     => 'child_field5',
			'parent' => [ 'checkbox_parent' => [ 'child_field5' ] ],
			'title'  => __( 'Дочернее поле 5' ),
			'width'  => 'full',
			'value'  => ! empty( $this->values['child_field5'] ) ? $this->values['child_field5'] : ''
		] )->get_html();

		//text
		$content .= APF::setup( 'text', [
			'id'     => 'child_field6',
			'parent' => [ 'checkbox_parent' => [ 'child_field6' ] ],
			'title'  => __( 'Дочернее поле 6' ),
			'width'  => 'full',
			'value'  => ! empty( $this->values['child_field6'] ) ? $this->values['child_field6'] : ''
		] )->get_html();

		//text
		$content .= APF::setup( 'text', [
			'id'     => 'child_field7',
			'parent' => [ 'checkbox_parent' => [ 'child_field7' ] ],
			'title'  => __( 'Дочернее поле 7' ),
			'width'  => 'full',
			'value'  => ! empty( $this->values['child_field7'] ) ? $this->values['child_field7'] : ''
		] )->get_html();

		return $content;

	}

	function getTaxonomyFields( $data, $post ) {

		$content = '<div class="apf-fields apf-fields_flex">';
		//taxonomy select
		$content .= APF::setup( 'taxonomy', [
			'name'  => 'category', //'taxonomy-name',
			'title' => 'Одичное значение',
			'field' => APF::setup( 'select', [
				'id'     => 'taxonomy_single',
				'search' => 1,
				'value'  => ! empty( $this->values['taxonomy_single'] ) ? $this->values['taxonomy_single'] : 0
			] )
		] )->get_html();

		//taxonomy multiselect
		$content .= APF::setup( 'taxonomy', [
			'name'  => 'post_tag', //'taxonomy-name',
			'title' => 'Множественный выбор',
			'field' => APF::setup( 'multiselect', [
				'id'    => 'taxonomy_multi',
				'value' => ! empty( $this->values['taxonomy_multi'] ) ? $this->values['taxonomy_multi'] : []
			] )
		] )->get_html();

		//taxonomy multiselect
		$content .= APF::setup( 'taxonomy', [
			'name'  => 'post_tag', //'taxonomy-name',
			'title' => 'Сортируемые значения',
			'field' => APF::setup( 'multiselect', [
				'id'       => 'taxonomy_sort',
				'sortable' => 1,
				'value'    => ! empty( $this->values['taxonomy_sort'] ) ? $this->values['taxonomy_sort'] : []
			] )
		] )->get_html();
		$content .= '</div>';

		return $content;
	}

	function getRepeaterField( $data, $post ) {
		//Вывод репитера
		return $this->repeater_content( 'repeater_id', [
			'title'   => 'Заголовок репитера',
			'values'  => ! empty( $this->values['repeater_id'] ) ? $this->values['repeater_id'] : [],
			'button'  => 'Добавить еще',
			'item_id' => $post->ID
		] );
	}

	function getSubpagesField( $data, $post ) {

		//Вкладки
		$content = $this->get_subpages( [
			'subpages' => [
				'main'    => [
					'name' => 'Главная страница'
				],
				'news'    => [
					'name' => 'Страница новостей'
				],
				'article' => [
					'name' => 'Страница статей'
				]
			]
		], $post, function ( $id, $data, $post ) {

			$content = '<h4>' . $data['name'] . '</h4>';

			$content .= APF::setup( 'text', [
				'id'         => 'seo_' . $id . '_h1',
				'input_name' => 'seo[' . $id . '][h1]',
				'title'      => __( 'SEO H1' ) . ': ' . $data['name'],
				'width'      => 'full',
				'value'      => ! empty( $this->values['seo'][ $id ]['h1'] ) ? $this->values['seo'][ $id ]['h1'] : ''
			] )->get_html();

			$content .= APF::setup( 'text', [
				'id'         => 'seo_' . $id . '_title',
				'input_name' => 'seo[' . $id . '][title]',
				'title'      => __( 'SEO Title' ) . ': ' . $data['name'],
				'width'      => 'full',
				'value'      => ! empty( $this->values['seo'][ $id ]['title'] ) ? $this->values['seo'][ $id ]['title'] : ''
			] )->get_html();

			//textarea
			$content .= APF::setup( 'editor', [
				'id'         => 'seo_' . $id . '_description',
				'input_name' => 'seo[' . $id . '][description]',
				'title'      => __( 'SEO Description' ) . ': ' . $data['name'],
				'width'      => 'full',
				'value'      => ! empty( $this->values['seo'][ $id ]['description'] ) ? $this->values['seo'][ $id ]['description'] : ''
			] )->get_html();

			return $content;

		} );

		return $content;

	}

	function update( $post_id ) {

		$keys = [
			'text_field',
			'textarea_field',
			'number_field',
			'image_field',
			'editor_field',
			'select_field',
			'checkbox_field',
			'radio_field',
			'multitext_field',
			'taxonomy_single',
			'taxonomy_multi',
			'taxonomy_sort',
			'select_parent',
			'radio_parent',
			'checkbox_parent',
			'child_field1',
			'child_field2',
			'child_field3',
			'child_field4',
			'child_field5',
			'child_field6',
			'child_field7',
			'repeater_id',
			'seo'
		];

		$updateData = [];
		foreach ( $keys as $key ) {

			$value = $_POST[ $key ];

			//данные репитера
			if ( ! empty( self::$groups[ $key ] ) ) {
				$value = $this->repeater( $key )->setup_value( $value );
			}

			$updateData[ $key ] = $value;
		}

		update_post_meta( $post_id, 'meta_values', $updateData );

	}

}
