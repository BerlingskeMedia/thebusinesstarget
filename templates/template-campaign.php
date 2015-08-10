<?php
/*
Template Name: Campaign template
*/
?>
<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/content', 'campaign'); ?>
<?php endwhile; ?>
