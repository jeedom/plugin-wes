<?php
/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
if (init('id') == '') {
    throw new Exception('{{EqLogic ID ne peut etre vide}}');
}
$eqLogic = eqLogic::byId(init('id'));
if (!is_object($eqLogic)) {
    throw new Exception('{{EqLogic non trouvé}}');
}
?>
<script>
	var eqLogicIdGlobal = <?php echo init('id'); ?>;  
</script>
<fieldset>
<legend>{{Relai}}</legend>
<?php
echo '<ul id="ul_eqLogic" class="nav nav-list bs-sidenav sub-nav-list" data-eqLogic_id="relai_' . $eqLogic->getId() . '">';
	for ($compteurId = 1; $compteurId <= 2; $compteurId++) {
		$SubeqLogic = eqLogic::byLogicalId($eqLogic->getId()."_R".$compteurId, 'wes');
		if ( is_object($SubeqLogic) ) {
			echo '<label class="checkbox-inline">';
			echo '<input type="checkbox" class="configPusheqLogic" data-configPusheqLogic_id="' . $SubeqLogic->getId() . '" checked/>' . $SubeqLogic->getName();
			echo '</label><br>';
		}
	}
echo '</ul>';
?>
</fieldset>
<?php
for ($compteurId = 1; $compteurId <= 9; $compteurId++) {
	$SubeqLogic = eqLogic::byLogicalId($eqLogic->getId()."_R".$compteurId."01", 'wes');
	if ( is_object($SubeqLogic) ) {
		echo "<fieldset>
			<legend>{{Relai 1-Wire ".$compteurId."}}</legend>
			<ul id='ul_eqLogic' class='nav nav-list bs-sidenav sub-nav-list' data-eqLogic_id='relai_" . $eqLogic->getId() . "'>";
			for ($souscompteurId = 1; $souscompteurId <= 8; $souscompteurId++) {
				$SubeqLogic = eqLogic::byLogicalId($eqLogic->getId()."_R".$compteurId.sprintf("%02d", $souscompteurId), 'wes');
				if ( is_object($SubeqLogic) ) {
					echo '<label class="checkbox-inline">';
					echo '<input type="checkbox" class="configPusheqLogic" data-configPusheqLogic_id="' . $SubeqLogic->getId() . '" checked/>' . $SubeqLogic->getName();
					echo '</label><br>';
				}
			}
		echo "</ul>
		</fieldset>\n";
	}
}
?>
<fieldset>
<legend>{{Entrée numérique}}</legend>
<?php
echo '<ul id="ul_eqLogic" class="nav nav-list bs-sidenav sub-nav-list" data-eqLogic_id="bouton_' . $eqLogic->getId() . '">';
	for ($compteurId = 1; $compteurId <= 2; $compteurId++) {
		$SubeqLogic = eqLogic::byLogicalId($eqLogic->getId()."_B".$compteurId, 'wes');
		if ( is_object($SubeqLogic) ) {
			echo '<label class="checkbox-inline">';
			echo '<input type="checkbox" class="configPusheqLogic" data-configPusheqLogic_id="' . $SubeqLogic->getId() . '" checked/>' . $SubeqLogic->getName();
			echo '</label><br>';
		}
	}
echo '</ul>';
?>
</fieldset>
<div class="form-group alert alert-warning">
Attention, le serveur 5 renseigné dans le WES sera remplacé.
Chaque case validée renseignera le Push settings concerné de la Wes et effacera la configuration existante.
</div>
<div id='div_configurePush' style="display: none;"></div>
<a class="btn btn-warning pull-right" id="bt_ApplyconfigPush" style="color : white;"><i class="fa fa-wrench"></i> {{Appliquer}}</a>
<a class="btn btn-success pull-right" id="bt_UnCheckAll" style="color : white;"><i class="fa fa-square-o"></i> {{Tout décocher}}</a>
<a class="btn btn-success pull-right" id="bt_CheckAll" style="color : white;"><i class="fa fa-check-square-o"></i> {{Tout cocher}}</a>
<?php
include_file('desktop', 'wes', 'js', 'wes');
?>