<?php

namespace Core\Module\CustomFields\Type;

use Core\Module\CustomFields\FieldAbstract;

class FieldFile extends FieldAbstract
{
    public $output_size = [100, 100];

    public function __construct($args)
    {

        $args['type'] = 'file';

        parent::__construct($args);
    }

    public function get_input()
    {

        $icon = '';
        $src = '';
        if($this->value) {
            $icon = wp_get_attachment_image($this->value, $this->output_size, true);
            $src = wp_get_attachment_url($this->value);
        }

        $content = '<div class="apf-file" id="' . $this->input_id . '">';
        $content .= '<span class="apf-file__icon">'.$icon.'</span>';
        if($src) {
            $content .= '<span class="apf-file__url"><a target="_blank" href="'.$src.'">Ссылка на файл</a></span>';
        }
        $content .= '<span class="apf-file__buttons">';
        $content .= '<input type="button" name="" id="' . $this->input_id . '-select" class="button" value="Выбрать"/>';
        $content .= '<input type="button" name="" id="' . $this->input_id . '-remove" class="button" value="Удалить"/>';
        $content .= '</span>';
        $content .= '<input type="hidden" name="' . $this->input_name . '" id="' . $this->input_id . '-input" value="'.$this->value.'" size="50"/>';
        $content .= '</div>';

        if(wp_doing_ajax()) {
            $content .= '<script>apf_file_uploader_init("'.$this->input_id.'");</script>';
        } else {
            $content .= '<script>jQuery(window).on("load", function() {apf_file_uploader_init("'.$this->input_id.'");});</script>';
        }

        return $content;

    }

}
