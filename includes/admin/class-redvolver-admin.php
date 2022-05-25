<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;

class Redvolver_Admin {

    private $prefix = 'rv_';

    /**
     * Option key, and option page slug
     * @var string
     */
    private $key = 'redvolver_options';

    /**
     * Options page metabox id
     * @var string
     */
    private $metabox_id = 'redvolver_option_metabox';

    /**
     * Options Page title
     * @var string
     */
    protected $title = '';

    /**
     * Options Page hook
     * @var string
     */
    protected $options_page = '';

    /**
     * Holds an instance of the object
     *
     * @var Redvolver_Admin
     **/
    private static $instance = null;

    /**
     * Constructor
     * @since 0.1.0
     */
    private function __construct() {
        // Set our title
        $this->title = __( 'Redvolver Options', 'redvolver' );
    }

    /**
     * Returns the running object
     *
     * @return Redvolver_Admin
     **/
    public static function get_instance() {
        if( is_null( self::$instance ) ) {
            self::$instance = new self();
            self::$instance->hooks();
            //self::$instance->carbonfields();
        }
        return self::$instance;
    }

    /**
     * Initiate our hooks
     * @since 0.1.0
     */
    public function hooks() {

        //add_action( 'admin_init', array( $this, 'init' ) );
        add_action( 'carbon_register_fields', array( $this, 'carbonfields' ) );
    }

    public function carbonfields() {

        Container::make('theme_options', 'Redbubble')
            ->add_fields(
                array(
                    Field::make("html", $this->prefix . 'title')
                        ->set_html('<h1>Bubble Options</h1>'),
                    Field::make("set", $this->prefix . 'enabled_on', __( 'Enable on', 'redvolver' ))
                        ->set_options(array( $this, 'get_post_types' )),
                    Field::make("text", $this->prefix . 'default_number', __( 'Item numbers', 'redvolver' )),
                    Field::make("select", $this->prefix . 'default_type', __( 'Default Type', 'redvolver' ))
                        ->set_options(array( $this, 'get_default_types' )),
                    Field::make("file", $this->prefix . 'default_image', __( 'Default Image', 'redvolver' ))
                    ->set_conditional_logic(array(
                        'relation' => 'AND', // Optional, defaults to "AND"
                        array(
                            'field' => $this->prefix . 'default_type',
                            'value' => 'image', // Optional, defaults to "". Should be an array if "IN" or "NOT IN" operators are used.
                            'compare' => '=', // Optional, defaults to "=". Available operators: =, <, >, <=, >=, IN, NOT IN
                        )
                    )),
                    Field::make("text", $this->prefix . 'default_font_class', __( 'Default font class', 'redvolver' ))
                    ->set_conditional_logic(array(
                        'relation' => 'AND', // Optional, defaults to "AND"
                        array(
                            'field' => $this->prefix . 'default_type',
                            'value' => 'font', // Optional, defaults to "". Should be an array if "IN" or "NOT IN" operators are used.
                            'compare' => '=', // Optional, defaults to "=". Available operators: =, <, >, <=, >=, IN, NOT IN
                        )
                    )),
                    //Field::make("textarea", $this->prefix . 'default_custom_html', __( 'Default custom Html', 'redvolver' ))
                        //->set_rows(4),
                    Field::make("checkbox", $this->prefix . 'fixed_size', __( 'Fixed Size', 'redvolver' ))
                        ->help_text(__( 'Enable to fix item size to max size', 'redvolver' )),
                    Field::make("text", $this->prefix . 'min_size', __( 'Min Size', 'redvolver' ))
                        ->set_default_value(50),
                    Field::make("text", $this->prefix . 'max_size', __( 'Max Size', 'redvolver' ))
                        ->set_default_value(200),
                    Field::make("checkbox", $this->prefix . 'random_speed', __( 'Random Speed Animation', 'redvolver' ))
                        ->help_text(__( 'Random Speed Animation every item have different random speed', 'redvolver' )),
                    Field::make("select", $this->prefix . 'animation_speed', __( 'Animation Speed', 'redvolver' ))
                        ->set_options(array( $this, 'get_default_speed' ))
                        ->set_default_value(1),
                    Field::make("checkbox", $this->prefix . 'random_opacity', __( 'Random Opacity', 'redvolver' ))
                        ->help_text(__( 'Random Opacity', 'redvolver' )),
                    Field::make("select", $this->prefix . 'default_opacity', __( 'Default Opacity', 'redvolver' ))
                        ->set_options(array( $this, 'get_default_opacity' ))
                        ->set_default_value(0.5),
                    Field::make("checkbox", $this->prefix . 'random_colors', __( 'Random Colors', 'redvolver' ))
                        ->help_text(__( 'Random Colors if enabled override custom colors', 'redvolver' )),
                    Field::make('complex', $this->prefix . 'colors',__( 'Custom Colors', 'redvolver' ))
                        ->set_layout('tabbed-horizontal')
                        ->add_fields(array(
                            Field::make("color", $this->prefix . 'color', __( 'Custom Color', 'redvolver' ))
                        )),
                )
        );

        // Add second options page under 'Basic Options'
        Container::make('theme_options', 'Bubble Layouts')
            ->set_page_parent('Redbubble')  // title of a top level Theme Options page
            ->add_fields(
                array(
                    Field::make('complex', $this->prefix.'layouts','Layouts')
                    ->set_layout('tabbed-horizontal')
                    ->add_fields(array(
                        Field::make('text', $this->prefix . 'layout_title', __( 'Layout Title', 'redvolver' ))->set_required(true),

                        //Field::make("radio", $this->prefix . 'layout_type', __( 'Layout Type', 'redvolver' ))
                            //->set_options(array( $this, 'get_layout_types' )),

                        Field::make("text", $this->prefix . 'default_number', __( 'Item numbers', 'redvolver' )),
                    Field::make("select", $this->prefix . 'default_type', __( 'Default Type', 'redvolver' ))
                        ->set_options(array( $this, 'get_default_types' )),
                    Field::make("file", $this->prefix . 'default_image', __( 'Default Image', 'redvolver' ))
                    ->set_conditional_logic(array(
                        'relation' => 'AND', // Optional, defaults to "AND"
                        array(
                            'field' => $this->prefix . 'default_type',
                            'value' => 'image', // Optional, defaults to "". Should be an array if "IN" or "NOT IN" operators are used.
                            'compare' => '=', // Optional, defaults to "=". Available operators: =, <, >, <=, >=, IN, NOT IN
                        )
                    )),
                    Field::make("text", $this->prefix . 'default_font_class', __( 'Default font class', 'redvolver' ))
                    ->set_conditional_logic(array(
                        'relation' => 'AND', // Optional, defaults to "AND"
                        array(
                            'field' => $this->prefix . 'default_type',
                            'value' => 'font', // Optional, defaults to "". Should be an array if "IN" or "NOT IN" operators are used.
                            'compare' => '=', // Optional, defaults to "=". Available operators: =, <, >, <=, >=, IN, NOT IN
                        )
                    )),
                    //Field::make("textarea", $this->prefix . 'default_custom_html', __( 'Default custom Html', 'redvolver' ))
                        //->set_rows(4),
                    Field::make("checkbox", $this->prefix . 'fixed_size', __( 'Fixed Size', 'redvolver' ))
                        ->help_text(__( 'Enable to fix item size to max size', 'redvolver' )),
                    Field::make("text", $this->prefix . 'min_size', __( 'Min Size', 'redvolver' ))
                        ->set_default_value(50),
                    Field::make("text", $this->prefix . 'max_size', __( 'Max Size', 'redvolver' ))
                        ->set_default_value(200),
                    Field::make("checkbox", $this->prefix . 'random_speed', __( 'Random Speed Animation', 'redvolver' ))
                        ->help_text(__( 'Random Speed Animation every item have different random speed', 'redvolver' )),
                    Field::make("select", $this->prefix . 'animation_speed', __( 'Animation Speed', 'redvolver' ))
                        ->set_options(array( $this, 'get_default_speed' ))
                        ->set_default_value(1),
                    Field::make("checkbox", $this->prefix . 'random_opacity', __( 'Random Opacity', 'redvolver' ))
                        ->help_text(__( 'Random Opacity', 'redvolver' )),
                    Field::make("select", $this->prefix . 'default_opacity', __( 'Default Opacity', 'redvolver' ))
                        ->set_options(array( $this, 'get_default_opacity' ))
                        ->set_default_value(0.5),
                    Field::make("checkbox", $this->prefix . 'random_colors', __( 'Random Colors', 'redvolver' ))
                        ->help_text(__( 'Random Colors if enabled override custom colors', 'redvolver' )),
                    Field::make('complex', $this->prefix . 'colors',__( 'Custom Colors', 'redvolver' ))
                        ->set_layout('tabbed-horizontal')
                        ->add_fields(array(
                            Field::make("color", $this->prefix . 'color', __( 'Custom Color', 'redvolver' ))
                        )),

                        /*
                        Field::make('complex', $this->prefix . 'layout_part','Layout Part')->add_fields('part',array(
                            Field::make('select', $this->prefix . 'layout_part_type','Type') 
                                ->set_options(array( $this, 'get_default_types' )),
                            Field::make('text', $this->prefix . 'layout_part_html','Custom Html')
                            ->set_conditional_logic(array(
                                'relation' => 'AND', // Optional, defaults to "AND"
                                array(
                                    'field' => $this->prefix . 'layout_part_type',
                                    'value' => 'customhtml', // Optional, defaults to "". Should be an array if "IN" or "NOT IN" operators are used.
                                    'compare' => '=', // Optional, defaults to "=". Available operators: =, <, >, <=, >=, IN, NOT IN
                                )
                            )),
                            Field::make('text', $this->prefix . 'layout_part_top','Top Position')->help_text('Percentage Position %')->set_width(50),
                            Field::make('text', $this->prefix . 'layout_part_left','Left Position')->help_text('Percentage Position %')->set_width(50),
                        )) 
                        ->set_conditional_logic(array(
                                'relation' => 'AND', // Optional, defaults to "AND"
                                array(
                                    'field' => $this->prefix . 'layout_type',
                                    'value' => 'builder',
                                    'compare' => '=',
                                )
                            )),
                        */
                        
                    )),
                )
        );
    }


    /**
     * Register our setting to WP
     * @since  0.1.0
     */
    public function init() {
        register_setting( $this->key, $this->key );
    }

    public function get_post_types() {

        $post_types = apply_filters( 'redvolver_post_types', get_post_types( array( 'public' => true ), 'objects' ) );

        foreach ( $post_types as $post_type ) {
            if ( $post_type->name  == 'attachment' ) continue;
            $types[ $post_type->name ] = $post_type->labels->name;
        }

        return $types;

    }

    public function get_default_types() {
        $deftype = array(
            'bubble' => 'Bubble',
            'square' => 'Square',
            'font' => 'Font Icon',
            'image' => 'Image',
            //'customhtml' => 'Custom Html',
        );
        return $deftype;
    }

    public function get_layout_types() {
        $deftype = array(
            'general' => 'General',
            'builder' => 'Builder',
        );
        return $deftype;
    }

    public function get_default_speed() {
        $speed = array(
            '10' => '10',
            '9' => '9',
            '8' => '8',
            '7' => '7',
            '6' => '6',
            '5' => '5',
            '4' => '4',
            '3' => '3',
            '2' => '2',
            '1' => '1',
            '0' => '0',
            '-1' => '-1',
            '-2' => '-2',
            '-3' => '-3',
            '-4' => '-4',
            '-5' => '-5',
            '-6' => '-6',
            '-7' => '-7',
            '-8' => '-8',
            '-9' => '-9',
            '-10' => '-10',
        );
        return $speed;
    }
    
    public function get_default_opacity() {
        $opacity = array(
            '0.1' => '0.1',
            '0.2' => '0.2',
            '0.3' => '0.3',
            '0.4' => '0.4',
            '0.5' => '0.5',
            '0.6' => '0.6',
            '0.7' => '0.7',
            '0.8' => '0.8',
            '0.9' => '0.9',
            '1' => '1'
        );
        return $opacity;
    }

    public function check_number($value) {
        if ( ! is_int( $value ) ) {
            // Empty the value
            $value = '100';
        }
        return $value;
    }

    /**
     * Public getter method for retrieving protected/private variables
     * @since  0.1.0
     * @param  string  $field Field to retrieve
     * @return mixed          Field value or exception is thrown
     */
    public function __get( $field ) {
        // Allowed fields to retrieve
        if ( in_array( $field, array( 'key', 'metabox_id', 'title', 'options_page' ), true ) ) {
            return $this->{$field};
        }

        throw new Exception( 'Invalid property: ' . $field );
    }

}

/**
 * Helper function to get/return the Redvolver_Admin object
 * @since  0.1.0
 * @return Redvolver_Admin object
 */
function redvolver_admin() {
    return Redvolver_Admin::get_instance();
}

// Get it started
redvolver_admin();