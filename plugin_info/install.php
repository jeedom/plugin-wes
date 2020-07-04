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

require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';

function wes_install() {
	$cron = cron::byClassAndFunction('wes', 'daemon');
	if (!is_object($cron)) {
		$cron = new cron();
		$cron->setClass('wes');
		$cron->setFunction('daemon');
		$cron->setEnable(1);
		$cron->setDeamon(1);
		$cron->setTimeout(1440);
		$cron->setSchedule('* * * * *');
		$cron->save();
	}
	config::save('temporisation_lecture', 60, 'wes');
	$cron->start();
}

function wes_update() {
	$analogiques = eqLogic::byType('wes_analogique');
	$boutons = eqLogic::byType('wes_bouton');
	$compteurs = eqLogic::byType('wes_compteur');
	$pinces = eqLogic::byType('wes_pince');
	$relais = eqLogic::byType('wes_relai');
	$teleinfos = eqLogic::byType('wes_teleinfo');
	$temperatures = eqLogic::byType('wes_temperature');
	$wess = eqLogic::byType('wes');
	
	foreach ($wess as $wes){
		if(is_object($wes)){
			if($wes->getConfiguration('type') == null || $wes->getConfiguration('type') == ""){
				$wes->setConfiguration('type','general');
				$wes->save();
			}
			$wessCmd = cmd::byEqLogicId($wes->getId());
			foreach ($wessCmd as $wesCmd){
				if(is_object($wesCmd)){
					if($wesCmd->getConfiguration('type') == null || $wes->getConfiguration('type') == ""){
						$wesCmd->setConfiguration('type','general');
						$wesCmd->save();
					}
				}
			}
		}
	}
	
	
	foreach ($analogiques as $analogique){
		if(is_object($analogique)){
			$analogique->setEqType_name('wes');
			$analogique->setConfiguration('type','analogique');
			$analogique->save();
			$analogiquesCmd = cmd::byEqLogicId($analogique->getId());
			foreach ($analogiquesCmd as $analogiqueCmd){
				if(is_object($analogiqueCmd)){
					$analogiqueCmd->setEqType('wes');
					$analogiqueCmd->setConfiguration('type','analogique');
					$analogiqueCmd->save();
				}
			}
		}
	}
	
	foreach ($boutons as $bouton){
		if(is_object($bouton)){
			$bouton->setEqType_name('wes');
			$bouton->setConfiguration('type','bouton');
			$bouton->save();
			$boutonsCmd = cmd::byEqLogicId($bouton->getId());
			foreach ($boutonsCmd as $boutonCmd){
				if(is_object($boutonCmd)){
					$boutonCmd->setEqType('wes');
					$boutonCmd->setConfiguration('type','bouton');
					$boutonCmd->save();
				}
			}
		}
	}
	
	foreach ($compteurs as $compteur){
		if(is_object($compteur)){
			$compteur->setEqType_name('wes');
			$compteur->setConfiguration('type','compteur');
			$compteur->save();
			$compteursCmd = cmd::byEqLogicId($compteur->getId());
			foreach ($compteursCmd as $compteurCmd){
				if(is_object($compteurCmd)){
					$compteurCmd->setEqType('wes');
					$compteurCmd->setConfiguration('type','compteur');
					$compteurCmd->save();
				}
			}
		}
	}
	
	foreach ($relais as $relai){
		if(is_object($relai)){
			$relai->setEqType_name('wes');
			$relai->setConfiguration('type','relai');
			$relai->save();
			$relaisCmd = cmd::byEqLogicId($relai->getId());
			foreach ($relaisCmd as $relaiCmd){
				if(is_object($relaiCmd)){
					$relaiCmd->setEqType('wes');
					$relaiCmd->setConfiguration('type','relai');
					$relaiCmd->save();
				}
			}
		}
	}
	
	foreach ($pinces as $pince){
		if(is_object($pince)){
			$pince->setEqType_name('wes');
			$pince->setConfiguration('type','pince');
			$pince->save();
			$pincesCmd = cmd::byEqLogicId($pince->getId());
			foreach ($pincesCmd as $pinceCmd){
				if(is_object($pinceCmd)){
					$pinceCmd->setEqType('wes');
					$pinceCmd->setConfiguration('type','pince');
					$pinceCmd->save();
				}
			}
		}
	}
	
	foreach ($teleinfos as $teleinfo){
		if(is_object($teleinfo)){
			$teleinfo->setEqType_name('wes');
			$teleinfo->setConfiguration('type','teleinfo');
			$teleinfo->save();
			$teleinfosCmd = cmd::byEqLogicId($teleinfo->getId());
			foreach ($teleinfosCmd as $teleinfoCmd){
				if(is_object($teleinfoCmd)){
					$teleinfoCmd->setEqType('wes');
					$teleinfoCmd->setConfiguration('type','teleinfo');
					$teleinfoCmd->save();
				}
			}
		}
	}
	
	foreach ($temperatures as $temperature){
		if(is_object($temperature)){
			$temperature->setEqType_name('wes');
			$temperature->setConfiguration('type','temperature');
			$temperature->save();
			$temperaturesCmd = cmd::byEqLogicId($temperature->getId());
			foreach ($temperaturesCmd as $temperatureCmd){
				if(is_object($temperatureCmd)){
					$temperatureCmd->setEqType('wes');
					$temperatureCmd->setConfiguration('type','temperature');
					$temperatureCmd->save();
				}
			}
		}
	}
	
    $cron = cron::byClassAndFunction('wes', 'pull');
	if (is_object($cron)) {
		$cron->stop();
		$cron->remove();
	}
    $cron = cron::byClassAndFunction('wes', 'cron');
	if (is_object($cron)) {
		$cron->stop();
		$cron->remove();
	}
	$daemon = cron::byClassAndFunction('wes', 'daemon');
	if (!is_object($daemon)) {
		$daemon = new cron();
		$daemon->setClass('wes');
		$daemon->setFunction('daemon');
		$daemon->setEnable(1);
		$daemon->setDeamon(1);
		$daemon->setTimeout(1440);
		$daemon->setSchedule('* * * * *');
		$daemon->save();
		$daemon->start();
	}
	else
	{
		wes::deamon_start();
	}
	config::save('temporisation_lecture', 60, 'wes');
	foreach (eqLogic::byType('wes') as $eqLogic) {
		$eqLogic->save();
	}
}

function wes_remove() {
    $cron = cron::byClassAndFunction('wes', 'daemon');
    if (is_object($cron)) {
        $cron->remove();
    }
}
?>
