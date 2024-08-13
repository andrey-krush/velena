<?php

class Home_Content
{

    public function __construct()
    {
        $this->page_title = pll__('Публікації');
        $this->terms = get_terms(array(
            'taxonomy'   => 'category',
            'hide_empty' => true,
        ));

        $args = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => 9,
        );
        $this->paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $args['paged'] = $this->paged;

        $this->query = new WP_Query($args);
    }

    public function render()
    {
?>

        <main class="main">
            <section class="catalog-blog">
                <div class="container">
                    <div class="catalog-blog__head page-head">
                        <?php if (!empty($this->page_title)) : ?>
                            <div class="page-head__title title-h1">
                                <h2><?php echo $this->page_title; ?></h2>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($this->terms)) : ?>
                            <div class="page-head__link">
                                <a class="active"><?php echo pll__('Усі публікації'); ?></a>
                                <?php foreach ($this->terms as $item) : ?>
                                    <a href="<?php echo get_term_link($item->term_id); ?>"><?php echo $item->name; ?></a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php if ($this->query->have_posts()) : ?>
                        <div class="catalog-blog__content">
                            <?php while ($this->query->have_posts()) : $this->query->the_post(); ?>
                                <?php $month_array = get_field('month_' . pll_current_language(), 'option'); ?>
                                <a href="<?php echo get_the_permalink(); ?>">
                                    <article class="catalog-blog__item">
                                        <?php if (!empty(get_the_post_thumbnail_url())) : ?>
                                            <div class="catalog-blog__img">
                                                <?php $thumbnail_id = get_post_thumbnail_id(get_the_ID());
                                                $alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);  ?>
                                                <img src="<?php echo get_the_post_thumbnail_url(); ?>" width="340" height="200" alt="<?php echo $alt; ?>">
                                            </div>
                                        <?php endif; ?>
                                        <div class="catalog-blog__descr">
                                            <div class="section-data">
                                                <time datetime="2023-03-05"><?php echo get_the_date('d') . ' ' . $month_array['month_' . get_the_date('n')]; ?></time>
                                            </div>
                                            <div class="catalog-blog__title">
                                                <h3><?php echo get_the_title(); ?></h3>
                                            </div>
                                        </div>
                                    </article>
                                </a>
                            <?php endwhile; ?>
                        </div>
                        <div class="catalog-blog__pagination pagination">
                            <?php echo paginate_links(array(
                                'total' => $this->query->max_num_pages,
                                'current' => $this->paged,
                                'prev_next' => false,
                                'end_size' => 1,
                                'mid_size' => 1,
                            )); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        </main>

<?php
    }
}
