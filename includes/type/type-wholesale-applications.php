<?php

class Type_Wholesale_Applications {

    public static function init() {

        add_action('init', [__CLASS__, 'register_type']);
        add_action('acf/init', [__CLASS__, 'acf_add_local_field_group']);

    }

    public static function register_type() {

        register_post_type( 'whole_application', array(
            'labels' => array(
                'name' => 'Заявки опт',
                'singular_name' => 'Заявка опт',
                'menu_name' => 'Заявки опт',
                'all_items' => 'All Заявки опт',
                'edit_item' => 'Edit Заявка опт',
                'view_item' => 'View Заявка опт',
                'view_items' => 'View Заявки опт',
                'add_new_item' => 'Add New Заявка опт',
                'new_item' => 'New Заявка опт',
                'parent_item_colon' => 'Parent Заявка опт:',
                'search_items' => 'Search Заявки опт',
                'not_found' => 'No заявки опт found',
                'not_found_in_trash' => 'No заявки опт found in Trash',
                'archives' => 'Заявка опт Archives',
                'attributes' => 'Заявка опт Attributes',
                'insert_into_item' => 'Insert into заявка опт',
                'uploaded_to_this_item' => 'Uploaded to this заявка опт',
                'filter_items_list' => 'Filter заявки опт list',
                'filter_by_date' => 'Filter заявки опт by date',
                'items_list_navigation' => 'Заявки опт list navigation',
                'items_list' => 'Заявки опт list',
                'item_published' => 'Заявка опт published.',
                'item_published_privately' => 'Заявка опт published privately.',
                'item_reverted_to_draft' => 'Заявка опт reverted to draft.',
                'item_scheduled' => 'Заявка опт scheduled.',
                'item_updated' => 'Заявка опт updated.',
                'item_link' => 'Заявка опт Link',
                'item_link_description' => 'A link to a заявка опт.',
            ),
            'public' => true,
            'exclude_from_search' => false,
            'show_in_rest' => true,
            'supports' => array(
                0 => 'title',
                1 => 'editor',
                2 => 'thumbnail',
            ),
            'delete_with_user' => false,
        ));
    }

    public static function acf_add_local_field_group() {

    }
}