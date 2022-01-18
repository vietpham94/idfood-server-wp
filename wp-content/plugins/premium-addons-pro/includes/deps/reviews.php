<?php

if ( ! defined('ABSPATH') ) exit;

define( 'PREMIUM_FB_REV_GRAPH_API', 'https://graph.facebook.com/v4.0/' );

define( 'PREMIUM_GOOGLE_PLACE_API', 'https://maps.googleapis.com/maps/api/place/' );

define( 'PREMIUM_FB_REV_AVATAR', PREMIUM_ADDONS_URL . 'assets/frontend/images/person-image.jpg' );

define( 'PREMIUM_YELP_API', 'https://api.yelp.com/v3/businesses' );

/**
 * Gets JSON Data from Facebook
 * @since 1.0.0
 */
function premium_fb_rev_api_rating( $page_id, $page_access_token ) { 

    $api_url = PREMIUM_FB_REV_GRAPH_API . $page_id . "/ratings?access_token=" . $page_access_token . "&fields=reviewer{id,name,picture.width(200).height(200)},created_time,rating,recommendation_type,review_text,open_graph_story{id}&limit=9999";

    $api_response = rplg_urlopen( $api_url );
    
    return $api_response;
}

/**
 * Gets Page Data from Facebook
 * @since 1.0.0
 */
function premium_fb_rev_page( $page_id, $settings ) {
    
    $custom_image = $settings['image'];
    
    $page_name = $settings['name'];
    
    $page_rate = $settings['rate'];
    
    $rating     = $settings['rating'];
    
    $fill_color = $settings['fill_color'];
    
    $empty_color= $settings['empty_color'];
    
    $show_stars = $settings['stars'];
    
    $star_size  = $settings['size'];
    
    if( empty( $custom_image ) ) {
        $page_img = 'https://graph.facebook.com/' . $page_id .'/picture';
    } else {
        $page_img = $custom_image;
    }
    
    $page_link = sprintf( '<a class="premium-fb-rev-page-link" href="https://fb.com/%s" target="_blank" title="%2$s" ><span>%2$s</span></a>', $page_id, $page_name );
?>
    
    <div class="premium-fb-rev-page-left">
        <img class="premium-fb-rev-img" src="<?php echo esc_attr( $page_img ); ?>" alt="<?php echo $page_name; ?>">
    </div>
    <div class="premium-fb-rev-page-right">
        <?php if( ! empty( $page_name ) ) : ?>
        <div class="premium-fb-rev-page-link-wrapper"><?php
            echo $page_link;
        ?>
       </div>
        <?php endif; ?>
        <div class="premium-fb-rev-page-rating-wrapper">
            <?php if( $page_rate ) : ?>
                <span class="premium-fb-rev-page-rating"><?php echo $rating; ?></span>
            <?php endif; ?>
            <?php if( $show_stars ) : ?>
                <span class="premium-fb-rev-page-stars"><?php premium_fb_rev_stars( $rating, $fill_color, $empty_color, $star_size ); ?></span>
            <?php endif; ?>
        </div>
   </div>
<?php
}

/**
* Gets reviews data from Facebook
* @since 1.0.0
*/
function premium_fb_rev_reviews( $reviews, $settings ) { 
    
        $limit      = $settings['limit'];

        $min_filter = $settings['filter_min'];

        $max_filter = $settings['filter_max'];

        $show_date  = $settings['date'];

        $show_stars = $settings['stars'];

        $date_format= $settings['format'];

        $fill_color = $settings['fill_color'];

        $empty_color= $settings['empty_color'];

        $star_size  = $settings['stars_size'];

        $rev_text   = $settings['text'];

        $length     = $settings['rev_length'];

        $readmore   = $settings['readmore'];

        $skin_type  = $settings['skin_type'];
        
    ?>

   <div class="premium-fb-rev-reviews">
    <?php
        if ( count( $reviews ) > 0 ) {
            array_splice( $reviews, $limit );
            foreach ( $reviews as $review ) {
                
                if( isset( $review->rating ) ) {
                    $rating = $review->rating;
                } elseif( isset( $review->recommendation_type ) ) {
                    $rating = 'negative' === $review->recommendation_type ? 1 : 5;
                } else {
                    $rating = 5;
                }

                $image_link  = $review->reviewer->picture->data->url;

                $review_url = isset( $review->open_graph_story ) ? $review->open_graph_story->id : ''; 
                $review_url = sprintf( 'https://facebook.com/%s', $review_url ); 
                
                if( $min_filter <= $rating && $rating <= $max_filter ) { ?>
                    <div class="premium-fb-rev-review-wrap">
                         <div class="premium-fb-rev-review">
                             <div class="premium-fb-rev-review-inner">
                             <?php if($skin_type === "default"){ ?>
                                 <div class="premium-fb-rev-content-left">
                                     <img class="premium-fb-rev-img" src="<?php echo $image_link; ?>" alt="<?php echo $review->reviewer->name; ?>" onerror=" if( this.src!='<?php echo PREMIUM_FB_REV_AVATAR; ?>' ) this.src='<?php echo PREMIUM_FB_REV_AVATAR; ?>';">
                                 </div>
                             <?php } ?>
                                 <div class="premium-fb-rev-content-right">
                                     <?php if( isset( $review->reviewer->id ) ) : ?>
                                         <div class="premium-fb-rev-reviewer-wrapper">
                                     <?php
                                         $person_link = '<a class="premium-fb-rev-reviewer-link" href="' . $review_url . '" target="_blank"><span>'. $review->reviewer->name .'</span></a>';
                                         echo $person_link;
                                     ?>
                                        </div>
                                     <?php endif; ?>
                                     
                                     <?php if( $show_date || $show_stars ) : ?>
                                        <div class="premium-fb-rev-info">
                                            <?php if( $show_date ) : ?>
                                                <div class="premium-fb-rev-time"><span class="premium-fb-rev-time-text"><?php echo date( $date_format, strtotime ( $review->created_time ) ); ?></span></div>
                                            <?php endif; ?>
                                            <?php if( $show_stars ) : ?>
                                               <div class="premium-fb-rev-stars-container">
                                                  <span class="premium-fb-rev-stars"><?php
                                                      echo premium_fb_rev_stars( $rating, $fill_color, $empty_color, $star_size ); ?>
                                                  </span>
                                              </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif;
                                    if ( isset( $review->review_text ) && $rev_text ) : ?>
                                       <div class="premium-fb-rev-rating"> 
                                          <div class="premium-fb-rev-text-wrapper">
                                              <span class="premium-fb-rev-text reviews"><?php $review->more = premium_fb_rev_trim_text( $review->review_text, $length ); ?></span>
                                              <?php if ( $review->more ) : ?>
                                                <a class="premium-fb-rev-readmore" href="<?php echo $review_url; ?>" target="_blank" rel="noopener noreferrer"><?php echo $readmore; ?></a>
                                            <?php endif; ?>
                                          </div>
                                      </div>
                                   <?php endif; ?>
                                   <?php if($skin_type === "card"){ ?>
                                        <div class="premium-fb-rev-content-left">
                                            <img class="premium-fb-rev-img" src="<?php echo $image_link; ?>" alt="<?php echo $review->reviewer->name; ?>" onerror=" if( this.src!='<?php echo PREMIUM_FB_REV_AVATAR; ?>' ) this.src='<?php echo PREMIUM_FB_REV_AVATAR; ?>';">
                                        </div>
                                    <?php } ?>
                                 </div>
                             </div>
                         </div>
                    </div>
            <?php }
            }
        }
    ?>
    </div>
<?php }

/**
 * Gets JSON Data from Google
 * @since 1.0.0
 */
function premium_google_rev_api_rating ( $api_key, $place_id, $prefix ) {

    $language = '';
    
    if( ! empty ( $prefix ) )
        $language = '&language=' . $prefix;
    
    $api_url = PREMIUM_GOOGLE_PLACE_API . 'details/json?placeid=' . trim( $place_id ) . $language . '&key=' . trim( $api_key );
    
    $api_response = rplg_urlopen( $api_url );

    return $api_response;

}

/**
 * Render Place Layout
 * @since 1.0.0
 */
function premium_reviews_place( $place, $settings ) { 
    
    $custom_image   = $settings['image'];
    
    $rating         = $settings['rating'];
    
    $fill_color     = $settings['color'];
    
    $empty_color    = $settings['empty_color'];
    
    $show_stars     = $settings['stars'];
    
    $star_size      = $settings['stars_size'];
    
    $place_rate     = $settings['place_rate'];
    
    $api_key        = $settings['key'];
    
    $id             = $settings['id'];
    
    ?>

    <div class="premium-fb-rev-page-left">
        <?php if( empty( $custom_image ) ) {
            
            $image = premium_place_avatar( $place, $api_key );

            if ( ! empty ( $image ) ) {
                $place_img = $image;
            } elseif ( ! empty ( $place->icon ) ) {
                $place_img = $place->icon;
            } else {
                $place_img = '';
            }
            
            if( isset( $place_img ) ) {
                update_option('premium_reviews_img-' . $id , $place_img );
            } else {
                $place_img = get_option('premium_reviews_img-' . $id );
            }
            
        } else {
            
            $place_img = $custom_image;
            
        } ?>
        
        <img class="premium-fb-rev-img" src="<?php echo $place_img; ?>" alt="<?php echo $place->name; ?>">
    </div>
    <div class="premium-fb-rev-page-right">
        <?php if( !empty( $place->name ) ) : ?>
        <div class="premium-fb-rev-page-link-wrapper"><?php
            $place_link = '<a class="premium-fb-rev-page-link" href="' . $place->url . '" target="_blank"><span>'. $place->name .'</span></a>';
            echo $place_link; ?>
       </div>
        <?php endif; ?>
        <div class="premium-fb-rev-page-rating-wrapper">
            <?php if( $place_rate ) : ?>
                <span class="premium-fb-rev-page-rating"><?php echo $rating; ?></span>
            <?php endif; ?>
            <?php if( $show_stars ) : ?>
                <span class="premium-fb-rev-page-stars"><?php premium_fb_rev_stars( $rating, $fill_color, $empty_color, $star_size ); ?></span>
            <?php endif; ?>
        </div>
   </div>
<?php
}

/**
 * Gets place image from Google
 * @since 1.0.0
 */
function premium_place_avatar( $place_data, $api_key ) {
    
    if( isset( $place_data->image_url ) ) {
        
        return $place_data->image_url;
        
    } elseif( isset( $place_data->photos ) ) {
        
        $request_url = add_query_arg(
            array(
                'photoreference' => $place_data->photos[0]->photo_reference,
                'key'            => $api_key,
                'maxwidth'       => '800',
                'maxheight'      => '800',
            ),
            'https://maps.googleapis.com/maps/api/place/photo'
        );

        $response = rplg_urlopen( $request_url );

        foreach ( $response['headers'] as $header ) {
            if ( strpos( $header, 'Location: ') !== false ) {
                return str_replace('Location: ', '', $header);
            }
        }
    }
    
    return null;
}

/**
 * Render Google Reviews Layout
 * @since 1.0.0
 */
function premium_google_rev_reviews( $reviews, $settings) {
    
    $limit      = $settings['limit'];
    
    $min_filter = $settings['filter_min'];
    
    $max_filter = $settings['filter_max'];
    
    $show_date  = $settings['date'];
    
    $show_stars = $settings['stars'];
    
    $date_format= $settings['format'];
    
    $fill_color = $settings['fill_color'];
    
    $empty_color= $settings['empty_color'];
    
    $star_size  = $settings['stars_size'];
    
    $rev_text   = $settings['text'];
    
    $length     = $settings['rev_length'];
    
    $id         = $settings['id'];

    $readmore   = $settings['readmore'];

    $skin_type  = $settings['skin_type'];
    
?>

   <div class="premium-fb-rev-reviews">
    <?php if ( count( $reviews ) > 0) {
        array_splice( $reviews, $limit );
        foreach ( $reviews as $review ) {
            $review->more = false;
            
            if( $min_filter <= $review->rating && $review->rating <= $max_filter ) { 
                if ( strlen( $review->profile_photo_url ) > 0 ) {
                    $author_photo = $review->profile_photo_url;
                } else {
                    $author_photo = PREMIUM_FB_REV_AVATAR;
                } ?>
                <div class="premium-fb-rev-review-wrap">
                    <div class="premium-fb-rev-review">
                        <div class="premium-fb-rev-review-inner">
                        <?php if( $skin_type === "default" ){ ?>
                            <div class="premium-fb-rev-content-left">
                                <img class="premium-fb-rev-img" src="<?php echo $author_photo; ?>" alt="<?php echo $review->author_name; ?>" onerror="if( this.src!='<?php echo PREMIUM_FB_REV_AVATAR; ?>' ) this.src='<?php echo PREMIUM_FB_REV_AVATAR; ?>';">
                            </div>
                        <?php } ?>
                         <div class="premium-fb-rev-content-right">
                             <div class="premium-fb-rev-reviewer-wrapper">
                                <?php $person_link = '<a class="premium-fb-rev-reviewer-link" href="'. $review->author_url . '" target="_blank"><span>'. $review->author_name .'</span></a>';
                                    echo $person_link;
                                ?>
                             </div>

                        <?php if( $show_date || $show_stars ) : ?>
                            <div class="premium-fb-rev-info">
                                <?php if( $show_date ) : ?>
                                    <div class="premium-fb-rev-time"><span class="premium-fb-rev-time-text"><?php echo date( $date_format, $review->time ); ?></span></div>
                                <?php endif; ?>
                                <?php if( $show_stars ) : ?>
                                   <div class="premium-fb-rev-stars-container">
                                      <span class="premium-fb-rev-stars"><?php
                                          echo premium_fb_rev_stars( $review->rating, $fill_color, $empty_color, $star_size ); ?>
                                      </span>
                                  </div>
                                <?php endif; ?>
                            </div>
                       <?php endif;
                       if ( isset( $review->text ) && $rev_text ) : ?>
                            <div class="premium-fb-rev-rating">
                                <div class="premium-fb-rev-text-wrapper">
                                    <span class="premium-fb-rev-text"><?php $review->more = premium_fb_rev_trim_text( $review->text, $length ); ?></span>
                                    <?php if ( $review->more ) :
                                        $url = str_replace('reviews', 'place', $review->author_url );

                                        $review_url = sprintf( '%s/%s', $url, $id ); ?>

                                        <a class="premium-fb-rev-readmore" href="<?php echo $review_url; ?>" target="_blank" rel="noopener noreferrer"><?php echo $readmore; ?></a>
                                    <?php endif; ?>
                                </div>
                             </div>
                        <?php endif; ?>
                        <?php if($skin_type === "card"){ ?>
                            <div class="premium-fb-rev-content-left">
                                <img class="premium-fb-rev-img" src="<?php echo $author_photo; ?>" alt="<?php echo $review->author_name; ?>" onerror="if( this.src!='<?php echo PREMIUM_FB_REV_AVATAR; ?>' ) this.src='<?php echo PREMIUM_FB_REV_AVATAR; ?>';">
                            </div>
                        <?php } ?>
                         </div>
                    </div>
                </div>
            </div>
            <?php
            }
        }
    }
?>
   </div>
<?php }

/**
* Gets rating stars SVG
* @since 1.0.0
*/
function premium_fb_rev_stars( $rating, $fill_color, $empty_color, $star_size ) { 
    ?>
    <span class="premium-fb-rev-stars">
    <?php
        foreach (array( 1, 2, 3, 4, 5 ) as $val) {
            $score = $rating - $val;
            if ( $score >= 0 ) { ?>
            <span class="premium-fb-rev-star"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="<?php echo esc_attr($star_size); ?>" height="<?php echo esc_attr($star_size); ?>" viewBox="0 0 1792 1792"><path d="M1728 647q0 22-26 48l-363 354 86 500q1 7 1 20 0 21-10.5 35.5t-30.5 14.5q-19 0-40-12l-449-236-449 236q-22 12-40 12-21 0-31.5-14.5t-10.5-35.5q0-6 2-20l86-500-364-354q-25-27-25-48 0-37 56-46l502-73 225-455q19-41 49-41t49 41l225 455 502 73q56 9 56 46z" fill="<?php echo esc_attr($fill_color);?>"></path></svg></span>
            <?php } else if ($score > -1 && $score < 0) { ?>
            <span class="premium-fb-rev-star"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="<?php echo esc_attr($star_size); ?>" height="<?php echo esc_attr($star_size); ?>" viewBox="0 0 1792 1792"><path d="M1250 957l257-250-356-52-66-10-30-60-159-322v963l59 31 318 168-60-355-12-66zm452-262l-363 354 86 500q5 33-6 51.5t-34 18.5q-17 0-40-12l-449-236-449 236q-23 12-40 12-23 0-34-18.5t-6-51.5l86-500-364-354q-32-32-23-59.5t54-34.5l502-73 225-455q20-41 49-41 28 0 49 41l225 455 502 73q45 7 54 34.5t-24 59.5z" fill="<?php echo esc_attr($fill_color);?>"></path></svg></span>
            <?php } else { ?>
            <span class="premium-fb-rev-star"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="<?php echo esc_attr($star_size); ?>" height="<?php echo esc_attr($star_size); ?>" viewBox="0 0 1792 1792"><path d="M1201 1004l306-297-422-62-189-382-189 382-422 62 306 297-73 421 378-199 377 199zm527-357q0 22-26 48l-363 354 86 500q1 7 1 20 0 50-41 50-19 0-40-12l-449-236-449 236q-22 12-40 12-21 0-31.5-14.5t-10.5-35.5q0-6 2-20l86-500-364-354q-25-27-25-48 0-37 56-46l502-73 225-455q19-41 49-41t49 41l225 455 502 73q56 9 56 46z" fill="<?php echo esc_attr($empty_color); ?>"></path></svg></span>
            <?php
        }
    }
?>
    </span>
<?php }

function premium_fb_rev_trim_text( $text, $size ) {
    
    $length = count( preg_split('/\s+/', $text ) );
    
    if ( 0 < $size && $length >= $size ) {
                    
        $pieces = explode( " ", $text );
                    
        $text = implode( " ", array_splice( $pieces, 0, $size ) );

        echo $text . '...';
        
        return true;

    } else {
        
        echo $text;
        
    }
    
    return false;
}

function premium_yelp_rev_api_rating_place( $api_key, $place_id ) {

    $place_rating = rplg_urlopen( PREMIUM_YELP_API . '/' . $place_id, null, array( 'Authorization: Bearer ' . $api_key ) );

    return $place_rating;

}

/**
 * Gets Yelp Reviews API url
 * @since 1.5.8
 */
function premium_yelp_reviews_api( $business_id, $reviews_lang = '' ) {
     
    $url = PREMIUM_YELP_API . '/' . $business_id . '/reviews';
        
    $yrw_language = strlen( $reviews_lang ) > 0 ? $reviews_lang : get_option( 'yrw_language' );
    
    if ( strlen( $yrw_language ) > 0 ) {
        
        $url = $url . '?locale=' . $yrw_language;
        
    }
    
    return $url;
}

/**
 * Gets Yelp Reviews Data
 * @since 1.5.8
 */
function premium_yelp_reviews_data( $api_key, $place_id ) {
    
    $yelp_response = rplg_urlopen( premium_yelp_reviews_api( $place_id ), null, array( 'Authorization: Bearer ' . $api_key ) );

    return $yelp_response;
    
}

/**
 * Render Place Layout
 * @since 1.5.8
 */
function premium_yelp_rev_reviews( $reviews, $settings) {
    
        $limit      = $settings['limit'];

        $min_filter = $settings['filter_min'];

        $max_filter = $settings['filter_max'];

        $show_date  = $settings['date'];

        $show_stars = $settings['stars'];

        $date_format= $settings['format'];

        $fill_color = $settings['fill_color'];

        $empty_color= $settings['empty_color'];

        $star_size  = $settings['stars_size'];

        $rev_text   = $settings['text'];

        $length     = $settings['rev_length'];

        $readmore   = $settings['readmore'];

        $skin_type  = $settings['skin_type'];

    ?>

   <div class="premium-fb-rev-reviews">
    <?php if ( count( $reviews ) > 0) {
        array_splice( $reviews, $limit );
        foreach ( $reviews as $review ) {
            $review->more = false;
            
            if( $min_filter <= $review->rating && $review->rating <= $max_filter ) { 
                if ( strlen( $review->user->image_url ) > 0 ) {
                    $author_photo = $review->user->image_url;
                } else {
                    $author_photo = PREMIUM_FB_REV_AVATAR;
                } ?>
                <div class="premium-fb-rev-review-wrap">
                     <div class="premium-fb-rev-review">
                         <div class="premium-fb-rev-review-inner">
                         <?php if($skin_type === "default"){ ?>
                             <div class="premium-fb-rev-content-left">
                                 <img class="premium-fb-rev-img" src="<?php echo $author_photo; ?>" alt="<?php echo $review->name; ?>" onerror="if( this.src!='<?php echo PREMIUM_FB_REV_AVATAR; ?>' ) this.src='<?php echo PREMIUM_FB_REV_AVATAR; ?>';">
                             </div>
                         <?php } ?>
                             <div class="premium-fb-rev-content-right">
                                 <div class="premium-fb-rev-reviewer-wrapper">
                         <?php $person_link = '<a class="premium-fb-rev-reviewer-link" href="'. $review->user->profile_url . '" target="_blank"><span class="rating">'. $review->user->name .'</span></a>';
                             echo $person_link;
                         ?>
                                 </div>
                             <?php if( $show_date || $show_stars ) : ?>
                                 <div class="premium-fb-rev-info">
                                    <?php if( $show_date ) : ?>
                                            <div class="premium-fb-rev-time"><span class="premium-fb-rev-time-text"><?php echo date( $date_format, strtotime( $review->time_created ) );?></span></div>
                                    <?php endif; ?>
                                    <?php if( $show_stars ) : ?>
                                       <div class="premium-fb-rev-stars-container">
                                          <span class="premium-fb-rev-stars"><?php
                                              echo premium_fb_rev_stars( $review->rating, $fill_color, $empty_color, $star_size ); ?>
                                          </span>
                                      </div>
                                    <?php endif; ?>
                                 </div>
                                <?php endif;
                                if ( isset( $review->text ) && $rev_text ) : ?>
                                    <div class="premium-fb-rev-rating"> 
                                       <div class="premium-fb-rev-text-wrapper">
                                           <span class="premium-fb-rev-text reviews"><?php $review->more = premium_fb_rev_trim_text( $review->text, $length ); ?></span>
                                           <?php if ( $review->more && isset( $review->url ) ) :
                                                $url = $review->url;
                                            ?>
                                        
                                            <a class="premium-fb-rev-readmore" href="<?php echo $url; ?>" target="_blank" rel="noopener noreferrer"><?php echo $readmore; ?></a>
                                        <?php endif; ?>
                                       </div>
                                   </div>
                                <?php endif; ?>
                                <?php if($skin_type === "card"){ ?>
                                    <div class="premium-fb-rev-content-left">
                                        <img class="premium-fb-rev-img" src="<?php echo $author_photo; ?>" alt="<?php echo $review->name; ?>" onerror="if( this.src!='<?php echo PREMIUM_FB_REV_AVATAR; ?>' ) this.src='<?php echo PREMIUM_FB_REV_AVATAR; ?>';">
                                    </div>
                                <?php } ?>
                         </div>
                     </div>
                 </div>
            </div>
        <?php
            }
        }
    }
?>
   </div>
<?php }