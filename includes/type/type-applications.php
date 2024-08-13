<?php 

class Type_Applications {

    public static function init() {
        add_action('init', [__CLASS__, 'register_type']);
        add_action('acf/init', [__CLASS__, 'acf_add_local_field_group']);

        add_action('wp_ajax_for_opt_form', [__CLASS__, 'for_opt_form']);
        add_action('wp_ajax_nopriv_for_opt_form', [__CLASS__, 'for_opt_form']);

        add_action('wp_ajax_contact', [__CLASS__, 'contactAjaxHandler']);
        add_action('wp_ajax_nopriv_contact', [__CLASS__, 'contactAjaxHandler']);

    } 

    public static function register_type() {
        register_post_type( 'application', [
            'label'  => null,
            'labels' => [
                'name'               => 'Звернення',
                'singular_name'      => 'Звернення',
                'add_new'            => 'Додати звернення',
                'add_new_item'       => 'Додавання звернення',
                'edit_item'          => 'Редагування звернення',
                'new_item'           => 'Нове звернення',
                'view_item'          => 'Переглянути звернення',
                'search_items'       => 'Шукати звернення',
                'not_found'          => 'Не знайдено', 
                'not_found_in_trash' => 'Не знайдено в корзині', 
                'parent_item_colon'  => '', 
                'menu_name'          => 'Звернення',
            ],
            'has_archive' => true,
            'public' => true,
            'show_in_rest' => true,
            'show_ui' => true, 
            'supports' => array( 'title')
        ] );
    }

    public static function for_opt_form() {
        
        $title = "Нове звернення - " . $_POST['page_title'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $message = $_POST['message'];
        $quantity = $_POST['quantity'];


        $post_id = wp_insert_post(array(
            'post_title' => $title . ' - ' . date('d.m.Y'),
            'post_status'   => 'publish',
            'post_type' => 'application',
        ));

        update_field('name', $name, $post_id);
        update_field('mail', $email, $post_id);
        update_field('message', $message, $post_id);
        update_field('product_quantity', $quantity, $post_id);

        if( isset($_FILES['file']) && $_FILES['file']['error'] == 0 ) :

            if( str_contains( $_FILES['file']['name'], ' ' ) ) :
                $_FILES['file']['name'] = str_replace( ' ', '-', $_FILES['file']['name'] );
                $_FILES['file']['full_path'] = str_replace( ' ', '-', $_FILES['file']['full_path'] );
            endif;

            $attachment_id = self::upload_file($_FILES['file']);
            update_field('product_drawing', $attachment_id, $post_id);
    
        endif;

        $emails = get_field( 'contact_us_block', (new Foropt_Page())::get_ID() )['emails'];  
        
        if( !empty( $emails ) ) : 

            $file_url = get_field('product_drawing', $post_id);

            $message = '
                Ім’я : ' . $name . '<br>
                Email : ' . $email   . '<br>
                Повідомлення : ' . $message  . '<br>
                Кількість товару : ' . $quantity  . '<br>'
            ;

            if( !empty( $file_url ) ) :
               $message .= 'Файл : ' . $file_url['url'];
            endif;

            $headers = array(
                'content-type: text/html',
            );

            wp_mail( $emails, $title, $message, $headers );
        

        endif;

        wp_send_json_success();
        die;

    }

    public static function contactAjaxHandler() {

        $title = "Нове звернення - " . $_POST['page_title'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $message = $_POST['message'];


        $post_id = wp_insert_post(array(
            'post_title' => $title . ' - ' . date('d.m.Y'),
            'post_status'   => 'publish',
            'post_type' => 'application',
        ));

        update_field('name', $name, $post_id);
        update_field('mail', $email, $post_id);
        update_field('message', $message, $post_id);

        $emails = get_field( 'emails', (new Contacts_Page())::get_ID() );

        if( !empty( $emails ) ) :

            $file_url = get_field('product_drawing', $post_id);

            $message = '
                Ім’я : ' . $name . '<br>
                Email : ' . $email   . '<br>
                Повідомлення : ' . $message  . '<br>'
            ;

            $headers = array(
                'content-type: text/html',
            );

            wp_mail( $emails, $title, $message, $headers );


        endif;

        wp_send_json_success();
        die;

    }

    public static function upload_file($file) {
        $wordpress_upload_dir = wp_upload_dir();
        // $wordpress_upload_dir['path'] is the full server path to wp-content/uploads/2017/05, for multisite works good as well
        // $wordpress_upload_dir['url'] the absolute URL to the same folder, actually we do not need it, just to show the link to file
        $i = 1; // number of tries when the file with the same name is already exists
        
        $profilepicture = $file;
        $new_file_path = $wordpress_upload_dir['path'] . '/' . $profilepicture['name'];
        $new_file_mime = mime_content_type( $profilepicture['tmp_name'] );
        
        if( empty( $profilepicture ) )
            die( 'File is not selected.' );
        
        if( $profilepicture['error'] )
            die( $profilepicture['error'] );
            
        if( $profilepicture['size'] > wp_max_upload_size() )
            die( 'It is too large than expected.' );
            
        if( !in_array( $new_file_mime, get_allowed_mime_types() ) )
            die( 'WordPress doesn\'t allow this type of uploads.' );
            
        while( file_exists( $new_file_path ) ) {
            $i++;
            $new_file_path = $wordpress_upload_dir['path'] . '/' . $i . '_' . $profilepicture['name'];
        }
        
        // looks like everything is OK
        if( move_uploaded_file( $profilepicture['tmp_name'], $new_file_path ) ) {
            
        
            $upload_id = wp_insert_attachment( array(
                'guid'           => $new_file_path, 
                'post_mime_type' => $new_file_mime,
                'post_title'     => preg_replace( '/\.[^.]+$/', '', $profilepicture['name'] ),
                'post_content'   => '',
                'post_status'    => 'inherit'
            ), $new_file_path );
        
            // wp_generate_attachment_metadata() won't work if you do not include this file
            
        
            // Generate and save the attachment metas into the database
            wp_update_attachment_metadata( $upload_id, wp_generate_attachment_metadata( $upload_id, $new_file_path ) );
        }
         
        return $upload_id;
    }

    public static function acf_add_local_field_group() {
        
        if ( function_exists('acf_add_local_field_group') ):

            acf_add_local_field_group(array(
                'key' => 'group_64858bc2c393e',
                'title' => 'Application',
                'fields' => array(
                    array(
                        'key' => 'field_64858bc440f5c',
                        'label' => 'Name',
                        'name' => 'name',
                        'aria-label' => '',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'translations' => 'translate',
                        'maxlength' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                    ),
                    array(
                        'key' => 'field_64858bd140f5d',
                        'label' => 'Mail',
                        'name' => 'mail',
                        'aria-label' => '',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'translations' => 'translate',
                        'maxlength' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                    ),
                    array(
                        'key' => 'field_64858bd840f5e',
                        'label' => 'Message',
                        'name' => 'message',
                        'aria-label' => '',
                        'type' => 'textarea',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'translations' => 'translate',
                        'maxlength' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                    ),
                    array(
                        'key' => 'field_64858be140f5f',
                        'label' => 'Product quantity',
                        'name' => 'product_quantity',
                        'aria-label' => '',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'translations' => 'translate',
                        'maxlength' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                    ),
                    array(
                        'key' => 'field_64858bf640f60',
                        'label' => 'Product drawing',
                        'name' => 'product_drawing',
                        'aria-label' => '',
                        'type' => 'file',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'return_format' => 'array',
                        'library' => 'all',
                        'translations' => 'copy_once',
                        'min_size' => '',
                        'max_size' => '',
                        'mime_types' => '',
                    ),
                ),
                'location' => array(
                    array(
                        array(
                            'param' => 'post_type',
                            'operator' => '==',
                            'value' => 'application',
                        ),
                    ),
                ),
                'menu_order' => 0,
                'position' => 'normal',
                'style' => 'default',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
                'hide_on_screen' => '',
                'active' => true,
                'description' => '',
                'show_in_rest' => 0,
            ));
            
        endif;    

    }
}