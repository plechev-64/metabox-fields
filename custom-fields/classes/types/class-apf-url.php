<?php

class APF_Url extends APF_Field {

	public $placeholder;
	public $maxlength;
	public $pattern;

	function __construct( $args ) {

		$args['type'] = 'url';

		parent::__construct( $args );
	}

	function get_input() {
		return '<input type="url" ' . $this->get_pattern() . ' ' . $this->get_maxlength() . ' ' . $this->get_required() . ' ' . $this->get_placeholder() . ' name="' . $this->input_name . '" id="' . $this->input_id . '" value=\'' . $this->value . '\'/>';
	}

}
