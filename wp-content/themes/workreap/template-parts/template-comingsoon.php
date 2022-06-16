<?php
$maintenance  = '';
$logo         = '';
$img          = '';
$title        = '';
$description  = '';
$copyright    = '';
$date         = '';
if (function_exists('fw_get_db_settings_option')) {
    $maintenance    = fw_get_db_settings_option('maintenance');
    $logo           = fw_get_db_settings_option('logo');
    $img            = fw_get_db_settings_option('img');
    $title          = fw_get_db_settings_option('title');
    $description    = fw_get_db_settings_option('description');
    $copyright      = fw_get_db_settings_option('copyright');
    $date           = fw_get_db_settings_option('date');
    $formatted_date = date("Y, n, d, H, i, s", strtotime("-1 month", strtotime($date)));
}

$post_name = workreap_get_post_name();

if (( !empty($maintenance) && $maintenance == 'enable' and ! (is_user_logged_in()) ) || $post_name == "coming-soon") { ?>
    <?php 
    if( !empty( $img ) ||
        !empty( $logo['url'] ) ||
        !empty( $title ) ||
        !empty( $description ) ||
        !empty( $date ) ) {
        ?>
        <div class="wt-comingsoon-page">
            <div class="wt-haslayout wt-main-section">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="wt-comingsoon-holder wt-haslayout">
                                <div class="wt-comingsoon-aligncenter">
                                    <?php 
                                    if( !empty( $logo['url'] ) ||
                                        !empty( $title ) ||
                                        !empty( $description ) ||
                                        !empty( $date ) ) {
                                        ?>
                                        <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 float-left">
                                            <div class="wt-comingsoon-content">
                                                <?php if (!empty($logo['url'])) { ?>
                                                    <strong class="wt-comingsoon-logo"><img src="<?php echo esc_url($logo['url']); ?>" alt="<?php esc_attr_e('Maintenance Logo','workreap'); ?>"></strong>
                                                <?php } ?>
                                                <?php if (!empty($title)) { ?>
                                                    <div class="wt-title">
                                                        <h2><?php echo esc_html($title); ?></h2> 
                                                    </div>
                                                <?php } ?>
                                                <?php if (!empty($description)) { ?>
                                                    <div class="wt-description">
                                                        <p><?php echo wp_kses_post(do_shortcode($description)); ?></p>
                                                    </div>
                                                <?php } ?>
                                                <?php if( !empty( $date ) ) { ?>
                                                    <ul id="wt-comming-sooncounter" class="wt-comming-sooncounter">
                                                        <li class="wt-counterbox">
                                                            <div id="days" class="timer_box"></div>
                                                        </li>
                                                        <li class="wt-counterbox">
                                                            <div id="hours" class="timer_box"></div>
                                                        </li>
                                                        <li class="wt-counterbox">
                                                            <div id="minutes" class="timer_box"></div>
                                                        </li>
                                                        <li class="wt-counterbox">
                                                            <div id="seconds" class="timer_box"></div>
                                                        </li>
                                                    </ul>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <?php if( !empty( $img['url'] ) ){ ?>
                                        <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 float-left">
                                            <div class="wt-comingsoonimg">
                                                <figure><img src="<?php echo esc_url( $img['url'] ); ?>" alt="<?php esc_attr_e('Maintenance', 'workreap'); ?>"></figure>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php if (!empty($copyright)) { ?>
            <p class="wt-copyrights wt-comingsoon-wt-copyrights"><?php echo do_shortcode(esc_html( $copyright) ); ?></p>    
        <?php } ?>
        <?php
        $script = "
            (function($) {
                var launch = new Date(".esc_js($formatted_date).");
                var days = jQuery('#days');
                var hours = jQuery('#hours');
                var minutes = jQuery('#minutes');
                var seconds = jQuery('#seconds');
                setDate();
                function setDate(){
                    var now = new Date();
                    if( launch < now ){
                        days.html('<h1>0</h1><p>". esc_html__('Days','workreap') ."</p>');
                        hours.html('<h1>0</h1><p>". esc_html__('Hours','workreap') ."</p>');
                        minutes.html('<h1>0</h1><p>". esc_html__('Minutes','workreap') ."</p>');
                        seconds.html('<h1>0</h1><p>". esc_html__('Second','workreap') ."</p>');
                    }
                    else{
                        var s = -now.getTimezoneOffset()*60 + (launch.getTime() - now.getTime())/1000;
                        var d = Math.floor(s/86400);
                        days.html('<h1>'+d+'</h1><p>". esc_html__('Day','workreap') ."'+(d>1?'s':''),'</p>');
                        s -= d*86400;
                        var h = Math.floor(s/3600);
                        hours.html('<h1>'+h+'</h1><p>". esc_html__('Hour','workreap') ."'+(h>1?'s':''),'</p>');
                        s -= h*3600;
                        var m = Math.floor(s/60);
                        minutes.html('<h1>'+m+'</h1><p>". esc_html__('Minute','workreap') ."'+(m>1?'s':''),'</p>');
                        s = Math.floor(s-m*60);
                        seconds.html('<h1>'+s+'</h1><p>". esc_html__('Second','workreap') ."'+(s>1?'s':''),'</p>');
                        setTimeout(setDate, 1000);
                    }
                }
            })(jQuery);
        ";
        wp_add_inline_script('workreap-callbacks', $script, 'after');
    }
        wp_footer();
        die;
}
