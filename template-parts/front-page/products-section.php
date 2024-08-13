<?php

class Front_Page_Products_Section {

    public function __construct() {
        $this->first_category = get_field('сategories_section')['first_category'];
        $this->second_category = get_field('сategories_section')['second_category'];
        $this->third_category = get_field('сategories_section')['third_category'];
        $this->fourth_category = get_field('сategories_section')['fourth_category'];
        $this->fifth_category = get_field('сategories_section')['fifth_category'];
        $this->sixth_category = get_field('сategories_section')['sixth_category'];
        $this->seventh_category = get_field('сategories_section')['seventh_category'];
    }

    public function render() {
        ?>

            <div class="products">
                <div class="container container--full">
                    <div class="products__inner">
                        <div class="products__container">
                            <?php if ( !empty( $this->first_category ) ) : ?>
                            <?php $term_id = $this->first_category->term_id; ?>
                            <?php $main_image = get_field( 'main_page_settings', 'term_'.$term_id )['main_image']; ?>
                            <?php $left_image = get_field( 'main_page_settings', 'term_'.$term_id )['left_image']; ?>
                            <?php $right_image = get_field( 'main_page_settings', 'term_'.$term_id )['right_image']; ?>
                            <?php $offset_left_image = get_field( 'main_page_settings', 'term_'.$term_id )['offset_left_image']; ?>
                            <?php $offset_right_image = get_field( 'main_page_settings', 'term_'.$term_id )['offset_right_image']; ?>
                            <article class="products__item">
                                <a href="<?php echo get_term_link( $term_id ); ?>" class="products__content">
                                    <div class="products__img">
                                        <img src="<?php echo $main_image; ?>" alt="<?php echo $this->first_category->slug; ?>">
                                        <div class="products__picture products__picture--left" style="--translateY: <?php echo !empty($offset_left_image) ? $offset_left_image : '0%'; ?>">
                                            <img src="<?php echo $left_image; ?>" width="316" height="241" alt="tomato">
                                        </div>
                                        <div class="products__picture products__picture--right" style="--translateY: <?php echo !empty($offset_right_image) ? $offset_right_image : '0%'; ?>">
                                            <img src="<?php echo $right_image; ?>" width="316" height="241" alt="tomato">
                                        </div>
                                    </div>
                                    <div class="products__text">
                                        <h3><?php echo $this->first_category->name; ?></h3>
                                    </div>
                                </a>
                            </article>
                            <?php endif; ?>
                            <?php if ( !empty( $this->second_category ) ) : ?>
                            <?php $term_id = $this->second_category->term_id; ?>
                            <?php $main_image = get_field( 'main_page_settings', 'term_'.$term_id )['main_image']; ?>
                            <?php $left_image = get_field( 'main_page_settings', 'term_'.$term_id )['left_image']; ?>
                            <?php $right_image = get_field( 'main_page_settings', 'term_'.$term_id )['right_image']; ?>
                            <?php $offset_left_image = get_field( 'main_page_settings', 'term_'.$term_id )['offset_left_image']; ?>
                            <?php $offset_right_image = get_field( 'main_page_settings', 'term_'.$term_id )['offset_right_image']; ?>
                                <article class="products__item">
                                    <a href="<?php echo get_term_link( $term_id ); ?>" class="products__content">
                                        <div class="products__img">
                                            <img src="<?php echo $main_image; ?>" alt="<?php echo $this->second_category->slug; ?>">
                                            <div class="products__picture products__picture--left" style="--translateY: <?php echo !empty($offset_left_image) ? $offset_left_image : '0%'; ?>">
                                                <img src="<?php echo $left_image; ?>" width="316" height="241" alt="tomato">
                                            </div>
                                            <div class="products__picture products__picture--right" style="--translateY: <?php echo !empty($offset_right_image) ? $offset_right_image : '0%'; ?>">
                                                <img src="<?php echo $right_image; ?>" width="316" height="241" alt="tomato">
                                            </div>
                                        </div>
                                        <div class="products__text">
                                            <h3><?php echo $this->second_category->name; ?></h3>
                                        </div>
                                    </a>
                                </article>
                            <?php endif; ?>
                            <?php if ( !empty( $this->third_category ) ) : ?>
                            <?php $term_id = $this->third_category->term_id; ?>
                            <?php $main_image = get_field( 'main_page_settings', 'term_'.$term_id )['main_image']; ?>
                            <?php $left_image = get_field( 'main_page_settings', 'term_'.$term_id )['left_image']; ?>
                            <?php $right_image = get_field( 'main_page_settings', 'term_'.$term_id )['right_image']; ?>
                            <?php $offset_left_image = get_field( 'main_page_settings', 'term_'.$term_id )['offset_left_image']; ?>
                            <?php $offset_right_image = get_field( 'main_page_settings', 'term_'.$term_id )['offset_right_image']; ?>
                                <article class="products__item">
                                    <a href="<?php echo get_term_link( $term_id ); ?>" class="products__content">
                                        <div class="products__img">
                                            <img src="<?php echo $main_image; ?>" alt="<?php echo $this->third_category->slug; ?>">
                                            <div class="products__picture products__picture--left" style="--translateY: <?php echo !empty($offset_left_image) ? $offset_left_image : '0%'; ?>">
                                                <img src="<?php echo $left_image; ?>" width="316" height="241" alt="tomato">
                                            </div>
                                            <div class="products__picture products__picture--right" style="--translateY: <?php echo !empty($offset_right_image) ? $offset_right_image : '0%'; ?>">
                                                <img src="<?php echo $right_image; ?>" width="316" height="241" alt="tomato">
                                            </div>
                                        </div>
                                        <div class="products__text">
                                            <h3><?php echo $this->third_category->name; ?></h3>
                                        </div>
                                    </a>
                                </article>
                            <?php endif; ?>
                            <?php if ( !empty( $this->fourth_category ) ) : ?>
                            <?php $term_id = $this->fourth_category->term_id; ?>
                            <?php $main_image = get_field( 'main_page_settings', 'term_'.$term_id )['main_image']; ?>
                            <?php $left_image = get_field( 'main_page_settings', 'term_'.$term_id )['left_image']; ?>
                            <?php $right_image = get_field( 'main_page_settings', 'term_'.$term_id )['right_image']; ?>
                            <?php $offset_left_image = get_field( 'main_page_settings', 'term_'.$term_id )['offset_left_image']; ?>
                            <?php $offset_right_image = get_field( 'main_page_settings', 'term_'.$term_id )['offset_right_image']; ?>
                                <article class="products__item">
                                    <a href="<?php echo get_term_link( $term_id ); ?>" class="products__content">
                                        <div class="products__img">
                                            <img src="<?php echo $main_image; ?>" alt="<?php echo $this->fourth_category->slug; ?>">
                                            <div class="products__picture products__picture--left" style="--translateY: <?php echo !empty($offset_left_image) ? $offset_left_image : '0%'; ?>">
                                                <img src="<?php echo $left_image; ?>" width="316" height="241" alt="tomato">
                                            </div>
                                            <div class="products__picture products__picture--right" style="--translateY: <?php echo !empty($offset_right_image) ? $offset_right_image : '0%'; ?>">
                                                <img src="<?php echo $right_image; ?>" width="316" height="241" alt="tomato">
                                            </div>
                                        </div>
                                        <div class="products__text">
                                            <h3><?php echo $this->fourth_category->name; ?></h3>
                                        </div>
                                    </a>
                                </article>
                            <?php endif; ?>
                            <?php if ( !empty( $this->fifth_category ) ) : ?>
                            <?php $term_id = $this->fifth_category->term_id; ?>
                            <?php $main_image = get_field( 'main_page_settings', 'term_'.$term_id )['main_image']; ?>
                            <?php $left_image = get_field( 'main_page_settings', 'term_'.$term_id )['left_image']; ?>
                            <?php $right_image = get_field( 'main_page_settings', 'term_'.$term_id )['right_image']; ?>
                            <?php $offset_left_image = get_field( 'main_page_settings', 'term_'.$term_id )['offset_left_image']; ?>
                            <?php $offset_right_image = get_field( 'main_page_settings', 'term_'.$term_id )['offset_right_image']; ?>
                                <article class="products__item">
                                    <a href="<?php echo get_term_link( $term_id ); ?>" class="products__content">
                                        <div class="products__img">
                                            <img src="<?php echo $main_image; ?>" alt="<?php echo $this->fifth_category->slug; ?>">
                                            <div class="products__picture products__picture--left" style="--translateY: <?php echo !empty($offset_left_image) ? $offset_left_image : '0%'; ?>">
                                                <img src="<?php echo $left_image; ?>" width="316" height="241" alt="tomato">
                                            </div>
                                            <div class="products__picture products__picture--right" style="--translateY: <?php echo !empty($offset_right_image) ? $offset_right_image : '0%'; ?>">
                                                <img src="<?php echo $right_image; ?>" width="316" height="241" alt="tomato">
                                            </div>
                                        </div>
                                        <div class="products__text">
                                            <h3><?php echo $this->fifth_category->name; ?></h3>
                                        </div>
                                    </a>
                                </article>
                            <?php endif; ?>
                            <?php if ( !empty( $this->sixth_category ) ) : ?>
                            <?php $term_id = $this->sixth_category->term_id; ?>
                            <?php $main_image = get_field( 'main_page_settings', 'term_'.$term_id )['main_image']; ?>
                            <?php $left_image = get_field( 'main_page_settings', 'term_'.$term_id )['left_image']; ?>
                            <?php $right_image = get_field( 'main_page_settings', 'term_'.$term_id )['right_image']; ?>
                            <?php $offset_left_image = get_field( 'main_page_settings', 'term_'.$term_id )['offset_left_image']; ?>
                            <?php $offset_right_image = get_field( 'main_page_settings', 'term_'.$term_id )['offset_right_image']; ?>
                                <article class="products__item">
                                    <a href="<?php echo get_term_link( $term_id ); ?>" class="products__content">
                                        <div class="products__img">
                                            <img src="<?php echo $main_image; ?>" alt="<?php echo $this->sixth_category->slug; ?>">
                                            <div class="products__picture products__picture--left" style="--translateY: <?php echo !empty($offset_left_image) ? $offset_left_image : '0%'; ?>">
                                                <img src="<?php echo $left_image; ?>" width="316" height="241" alt="tomato">
                                            </div>
                                            <div class="products__picture products__picture--right" style="--translateY: <?php echo !empty($offset_right_image) ? $offset_right_image : '0%'; ?>">
                                                <img src="<?php echo $right_image; ?>" width="316" height="241" alt="tomato">
                                            </div>
                                        </div>
                                        <div class="products__text">
                                            <h3><?php echo $this->sixth_category->name; ?></h3>
                                        </div>
                                    </a>
                                </article>
                            <?php endif; ?>
                            <?php if ( !empty( $this->seventh_category ) ) : ?>
                            <?php $term_id = $this->seventh_category->term_id; ?>
                            <?php $main_image = get_field( 'main_page_settings', 'term_'.$term_id )['main_image']; ?>
                            <?php $left_image = get_field( 'main_page_settings', 'term_'.$term_id )['left_image']; ?>
                            <?php $right_image = get_field( 'main_page_settings', 'term_'.$term_id )['right_image']; ?>
                            <?php $offset_left_image = get_field( 'main_page_settings', 'term_'.$term_id )['offset_left_image']; ?>
                            <?php $offset_right_image = get_field( 'main_page_settings', 'term_'.$term_id )['offset_right_image']; ?>
                                <article class="products__item">
                                    <a href="<?php echo get_term_link( $term_id ); ?>" class="products__content">
                                        <div class="products__img">
                                            <img src="<?php echo $main_image; ?>" alt="<?php echo $this->seventh_category->slug; ?>">
                                            <div class="products__picture products__picture--left" style="--translateY: <?php echo !empty($offset_left_image) ? $offset_left_image : '0%'; ?>">
                                                <img src="<?php echo $left_image; ?>" width="316" height="241" alt="tomato">
                                            </div>
                                            <div class="products__picture products__picture--right" style="--translateY: <?php echo !empty($offset_right_image) ? $offset_right_image : '0%'; ?>">
                                                <img src="<?php echo $right_image; ?>" width="316" height="241" alt="tomato">
                                            </div>
                                        </div>
                                        <div class="products__text">
                                            <h3><?php echo $this->seventh_category->name; ?></h3>
                                        </div>
                                    </a>
                                </article>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

        <?php
    }
}