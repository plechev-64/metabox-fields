<?php

namespace Core\Module\CustomFields\Type;

use Core\Module\CustomFields\FieldAbstract;

class FieldSlider extends FieldAbstract
{
    public $value_min	 = 0;
    public $value_max	 = 100;
    public $value_step	 = 1;
    public $unit;

    public function __construct($args)
    {

        $args['type'] = 'slider';

        parent::__construct($args);
    }

    public function get_input()
    {

        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-slider');
        wp_enqueue_script('jquery-touch-punch');

        $content = '<div id="apf-slider-' . $this->rand . '" class="apf-slider apf-slider-' . $this->rand . '">';

        $content .= '<span class="apf-slider__value"><span></span>';
        if ($this->unit) {
            $content .= ' ' . $this->unit;
        }
        $content .= '</span>';

        $content .= '<div class="apf-slider__box"></div>';
        $content .= '<input type="hidden" class="apf-slider__field" id="' . $this->input_id . '" data-idrand="' . $this->rand . '" name="' . $this->input_name . '" value="' . $this->value_min . '">';
        $content .= '</div>';

        $content .= '<script>jQuery(window).on("load", function() {apf_init_slider({id: '.$this->rand.',value: '.($this->value ? $this->value : 0).',min: '.$this->value_min.',max: '.$this->value_max.',step: '.$this->value_step.'});});</script>';

        return $content;
    }


}
