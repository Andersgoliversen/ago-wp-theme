# Wordpress theme for andersgoliversen.com

A minimal WordPress theme powered by Tailwind CSS. The code here is what runs on [andersgoliversen.com](https://andersgoliversen.com) and may be useful as a reference for building your own theme.

## Setup

```bash
npm install
npm run build
```

The build step compiles `src/css/theme.src.css` to `assets/css/theme.css` and minifies JavaScript. Activate the theme in the WordPress admin panel after building.

## Homepage content

Add blocks to the page selected as the static homepage to replace the default animated featured cards. Leaving that page's content empty preserves the theme's existing Art, Rock Art Research, and Diurnalis showcase. The latest-posts section remains theme-managed in either case.

## Deployment package

Run `npm run zip` to build `dist/ago-wp-theme.zip`. The archive is assembled from an explicit allowlist of deployable theme files, so source files, build tools, dependencies, and temporary text files are excluded.

## About this repository

This project focuses on performance and simplicity. While it is tailored for andersgoliversen.com, other developers can explore the source to replicate aspects such as Tailwind integration, lazy-loaded images, and animated images/links/icons.
