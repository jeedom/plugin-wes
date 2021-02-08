<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$plugin = plugin::byId('wes');
sendVarToJS('eqType', $plugin->getId());
$eqLogics = eqLogic::byType($plugin->getId());
$typeArray = wes::getTypes();
$typeid = array();
foreach ($typeArray as $type => $data) {
	$typeid[] = $type;
}
sendVarToJS('typeid', $typeid);
?>

<div class="row row-overflow">
	<div class="col-lg-12 eqLogicThumbnailDisplay">
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
	<?php
		if (count($eqLogics) == 0) {
			echo '<div class="eqLogicThumbnailContainer">';
			echo "<br/><br/><br/><center><span style='color:#767676;font-size:1.2em;font-weight: bold;'>{{Vous n'avez pas encore de Wes, cliquez sur Ajouter pour commencer}}</span></center>";
			echo '</div>';
		} else {
			echo '<ul class="nav nav-tabs" role="tablist">';
			foreach ($typeArray as $type => $data) {
				$img = $plugin->getPathImgIcon();
				if (file_exists(dirname(__FILE__) . '/../../core/config/'.$type.'.png')) {
					$img = 'plugins/wes/core/config/'.$type.'.png';
				}
				echo '<li role="presentation"><a href="#'.$type.'tab" class="wesTab" aria-controls="profile" role="tab" data-toggle="tab"><img src="'.$img.'" height="24px"/> {{'.$data['name'].'}}</a></li>';
			}
			echo '</ul>';
			echo '<div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">';
			foreach ($typeArray as $type => $data) {
				echo '<div role="tabpanel" class="tab-pane active" id="'.$type.'tab">';
				echo '<div class="eqLogicThumbnailContainer packery">';
				foreach ($eqLogics as $eqLogic) {
					if($eqLogic->getConfiguration('type') == $type){
						$opacity = ($eqLogic->getIsEnable()) ? '' : 'disableCard';
						echo '<div class="eqLogicDisplayCard cursor '.$opacity.'" data-eqLogic_id="' . $eqLogic->getId() . '">';
						if (file_exists(dirname(__FILE__) . '/../../core/config/'.$type.'.png')) {
							echo '<img class="lazy" src="plugins/wes/core/config/'.$type.'.png"/>'; 
						} else {
							echo '<img src="' . $plugin->getPathImgIcon() . '"/>';
						}
						echo '<br>';
						echo '<span class="name">' . $eqLogic->getHumanName(true, true) . '</span>';
						echo '</div>';
					}
				}
				echo '</div>';
				echo '</div>';
			}
			echo '</div>';
		}
	?>
	</div>
	<div class="col-lg-12 eqLogic" style="display: none;">
		<div class="input-group pull-right" style="display:inline-flex">
			<span class="input-group-btn">
			<a class="btn btn-default btn-sm eqLogicAction roundedLeft" data-action="configure"><i class="fa fa-cogs"></i> {{Configuration avancée}}</a><a class="btn btn-primary btn-sm eqLogicAction showgeneral" id="bt_goCarte"><i class="fa fa-cogs"></i> {{Accéder à la carte}}</a><a class="btn btn-primary btn-sm eqLogicAction showgeneral" id="bt_configPush"><i class="fa fa-wrench"></i> {{Configuration Push}}</a><a class="btn btn-default btn-sm eqLogicAction" data-action="copy"><i class="fas fa-copy"></i> {{Dupliquer}}</a><a class="btn btn-sm btn-success eqLogicAction" data-action="save"><i class="fas fa-check-circle"></i> {{Sauvegarder}}</a><a class="btn btn-danger btn-sm eqLogicAction roundedRight" data-action="remove"><i class="fas fa-minus-circle"></i> {{Supprimer}}</a>
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
				<div class="col-sm-7">
					<form class="form-horizontal">
						<fieldset>
							<div class="form-group">
								<label class="col-sm-3 control-label">{{Nom}}</label>
								<div class="col-sm-7">
									<input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
									<input id="typeEq" type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="type" style="display : none;"/>
									<input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement}}"/>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" >{{Objet parent}}</label>
								<div class="col-sm-7">
									<select class="eqLogicAttr form-control" data-l1key="object_id">
										<option value="">{{Aucun}}</option>
										<?php
											$options = '';
											foreach ((jeeObject::buildTree(null, false)) as $object) {
												$options .= '<option value="' . $object->getId() . '">' . str_repeat('&nbsp;&nbsp;', $object->getConfiguration('parentNumber')) . $object->getName() . '</option>';
											}
											echo $options;
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
									<div class="col-sm-7">
										<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-label-text="{{Activer}}" data-l1key="isEnable" checked/>Activer</label>
										<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-label-text="{{Visible}}" data-l1key="isVisible" checked/>Visible</label>
									</div>
							</div>
							<br/>
							<div class="form-group showgeneral" style="display: none;" data-cmd_id="ipAddress">
								<label class="col-sm-3 control-label">{{IP du Wes}}</label>
								<div class="col-sm-7">
									<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="ip" data-cmd_id="ipAddress"/>
								</div>
							</div>
							<div class="form-group showgeneral" style="display: none;" data-cmd_id="port">
								<label class="col-sm-3 control-label">{{Port du Wes}}</label>
								<div class="col-sm-7">
									<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="port" data-cmd_id="port"/>
								</div>
							</div>
							<div class="form-group showgeneral" style="display: none;" data-cmd_id="username">
								<label class="col-sm-3 control-label">{{Compte du Wes}}</label>
								<div class="col-sm-7">
									<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="username" data-cmd_id="username"/>
								</div>
							</div>
							<div class="form-group showgeneral" style="display: none;" data-cmd_id="password">
								<label class="col-sm-3 control-label">{{Password du Wes}}</label>
								<div class="col-sm-7">
									<input type="password" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="password" data-cmd_id="password"/>
								</div>
							</div>
							<div class="form-group showgeneral" style="display: none;" data-cmd_id="ftpusername">
								<label class="col-sm-3 control-label">{{Compte FTP}}</label>
								<div class="col-sm-7">
									<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="ftpusername" data-cmd_id="ftpusername"/>
								</div>
							</div>
							<div class="form-group showgeneral" style="display: none;" data-cmd_id="ftppassword">
								<label class="col-sm-3 control-label">{{Password FTP}}</label>
								<div class="col-sm-7">
									<input type="password" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="ftppassword" data-cmd_id="ftppassword"/>
								</div>
							</div>
							<div class="form-group showgeneral" style="display: none;" data-cmd_id="usecustomcgx">
								<label class="col-sm-3 control-label" ></label>
								<div class="col-sm-7">
									<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-label-text="{{Activer}}" data-l1key="configuration" data-l2key="usecustomcgx"/>Utiliser Fichier CGX Jeedom</label>
								</div>
							</div>
							<div class="form-group showteleinfo" style="display: none;" data-cmd_id="tarification">
								<label class="col-sm-3 control-label">{{Tarification}}</label>
								<div class="col-sm-7">
									<select class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="tarification"  data-cmd_id="tarification">
										<option value="">Sans</option>
										<option value="BASE">Base</option>
										<option value="HC">Heure creuse/Heure pleine</option>
										<option value="BBRH">Tempo</option>
										<option value="EJP">EJP</option>
									</select>
								</div>
							</div>
						</fieldset>
					</form>
				</div>
				<div class="col-sm-5">
					<form class="form-horizontal">
						<fieldset>
							<div class="form-group">
								<div style="text-align: center">
									<center>
										<img src="core/img/no_image.gif" data-original=".jpg" id="img_device" class="img-responsive" style="max-height : 250px;"  onerror="this.src='plugins/wes/plugin_info/wes_icon.png'"/>
									</center>
								</div>
							</div>
						</fieldset>
					</form>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane" id="commandtab">
				<table id="table_cmd" class="table table-bordered table-condensed">
					<thead>
						<tr>
							<th>{{Nom}}</th>
							<th>{{Type}}</th>
							<th>{{LogicalId}}</th>
							<th>{{Unité}}</th>
							<th>{{Paramètres}}</th>
							<th></th>
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
