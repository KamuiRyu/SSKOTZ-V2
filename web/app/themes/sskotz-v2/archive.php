<?php
/**
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.2
 */

$templates = array('archive.twig', 'index.twig');
$context = Timber::context();
$timber_post = new Timber\Post();

// Pegar o parametro do URL
$archive = $wp_query->get_queried_object();
$paged = get_query_var('paged') ? get_query_var('paged') : 1;
/**
 * Returns all posts of type 'cosmos' with custom fields and organized (only needed)
 *
 * @return array
 */

function getArchiveCosmos()
{
    $cosmos = [];
    $args = array(
        'post_type' => 'cosmos',
        'post_satus' => 'publish',
        'numberposts' => -1
    );
    $cosmos_posts = Timber::get_posts($args);
    foreach ($cosmos_posts as $cosmo) {
        $data = get_fields($cosmo->ID);
        $info = [
            'cosmo_name' => $data['cosmo_name'],
            'cosmo_bonus' => $data['cosmo_bonus'],
            'cosmo_qntstatus' => $data['cosmo_qntstatus'],
            'cosmo_img' => $data['cosmo_img']['url'],
            'cosmo_type' => get_the_terms($cosmo->ID, 'cosmo_type')[0]->slug,
            'cosmo_link' => get_permalink($cosmo->ID),
            'cosmo_status1_tipo' => $data['cosmo_status1']['tipo'],
            'cosmo_status1_max' => $data['cosmo_status1']['max']
        ];
        if ($data['cosmo_qntstatus'] > 1) {
            $info['cosmo_status2_tipo'] = $data['cosmo_status2']['tipo'];
            $info['cosmo_status2_max'] = $data['cosmo_status2']['max'];
        }
        $cosmos[] = array_merge($info);
    }
    return $cosmos;
}

/**
 * Returns all posts of type 'cosmos' with custom fields and organized (only needed)
 *
 * @return array
 */

function getArchiveCharacters()
{
    $characters = [];
    $args = array(
        'post_type' => 'characters',
        'post_satus' => 'publish',
        'numberposts' => -1,
        'order' => 'ASC',
        'orderby' => 'meta_value',
        'meta_key' => 'character_rarity'
    );
    $characters_posts = Timber::get_posts($args);
    foreach ($characters_posts as $saint) {
        $data = get_fields($saint->ID);
        $info = [
            'character_name' => $data['character_name'],
            'character_name_comp' => $data['character_name_comp'],
            'character_rarity' => $data['character_rarity'],
            'character_avatar' => wp_get_attachment_image_src(
                get_post_thumbnail_id($saint->ID)
            )[0],
            'character_tier_pvp' => $data['character_tier_pvp'],
            'character_tier_pve' => $data['character_tier_pve'],
            'character_link' => get_permalink($saint->ID)
        ];
        $characters[] = array_merge($info);
    }
    return $characters;
}

/**
 * Returns category of archives
 *
 * @param [type] $archive
 * @return array
 */

function getArchiveCategoryPost($archive)
{
    $array = [];

    if ($archive->slug != 'news') {
        if ($archive->slug != 'guides') {
            $category = get_the_category();

            if (count($category) > 1) {
                $i = 0;
                foreach ($category as $cat) {
                    if ($cat->slug == 'guides' or $cat->slug == 'news') {
                        unset($category[$i]);
                    }
                    $i++;
                }
                $category = array_merge($category);
            }

            if (!empty($category)) {
                $array['name'] = $category[0]->name;
                $array['slug'] = $category[0]->slug;
            } else {
                $category = get_category_by_slug($archive->slug);
                if (!empty($category)) {
                    $array['name'] = $category->name;
                    $array['slug'] = $category->slug;
                }
            }
        } else {
            $array['name'] = 'Guias';
            $array['slug'] = 'guides';
        }
    } else {
        $array['name'] = 'Publicações';
        $array['slug'] = 'news';
    }

    return $array;
}

/**
 * Returns parent of category
 *
 * @param [type] $archive
 * @return array
 */

function GetArchiveCategoryParent($category)
{
    if ($category->category_parent > 0) {
        return get_category($category->category_parent);
    }
}

/**
 * Returns post of category or subcategory given
 * @param [type] $query
 * @return array
 */

function getArchivePosts($archive, $paged)
{
    $posts = [];
    $categoryguidesParents = get_categories(array(
        'parent' => get_category_by_slug('guides')->cat_ID
    ));
    $categorynewsParents = get_categories(array(
        'parent' => get_category_by_slug('news')->cat_ID
    ));
    $categoryIgnore = [];

    if ($archive->slug != 'news') {
        if ($archive->slug != 'guides') {
            $category = get_the_category();
            $cont = 0;
            foreach ($category as $cat) {
                if ($cat->slug == 'guides' or $cat->slug == 'news') {
                    unset($category[$cont]);
                    $category = array_merge($category);
                }
                $cont++;
            }
            if (!empty($category)) {
                $args = array(
                    'post_type' => 'post',
                    'post_satus' => 'publish',
                    'orderby' => 'publish_date',
                    'cat' => $category[0]->cat_ID,
                    'paged' => $paged,
                    'post_per_page' => 9
                );
            }
        } else {
            foreach ($categorynewsParents as $parent) {
                array_push($categoryIgnore, $parent->cat_ID);
            }
            $args = array(
                'post_type' => 'post',
                'post_satus' => 'publish',
                'orderby' => 'publish_date',
                'category__not_in' => $categoryIgnore,
                'paged' => $paged,
                'post_per_page' => 9
            );
        }
    } else {
        foreach ($categoryguidesParents as $parent) {
            array_push($categoryIgnore, $parent->cat_ID);
        }

        $args = array(
            'post_type' => 'post',
            'post_satus' => 'publish',
            'orderby' => 'publish_date',
            'category__not_in' => $categoryIgnore,
            'paged' => $paged,
            'post_per_page' => 9
        );
    }
    if (!empty($args)) {
        $context['news'] = new Timber\PostQuery($args);
        foreach ($context['news'] as $post) {
            $data = get_fields($post->ID);
            $public = [
                'post_title' => $post->post_title,
                'post_img' => $data['post_thumb']['url'],
                'post_author_name' => get_the_author_meta(
                    'display_name',
                    $post->post_author
                ),
                'post_author_avatar' => get_avatar_url($post->post_author),
                'post_author_link' => get_author_posts_url($post->post_author),
                'post_tag' => get_the_category($post->ID),
                'post_date' => date(
                    'd/m/Y H:i:s',
                    strtotime($post->post_date_gmt)
                )
            ];
            if ($archive->slug != 'news' and $archive->slug != 'guides') {
                $categories = get_the_category($post->ID);
                $server_protocol =
                    strpos(strtolower($_SERVER['SERVER_PROTOCOL']), 'https') ===
                    false
                        ? 'http'
                        : 'https';
                $key =
                    $server_protocol .
                    '://' .
                    $_SERVER['HTTP_HOST'] .
                    $_SERVER['REQUEST_URI'];
                $public['post_link'] = $key . $post->post_name;
            } else {
                $categories = get_the_category($post->ID);
                $server_protocol =
                    strpos(strtolower($_SERVER['SERVER_PROTOCOL']), 'https') ===
                    false
                        ? 'http'
                        : 'https';
                $key =
                    $server_protocol .
                    '://' .
                    $_SERVER['HTTP_HOST'] .
                    $_SERVER['REQUEST_URI'];
                foreach ($categories as $category) {
                    if ($category->slug != 'news') {
                        if ($category->slug != 'guides') {
                            if ($category->slug != 'spotlight') {
                                $key .= $category->slug;
                            }
                        }
                    }
                }
                $public['post_link'] = $key . '/' . $post->post_name;
            }
            $posts[] = array_merge($public);
        }
    }
    $posts['pagination'] = Timber::get_pagination();
    return $posts;
}

// Verifica o post type
if ($timber_post->post_type == 'cosmos') {
    // Caso seja 'cosmos', buscar todos os posts com post type cosmos
    $context['posts'] = getArchiveCosmos();
    $render = array('archive-cosmos.twig', 'archive.twig');
} elseif ($timber_post->post_type == 'characters') {
    // Caso seja 'characters', buscar todos os posts com post type characters
    $context['posts'] = getArchiveCharacters();
    $render = array('archive-characters.twig', 'archive.twig');
} else {
    // Caso não seja nenhum dos outros, verificar o parametro passado
    $context['posts'] = getArchivePosts($archive, $paged);
    $context['categorypage'] = getArchiveCategoryPost($archive);
    if ($archive->slug != 'news') {
        if ($archive->slug != 'guides') {
            $archive = GetArchiveCategoryParent($archive);
        }
    }
    if (!empty($category)) {
        $render = array('archive-' . $category->slug . '.twig', 'archive.twig');
    } else {
        $render = array('archive-' . $archive->slug . '.twig', 'archive.twig');
    }
}

Timber::render($render, $context);
