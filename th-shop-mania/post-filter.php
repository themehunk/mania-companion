<?php
if (!class_exists('Th_Simple_Post_filter')) {
    class Th_Simple_Post_filter
    {
        public function elemento_get_category()
        {
            $args = array(
                'taxonomy'     => 'product_cat',
                'orderby'      => 'name',
                'show_count'   => 1,
                'pad_counts'   => 0,
                'hierarchical' => 0,
                'title_li'     => '',
                'hide_empty'   => true
            );
            $returnArray = ['0' => "No Category Found"];
            $all_categories = get_categories($args);
            if (!empty($all_categories)) {
                $returnArray = [];
                $returnArray['all'] = __('All', 'mania-companion');
                foreach ($all_categories as $cateValue) {
                    $returnArray[$cateValue->slug] = $cateValue->name;
                }
            }
            return $returnArray;
        }
        public function simple_product_slider($cate = [], $options = [])
        {
            if (is_array($cate) && !empty($cate)) {
                $numOfproduct = $options['number_of_product'];
                $args = array(
                    'post_type' => 'product',
                    'posts_per_page' => $numOfproduct,
                    'meta_query' => array(
                        array(
                            'key' => '_stock_status',
                            'value' => 'instock'
                        ),
                        array(
                            'key' => '_backorders',
                            'value' => 'no'
                        ),
                    )
                );
                $stringCate = '';
                $stringCate = implode(",", $cate);
                if (!in_array('all', $cate)) {
                    $args['product_cat'] = $stringCate;
                }
                // random, featured, recently 
                if (isset($options['product_show_by'])) {
                    if ($options['product_show_by'] == 'featured') {
                        $args['post__in'] = wc_get_featured_product_ids();
                        $args['orderby'] = 'date';
                    } else if ($options['product_show_by'] == 'random') {
                        $args['orderby'] = 'rand';
                    } else {
                        $args['orderby'] = 'date';
                    }
                }
                $query = new WP_Query($args);
                $productHtml = ''; ?>
                <div class="ea-simple-product-slider">
             <?php   if ($query->have_posts()) {
                    echo $this->product_slide($query, $options, $stringCate);
                } ?>
                </div>
           <?php     return $productHtml;
            } else { ?>
                 <p><?php esc_html_e('No Product','mania-companion');?> </p>
          <?php  }
        }
        // slider slides 
        private function product_slide($query, $options)
        {
            // return $cate;
            // html options 
            $sliderSetting = [
                'items' => $options['number_of_column_slide'],
            ];
            // number of column mobile and tablet
            $sliderSetting['items_mobile'] = $options['number_of_column_slide_mobile'];
            $sliderSetting['items_tablet'] = $options['number_of_column_slide_tablet'];
            // slider autoplay and speed 
            if (isset($options['slider_auto_play']) && $options['slider_auto_play'] == 'on' && isset($options['autoPlaySpeed']) && intval($options['autoPlaySpeed'])) {
                $sliderSetting['autoplay'] = true;
                $sliderSetting['autoPlaySpeed'] = $options['autoPlaySpeed'];
            }
            // slider controll 
            if (isset($options['slider_controll'])) {
                $sliderSetting['slider_controll'] = $options['slider_controll'];
                if ($options['slider_controll'] == 'arr' || $options['slider_controll'] == 'ar_do') {
                    $availNextPrevious = true;
                }
            }
            // slider loop 
            if (isset($options['slider_loop']) && $options['slider_loop'] == '1') {
                $sliderSetting['slider_loop'] = 1;
            }
            // slider direction 
            if (isset($options['autoPlayDirection']) && $options['autoPlayDirection'] == 'l') {
                $sliderSetting['autoPlayDirection'] = 'l';
            }
            // slider_auto_play

            $dataSetting = wp_json_encode($sliderSetting);

            $productHtml = '';
            ?>
            <div class="woocommerce elemento-owl-slider-common-secript" data-setting="<?php echo esc_attr($dataSetting); ?>"> <?php
            if (isset($availNextPrevious)) {
                $arrowType = '-alt'; //1 
                // if ($options['arrow_type'] == '2') {
                //     $arrowType = '-alt2'; //2
                // } else if ($options['arrow_type'] == '3') {
                //     $arrowType = ''; //3
                // } ?>
                <div class="elemento-addons-owl-np-cln elemento-addons-owl-prev"><span class="dashicons dashicons-arrow-left <?php echo esc_attr($arrowType); ?>"></span></div>
                <div class="elemento-addons-owl-np-cln elemento-addons-owl-next"><span class="dashicons dashicons-arrow-right<?php echo esc_attr($arrowType); ?>"></span></div>
        <?php    }  ?>
            <div class='elemento-owl-slider owl-carousel owl-theme'>
        <?php    if ($query->have_posts()) {
                if (isset($options['number_of_row']) && $options['number_of_row'] == '2') {
                    $countRow = 1;
                    $countTotal = count($query->posts);
                    $itemOpen = true; ?>
                    <div class="item">
                <?php    while ($query->have_posts()) {
                        $query->the_post();
                        $productId = get_the_ID();
                        $this->product_html1($productId, $options);
                        if ($countRow % 2 === 0) { ?>
                            </div>
                        <?php    $itemOpen = false;
                            if ($countTotal != $countRow) {
                                $itemOpen = true;  ?>
                                <div class="item">
                        <?php    }
                        }
                        $countRow++;
                    }
                    if ($itemOpen) { ?>
                        </div>
                   <?php }
                } else {
                    while ($query->have_posts()) {
                        $query->the_post();
                        $productId = get_the_ID();  ?>
                        <div class="item">
                      <?php  $this->product_html1($productId, $options); ?>
                        </div>
                <?php    }
                }
            }  ?>
            </div>
            <div>
         <?php   
        }
        public function product_html1($productId, $options)
        {
            $product = wc_get_product($productId);
            $regularPrice = $product->get_regular_price();
            $currentPrice = $product->get_price();
            $currentPricehtml = $product->get_price_html();
            $rating = $product->get_average_rating();
            $count_rating = $product->get_rating_count();
            $ratingHtml = $count_rating > 0 ? wc_get_rating_html($rating, $count_rating) : false;

            $checkSale = $regularPrice > $currentPrice ? true : false;
            
            // add to cart --------------
            $addToCart = '';
            // $textAddTocart = $options['add_to_cart_text'] !== '' && $options['add_to_cart_text'] ? $options['add_to_cart_text'] : false;
            $iconAddTocart = $options['add_to_cart_icon_on'] == 'on' && $options['add_to_cart_icon_on'] ? true : false;
            // $addToCart = $this->elemento_add_tocart($product, $iconAddTocart);
            // quick view --------------
            // price --------------
            $price = '<span class="elemento-addons-price">' . $currentPricehtml . '</span>';

            $returnHtml = ''; ?>
            <div class="elemento-product-outer-wrap"> <!--inner wrap -->
  <?php     
    // $wishlist_ = elemento_addons_wishlist_wpc($productId);
            // $compare_ = elemento_addons_compare($productId);
        ?>
            <div class="elemento-product-simple-inner-wrap"> 
                <?php 
            //inner rap
            // quick view 
            if (function_exists('th_elemento_addon_quickView_enable')) { ?>
                <a href="#" data-product="<?php echo esc_attr($productId); ?>" class="elemento-addons-quickview-simple"><?php echo __('Quick View','mania-companion'); ?></a>
          <?php  }
            // sale price 
            $ps_sale = '';
            if ($checkSale) {
                $salePrice = $regularPrice - $currentPrice;
                // $saleText = __('Sale', 'mania-companion');
                $currency_ = get_woocommerce_currency_symbol(); ?>
                <div class="elemento-addons-sale">
                    <span class="elemento-addons-sale-tag"><?php echo '-'.esc_html($currency_).esc_html($salePrice); ?></span>
                </div>
         <?php   } ?>
        <a class="img_" href="<?php echo esc_url(get_permalink($productId)); ?>">
            <?php echo wp_kses_post( $product->get_image() ); ?>
                                    
        </a>

          <a class="elemento-addons-product-title" href="<?php echo esc_url(get_permalink($productId)); ?>">
            <?php echo wp_kses_post( $product->get_name() ); ?>
               </a>
          <?php
          if ($ratingHtml) { ?>
             <div class="elemento-addons-rating">
               <?php echo wp_kses_post( $ratingHtml ); ?> 
             </div>
         <?php } 
         // add to cart 
         ?>
         <span class="elemento-addons-price">
            <?php echo wp_kses_post( $currentPricehtml );  ?>
         </span>
           
            </div> <?php //inner rap ?>

            <div class='elemento-product-simple-inner-bottom'>
          <?php   //button wrap
            echo $this->elemento_add_tocart($product, $iconAddTocart);
            
                // buttons icon  ?>
                <div class='buttons_'>
                <?php 
                elemento_addons_wishlist_wpc($productId);
                 elemento_addons_compare($productId); ?>
                </div>
             
            </div> <?php //button wrap
             ?>
            </div> <!--inner wrap -->
       <?php     
        }
        private function product_html2($productId, $product, $ps_sale, $ratingHtml, $price, $addToCart)
        {
            $wishlist_ = elemento_addons_wishlist_wpc($productId);
            $compare_ = elemento_addons_compare($productId);

            $productHtml = '<div class="elemento-product-simple-inner-wrap">'; //inner rap
            // quick view 
            if (function_exists('th_elemento_addon_quickView_enable')) {
                $productHtml .= '<a href="#" data-product="' . $productId . '" class="elemento-addons-quickview-simple">' . __('Quick View','mania-companion') . '</a>';
            }
            $productHtml .= $ps_sale;
            $productHtml .= '<a class="img_" href="' . get_permalink($productId) . '">
                                    ' . $product->get_image() . '
                                    </a>';

            $productHtml .= '<a class="elemento-addons-product-title" href="' . get_permalink($productId) . '">' . $product->get_name() . '</a>';
            $productHtml .= $ratingHtml ? '<div class="elemento-addons-rating">' . $ratingHtml . '</div>' : '';
            // add to cart 
            $productHtml .=  $price;
            $productHtml .=  '</div>'; //inner rap

            $productHtml .=  "<div class='elemento-product-simple-inner-bottom'>"; //button wrap
            $productHtml .=  $addToCart;
            if ($wishlist_ || $compare_) {
                // buttons icon 
                $productHtml .=  "<div class='buttons_'>";
                $productHtml .=  $wishlist_;
                $productHtml .=  $compare_;
                $productHtml .=  "</div>";
                // buttons icon 
            }
            $productHtml .=  "</div>"; //button wrap
            return $productHtml;
        }
        function  elemento_add_tocart($product,  $icon)
        {
            $icon_ = $icon ? '<span class="dashicons dashicons-cart"></span>' : '';
            $cart_url =  apply_filters(
                'woocommerce_loop_add_to_cart_link',
                sprintf(
                    '<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" data-quantity="%s" class="elemento-add-to-cart-btn button %s %s">%s %s</a>',
                    esc_url($product->add_to_cart_url()),
                    esc_attr($product->get_id()),
                    esc_attr($product->get_sku()),
                    1,
                    $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
                    $product->is_purchasable() && $product->is_in_stock() && $product->supports('ajax_add_to_cart') ? 'ajax_add_to_cart' : '',
                    $icon_,
                    esc_html($product->add_to_cart_text()),
                ),
                $product
            );
            return $cart_url;
        }

        // class end 
    }
}
