<?php
// $Id$
?>
	  <div id="container" class="">
      <div id="header">
        <div id="header-top">
          <div id="logo-floater">
          <?php if ($logo || $site_title): ?>
            
              <div id="branding" class="clearfix"><a href="<?php print $front_page ?>" title="<?php print $site_name_and_slogan ?>">
              <?php if ($logo): ?>
                <img src="<?php print $logo ?>" alt="<?php print $site_name_and_slogan ?>" id="logo" />
              <?php endif; ?>
              <div class="site-title"><?php print $site_name ?></div>
              </a></div>
            <?php else: /* Use h1 when the content title is empty */ ?>
              <h1 id="branding"><a href="<?php print $front_page ?>" title="<?php print $site_name_and_slogan ?>">
              <?php if ($logo): ?>
                <img src="<?php print $logo ?>" alt="<?php print $site_name_and_slogan ?>" id="logo" />
              <?php endif; ?>
              </a></h1>
        
          <?php endif; ?>
          </div>
      		<div id="header-top-right"  class="clearfix">
            <?php if ($site_slogan): ?>
            <div id="site-slogan">
              <?php print $site_slogan ?>
            </div>
            <?php endif; ?>
      		</div>
        </div>
        <div id="header-bottom">
      		<div id="header-bottom-left">&nbsp;</div>
      		<div id="header-bottom-right">
            <div class="title-area">
              <h2>This is the Ultimate Drupal Corporate Theme "Busy"</h2>
              <a class="read-more" href=".">More information</a>
            </div>
      		</div>
      		<div class="clearfix"></div>
        </div>
      </div>
      <div id="main-wrapper">
       <!-- Content Top wird als Block umgesetzt, daher braucht das hier noch ne eigene Region. Für den Start deaktiviert.  -->
       <!--  
       <div id="content_top">
          <div class="span-6 prefix-1 suffix-1 block">
            <h4>News</h4>
            <div class="block-content">
              blah fasel blup
            </div>
          </div>
          <div class="span-6 prefix-1 suffix-1 block">
            <h4>Blupdiwupp</h4>
            <div class="block-content">
              blah fasel blup
            </div>
          </div>
          <div class="span-6 prefix-1 suffix-1 block last">
            <h4>This is ...</h4>
            <div class="block-content">
              blah fasel blup
            </div>
          </div>
        </div> 
        -->
        <div id="main">
          <div id="content">
            <?php if ($main_menu): ?>
            <div id="navigation">
              <div class="section">
                <?php print theme('links__system_main_menu', array('links' => $main_menu, 'attributes' => array('id' => 'main-menu', 'class' => array('links', 'clearfix')))); ?>
              </div>
            </div>
            <?php endif; ?>
            <div id="content-area">
              <?php print $breadcrumb; ?>
              <?php if ($page['highlight']): ?>
              <div id="highlight"><?php render($page['highlight']); ?></div>
              <?php endif; ?>
              <a id="main-content"></a>
              <?php if ($tabs): ?>
              <div id="tabs-wrapper" class="clearfix">
              <?php endif; ?>
                <?php print render($title_prefix); ?>
                <?php if ($title): ?>
                <h1<?php print $tabs ? ' class="with-tabs"' : '' ?>><?php print $title ?></h1>
                <?php endif; ?>
                <?php print render($title_suffix); ?>
              <?php if ($tabs): ?>
                <?php print render($tabs) ?>
              </div>
              <?php endif; ?>
              <?php print $messages; ?>
              <?php print render($page['help']); ?>
              <?php if ($action_links): ?><ul class="action-links"><?php print render($action_links); ?></ul><?php endif; ?>
              <div class="clearfix">
                <?php print render($page['content']); ?>
              </div>
              <?php print $feed_icons ?>
              <?php print render($page['footer']) ?>
            </div>
          </div>
          <?php if ($page['sidebar_first']): ?>
          <div class="sidebar-first sidebar">
            <?php print render($page['sidebar_first']); ?>
            <?php print theme('links__system_secondary_menu', array('links' => $secondary_menu, 'attributes' => array('id' => 'secondary-menu', 'class' => array('links', 'clearfix')))); ?>
          </div>
          <?php endif; ?>
          <div class="clearfix"></div>
        </div>
      </div>
      <div class="clearfix"></div>
      <!-- Der Footer muss Block-Inhalt bekommen. Wir müssen schauen, wie der auch ohne Inhalt bzw. nur dem Standard entprechend aussieht.  -->
      <div id="page-footer">
        <?php print render($page['page_footer']); ?>
        <div class="clearfix"></div>
      </div>
    </div>
