<?php
return array(
	'title'      => 'Immigro Setting',
	'id'         => 'immigro_meta',
	'icon'       => 'el el-cogs',
	'position'   => 'normal',
	'priority'   => 'core',
	'post_types' => array( 'page', 'post' ),
	'sections'   => array(
		require_once IMMIGROPLUGIN_PLUGIN_PATH . '/metabox/header.php',
		require_once IMMIGROPLUGIN_PLUGIN_PATH . '/metabox/banner.php',
		require_once IMMIGROPLUGIN_PLUGIN_PATH . '/metabox/sidebar.php',
		require_once IMMIGROPLUGIN_PLUGIN_PATH . '/metabox/footer.php',
	),
);