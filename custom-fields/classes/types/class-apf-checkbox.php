<?php

class APF_Checkbox extends APF_Field {

	public $values;
	public $display		 = 'inline';
	public $value_in_key;

	function __construct( $args ) {

		$args['type'] = 'checkbox';

		parent::__construct( $args );
	}

	function get_input() {

		if ( ! $this->values )
			return false;

		$currentValues = (is_array( $this->value )) ? $this->value : array();

		$content = '';

		foreach ( $this->values as $k => $value ) {

			if ( $this->value_in_key )
				$k = $value;

			$checked = checked( in_array( $k, $currentValues ), true, false );

			$content .= '<span class="apf-checkbox apf-checkbox_' . $this->display . '">';
			$content .= '<input ' . $this->get_required() . ' ' . $checked . ' id="' . $this->input_id . '_' . $k . $this->rand . '" data-id="' . $this->input_id . '" type="checkbox" name="' . $this->input_name . '[]" value="' . trim( $k ) . '"> ';
			$content .= '<label class="apf-checkbox__label" for="' . $this->input_id . '_' . $k . $this->rand . '">';
			$content .= $value;
			$content .= '</label>';
			$content .= '</span>';
		}

		return $content;
	}

}
