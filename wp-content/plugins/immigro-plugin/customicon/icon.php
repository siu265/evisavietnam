<?php


class Icon
{

	public function __construct()
	{
		add_filter('elementor/icons_manager/additional_tabs', array($this, 'custom_icon'));
	}

	function custom_icon($array)
	{
		$plugin_url = plugins_url();

		return array(
			'custom-icon' => array(
				'name'          => 'custom-icon',
				'label'         => 'Theme Icon',
				'url'           => '',
				'enqueue'       => array(
					plugin_dir_url( __FILE__ ) . '/icomoon.css',
				),

				'prefix'        => '',
				'displayPrefix' => '',
				'labelIcon'     => 'custom-icon',
				'ver'           => '',
				'fetchJson'     => plugin_dir_url( __FILE__ ) .  '/icon.json',
				'native'        => 1,
			),
		);
	}
}


new Icon();








