<?php

namespace Core\Module\CustomFields\Type;

use Core\Module\CustomFields\FieldAbstract;

class FieldCustom extends FieldAbstract
{
    public $content;

    public function __construct($args)
    {

        $args['type'] = 'custom';

        parent::__construct($args);
    }

    public function get_input()
    {
        return $this->content ?: false;
    }

}
