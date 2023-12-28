<?php

namespace Core\Module\CustomFields\Type;

use Core\Module\CustomFields\FieldAbstract;

class FieldNumber extends FieldAbstract
{
    public $value_step = 1;
    public $value_max;
    public $value_min;
    public $placeholder;

    public function __construct($args)
    {

        $args['type'] = 'number';

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

    protected function get_step()
    {
        return $this->value_step !== '' ? 'step="' . $this->value_step . '"' : '';
    }

    public function get_input()
    {
        return '<input type="' . $this->type . '" ' . $this->get_disabled() . ' ' . $this->get_step() . ' ' . $this->get_min() . ' ' . $this->get_max() . ' ' . $this->get_required() . ' ' . $this->get_placeholder() . ' name="' . $this->input_name . '" id="' . $this->input_id . '" value=\'' . $this->value . '\'/>';
    }

}
