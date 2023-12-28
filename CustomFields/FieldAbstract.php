<?php

namespace Core\Module\CustomFields;

class FieldAbstract
{
    public $id;
    public $type;
    public $title;
    public $value		 = null;
    public $default		 = null;
    public $notice;
    public $input_id;
    public $input_name;
    public $input_class;
    public $rand;
    public $class;
    public $required;
    public $disabled;
    public $width = 'initial';
    public $parent = [];

    public function __construct($args)
    {

        $this->init_properties($args);

        $this->rand = rand(0, 1000);

        if (!$this->input_name) {
            $this->input_name = $this->id;
        }

        if (!$this->input_id) {
            $this->input_id = $this->id;
        }

    }

    public function init_properties($args)
    {

        foreach ($args as $key => $val) {
            $this->$key = $val;
        }

        if (!isset($this->value) && isset($this->default)) {
            $this->value = $this->default;
        }
    }

    public function get_prop($propName)
    {
        return $this->isset_prop($propName) ? $this->$propName : false;
    }

    public function isset_prop($propName)
    {
        return isset($this->$propName);
    }

    public function set_prop($propName, $value)
    {
        $this->$propName = $value;
    }

    public function get_value()
    {
        return !empty($this->value) ? $this->value : false;
    }

    public function get_input()
    {
        return false;
    }

    public function get_html($more = '')
    {

        $dataAttrs = [];
        if($this->parent) {
            foreach($this->parent as $parentKey => $parentValues) {
                $dataAttrs[] = 'data-parent-'.$parentKey.'="'.implode(':', $parentValues).'"';
                //break;
            }
        }

        $content = '<div class="apf-field apf-field_'.$this->type.' '.($this->parent ? 'apf-field_children' : '').'" '.implode(' ', $dataAttrs).'>';

        if($this->title) {
            $content .= '<label class="apf-field__title">' . $this->title . ($this->required ? ' <span style="color:red">*</span>' : '').'</label>';
        }

        $content .= '<div class="apf-field__content apf-field__content_'.$this->width.'">';
        $content .= $this->get_input() . $more;
        $content .= '</div>';

        if($this->notice) {
            $content .= '<small class="apf-field__notice">' . $this->notice . '</small>';
        }

        $content .= '</div>';

        return $content;
    }

    protected function get_required($attr = false)
    {
        return $this->required ? ($attr ?: 'required="required"') : '';
    }

    protected function get_disabled($attr = false)
    {
        return $this->disabled ? ($attr ?: 'disabled="disabled"') : '';
    }

    protected function get_placeholder()
    {
        return !empty($this->placeholder) ? 'placeholder="' . $this->placeholder . '"' : '';
    }

    protected function get_maxlength()
    {
        return !empty($this->maxlength) ? 'maxlength="' . $this->maxlength . '"' : '';
    }

    protected function get_pattern()
    {
        return !empty($this->pattern) ? 'pattern="' . $this->pattern . '"' : '';
    }

    protected function get_input_id()
    {
        return $this->input_id ? 'id="' . $this->input_id . '"' : '';
    }

    protected function get_input_class()
    {
        return $this->input_class ? 'class="' . $this->input_class . '"' : '';
    }


}
