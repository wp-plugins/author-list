<?php
/*
  Plugin Name: Author List
  Plugin URI: http://buffercode.com/project/author-list/
  Description: Easy way to display the number of post in that particular category by selecting from admin dashboard widget.
  Version: 2.0
  Author: vinoth06
  Author URI: http://buffercode.com/
  License: GPLv2
  License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

//Adding CSS Font
function buffercode_author_list_font_css() {
    if (!is_admin()) {
        wp_enqueue_style('bc_author_list', plugins_url('css\bc-author-list.css', __FILE__));
        wp_enqueue_style('bc_author_list_tool_tip', plugins_url('css\tooltip.css', __FILE__));

        wp_enqueue_script(
                'bc_author_list_script', plugins_url('js\tooltip.js', __FILE__), array('jQuery')
        );
    }
}

add_action('wp_enqueue_scripts', 'buffercode_author_list_font_css');


// Additing Action hook widgets_init
add_action('widgets_init', 'buffercode_author_list');

function buffercode_author_list() {
    register_widget('buffercode_author_list_info');
}

class buffercode_author_list_info extends WP_Widget {

    function buffercode_author_list_info() {
        $this->WP_Widget('buffercode_author_list_info', 'Author List', array('description' => __('Author List Display', 'buffercode_author_list_td'),));
    }

    public function form($instance) {

        $buffercode_author_list_options = array('1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9', '10' => '10', 'All' => '99');

        $buffercode_author_list_cutom_title = isset($instance['buffercode_author_list_cutom_title']) ? $instance['buffercode_author_list_cutom_title'] : 'Our Authors';

        $buffercode_author_list_limit = isset($instance['buffercode_author_list_limit']) ? $instance['buffercode_author_list_limit'] : 5;

        $buffercode_author_list_img_size = isset($instance['buffercode_author_list_img_size']) ? $instance['buffercode_author_list_img_size'] : 48;
        ?>

        <p>Custom Name: <input class="widefat" name="<?php echo $this->get_field_name('buffercode_author_list_cutom_title'); ?>" type="text" value="<?php echo esc_attr($buffercode_author_list_cutom_title); ?>" /></p>

        <p>Number of Authors:
            <select name="<?php echo $this->get_field_name('buffercode_author_list_limit'); ?>" class="widefat">
                <?php
                foreach ($buffercode_author_list_options as $buffercode_author_list_langu => $buffercode_author_list_code) {
                    echo '<option value="' . $buffercode_author_list_code . '" id="' . $buffercode_author_list_code . '"', $buffercode_author_list_limit == $buffercode_author_list_code ? ' selected="selected"' : '', '>', $buffercode_author_list_langu, '</option>';
                }
                ?>
            </select></p>

        <p>Author Image Size:
            <select name="<?php echo $this->get_field_name('buffercode_author_list_img_size'); ?>" id="<?php echo $this->get_field_id('buffercode_author_list_img_size'); ?>" class="widefat">
                <?php
                $buffercode_author_list_img_options = array('24' => '24', '48' => '48', '96' => '96');
                foreach ($buffercode_author_list_img_options as $buffercode_author_list_img_langu => $buffercode_author_list_img_code) {
                    echo '<option value="' . $buffercode_author_list_img_code . '" id="' . $buffercode_author_list_img_code . '"', $buffercode_author_list_img_size == $buffercode_author_list_img_code ? ' selected="selected"' : '', '>', $buffercode_author_list_img_langu, '</option>';
                }
                ?>
            </select></p>


        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['buffercode_author_list_cutom_title'] = (!empty($new_instance['buffercode_author_list_cutom_title']) ) ? strip_tags($new_instance['buffercode_author_list_cutom_title']) : '';

        $instance['buffercode_author_list_limit'] = (!empty($new_instance['buffercode_author_list_limit']) ) ? strip_tags($new_instance['buffercode_author_list_limit']) : '';

        $instance['buffercode_author_list_img_size'] = (!empty($new_instance['buffercode_author_list_img_size']) ) ? strip_tags($new_instance['buffercode_author_list_img_size']) : '';

        return $instance;
    }

    function widget($args, $instance) {
        extract($args);
        $k = 0;
        $i = 1;
        $j = 1;
        echo $before_widget;
        $bc_name_value = apply_filters('widget_title', $instance['buffercode_author_list_cutom_title']);

        $buffercode_author_list_limit = empty($instance['buffercode_author_list_limit']) ? '&nbsp;' :
                $instance['buffercode_author_list_limit'];

        $buffercode_author_list_img_size = empty($instance['buffercode_author_list_img_size']) ? '&nbsp;' :
                $instance['buffercode_author_list_img_size'];
        if (!empty($bc_name_value)) {
            echo $before_title . $bc_name_value . $after_title;
        }

        $buffercode_author_list_uc = get_users('role=author');

        $k = count($buffercode_author_list_uc);

        arsort($buffercode_author_list_uc);



        foreach ($buffercode_author_list_uc as $value) {
            $post = '';
            $buffercode_author_list_author_email = get_the_author_meta('user_email', $value->ID);
            $buffercode_author_list_author_login_id = get_the_author_meta('user_login', $value->ID);
            $buffercode_author_list_author_nickname = get_the_author_meta('nickname', $value->ID);
            ?>

            <li class="author-list-<?php echo $buffercode_author_list_img_size; ?>">
                <a href="<?php echo get_author_posts_url($value->ID); ?>" class="tooltip"><?php echo get_avatar($buffercode_author_list_author_email, $buffercode_author_list_img_size, '', $buffercode_author_list_author_login_id); ?>
                    <span>
                        <h4>
                            <?php
                            if (!empty($buffercode_author_list_author_nickname)) {
                                echo $buffercode_author_list_author_nickname;
                            } else {
                                echo strtoupper($buffercode_author_list_author_login_id);
                            }
                            ?>
                        </h4>
                        <?php
                        echo get_avatar($buffercode_author_list_author_email, $buffercode_author_list_img_size, '', $buffercode_author_list_author_login_id);
                        $authorPosts = get_posts(['posts_per_page' => 5, 'author' => $value->ID,]);
                        $post .= '<ul>';
                        foreach ($authorPosts as $authorPost) {
                            $post .= '<li>' . $authorPost->post_title . '</li>';
                        }
                        $post .= '</ul>';
                        ?>

                        <h6>Total Post :<?php echo count_user_posts($value->ID); ?> </h6><br>
                        <?php
                        if (count($authorPosts) > 0) {
                            echo '<h6>Recent Posts</h6>';
                            echo $post;
                        }
                        ?>

                    </span>
                </a>
            </li>

            <?php
            if ($buffercode_author_list_limit < 11) {
                if ($buffercode_author_list_limit > $i)
                    $i++;
                else
                    break;
            }
            else {
                if ($k > $j)
                    $j++;
                else
                    break;
            }
        }
        echo $after_widget;
    }

}
