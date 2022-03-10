<?php

class APF_Text extends APF_Field {

	public $placeholder;
	public $maxlength;
	public $pattern;

	function __construct( $args ) {

		$args['type'] = 'text';

		parent::__construct( $args );
	}

	function get_input() {
		return '<input type="text" ' . $this->get_disabled() . ' ' . $this->get_pattern() . ' ' . $this->get_maxlength() . ' ' . $this->get_required() . ' ' . $this->get_placeholder() . ' name="' . $this->input_name . '" id="' . $this->input_id . '" value=\'' . esc_textarea(wp_unslash($this->value)) . '\'/>';
	}

}
