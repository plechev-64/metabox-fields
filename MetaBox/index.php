<?php

add_action('wp_ajax_apf_load_new_fields_group', 'apf_load_new_fields_group', 10);
function apf_load_new_fields_group()
{
    global $repeaters;

    $group_id = $_POST['group_id'];
    $metabox_id = $_POST['metabox_id'];
    $counter = intval($_POST['counter']);
    $post_id = intval($_POST['post_id']);

    if(!isset($repeaters[$metabox_id])) {
        wp_send_json([
            'error' => __('Не найден метабокс!')
        ]);
    }

    $metabox = $repeaters[$metabox_id];

    $content = $metabox->repeater($group_id)->get_row_content($post_id, ++$counter, true);

    wp_send_json([
        'content' => $content
    ]);

}
