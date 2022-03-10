<?php

class APF_Custom extends APF_Field {

	public $content;

	function __construct( $args ) {

		$args['type'] = 'custom';

		parent::__construct( $args );
	}

	function get_input() {
		return $this->content ? $this->content : false;
	}

}
