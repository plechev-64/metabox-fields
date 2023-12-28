<?php

namespace Core\Module\CustomFields\Type;

use Core\Module\CustomFields\FieldAbstract;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class FieldCheckbox extends FieldAbstract
{
    public $values;
    public $display		 = 'inline';
    public $value_in_key;
    public $multi = true;

    public function __construct($args)
    {

        $args['type'] = 'checkbox';

        parent::__construct($args);
    }

    public function get_input()
    {

        if (!$this->values) {
            return false;
        }

        $currentValues = (is_array($this->value)) ? $this->value : array();

        $content = '';

        $inputName = $this->multi ? $this->input_name . '[]' : $this->input_name;

        foreach ($this->values as $k => $value) {

            if ($this->value_in_key) {
                $k = $value;
            }

            $checked = checked(in_array($k, (array) $currentValues), true, false);

            $content .= '<span class="apf-checkbox apf-checkbox_' . $this->display . '">';
            $content .= '<input ' . $this->get_required() . ' ' . $checked . ' id="' . $this->input_id . '_' . $k . $this->rand . '" data-id="' . $this->input_id . '" type="checkbox" name="' . $inputName . '" value="' . trim($k) . '"> ';
            $content .= '<label class="apf-checkbox__label" for="' . $this->input_id . '_' . $k . $this->rand . '">';
            $content .= $value;
            $content .= '</label>';
            $content .= '</span>';
        }

        return $content;
    }

}
