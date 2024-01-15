# LearnDash Course Materials Addon

**Contributors:** Fahad Ahmed  
**Tags:** learndash, courses, materials, addon  
**Requires at least:** 5.0  
**Tested up to:** 6.4.2  
**Stable tag:** 1.0  
**License:** GPLv2 or later  
**License URI:** [GNU General Public License v2.0](http://www.gnu.org/licenses/gpl-2.0.html)

## Description

LearnDash Course Materials Addon is a WordPress plugin designed to extend LearnDash functionality by adding a shortcode to list course materials. It also includes a custom meta box for each course to control the visibility of materials.

## Features

- Show or hide course materials based on user enrollment.
- Custom meta box for controlling material visibility in each course.
- Shortcode to display a list of courses with their materials.

## Installation

1. Upload the `learndash-course-materials-addon` directory to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.

## Usage

1. After activation, a new meta box "Custom Settings" will appear in the LearnDash course editor.
2. Use the checkbox in the meta box to control whether course materials should be visible to enrolled users only or to everyone.
3. Use the `[woo_ld_course_materials_list]` shortcode to display a list of courses with their materials.

## Shortcode Usage

Use the following shortcode to display a list of courses with their materials:

[woo_ld_course_materials_list num="10" courses="all"]


Parameters:
- `num`: Number of courses to display (default is 10).
- `courses`: Specify courses to display, e.g., `courses="1,2,3"` or `courses="all"` or `courses="user-enrolled-only"`.

## Templates

The plugin includes two templates for rendering course information:
1. `single-course-template.php`: Template for displaying details of a single course.
2. `course-list-template.php`: Template for displaying a list of courses.

Feel free to customize these templates according to your needs.

## Frequently Asked Questions

### How do I control the visibility of course materials?

In the course editor, you'll find a meta box named "Custom Settings." Use the checkbox to decide whether materials should be visible to enrolled users only or to everyone.

### How do I display a list of courses with materials?

Use the `[woo_ld_course_materials_list]` shortcode in your WordPress editor or any supported area. You can customize the shortcode with parameters like `num` and `courses`.

## Changelog

### 1.0
- Initial release.

## Upgrade Notice

### 1.0
- Initial release.
