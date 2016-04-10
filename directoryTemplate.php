<?php
function dirFunc($attr){
                if(isset($atts)){
                    $a = shortcode_atts( array(
                        'col'	=> '3',
                        'theme' => 'light'
                    ), $atts);
                }
                
                $args = array('post_type' => 'Directory List');
                $dl_query = new WP_Query($args);
                $content = '';

                // The Loop
                if($dl_query->have_posts() ){
                    //restricts excerpt to 20 words
                    
                    function wpdocs_custom_excerpt_length( $length ) {
                        return 20;
                    }
                    add_filter( 'excerpt_length', 'wpdocs_custom_excerpt_length', 999 );
                    
                     function wpdocs_excerpt_more( $more ) {
                            return '...';
                        }
                        add_filter( 'excerpt_more', 'wpdocs_excerpt_more' );
                        
                        
                    
                        $content .= '<article id="dir-list">';
                        while ( $dl_query->have_posts() ) {
                                $dl_query->the_post();
                                    $id = get_the_ID();
                                
                                     $content .=  '<section class="dir_card">';
                                     $content .=  '<h3>'.get_the_title().'</h3>';
                                
                                     
                                
                                $meta = get_post_meta($id);
                                
                                if( has_post_thumbnail($id) ) {
                                    $content .= '<section class="inline img">';
                                    $content .= get_the_post_thumbnail($id, 'medium'); 
                                    $content .= '</section>';
                                }
                                  
                                $content .= '<section class="inline contact">';
                                if ( isset( $meta['listing-st-address1'][0] ) ) { 
                                    $content .=  '<span class="inline"><strong>Street Address 1: </strong><br>'.  $meta['listing-st-address1'][0].'</span>'; 
                                    $gmLink = $meta['listing-st-address1'][0].' ';
                                    } 
                                
                                if ( isset( $meta['listing-st-address2'][0] ) ) { 
                                   $content .=  '<span class="inline"><strong>Street Address 2: </strong><br>'.$meta['listing-st-address2'][0].'</span>'; 
                                    $gmLink .= $meta['listing-st-address2'][0].' ';
                                    }
                                
                                if (isset( $meta['listing-city'][0] ) ) { 
                                    $content .=  '<span class="inline"><strong>City: </strong><br>'. $meta['listing-city'][0].' </span>'; 
                                    $gmLink .= $meta['listing-city'][0].' ';
                                    }
                                
                                if (isset($meta['listing-state'][0])) { 
                                    $content .=  '<span class="inline"><strong>State: </strong><br>'. $meta['listing-state'][0].' </span>'; 
                                    $gmLink .= $meta['listing-state'][0].' ';
                                    }
                                
                                if (isset($meta['listing-zip'][0])){ 
                                   $content .=  ' <span class="inline"><strong>Zip: </strong><br>'. $meta['listing-zip'][0].'</span>';
                                   $gmLink .= $meta['listing-zip'][0];
                                   }
                                   
                                  if(isset($gmLink)){
                                      $gmLink = str_replace(" ", "+", $gmLink);
                                      $content .= ' <span class="inline"><a href="https://www.google.com/maps/place/'.$gmLink.'" target="_blank"><button class="btn">Find On Google Maps</button></a></span>';
                                  } 
                                   
                                  $content .= '</section><br>';
                                  
                                
                                if (isset($meta['listing-phone'][0])) {
                                    $content .=  '<span class="inline"> <strong>Telephone: </strong> <br>'. $meta['listing-phone'][0].'</span>';}
                                
                                if (isset($meta['listing-email'][0])) { 
                                    $content .=  '<span class="inline"><strong>Email: </strong><br>'. $meta['listing-email'][0].'</span>';}
                                
                                if (isset($meta['listing-website'][0])) { 
                                   $content .=  '<span class="inline"><strong>URL: </strong><br> <a href="'. $meta['listing-website'][0].'" target="_blank">'.$meta['listing-website'][0].'</a></span><br>';}
                                   
                                   $content .=  '<strong>Description: </strong>';
                                   $content .=  get_the_content();
                                   $content .= "<br>";
                                   $content .=  '</section>';
                        
                        }              
                wp_reset_postdata();
                 $content .=  '</article>';
            }
            
            return $content;
      
            // echo '<pre>';print_r($x); exit;
           

 }
add_shortcode( 'directory-listing', 'dirFunc' );
 
?>