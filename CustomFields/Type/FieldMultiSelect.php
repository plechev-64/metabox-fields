<?php

namespace Core\Module\CustomFields\Type;

use Core\Module\CustomFields\FieldAbstract;

class FieldMultiSelect extends FieldAbstract
{
    public $empty_first;
    public $values;
    public $sortable = 0;

    public function __construct($args)
    {

        $args['type'] = 'multiselect';

        parent::__construct($args);
    }

    public function get_input()
    {

        wp_enqueue_script('chosen-jquery', APF_URL . '/assets/chosen.min.js');
        wp_enqueue_style('chosen-jquery', APF_URL . '/assets/chosen.min.css');

        if(empty($this->value) || !is_array($this->value)) {
            $this->value = [];
        }

        $content = '<select ' . $this->get_required('is_required="true"') . ' data-sortable="'.$this->sortable.'" data-placeholder="Выберите вариант" name="' . $this->input_name . '[]" id="' . $this->input_id . '" '.$this->get_input_class().' multiple>';

        if ($this->empty_first) {
            $content .= '<option value="">' . $this->empty_first . '</option>';
        }

        if ($this->values) {

            if($this->sortable && $this->value) {
                foreach ($this->value as $value) {
                    if(empty($this->values[$value])) {
                        continue;
                    }
                    $content .= '<option ' . selected(true, true, false) . ' value="' . trim($value) . '">' . $this->values[$value] . '</option>';
                }
                foreach ($this->values as $k => $value) {
                    if(in_array($k, $this->value)) {
                        continue;
                    }
                    $content .= '<option value="' . trim($k) . '">' . $value . '</option>';
                }
            } else {
                foreach ($this->values as $k => $value) {
                    $content .= '<option ' . selected(in_array($k, $this->value), true, false) . ' value="' . trim($k) . '">' . $value . '</option>';
                }
            }
        }

        $content .= '</select>';

        $content .= '<script>
			jQuery(document).ready(function() {
				jQuery("#'.$this->input_name.'").chosen({
					no_results_text: "Ничего не найдено",
					width: "100%",
					sortable: '.($this->sortable ? 1 : 0).'
				});
			'.(
            $this->sortable ? 'jQuery("#'.$this->input_name.'").attr("name", "");
				jQuery( "#'.$this->input_name.'_chosen ul" ).sortable( {
					connectWith: "#'.$this->input_name.'_chosen ul",
					cursor: "move",
					placeholder: "ui-sortable-placeholder",
					distance: 15,
				} );' : ''
        ).'
			});
		</script>';

        if($this->sortable) {
            wp_enqueue_script('jquery');
            wp_enqueue_script('jquery-ui-sortable');
        }

        return $content;
    }

}
