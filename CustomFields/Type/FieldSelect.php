<?php

namespace Core\Module\CustomFields\Type;

use Core\Module\CustomFields\FieldAbstract;

class FieldSelect extends FieldAbstract
{
    public $empty_first;
    public $values;
    public $search = false;

    public function __construct($args)
    {

        $args['type'] = 'select';

        parent::__construct($args);
    }

    public function get_input()
    {

        if($this->search) {
            wp_enqueue_script('chosen-jquery', APF_URL . '/assets/chosen.min.js');
            wp_enqueue_style('chosen-jquery', APF_URL . '/assets/chosen.min.css');
        }

        $content = '<select ' . $this->get_disabled() . ' ' . $this->get_required() . ' name="' . $this->input_name . '" id="' . $this->input_id . '" '.($this->search ? 'class="chosen-select"' : '').'>';

        if ($this->empty_first) {
            $content .= '<option value="">' . $this->empty_first . '</option>';
        }

        if ($this->values) {
            foreach ($this->values as $k => $value) {
                $content .= '<option ' . selected($this->value, $k, false) . ' value="' . trim($k) . '">' . $value . '</option>';
            }
        }

        $content .= '</select>';

        if($this->search) {
            $content .= '<script>
				jQuery(document).ready(function() {
					jQuery("#'.$this->input_name.'").chosen({
						no_results_text: "Ничего не найдено",
						width: "100%"
					});
				});
			</script>';
        }

        return $content;
    }

}
