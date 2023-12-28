<?php

namespace Core\Module\CustomFields\Type;

use Core\Module\CustomFields\FieldAbstract;

class FieldImage extends FieldAbstract
{
    public $output_size = [100, 100];

    public function __construct($args)
    {

        $args['type'] = 'image';

        parent::__construct($args);
    }

    public function get_input()
    {

        $image = '';
        if($this->value) {
            $image = wp_get_attachment_image($this->value, $this->output_size);
        }

        $content = '<div class="apf-image" id="' . $this->input_id . '">';
        $content .= '<div class="apf-image__icon" style="max-width: '.$this->output_size[0].'px;">'.$image.'</div>';
        $content .= '<span class="apf-image__buttons">';
        $content .= '<input type="button" name="" id="' . $this->input_id . '-select" class="button" value="Выбрать"/>';
        $content .= '<input type="button" name="" id="' . $this->input_id . '-remove" class="button" value="Удалить"/>';
        $content .= '</span>';
        $content .= '<input type="hidden" '.$this->get_required().' name="' . $this->input_name . '" id="' . $this->input_id . '-input" value="'.$this->value.'" size="50"/>';
        $content .= '</div>';

        if(wp_doing_ajax()) {
            $content .= '<script>apf_image_uploader_init("'.$this->input_id.'");</script>';
        } else {
            $content .= '<script>jQuery(window).on("load", function() {apf_image_uploader_init("'.$this->input_id.'");});</script>';
        }

        return $content;

    }

}
