<?php 
$ResultMenu = $result->result ;
      	if(FALSE){
			$ResultMenu = new ContentMenuLeft() ; 
		}
		$APP_URL = $ResultMenu->appURL ;
?>
<div class="search-top-nav grid-8">
          <!-- Search -->

          <!-- Top Navigation -->
          <div class="top-nav">
            <ul>
              <li><a class="on" href="<?php echo $APP_URL ; ?>"><i class="i i-left">&#xf015;</i> <span>Main</span></a></li>
              <li><a href="https://bitbucket.org/armteam/armphp/wiki/Home" target="_blank" ><i class="i i-left">&#xf05a;</i> <span>Wiki</span></a></li>
<!--              <li><a href="--><?php //echo $APP_URL."user_manager"; ?><!--" class="top-menu-trigger"><i class="i i-left">&#xf0ca;</i> <span>Users</span></a></li>-->
              <li>
              	<a href="https://bitbucket.org/armteam/armphp/" id="show-h-stats"><i class="i i-left">&#xf126;</i> <span>Git Info</span></a>
              </li>
            </ul>
          </div>
        </div>