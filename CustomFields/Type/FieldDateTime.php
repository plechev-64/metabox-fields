<?php

namespace Core\Module\CustomFields\Type;

use Core\Module\CustomFields\FieldAbstract;

class FieldDateTime extends FieldAbstract
{
    public $placeholder;
    public $value_max;
    public $value_min;

    public function __construct($args)
    {

        $args['type'] = 'datetime';

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
        $val = ($this->value) ? date("Y-m-d\TH:i:s", strtotime($this->value)) : '';
        return '<input type="datetime-local" autocomplete="off" ' . $this->get_min() . ' ' . $this->get_max() . ' ' . $this->get_required() . ' ' . $this->get_placeholder() . ' name="' . $this->input_name . '" id="' . $this->input_id . '" value="' . $val . '"/>';
    }


}
