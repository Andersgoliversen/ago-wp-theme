<?php

/**
 * Theme header
 * Adds hover / scale to all menu links and site title.
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="scroll-smooth">

<head>
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
        <?php wp_body_open(); ?>

        <header id="masthead"
                class="bg-pagebg/60 backdrop-blur supports-[backdrop-filter]:bg-pagebg/30">
                <div class="max-w-7xl mx-auto flex items-center justify-center gap-4 px-6 py-4">

                        <?php
                        /* ----------  Site logo and title ------------------- */
                        $logo_classes  = 'no-underline text-inherit decoration-transparent transition-transform duration-150 hover:scale-105 hover:opacity-80 inline-flex items-center';
                        // Add larger text size if on front page
                        if (is_front_page()) {
                            $name_classes = 'no-underline text-inherit decoration-transparent transition-transform duration-150 hover:scale-105 hover:text-neutral-600 hover:opacity-80 text-xl md:text-2xl font-semibold';
                        } else {
                            $name_classes = 'no-underline text-inherit decoration-transparent transition-transform duration-150 hover:scale-105 hover:text-neutral-600 hover:opacity-80 text-xl font-semibold';
                        }
                        ?>
                        <a href="<?php echo esc_url(home_url('/')); ?>"
                                class="<?php echo esc_attr($logo_classes); ?>">
                                <img src="<?php echo esc_url( wp_get_attachment_image_url( 8713, 'full' ) ); ?>"
                                     alt="Logo"
                                     class="h-[3em] w-auto" />
                        </a>
                        <a href="<?php echo esc_url(home_url('/')); ?>"
                                class="<?php echo esc_attr($name_classes); ?>">
                                <?php bloginfo('name'); ?>
                        </a>

                        <nav class="flex gap-4">
                                <?php
                                /* ----------  Primary menu --------------------------------------- */
                                $links = [
                                        ['slug' => '/blog/',     'label' => 'Blog',     'check' => is_home() || is_category()],
                                        ['slug' => '/gallery/',  'label' => 'Gallery',  'check' => is_page('gallery')],
                                        ['slug' => '/projects/', 'label' => 'Projects', 'check' => is_page('projects')],
                                        ['slug' => '/shop/',     'label' => 'Shop',     'check' => is_page('shop')],
                                        ['slug' => '/about/',    'label' => 'About',    'check' => is_page('about')],
                                ];
                                foreach ($links as $l) {
                                        $classes = 'no-underline text-inherit decoration-transparent transition-transform duration-150 hover:scale-105 hover:text-neutral-600';
                                        if ($l['check']) {
                                                $classes .= ' font-semibold';
                                        }
                                        printf(
                                                '<a href="%s" class="%s">%s</a>',
                                                esc_url(home_url($l['slug'])),
                                                esc_attr($classes),
                                                esc_html($l['label'])
                                        );
                                }
                                ?>
                        </nav>
                        <!-- ----------  YouTube icon link ------------------------------------- -->
                        <a href="https://www.youtube.com/@andersgoliversen" target="_blank" rel="noopener"
                                class="no-underline decoration-transparent transition-transform duration-150 hover:scale-105 group"
                                aria-label="YouTube channel">
                                <svg viewBox="0 0 48 48" class="w-9 h-7" aria-hidden="true">
                                        <rect x="4" y="10" width="40" height="28" rx="8" fill="#FF1A1A" class="youtube-bg transition-colors duration-150 group-hover:fill-[#FF5252]" />
                                        <polygon points="20,30 20,18 31,24" fill="#fff"/>
                                </svg>
                        </a>
                        <style>
                        a[aria-label="YouTube channel"] .youtube-bg {
                                transition: fill 0.15s;
                        }
                        a[aria-label="YouTube channel"]:hover .youtube-bg {
                                fill: #FF5252;
                        }
                        </style>

                </div>

                <div class="max-w-7xl mx-auto px-6 pb-4 text-center">
                        <?php get_search_form(); ?>
                </div>
        </header>