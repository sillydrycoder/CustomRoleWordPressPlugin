<?php
/**
 * Plugin Name: Editor-in-Chief Role
 * Plugin URI:  https://yourwebsite.com/editor-in-chief-role
 * Description: Adds a custom user role "Editor-in-Chief" with advanced capabilities.
 * Version:     1.0.0
 * Author:      Muhammad Ali
 * Author URI:  https://yourwebsite.com
 * License:     GPL2
 * Text Domain: editor-in-chief-role
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Add custom role on plugin activation
function eic_add_role() {
    add_role(
        'editor_in_chief',
        'Editor-in-Chief',
        array(
            'read'                   => true,
            'edit_posts'             => true,
            'edit_others_posts'      => true,
            'publish_posts'          => true,
            'edit_published_posts'   => true,
            'delete_posts'           => true,
            'delete_others_posts'    => true,
            'delete_published_posts' => true,
            'manage_categories'      => true,
            'moderate_comments'      => true,
            'edit_users'             => true,
            'list_users'             => true,
            'promote_users'          => true,
            'create_users'           => true,
            'remove_users'           => true,
            'manage_links'           => true,
            'manage_options'         => false, // Limited to prevent access to site-wide settings
            'delete_users'           => false, // Prevent deletion of users
            'edit_theme_options'     => false, // Prevent theme modifications
            'activate_plugins'       => false, // Prevent plugin management
            'edit_files'             => false, // Prevent file editing
        )
    );
}

// Remove custom role on plugin deactivation
function eic_remove_role() {
    remove_role('editor_in_chief');
}

// Activate and deactivate hooks
register_activation_hook(__FILE__, 'eic_add_role');
register_deactivation_hook(__FILE__, 'eic_remove_role');

// Add custom capabilities to the role
function eic_add_capabilities() {
    $role = get_role('editor_in_chief');

    if ($role) {
        // Example of adding additional capabilities if needed in the future
        $role->add_cap('edit_pages');
        $role->add_cap('publish_pages');
        // You can add more capabilities here as required.
    }
}
add_action('admin_init', 'eic_add_capabilities');

// Create a settings page for managing capabilities
function eic_custom_capabilities_page() {
    add_menu_page(
        'Editor-in-Chief Capabilities',
        'Editor-in-Chief Capabilities',
        'manage_options',
        'eic-capabilities',
        'eic_capabilities_page_html',
        'dashicons-admin-users',
        90
    );
}
add_action('admin_menu', 'eic_custom_capabilities_page');

function eic_capabilities_page_html() {
    if (!current_user_can('manage_options')) {
        return;
    }

    // Handle form submission
    if (isset($_POST['eic_capabilities_nonce']) && wp_verify_nonce($_POST['eic_capabilities_nonce'], 'eic_save_capabilities')) {
        $capabilities = array(
            'edit_pages'        => isset($_POST['edit_pages']),
            'publish_pages'     => isset($_POST['publish_pages']),
            'edit_posts'        => isset($_POST['edit_posts']),
            'edit_others_posts' => isset($_POST['edit_others_posts']),
            'publish_posts'     => isset($_POST['publish_posts']),
            'edit_published_posts' => isset($_POST['edit_published_posts']),
            'delete_posts'      => isset($_POST['delete_posts']),
            'delete_others_posts' => isset($_POST['delete_others_posts']),
            'delete_published_posts' => isset($_POST['delete_published_posts']),
            'manage_categories' => isset($_POST['manage_categories']),
            'moderate_comments' => isset($_POST['moderate_comments']),
            'edit_users'        => isset($_POST['edit_users']),
            'list_users'        => isset($_POST['list_users']),
            'promote_users'     => isset($_POST['promote_users']),
            'create_users'      => isset($_POST['create_users']),
            'remove_users'      => isset($_POST['remove_users']),
            'manage_links'      => isset($_POST['manage_links']),
        );

        $role = get_role('editor_in_chief');

        if ($role) {
            foreach ($capabilities as $capability => $has_capability) {
                if ($has_capability) {
                    $role->add_cap($capability);
                } else {
                    $role->remove_cap($capability);
                }
            }
            echo '<div class="updated"><p>Capabilities updated successfully.</p></div>';
        }
    }

    // Get current capabilities
    $role = get_role('editor_in_chief');
    if ($role) {
        $current_caps = $role->capabilities;
    }
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Manage Editor-in-Chief Capabilities', 'editor-in-chief-role'); ?></h1>
        <form method="post">
            <?php wp_nonce_field('eic_save_capabilities', 'eic_capabilities_nonce'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Edit Pages', 'editor-in-chief-role'); ?></th>
                    <td><input type="checkbox" name="edit_pages" <?php checked($current_caps['edit_pages']); ?> /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Publish Pages', 'editor-in-chief-role'); ?></th>
                    <td><input type="checkbox" name="publish_pages" <?php checked($current_caps['publish_pages']); ?> /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Edit Posts', 'editor-in-chief-role'); ?></th>
                    <td><input type="checkbox" name="edit_posts" <?php checked($current_caps['edit_posts']); ?> /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Edit Others Posts', 'editor-in-chief-role'); ?></th>
                    <td><input type="checkbox" name="edit_others_posts" <?php checked($current_caps['edit_others_posts']); ?> /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Publish Posts', 'editor-in-chief-role'); ?></th>
                    <td><input type="checkbox" name="publish_posts" <?php checked($current_caps['publish_posts']); ?> /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Edit Published Posts', 'editor-in-chief-role'); ?></th>
                    <td><input type="checkbox" name="edit_published_posts" <?php checked($current_caps['edit_published_posts']); ?> /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Delete Posts', 'editor-in-chief-role'); ?></th>
                    <td><input type="checkbox" name="delete_posts" <?php checked($current_caps['delete_posts']); ?> /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Delete Others Posts', 'editor-in-chief-role'); ?></th>
                    <td><input type="checkbox" name="delete_others_posts" <?php checked($current_caps['delete_others_posts']); ?> /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Delete Published Posts', 'editor-in-chief-role'); ?></th>
                    <td><input type="checkbox" name="delete_published_posts" <?php checked($current_caps['delete_published_posts']); ?> /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Manage Categories', 'editor-in-chief-role'); ?></th>
                    <td><input type="checkbox" name="manage_categories" <?php checked($current_caps['manage_categories']); ?> /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Moderate Comments', 'editor-in-chief-role'); ?></th>
                    <td><input type="checkbox" name="moderate_comments" <?php checked($current_caps['moderate_comments']); ?> /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Edit Users', 'editor-in-chief-role'); ?></th>
                    <td><input type="checkbox" name="edit_users" <?php checked($current_caps['edit_users']); ?> /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('List Users', 'editor-in-chief-role'); ?></th>
                    <td><input type="checkbox" name="list_users" <?php checked($current_caps['list_users']); ?> /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Promote Users', 'editor-in-chief-role'); ?></th>
                    <td><input type="checkbox" name="promote_users" <?php checked($current_caps['promote_users']); ?> /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Create Users', 'editor-in-chief-role'); ?></th>
                    <td><input type="checkbox" name="create_users" <?php checked($current_caps['create_users']); ?> /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Remove Users', 'editor-in-chief-role'); ?></th>
                    <td><input type="checkbox" name="remove_users" <?php checked($current_caps['remove_users']); ?> /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Manage Links', 'editor-in-chief-role'); ?></th>
                    <td><input type="checkbox" name="manage_links" <?php checked($current_caps['manage_links']); ?> /></td>
                </tr>
            </table>
            <p class="submit">
                <input type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes', 'editor-in-chief-role'); ?>" />
            </p>
        </form>
    </div>
    <?php
}
