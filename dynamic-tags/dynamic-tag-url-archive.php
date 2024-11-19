<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Dynamic Tag - Url Archive
 *
 * Elementor dynamic tag that returns a url archive custom post type.
 *
 * @since 1.0.0
 */
 
if (!class_exists('Elementor_Dynamic_Tag_Url_Archive')) {
    class Elementor_Dynamic_Tag_Url_Archive extends \Elementor\Core\DynamicTags\Tag {
    
    	/**
    	 * Get dynamic tag name.
    	 *
    	 * Retrieve the name of the url archive custom post type tag.
    	 *
    	 * @since 1.0.0
    	 * @access public
    	 * @return string Dynamic tag name.
    	 */
    	public function get_name() {
    		return 'url-archive-custom-post-type';
    	}
    
    	/**
    	 * Get dynamic tag title.
    	 *
    	 * Returns the title of the url archive custom post type tag.
    	 *
    	 * @since 1.0.0
    	 * @access public
    	 * @return string Dynamic tag title.
    	 */
    	public function get_title() {
    		return esc_html__( 'URL Archive CPT', 'default' );
    	}
    
    	/**
    	 * Get dynamic tag groups.
    	 *
    	 * Retrieve the list of groups the url archive custom post type tag belongs to.
    	 *
    	 * @since 1.0.0
    	 * @access public
    	 * @return array Dynamic tag groups.
    	 */
    	public function get_group() {
    		return [ 'archive' ];
    	}
    
    	/**
    	 * Get dynamic tag categories.
    	 *
    	 * Retrieve the list of categories the url archive custom post type tag belongs to.
    	 *
    	 * @since 1.0.0
    	 * @access public
    	 * @return array Dynamic tag categories.
    	 */
    	public function get_categories() {
    		return [ \Elementor\Modules\DynamicTags\Module::URL_CATEGORY ];
    	}
    	
        private function get_all_post_types() {
    		$post_types = get_post_types( array( 'public' => true, '_builtin' => true ), 'names', 'and' );
			unset( $post_types['attachment'] );
			return $post_types;
    	}	
    	    	
        protected function register_controls() {
    
            $cp_types = is_array($this->get_all_post_types()) ? $this->get_all_post_types() : [];
    
    		$this->add_control(
    			'select_post_type',
    			[
    				'type' => \Elementor\Controls_Manager::SELECT,
    				'label' => esc_html__( 'Select Post Type', 'textdomain' ),
    				'options' => $cp_types,
    				'default' => '',
    				'description' => '',
            		'dynamic' => [
            			'active' => true,
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
    	    $select_post_type = $this->get_settings( 'select_post_type' );
    	    $archive_link = get_post_type_archive_link( select_post_type );
			$return = '';

    	    if ( $archive_link ) {
				$return = esc_url( $archive_link );
			}
    	    
    		echo $return;
    	}
    
    }
}