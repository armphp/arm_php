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
          <h1 class="grid-6"><i>&#xf135;</i>DataMaker<span>step 4 [result]</span></h1>
          <div class="sitemap grid-6">
            <ul>
              <li><a href="<?php echo $APP_URL ."data_maker_manager/";?>" ><span>Conexão</span></a><i>/</i></li>
              <li><span>Database e config</span><i>/</i></li>
              <li><span>Tabelas</span><i>/</i></li>
              <li><span><strong>Resultado</strong></span></li>
            </ul>
          </div>
        </div>
        
        <div class="data grid-12">
        <!-- Sign-up forms -->
        
             <?php 
        
        include $FOLDER_VIEW."data_maker_manager/results/".$content->driver_module.".php" ;
        
        ?>
        
      </div>
        
      </div>
      
      <?php 
        include $FOLDER_VIEW."parts/footer.php";
        ?>
   
    </div>
     
</body>
</html>