<?php
!empty($args['data']) ? extract($args['data']) : array(); // data array contains all required var.
$all_listings = !empty($all_listings) ? $all_listings : new WP_Query;
$pagenation = get_directorist_option('paginate_all_listings', 1);
$display_sortby_dropdown = get_directorist_option('display_sort_by', 1);
$display_viewas_dropdown = get_directorist_option('display_view_as', 1);
wp_enqueue_style( 'atbdp-search-style', ATBDP_PUBLIC_ASSETS . 'css/search-style.css');
?>
<div id="directorist" class="atbd_wrapper">
    <?php if( $display_header == 'yes'  ) { ?>
        <div class="header_bar">
            <div class="<?php echo is_directoria_active() ? 'container': 'container-fluid'; ?>">
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        if(!empty($header_title) && !empty($listing_filters_button) && !empty($search_more_filters_fields)) {?>
                            <h3>
                                <?php echo esc_html($header_title); ?>
                            </h3>
                        <?php } ?>
                        <div class="atbd_generic_header">
                            <?php if(!empty($listing_filters_button) && !empty($search_more_filters_fields)) {?>
                                <div class="atbd_generic_header_title">
                                    <button class="more-filter btn btn-outline btn-outline-primary"><span class="fa fa-filter"></span> <?php echo $filters;?></button>
                                </div>
                            <?php } elseif((!empty($header_title) && empty($listing_filters_button)) || empty($search_more_filters_fields)) {?>
                                <h3>
                                    <?php echo esc_html($header_title); ?>
                                </h3>
                            <?php } ?>
                            <?php if ($display_viewas_dropdown || $display_sortby_dropdown) { ?>
                                <div class="atbd_listing_action_btn btn-toolbar" role="toolbar">
                                    <!-- Views dropdown -->
                                    <?php if ($display_viewas_dropdown) {
                                        $html = '<div class="dropdown">';
                                        $html .= '<a class="btn btn-outline-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        ' . __("View as", ATBDP_TEXTDOMAIN) . '<span class="caret"></span>
                                    </a>';
                                        $html .= '<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">';
                                        $views = atbdp_get_listings_view_options();
                                        foreach ($views as $value => $label) {
                                            $active_class = ($view == $value) ? ' active' : '';
                                            $html .= sprintf('<a class="dropdown-item%s" href="%s">%s</a>', $active_class, add_query_arg('view', $value), $label);

                                        }
                                        $html .= '</div>';
                                        $html .= '</div>';
                                        /**
                                         * @since 5.0.0
                                         * @package Directorist
                                         * @param htmlUms $html it return the markup for list and grid
                                         * @param string $view the shortcode attr view_as value
                                         * @param array $views it return the views type array
                                         *
                                         */
                                        echo apply_filters('atbdp_listings_view_as', $html, $view, $views);
                                        ?>
                                    <?php } ?>
                                    <!-- Orderby dropdown -->
                                    <?php
                                    if ($display_sortby_dropdown) {
                                        ?>
                                        <div class="dropdown">
                                            <a class="btn btn-outline-primary dropdown-toggle" href="#" role="button"
                                               id="dropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true"
                                               aria-expanded="false">
                                                <?php _e("Sort by", ATBDP_TEXTDOMAIN); ?> <span class="caret"></span>
                                            </a>

                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink2">
                                                <?php
                                                $options = atbdp_get_listings_orderby_options();

                                                foreach ($options as $value => $label) {
                                                    $active_class = ($value == $current_order) ? ' active' : '';
                                                    printf('<a class="dropdown-item%s" href="%s">%s</a>', $active_class, add_query_arg('sort', $value), $label);
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                        <!--ads advance search-->
                        <?php
                        $filters_display = !empty($filters_display)?$filters_display:'ads_slide';
                        ?>
                        <div class="<?php echo ('overlapping' === $filters_display)?'ads_float':'ads_slide'?>">
                            <div class="ads-advanced">
                                <form action="<?php echo ATBDP_Permalink::get_search_result_page_link(); ?>" role="form">
                                    <div class="atbd_seach_fields_wrapper"<?php echo empty($search_border)?'style="border: none;"':'';?>>
                                        <div class="row atbdp-search-form">
                                            <?php   if(in_array( 'search_text', $search_more_filters_fields )) { ?>
                                                <div class="col-md-6 col-sm-12 col-lg-4">
                                                    <div class="single_search_field search_query">
                                                        <input class="form-control search_fields" type="text" name="q"
                                                               placeholder="<?php echo esc_html($text_placeholder); ?>">
                                                    </div>
                                                </div>
                                            <?php } if(in_array( 'search_category', $search_more_filters_fields )) {?>
                                                <div class="col-md-6 col-sm-12 col-lg-4">
                                                    <div class="single_search_field search_category">
                                                        <?php
                                                        $args = array(
                                                            'show_option_none' =>  $category_placeholder,
                                                            'taxonomy' => ATBDP_CATEGORY,
                                                            'id' => 'cat-type',
                                                            'option_none_value'  => '',
                                                            'class' => 'form-control directory_field bdas-category-search',
                                                            'name' => 'in_cat',
                                                            'orderby' => 'name',
                                                            'selected' => isset( $_GET['in_cat'] ) ? (int) $_GET['in_cat'] : -1,
                                                            'hierarchical' => true,
                                                            'value_field'  => 'id',
                                                            'depth' => 10,
                                                            'show_count' => false,
                                                            'hide_empty' => false,
                                                        );

                                                        wp_dropdown_categories($args);
                                                        ?>
                                                    </div>
                                                </div>
                                            <?php } if(in_array( 'search_location', $search_more_filters_fields )) {?>
                                                <div class="col-md-12 col-sm-12 col-lg-4">
                                                    <div class="single_search_field search_location">
                                                        <?php
                                                        $args = array(
                                                            'show_option_none' =>  $location_placeholder,
                                                            'taxonomy' => ATBDP_LOCATION,
                                                            'id' => 'cat-type',
                                                            'option_none_value'  => '',
                                                            'class' => 'form-control directory_field',
                                                            'name' => 'in_loc',
                                                            'orderby' => 'name',
                                                            'selected' => isset( $_GET['in_loc'] ) ? (int) $_GET['in_loc'] : -1,
                                                            'hierarchical' => true,
                                                            'value_field'  => 'id',
                                                            'depth' => 10,
                                                            'show_count' => false,
                                                            'hide_empty' => false,
                                                        );

                                                        wp_dropdown_categories($args);
                                                        ?>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <?php
                                            /**
                                             * @since 5.0
                                             */
                                            do_action('atbdp_search_field_after_location');

                                            ?>
                                        </div>
                                    </div>
                                    <?php if(in_array( 'search_price', $search_more_filters_fields) || in_array( 'search_price_range', $search_more_filters_fields) ) { ?>
                                        <div class="form-group ">
                                            <label class=""><?php _e('Price Range', ATBDP_TEXTDOMAIN);?></label>
                                            <div class="price_ranges">
                                                <?php if(in_array( 'search_price', $search_more_filters_fields)) { ?>
                                                <div class="range_single">
                                                    <input type="text" name="price[0]" class="form-control" placeholder="Min Price" value="<?php if( isset( $_GET['price'] ) ) echo esc_attr( $_GET['price'][0] ); ?>">
                                                </div>
                                                <div class="range_single">
                                                    <input type="text" name="price[1]" class="form-control" placeholder="Max Price" value="<?php if( isset( $_GET['price'] ) ) echo esc_attr( $_GET['price'][1] ); ?>">
                                                </div>
                                                <?php } if(in_array( 'search_price_range', $search_more_filters_fields )) {?>
                                                    <div class="price-frequency">
                                                        <label class="pf-btn"><input type="radio" name="price_range" value="bellow_economy"<?php if(!empty($_GET['price_range']) && 'bellow_economy' == $_GET['price_range']) { echo "checked='checked'";}?>><span>$</span></label>
                                                        <label class="pf-btn"><input type="radio" name="price_range" value="economy" <?php if(!empty($_GET['price_range']) && 'economy' == $_GET['price_range']) { echo "checked='checked'";}?>><span>$$</span></label>
                                                        <label class="pf-btn"><input type="radio" name="price_range" value="moderate" <?php if(!empty($_GET['price_range']) && 'moderate' == $_GET['price_range']) { echo "checked='checked'";}?>><span>$$$</span></label>
                                                        <label class="pf-btn"><input type="radio" name="price_range" value="skimming" <?php if(!empty($_GET['price_range']) && 'skimming' == $_GET['price_range']) { echo "checked='checked'";}?>><span>$$$$</span></label>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div><!-- ends: .form-group -->
                                    <?php } ?>
                                    <?php if(in_array( 'search_rating', $search_more_filters_fields )) { ?>
                                        <div class="form-group">
                                            <label><?php _e('Filter by Ratings', ATBDP_TEXTDOMAIN);?></label>
                                            <select class="select-basic form-control">
                                                <option value=""><?php _e('Select Ratings', ATBDP_TEXTDOMAIN);?></option>
                                                <option name='search_by_rating' value="5" <?php if(!empty($_GET['search_by_rating']) && '5' == $_GET['search_by_rating']) { echo "checked='checked'";}?>>5 Star</option>
                                                <option name='search_by_rating' value="4" <?php if(!empty($_GET['search_by_rating']) && '4' == $_GET['search_by_rating']) { echo "checked='checked'";}?>>4 Star & Up</option>
                                                <option name='search_by_rating' value="3" <?php if(!empty($_GET['search_by_rating']) && '3' == $_GET['search_by_rating']) { echo "checked='checked'";}?>>3 Star & Up</option>
                                                <option name='search_by_rating' value="2" <?php if(!empty($_GET['search_by_rating']) && '2' == $_GET['search_by_rating']) { echo "checked='checked'";}?>>2 Star & Up</option>
                                                <option name='search_by_rating' value="1" <?php if(!empty($_GET['search_by_rating']) && '1' == $_GET['search_by_rating']) { echo "checked='checked'";}?>>1 Star & Up</option>
                                            </select>
                                        </div><!-- ends: .form-group -->
                                    <?php } if(in_array( 'search_open_now', $search_more_filters_fields ) && in_array( 'directorist-business-hours/bd-business-hour.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) )) { ?>
                                        <div class="form-group">
                                            <label>Open Now</label>
                                            <div class="check-btn">
                                                <div class="btn-checkbox">
                                                    <label>
                                                        <input type="checkbox" name="open_now" value="open_now" <?php if(!empty($_GET['open_now']) && 'open_now' == $_GET['open_now']) { echo "checked='checked'";}?>>
                                                        <span><i class="fa fa-clock-o"></i><?php _e('Open Now', ATBDP_TEXTDOMAIN);?> </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div><!-- ends: .form-group -->
                                    <?php } if(in_array( 'search_tag', $search_more_filters_fields )) {
                                    $terms = get_terms(ATBDP_TAGS);
                                    if(!empty($terms)) {
                                        ?>
                                        <div class="form-group ads-filter-tags">
                                            <label>Tags</label>
                                            <div class="bads-tags">
                                                <?php

                                                    foreach($terms as $term) {
                                                        ?>
                                                        <div class="custom-control custom-checkbox checkbox-outline checkbox-outline-primary">
                                                            <input type="checkbox" class="custom-control-input" name="in_tag" value="<?php echo $term->term_id;?>" id="<?php echo $term->term_id;?>">
                                                            <span class="check--select"></span>
                                                            <label for="<?php echo $term->term_id;?>" class="custom-control-label"><?php echo $term->name;?></label>
                                                        </div>
                                                    <?php }?>
                                            </div>
                                            <a href="#" class="more-less ad"><?php _e('Show More', ATBDP_TEXTDOMAIN);?></a>
                                        </div><!-- ends: .form-control -->
                                    <?php } } if(in_array( 'search_custom_fields', $search_more_filters_fields )) { ?>
                                        <div id="atbdp-custom-fields-search" class="atbdp-custom-fields-search">
                                            <?php do_action( 'wp_ajax_atbdp_custom_fields_search', isset( $_GET['in_cat'] ) ? (int) $_GET['in_cat'] : 0 ); ?>
                                        </div>
                                    <?php } ?>
                                    <?php if(in_array( 'search_website', $search_more_filters_fields ) || in_array( 'search_email', $search_more_filters_fields ) || in_array( 'search_phone', $search_more_filters_fields ) || in_array( 'search_address', $search_more_filters_fields ) || in_array( 'search_zip_code', $search_more_filters_fields )) {?>
                                        <div class="form-group">
                                            <div class="bottom-inputs">
                                                <?php if(in_array( 'search_website', $search_more_filters_fields )) {?>
                                                    <div>
                                                        <input type="text" name="website" placeholder="<?php _e('Website', ATBDP_TEXTDOMAIN);?>" value="<?php echo !empty($_GET['website']) ? $_GET['website'] : ''; ?>" class="form-control">
                                                    </div>
                                                <?php } if(in_array( 'search_email', $search_more_filters_fields )) {?>
                                                    <div>
                                                        <input type="text" name="email" placeholder=" <?php _e('Email', ATBDP_TEXTDOMAIN);?>" value="<?php echo !empty($_GET['email']) ? $_GET['email'] : ''; ?>" class="form-control">
                                                    </div>
                                                <?php } if(in_array( 'search_phone', $search_more_filters_fields )) {?>
                                                    <div>
                                                        <input type="text" name="phone" placeholder="<?php _e('Phone Number', ATBDP_TEXTDOMAIN);?>" value="<?php echo !empty($_GET['phone']) ? $_GET['phone'] : ''; ?>" class="form-control">
                                                    </div>
                                                <?php } if(in_array( 'search_address', $search_more_filters_fields )) {?>
                                                    <div>
                                                        <input type="text" name="address" value="<?php echo !empty($_GET['address']) ? $_GET['address'] : ''; ?>" placeholder="<?php _e('Address', ATBDP_TEXTDOMAIN);?>"
                                                               class="form-control location-name">
                                                    </div>
                                                <?php } if(in_array( 'search_zip_code', $search_more_filters_fields )) {?>
                                                    <div>
                                                        <input type="text" name="zip_code" placeholder=" <?php _e('Zip/Post Code', ATBDP_TEXTDOMAIN);?>" value="<?php echo !empty($_GET['zip_code']) ? $_GET['zip_code'] : ''; ?>" class="form-control">
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <div class="bdas-filter-actions">

                                        <a href="<?php echo get_permalink();?>" class="btn btn-outline btn-outline-primary btn-lg"><?php _e('Reset Filters', ATBDP_TEXTDOMAIN);?></a>

                                        <button type="submit" class="btn btn-primary btn-lg"><?php _e('Apply Filters', ATBDP_TEXTDOMAIN);?></button>

                                    </div><!-- ends: .bdas-filter-actions -->
                                </form>
                            </div> <!--ads advanced -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php }
            /**
             * @since 5.0
             * It fires before the listings columns
             * It only fires if the parameter [directorist_all_listing action_before_after_loop="yes"]
             */
            $action_before_after_loop = !empty($action_before_after_loop)?$action_before_after_loop:'';
            if ('yes' === $action_before_after_loop) {
                do_action('atbdp_before_list_listings_loop');
            }
            ?>
            <?php if ($all_listings->have_posts()) { ?>
                <?php listing_view_by_list($all_listings); ?>
            <?php } else { ?>
                <p><?php _e('No listing found.', ATBDP_TEXTDOMAIN); ?></p>
            <?php }
            /**
             * @since 5.0
             * It fires before the listings columns
             * It only fires if the parameter [directorist_all_listing action_before_after_loop="yes"]
             */
            $action_before_after_loop = !empty($action_before_after_loop)?$action_before_after_loop:'';
            if ('yes' === $action_before_after_loop) {
                do_action('atbdp_after_list_listings_loop');
            }
            ?>
    <?php
    /**
     * @since 5.0
     */
    do_action('atbdp_before_listings_pagination');

    $show_pagination = !empty($show_pagination) ? $show_pagination : '';
    if (('yes' === $show_pagination)) {
        if (1 == $pagenation) {
            ?>
            <div class="row">
                <div class="col-md-12">
                    <?php
                    $paged = !empty($paged) ? $paged : '';
                    echo atbdp_pagination($all_listings, $paged);
                    ?>
                </div>
            </div>
        <?php }
    } ?>
        </div> <!--ends .row -->
    </div>
</div>
