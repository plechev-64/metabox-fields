<?php

namespace Core\Module\MetaBox;

class MetaBox extends MetaBoxAbstract
{
    public $context = '';
    public $priority = '';
    public $title = '';
    public $post_types = [];
    public $post_ids = [];
    public $taxonomies = [];
    public $is_term = false;
    public $is_post = false;

    public function __construct($id, $args = [])
    {

        $args = wp_parse_args($args, [
            'post_types' => [],
            'post_ids'   => [],
            'taxonomies' => [],
            'context'    => 'normal',
            'title'      => '',
            'priority'   => 'default'
        ]);

        $this->id         = $id;
        $this->title      = $args['title'];
        $this->post_types = $args['post_types'];
        $this->context    = $args['context'];
        $this->priority   = $args['priority'];
        $this->taxonomies = $args['taxonomies'];
        $this->post_ids   = $args['post_ids'];

        if ($this->post_types) {

            if (!$this->post_ids || $this->post_ids && isset($_GET['action']) && isset($_GET['post']) && $_GET['action'] == 'edit' && in_array($_GET['post'], $this->post_ids)) {
                add_action('add_meta_boxes', [ $this, 'initPostMetabox' ], 10);
            }

            add_action('save_post', [ $this, 'updateData' ], 1);

        }

        if ($this->taxonomies) {
            add_action('admin_init', [ $this, 'initTaxonomyMetabox' ], 10);
            add_action('create_term', [ $this, 'updateData' ], 10);
            add_action('edit_term', [ $this, 'updateData' ], 10);
        }

    }

    public function initScripts()
    {
        wp_enqueue_style('apl-fields', APF_URL . '/assets/style.css', false, time());
        wp_enqueue_script('apl-fields', APF_URL . '/assets/scripts.js', false, time());
    }

    public function getChosenScripts()
    {

        wp_enqueue_script('chosen-jquery', APF_URL . '/assets/chosen.min.js');
        wp_enqueue_style('chosen-jquery', APF_URL . '/assets/chosen.min.css');

        return '<script>
			jQuery(document).ready(function($) {
				$("select[multiple]").chosen({
					no_results_text: "Ничего не найдено",
					width: "100%",
				});
				$("select.chosen-select").chosen({
					no_results_text: "Ничего не найдено"
				});
			});
		</script>';
    }

    public function initTaxonomyMetabox()
    {

        if (isset($_GET['taxonomy']) && taxonomy_exists($_GET['taxonomy'])) {
            $taxonomy = $_GET['taxonomy'];
        } else {
            return;
        }

        if (is_array($this->taxonomies) && !in_array($taxonomy, $this->taxonomies)) {
            return false;
        }

        $this->is_term = true;

        $this->initScripts();

        add_action($taxonomy . '_add_form_fields', [ $this, 'metaboxContent' ], 10);
        add_action($taxonomy . '_edit_form_fields', [ $this, 'metaboxContent' ], 10);

    }

    public function initPostMetabox($post_type)
    {

        if (is_array($this->post_types) && !in_array($post_type, $this->post_types)) {
            return false;
        }

        $this->is_post = true;

        $this->initScripts();

        add_meta_box($this->id, $this->title, [
            $this,
            'metaboxContent'
        ], $post_type, $this->context, $this->priority);

    }

    public function getNonceInput(): string
    {
        return '<input type="hidden" name="' . $this->id . '_nonce" value="' . wp_create_nonce(__FILE__) . '" />';
    }

    public function verifyUpdate($item_id): bool
    {

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return false;
        }

        if (!isset($_POST[ $this->id . '_nonce' ]) || !wp_verify_nonce($_POST[ $this->id . '_nonce' ], __FILE__)) {
            return false;
        }

        if (isset($_POST['post_type']) && $this->post_types) {

            if (is_array($this->post_types) && !in_array($_POST['post_type'], $this->post_types)) {
                return false;
            }

            if (!current_user_can('edit_post', $item_id)) {
                return false;
            }

            $this->is_post = 1;

        } elseif (isset($_POST['taxonomy']) && $this->taxonomies) {

            if (is_array($this->taxonomies) && !in_array($_POST['taxonomy'], $this->taxonomies)) {
                return false;
            }
            if (!current_user_can('manage_categories')) {
                return false;
            }

            $this->is_term = 1;

        }

        return true;
    }

    public static function getFieldsBox($fields): string
    {
        $content = '';
        foreach ($fields as $field) {
            if (is_array($field)) {
                $childHtml = '';
                foreach ($field as $k => $f) {
                    if (!$k) {
                        continue;
                    }
                    $childHtml .= $f->get_input();
                }
                $content .= $field[0]->get_html($childHtml);
            } else {
                $content .= $field->get_html();
            }
        }

        return $content;
    }

    public function getFieldsContent($fields): string
    {
        $content = '';
        foreach ($fields as $field) {
            if (is_array($field)) {
                $childHtml = '';
                foreach ($field as $k => $f) {
                    if (!$k) {
                        continue;
                    }
                    $childHtml .= $f->get_input();
                }
                $content .= $field[0]->get_html($childHtml);
            } else {
                $content .= $field->get_html();
            }
        }

        return $content;
    }

    public function getTaxonomyTable($fields)
    {

        if (empty($_GET['tag_ID'])) {
            $content = '<h3>' . $this->title . '</h3>';
            $content .= $this->getFieldsContent($fields);
        } else {
            $content = '<table class="form-table" role="presentation">';

            $content .= '<tr>';
            $content .= '<th>' . $this->title . '</th>';
            $content .= '<td>';

            $content .= $this->getFieldsContent($fields);

            $content .= '</td>';
            $content .= '<tr/>';

            $content .= '</table>';
        }

        return $content;

    }

    public function termWrapper($content)
    {
        return '<tr class="form-field"><td colspan="2">' . $content . '</td></tr>';
    }

    public function metaboxContent($itemObject)
    {

        $content = $this->getContent($itemObject);

        $content .= $this->getNonceInput();

        echo $content;

    }

    public function updateData($item_id)
    {

        if (!$this->verifyUpdate($item_id)) {
            return false;
        }

        $this->update($item_id);
    }

    public function getContent($itemObject)
    {

    }

    public function update($item_id)
    {

    }


}
