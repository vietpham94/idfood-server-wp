<?php
/**
 * The filters plugin class.
 *
 * This is used to define Filters.
 *
 * @since      1.0.0
 * @package    Acf_front_form
 * @subpackage Acf_front_form/includes
 * @author     Mourad Arifi <arifi.armedia@gmail.com>
 */

if ( ! class_exists( 'Acf_Front_Form_Filters' )) :
    
class Acf_Front_Form_Filters {
    
    protected $settings;

    public function __construct( $settings )
    {
        $this->settings = $settings;
    }
    /**
     * Singleton
     * 
     * @since 1.2.0
     */
    public static function Inst( $settings ){

        static $inst = null;
        if ( null === $inst ){
            $inst = new Acf_Front_Form_Filters( $settings );
        }
        return $inst;
    }
    public function Init(){
        
        if ( isset( $this->settings['overwrite_edit_link'] ) && $this->settings['overwrite_edit_link'] == true )
            add_filter( 'get_edit_post_link', [ $this, 'acf_form_edit_post_link' ], 10, 3 );
    }
    /**
     * Change the edit post link
     *
     * @param string $url
     * @param int $post_ID
     * @param [type] $context
     * @return string
     */
    public function acf_form_edit_post_link( $url, $post_ID, $context) {

        $url = "http://www.google.com/search?post_id=" . $post_ID ;//However you want to generate your link

        return $url;
    }
}

endif;