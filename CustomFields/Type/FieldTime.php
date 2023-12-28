<?php

namespace Core\Module\CustomFields\Type;

use Core\Module\CustomFields\FieldAbstract;

class FieldTime extends FieldAbstract
{
    public $placeholder;
    public $value_max;
    public $value_min;

    public function __construct($args)
    {

        $args['type'] = 'time';

        parent::__construct($args);
    }

    protected function get_min()
    {
        return $this->value_min !== '' ? 'min="' . $this->value_min . '"' : '';
    }

    protected function get_max()
    {
        return $this->value_max !== '' ? 'max="' . $this->value_max . '"' : '';
    }

    public function get_input()
    {
        return '<input type="time" autocomplete="off" ' . $this->get_min() . ' ' . $this->get_max() . ' ' . $this->get_required() . ' ' . $this->get_placeholder() . ' name="' . $this->input_name . '" id="' . $this->input_id . '" value="' . ($this->value ?: '00:00') . '"/>';
    }


}
