<?php

namespace Core\Module\CustomFields\Type;

use Core\Module\CustomFields\FieldAbstract;

class FieldTextarea extends FieldAbstract
{
    public $required;
    public $placeholder;
    public $maxlength;

    public function __construct($args)
    {

        $args['type'] = 'textarea';

        parent::__construct($args);
    }

    public function get_input()
    {
        return '<textarea name="' . $this->input_name . '" ' . $this->get_disabled() . ' ' . $this->get_maxlength() . ' ' . $this->get_required() . ' ' . $this->get_placeholder() . ' id="' . $this->input_id . '" rows="5" cols="50">' . esc_textarea(wp_unslash($this->value)) . '</textarea>';
    }

}
