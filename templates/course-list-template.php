<!-- course-list-template.php -->
<?php if (!empty($save_chapter)) : ?>
    <div class="course-list">
        <?php foreach ($save_chapter as $course) : ?>
            <div class="course">
                <?php if ($course['course_thumbnail']) : ?>
                    <img src="<?php echo esc_url($course['course_thumbnail']); ?>" alt="<?php echo esc_attr($course['course_title']); ?>">
                <?php endif; ?>

                <h2><?php echo esc_html($course['course_title']); ?></h2>
                <p><?php echo esc_html($course['course_description']); ?></p>
                
                <!-- Link to view more details -->
                <a href="<?php echo esc_url(add_query_arg('course_id', $course['course_id'], site_url(basename(get_permalink())))); ?>">View More</a>

              

            </div>
        <?php endforeach; ?>
    </div>
<?php else : ?>
    <p>No Courses Found</p>
<?php endif; ?>
