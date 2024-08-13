<?php 

class Forrozn_Page_Find_Section {

    public function __construct() {
        $find_section = get_field('find_section');
        $this->title = $find_section['title'];
        $this->subtitle = $find_section['subtitle'];
        $this->button = $find_section['button'];
        $this->image = $find_section['image'];
    }

    public function render() {
        ?>

            <section class="forrozn-find">
                <div class="container">
                    <div class="forrozn-find__content">
                        <?php if( !empty( $this->title ) ) : ?>
                            <div class="forrozn-find__title title-h2">
                                <h2><?php echo $this->title; ?></h2>
                            </div>
                        <?php endif; ?>
                        <?php if( !empty( $this->subtitle ) ) : ?>
                            <div class="forrozn-find__text section-text">
                                <p><?php echo $this->subtitle; ?></p>
                            </div>
                        <?php endif; ?>
                        <?php if( !empty( $this->button ) ) : ?>
                        <div class="forrozn-find__btn">
                            <a href="<?php echo $this->button['url']; ?>" class="btn"><?php echo $this->button['title']; ?></a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if( !empty( $this->image ) ) : ?>
                    <div class="forrozn-find__img" style="background-image: url('<?php echo $this->image; ?>');">
                    </div>
                <?php endif; ?>
            </section>

        </main>

        <?php
    }
}