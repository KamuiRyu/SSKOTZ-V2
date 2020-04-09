<?php
/**
 * Template Name: Contact Page
 * Description: A Page Template with a darker design.
 */

// Code to display Page goes here...

/**
 * The template for displaying Author Archive pages
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

$context = Timber::context();

Timber::render(array('contact.twig', 'index.twig'), $context);