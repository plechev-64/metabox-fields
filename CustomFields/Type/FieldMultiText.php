<?php

namespace Core\Module\CustomFields\Type;

use Core\Module\CustomFields\FieldAbstract;

class FieldMultiText extends FieldAbstract
{
    public $required;
    public $placeholder;

    public function __construct($args)
    {

        $args['type'] = 'multi-text';

        parent::__construct($args);
    }

    public function get_input()
    {

        if (!$this->default) {
            $this->default = '';
        }

        $content = '<span class="multi-text">';

        if ($this->value && is_array($this->value)) {
            $cnt = count($this->value);
            foreach ($this->value as $k => $val) {

                $key = is_string($k) ? $k : '';

                $content .= '<span class="multi-text__value">';
                $content .= '<input type="text" ' . $this->get_required() . ' ' . $this->get_placeholder() . ' name="' . $this->input_name . '[' . $key . ']" value="' . $val . '"/>';
                if (!is_string($k)) {
                    if ($cnt == ++$k) {
                        $content .= '<a href="#" onclick="apf_add_dynamic_field(this);return false;"><i class="rcli fa-plus" aria-hidden="true"></i></a>';
                    } else {
                        $content .= '<a href="#" onclick="apf_remove_dynamic_field(this);return false;"><i class="rcli fa-minus" aria-hidden="true"></i></a>';
                    }
                }
                $content .= '</span>';
            }
        } else {
            $content .= '<span class="multi-text__value">';
            $content .= '<input type="text" ' . $this->get_required() . ' ' . $this->get_placeholder() . ' name="' . $this->input_name . '[]" value="' . $this->default . '"/>';
            $content .= '<a href="#" onclick="apf_add_dynamic_field(this);return false;"><i class="rcli fa-plus" aria-hidden="true"></i></a>';
            $content .= '</span>';
        }

        $content .= '</span>';

        return $content;
    }

}
