<?php

class APF_Hidden extends APF_Field {

	public $values = array();

	function __construct( $args ) {

		$args['type'] = 'hidden';

		parent::__construct( $args );
	}

	function get_html($more = '') {

		if ( $this->values && is_array( $this->values ) ) {

			$content = '';
			foreach ( $this->values as $value ) {
				$content .= '<input type="' . $this->type . '" name="' . $this->input_name . '[]" value=\'' . $value . '\'/>';
			}

			return $content;
		}

		return '<input type="' . $this->type . '" ' . $this->get_pattern() . ' ' . $this->get_maxlength() . ' ' . $this->get_required() . ' ' . $this->get_placeholder() . ' name="' . $this->input_name . '" id="' . $this->input_id . '" value=\'' . $this->value . '\'/>';
	}

}
