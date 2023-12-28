<?php

namespace Core\Module\CustomFields\Type;

use Core\Module\CustomFields\FieldAbstract;

class FieldUrl extends FieldAbstract
{
    public $placeholder;
    public $maxlength;
    public $pattern;

    public function __construct($args)
    {

        $args['type'] = 'url';

        parent::__construct($args);
    }

    public function get_input()
    {
        return '<input type="url" ' . $this->get_pattern() . ' ' . $this->get_maxlength() . ' ' . $this->get_required() . ' ' . $this->get_placeholder() . ' name="' . $this->input_name . '" id="' . $this->input_id . '" value=\'' . $this->value . '\'/>';
    }

}
