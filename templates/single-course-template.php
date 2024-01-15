<!-- single-course-template.php -->
<div class="single-course">
    <?php if ($show_single_chapter['course_thumbnail']) : ?>
        <img src="<?php echo esc_url($show_single_chapter['course_thumbnail']); ?>" alt="<?php echo esc_attr($show_single_chapter['course_title']); ?>">
    <?php endif; ?>

    <h2><?php echo esc_html($show_single_chapter['course_title']); ?></h2>
    <p><?php echo esc_html($show_single_chapter['course_description']); ?></p>

    <?php
    // Check if the current user is in the allowed users array or is an administrator
    $current_user_id = get_current_user_id(); 
    $is_user_allowed = in_array($current_user_id, $show_single_chapter['allowed_users']) || current_user_can('administrator') || 0==$current_user_id;

    
    if ($is_user_allowed) :
    ?>
        <!-- <p>Allowed Users: <?php echo implode(', ', $show_single_chapter['allowed_users']); ?></p> -->
        <h3>Materials</h3>
        <?php
        // Show materials here.
        echo $show_single_chapter['course_materials'];
    else :
        // Display a red heading for users who are not allowed
        echo '<h3 style="color: red;">You are not enrolled in this course.</h3>';
    endif;
    ?>
</div>
