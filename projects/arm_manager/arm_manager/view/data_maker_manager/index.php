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
    
    <script>
	function testConnection(){
		$.ajax({
			dataType: "json",
			url: "<?php echo ARMNavigation::getAppUrl("data_maker_manager/test_connection/")."return.json/" ?>",
			data: {
				host:$("[name=host]").val(),
				database:$("[name=database]").val(),
				user:$("[name=user]").val(),
				password:$("[name=password]").val(),
				driver_module:$("[name=driver_module]").val()
			},
			success: function(r){
				console.log(r);
				if( r != null && r.result != null && r.result.result != null ){
					var resultado = r.result.result ;
					console.log(resultado);
					if(resultado.success){
						alert('Conexão enviada funciona') ;
						return ;
					}
				}
				alert('Erro ao conectar') ;
			}
		});
	}
	</script>
        <!-- Title & Sitemap -->
        <div class="title-sitemap grid-12">
          <h1 class="grid-6"><i>&#xf135;</i>DataMaker<span>step 1 [Database info]</span></h1>
          <div class="sitemap grid-6">
            <ul>
              <li><span><strong>Conexão</strong></b></span><i>/</i></li>
              <li><span>Database e config</span><i>/</i></li>
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
              <h3 class="widget-header-title">Dados de Conexão</h3>
            </header>
            <div class="widget-body no-padding">
              <form action="<?php echo $CURRENT_CONTROLLER_URL ; ?>save_connection/" method="post">
              <div class="widget-separator grid-6 no-border">
                <h5 class="typo">Host</h5>
                <input type="text" name="host" required="required" value="<?php 
                
                $host = ARMNavigation::getVar( "host" ) ;

                echo ( $host ) ? $host : "localhost" ; ?>" class="form form-full">
              </div>
              <div class="grid-6 widget-separator no-border">
                <h5 class="typo">Driver Module</h5>
                <select name="driver_module" class="form">
                  <optgroup label="Basic DAO interface">
                      <option value="ARMMysqliModule">ARMMysqliModule</option>
                  </optgroup>
                </select>
              </div>
              <div class="widget-separator grid-6 no-border">
                <h5 class="typo">Username</h5>
                <div class="field-icon field-icon-full field-icon-left">
                  <i class="i">&#xf007;</i>
                  <input name="user" type="text" class="form form-full" required="required" value="<?php 
                  $userName = ARMNavigation::getVar( "user" ) ;
                  echo ( $userName && $userName != "" ) ? $userName : "root" ; ?>">
                </div>
              </div>
              <div class="widget-separator grid-6 no-border">
                <h5 class="typo">Password</h5>
                <input type="text" name="password" placeholder="<?php ARMNavigation::getVar( "password" ) ; ?>" class="form form-full">
              </div>
              
              <div class="widget-separator no-border grid-6">
                <input type="button" onclick="testConnection()" value="* Test Connection" class="btn btn-submit btn-3d btn-small">
              </div>
              <div class="widget-separator no-border grid-6">
                <input type="submit" value="Next" class="btn btn-submit btn-3d btn-big">
              </div>
              <?php 
            if( ! $content->result->success ){
				if( $content->result->array_messages ){
					foreach($content->result->array_messages as $message ){
						?>
						<div class="grid-6 widget-separator no-border"  style="width: 520px;" >
							<input disabled="disabled" class="form form-error form-full" type="text" value="<?php echo $message ; ?>">
						</div>
						<?php 
					}
				}
			}
              ?>
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