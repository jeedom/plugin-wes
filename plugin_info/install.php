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
	config::save('subClass', 'wes_temperature;wes_relai;wes_bouton;wes_compteur;wes_teleinfo;wes_pince;wes_analogique', 'wes');
}

function wes_update() {
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
	foreach (eqLogic::byType('wes_bouton') as $SubeqLogic) {
		$SubeqLogic->save();
	}
	foreach (eqLogic::byType('wes_temperature') as $SubeqLogic) {
		$SubeqLogic->save();
	}
	foreach (eqLogic::byType('wes_relai') as $SubeqLogic) {
		$SubeqLogic->save();
	}
	foreach (eqLogic::byType('wes_compteur') as $SubeqLogic) {
		$SubeqLogic->save();
	}
	foreach (eqLogic::byType('wes') as $eqLogic) {
		$eqLogic->save();
	}
	config::save('subClass', 'wes_temperature;wes_relai;wes_bouton;wes_compteur;wes_teleinfo;wes_pince;wes_analogique', 'wes');
}

function wes_remove() {
    $cron = cron::byClassAndFunction('wes', 'daemon');
    if (is_object($cron)) {
        $cron->remove();
    }
	config::remove('subClass', 'wes');
}
?>