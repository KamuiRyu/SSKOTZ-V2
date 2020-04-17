<?php
/**
 * The main template file
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */

$context = Timber::context();
define('ROLE_MAP', [
    'administrator' => 'Administrador',
    'author' => 'Autor',
    'contributor' => 'Contribuidor',
    'editor' => 'Editor',
    'subscriber' => 'Assinante',
    'design' => 'Designer',
    'developer' => 'Desenvolvedor',
    'publisher' => 'Publicador',
    'streamer' => 'Streamer'
]);

$guide_cosmo = get_category_by_slug('cosmos');
$guide_dg = get_category_by_slug('dungeons');
$guide_tips = get_category_by_slug('tips');
$guide_others = get_category_by_slug('others');

// Buscar postagens para aba de ultimos personagens lançados
$context['character'] = Timber::get_posts([
    'post_type' => 'characters',
    'post_satus' => 'publish',
    'numberposts' => 10,
    'orderby' => 'modified',
    'meta_query' => array(
        array(
            'key' => 'character_spotlight',
            'value' => 'yes',
            'compare' => 'LIKE'
        )
    )
]);

// Buscar a novidade destaque mais recente
$context['spotlight'] = Timber::get_posts([
    'post_type' => 'post',
    'post_satus' => 'publish',
    'numberposts' => 1,
    'orderby' => 'publish_date',
    'category__not_in' => array(
        $guide_cosmo->cat_ID,
        $guide_dg->cat_ID,
        $guide_tips->cat_ID,
        $guide_others->cat_ID
    ),
    'category_name' => 'spotlight'
]);

$args = array(
    'post_type' => 'post',
    'post_satus' => 'publish',
    'numberposts' => 4,
    'orderby' => 'publish_date',
    'category__not_in' => array(
        $guide_cosmo->cat_ID,
        $guide_dg->cat_ID,
        $guide_tips->cat_ID,
        $guide_others->cat_ID
    )
);

if (!empty($context['spotlight'][0]->ID)) {
    $args['post__not_in'] = array($context['spotlight'][0]->ID);
}

// Buscar todas as novidades recentes
$context['public'] = Timber::get_posts($args);
// Buscar os membros da equipe
$context['team'] = get_users([
    'numberposts' => 6,
    'orderby' => 'rand'
]);


/**
 * Returns latest videos in channel SamukaPLM
 *
 * @param [type] $person
 * @return array
 */
/*

function getVideosChannel(){
    $channel_id = 'UUfhfWhrL7BNK6-_VtoXMdlA';
    $api_key = 'AIzaSyAkLlbeC6LEltupKzKX7bYut49ioiFiu5M';
    
    $json_url="https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&playlistId=".$channel_id."&key=".$api_key;
    $json = file_get_contents($json_url);
    $listFromYouTube=json_decode($json);
    $videos = array();
    $i = 0;
    foreach($listFromYouTube->items as $item){
        $videos[$i] = [
            'title' => $item->snippet->title,
            'thumbnail' => $item->snippet->thumbnails->medium->url,
            'channeltitle' => $item->snippet->channelTitle,
            'video' => 'https://www.youtube.com/watch?v='.$item->snippet->resourceId->videoId
        ];
        $i++;
    }
    return $videos;
}
*/

/**
 * Returns extra info about a given user
 *
 * @param [type] $person
 * @return array
 */


function getUserInfo($person)
{
    $user = [];
    foreach (['discord', 'twitter', 'facebook', 'instagram'] as $socialMedia) {
        $media = get_user_meta($person->ID, $socialMedia);
        if (isset($media[0]) && !empty($media[0])) {
            $user[$socialMedia] = $media[0];
        }
    }

    $live = get_user_meta($person->ID, 'live');

    if (isset($live[0]) && !empty($live[0])) {
        $user['live'] = $live[0];
        if (strpos($live[0], 'twitch') != '') {
            $user['plataform_live'] = 'twitch';
        } elseif (strpos($live[0], 'youtube') != '') {
            $user['plataform_live'] = 'youtube';
        } elseif (strpos($live[0], 'facebook') != '') {
            $user['plataform_live'] = 'facebook';
        } else {
            $user['plataform_live'] = 'other';
        }
    }

    return $user;
}

/**
 * Returns all roles as text
 *
 * @param [type] $user
 * @return string
 */

function getUserRolesAsText($user)
{
    $roles = [];
    foreach ($user->roles as $role) {
        $roles[] = ROLE_MAP[$role];
    }

    return implode(', ', $roles);
}

/**
 * Returns posts of type 'character' with custom fields and organized (only needed)
 *
 * @param [type] $query
 * @return array
 */

function getPostsCharacter($query)
{
    $characters = [];
    foreach ($query as $post) {
        $data = get_fields($post->ID);
        $skills = get_fields($data['character_skills']->ID);
        unset($skills['skill_qnt']);
        unset($skills['skill_has_cr']);
        unset($skills['skill_cr']);
        unset($skills['skill_cr_number']);
        $saint = [
            'character_name' => $post->character_name,
            'character_slug' => $post->slug,
            'character_description' => $data['character_description'],
            'character_rarity' => $data['character_rarity'],
            'character_thumb' => wp_get_attachment_image_src(
                get_post_thumbnail_id($post->ID)
            )[0],
            'character_picture' => $data['character_img_spotlight']['url'],
            'character_link' => get_the_permalink($post->ID)
        ];
        $saint['character_skills'] = array_merge($skills);
        $characters[] = array_merge($saint);
    }
    return $characters;
}

/**
 * Returns posts of category 'spotlight' with custom fields and organized (only needed)
 *
 * @param [type] $query
 * @return array
 */

function getPostsSpotlight($query)
{
    $spotlights = [];
    foreach ($query as $post) {
        $data = get_fields($post->ID);
        $spotlight = [
            'post_title' => $post->post_title,
            'post_img' => $data['post_thumb']['url'],
            'post_author_name' => get_the_author_meta(
                'display_name',
                $post->post_author
            ),
            'post_author_avatar' => get_avatar_url($post->post_author),
            'post_author_link' => get_author_posts_url($post->post_author),
            'post_tag' => get_the_category($post->ID),
            'post_date' => date('d/m/Y H:i:s', strtotime($post->post_date_gmt))
        ];
        $categories = get_the_category($post->ID);
        $server_protocol =
            strpos(strtolower($_SERVER['SERVER_PROTOCOL']), 'https') === false
                ? 'http'
                : 'https';
        $key =
            $server_protocol .
            '://' .
            $_SERVER['HTTP_HOST'] .
            $_SERVER['REQUEST_URI'];
        if (array_search('news', array_column($categories, 'slug')) > 0) {
            $key .= 'news';
        } elseif (
            array_search('guides', array_column($categories, 'slug')) > 0
        ) {
            $key .= 'guides';
        }
        foreach ($categories as $category) {
            if ($category->slug != 'news') {
                if ($category->slug != 'guides') {
                    if ($category->slug != 'spotlight') {
                        $key .= '/' . $category->slug;
                    }
                }
            }
        }
        $spotlight['post_link'] = $key . '/' . $post->post_name;
        $spotlights[] = array_merge($spotlight);
    }
    return $spotlights;
}

/**
 * Returns posts of category 'news' with custom fields and organized (only needed)
 *
 * @param [type] $query
 * @return array
 */

function getPostsNews($query)
{
    $publics = [];
    foreach ($query as $post) {
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
            'post_date' => date('d/m/Y H:i:s', strtotime($post->post_date_gmt))
        ];
        $categories = get_the_category($post->ID);
        $server_protocol =
            strpos(strtolower($_SERVER['SERVER_PROTOCOL']), 'https') === false
                ? 'http'
                : 'https';
        $key =
            $server_protocol .
            '://' .
            $_SERVER['HTTP_HOST'] .
            $_SERVER['REQUEST_URI'];
        
        foreach($categories as $category){
            if($category->slug == 'news'){
                $key .= 'news';
            }elseif($category->slug == 'guides'){
                $key .= 'guides';
            }
        }

        foreach ($categories as $category) {
            if ($category->slug != 'news') {
                if ($category->slug != 'guides') {
                    if ($category->slug != 'spotlight') {
                        $key .= '/' . $category->slug;
                    }
                }
            }
        }
        $public['post_link'] = $key . '/' . $post->post_name;
        $publics[] = array_merge($public);
    }
    return $publics;
}

/**
 * Returns extra info about team members
 *
 * @param [type] $users
 * @return array
 */

function getTeamMembers($users)
{
    $team = [];
    foreach ($users as $user) {
        $person = get_userdata($user->ID);
        $user = [
            'name' => $person->display_name,
            'avatar' => get_avatar_url($person->ID),
            'role' => getUserRolesAsText($person)
        ];
        $extraInfo = getUserInfo($person);

        $team[] = array_merge($user, $extraInfo);
    }
    return $team;
}

// Passando os arrays para dentro do context
$context['spotlight'] = getPostsSpotlight($context['spotlight']);
$context['public'] = getPostsNews($context['public']);
$context['character'] = getPostsCharacter($context['character']);
$context['team'] = getTeamMembers($context['team']);
$templates = array('index.twig');
Timber::render($templates, $context);
