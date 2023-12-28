<?php

namespace Core\Module\CustomFields;

use Core\Module\MetaBox\MetaBox;

class Repeater
{
    public $id = '';
    public $metabox_id = '';
    public $fields = [];

    public function __construct($fields)
    {
        $this->fields = $fields;
    }

    public function pre_setup_fields($post_id = 0)
    {
        //
    }

    public function setup_field($field, $prefix, $rand, $values)
    {

        $field->set_prop('input_name', $this->id . '[' . $field->get_prop('id') . '][' . $rand . ']');
        $field->set_prop('input_id', $prefix . $field->id);

        if ($values) {
            $field->set_prop('value', $values[ $field->get_prop('id') ]);
        }

        return $field;

    }

    public function setup_fields($post_id, $counter = 0, $values = false, $force = false)
    {

        if (!$values && !$force) {
            return false;
        }

        $this->pre_setup_fields($post_id);

        $rand = rand(100, 100000);

        foreach ($this->fields as $field) {
            $this->setup_field($field, $this->id . '_' . $counter . '_', $rand, $values);
        }

        return true;

    }

    public function get_row_content($post_id, $counter = 0, $value = false, $force = false)
    {

        if (!$this->setup_fields($post_id, $counter, $value, $force)) {
            return false;
        }

        $content = '<div class="apf-fields-group">';

        $content .= '<div class="apf-fields apf-fields_flex">';
        $content .= MetaBox::getFieldsBox($this->fields);
        $content .= '</div>';

        $content .= '<div class="submit-wrap">';
        $content .= '<a href="#" class="button secondary" onclick="apf_remove_fields_group(this); return false;">' . __('Удалить') . '</a>';
        $content .= '</div>';

        $content .= '</div>';

        return $content;

    }

    public function get_content($values, $post_id = 0, $submit = 'Добавить')
    {

        $content = '<div id="' . $this->id . '" class="apf-groups apf-field">';
        if ($value = !empty($values) && is_array($values) ? $values : []) {
            foreach ($value as $k => $pair) {
                $content .= $this->get_row_content($post_id, $k, $pair);
            }
        }

        $content .= '</div>';

        $content .= '<div class="submit-wrap" style="text-align:right;">';
        $content .= '<a href="#" class="button-primary" onclick="apf_load_new_fields_group(\'' . $this->id . '\', \'' . $this->metabox_id . '\', ' . $post_id . '); return false;">' . $submit . '</a>';
        $content .= '</div>';

        return $content;

    }

    public function setup_value($value)
    {

        $faqsData = [];

        if(!$value) {
            return $faqsData;
        }

        foreach ($value as $nameKey => $data) {
            foreach ($data as $k => $val) {
                $faqsData[ $k ][ $nameKey ] = $val;
            }
        }

        if (!$faqsData) {
            return $faqsData;
        }

        $exchangeCoins = [];
        foreach ($faqsData as $faq) {
            $exchangeCoins[] = $faq;
        }

        return $exchangeCoins;

    }

}
