<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Dynamic Tag - Post Meta
 *
 * Elementor dynamic tag that returns a post meta.
 *
 * @since 1.0.0
 */
 
if (!class_exists('Elementor_Dynamic_Tag_Post_Meta')) {
    class Elementor_Dynamic_Tag_Post_Meta extends \Elementor\Core\DynamicTags\Tag {
    
    	/**
    	 * Get dynamic tag name.
    	 *
    	 * Retrieve the name of the post meta tag.
    	 *
    	 * @since 1.0.0
    	 * @access public
    	 * @return string Dynamic tag name.
    	 */
    	public function get_name() {
    		return 'post-meta';
    	}
    
    	/**
    	 * Get dynamic tag title.
    	 *
    	 * Returns the title of the post meta tag.
    	 *
    	 * @since 1.0.0
    	 * @access public
    	 * @return string Dynamic tag title.
    	 */
    	public function get_title() {
    		return esc_html__( 'Post Meta', 'default' );
    	}
    
    	/**
    	 * Get dynamic tag groups.
    	 *
    	 * Retrieve the list of groups the post meta tag belongs to.
    	 *
    	 * @since 1.0.0
    	 * @access public
    	 * @return array Dynamic tag groups.
    	 */
    	public function get_group() {
    		return [ 'post' ];
    	}
    
    	/**
    	 * Get dynamic tag categories.
    	 *
    	 * Retrieve the list of categories the post meta tag belongs to.
    	 *
    	 * @since 1.0.0
    	 * @access public
    	 * @return array Dynamic tag categories.
    	 */
    	public function get_categories() {
    		return [ 'text' ];
    	}
    	
        private function get_all_metas() {
    		global $wpdb;
            $meta_keys = $wpdb->get_col("SELECT DISTINCT meta_key FROM {$wpdb->postmeta} ORDER BY meta_key ASC");
            $meta_keys_assoc = array_combine($meta_keys, $meta_keys);
    		return $meta_keys_assoc;
    	}	
    	
        private function get_meta_values( $key = '' ) {
            global $wpdb;
            $values = false;
            if($key !== ''){
                $values = $wpdb->get_col( $wpdb->prepare( "
                    SELECT pm.meta_value FROM {$wpdb->postmeta} pm
                    LEFT JOIN {$wpdb->posts} p ON p.ID = pm.post_id
                    WHERE pm.meta_key = '%s' 
                ", $key ) );
            }
            return $values;
        }	
    	
        protected function register_controls() {
    
            $metas = is_array($this->get_all_metas()) ? $this->get_all_metas() : [];
            
            $metas_array = [];
            $array_values = [];
            
            if(count($metas) > 0){
                foreach($metas as $k => $kk){
                    $values = $this->get_meta_values($k);
                    if(is_array($values) && count($values) > 0){
                        $array_values = array_filter($values, function($value) {
                            return is_serialized($value) && is_array(maybe_unserialize($value));
                        });
                        if(is_array($array_values) && count($array_values) > 0){
                            $metas_array[$k] = $k;
                        }
                    }
                }
            }
    
    		$this->add_control(
    			'select_meta',
    			[
    				'type' => \Elementor\Controls_Manager::SELECT,
    				'label' => esc_html__( 'Select Meta', 'textdomain' ),
    				'options' => $metas,
    				'default' => '',
    				'description' => '',
            		'dynamic' => [
            			'active' => true,
            		],
    			]
    		);
    		
            $this->add_control(
                'other_key',
                [
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'label' => __( 'Other key', 'text-domain' ),
                    'default' => '',
                    'description' => 'If the selected meta data is an array, enter the key name here.',
                    'condition' => [
                        'select_meta' => array_keys($metas_array), 
                    ],
                ]
            );		
    		
    
    	}
    
    	/**
    	 * Render tag output on the frontend.
    	 *
    	 * Written in PHP and used to generate the final HTML.
    	 *
    	 * @since 1.0.0
    	 * @access public
    	 * @return void
    	 */
    	public function render() {
    	    $select_meta = $this->get_settings( 'select_meta' );
    	    $other_key = $this->get_settings( 'other_key' );
    	    
    	    $get_meta_field = get_post_meta(get_the_ID(),$select_meta,true);
    	    
    	    if(!is_array($get_meta_field)){
    	        $return = $get_meta_field;
    	    }else{
    	        $return = isset($get_meta_field[$other_key]) ? do_shortcode($get_meta_field[$other_key]) : 'Error: other key not found '.json_encode($get_meta_field,JSON_PRETTY_PRINT) . json_encode($other_key,JSON_PRETTY_PRINT);    
    	    }
    	    
    		echo $return;
    	}
    
    }
}