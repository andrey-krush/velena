<?php

class Taxonomy_Attribute {


    public static function init() {

        add_action( 'init', [ __CLASS__, 'register_taxonomy' ] );
        add_action( 'acf/init', [__CLASS__, 'acf_add_local_field_group'] );

    }

    public static function register_taxonomy() {

        register_taxonomy( 'attribute','product', array(
            "hierarchical" => true,
            "label" => "Атрибути продукту",
            "singular_label" => "Атрибути продукту",
            'show_ui' => true,
            'show_admin_column' => true,
        ));

    }

    public static function acf_add_local_field_group() {


    }


}
