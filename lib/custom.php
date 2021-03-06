<?php

function get_current_pod() {
  global $wp_query;  
  
  if(isset($wp_query->post))
    $post = $wp_query->post;

  if(!isset($post)) return null;

  $pod = pods($post->post_type, array('where' => 'id = ' . $post->ID));

  if($pod && $pod->fetch()) return $pod;

  return null;
}

function current_post_is_category() {
  global $post;
  
  return preg_match('/category/', $post->post_type);
}

function current_category_plural() {
  global $post;

  $parts = preg_split('/_/', $post->post_type, 2);
  
  $category = $parts[0];

  if(preg_match('/y$/', $category)) {
    $category = preg_replace('/y$/', 'ies', $category);
  } else {
    $category.= 's';
  }
  
  return ucwords($category);
}

function get_more_posts_of_same_type() {
  global $post;

  $pod = pods($post->post_type, array('where' => 't.post_status = "publish" AND id != ' . $post->ID));
  
  $result = "<ul class='other-categories'>";

  while($pod->fetch()) {
    $result.= "<li><a href='{$pod->field('permalink')}'>" . $pod->display('title')  . "</a></li>";
  }
  
  $result.= "</ul>";

  return $result;
}

function current_page_pod_label_plural() {
  $map = array(
    'solutions'  => 'Solutions',
    'services'   => 'Services',
    'industries' => 'Industries'
  );
  
  return (isset($map[current_page()])) ? $map[current_page()] : null;
}

function current_page_pod_type() {
  $map = array(
    'solutions' => 'solution_category',
    'services'  => 'service_category',
    'industries' => 'industry_category',
  );

  return (isset($map[current_page()])) ? $map[current_page()] : null;
}

function solcat() {
  $my_query = new WP_Query(array(
    'post_type'   => current_page_pod_type(),
    'post_status' => 'publish'
  ));
  
  if($my_query->have_posts()) :
    while ($my_query->have_posts()) : $my_query->the_post(); ?>
       <li><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></li><?php
    endwhile;
  endif;
}

function get_category_listing() {
  $order    = 'order="CAST(display_order.meta_value AS SIGNED) ASC"';
  $template = "category_listing";
  $name     = "";

  switch(current_page()) {
  case 'solutions':
    $name = "solution_category";
    break;
  case 'services':
    $name = "service_category";
    break; 
  case 'industries':
    $name = "industry_category";
    break;
  case 'case-studies':
    $name = "case_study";
    break;
  case 'company/team-members':
    $name     = "team_member";
    $template = "employee_listing";
    break;   
  }
  
  return ($name == "") ? "" : do_shortcode("[pods name='{$name}' template='{$template}' {$order}]");
}

function get_category_listing_label() {
  switch(current_page()) {
  case 'solutions':
    return "Solutions For Your Business";
    break;
  case 'services':
    return "Services For Your Business";
    break; 
  case 'industries':
    return "Industries We Serve";
    break;
  case 'case-studies':
    return "Some Of Our Customers";
    break;
  case 'company/team-members':
    return "Alteva Executive Team";
    break;   
  default:
    return "";
  }
}

function current_page() {
  global $post;
  return get_page_uri($post->ID); 
}

function current_post_type() {
  global $post;
  return $post->post_type;
}

function nav_html($location) {
  $defaults = array(
    'theme_location'  => $location,
    'menu'            => '',
    'container'       => 'div',
    'container_class' => '',
    'container_id'    => '',
    'menu_class'      => 'menu',
    'menu_id'         => '',
    'echo'            => false,
    'fallback_cb'     => 'wp_page_menu',
    'before'          => '',
    'after'           => '',
    'link_before'     => '',
    'link_after'      => '',
    'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
    'depth'           => 0,
    'walker'          => ''
  );

  remove_filter('wp_nav_menu_args', 'roots_nav_menu_args');

  $result = wp_nav_menu($defaults);

  add_filter('wp_nav_menu_args', 'roots_nav_menu_args');
  
  return $result;
}

function get_custom_excerpt($content) {
  $parts = preg_split("/\<p>\<!--more-->\<\/p>/", $content, 2);
  
  if(count($parts) != 2) {
    $parts = preg_split('/\<p>\<span id="more-(.+)">\<\/span>\<\/p>/', $content, 2);
  }

  if (count($parts) == 2) {
    return $parts[0] .
      "<a href='#' class='read-more'>Read More</a>" .  
      "<div class='post-more'>" .
      $parts[1] . 
      "</div>";
  } else {
    return $content;
  }
}

add_filter('the_content', 'get_custom_excerpt');

function get_current_network_status($problem_only = false) {
  $where = 't.post_status = "publish"';

  if($problem_only)
    $where.= ' AND problem.meta_value = "1"'; 

  $pod = pods('status_update', array('where' => $where));
  
  $messages = array();

  while($pod->fetch()) {
    $class = ($pod->field('problem')) ? "problem" : "normal"; 

    $messages[] = "<div class='{$class}'>{$pod->display('content')}</div>";
  }

  return join($messages);
}

add_action('wp_ajax_hide_network_status', 'alteva_hide_network_status');

function alteva_hide_network_status() {
  setcookie("hide_network_status", 1, time()+3600, "/");
}

function network_status_visible() {
  return !isset($_COOKIE['hide_network_status']);
}