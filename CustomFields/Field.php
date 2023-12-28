<?php

namespace Core\Module\CustomFields;

use Core\Module\CustomFields\Type\FieldCheckbox;
use Core\Module\CustomFields\Type\FieldCustom;
use Core\Module\CustomFields\Type\FieldDate;
use Core\Module\CustomFields\Type\FieldDateTime;
use Core\Module\CustomFields\Type\FieldEditor;
use Core\Module\CustomFields\Type\FieldFile;
use Core\Module\CustomFields\Type\FieldHidden;
use Core\Module\CustomFields\Type\FieldImage;
use Core\Module\CustomFields\Type\FieldMultiSelect;
use Core\Module\CustomFields\Type\FieldMultiText;
use Core\Module\CustomFields\Type\FieldNumber;
use Core\Module\CustomFields\Type\FieldPosts;
use Core\Module\CustomFields\Type\FieldRadio;
use Core\Module\CustomFields\Type\FieldSelect;
use Core\Module\CustomFields\Type\FieldSlider;
use Core\Module\CustomFields\Type\FieldTaxonomy;
use Core\Module\CustomFields\Type\FieldText;
use Core\Module\CustomFields\Type\FieldTextarea;
use Core\Module\CustomFields\Type\FieldTime;
use Core\Module\CustomFields\Type\FieldUrl;

class Field
{
    public static array $types = [
        'text'        => FieldText::class,
        'textarea'    => FieldTextarea::class,
        'url'         => FieldUrl::class,
        'radio'       => FieldRadio::class,
        'checkbox'    => FieldCheckbox::class,
        'select'      => FieldSelect::class,
        'number'      => FieldNumber::class,
        'hidden'      => FieldHidden::class,
        'date'        => FieldDate::class,
        'editor'      => FieldEditor::class,
        'custom'      => FieldCustom::class,
        'slider'      => FieldSlider::class,
        'posts'       => FieldPosts::class,
        'multi-text'  => FieldMultiText::class,
        'multiselect' => FieldMultiSelect::class,
        'file'        => FieldFile::class,
        'image'       => FieldImage::class,
        'taxonomy'    => FieldTaxonomy::class,
        'datetime'    => FieldDateTime::class,
        'time'        => FieldTime::class,
    ];

    public static function get($field)
    {
        return $field;
    }

    public static function setup($type, $args)
    {

        if (isset(self::$types[ $type ])) {
            $className = self::$types[ $type ];

            return new $className($args);
        }

        return new FieldAbstract($args);

    }

}
