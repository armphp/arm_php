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
          <h1 class="grid-6"><i>&#xf135;</i>DataMaker<span>step 2 [Select database and config]</span></h1>
          <div class="sitemap grid-6">
            <ul>
              <li><a href="<?php echo $APP_URL ."data_maker_manager/";?>" ><span>Conexão</span></a><i>/</i></li>
              <li><span><strong>Database e config</strong></span><i>/</i></li>
              <li><span>Tabelas</span><i>/</i></li>
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
              <h3 class="widget-header-title">Database</h3>
            </header>
            <div class="widget-body no-padding">
            	
              <div class="widget-separator grid-6 no-border" style="width: 506px;">
              	<h4 class="typo light">Informações de banco</h4>
              </div>
              <form action="<?php echo $CURRENT_CONTROLLER_URL ; ?>save_database/" method="post">
              <div class="widget-separator grid-6 ">
                <h5 class="typo">Nome do banco</h5>
                <?php
                if( count( $content->databases ) > 0 ) { 
                ?>
                <select name="database" class="form">
                      <?php
                    foreach( $content->databases as $database ){
						
?>
                      <option <?php
						if( ARMNavigation::getVar( "database" ) == $database ){
							echo " selected=selected "; 
						}
                      ?> value="<?php echo $database ; ?>"><?php echo $database ; ?></option>
				<?php  } ?>
                </select>
                <?php
				} //end if( count( $content->databases ) > 0 ) {
				else {
					?>
					<input name="database" type="text" class="form form-full" required="required" value="<?php 
                  
                  echo ARMNavigation::getVar( "database" ) ; ?>">
					<?php 
				}
                ?>
              </div>
              <div class="widget-separator grid-6 ">
                <h5 class="typo light">Criar TODAS as tabelas do banco?</h5>
                <input name="all" type="checkbox"  class="uniform"><h5 class="typo inline">Sim! Criar todas!</h5>
              </div>
              <input type="hidden" name="save_config" value=1 >
              <div class="widget-separator grid-6 no-border" style="width: 506px;">
              	<h4 class="typo light">Configuração</h4>
              </div>

              <div class="widget-separator grid-6 no-border">
                <h5 class="typo light">Prefixo para nome de arquivos</h5>
              	<input name="prefix_name" type="text" class="form form-full" value="ARM">
              </div>

              <div class="widget-separator no-border grid-12">
                <input type="submit" value="Próximo" class="btn btn-submit btn-3d btn-small">
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