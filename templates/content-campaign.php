<?php
  $bg = get_field('background_image');
  if (!$bg)
  $bg = "/wp-content/themes/businesstarget/dist/images/geo_bg.jpg";
?>

<div class="page campaign">
  <div class="bg-image" style="background-image: url(<?php echo $bg ?>)">
    <?php if (get_field('show_overlay') == true ) : ?>
    	<div class="overlay"></div>
    <?php endif; ?>
    <div class="container main-container">
      <div class="row">
        <div class="col-md-8 col-md-offset-2">
          <h1><?php echo get_field('header')?></h1>
          <div class="page-content">
            <?php the_content();?>
          </div>
        </div>
      </div>
      <div class="row">
        <?php get_template_part('templates/content', 'signup'); ?>
      </div>
    </div>
  </div>
</div>
<div class="points">
  <div class="container">
    <div class="col-md-4">
      <article class="wow left fadeInLeftBig">
          <?php the_field('points_left', 'option'); ?>
        <div class="image" />
      </article>
    </div>
    <div class="col-md-4">
      <article class="wow middle middle-break fadeInUpBig">
          <?php the_field('points_middle', 'option'); ?>
        <div class="image" />
      </article>
    </div>
    <div class="col-md-4">
      <article class="wow right middle-break fadeInRightBig">
          <?php the_field('points_right', 'option'); ?>
        <div class="image" />
      </article>
    </div>
  </div>
</div>
