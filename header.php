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
                        <!-- ----------  GitHub icon link ---------------------------- -->
                        <a href="https://github.com/Andersgoliversen" target="_blank" rel="noopener"
                                class="no-underline text-inherit decoration-transparent transition-transform duration-150 hover:scale-105 hover:text-neutral-600"
                                aria-label="GitHub profile">
                                <svg viewBox="0 0 16 16" class="w-7 h-7 fill-current" aria-hidden="true">
                                        <path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82A7.64 7.64 0 0 1 8 4.69c.68 0 1.36.09 2 .27 1.53-1.03 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.19 0 .21.15.46.55.38A8.014 8.014 0 0 0 16 8c0-4.42-3.58-8-8-8z"/>
                                </svg>
                        </a>
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