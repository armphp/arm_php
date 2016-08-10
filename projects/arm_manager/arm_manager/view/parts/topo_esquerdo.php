<!-- Sidebar -->
    <aside class="sidebar">
      <!-- .sidebar-mobile .sidebar-reduce -->
      <!-- Logo and Reduce Sidebar -->
      <div class="logo-reduce-sidebar">
        <div class="logo">
          <a href="<?php echo $APP_URL ; ?>">
            <img class="logo-sidebar-big" src="media/arm_220.png" alt="ARM Manager">
            <img class="logo-sidebar-small" src="media/arm_50.png" alt="ARM Manager">
          </a>
        </div>
        <div class="reduce-sidebar">&#xf0c9;</div>
      </div>
      
    <?php
    	ARMSimplePHPResult::getPageResult( "widget/dev/menu_left/" ) ;
    ?>
      <!-- Announcement Widget -->
      
    </aside>