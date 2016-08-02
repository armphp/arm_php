 <!-- Header -->
      <header class="header grid-12">
        <!-- Mobide Header -->
        <div class="grid-12 mobile-header">
          <!-- Logo -->
          <div class="logo-mh">
            <a href="index.html">
              <img src="media/arm_50.png" alt="acura-logo">
            </a>
          </div>
          <!-- Reduce -->
          <div class="reduce-sidebar-mh">&#xf0c9;</div>
        </div>
        <!-- Search and Top Nav-->
        <?php
			ARMSimplePHPResult::getPageResult( "widget/dev/top_search/" ) ;
		?>

        <?php
		//	ARMSimplePHPResult::getPageResult( "widget/user_info/top_right/" ) ;
		?>
        
        </header>