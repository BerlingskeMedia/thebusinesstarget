
<div class="page static">
  <div class="bg-image" style="background-image: url(/wp-content/themes/businesstarget/dist/images/geo_bg.jpg)" >
    <div class="container main-container">
      <div class="row">
        <div class="col-md-8 col-md-offset-2">
          <h1><?php echo get_the_title(); ?></h1>
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
