<?php

namespace Core\Module\MetaBox;

class AdminPage extends MetaBoxAbstract
{
    public $title = '';
    public $counter = null;
    public $right = '';
    public $submenu = '';
    public $template = '';
	public $priority = '';

    public function __construct($id, $args = [])
    {

        $args = wp_parse_args($args, [
            'title'   => '',
            'right'   => 'manage_options',
            'submenu' => '',
            'counter' => null,
            'template' => 'pageContent',
            'priority' => 10,
        ]);

        $this->id      = $id;
        $this->title   = $args['title'];
        $this->right   = $args['right'];
        $this->submenu = $args['submenu'];
        $this->counter = $args['counter'];
        $this->template = $args['template'];
	    $this->priority = $args['priority'];

        add_action('admin_menu', [ $this, 'initPage' ], $this->priority);
        add_action('admin_init', [ $this, 'updateData' ], $this->priority);

    }

    public function initScripts()
    {
        wp_enqueue_style('apl-fields', APF_URL . '/assets/style.css', false, time());
        wp_enqueue_script('apl-fields', APF_URL . '/assets/scripts.js', false, time());
    }

    public function getChosenScripts(): string
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

    public function initPage()
    {
        if ($this->submenu) {
            add_submenu_page($this->submenu, $this->title, $this->title, $this->right, $this->id, [
                $this,
                $this->template
            ]);
        } else {

            $menuLabel = $this->title;
            if($this->counter && is_callable($this->counter)) {
                $counterCallable = $this->counter;
                $cnt = $counterCallable();
                if($cnt) {
                    $menuLabel .= " <span class='update-plugins count-1'><span class='update-count'>$cnt </span></span>";
                }
            }

            add_menu_page($this->title, $menuLabel, $this->right, $this->id, [ $this, $this->template ]);
        }
    }

    public function getFieldsContent($fields): string
    {
        $content = '';
        foreach ($fields as $field) {
            $content .= $field->getHtml();
        }

        return $content;
    }

    public function pageContent()
    {

        $this->initScripts();

        $content = '<div class="wrap pkr-page">';
        $content .= '<h1>' . $this->title . '</h1>';

        $content .= '<div class="postbox" style="padding: 20px;margin:20px 0;">';
        $content .= $this->getContent();
        $content .= '</div>';

        $content .= '</div>';
        echo $content;
    }

    public function pageContentLK()
    {
        $this->initScripts();
        echo $this->getContent();
    }

    public function getContent(): string
    {
        return $this->getFormContent();
    }

    public function getFormContent(): string
    {
        $content = '<form method="post">';
        $content .= $this->getForm();
        $content .= $this->getNonceField();
        $content .= '<div class="submit-wrap">' . get_submit_button() . '</div>';
        $content .= '</form>';
        return $content;
    }

    public function getForm()
    {

    }

    public function getNonceField()
    {
        return '<input type="hidden" name="' . $this->id . '_nonce" value="' . wp_create_nonce(__FILE__) . '" />';
    }

    public function updateData()
    {

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return false;
        }

        if (!isset($_POST[ $this->id . '_nonce' ]) || !wp_verify_nonce($_POST[ $this->id . '_nonce' ], __FILE__)) {
            return false;
        }

        if (!current_user_can($this->right)) {
            return false;
        }

        $this->update();

    }

    public function update()
    {

    }

}
