<?php


class TermsMetaBox extends MetaBox {

	function __construct( $id, $args = [] ) {
		parent::__construct( $id, $args );
	}

	function get_content( $term ) {

		return $this->get_taxonomy_table( [
			APF::setup( 'text', [
				'id'    => 'text_value',
				'width' => 'full',
				'title' => __( 'Текстовое поле' ),
				'value' => !empty($term->term_id)? get_term_meta( $term->term_id, 'text_value', 1 ): ''
			] ),
			APF::setup( 'textarea', [
				'id'    => 'textarea_value',
				'width' => 'full',
				'title' => __( 'Текстовое поле' ),
				'value' => !empty($term->term_id)? get_term_meta( $term->term_id, 'textarea_value', 1 ): ''
			] ),
			APF::setup( 'checkbox', [
				'id'    => 'checkbox_value',
				'width' => 'full',
				'title' => __( 'Чекбоксы' ),
				'values'      => [
					1 => 'Один',
					2 => 'Два',
					3 => 'Три',
				],
				'value' => !empty($term->term_id)? get_term_meta( $term->term_id, 'checkbox_value', 1 ): []
			] )
		] );

	}

	function update( $term_id ) {

		$keys = [
			'text_value',
			'textarea_value',
			'checkbox_value',
		];

		foreach ( $keys as $key ) {

			if ( ! empty( $_POST[ $key ] ) ) {
				update_term_meta( $term_id, $key, $_POST[ $key ] );
			} else {
				delete_term_meta( $term_id, $key );
			}

		}

	}

}