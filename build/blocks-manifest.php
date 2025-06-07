<?php
// This file is generated. Do not modify it manually.
return array(
	'openprompt' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'gen-ai/delsaprompt',
		'version' => '0.1.0',
		'title' => 'delsaprompt',
		'category' => 'widgets',
		'icon' => 'smiley',
		'description' => 'Example block scaffolded with Create Block tool.',
		'example' => array(
			
		),
		'supports' => array(
			'html' => false
		),
		'attributes' => array(
			'prompt' => array(
				'type' => 'string',
				'default' => ''
			),
			'response' => array(
				'type' => 'string',
				'default' => ''
			),
			'model' => array(
				'type' => 'string',
				'default' => 'gpt-3.5-turbo'
			)
		),
		'textdomain' => 'delsaprompt',
		'editorScript' => 'file:./index.js',
		'editorStyle' => 'file:./index.css',
		'style' => 'file:./style-index.css'
	)
);
