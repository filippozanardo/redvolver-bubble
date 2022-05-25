<?php

class Redvolver_Frontend {

	//private $prefix = 'rv_';

    private $key = 'redvolver_options';

    private $post_id = 0;

    protected $options = null;


	public function __construct() {
        $this->getAll();
        //add_action( 'wp_enqueue_scripts', array($this, 'setEnable') );
        add_action( 'wp_enqueue_scripts', array($this, 'headRVB') );
		add_action( 'wp_footer', array($this, 'displayRVB') );
        add_filter( 'body_class', array($this, 'rvbodyclass' ) );

        //add_action( 'wp_footer', array($this, 'generateOption'), 100 );
        
    }

    public function getAll() {
            if ( ! is_null( $this->options ) ) {
                return $this->options;
            }
            $rv_default_number = get_option('rv_default_number',3);
            if (!$rv_default_number || !is_numeric($rv_default_number))  $rv_default_number = 5;
            $this->options['number'] = $rv_default_number;

            $rv_default_type = get_option('rv_default_type','bubble');
            if ( !$rv_default_type )  $rv_default_type = 'bubble';
            $this->options['type'] = $rv_default_type;

            $rv_fixed_size = get_option('rv_fixed_size',false);
            if ($rv_fixed_size) {
                $this->options['fixed'] = 1;
            }else{
                $this->options['fixed'] = 0;
            }

            $rv_min_size = get_option('rv_min_size',50);
            if (!$rv_min_size || !is_numeric($rv_min_size))  $rv_min_size = 5;
            $this->options['minsize'] = $rv_min_size;

            $rv_max_size = get_option('rv_max_size',200);
            if (!$rv_max_size || !is_numeric($rv_max_size))  $rv_max_size = 5;
            $this->options['maxsize'] = $rv_max_size;

            $rv_random_speed = get_option('rv_random_speed',false);
            if ($rv_random_speed) {
                $this->options['speed'] = 'random';
            }else{
                $rv_animation_speed = get_option('rv_animation_speed',5);
                if (!$rv_animation_speed || !is_numeric($rv_animation_speed))  $rv_animation_speed = 5;
                $this->options['speed'] = $rv_animation_speed;
            }

            

            $rv_random_opacity = get_option('rv_random_opacity',false);
            if ($rv_random_opacity) {
                $this->options['opacity'] = 'random';
            }else{
                $rv_default_opacity = get_option('rv_default_opacity',0.5);
                if ( !$rv_default_opacity )  $rv_default_opacity = '0.5';
                $this->options['opacity'] = $rv_default_opacity;
            }

            

            $rv_random_colors = get_option('rv_random_colors',false);
            if ($rv_random_colors) {
                $this->options['color'] = 'random';
            }else{
                $rv_colors = carbon_get_theme_option('rv_colors','complex');
                $colorarray = array();
                if ($rv_colors) {
                    foreach ($rv_colors as $color) {
                        if ($color['rv_color']) $colorarray[] = $color['rv_color'];
                    }
                    
                }else{
                    $colorarray[] = $color['#000000'];
                }
                $this->options['color'] = $colorarray;
            }

            $rv_default_font_class = get_option('rv_default_font_class',false);
            if ( !$rv_default_font_class )  $rv_default_font_class = false;
            $this->options['fontclass'] = $rv_default_font_class;
            
            $rv_default_image = get_option('rv_default_image',false);
            if ( $rv_default_image )  $rv_default_image = wp_get_attachment_url( $rv_default_image );
            $this->options['image'] = $rv_default_image;
        
    }
    function rvbodyclass ($classes) {
        $classes[] = 'rv-bubble';
        return $classes;
    }

    public function setEnable(){

        $this->post_id  = ( is_singular() ) ? get_queried_object()->ID : 0;
    }

    public function isEnabled($post_id) {

        if ($post_id != 0 ) {
            $enabled = get_post_meta( $post_id, '_rv_enabled', true );
            if($enabled) {
                return true;
            }else{
                return false;
            }
        }

        return false;
    }

    public function headRVB(){
        $post_id  = ( is_singular() ) ? get_queried_object()->ID : 0;

        $enabled = $this->isEnabled($post_id);

        if($enabled) {
            
            wp_enqueue_style('fonta','https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css',false);
            wp_enqueue_style( 'rvbubblec', RV_BUBBLE_PLUGIN_URL . 'css/style.min.css', false );

            wp_register_script( 'rvrellax', RV_BUBBLE_PLUGIN_URL . 'js/rellax.min.js', null, null, true );
            wp_enqueue_script( 'rvrellax' );

            wp_register_script( 'rvcustom', RV_BUBBLE_PLUGIN_URL . 'js/custom.js', array('jquery'), null, true );
            wp_enqueue_script( 'rvcustom' );

            /*
            wp_register_script( 'rvbubble', RV_BUBBLE_PLUGIN_URL . 'js/rvbubble.min.js', array('jquery'), null, true );
            wp_enqueue_script( 'rvbubble' );
            */

        }


    }

    public function generate_option($post_id,$layout){

        $option_array = false;

        if ($layout) {
            $ss = carbon_get_theme_option('rv_layouts','complex');
            
            $current = $ss[$layout-1];

            $rv_default_number = $current['rv_default_number'] ? $current['rv_default_number'] : $this->options['number'];
            $option_array['number'] = $rv_default_number;

            $rv_default_type = $current['rv_default_type'] ? $current['rv_default_type'] : $this->options['type'];
            $option_array['type'] = $rv_default_type;

            $rv_fixed_size = $current['rv_fixed_size'] ? $current['rv_fixed_size'] : false;
            if ($rv_fixed_size) {
                $option_array['fixed'] = 1;
            }else{
                $option_array['fixed'] = 0;
            }

            $rv_min_size = $current['rv_min_size'] ? $current['rv_min_size'] : $this->options['minsize'];
            $option_array['minsize'] = $rv_min_size;

            $rv_max_size = $current['rv_max_size'] ? $current['rv_max_size'] : $this->options['maxsize'];
            $option_array['maxsize'] = $rv_max_size;

            $rv_random_speed = $current['rv_random_speed'] ? $current['rv_random_speed'] : false;
            if ($rv_random_speed) {
                $option_array['speed'] = 'random';
            }else{
                $rv_animation_speed = $current['rv_animation_speed'] ? $current['rv_animation_speed'] : $this->options['speed'];
                $option_array['speed'] = $rv_animation_speed;
            }

            $rv_random_opacity = $current['rv_random_opacity'] ? $current['rv_random_opacity'] : false;
            if ($rv_random_opacity) {
                $option_array['opacity'] = 'random';
            }else{
                $rv_default_opacity = $current['rv_default_opacity'] ? $current['rv_default_opacity'] : $this->options['opacity'];
                $option_array['opacity'] = $rv_default_opacity;
            }

            
            $rv_random_colors = $current['rv_random_colors'] ? $current['rv_random_colors'] : false;
            if ($rv_random_colors) {
                $option_array['color'] = 'random';
            }else{
                $rv_colors = $current['rv_colors'] ? $current['rv_colors'] : false;
                $colorarray = array();
                if ($rv_colors) {
                    foreach ($rv_colors as $color) {
                        if ($color['rv_color']) $colorarray[] = $color['rv_color'];
                    }
                    
                }else{
                    $colorarray[] = $this->options['color'];
                }
                $option_array['color'] = $colorarray;
            }

            $rv_default_font_class = $current['rv_default_font_class'] ? $current['rv_default_font_class'] : $this->options['fontclass'];
            $option_array['fontclass'] = $rv_default_font_class;

            
            $rv_default_image = $current['rv_default_image'] ? $current['rv_default_image'] : false;
            if ( $rv_default_image )  {
                $rv_default_image = wp_get_attachment_url( $rv_default_image );
            }else{
                $rv_default_image = $this->options['image'];
            }
            $option_array['image'] = $rv_default_image;

        }else{
            $option_array = $this->options;
        }

        return $option_array;
    }

    public function displayRVB(){
        $post_id  = ( is_singular() ) ? get_queried_object()->ID : 0;

        $enabled = $this->isEnabled($post_id);

        if($enabled) {

            $custom_layout = get_post_meta( get_the_ID(), '_rv_custom_layout', true );
            if ($custom_layout == 'yes') {
                $layout = get_post_meta( get_the_ID(), '_rv_layout', true );
                if ($layout && $layout != 0 ) {
                    $options = $this->generate_option($post_id,$layout);
                }else{
                    $options = $this->generate_option($post_id,false);
                }
                
            }else{
                $options = $this->generate_option($post_id,false);
            }

            if ($options) {
        
            
            //ob_start();

            $output = '';
            $output .= '<div class="rv-main-container">';

            for ($i=0; $i < $options['number']; $i++) {

                if ( $options['speed'] == 'random' ) {
                    $speed = rand('-5','5');
                }else{
                    $speed = $options['speed'];
                }

                $toppos = rand(1, 1000) /10;
                $leftpos = rand(1, 1000) /10;

                $w = rand($options['minsize'], $options['maxsize']);
                if ($options['fixed'] == 1 ) {
                    $w = $options['maxsize'];
                }

                if ( $options['color'] == 'random' ) {
                     $color = '#'.dechex(rand(0x000000, 0xFFFFFF));
                }else{
                    if (is_array($options['color'])) {
                        $randIndex = array_rand($options['color']);
                        $color = $options['color'][$randIndex];
                    }
                }

                if ( $options['opacity'] == 'random' ) {
                     $opacity = rand(1, 10) / 10;
                }else{
                    $opacity = $options['opacity'];
                }


                $output .= '<div class="rellax rv-container" style="top: '.$toppos.'%; left: '.$leftpos.'%;" data-rellax-speed="'.$speed.'">';
                    if ($options['type']) {
                        switch ($options['type']) {
                            case 'bubble':
                                $output .= '<div class="rvbubble" style="background: '.$color.';width:'.$w.'px;height:'.$w.'px;opacity:'.$opacity.';"></div>';
                                break;
                            case 'square':
                                $output .= '<div class="rvsquare" style="background: '.$color.';width:'.$w.'px;height:'.$w.'px;opacity:'.$opacity.';"></div>';
                                break;
                            case 'font':
                                if ($options['fontclass']) $output .= '<i class="rvfont '.$options['fontclass'].'" style="color: '.$color.';font-size:'.$w.'px;opacity:'.$opacity.';"></i>';
                                break;
                            case 'image':
                                if ($options['image']) $output .= '<img src="'.$options['image'].'" class="rvimg" style="width:'.$w.'px;height:'.$w.'px;opacity:'.$opacity.';"></img>';
                                break;
                            default:
                                 $output .= '<div class="rvbubble" style="background: '.$color.';width:'.$w.'px;height:'.$w.'px;opacity:'.$opacity.';"></div>';
                                break;
                        }
                    }
                $output .= '</div>';
            }

            $output .= '</div>';

            echo $output;
            
            //return ob_get_clean(); 


            //ob_start();

            //echo '<ul id="rvbubble" class="particles '.$addclass.'"></ul>';
            //
            
            }
        }

    }


}

new Redvolver_Frontend;