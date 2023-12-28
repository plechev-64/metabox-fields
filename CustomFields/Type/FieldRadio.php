<?php

namespace Core\Module\CustomFields\Type;

use Core\Module\CustomFields\FieldAbstract;

class FieldRadio extends FieldAbstract
{
    public $required;
    public $values;
    public $display_items = 'inline';
    public $empty_first;
    public $empty_value;
    public $value_in_key;

    public function __construct($args)
    {

        $args['type'] = 'radio';

        parent::__construct($args);
    }

    public function get_input()
    {

        if (!$this->values) {
            return false;
        }

        $content = '';

        if ($this->empty_first) {
            $content .= '<span class="apf-radio apf-radio_' . $this->display_items . '">';
            $content .= '<input type="radio" ' . $this->get_required() . ' ' . checked($this->value, '', false) . ' data-id="' . $this->input_id . '" id="' . $this->input_id . '_' . $this->rand . '" name="' . $this->input_name . '" value="' . $this->empty_value . '"> ';
            $content .= '<label for="' . $this->input_id . '_' . $this->rand . '">' . $this->empty_first . '</label>';
            $content .= '</span>';
        }

        $a = 0;

        if (!$this->empty_first && !$this->value) {
            $this->value = ($this->value_in_key) ? $this->values[0] : 0;
        }

        foreach ($this->values as $k => $value) {

            $k = trim($k);

            $content .= '<span class="apf-radio apf-radio_' . $this->display_items . '" data-value="' . $k . '">';
            $content .= '<input type="radio" ' . $this->get_required() . ' ' . checked($this->value, $k, false) . ' data-id="' . $this->input_id . '" id="' . $this->input_id . '_' . $k . $this->rand . '" name="' . $this->input_name . '" value="' . $k . '"> ';
            $content .= '<label class="apf-radio__label" for="' . $this->input_id . '_' . $k . $this->rand . '">' . $value . '</label>';
            $content .= '</span>';

            $a++;
        }

        return $content;
    }

}
