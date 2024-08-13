<?php 

class About_Page_Video_Section {

    public function __construct() {
        $video_section = get_field('video_section');
        $this->text_above_video = $video_section['text_above_video'];
        $this->text_under_video = $video_section['text_under_video'];
        $this->text_near_video = $video_section['text_near_video'];
        $this->video_link = $video_section['video_link'];
        $this->logo_brand = $video_section['logo_brand'];
        
    }

    public function render() {
        ?>

            <section class="about-content">
                <div class="container container--small">
                    <?php if( !empty( $this->text_above_video ) ) : ?>
                        <div class="section-text section-text--big">
                           <p><?php echo $this->text_above_video; ?></p>
                        </div>
                    <?php endif; ?>
                    <?php if( !empty( $this->logo_brand ) ) : ?>
                        <div class="section-text__brands">
                            <img src="<?php echo $this->logo_brand; ?>" alt="brand">
                        </div>
                    <?php endif; ?>
                    <?php if( !empty( $this->video_link ) || !empty( $this->text_near_video ) ) : ?>
                        <div class="about-content__videos">
                            <?php if( !empty( $this->video_link ) ) : ?>
                                <div class="about-content__video">
                                    <iframe width="560" height="315" src="<?php echo $this->video_link; ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                                </div>
                            <?php endif; ?>
                            <?php if( !empty( $this->text_near_video ) ) : ?>
                                <div class="about-content__text section-text section-text--small">
                                    <p><?php echo $this->text_near_video; ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <?php if( !empty($this->text_under_video ) ) : ?>
                        <div class="section-text section-text--big">
                            <p><?php echo $this->text_under_video; ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </section>

        <?php
    }
}