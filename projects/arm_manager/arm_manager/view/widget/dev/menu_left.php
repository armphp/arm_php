<!-- Sidebar Nav -->
      
      <nav class="nav-sidebar">
        <ul>
          <li>
            <a title="Forms" href="<?php  echo $APP_URL."project/new/"; ?>" class="<?php echo ( ContentMenuLeft::MENU_NOVO_PROJETO == $ResultMenu->selected ) ? "on" : "" ; ?> sidebar-small tipsy-w">
              <span class="icon">&#xf044;</span>
            </a>
            <a href="<?php  echo $APP_URL."project/new/"; ?>" class="<?php echo ( ContentMenuLeft::MENU_NOVO_PROJETO == $ResultMenu->selected ) ? "on" : "" ; ?> sidebar-big">
              <span class="icon">&#xf044;</span>
              New Project
            </a>
          </li>
          <li>
            <a title="Dashboard" href="<?php  echo $APP_URL."data_maker_manager/"; ?>" class="<?php echo ( ContentMenuLeft::MENU_DATA_MAKER == $ResultMenu->selected ) ? "on" : "" ; ?> sidebar-small tipsy-w">
              <span class="icon">&#xf135;</span>
            </a>
            <a href="<?php  echo $APP_URL."data_maker_manager/"; ?>" class="<?php echo ( ContentMenuLeft::MENU_DATA_MAKER == $ResultMenu->selected ) ? "on" : "" ; ?> sidebar-big">
              <span class="icon">&#xf135;</span>
              DataMaker
            </a>
          </li>
          <li>
            <a href="<?php  echo $APP_URL."stored/modules/"; ?>" class="<?php echo ( ContentMenuLeft::MENU_STORED == $ResultMenu->selected ) ? "on" : "" ; ?> sidebar-small">
              <span class="icon">&#xf0b1;</span>
            </a>
            <a href="<?php  echo $APP_URL."stored/modules/"; ?>" class="<?php echo ( ContentMenuLeft::MENU_STORED == $ResultMenu->selected ) ? "on" : "" ; ?> sidebar-big">
              <span class="icon">&#xf0b1;</span>
              Modules
            </a>
          </li>
          
        </ul>
      </nav>