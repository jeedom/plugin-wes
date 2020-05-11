<?php
if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
sendVarToJS('eqType', 'wes');
$eqLogics = eqLogic::byType('wes');
?>

<div class="row row-overflow">
    <div class="col-lg-3">
        <div class="bs-sidebar">
            <ul id="ul_eqLogic" class="nav nav-list bs-sidenav">
                <a class="btn btn-default eqLogicAction" style="width : 100%;margin-top : 5px;margin-bottom: 5px;" data-action="add"><i class="fa fa-plus-circle"></i> {{Ajouter une wes}}</a>
                <?php
               foreach ($eqLogics as $eqLogic) {
                    echo '<li>'."\n";
						echo '<i class="fa fa-sitemap cursor eqLogicAction" data-action="hide" data-eqLogic_id="' . $eqLogic->getId() . '"></i>'."\n";
						echo '<a class="cursor li_eqLogic" style="display: inline;" data-eqLogic_id="' . $eqLogic->getId() . '" data-eqLogic_type="wes">' . $eqLogic->getName() . '</a>'."\n";
						echo '<ul id="ul_eqLogic" class="nav nav-list bs-sidenav sub-nav-list" data-eqLogic_id="' . $eqLogic->getId() . '" style="display: none;">'."\n";
							echo '<li>'."\n";
								echo '<i class="fa fa-line-chart cursor eqLogicAction" data-action="hide" data-eqLogic_id="temperature_' . $eqLogic->getId() . '"></i>'."\n";
								echo '<a class="cursor eqLogicAction" data-action="hide" style="display: inline;" data-eqLogic_id="temperature_' . $eqLogic->getId() . '" data-eqLogic_type="wes">{{Entrée analogiques 1-Wire}}</a>'."\n";
								echo '<ul id="ul_eqLogic" class="nav nav-list bs-sidenav sub-nav-list" data-eqLogic_id="temperature_' . $eqLogic->getId() . '" style="display: none;">'."\n";
									$compteurId = 1;
									$SubeqLogic = eqLogic::byLogicalId($eqLogic->getId()."_A".$compteurId, 'wes_temperature');
									while ( is_object($SubeqLogic) ) {
										echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $SubeqLogic->getId() . '" data-eqLogic_type="wes_temperature"><a>' . $SubeqLogic->getName() . '</a></li>'."\n";
										$compteurId ++;
										$SubeqLogic = eqLogic::byLogicalId($eqLogic->getId()."_A".$compteurId, 'wes_temperature');
									}
								echo '</ul>'."\n";
							echo '</li>'."\n";
							echo '<li>'."\n";
								echo '<i class="fa fa-plug cursor eqLogicAction" data-action="hide" data-eqLogic_id="relai_' . $eqLogic->getId() . '"></i>'."\n";
								echo '<a class="cursor eqLogicAction" data-action="hide" style="display: inline;" data-eqLogic_id="relai_' . $eqLogic->getId() . '" data-eqLogic_type="wes">{{Relai}}</a>'."\n";
								echo '<ul id="ul_eqLogic" class="nav nav-list bs-sidenav sub-nav-list" data-eqLogic_id="relai_' . $eqLogic->getId() . '" style="display: none;">'."\n";
									for ($compteurId = 1; $compteurId <= 2; $compteurId++) {
										$SubeqLogic = eqLogic::byLogicalId($eqLogic->getId()."_R".$compteurId, 'wes_relai');
										if ( is_object($SubeqLogic) ) {
											echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $SubeqLogic->getId() . '" data-eqLogic_type="wes_relai"><a>' . $SubeqLogic->getName() . '</a></li>'."\n";
										}
									}
								echo '</ul>'."\n";
							echo '</li>'."\n";
							$SubeqLogic = eqLogic::byLogicalId($eqLogic->getId()."_R101", 'wes_relai');
							if ( is_object($SubeqLogic) ) {
								echo '<li>'."\n";
									echo '<i class="fa fa-plug cursor eqLogicAction" data-action="hide" data-eqLogic_id="relai1w_' . $eqLogic->getId() . '"></i>'."\n";
									echo '<a class="cursor eqLogicAction" data-action="hide" style="display: inline;" data-eqLogic_id="relai1w_' . $eqLogic->getId() . '" data-eqLogic_type="wes">{{Relai 1-Wire}}</a>'."\n";
									echo '<ul id="ul_eqLogic" class="nav nav-list bs-sidenav sub-nav-list" data-eqLogic_id="relai1w_' . $eqLogic->getId() . '" style="display: none;">'."\n";
										for ($compteurId = 1; $compteurId <= 9; $compteurId++) {
											$SubeqLogic = eqLogic::byLogicalId($eqLogic->getId()."_R".$compteurId."01", 'wes_relai');
											if ( is_object($SubeqLogic) ) {
												echo '<li>'."\n";
													echo '<i class="fa fa-plug cursor eqLogicAction" data-action="hide" data-eqLogic_id="relai1w_'.$compteurId.'_' . $eqLogic->getId() . '"></i>'."\n";
													echo '<a class="cursor eqLogicAction" data-action="hide" style="display: inline;" data-eqLogic_id="relai1w_'.$compteurId.'_' . $eqLogic->getId() . '" data-eqLogic_type="wes">{{Carte '.$compteurId.'}}</a>'."\n";
													echo '<ul id="ul_eqLogic" class="nav nav-list bs-sidenav sub-nav-list" data-eqLogic_id="relai1w_'.$compteurId.'_' . $eqLogic->getId() . '" style="display: none;">'."\n";
														for ($souscompteurId = 1; $souscompteurId <= 8; $souscompteurId++) {
															$SubeqLogic = eqLogic::byLogicalId($eqLogic->getId()."_R".$compteurId.sprintf("%02d", $souscompteurId), 'wes_relai');
															if ( is_object($SubeqLogic) ) {
																echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $SubeqLogic->getId() . '" data-eqLogic_type="wes_relai"><a>' . $SubeqLogic->getName() . '</a></li>'."\n";
															}
														}
													echo '</ul>'."\n";
												echo '</li>'."\n";
											}
										}
									echo '</ul>'."\n";
								echo '</li>'."\n";
							}
							echo '<li>'."\n";
								echo '<i class="fa fa-twitch cursor eqLogicAction" data-action="hide" data-eqLogic_id="bouton_' . $eqLogic->getId() . '"></i>'."\n";
								echo '<a class="cursor eqLogicAction" data-action="hide" style="display: inline;" data-eqLogic_id="bouton_' . $eqLogic->getId() . '" data-eqLogic_type="wes">{{Entrée numérique}}</a>'."\n";
								echo '<ul id="ul_eqLogic" class="nav nav-list bs-sidenav sub-nav-list" data-eqLogic_id="bouton_' . $eqLogic->getId() . '" style="display: none;">'."\n";
									$compteurId = 1;
									$SubeqLogic = eqLogic::byLogicalId($eqLogic->getId()."_B".$compteurId, 'wes_bouton');
									while ( is_object($SubeqLogic) ) {
										echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $SubeqLogic->getId() . '" data-eqLogic_type="wes_bouton"><a>' . $SubeqLogic->getName() . '</a></li>'."\n";
										$compteurId ++;
										$SubeqLogic = eqLogic::byLogicalId($eqLogic->getId()."_B".$compteurId, 'wes_bouton');
									}
								echo '</ul>'."\n";
							echo '</li>'."\n";
							echo '<li>'."\n";
								echo '<i class="fa fa-calculator cursor eqLogicAction" data-action="hide" data-eqLogic_id="compteur_' . $eqLogic->getId() . '"></i>'."\n";
								echo '<a class="cursor eqLogicAction" data-action="hide" style="display: inline;" data-eqLogic_id="compteur_' . $eqLogic->getId() . '" data-eqLogic_type="wes">{{Compteur}}</a>'."\n";
								echo '<ul id="ul_eqLogic" class="nav nav-list bs-sidenav sub-nav-list" data-eqLogic_id="compteur_' . $eqLogic->getId() . '" style="display: none;">'."\n";
									$compteurId = 1;
									$SubeqLogic = eqLogic::byLogicalId($eqLogic->getId()."_C".$compteurId, 'wes_compteur');
									while ( is_object($SubeqLogic) ) {
										echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $SubeqLogic->getId() . '" data-eqLogic_type="wes_compteur"><a>' . $SubeqLogic->getName() . '</a></li>'."\n";
										$compteurId ++;
										$SubeqLogic = eqLogic::byLogicalId($eqLogic->getId()."_C".$compteurId, 'wes_compteur');
									}
								echo '</ul>'."\n";
							echo '</li>'."\n";
							echo '<li>'."\n";
								echo '<i class="icon nourriture-apron cursor eqLogicAction" data-action="hide" data-eqLogic_id="pince_' . $eqLogic->getId() . '"></i>'."\n";
								echo '<a class="cursor eqLogicAction" data-action="hide" style="display: inline;" data-eqLogic_id="pince_' . $eqLogic->getId() . '" data-eqLogic_type="wes">{{Pince ampèremétrique}}</a>'."\n";
								echo '<ul id="ul_eqLogic" class="nav nav-list bs-sidenav sub-nav-list" data-eqLogic_id="pince_' . $eqLogic->getId() . '" style="display: none;">'."\n";
									$compteurId = 1;
									$SubeqLogic = eqLogic::byLogicalId($eqLogic->getId()."_P".$compteurId, 'wes_pince');
									while ( is_object($SubeqLogic) ) {
										echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $SubeqLogic->getId() . '" data-eqLogic_type="wes_pince"><a>' . $SubeqLogic->getName() . '</a></li>'."\n";
										$compteurId ++;
										$SubeqLogic = eqLogic::byLogicalId($eqLogic->getId()."_P".$compteurId, 'wes_pince');
									}
								echo '</ul>'."\n";
							echo '</li>'."\n";
							echo '<li>';
								echo '<i class="fa fa-bolt cursor eqLogicAction" data-action="hide" data-eqLogic_id="teleinfo_' . $eqLogic->getId() . '"></i>';
								echo '<a class="cursor eqLogicAction" data-action="hide" style="display: inline;" data-eqLogic_id="teleinfo_' . $eqLogic->getId() . '" data-eqLogic_type="wes">{{Téléinfo}}</a>';
								echo '<ul id="ul_eqLogic" class="nav nav-list bs-sidenav sub-nav-list" data-eqLogic_id="teleinfo_' . $eqLogic->getId() . '" style="display: none;">';
									$compteurId = 1;
									$SubeqLogic = eqLogic::byLogicalId($eqLogic->getId()."_T".$compteurId, 'wes_teleinfo');
									while ( is_object($SubeqLogic) ) {
										echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $SubeqLogic->getId() . '" data-eqLogic_type="wes_teleinfo"><a>' . $SubeqLogic->getName() . '</a></li>';
										$compteurId ++;
										$SubeqLogic = eqLogic::byLogicalId($eqLogic->getId()."_T".$compteurId, 'wes_teleinfo');
									}
								echo '</ul>';
							echo '</li>';
							echo '<li>';
								echo '<i class="fa fa-bolt cursor eqLogicAction" data-action="hide" data-eqLogic_id="analogique_' . $eqLogic->getId() . '"></i>';
								echo '<a class="cursor eqLogicAction" data-action="hide" style="display: inline;" data-eqLogic_id="analogique_' . $eqLogic->getId() . '" data-eqLogic_type="wes">{{Analogique}}</a>';
								echo '<ul id="ul_eqLogic" class="nav nav-list bs-sidenav sub-nav-list" data-eqLogic_id="analogique_' . $eqLogic->getId() . '" style="display: none;">';
									$compteurId = 1;
									$SubeqLogic = eqLogic::byLogicalId($eqLogic->getId()."_N".$compteurId, 'wes_analogique');
									while ( is_object($SubeqLogic) ) {
										echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $SubeqLogic->getId() . '" data-eqLogic_type="wes_analogique"><a>' . $SubeqLogic->getName() . '</a></li>';
										$compteurId ++;
										$SubeqLogic = eqLogic::byLogicalId($eqLogic->getId()."_N".$compteurId, 'wes_analogique');
									}
								echo '</ul>';
							echo '</li>';
						echo '</ul>'."\n";
					echo '</li>'."\n";
                }
                ?>
            </ul>
        </div>
    </div>
    <div class="col-lg-9 col-md-9 col-sm-8 eqLogicThumbnailDisplay" style="border-left: solid 1px #EEE; padding-left: 25px;">
	  <legend><i class="fa fa-cog"></i>  {{Gestion}}</legend>
	   <div class="eqLogicThumbnailContainer">
		<div class="cursor eqLogicAction" data-action="add" style="background-color : #ffffff; height : 120px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >
		 <center>
		  <i class="fa fa-plus-circle" style="font-size : 6em;color:#94ca02;"></i>
		</center>
		<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#94ca02"><center>Ajouter</center></span>
	  </div>
	  <div class="cursor eqLogicAction" data-action="gotoPluginConf" style="background-color : #ffffff; height : 120px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;">
		<center>
		  <i class="fa fa-wrench" style="font-size : 6em;color:#767676;"></i>
		</center>
		<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#767676"><center>{{Configuration}}</center></span>
	  </div>
	</div>
        <legend>{{Mes wes}}
        </legend>
		<div class="eqLogicThumbnailContainer">
			  <div class="cursor eqLogicAction" data-action="add" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >
				 <center>
					<i class="fa fa-plus-circle" style="font-size : 7em;color:#94ca02;"></i>
				</center>
				<span style="font-size : 1.1em;position:relative; top : 23px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#94ca02"><center>Ajouter</center></span>
			</div>
			<?php
			if (count($eqLogics) == 0) {
				echo "<br/><br/><br/><center><span style='color:#767676;font-size:1.2em;font-weight: bold;'>{{Vous n'avez pas encore de Wes, cliquez sur Ajouter un équipement pour commencer}}</span></center>";
			} else {
                foreach ($eqLogics as $eqLogic) {
                    echo '<div class="eqLogicDisplayCard cursor" data-eqLogic_id="' . $eqLogic->getId() . '" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >';
                    echo "<center>";
                    echo '<img src="plugins/wes/doc/images/wes_icon.png" height="105" width="95" />';
                    echo "</center>";
                    echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>' . $eqLogic->getHumanName(true, true) . '</center></span>';
                    echo '</div>';
                }
			} ?>
		</div>
    </div>

    <div class="col-lg-9 eqLogic wes" style="border-left: solid 1px #EEE; padding-left: 25px;display: none;">
        <form class="form-horizontal">
            <fieldset>
                <legend>
                   <i class="fa fa-arrow-circle-left eqLogicAction cursor" data-action="returnToThumbnailDisplay"></i> {{Général}}
				   <i class='fa fa-cogs eqLogicAction pull-right cursor expertModeVisible' data-action='configure'></i>
                </legend>
                <div class="form-group">
                    <label class="col-lg-2 control-label">{{Nom de la Wes}}</label>
                    <div class="col-lg-3">
                        <input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
                        <input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de la Wes}}"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label" >{{Objet parent}}</label>
                    <div class="col-lg-3">
                        <select class="form-control eqLogicAttr" data-l1key="object_id">
                            <option value="">{{Aucun}}</option>
                            <?php
                            foreach (object::all() as $object) {
                                echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>'."\n";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">{{Catégorie}}</label>
                    <div class="col-lg-8">
                        <?php
                        foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
                            echo '<label class="checkbox-inline">'."\n";
                            echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" />' . $value['name'];
                            echo '</label>'."\n";
                        }
                        ?>

                    </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" ></label>
					<div class="col-sm-10">
					<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-label-text="{{Activer}}" data-l1key="isEnable" checked/>Activer</label>
					<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-label-text="{{Visible}}" data-l1key="isVisible" checked/>Visible</label>
					<a class="btn btn-default" id="bt_configPush" title='{{Configurer push}}'><i class="fa fa-wrench"></i></a>
					<a class="btn btn-default" id="bt_goCarte" title='{{Accéder à la carte}}'><i class="fa fa-cogs"></i></a>
					</div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">{{IP de la Wes}}</label>
                    <div class="col-lg-3">
                        <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="ip"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">{{Port de la Wes}}</label>
                    <div class="col-lg-3">
                        <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="port"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">{{Compte de la Wes}}</label>
                    <div class="col-lg-3">
                        <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="username"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">{{Password de la Wes}}</label>
                    <div class="col-lg-3">
                        <input type="password" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="password"/>
                    </div>
                </div>
            </fieldset> 
        </form>

        <legend>{{Indicateurs}}</legend>
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

        <form class="form-horizontal">
            <fieldset>
                <div class="form-actions">
                    <a class="btn btn-danger eqLogicAction" data-action="remove"><i class="fa fa-minus-circle"></i> {{Supprimer}}</a>
                    <a class="btn btn-success eqLogicAction" data-action="save"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
                </div>
            </fieldset>
        </form>

    </div>
	<?php include_file('desktop', 'wes_temperature', 'php', 'wes'); ?>
	<?php include_file('desktop', 'wes_bouton', 'php', 'wes'); ?>
	<?php include_file('desktop', 'wes_relai', 'php', 'wes'); ?>
	<?php include_file('desktop', 'wes_compteur', 'php', 'wes'); ?>
	<?php include_file('desktop', 'wes_pince', 'php', 'wes'); ?>
	<?php include_file('desktop', 'wes_teleinfo', 'php', 'wes'); ?>
	<?php include_file('desktop', 'wes_analogique', 'php', 'wes'); ?>
</div>

<?php
include_file('core', 'plugin.template', 'js');
include_file('desktop', 'wes', 'js', 'wes');
?>
<script type="text/javascript">
if (getUrlVars('saveSuccessFull') == 1) {
    $('#div_alert').showAlert({message: '{{Sauvegarde effectuée avec succès}}<br>{{Utilisez sur l\'icône suivant pour voir le détail de l\'élément <i class="fa fa-sitemap"></i>}}', level: 'success'});
}
</script>