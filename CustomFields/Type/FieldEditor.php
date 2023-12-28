<?php

namespace Core\Module\CustomFields\Type;

use Core\Module\CustomFields\FieldAbstract;

class FieldEditor extends FieldAbstract
{
    public $tinymce;
    public $editor_id;
    public $quicktags;
    public $media_button;

    public function __construct($args)
    {

        $args['type'] = 'editor';

        parent::__construct($args);

    }

    public function get_input()
    {

        $editor_id = $this->editor_id ? $this->editor_id : $this->id;

        $data = array( 'wpautop'		 => 1
            , 'media_buttons'	 => $this->media_button
            , 'textarea_name'	 => $this->input_name
            , 'textarea_rows'	 => 10
            , 'tabindex'		 => null
            , 'editor_css'	 => ''
            , 'editor_class'	 => 'autosave'
            , 'teeny'			 => 0
            , 'dfw'			 => 0
            , 'tinymce'		 => $this->tinymce ? true : false
            , 'quicktags'		 => $this->quicktags ? array( 'buttons' => $this->quicktags ) : true
        );

        ob_start();

        wp_editor($this->value, $editor_id, $data);

        $content = ob_get_contents();

        ob_end_clean();

        return $content;
    }

}
