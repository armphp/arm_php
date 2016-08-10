<?php 
if(FALSE) $content = new ContentDataMakerResultVO() ;


function getColorByResult( $result ){
	return ( $result ) ? "#B3CC57":"#ff4242" ;
}
function getIconByResult( $result ){
	return ( $result ) ? "":"" ;
}

function getLineResultByKey( $resultVO , $key ){
	$comments = "" ;
	if(! $resultVO->result[$key]->success ){
		$comments = "<br/>".implode( "<br />" , $resultVO->result[$key]->array_messages ) ;
	}
	
	return "<td class=\"j-center\"><i class=\"i\" style=\"color: ". getColorByResult( $resultVO->result[ $key ]->success ).";\" >". getIconByResult( $resultVO->result[ $key ]->success )." $comments </i></td>";
}
?>
<div class="grid-12">
          <div class="widget">
            <header class="widget-header">
              <div class="widget-header-icon">&#xf135;</div>
              <h3 class="widget-header-title">Result</h3>
            </header>
        
        
        <div class="widget-body no-padding">
		<table class="tables table-bordered table-pricing">
			<thead>
				<tr>
					<th style="min-width: 200px;">Tabela</th>
					<th>BaseEntity</th>
					<th>Entity</th>
					<th>BaseDAO</th>
					<th>DAO</th>
					<th>VO</th>
					<th>ModelGateway</th>
					<th>Module</th>
					
					<th>Final</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach( $content->result->result as $table => $resultVO ){ 
					
					?>
				<tr>
					<td class="j-left"><?php echo $table ; ?></td>
					<?php echo getLineResultByKey( $resultVO , "BaseEntity" ) ;?>
					<?php echo getLineResultByKey( $resultVO , "Entity" ) ;?>
					<?php echo getLineResultByKey( $resultVO , "BaseDAO" ) ;?>
					<?php echo getLineResultByKey( $resultVO , "DAO" ) ;?>
					<?php echo getLineResultByKey( $resultVO , "VO" ) ;?>
					<?php echo getLineResultByKey( $resultVO , "ModelGateway" ) ;?>
					<?php echo getLineResultByKey( $resultVO , "Module" ) ;?>
					
					<td class="j-center"><i class="i" style="color: <?php echo getColorByResult( $resultVO->success ) ; ?>;"><?php echo getIconByResult( $resultVO->success ) ?></i></td>
				</tr>
				
				<?php } // end foreach ?>
			</tbody>
		</table>
		</div>
        
        
          </div>
        </div>

