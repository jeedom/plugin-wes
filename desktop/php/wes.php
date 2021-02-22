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
		<legend><i class="fas fa-digital-tachograph"></i> {{Mes serveurs Wes}}</legend>
<?php
if (count($eqLogics) == 0) {
	echo "<br/><br/><div class=\"text-center\" style='color:#767676;font-size:1.2em;font-weight: bold;'>{{Aucun serveur Wes n'est paramétré, cliquer sur \"Ajouter\" pour commencer}}</div>";
} else {
	echo '<div class="input-group" style="margin-bottom:5px;">';
	echo '<input class="form-control roundedLeft" placeholder="{{Rechercher}}" id="in_searchWes"/>';
	echo '<div class="input-group-btn">';
	echo '<a id="bt_resetWesSearch" class="btn" style="width:30px"><i class="fas fa-times"></i> </a>';
	echo '</div>';
	echo '<div class="input-group-btn">';
	echo '<a class="btn" id="bt_openAll"><i class="fas fa-folder-open"></i></a>';
	echo '</div>';
	echo '<div class="input-group-btn">';
	echo '<a class="btn roundedRight" id="bt_closeAll"><i class="fas fa-folder"></i></a>';
	echo '</div>';
	echo '</div>';
	echo '<div class="panel-group" id="accordionWes">';
	$img = $plugin->getPathImgIcon();
	$generalEqLogics = array();
	$childEqLogics = array();
	foreach ($eqLogics as $eqLogic) {
		if ($eqLogic->getConfiguration('type') == 'general') {
			array_push($generalEqLogics, $eqLogic);
		}
		else {
			$generalId = explode('_', $eqLogic->getLogicalId());
			$childEqLogics[$generalId[0]][$eqLogic->getConfiguration('type')][] = $eqLogic;
		}
	}

	foreach ($generalEqLogics as $generalEqLogic) {
		echo '<div style="width:100%;display:flex;">';
		echo '<div class="eqLogicThumbnailContainer" style="width:130px;">';
		$opacity = ($generalEqLogic->getIsEnable()) ? '' : 'disableCard';
		echo '<div class="eqLogicDisplayCard cursor '.$opacity.'" data-eqLogic_id="' . $generalEqLogic->getId() . '">';
		if (file_exists(dirname(__FILE__) . '/../../core/config/general.png')) {
			$img = 'plugins/wes/core/config/general.png';
		}
		echo '<img src="' . $img . '"/>';
		echo '<span class="name">' . $generalEqLogic->getHumanName(true, true) . '</span>';
		echo '</div>';
		echo '</div>';

		echo '<div class="col-sm-12" style="margin-bottom:20px;">';
		foreach ($childEqLogics[$generalEqLogic->getId()] as $type => $childEqLogic) {
			echo '<div class="panel panel-default" style="margin-bottom:0!important;">';
			// echo '<div class="panel-heading">';
			echo '<div class="panel-title">';
			echo '<a class="accordion-toggle wesTab" data-toggle="collapse" data-parent="" aria-expanded="false" href="#wes_'.$type.$generalEqLogic->getId().'">';
			if (file_exists(dirname(__FILE__) . '/../../core/config/'.$type.'.png')) {
				$img = 'plugins/wes/core/config/'.$type.'.png';
			}
			echo '<img src="'.$img.'" width="30px"/> ' . $typeArray[$type]['name'];
			echo '</div>';
			// echo '</div>';
			echo '<div id="wes_'.$type.$generalEqLogic->getId().'" class="panel-collapse collapse">';
			echo '<div class="panel-body">';
			echo '<div class="eqLogicThumbnailContainer packery">';
			foreach ($childEqLogic as $eqLogic) {
				$opacity = ($eqLogic->getIsEnable()) ? '' : 'disableCard';
				echo '<div class="eqLogicDisplayCard cursor '.$opacity.'" data-eqLogic_id="' . $eqLogic->getId() . '">';
				if (file_exists(dirname(__FILE__) . '/../../core/config/'.$type.'.png')) {
					$img = 'plugins/wes/core/config/'.$type.'.png';
				}
				echo '<img src="' . $img . '"/>';
				echo '<span class="name">' . $eqLogic->getHumanName(true, true) . '</span>';
				echo '</div>';
			}
			echo '</div>';
			echo '</div>';
			echo '</div>';
			echo '</div>';
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
			<a class="btn btn-default btn-sm eqLogicAction roundedLeft" data-action="configure"><i class="fa fa-cogs"></i> {{Configuration avancée}}
			</a><a class="btn btn-primary btn-sm eqLogicAction showgeneral" id="bt_goCarte"><i class="far fa-window-restore"></i> {{Interface Wes}}
			</a><a class="btn btn-info btn-sm eqLogicAction showgeneral" id="bt_configPush"><i class="fa fa-wrench"></i> {{Configuration Push}}
			</a><a class="btn btn-default btn-sm eqLogicAction" data-action="copy"><i class="fas fa-copy"></i> {{Dupliquer}}
			</a><a class="btn btn-sm btn-success eqLogicAction" data-action="save"><i class="fas fa-check-circle"></i> {{Sauvegarder}}
			</a><a class="btn btn-danger btn-sm eqLogicAction roundedRight" data-action="remove"><i class="fas fa-minus-circle"></i> {{Supprimer}}</a>
		</span>
	</div>
	<ul class="nav nav-tabs" role="tablist">
		<li role="presentation"><a href="#" class="eqLogicAction" aria-controls="home" role="tab" data-toggle="tab" data-action="returnToThumbnailDisplay"><i class="fas fa-arrow-circle-left"></i></a></li>
		<li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-tachometer-alt"></i><span class="hidden-xs"> {{Équipement}}</span></a></li>
		<li role="presentation"><a href="#commandtab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-list"></i><span class="hidden-xs"> {{Commandes}}</span></a></li>
	</ul>

	<div class="tab-content">
		<div role="tabpanel" class="tab-pane active" id="eqlogictab">
			<form class="form-horizontal">
				<fieldset>
					<div class="col-lg-6">
						<legend><i class="fas fa-wrench"></i> {{Général}}</legend>
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
							<div class="col-sm-7">
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
							<label class="col-sm-3 control-label">{{Options}}</label>
							<div class="col-sm-7">
								<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-label-text="{{Activer}}" data-l1key="isEnable" checked/>Activer</label>
								<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-label-text="{{Visible}}" data-l1key="isVisible" checked/>Visible</label>
							</div>
						</div>
						<br>

						<legend class="showgeneral" style="display: none;"><i class="fas fa-cogs"></i> {{Paramètres}}</legend>
						<div class="form-group showgeneral" style="display: none;" data-cmd_id="ipAddress">
							<label class="col-sm-3 control-label">{{IP du Wes}}
								<sup><i class="fas fa-question-circle tooltips" title="{{Adresse ip sur laquelle le serveur Wes est joignable}}"></i></sup>
							</label>
							<div class="col-sm-7">
								<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="ip" data-cmd_id="ipAddress"/>
							</div>
						</div>
						<div class="form-group showgeneral" style="display: none;" data-cmd_id="port">
							<label class="col-sm-3 control-label">{{Port du Wes}}
								<sup><i class="fas fa-question-circle tooltips" title="{{Port de communication sur lequel le serveur Wes est joignable. 80 par défaut}}"></i></sup>
							</label>
							<div class="col-sm-7">
								<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="port" data-cmd_id="port"/>
							</div>
						</div>
						<div class="form-group showgeneral" style="display: none;" data-cmd_id="username">
							<label class="col-sm-3 control-label">{{Identifiant HTTP}}
								<sup><i class="fas fa-question-circle tooltips" title="{{Renseigner l'identifiant du compte pour l'accès HTTP. Permet de communiquer avec le Wes}}"></i></sup>
							</label>
							<div class="col-sm-7">
								<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="username" data-cmd_id="username"/>
							</div>
						</div>
						<div class="form-group showgeneral" style="display: none;" data-cmd_id="password">
							<label class="col-sm-3 control-label">{{Mot de passe HTTP}}
								<sup><i class="fas fa-question-circle tooltips" title="{{Renseigner le mot de passe du compte pour l'accès HTTP. Permet de communiquer avec le Wes}}"></i></sup>
							</label>
							<div class="col-sm-7">
								<input type="password" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="password" data-cmd_id="password"/>
							</div>
						</div>
						<div class="form-group showgeneral" style="display: none;" data-cmd_id="ftpusername">
							<label class="col-sm-3 control-label">{{Identifiant FTP}}
								<sup><i class="fas fa-question-circle tooltips" title="{{Renseigner l'identifiant du compte pour l'accès FTP. Permet l'envoi du fichier CGX sur le Wes}}"></i></sup>
							</label>
							<div class="col-sm-7">
								<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="ftpusername" data-cmd_id="ftpusername"/>
							</div>
						</div>
						<div class="form-group showgeneral" style="display: none;" data-cmd_id="ftppassword">
							<label class="col-sm-3 control-label">{{Mot de passe FTP}}
								<sup><i class="fas fa-question-circle tooltips" title="{{Renseigner le mot de passe du compte pour l'accès FTP. Permet l'envoi du fichier CGX sur le Wes}}"></i></sup>
							</label>
							<div class="col-sm-7">
								<input type="password" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="ftppassword" data-cmd_id="ftppassword"/>
							</div>
						</div>
						<div class="form-group showgeneral" style="display: none;" data-cmd_id="usecustomcgx">
							<label class="col-sm-3 control-label" >{{Fichier CGX Jeedom}}
								<sup><i class="fas fa-question-circle tooltips" title="{{Cocher la case pour autoriser l'envoi du fichier CGX spécialement conçu pour le plugin afin de récupérer davantage de données}}"></i></sup>
							</label>
							<div class="col-sm-7">
								<input type="checkbox" class="eqLogicAttr" data-label-text="{{Activer}}" data-l1key="configuration" data-l2key="usecustomcgx"/>
							</div>
						</div>
						<div class="form-group showteleinfo" style="display: none;" data-cmd_id="tarification">
							<label class="col-sm-3 control-label">{{Tarification}}
								<sup><i class="fas fa-question-circle tooltips" title="{{Indiquer la formule de tarification de votre abonnement}}"></i></sup>
							</label>
							<div class="col-sm-7">
								<select class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="tarification"  data-cmd_id="tarification">
									<option value="">Sans</option>
									<option value="BASE">Base</option>
									<option value="HC">Heures creuses</option>
									<option value="BBRH">Tempo</option>
									<option value="EJP">EJP</option>
								</select>
							</div>
						</div>
					</div>

					<div class="col-lg-6 showgeneral" style="display: none;">
						<legend><i class="fas fa-tasks"></i> {{Gestion des équipements}}</legend>
						<div class="alert alert-warning col-xs-10 col-xs-offset-1">
							<i class="fas fa-exclamation-triangle"></i>
							{{Décocher une ou plusieurs cases aura pour conséquence la suppression du ou des équipements correspondants.}}
						</div>
						<?php
						foreach ($typeArray as $key => $value) {
							if (isset($value["maxnumber"])) {
								echo'<div class="form-group">';
								echo '<label class="col-sm-3 control-label">'.$value["name"].'<img width="30px" src="plugins/wes/core/config/'.$key.'.png"/></label>';
								echo '<div class="col-sm-7">';
								foreach (range(1,$value["maxnumber"]) as $number) {
									echo '<label class="checkbox-inline">';
									echo '<input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="' . $key.$number . '"/>' .$value["type"].$number;
									echo '</label>';
								}
								echo '</div>';
								echo '</div>';
							}
						}
						?>
					</div>
				</fieldset>
			</form>
			<hr>
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
