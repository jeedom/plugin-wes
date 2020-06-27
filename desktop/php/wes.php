<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$plugin = plugin::byId('wes');
sendVarToJS('eqType', $plugin->getId());
$eqLogics = eqLogic::byType($plugin->getId());
$numberOfEqlogic = count($eqLogics);
$typeArray = array();
$typeArray[] = 'general';
$forJSArrayType = '#generaltab';
?>

<div class="row row-overflow">
	<div class="col-xs-12 eqLogicThumbnailDisplay">
		<legend><i class="fas fa-cog"></i>  {{Gestion}}</legend>
		<div class="eqLogicThumbnailContainer">
			<div class="cursor eqLogicAction logoPrimary" data-action="add">
				<i class="fas fa-plus-circle"></i>
				<br>
				<span>{{Ajouter}}</span>
			</div>
			<div class="cursor eqLogicAction logoSecondary" data-action="gotoPluginConf">
				<i class="fas fa-wrench"></i>
				<br>
				<span>{{Configuration}}</span>
			</div>
		</div>
  	<?php if($numberOfEqlogic != 0){
    	echo '<ul class="nav nav-tabs" role="tablist">';
      	echo '<li role="presentation" class="active"><a href="#generaltab" class="wesTab" aria-controls="home" role="tab" data-toggle="tab">{{Général}}</a></li>';
      	foreach ($eqLogics as $eqLogic) {
        	if(!in_array($eqLogic->getConfiguration('type'), $typeArray)){
            	$typeArray[] = $eqLogic->getConfiguration('type');
              	echo '<li role="presentation"><a href="#'.$eqLogic->getConfiguration('type').'tab" class="wesTab" aria-controls="profile" role="tab" data-toggle="tab">{{'.$eqLogic->getConfiguration('type').'}}</a></li>';
            }
        }
      	echo '</ul>';
  	}?>
		<?php
		if (count($eqLogics) == 0) {
          	echo '<div class="eqLogicThumbnailContainer">';
			echo "<br/><br/><br/><center><span style='color:#767676;font-size:1.2em;font-weight: bold;'>{{Vous n'avez pas encore de Wes, cliquez sur Ajouter pour commencer}}</span></center>";
          	echo '</div>';
		} else {
          echo '<div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">';
          foreach ($typeArray as $keyType) {
            if($keyType == 'general'){
              echo '<div role="tabpanel" class="tab-pane active" id="'.$keyType.'tab">';
            }else{
              echo '<div role="tabpanel" class="tab-pane" id="'.$keyType.'tab">';
              $forJSArrayType = $forJSArrayType.', #'.$keyType.'tab';
            }
            echo '<div class="eqLogicThumbnailContainer">';
          	foreach ($eqLogics as $eqLogic) {
              	if($eqLogic->getConfiguration('type') == $keyType){
                  $opacity = ($eqLogic->getIsEnable()) ? '' : 'disableCard';
                  echo '<div class="eqLogicDisplayCard cursor '.$opacity.'" data-eqLogic_id="' . $eqLogic->getId() . '">';
                  echo '<img src="' . $plugin->getPathImgIcon() . '"/>';
                  echo '<br>';
                  echo '<span class="name">' . $eqLogic->getHumanName(true, true) . '</span>';
                  echo '</div>';
                }
            }
            echo '</div>';
            echo '</div>';
            ?>
            <script>
              $(".wesTab").on('click',function(){
   				setTimeout(function(){
                   $("<?php echo $forJSArrayType; ?>").packery({
                     itemSelector: ".eqLogicDisplayCard",
                     gutter:2
                   });
                 },2);
 			  });
              </script>
            <?php
          }
          echo '</div>';
		}
		?>
  </div>
  <div class="col-xs-12 eqLogic" style="display: none;">
		<div class="input-group pull-right" style="display:inline-flex">
			<span class="input-group-btn">
			<a class="btn btn-primary btn-sm eqLogicAction roundedLeft" id="bt_goCarte"><i class="fa fa-cogs"></i> {{Accéder à la carte}}</a><a class="btn btn-primary btn-sm eqLogicAction" id="bt_configPush"><i class="fa fa-wrench"></i> {{Configuration Push}}</a><a class="btn btn-default btn-sm eqLogicAction" data-action="configure"><i class="fa fa-cogs"></i> {{Configuration avancée}}</a><a class="btn btn-default btn-sm eqLogicAction" data-action="copy"><i class="fas fa-copy"></i> {{Dupliquer}}</a><a class="btn btn-sm btn-success eqLogicAction" data-action="save"><i class="fas fa-check-circle"></i> {{Sauvegarder}}</a><a class="btn btn-danger btn-sm eqLogicAction roundedRight" data-action="remove"><i class="fas fa-minus-circle"></i> {{Supprimer}}</a>
			</span>
		</div>
	  <ul class="nav nav-tabs" role="tablist">
	    <li role="presentation"><a href="#" class="eqLogicAction" aria-controls="home" role="tab" data-toggle="tab" data-action="returnToThumbnailDisplay"><i class="fa fa-arrow-circle-left"></i></a></li>
	    <li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-tachometer-alt"></i> {{Equipement}}</a></li>
	    <li role="presentation"><a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-list-alt"></i> {{Commandes}}</a></li>
	  </ul>
	<div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
  	<div role="tabpanel" class="tab-pane active" id="eqlogictab">
      <br/>
    	<form class="form-horizontal">
        <fieldset>
	        <div class="form-group">
	            <label class="col-sm-3 control-label">{{Nom de votre WES}}</label>
	            <div class="col-sm-3">
	                <input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
	                <input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement}}"/>
	            </div>
	        </div>
          <div class="form-group">
          	<label class="col-sm-3 control-label" >{{Objet parent}}</label>
            <div class="col-sm-3">
            	<select id="sel_object" class="eqLogicAttr form-control" data-l1key="object_id">
              	<option value="">{{Aucun}}</option>
                <?php
								foreach (jeeObject::all() as $object) {
									echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
								}
								?>
              </select>
            </div>
        	</div>
          <div class="form-group">
          	<label class="col-sm-3 control-label">{{Catégorie}}</label>
            <div class="col-sm-9">
	            <?php
	              foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
	              echo '<label class="checkbox-inline">';
	              echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" />' . $value['name'];
	              echo '</label>';
	              }
	            ?>
            </div>
        	</div>
					<div class="form-group">
          	<label class="col-sm-3 control-label" ></label>
						<div class="col-sm-9">
							<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-label-text="{{Activer}}" data-l1key="isEnable" checked/>Activer</label>
							<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-label-text="{{Visible}}" data-l1key="isVisible" checked/>Visible</label>
            </div>
					</div>
          <br />
          <div class="form-group">
          	<label class="col-sm-3 control-label">{{IP du Wes}}</label>
            <div class="col-sm-3">
            	<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="ip"/>
            </div>
          </div>
          <div class="form-group">
          	<label class="col-sm-3 control-label">{{Dossier du Wes}}</label>
            <div class="col-sm-3">
            	<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="type" value="general" placeholder="general"/>
            </div>
          </div>
          <div class="form-group">
          	<label class="col-sm-3 control-label">{{Port du Wes}}</label>
            <div class="col-sm-3">
            	<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="port"/>
            </div>
          </div>
          <div class="form-group">
          	<label class="col-sm-3 control-label">{{Compte du Wes}}</label>
            <div class="col-sm-3">
            	<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="username"/>
            </div>
          </div>
          <div class="form-group">
          	<label class="col-sm-3 control-label">{{Password du Wes}}</label>
          	<div class="col-sm-3">
          		<input type="password" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="password"/>
          	</div>
        	</div>
        </fieldset>
      </form>
		</div>
    <div role="tabpanel" class="tab-pane" id="commandtab">
    	<table id="table_cmd" class="table table-bordered table-condensed">
      	<thead>
        	<tr>
          	<th style="width: 50px;">#</th>
            <th style="width: 230px;">{{Nom}}</th>
            <th style="width: 110px;">{{Sous-Type}}</th>
            <th>{{Valeur}}</th>
            <th style="width: 100px;">{{Unité}}</th>
            <th style="width: 200px;">{{Paramètres}}</th>
            <th style="width: 100px;"></th>
          </tr>
        </thead>
        <tbody>

        </tbody>
      </table>
		</div>
	</div>
	</div>
</div>

<?php
include_file('core', 'plugin.template', 'js');
include_file('desktop', 'wes', 'js', 'wes');
?>
