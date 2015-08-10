<?php
/*
Template Name: Static template
*/
?>
<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/content', 'static'); ?>
<?php endwhile; ?>
