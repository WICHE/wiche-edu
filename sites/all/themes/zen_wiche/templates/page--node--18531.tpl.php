<?php
/**
 * @file
 * Returns the HTML for a single Drupal page.
 *
 * Complete documentation for this file is available online.
 * @see http://drupal.org/node/1728148
 */
?>

<div id="page" class="benchmarks2019">
<div id="tag"> <?php if ($site_slogan): ?>
          <h2 class="header--site-slogan" id="site-slogan"><?php print $site_slogan; ?></h2>
        <?php endif; ?></div>
  <header class="header" id="header" role="banner">
    <?php if ($logo): ?>
      <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" class="header--site-link" rel="home"><img src="<?php print $logo; ?>" alt="<?php print t('Home'); ?>" class="header--logo-image" /></a>
    <?php endif; ?>
    <?php if ($site_name || $site_slogan): ?>
      <div class="header--name-and-slogan" id="name-and-slogan">
        <?php if ($site_name): ?>
          <h1 class="header--site-name" id="site-name">
            <?php print $site_name; ?>
          </h1>
        <?php endif; ?>
      </div>
    <?php endif; ?>

    <div id="secondary-menu" class="header--secondary-menu"> <?php print render($page['log_in']); ?></div>
 <!--social media-->
    <ul class="social-media" style="width:48px!important;">
   <!-- <li class="facebook"><a href="https://www.facebook.com/wiche.edu" target="_blank"><img src="/sites/all/themes/zen_wiche/images/facebook.png" alt="Follow us on Facebook" width="16" height="16" /></a></li>-->
    <li class="twitter"><a href="https://twitter.com/wicheEDU" target="_blank"><img src="/sites/all/themes/zen_wiche/images/twitter.png" alt="Follow us on Twitter" width="16" height="16" /></a></li>
    <!--<li class="delicious"><a href="https://delicious.com/wiche_edu" target="_blank"><img src="/sites/all/themes/zen_wiche/images/delicious.png" alt="Our Delicious Links" width="16" height="16" /></a></li>-->
  </ul>
  <!--end social media--> 

<div class="phone-nav">
   <nav class="nav-collapse"> <?php print render($page['phone_nav']); ?>
   </nav>
   </div>
   
    <?php print render($page['header']); ?>

  </header>
  <div id="main">
    <div id="full-rotator"> <?php print render($page['slideshow']); ?></div>
    <div id="content" class="column" role="main" style="margin-left:20px;margin-right:20px;">
      <?php print $breadcrumb; ?>
      <a id="main-content"></a>
      <?php print render($title_prefix); ?>
      <?php if ($title): ?>
        <h1 class="page--title title" id="page-title"><?php print $title; ?></h1>
      <?php endif; ?>
      <?php print render($title_suffix); ?>
      <?php print $messages; ?>
      <?php print render($tabs); ?>
      <?php print render($page['help']); ?>
      <?php print render($page['highlighted']); ?>
	  <div style="clear:left">&nbsp;</div>
      <?php print render($page['content']); ?>
      <?php if ($action_links): ?>
        <ul class="action-links"><?php print render($action_links); ?></ul>
      <?php endif; ?>
      <?php print $feed_icons; ?>
    </div>

    <div id="navigation">
	<a name="main-nav" tabindex="-1"></a>
      <?php print render($page['navigation']); ?>
 
    </div>
    <?php
      // Render the sidebars to see if there's anything in them.
      $sidebar_first  = render($page['sidebar_first']);
      $sidebar_second = render($page['sidebar_second']);
    ?>

    <?php if ($sidebar_first || $sidebar_second): ?>
      <aside class="sidebars">
        <?php print $sidebar_first; ?>
        <?php print $sidebar_second; ?>
      </aside>
    <?php endif; ?>

  </div>

 <footer id="footer" class="<?php print $classes; ?> clearfix">
    
    <div class="header-foot">
       <img id="foot-logo" src="<?php print base_path() . path_to_theme(); ?>/images/wiche-logo-footer.png" width="261" height="41" alt="WICHE, Western Interstate Commision For Higher Education" />
    <h2 id="foot-tag">Access. Collaboration. Innovation.</h2>
        
        	<div class="foot-menu">
            	 <?php print render($page['footer_menu']); ?>
            </div>
        <div class="footer-columns">
        		<div class = "copyright">
                  <?php print render($page['copy_right']); ?>
   </div>          
              </div>             
        </div><!--end header-foot-->
           
  </footer>
 </div>
</div><!-- end #page -->
             
<?php print render($page['bottom']); ?>

