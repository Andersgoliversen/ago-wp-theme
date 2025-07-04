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
        <script>
        if(localStorage.theme==='dark'||(!localStorage.theme&&window.matchMedia('(prefers-color-scheme: dark)').matches)){
            document.documentElement.classList.add('dark');
        }
        </script>
        <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
        <?php wp_body_open(); ?>

        <header id="masthead"
                class="bg-pagebg/60 dark:bg-neutral-900/60 backdrop-blur supports-[backdrop-filter]:bg-pagebg/30 supports-[backdrop-filter]:dark:bg-neutral-900/30">
                <div class="max-w-7xl mx-auto flex flex-col items-center gap-4 px-6 py-4 md:flex-row md:justify-center">

                        <?php
                        /* ----------  Site logo and title ------------------- */
                        $logo_classes  = 'no-underline text-inherit decoration-transparent transition-transform duration-150 hover:scale-105 hover:opacity-80 active:scale-95 active:text-neutral-900 dark:active:text-neutral-100 inline-flex items-center'; // Added active state for click feedback
                        // Add larger text size if on front page
                        if (is_front_page()) {
                                $name_classes = 'no-underline text-inherit decoration-transparent transition-transform duration-150 hover:scale-105 hover:text-neutral-600 dark:hover:text-neutral-300 hover:opacity-80 active:scale-95 active:text-neutral-900 dark:active:text-neutral-100 text-xl md:text-2xl font-semibold'; // Add active state
                        } else {
                                $name_classes = 'no-underline text-inherit decoration-transparent transition-transform duration-150 hover:scale-105 hover:text-neutral-600 dark:hover:text-neutral-300 hover:opacity-80 active:scale-95 active:text-neutral-900 dark:active:text-neutral-100 text-xl font-semibold'; // Add active state
                        }
                        ?>
                        <div class="flex items-center gap-2">
                                <a href="<?php echo esc_url(home_url('/')); ?>"
                                        class="<?php echo esc_attr($logo_classes); ?> ag-interactive">
                                        <?php
                                        // Use 'medium' size for the logo for better performance
                                        // and add fetchpriority high as it's likely an LCP element.
                                        echo wp_get_attachment_image(
                                                8713,
                                                'medium', // Changed from 'full' to 'medium'
                                                false,
                                                [
                                                        'class'           => 'h-[3em] w-auto ag-icon', // Tailwind class for height, width auto
                                                        'alt'             => get_bloginfo('name') . ' - Home',
                                                        'fetchpriority'   => 'high', // Prioritize fetching this image
                                                        'loading'         => 'eager', // Eager load LCP candidates
                                                ]
                                        );
                                        ?>
                                </a>
                                <a href="<?php echo esc_url(home_url('/')); ?>"
                                        class="<?php echo esc_attr($name_classes); ?>">
                                        <?php bloginfo('name'); ?>
                                </a>
                                <button class="theme-toggle md:hidden no-underline decoration-transparent ml-2" aria-label="Toggle color scheme">
                                        <svg class="sun w-5 h-5 transition-transform duration-150 hover:scale-105 active:scale-95" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <circle cx="12" cy="12" r="5"/>
                                                <g>
                                                        <line x1="12" y1="1" x2="12" y2="3"/>
                                                        <line x1="12" y1="21" x2="12" y2="23"/>
                                                        <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/>
                                                        <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
                                                        <line x1="1" y1="12" x2="3" y2="12"/>
                                                        <line x1="21" y1="12" x2="23" y2="12"/>
                                                        <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/>
                                                        <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
                                                </g>
                                        </svg>
                                        <svg class="moon w-5 h-5 transition-transform duration-150 hover:scale-105 active:scale-95" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
                                        </svg>
                                </button>
                        </div>

                        <div class="flex flex-wrap items-center justify-center gap-4">
                        <nav class="flex flex-wrap gap-4 justify-center">
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
                                        $classes = 'no-underline text-inherit decoration-transparent transition-transform duration-150 hover:scale-105 hover:text-neutral-600 dark:hover:text-neutral-300 active:scale-95 active:text-neutral-900 dark:active:text-neutral-100'; // Active state for nav links
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
                                class="no-underline text-inherit decoration-transparent transition-transform duration-150 hover:scale-105 hover:text-neutral-600 dark:hover:text-neutral-300 active:scale-95 active:text-neutral-900 dark:active:text-neutral-100 ag-interactive"
                                aria-label="GitHub profile"><!-- Darken and shrink on click -->
                                <svg width="24" height="24" viewBox="0 0 16 16" class="w-6 h-6 fill-current ag-icon" aria-hidden="true">
                                        <path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82A7.64 7.64 0 0 1 8 4.69c.68 0 1.36.09 2 .27 1.53-1.03 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.19 0 .21.15.46.55.38A8.014 8.014 0 0 0 16 8c0-4.42-3.58-8-8-8z" />
                                </svg>
                        </a>
                        <!-- ----------  YouTube icon link ------------------------------------- -->
                        <a href="https://www.youtube.com/@andersgoliversen" target="_blank" rel="noopener"
                                class="no-underline decoration-transparent transition-transform duration-150 hover:scale-105 active:scale-95 active:text-neutral-900 dark:active:text-neutral-100 group header-youtube-link ag-interactive"
                                aria-label="YouTube channel"><!-- Darken and shrink on click -->
                                <svg width="24" height="24" viewBox="0 0 48 48" class="w-6 h-6 ag-icon" aria-hidden="true">
                                        <circle cx="24" cy="24" r="24" fill="#FF1A1A" class="youtube-bg transition-colors duration-150" />
                                        <polygon points="20,32 20,16 34,24" fill="#fff" />
                                </svg>
                        </a>
                        <button class="theme-toggle hidden md:inline-flex no-underline decoration-transparent ml-2" aria-label="Toggle color scheme">
                                <svg class="sun w-5 h-5 transition-transform duration-150 hover:scale-105 active:scale-95" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="5"/>
                                        <g>
                                                <line x1="12" y1="1" x2="12" y2="3"/>
                                                <line x1="12" y1="21" x2="12" y2="23"/>
                                                <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/>
                                                <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
                                                <line x1="1" y1="12" x2="3" y2="12"/>
                                                <line x1="21" y1="12" x2="23" y2="12"/>
                                                <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/>
                                                <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
                                        </g>
                                </svg>
                                <svg class="moon w-5 h-5 transition-transform duration-150 hover:scale-105 active:scale-95" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
                                </svg>
                        </button>
                        </div>

                </div>

                <div class="max-w-7xl mx-auto px-6 pb-4 text-center">
                        <?php get_search_form(); ?>
                </div>
        </header>