<?php
/*
Plugin Name: Multisite Extended
Description: Permet la récupération des posts de tous les sites du réseau. Possibilité de filtrer par "catégories".
Author: Vincent Lahaye
Version: 1.0
Author URI: http://www.vincent-lahaye.com/
License: GPL2
*/
/*  Copyright 2012  Vincent Lahaye  (email : vincent.lahaye@supinfo.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if (!function_exists('add_action')){
	echo "Try again your shit<br />I dare you<br />I double dare you motherfucker";
	exit;
}

require_once 'functions.php';

add_shortcode('multisite_extended_posts', 'muextended_func');
function muextended_func($atts){
	extract(shortcode_atts(array(
		'cat' => '',
	), $atts ));

	return muextended_posts($cat);
}

function muextended_posts($cat = false){

	foreach(wp_list_sites() as $site)
		$sites[] = $site->blog_id;
			
	$args = array(
		'post_type' 	=> 'post',
		'catname'		=> $cat,
		'sites' 		=> array('sites__in' => $sites),
	);
	
	$query = new WP_Query_Multisite($args); //
	
	while($query->have_posts()) : $query->the_post();
		$output .= get_template_part('content', get_post_format());
	endwhile; 
	
	wp_reset_postdata();
		
	return $output;
}
?>
