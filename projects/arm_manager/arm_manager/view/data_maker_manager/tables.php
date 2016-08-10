<!doctype html>
<html lang="en">
<?php
$content = $result->result ; 

if(FALSE) $content = new ContentDataMakerResultVO() ;
 
?>
<?php
	include $FOLDER_VIEW."parts/basic_head.php"; 
?>
<!-- Main Container -->
  <div class="container">
    <!-- Sidebar -->
    <?php 
    include $FOLDER_VIEW."parts/topo_esquerdo.php";
    ?>
    <!-- Contents -->
    <div class="contents">
	
    <?php 
    include $FOLDER_VIEW."parts/header.php";
    ?>
        
        <!-- Title & Sitemap -->
        <div class="title-sitemap grid-12">
          <h1 class="grid-6"><i>&#xf135;</i>DataMaker<span>step 3 [tables]</span></h1>
          <div class="sitemap grid-6">
            <ul>
              <li><a href="<?php echo $APP_URL ."data_maker_manager/";?>" ><span>Conexão</span></a><i>/</i></li>
              <li><a href="<?php echo $APP_URL ."data_maker_manager/database/";?>" ><span>Database</span></a><i>/</i></li>
              <li><span><strong>Tabelas</strong></span><i>/</i></li>
              <li><span>Resultado</span></li>
            </ul>
          </div>
        </div>
        
        <div class="data grid-12">
        <!-- Sign-up forms -->
        <div class="grid-6">
          <div class="widget">
            <header class="widget-header">
              <div class="widget-header-icon">&#xf135;</div>
              <h3 class="widget-header-title">Tabelas</h3>
            </header>
            <div class="widget-body no-padding">
              <form action="<?php echo $CURRENT_CONTROLLER_URL ; ?>save_tables/" method="post">
              
              <div class="widget-separator grid-6 no-border">
                <h5 class="typo light">Selecione as tabelas</h5>
                <?php foreach( $content->tables as $table ) {

                	?>
                <input name="tables[]" type="checkbox" value="<?php echo $table ; ?>" class="uniform"><h5 class="typo inline"><?php echo $table ; ?></h5><br />
                <?php } ?>
              </div>
              <div class="widget-separator no-border grid-12">
                <input type="submit" value="Do it" class="btn btn-submit btn-3d btn-small">
              </div>
              </form>
            </div>
          </div>
        </div>
        
        
      </div>
        
      </div>
      
      <?php 
        include $FOLDER_VIEW."parts/footer.php";
        ?>
   
    </div>
     
</body>
</html>