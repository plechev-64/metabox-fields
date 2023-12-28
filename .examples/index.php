<?php

use Example\PostMetaBox;
use Example\SettingsPage;
use Example\TermsMetaBox;
use Example\ThumbnailMetaBox;

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
