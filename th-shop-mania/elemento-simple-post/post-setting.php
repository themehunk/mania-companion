<?php
class elemento_post_simple
{
    //post category title and slug
    public static function postcategory()
    {
        $category = get_categories();
        if (!empty($category)) {
            $arrcate = ['all' => "All"];
            foreach ($category as $cate_value) {
                $arrcate[$cate_value->slug] = $cate_value->name;
            }
            return $arrcate;
        }
    }
    // show in page 
    function post_html($settings)
    { ?>
        <div class="elemento-addons-simple-post">
        <?php $this->ListGridLayout($settings);  ?>
        </div>
        
    <?php }
    //grid list layout 
    private function ListGridLayout($options)
    {
        if (isset($options['post_category']) && is_array($options['post_category']) && !empty($options['post_category'])) {
            $numOfPost = absint($options['number_of_post']);
            // $numOfcolumn = $options['number_of_column'];
            $args = array(
                'post_type' => 'post',
                'posts_per_page' => absint($numOfPost),
            );
            $stringCate = $dataSetting = $postHtml = '';
            $stringCate = implode(",", $options['post_category']);
            if (!in_array('all', $options['post_category'])) {
                $args['category_name'] = $stringCate;
            }
            // post show by 
            if ($options['post_show_by'] != 'recent') {
                $args['orderby'] = sanitize_key($options['post_show_by']);
            }
            // html options 
            $query = new WP_Query($args);
            if ($query->have_posts()) {
                // post pagination 
                $pagination_ = '';
               
                // post pagination ?>
                <div class='elemento-post-layout-listGrid' data-setting="<?php echo esc_attr($dataSetting); ?>">
               <?php while ($query->have_posts()) {
                    $query->the_post();
                    $post_id_ = get_the_ID(); ?>
                    <div class="elemento-post-layout-iteme"><div>
                <?php   $this->postContentHtml($post_id_, $options);  ?>
                    </div></div>
            <?php    } ?>
                </div>
            <?php   // Pagination
                     if ($options['pagination_show'] == 'on') {
                    $totalPosts = $query->found_posts;
                    if ($totalPosts > $numOfPost) {
                        $totalPages = ceil($totalPosts / $numOfPost);
                        $dataSetting_array = array(
                            "current_page" => 1,
                            "total_page" => $totalPages,
                            "post_per_page" => $numOfPost,
                            "category" => $options['post_category'],
                            'post_show_by' => $options['post_show_by'],
                            'pagination' => [
                                'pagination_show' => $options['pagination_show'],
                                // 'pagination_page_limit' => $options['pagination_page_limit'],
                                // 'pagination_shorten' => $options['pagination_shorten']
                            ],
                            'options' => [
                                // 'post_title_anable' => $options['post_title_anable'],
                                'post_title_tag' => $options['post_title_tag'],
                                'post_meta_data' => $options['post_meta_data'],
                                'excerpt_anable' => $options['excerpt_anable'],
                                'excerpt_length' => $options['excerpt_length'],
                                // 'read_more_enable' => $options['read_more_enable'],
                                'read_more_new_tab' => $options['read_more_new_tab'],
                                'read_more_text' => $options['read_more_text'],
                            ]
                        );
                        $dataSetting = wp_json_encode($dataSetting_array);
                        $this->pagination($totalPages, 1);
                    }
                }
            }
            
        }
    }
    public function pagination($totalPAges, $currentPage, $ExternalProvideLinks = 4)
    {
        $pagination_ = '';
        $currentPAgeVAr2 =  $currentPage; ?>
        <div class="elemento-addons-pagination">
            <?php
        // next previous btn 
        $disableAndEnable =  $currentPage < 2 ? 'disable' : ""; ?>
        <a href="#" data-link="prev" class="elemento-post-link <?php echo esc_attr($disableAndEnable); ?>"> <?php echo esc_html__('Previous','mania-companion'); ?> </a>
       <?php  // pagination link number 
        // -------------------------
        // 1 - left links = 1 ,self link = 1 ==then==   2 
        // 2 - now show links ExternalProvideLinks - 2 step 1
        $shoLInksRight = $ExternalProvideLinks - 2;
        // left link 
        if ($currentPAgeVAr2 > 1) {
            $paginationMinus = $currentPAgeVAr2 - 1; ?>
            <a href="#" data-link="<?php echo esc_attr($paginationMinus); ?>" class="elemento-post-link"><?php echo esc_html($paginationMinus); ?></a>
     <?php   }
        // current link  ?>
       <a href="#" data-link="<?php echo esc_attr($currentPAgeVAr2); ?>" class="elemento-post-link active"><?php echo esc_html($currentPAgeVAr2); ?></a>
       <?php
        // right links -----------
        $calculateLink = $currentPAgeVAr2 + $shoLInksRight;
        $appearLast = true;
        //check total
        if ($totalPAges > $calculateLink) {
            $calculateLoop = $calculateLink;
        } else {
            $appearLast = false;
            $calculateLoop = $totalPAges;
        }
        $loopNumberGEt = $calculateLoop - $currentPAgeVAr2;

        for ($_i = 0; $_i < $loopNumberGEt; $_i++) {
            $currentPAgeVAr2++;   ?>
            <a href="#" data-link="<?php echo esc_attr($currentPAgeVAr2); ?>" class="elemento-post-link"><?php echo esc_html($currentPAgeVAr2); ?></a>
     <?php   }
        // right links -----------
        // last page link -----------
        if ($appearLast) { ?>
            <a href="#" class="_last-page"><?php esc_html_e('...','mania-companion'); ?></a>
            <a href="#" data-link="<?php echo esc_attr($totalPAges); ?>" class="elemento-post-link _last-page"><?php echo esc_html($totalPAges); ?></a>
     <?php   }
        // -------------------------
        // next previous btn 
        $disableAndEnable = $currentPage == $totalPAges ? 'disable' : ""; ?>
        <a href="#" data-link="next" class="elemento-post-link <?php echo esc_attr($disableAndEnable); ?>"><?php echo esc_html__('Next','mania-companion'); ?></a> 
        </div>
        <?php
        // pagination data -------------- 
        
    }

    //slider list html
    public function postContentHtml($post_id_, $options)
    {
        $postLink = get_permalink();
        $imageFeaturedImage = '';

        $category_detail = get_the_category($post_id_);
        $category_ = '';
        

        if (get_the_post_thumbnail_url()) {  ?>
            <a href="<?php echo esc_url($postLink); ?>" class='elemento-featured-image'>
            <img src="<?php echo esc_url(get_the_post_thumbnail_url()); ?>" class='elemento-featured-image-image'>
            </a>
   <?php     } ?>
        <div class='elemento-post-content-all'>
      <?php  // content ------------------
               ?>
        <div class='elemento-post-content'>
        <?php // image  
        if (!empty($category_detail)) {
            $category_detail = json_decode(json_encode($category_detail), true);
            $newCAtegory = array_slice($category_detail, 0, 3); ?>
            <div class="_category_">
          <?php  foreach ($newCAtegory as $value_) {
                $category_link = get_category_link($value_['cat_ID']);
                $category_ .= $value_['name'];
                  ?>
                <a target='_self' href="<?php echo esc_url($category_link);  ?>">
              <?php echo esc_html($category_); ?>
                </a>
       <?php     }  ?>
            </div>
    <?php    } ?>
       <<?php echo esc_attr($options['post_title_tag']); ?> class="elemento-post-title"> 
        <a href="<?php echo esc_url($postLink); ?>">
        <?php echo wp_kses_post( get_the_title() );  ?>
        </a>
        </<?php echo esc_attr($options['post_title_tag']); ?> >
    <?php 
        //post meta 
        if (!empty($options['post_meta_data'])) {  ?>
            <div class="elemento-post-meta-data">
        <?php    if (in_array("author", $options['post_meta_data'])) { ?>
                <span class="elemento-post-author"><?php echo wp_kses_post(get_the_author()); ?></span>
         <?php   }
            if (in_array("date", $options['post_meta_data'])) { ?>
                <span class="elemento-post-date"><?php echo wp_kses_post(get_the_date('F j, Y')); ?></span>
        <?php    }
            // if (in_array("time", $options['post_meta_data'])) {
            //     $html .= '<span class="elemento-post-time">' . get_post_time() . '</span>';
            // }
            if (in_array("comments", $options['post_meta_data'])) {
                $showComment = get_comments_number() ? get_comments_number() : __('No', 'mania-companion'); ?>
                <span class="elemento-post-comments">
                <?php echo esc_html($showComment)  . ' ' . __('Comment', 'mania-companion'); ?>
                </span>
       <?php     } 
            // if (in_array("datemodified", $options['post_meta_data'])) {
            //     $html .= '<span class="elemento-post-date">' . get_the_author() . '</span>';
            // } ?>
            </div>
     <?php   }
        // excerpt 
        if ($options['excerpt_anable'] == 'on') {
            $excerpt_ = get_the_excerpt();
            $excerpt_ = substr($excerpt_, 0, $options['excerpt_length']);
            $result_ = substr($excerpt_, 0, strrpos($excerpt_, ' ')); ?>
            <div class="elemento-post-excerpt"><p><?php echo esc_html($result_); ?></p></div>
   <?php     }
        // read more 
        // read_more_new_tab
        $readMoreNewTab = $options['read_more_new_tab'] == 'on' ? '_blank' : "_self"; ?>
        <a class="elemento-post-read-more" target="<?php echo esc_attr($readMoreNewTab); ?>" href="<?php echo esc_url($postLink); ?>"><?php echo esc_html($options['read_more_text']); ?></a>
        </div>
       
        </div>
        
  <?php  }
    //grid list layout

}
