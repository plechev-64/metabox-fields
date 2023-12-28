<?php

namespace Core\Module\CustomFields\Type;

use Core\Module\CustomFields\FieldAbstract;
use Core\Module\CustomFields\Field;

class FieldTaxonomy extends FieldAbstract
{
    public $name;
    public $field;

    public function __construct($args)
    {

        $args['type'] = 'taxonomy';

        parent::__construct($args);
    }

    public function get_input()
    {

        $terms   = get_terms([
            'taxonomy'   => $this->name,
            'hide_empty' => false
        ]);

        $values = [];
        foreach ($terms as $term) {
            $values[ $term->term_id ] = $term->name;
        }

        if(is_array($this->field)) {

            if($this->value) {
                $this->field['value'] = $this->value;
            }

            if($this->id) {
                $this->field['id'] = $this->id;
            }

            if($this->input_name) {
                $this->field['input_name'] = $this->input_name;
            }

            $this->field = Field::setup($this->field['type'], $this->field);
        }

        $this->field->set_prop('values', $values);

        return $this->field->get_input();

    }

    public function set_prop($propName, $value)
    {
        $this->field->set_prop($propName, $value);
    }

    public function get_prop($propName)
    {
        return $this->field->get_prop($propName);
    }

}
