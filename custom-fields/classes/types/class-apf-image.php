<?php

class APF_Image extends APF_Field {

	public $output_size = [100, 100];

	function __construct( $args ) {

		$args['type'] = 'image';

		parent::__construct( $args );
	}

	function get_input() {

		$image = '';
		if($this->value){
			$image = wp_get_attachment_image( $this->value, $this->output_size );
		}

		$content = '<div class="apf-image" id="' . $this->input_id . '">';
		$content .= '<span class="apf-image__icon" style="width:'.$this->output_size[0].'px;height:'.$this->output_size[1].'px">'.$image.'</span>';
		$content .= '<span class="apf-image__buttons">';
		$content .= '<input type="button" name="" id="' . $this->input_id . '-select" class="button" value="Выбрать"/>';
		$content .= '<input type="button" name="" id="' . $this->input_id . '-remove" class="button" value="Удалить"/>';
		$content .= '</span>';
		$content .= '<input type="hidden" '.$this->get_required().' name="' . $this->input_name . '" id="' . $this->input_id . '-input" value="'.$this->value.'" size="50"/>';
		$content .= '</div>';

		if(wp_doing_ajax()){
			$content .= '<script>qz_image_uploader_init("'.$this->input_id.'");</script>';
		}else{
			$content .= '<script>jQuery(window).on("load", function() {qz_image_uploader_init("'.$this->input_id.'");});</script>';
		}

		return $content;

	}

}
