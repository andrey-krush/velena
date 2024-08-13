<?php 

class Gutenberg_Table {

    public static function init() {
        add_action( 'acf/init', [ __CLASS__, 'acf_register_block_type' ] );
        add_action( 'acf/init', [ __CLASS__, 'acf_add_local_field_group' ] );
    }

    public static function acf_register_block_type() {
        acf_register_block_type( [
            'name' => 'slider-block',
            'title' => 'Table block',
            'description' => '',
            'category' => 'custom',
            'icon' => '',
            'post_types' => ['post'],
            'keywords' => [],
            'mode' => 'auto',
            'align' => 'full',
            'render_callback' => [ __CLASS__, 'render' ],
            'example' => [
                'attributes' => [
                    'mode' => 'preview',
                    'data' => [
                        'testimonial' => '',
                        'author' => '',
                    ]
                ]
            ]
        ]);
    }


    public static function render( $block, $content = '', $is_preview = false, $post_id = 0 ) {
        
        $first_column_name = get_field('first_column_name');        
        $second_column_name = get_field('second_column_name');        
        $third_column_name = get_field('third_column_name');   
        $rows = get_field('rows');     
        
        ?>
            <div class="section-text__table">
                <table>
                    <thead>
                        <tr>
                            <?php if( !empty( $first_column_name ) ) : ?>
                                <th><?php echo $first_column_name; ?></th>
                            <?php endif; ?>
                            <?php if( !empty( $second_column_name ) ) : ?>
                                <th><?php echo $second_column_name; ?></th>
                            <?php endif; ?>
                            <?php if( !empty( $third_column_name ) ) : ?>
                                <th><?php echo $third_column_name; ?></th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach( $rows as $item ) : ?>
                            <tr>
                            <?php if( !empty( $item['first_column'] ) ) : ?>
                                <td><?php echo $item['first_column']; ?></td>
                            <?php endif; ?>
                            <?php if( !empty( $item['second_column'] ) ) : ?>
                                <td><?php echo $item['second_column']; ?></td>
                            <?php endif; ?>
                            <?php if( !empty( $item['third_column'] ) ) : ?>
                                <td><?php echo $item['third_column']; ?></td>
                            <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                        
                    </tbody>
                </table>
            </div>
        <?
    }
    
    public static function acf_add_local_field_group() {
        if ( function_exists('acf_add_local_field_group') ):

            acf_add_local_field_group(array(
                'key' => 'group_645be86a5647b',
                'title' => 'Table block',
                'fields' => array(
                    array(
                        'key' => 'field_645be86b2b92b',
                        'label' => 'First column name',
                        'name' => 'first_column_name',
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
                        'key' => 'field_645be88e2b92c',
                        'label' => 'Second column name',
                        'name' => 'second_column_name',
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
                        'key' => 'field_645be8bc2b92d',
                        'label' => 'Third column name',
                        'name' => 'third_column_name',
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
                        'key' => 'field_645be8c62b92e',
                        'label' => 'Rows',
                        'name' => 'rows',
                        'aria-label' => '',
                        'type' => 'repeater',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'layout' => 'table',
                        'pagination' => 0,
                        'min' => 0,
                        'max' => 0,
                        'collapsed' => '',
                        'button_label' => 'Add Row',
                        'rows_per_page' => 20,
                        'sub_fields' => array(
                            array(
                                'key' => 'field_645be8cf2b92f',
                                'label' => 'First column',
                                'name' => 'first_column',
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
                                'parent_repeater' => 'field_645be8c62b92e',
                            ),
                            array(
                                'key' => 'field_645be8d72b930',
                                'label' => 'Second column',
                                'name' => 'second_column',
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
                                'parent_repeater' => 'field_645be8c62b92e',
                            ),
                            array(
                                'key' => 'field_645be8e22b931',
                                'label' => 'Third column',
                                'name' => 'third_column',
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
                                'parent_repeater' => 'field_645be8c62b92e',
                            ),
                        ),
                    ),
                ),
                'location' => array(
                    array(
                        array(
                            'param' => 'block',
                            'operator' => '==',
                            'value' => 'acf/slider-block',
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
