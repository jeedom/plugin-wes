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

/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';

include_file('core', 'wes_temperature', 'class', 'wes');
include_file('core', 'wes_relai', 'class', 'wes');
include_file('core', 'wes_bouton', 'class', 'wes');
include_file('core', 'wes_compteur', 'class', 'wes');
include_file('core', 'wes_teleinfo', 'class', 'wes');
include_file('core', 'wes_pince', 'class', 'wes');
include_file('core', 'wes_analogique', 'class', 'wes');

class wes extends eqLogic {
    /*     * *************************Attributs****************************** */

    /*     * ***********************Methode static*************************** */

	public static function daemon() {
		$starttime = microtime (true);
		log::add('wes','debug','cron start');
		foreach (self::byType('wes') as $eqLogic) {
			$eqLogic->pull();
		}
		log::add('wes','debug','cron stop');
		$endtime = microtime (true);
		if ( $endtime - $starttime < config::byKey('temporisation_lecture', 'wes', 60, true) )
		{
			usleep(floor((config::byKey('temporisation_lecture', 'wes') + $starttime - $endtime)*1000000));
		}
	}

	public static function deamon_info() {
		$return = array();
		$return['log'] = '';
		$return['state'] = 'nok';
		$cron = cron::byClassAndFunction('wes', 'daemon');
		if (is_object($cron) && $cron->running()) {
			$return['state'] = 'ok';
		}
		$return['launchable'] = 'ok';
		return $return;
	}

	public static function deamon_start($_debug = false) {
		self::deamon_stop();
		$deamon_info = self::deamon_info();
		if ($deamon_info['launchable'] != 'ok') {
			throw new Exception(__('Veuillez vérifier la configuration', __FILE__));
		}
		$cron = cron::byClassAndFunction('wes', 'daemon');
		if (!is_object($cron)) {
			throw new Exception(__('Tâche cron introuvable', __FILE__));
		}
		$cron->run();
	}

	public static function deamon_stop() {
		$cron = cron::byClassAndFunction('wes', 'daemon');
		if (!is_object($cron)) {
			throw new Exception(__('Tâche cron introuvable', __FILE__));
		}
		$cron->halt();
	}

	public static function deamon_changeAutoMode($_mode) {
		$cron = cron::byClassAndFunction('wes', 'daemon');
		if (!is_object($cron)) {
			throw new Exception(__('Tâche cron introuvable', __FILE__));
		}
		$cron->setEnable($_mode);
		$cron->save();
	}

	public function getUrl($file, $postarg = "") {
		$url = 'http://';
		$url .= $this->getConfiguration('ip');
		if ( $this->getConfiguration('port') != '' )
		{
			$url .= ':'.$this->getConfiguration('port');
		}
		$process = curl_init();
		curl_setopt($process, CURLOPT_URL, $url.'/'.$file);
		curl_setopt($process, CURLOPT_USERPWD, $this->getConfiguration('username') . ":" . $this->getConfiguration('password'));
		curl_setopt($process, CURLOPT_TIMEOUT, 30);
		curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
		log::add('wes','debug','Url '.$url.'/'.$file);
		if ( $postarg != "" ) {
			log::add('wes','debug','Post '.$postarg);
			curl_setopt($process, CURLOPT_POST, 1);
			curl_setopt($process, CURLOPT_POSTFIELDS, $postarg);
		}
		$return = curl_exec($process);
		curl_close($process);
		if ( $return === false )
			throw new Exception(__('Le wes ne repond pas.',__FILE__));
		usleep (50);
		return $return;
	}

	public function preUpdate()
	{
		if ( $this->getIsEnable() )
		{
			log::add('wes','debug','get data.cgx');
			$this->xmlstatus = simplexml_load_string($this->getUrl('data.cgx'));
			if ( $this->xmlstatus === false )
				throw new Exception(__('Le wes ne repond pas.',__FILE__));
		}
	}

	public function preInsert()
	{
		$this->setIsVisible(0);
	}

	public function postInsert()
	{
		$cmd = $this->getCmd(null, 'status');
		if ( ! is_object($cmd) ) {
			$cmd = new wesCmd();
			$cmd->setName('Etat');
			$cmd->setEqLogic_id($this->getId());
			$cmd->setType('info');
			$cmd->setSubType('binary');
			$cmd->setLogicalId('status');
			$cmd->setIsVisible(1);
			$cmd->setEventOnly(1);
			$cmd->save();
		}
	}

	public function postUpdate()
	{
		$this->xmlstatus = simplexml_load_string($this->getUrl('data.cgx'));
		$compteurId = 1;
		$status = $this->xmlstatus->xpath('//temp/SONDE'.$compteurId);
		while ( count($status) != 0 ) {
			if ( ! is_object(self::byLogicalId($this->getId()."_A".$compteurId, 'wes_temperature')) ) {
				log::add('wes','debug','Creation temperature : '.$this->getId().'_A'.$compteurId);
				$eqLogic = new wes_temperature();
				$eqLogic->setLogicalId($this->getId().'_A'.$compteurId);
				$eqLogic->setName('Temperature ' . $compteurId);
				$eqLogic->save();
			}
			$compteurId ++;
			$status = $this->xmlstatus->xpath('//temp/SONDE'.$compteurId);
		}
		$this->xmlstatus = simplexml_load_string($this->getUrl('data.cgx'));
		for ($compteurId = 1; $compteurId <= 9; $compteurId++) {
			$status = $this->xmlstatus->xpath('//relais1W/RELAIS'.$compteurId."01");
			if ( count($status) != 0 ) {
				for ($souscompteurId = 1; $souscompteurId <= 8; $souscompteurId++) {
					if ( ! is_object(self::byLogicalId($this->getId()."_R".$compteurId.sprintf("%02d", $souscompteurId), 'wes_relai')) ) {
						log::add('wes','debug','Creation relai : '.$this->getId().'_R'.$compteurId.sprintf("%02d", $souscompteurId));
						$eqLogic = new wes_relai();
						$eqLogic->setLogicalId($this->getId().'_R'.$compteurId.sprintf("%02d", $souscompteurId));
						$eqLogic->setName('Relai ' . $compteurId.sprintf("%02d", $souscompteurId));
						$eqLogic->save();
					}
				}
			}
			else {
				for ($souscompteurId = 1; $souscompteurId <= 8; $souscompteurId++) {
					$eqLogic = self::byLogicalId($this->getId()."_R".$compteurId.sprintf("%02d", $souscompteurId), 'wes_relai');
					if ( is_object($eqLogic) ) {
						log::add('wes','debug','Suppression relai : '.$this->getId().'_R'.$compteurId.sprintf("%02d", $souscompteurId));
						$eqLogic->remove();
					}
				}
			}
		}

		for ($compteurId = 1; $compteurId <= 2; $compteurId++) {
			if ( ! is_object(self::byLogicalId($this->getId()."_R".$compteurId, 'wes_relai')) ) {
				log::add('wes','debug','Creation relai : '.$this->getId().'_R'.$compteurId);
				$eqLogic = new wes_relai();
				$eqLogic->setLogicalId($this->getId().'_R'.$compteurId);
				$eqLogic->setName('Relai ' . $compteurId);
				$eqLogic->save();
			}
		}
		for ($compteurId = 1; $compteurId <= 2; $compteurId++) {
			if ( ! is_object(self::byLogicalId($this->getId()."_B".$compteurId, 'wes_bouton')) ) {
				log::add('wes','debug','Creation bouton : '.$this->getId().'_B'.$compteurId);
				$eqLogic = new wes_bouton();
				$eqLogic->setLogicalId($this->getId().'_B'.$compteurId);
				$eqLogic->setName('Bouton ' . $compteurId);
				$eqLogic->save();
			}
		}
		$compteurId = 1;
		$status = $this->xmlstatus->xpath('//impulsion/INDEX'.$compteurId);
		while ( count($status) != 0 ) {
			if ( ! is_object(self::byLogicalId($this->getId()."_C".$compteurId, 'wes_compteur')) ) {
				log::add('wes','debug','Creation compteur : '.$this->getId().'_C'.$compteurId);
				$eqLogic = new wes_compteur();
				$eqLogic->setLogicalId($this->getId().'_C'.$compteurId);
				$eqLogic->setName('Compteur ' . $compteurId);
				$eqLogic->save();
			}
			$compteurId ++;
			$status = $this->xmlstatus->xpath('//impulsion/INDEX'.$compteurId);
		}

		for ($compteurId = 1; $compteurId <= 2; $compteurId++) {
			if ( ! is_object(self::byLogicalId($this->getId()."_T".$compteurId, 'wes_teleinfo')) ) {
				log::add('wes','debug','Creation teleinfo : '.$this->getId().'_T'.$compteurId);
				$eqLogic = new wes_teleinfo();
				$eqLogic->setLogicalId($this->getId().'_T'.$compteurId);
				$eqLogic->setName('Teleinfo ' . $compteurId);
				$eqLogic->save();
			}
		}

		$compteurId = 1;
		$status = $this->xmlstatus->xpath('//pince/I'.$compteurId);
		while ( count($status) != 0 ) {
			if ( ! is_object(self::byLogicalId($this->getId()."_P".$compteurId, 'wes_pince')) ) {
				log::add('wes','debug','Creation pince : '.$this->getId().'_P'.$compteurId);
				$eqLogic = new wes_pince();
				$eqLogic->setLogicalId($this->getId().'_P'.$compteurId);
				$eqLogic->setName('Pince ' . $compteurId);
				$eqLogic->save();
			}
			$compteurId ++;
			$status = $this->xmlstatus->xpath('//pince/I'.$compteurId);
		}

		$compteurId = 1;
		$status = $this->xmlstatus->xpath('//analogique/AD'.$compteurId);
		while ( count($status) != 0 ) {
			if ( ! is_object(self::byLogicalId($this->getId()."_N".$compteurId, 'wes_analogique')) ) {
				log::add('wes','debug','Creation Analogique : '.$this->getId().'_N'.$compteurId);
				$eqLogic = new wes_analogique();
				$eqLogic->setLogicalId($this->getId().'_N'.$compteurId);
				$eqLogic->setName('Analogique ' . $compteurId);
				$eqLogic->save();
			}
			$compteurId ++;
			$status = $this->xmlstatus->xpath('//analogique/AD'.$compteurId);
		}

		$cmd = $this->getCmd(null, 'status');
		if ( ! is_object($cmd) ) {
			$cmd = new wesCmd();
			$cmd->setName('Etat');
			$cmd->setEqLogic_id($this->getId());
			$cmd->setType('info');
			$cmd->setSubType('binary');
			$cmd->setLogicalId('status');
			$cmd->setIsVisible(1);
			$cmd->setEventOnly(1);
			$cmd->save();
		}
/*        $reboot = $this->getCmd(null, 'reboot');
        if ( ! is_object($reboot) ) {
            $reboot = new wesCmd();
			$reboot->setName('Reboot');
			$reboot->setEqLogic_id($this->getId());
			$reboot->setType('action');
			$reboot->setSubType('other');
			$reboot->setLogicalId('reboot');
			$reboot->setIsVisible(0);
			$reboot->setEventOnly(1);
			$reboot->save();
		}
*/	}

	public function preRemove()
	{
		foreach (self::byType('wes_compteur') as $eqLogic) {
			if ( substr($eqLogic->getLogicalId(), 0, strpos($eqLogic->getLogicalId(),"_")) == $this->getId() ) {
				log::add('wes','debug','Suppression compteur : '.$eqLogic->getName());
				$eqLogic->remove();
			}
		}
		foreach (self::byType('wes_temperature') as $eqLogic) {
			if ( substr($eqLogic->getLogicalId(), 0, strpos($eqLogic->getLogicalId(),"_")) == $this->getId() ) {
				log::add('wes','debug','Suppression temperature : '.$eqLogic->getName());
				$eqLogic->remove();
			}
		}
		foreach (self::byType('wes_relai') as $eqLogic) {
			if ( substr($eqLogic->getLogicalId(), 0, strpos($eqLogic->getLogicalId(),"_")) == $this->getId() ) {
				log::add('wes','debug','Suppression relai : '.$eqLogic->getName());
				$eqLogic->remove();
			}
		}
		foreach (self::byType('wes_bouton') as $eqLogic) {
			if ( substr($eqLogic->getLogicalId(), 0, strpos($eqLogic->getLogicalId(),"_")) == $this->getId() ) {
				log::add('wes','debug','Suppression bouton : '.$eqLogic->getName());
				$eqLogic->remove();
			}
		}
		foreach (self::byType('wes_pince') as $eqLogic) {
			if ( substr($eqLogic->getLogicalId(), 0, strpos($eqLogic->getLogicalId(),"_")) == $this->getId() ) {
				log::add('wes','debug','Suppression pince : '.$eqLogic->getName());
				$eqLogic->remove();
			}
		}
		foreach (self::byType('wes_teleinfo') as $eqLogic) {
			if ( substr($eqLogic->getLogicalId(), 0, strpos($eqLogic->getLogicalId(),"_")) == $this->getId() ) {
				log::add('wes','debug','Suppression teleinfo : '.$eqLogic->getName());
				$eqLogic->remove();
			}
		}
		foreach (self::byType('wes_analogique') as $eqLogic) {
			if ( substr($eqLogic->getLogicalId(), 0, strpos($eqLogic->getLogicalId(),"_")) == $this->getId() ) {
				log::add('wes','debug','Suppression analogique : '.$eqLogic->getName());
				$eqLogic->remove();
			}
		}
	}

	public function configPush() {
		if ( config::byKey("internalAddr") == "" || config::byKey("internalPort") == "" )
		{
			throw new Exception(__('L\'adresse IP ou le port local de jeedom ne sont pas définit (Administration => Configuration réseaux => Accès interne).', __FILE__));
		}
		$pathjeedom = config::byKey("internalComplement");
		if ( substr($pathjeedom, 0, 1) != "/" ) {
			$pathjeedom = "/".$pathjeedom;
		}
		if ( substr($pathjeedom, -1) != "/" ) {
			$pathjeedom = $pathjeedom."/";
		}
		if ( $this->getIsEnable() ) {
			$this->getUrl('rqthttp.cgi', 'RQd5='.config::byKey("internalAddr").'&RQp5='.config::byKey("internalPort"));
			$compteurId=0;
 			foreach (explode(',', init('eqLogicPush_id')) as $_eqLogic_id) {
				$eqLogic = eqLogic::byId($_eqLogic_id);
				if (!is_object($eqLogic)) {
					throw new Exception(__('Impossible de trouver l\'équipement : ', __FILE__) . $_eqLogic_id);
				}
				if ( method_exists($eqLogic, "configPush" ) ) {
					$compteurId = $eqLogic->configPush($this, $compteurId, $pathjeedom);
					$compteurId++;
				}
			}
		}
	}

	public function event() {
		foreach (eqLogic::byType('wes') as $eqLogic) {
			if ( $eqLogic->getId() == init('id') ) {
				$eqLogic->pull();
			}
		}
	}

	public function pull() {
		if ( $this->getIsEnable() ) {
			log::add('wes','debug','pull '.$this->getName());
			$statuscmd = $this->getCmd(null, 'status');
			log::add('wes','debug','get data.cgx');
			$this->xmlstatus = simplexml_load_string($this->getUrl('data.cgx'));
			$count = 0;
			while ( $this->xmlstatus === false && $count < 3 ) {
				log::add('wes','debug','reget data.cgx');
				$this->xmlstatus = simplexml_load_string($this->getUrl('data.cgx'));
				$count++;
			}
			if ( $this->xmlstatus === false ) {
				if ($statuscmd->execCmd() != 0) {
					$statuscmd->setCollectDate('');
					$statuscmd->event(0);
				}
				log::add('wes','error',__('Le wes ne repond pas.',__FILE__)." ".$this->getName()." get data.cgx");
				return false;
			}
			if ($statuscmd->execCmd() != 1) {
				$statuscmd->setCollectDate('');
				$statuscmd->event(1);
			}
			foreach (self::byType('wes_relai') as $eqLogicRelai) {
				if ( $eqLogicRelai->getIsEnable() && substr($eqLogicRelai->getLogicalId(), 0, strpos($eqLogicRelai->getLogicalId(),"_")) == $this->getId() ) {
					$wesid = substr($eqLogicRelai->getLogicalId(), strpos($eqLogicRelai->getLogicalId(),"_")+2);
					if ( $wesid < 10 ) {
						$xpathModele = '//relais/RELAIS'.$wesid;
						$status = $this->xmlstatus->xpath($xpathModele);
						
						if ( count($status) != 0 )
						{
							$eqLogic_cmd = $eqLogicRelai->getCmd(null, 'state');
							if ($eqLogic_cmd->execCmd() != $eqLogic_cmd->formatValue($status[0])) {
								log::add('wes','debug',"Change state off ".$eqLogicRelai->getName());
								$eqLogic_cmd->setCollectDate('');
								$eqLogic_cmd->event($status[0]);
							}
						}
					} else {
						$xpathModele = '//relais1W/RELAIS'.$wesid;
						$status = $this->xmlstatus->xpath($xpathModele);
						
						if ( count($status) != 0 )
						{
							$eqLogic_cmd = $eqLogicRelai->getCmd(null, 'state');
							if ($eqLogic_cmd->execCmd() != $eqLogic_cmd->formatValue($status[0])) {
								log::add('wes','debug',"Change state off ".$eqLogicRelai->getName());
								$eqLogic_cmd->setCollectDate('');
								$eqLogic_cmd->event($status[0]);
							}
						}
					}
				}
			}
			foreach (self::byType('wes_bouton') as $eqLogicBouton) {
				if ( $eqLogicBouton->getIsEnable() && substr($eqLogicBouton->getLogicalId(), 0, strpos($eqLogicBouton->getLogicalId(),"_")) == $this->getId() ) {
					$wesid = substr($eqLogicBouton->getLogicalId(), strpos($eqLogicBouton->getLogicalId(),"_")+2);
					$xpathModele = '//entree/ENTREE'.$wesid;
					$status = $this->xmlstatus->xpath($xpathModele);
					
					if ( count($status) != 0 )
					{
						$eqLogic_cmd = $eqLogicBouton->getCmd(null, 'state');
						if ($eqLogic_cmd->execCmd() != $eqLogic_cmd->formatValue($status[0])) {
							log::add('wes','debug',"Change state off ".$eqLogicBouton->getName());
							$eqLogic_cmd->setCollectDate('');
							$eqLogic_cmd->event($status[0]);
						}
					}
				}
			}
			foreach (self::byType('wes_temperature') as $eqLogictemperature) {
				if ( $eqLogictemperature->getIsEnable() && substr($eqLogictemperature->getLogicalId(), 0, strpos($eqLogictemperature->getLogicalId(),"_")) == $this->getId() ) {
					$wesid = substr($eqLogictemperature->getLogicalId(), strpos($eqLogictemperature->getLogicalId(),"_")+2);
					$xpathModele = '//temp/SONDE'.$wesid;
					$status = $this->xmlstatus->xpath($xpathModele);
					
					if ( count($status) != 0 )
					{
						$eqLogic_cmd = $eqLogictemperature->getCmd(null, 'reel');
						if ($eqLogic_cmd->execCmd() != $eqLogic_cmd->formatValue($status[0])) {
							log::add('wes','debug',"Change reel ".$eqLogictemperature->getName());
						}
						$eqLogic_cmd->setCollectDate('');
						$eqLogic_cmd->event($status[0]);
					}
				}
			}
			foreach (self::byType('wes_compteur') as $eqLogicCompteur) {
				if ( $eqLogicCompteur->getIsEnable() && substr($eqLogicCompteur->getLogicalId(), 0, strpos($eqLogicCompteur->getLogicalId(),"_")) == $this->getId() ) {
					$wesid = substr($eqLogicCompteur->getLogicalId(), strpos($eqLogicCompteur->getLogicalId(),"_")+2);
					$xpathModele = '//impulsion/INDEX'.$wesid;
					$status = $this->xmlstatus->xpath($xpathModele);
					
					if ( count($status) != 0 )
					{
						$nbimpulsion_cmd = $eqLogicCompteur->getCmd(null, 'nbimpulsion');
						$nbimpulsion = $nbimpulsion_cmd->execCmd();
						$nbimpulsionminute_cmd = $eqLogicCompteur->getCmd(null, 'nbimpulsionminute');
						if ( $nbimpulsion != $status[0] ) {
							log::add('wes','debug',"Change nbimpulsion off ".$eqLogicCompteur->getName());
							$lastCollectDate = $nbimpulsion_cmd->getCollectDate();
							if ( $lastCollectDate == '' ) {
								log::add('wes','debug',"Change nbimpulsionminute 0");
								$nbimpulsionminute = 0;
							} else {
								$DeltaSeconde = (time() - strtotime($lastCollectDate))*60;
								if ( $DeltaSeconde != 0 )
								{
									if ( $status[0] > $nbimpulsion ) {
										$DeltaValeur = $status[0] - $nbimpulsion;
									} else {
										$DeltaValeur = $status[0];
									}
									$nbimpulsionminute = round (($status[0] - $nbimpulsion)/(time() - strtotime($lastCollectDate))*60, 6);
								} else {
									$nbimpulsionminute = 0;
								}
							}
							log::add('wes','debug',"Change nbimpulsionminute ".$nbimpulsionminute);
							$nbimpulsionminute_cmd->setCollectDate(date('Y-m-d H:i:s'));
							$nbimpulsionminute_cmd->event($nbimpulsionminute);
						} else {
							$nbimpulsionminute_cmd->setCollectDate(date('Y-m-d H:i:s'));
							$nbimpulsionminute_cmd->event(0);
						}
						$nbimpulsion_cmd->setCollectDate(date('Y-m-d H:i:s'));
						$nbimpulsion_cmd->event($status[0]);
					}
				}
			}
			foreach (self::byType('wes_pince') as $eqLogicPince) {
				if ( $eqLogicPince->getIsEnable() && substr($eqLogicPince->getLogicalId(), 0, strpos($eqLogicPince->getLogicalId(),"_")) == $this->getId() ) {
					$wesid = substr($eqLogicPince->getLogicalId(), strpos($eqLogicPince->getLogicalId(),"_")+2);
					$xpathModele = '//pince/I'.$wesid;
					$status = $this->xmlstatus->xpath($xpathModele);
					
					if ( count($status) != 0 )
					{
						$eqLogic_cmd = $eqLogicPince->getCmd(null, 'intensite');
						if ($eqLogic_cmd->execCmd() != $eqLogic_cmd->formatValue($status[0])) {
							log::add('wes','debug',"Change intensite ".$eqLogicPince->getName());
						}
						$eqLogic_cmd->setCollectDate('');
						$eqLogic_cmd->event($status[0]);
					}
					$xpathModele = '//pince/INDEX'.$wesid;
					$status = $this->xmlstatus->xpath($xpathModele);
					
					if ( count($status) != 0 )
					{
						$eqLogic_cmd = $eqLogicPince->getCmd(null, 'puissance');
						if ($eqLogic_cmd->execCmd() != $eqLogic_cmd->formatValue($status[0])) {
							log::add('wes','debug',"Change puissance ".$eqLogicPince->getName());
						}
						$eqLogic_cmd->setCollectDate('');
						$eqLogic_cmd->event($status[0]);
					}
				}
			}
			foreach (self::byType('wes_teleinfo') as $eqLogicTeleinfo) {
				if ( $eqLogicTeleinfo->getIsEnable() && substr($eqLogicTeleinfo->getLogicalId(), 0, strpos($eqLogicTeleinfo->getLogicalId(),"_")) == $this->getId() ) {
					$wesid = substr($eqLogicTeleinfo->getLogicalId(), strpos($eqLogicTeleinfo->getLogicalId(),"_")+2, 1);
					$xpathModele = '//tic'.$wesid;
					$status = $this->xmlstatus->xpath($xpathModele);
					
					if ( count($status) != 0 )
					{
						foreach($status[0] as $item => $data) {
						log::add('wes','debug',"Trouve ".$item." => ".$data);
							$eqLogic_cmd = $eqLogicTeleinfo->getCmd(null, $item);
							if ( is_object($eqLogic_cmd) ) {
								$eqLogic_cmd_evol = $eqLogicTeleinfo->getCmd(null, $item."_evolution");
								if ( is_object($eqLogic_cmd_evol) ) {
									$ancien_data = $eqLogic_cmd->execCmd();
									if ($ancien_data != $data) {
										log::add('wes', 'debug', $eqLogic_cmd->getName().' Change '.$data);
										if ( $eqLogic_cmd->getCollectDate() == '' ) {
											$nbimpulsionminute = 0;
										} else {
											if ( $data > $ancien_data ) {
												$nbimpulsionminute = round (($data - $ancien_data)/(time() - strtotime($eqLogic_cmd->getCollectDate()))*60);
											} else {
												$nbimpulsionminute = round ($data/(time() - strtotime($eqLogic_cmd_evol->getCollectDate())*60));
											}
										}
										$eqLogic_cmd_evol->setCollectDate(date('Y-m-d H:i:s'));
										$eqLogic_cmd_evol->event($nbimpulsionminute);
									} else {
										$eqLogic_cmd_evol->setCollectDate(date('Y-m-d H:i:s'));
										$eqLogic_cmd_evol->event(0);
									}
									$eqLogic_cmd->setCollectDate(date('Y-m-d H:i:s'));
									$eqLogic_cmd->event($data);
								} else {
									$eqLogic_cmd->setCollectDate(date('Y-m-d H:i:s'));
									$eqLogic_cmd->event($data);
								}
							}
						}
					}
				}
			}
			foreach (self::byType('wes_analogique') as $eqLogicCompteur) {
				if ( $eqLogicCompteur->getIsEnable() && substr($eqLogicCompteur->getLogicalId(), 0, strpos($eqLogicCompteur->getLogicalId(),"_")) == $this->getId() ) {
					$wesid = substr($eqLogicCompteur->getLogicalId(), strpos($eqLogicCompteur->getLogicalId(),"_")+2);
					$xpathModele = '//analogique/AD'.$wesid;
					$status = $this->xmlstatus->xpath($xpathModele);
					
					if ( count($status) != 0 )
					{
						$eqLogic_cmd = $eqLogicCompteur->getCmd(null, 'brut');
						if ($eqLogic_cmd->execCmd() != $eqLogic_cmd->formatValue($status[0])) {
							log::add('wes','debug',"Change brut ".$eqLogicCompteur->getName());
						}
						$eqLogic_cmd->setCollectDate('');
						$eqLogic_cmd->event($status[0]);
					}
				}
			}
			log::add('wes','debug','pull end '.$this->getName());
		}
	}
    /*     * **********************Getteur Setteur*************************** */
}

class wesCmd extends cmd 
{
    /*     * *************************Attributs****************************** */


    /*     * ***********************Methode static*************************** */


    /*     * *********************Methode d'instance************************* */

    /*     * **********************Getteur Setteur*************************** */
    public function execute($_options = null) {
		$eqLogic = $this->getEqLogic();
        if (!is_object($eqLogic) || $eqLogic->getIsEnable() != 1) {
            throw new Exception(__('Equipement desactivé impossible d\éxecuter la commande : ' . $this->getHumanName(), __FILE__));
        }
			
		if ( $this->getLogicalId() == 'all_on' )
		{
			$url .= 'preset.htm';
			for ($wesid = 0; $wesid <= 7; $wesid++) {
				$data['led'.($wesid+1)] =1;
			}
		}
		else if ( $this->getLogicalId() == 'all_off' )
		{
			$url .= 'preset.htm';
			for ($wesid = 0; $wesid <= 7; $wesid++) {
				$data['led'.($wesid+1)] =0;
			}
		}
		else if ( $this->getLogicalId() == 'reboot' )
		{
			$url .= "protect/settings/reboot.htm";
		}
		else
			return false;
		log::add('wes','debug','get '.$url.'?'.http_build_query($data));
		$result = $eqLogic->getUrl($url.'?'.http_build_query($data));
		$count = 0;
		while ( $result === false )
		{
		$result = $eqLogic->getUrl($url.'?'.http_build_query($data));
			if ( $count < 3 ) {
				log::add('wes','error',__('Le wes ne repond pas.',__FILE__)." ".$this->getName()." get ".$url."?".http_build_query($data));
				throw new Exception(__('Le wes ne repond pas.',__FILE__)." ".$this->getName());
			}
			$count ++;
		}
        return false;
    }

}
?>
