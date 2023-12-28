<?php

namespace Example;

use Core\Module\CustomFields\Field;
use Core\Module\MetaBox\MetaBox;

class ThumbnailMetaBox extends MetaBox {

	function __construct( $id, $args = [] ) {
		parent::__construct( $id, $args );
	}

	function get_content( $post ) {

		return Field::setup( 'image', [
			'id'          => '_thumbnail_id',
			'output_size' => [ 150, 150 ],
			'required'    => 1,
			'value'       => $post ? get_post_meta( $post->ID, '_thumbnail_id', 1 ) : ''
		] )->get_html();

	}

	function update( $post_id ) {

		$keys = [
			'_thumbnail_id'
		];

		foreach ( $keys as $key ) {

			if ( ! empty( $_POST[ $key ] ) ) {
				update_post_meta( $post_id, $key, $_POST[ $key ] );
			} else {
				delete_post_meta( $post_id, $key );
			}

		}

	}

}
