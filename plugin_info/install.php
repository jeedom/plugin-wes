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
		if(isObject($wes)){
			$wes->setEqType_name('wes');
			if($wes-getConfiguration('type') == null || $wes-getConfiguration('type') == ""){
				$wes->setConfiguration('type','general');
			}
			$wes->save();
		}
	}
	
	
	foreach ($analogiques as $analogique){
		if(isObject($analogique)){
			$analogique->setEqType_name('wes');
			$analogique->setConfiguration('type','analogique');
			$analogique->save();
		}
	}
	
	foreach ($boutons as $bouton){
		if(isObject($bouton)){
			$bouton->setEqType_name('wes');
			$bouton->setConfiguration('type','bouton');
			$bouton->save();
		}
	}
	
	foreach ($compteurs as $compteur){
		if(isObject($compteur)){
			$compteur->setEqType_name('wes');
			$compteur->setConfiguration('type','compteur');
			$compteur->save();
		}
	}
	
	foreach ($relais as $relai){
		if(isObject($relai)){
			$relai->setEqType_name('wes');
			$relai->setConfiguration('type','relai');
			$relai->save();
		}
	}
	
	foreach ($pinces as $pince){
		if(isObject($pince)){
			$pince->setEqType_name('wes');
			$pince->setConfiguration('type','pince');
			$pince->save();
		}
	}
	
	foreach ($teleinfos as $teleinfo){
		if(isObject($teleinfo)){
			$teleinfo->setEqType_name('wes');
			$teleinfo->setConfiguration('type','teleinfo');
			$teleinfo->save();
		}
	}
	
	foreach ($temperatures as $temperature){
		if(isObject($temperature)){
			$temperature->setEqType_name('wes');
			$temperature->setConfiguration('type','temperature');
			$temperature->save();
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
