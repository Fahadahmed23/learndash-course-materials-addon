<?php
/**
 * Plugin Name: LearnDash Course Materials Addon
 * Description: Addon to list course materials with shortcode.
 * Version: 1.0
 * Author: Fahad Ahmed
 * Author URI: https://www.linkedin.com/in/fahad-ahmed-optimist/
 */

class LearnDashCourseMaterialsAddon {

    public function __construct() {
        // Hook to add meta box
        add_action('add_meta_boxes', array($this, 'addMetaBox'));

        // Hook to save meta box data
        add_action('save_post', array($this, 'ld_custom_save_meta_box'));

        // Hook to add shortcode
        add_shortcode('woo_ld_course_materials_list', array($this, 'ld_custom_course_materials_list'));
    }

    public function addMetaBox() {
        add_meta_box(
            'ld_custom_meta_box',
            'Custom Settings',
            array($this, 'ld_custom_meta_box_callback'),
            'sfwd-courses',
            'side',
            'default'
        );
    }

    // Callback function to display the content of the custom meta box
    public function ld_custom_meta_box_callback($post) {
        $showMaterials = get_post_meta($post->ID, 'ld_custom_show_materials', true);
        ?>
        <label for="ld_custom_show_materials">
            <input type="checkbox" name="ld_custom_show_materials" value="enrolled" <?php checked($showMaterials, 'enrolled'); ?>>
            Show Course Materials to Enrolled Users Only
        </label>
        <?php
    }

    // Save custom settings when the course is updated.
    public function ld_custom_save_meta_box($postId) {
        if (isset($_POST['ld_custom_show_materials'])) {
            update_post_meta($postId, 'ld_custom_show_materials', 'enrolled');
        } else {
            update_post_meta($postId, 'ld_custom_show_materials', 'all');
        }
    }

    // Add shortcode for listing course materials
    public function ld_custom_course_materials_list($atts) {
        $atts = shortcode_atts(
            array(
                'num' => 10,
                'courses' => 'all',
            ),
            $atts,
            'woo_ld_course_materials_list'
        );

        $courses = explode(',', $atts['courses']);
        $courses_status = '';
        $selected_courses = array();

        if (empty($courses)) {

            $show_materials = get_post_meta($post->ID, 'ld_custom_show_materials', true);
            if ($show_materials === 'enrolled') {
                $courses_status = 'user-enrolled-only';
            } else {
                $courses_status = 'all';
            }
            
        } else {
            // Assume 'selected' status and filter numeric courses
            $courses_status = 'selected';
            $selected_courses = array_filter($courses, 'is_numeric');

            // Check for special status values
            if (in_array('all', $courses, true)) {
                $courses_status = 'all';
                $selected_courses = array(); // Clear selected courses for 'all'
            } elseif (in_array('user-enrolled-only', $courses, true)) {
                $courses_status = 'user-enrolled-only';
                $selected_courses = array(); // Clear selected courses for 'user-enrolled-only'
            }
        }


        $args = array(
            'post_type' => 'sfwd-courses',
            'post_status' => 'publish',
            'posts_per_page' => $atts['num'],
            'orderby' => 'title',
            'order' => 'ASC',
        );

        // Check if specific courses are selected
        if ($courses_status === 'selected' && !empty($selected_courses)) {
            $args['post__in'] = $selected_courses;
        }

        $save_chapter = array();
        $query = new WP_Query($args);

        // Check if a specific course_id is requested
        $get_course_id = isset($_GET['course_id']) ? sanitize_text_field($_GET['course_id']) : '';

        // Render the appropriate step template
        if (!empty($get_course_id) && file_exists(plugin_dir_path(__FILE__) . "templates/single-course-template.php")) {

            // check the course id is valid or not from custom post type : sfwd-courses

            if (! $this->is_valid_course_id($get_course_id)) {
                return 'Invalid Course ID';
            }

            $single_course = get_post($get_course_id);

            // now get the whole course details
            $course_id = $single_course->ID;

            // Get the course title
            $course_title = get_the_title($course_id);

            // Get the course description
            $course_description = $single_course->post_content;

            // thumnail
            $course_thumbnail = get_the_post_thumbnail_url($course_id, 'full');
            
            // now get course meta  key '_sfwd-courses'
            $course_meta = get_post_meta($course_id, '_sfwd-courses', true);
        
            // save all in an array
            $show_single_chapter = array(
                'course_id' => $course_id,
                'course_title' => $course_title,
                'course_description' => $course_description,
                'course_thumbnail' => $course_thumbnail,
                'course_materials' => $course_meta['sfwd-courses_course_materials'] ?? array(),
                'allowed_users' => learndash_get_course_users_access_from_meta( $course_id )
            );
            return $this->render_single_course_template($show_single_chapter);
        }
        else {
              // The Loop
            if ($query->have_posts()) {
                $materials = array();
                while ($query->have_posts()) {
                    $query->the_post();
                    $course_id = get_the_ID();
                    $course_title = get_the_title();
                    $course_description = get_the_content();

                    // $couse thumbnail
                    $course_thumbnail = get_the_post_thumbnail_url($course_id, 'full');

                    // save all in an array
                    $save_chapter[] = array(
                        'course_id' => $course_id,
                        'course_title' => $course_title,
                        'course_description' => $course_description,
                        'course_thumbnail' => $course_thumbnail,
                        'allowed_users' => learndash_get_course_users_access_from_meta( $course_id )
                    );
                    
                }
                
                wp_reset_postdata();

                return $this->render_course_list_template($save_chapter);


            } else {
                echo 'No Courses Found';
            
            }
        } 



    }

    public function is_valid_course_id($course_id) {
        $post_type = get_post_type($course_id);

        return post_type_exists($post_type) && $post_type === 'sfwd-courses';
    }


    // The function to render the single course 
    public function render_single_course_template($show_single_chapter)
    {
        ob_start();
        include plugin_dir_path(__FILE__) . 'templates/single-course-template.php'; 
        return ob_get_clean();
    }

    // The function to render the course list
    public function render_course_list_template($save_chapter)
    {
        ob_start();
        include plugin_dir_path(__FILE__) . 'templates/course-list-template.php';
        return ob_get_clean();
    }


}

// Instantiate the plugin class
new LearnDashCourseMaterialsAddon();
