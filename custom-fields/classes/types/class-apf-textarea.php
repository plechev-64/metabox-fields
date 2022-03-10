<?php

class APF_TextArea extends APF_Field {

	public $required;
	public $placeholder;
	public $maxlength;

	function __construct( $args ) {

		$args['type'] = 'textarea';

		parent::__construct( $args );
	}

	function get_input() {
		return '<textarea name="' . $this->input_name . '" ' . $this->get_disabled() . ' ' . $this->get_maxlength() . ' ' . $this->get_required() . ' ' . $this->get_placeholder() . ' id="' . $this->input_id . '" rows="5" cols="50">' . esc_textarea(wp_unslash($this->value)) . '</textarea>';
	}

}
