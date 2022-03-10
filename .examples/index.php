<?php

require_once 'SettingsPage.php';
require_once 'PostMetaBox.php';
require_once 'ThumbnailMetaBox.php';
require_once 'TermsMetaBox.php';

new SettingsPage( 'general-settings', [
	'title' => 'Настройки'
] );

new PostMetaBox( 'post-meta-box-id', [
	'post_types' => [ 'post', 'page' ],
	'title'      => 'Основные настройки'
] );

new ThumbnailMetaBox( 'thumbnail-meta-box', [
	'post_types' => [ 'post' ],
	'context'    => 'side',
	'title'      => 'Миниатюра'
] );

new TermsMetaBox( 'terms-meta-box', [
	'taxonomies' => [ 'category', 'post_tag' ],
	'title'      => __( 'Основные настройки' )
] );
