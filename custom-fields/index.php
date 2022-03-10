<?php

/*
 * version 1.0.0
 */

require_once( __DIR__ . '/classes/class-apf-field.php' );
require_once( __DIR__ . '/classes/types/class-apf-text.php' );
require_once( __DIR__ . '/classes/types/class-apf-textarea.php' );
require_once( __DIR__ . '/classes/types/class-apf-url.php' );
require_once( __DIR__ . '/classes/types/class-apf-radio.php' );
require_once( __DIR__ . '/classes/types/class-apf-checkbox.php' );
require_once( __DIR__ . '/classes/types/class-apf-select.php' );
require_once( __DIR__ . '/classes/types/class-apf-number.php' );
require_once( __DIR__ . '/classes/types/class-apf-hidden.php' );
require_once( __DIR__ . '/classes/types/class-apf-date.php' );
require_once( __DIR__ . '/classes/types/class-apf-editor.php' );
require_once( __DIR__ . '/classes/types/class-apf-custom.php' );
require_once( __DIR__ . '/classes/types/class-apf-select-posts.php' );
require_once( __DIR__ . '/classes/types/class-apf-slider.php' );
require_once( __DIR__ . '/classes/types/class-apf-multi-text.php' );
require_once( __DIR__ . '/classes/types/class-apf-multi-select.php' );
require_once( __DIR__ . '/classes/types/class-apf-file.php' );
require_once( __DIR__ . '/classes/types/class-apf-image.php' );
require_once( __DIR__ . '/classes/types/class-apf-taxonomy.php' );
require_once( __DIR__ . '/classes/types/class-apf-datetime.php' );
require_once( __DIR__ . '/classes/types/class-apf-posts.php' );
require_once 'classes/APF_Repeater.php';

class APF {

	static $types = [
		'posts'        => 'APF_Posts',
		'text'         => 'APF_Text',
		'textarea'     => 'APF_TextArea',
		'url'          => 'APF_Url',
		'radio'        => 'APF_Radio',
		'checkbox'     => 'APF_Checkbox',
		'select'       => 'APF_Select',
		'number'       => 'APF_Number',
		'hidden'       => 'APF_Hidden',
		'date'         => 'APF_Date',
		'editor'       => 'APF_Editor',
		'custom'       => 'APF_Custom',
		'slider'       => 'APF_Slider',
		'select-posts' => 'APF_SelectPosts',
		'multi-text'   => 'APF_MultiText',
		'multiselect'  => 'APF_MultiSelect',
		'file'         => 'APF_File',
		'image'        => 'APF_Image',
		'taxonomy'     => 'APF_Taxonomy',
		'datetime'     => 'APF_DateTime'
	];

	static function get( $field ) {
		return $field;
	}

	static function setup( $type, $args ) {

		if ( isset( self::$types[ $type ] ) ) {
			$className = self::$types[ $type ];

			return new $className( $args );
		}

		return new APF_Field( $args );

	}

}
