<?php

class Single_Product_Similar_Product {

    public function __construct() {

        $post_id = get_the_ID();

        $this->post_type_term  = get_terms([
            'taxonomy' => 'type',
            'object_ids' => [$post_id],
        ]);

        if( !empty( $this->post_type_term ) ) :

            $this->post_type_children_term  = get_terms([
                'taxonomy' => 'type',
                'parent' => $this->post_type_term[0]->term_id,
                'object_ids' => [$post_id],
            ]);

        endif;

        $this->args = [
            'post_type' => 'product',
            'post_status' => 'publish',
            'numberposts' => 5,
            'exclude' => [$post_id]
        ];

        if( !empty( $this->post_type_children_term ) ) :
            $this->args['tax_query'] = [
                [
                    'taxonomy' => 'type',
                    'field' => 'slug',
                    'terms' => $this->post_type_children_term[0]->slug
                ]
            ];
        elseif( !empty( $this->post_type_term ) )  :
            $this->args['tax_query'] = [
                [
                    'taxonomy' => 'type',
                    'field' => 'slug',
                    'terms' => $this->post_type_term[0]->slug
                ]
            ];
        endif;

        $this->posts = get_posts($this->args);

    }

    public function render() {
        if(  isset($this->args['tax_query']) and !empty( $this->posts )  ) : ?>

            <section class="similiar-products">
                <div class="container">
                    <div class="similiar-products__title title-h3">
                        <h2><?php echo pll__('Схожі товари'); ?></h2>
                    </div>
                    <div class="similiar-products__container">
                        <?php foreach( $this->posts as $item) :?>
                            <?php (new Type_Products())->render_product_article( $item->ID ); ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>

        <?php endif;
    }
}