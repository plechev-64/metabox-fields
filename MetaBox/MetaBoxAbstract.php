<?php

namespace Core\Module\MetaBox;

class MetaBoxAbstract
{
    public $id = '';
    public static $groups = [];

    public function initRepeater($id, $group)
    {
        global $repeaters;

        if(!isset($repeaters[$this->id])) {
            $repeaters[$this->id] = $this;
        }

        $group->metabox_id = $this->id;
        $group->id = $id;
        self::$groups[$id] = $group;
    }

    public function repeater($id)
    {

        if(empty(self::$groups[$id])) {
            return false;
        }

        return self::$groups[$id];

    }

    public function repeaterContent($id, $args = []): string
    {

        $args = wp_parse_args($args, [
            'title' => '',
            'notice' => '',
            'values' => [],
            'button' => 'Добавить еще',
            'item_id' => 0
        ]);

        $content = '<div class="apf-fields">';

        if(!empty($args['title'])) {
            $content .= '<h3 class="apf-field__title">'.$args['title'].'</h3>';
        }

        if(!empty($args['notice'])) {
            $content .= '<small class="apf-field__notice">'.$args['notice'].'</small>';
        }

        $content .= $this->repeater($id)->get_content($args['values'], $args['item_id'], $args['button']);
        $content .= '</div>';

        return $content;

    }

    public function getSubpages($subpagesData, $item, $callback): string
    {

        $content = '<div class="apf-fields apf-wrapper">';

        $content .= '<div class="apf-fields__menu">';
        $i = 0;
        foreach ($subpagesData['subpages'] as $id => $data) {
            $content .= '<input type="button" data-id="' . $id . '" class="apf-fields__menu-button button' . (!empty($subpagesData['active']) && $subpagesData['active'] == $id ? ' active' : (empty($subpagesData['active']) && !$i ? ' active' : '')) . '" onclick="APFSubPages.menuClick(this);" value="' . $data['name'] . '" />';
            $i++;
        }
        $content .= '</div>';

        $content .= '<div class="apf-fields__subpages">';

        if(!empty($subpagesData['notice'])) {
            $content .= $subpagesData['notice'];
        }

        $i = 0;
        foreach ($subpagesData['subpages'] as $id => $data) {

            $content .= '<div class="apf-fields apf-fields__subpage' . (!empty($subpagesData['active']) && $subpagesData['active'] == $id ? ' active' : (empty($subpagesData['active']) && !$i ? ' active' : '')) . '" data-id="' . $id . '">';
            $content .= $callback($id, $data, $item);
            $content .= '</div>';

            $i++;

        }

        $content .= '</div>';

        $content .= '</div>';

        return $content;

    }

    public function getNumberValues(int $from, int $to, float $step = 1): array
    {
        $values = [];
        for($i = $from; $i <= $to; $i += $step) {
            $values[(string) $i] = $i;
        }
        return $values;
    }

}
