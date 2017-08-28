<?php 
/**
 * FAQ Shortcode
 * Author:Evolve 
 */
class ev_faq_shortcode{

    function __construct(){
        
        
    }
    
    function ul_open($atts, $content = null){
        
        extract(shortcode_atts( array('type' => ''), $atts));
        
        $class = trim($type) == 'content' ? 'ev_faq_contents' : 'ev_faq_link' ;
        return '<ul class="'.$class.'">';
        
    }

    function ul_close($atts, $content = null){
        return '</ul>';
    }
    
    function link($atts, $content = null){
        
        extract(shortcode_atts( array('id' => ''), $atts));
        return '<li class="ev_faq_li"><a href="#'.$id.'" class="ev_faq_link">'.$content.'</a></li>';
    
    }
    
    function contents($atts, $content = null){
        
        extract(shortcode_atts( array('id' => '', 'title' => ''), $atts));
        return '<li class="ev_faq_content_li" id="'.$id.'">
                    <h3 class="ev_faq_title"><span class="ev_faq_question">Q:</span>'.$title.'</h3>
                    <p class="ev_faq_content"><span class="ev_faq_answer">A:</span>'.$content.'</p>
                    <a href="#" class="ev_faq_top">Back to top</a>
                </li>';       
        
    }
}

if(!is_admin()){
    $ev_faq_shortcode = new ev_faq_shortcode();
    add_shortcode('ev_faq_open', array($ev_faq_shortcode, 'ul_open'));  
    add_shortcode('ev_faq_close', array($ev_faq_shortcode, 'ul_close'));
    add_shortcode('ev_faq_link', array($ev_faq_shortcode, 'link'));
    add_shortcode('ev_faq_content', array($ev_faq_shortcode, 'contents'));
    
}


?>