<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;

class Redvolver_Metabox {

	private $prefix = '_rv_';

	public function __construct() {
		add_action( 'carbon_register_fields', array( $this, 'carbonfields' ) );
        // Set our title
        //$this->title = __( 'Redvolver Options', 'redvolver' );
    }

    public function carbonfields() {

    	$rv_enabled_on = get_option('rv_enabled_on');
    	if ($rv_enabled_on) {

    		Container::make('post_meta', 'Redvolver Bubble')
    			->show_on_post_type($rv_enabled_on)
    			->set_context('normal')
    			->set_priority('default')
    			->add_fields(array(
		        	Field::make("checkbox", $this->prefix . 'enabled', __( 'Enable Bubble', 'redvolver' )),
		        	Field::make("checkbox", $this->prefix . 'custom_layout', __( 'Custom Layout', 'redvolver' ))
		        	->set_conditional_logic(array(
				        'relation' => 'AND',
				        array(
				            'field' => $this->prefix . 'enabled',
				            'value' => 'yes',
				            'compare' => '=',
				        )
				    )),
				    Field::make("select", $this->prefix . 'layout', __( 'Layout', 'redvolver' ))
				    ->set_options(array( $this, 'get_layout' ))
		        	->set_conditional_logic(array(
				        'relation' => 'AND',
				        array(
				            'field' => $this->prefix . 'custom_layout',
				            'value' => 'yes',
				            'compare' => '=',
				        )
				    )),
				    
		    	));
    	}
    }

    public function get_layout() {
    	$layout_array[] = '';
    	$layouts = carbon_get_theme_option('rv_layouts','complex');
    	if ($layouts) {
	    	foreach ($layouts as $layout) {
	    		if ( !empty($layout['rv_layout_title']) ) {
	    			$layout_array[] = $layout['rv_layout_title'];
	    		}
	    	}
	    }
    	return $layout_array;
    }

}

new Redvolver_Metabox;