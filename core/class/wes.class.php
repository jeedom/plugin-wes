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

class wes extends eqLogic {

	private function getListeCommandes() {
		$commands = array(
						"teleinfo" => array(
							"ADCO" => array("name" => "Numero compteur", "type" => "info", "subtype"=> "numeric", "dashboard" =>"line", "mobile" =>"line", "xpath" => "//tic#id#/ADCO"),
							"OPTARIF" => array("name" => "Option tarif", "type" => "info", "subtype"=> "string", "xpath" => "//tic#id#/OPTARIF"),
							"ISOUSC" => array("name" => "Intensité souscrite", "type" => "info", "subtype"=> "numeric", "unite" => "A", "dashboard" =>"line", "mobile" =>"line", "xpath" => "//tic#id#/ISOUSC"),
							"PTEC" => array("name" => "Tarif en cours", "type" => "info", "subtype"=> "string", "xpath" => "//tic#id#/PTEC"),
							"PAP" => array("name" => "Puissance Apparente", "type" => "info", "subtype"=> "numeric", "unite" => "VA", "xpath" => "//tic#id#/PAP"),
							"IINST" => array("name" => "Intensité instantanée", "type" => "info", "subtype"=> "numeric", "unite" => "A", "xpath" => "//tic#id#/IINST"),
							"IINST1" => array("name" => "Intensité instantanée 1", "type" => "info", "subtype"=> "numeric", "unite" => "A", "visible" => 0, "xpath" => "//tic#id#/IINST1"),
							"IINST2" => array("name" => "Intensité instantanée 2", "type" => "info", "subtype"=> "numeric", "unite" => "A", "visible" => 0, "xpath" => "//tic#id#/IINST2"),
							"IINST3" => array("name" => "Intensité instantanée 3", "type" => "info", "subtype"=> "numeric", "unite" => "A", "visible" => 0, "xpath" => "//tic#id#/IINST3"),
							"IMAX" => array("name" => "Intensité maximum", "type" => "info", "subtype"=> "numeric", "unite" => "A", "xpath" => "//tic#id#/IMAX"),
							"IMAX1" => array("name" => "Intensité maximum 1", "type" => "info", "subtype"=> "numeric", "unite" => "A", "visible" => 0, "xpath" => "//tic#id#/IMAX1"),
							"IMAX2" => array("name" => "Intensité maximum 2", "type" => "info", "subtype"=> "numeric", "unite" => "A", "visible" => 0, "xpath" => "//tic#id#/IMAX2"),
							"IMAX3" => array("name" => "Intensité maximum 3", "type" => "info", "subtype"=> "numeric", "unite" => "A", "visible" => 0, "xpath" => "//tic#id#/IMAX3"),
							"TENS1" => array("name" => "Tension 1", "type" => "info", "subtype"=> "numeric", "unite" => "V", "visible" => 0, "xpath" => "//tic#id#/TENSION1"),
							"TENS2" => array("name" => "Tension 2", "type" => "info", "subtype"=> "numeric", "unite" => "V", "visible" => 0, "xpath" => "//tic#id#/TENSION2"),
							"TENS3" => array("name" => "Tension 3", "type" => "info", "subtype"=> "numeric", "unite" => "V", "visible" => 0, "xpath" => "//tic#id#/TENSION3"),
							"PEJP" => array("name" => "Préavis EJP", "type" => "info", "subtype"=> "binary", "filter" => "EJP", "xpath" => "//tic#id#/PEJP"),
							"DEMAIN" => array("name" => "Couleur demain", "type" => "info", "subtype"=> "string", "filter" => "BBRH", "xpath" => "//tic#id#/DEMAIN"),
							"BASE" => array("name" => "Index (base)", "type" => "info", "subtype"=> "numeric", "unite" => "kWh", "filter" => "BASE","calcul"=>"#value#/1000", "dashboard" =>"line", "mobile" =>"line", "xpath" => "//tic#id#/BASE"),
							"HCHC" => array("name" => "Index (heures creuses)", "type" => "info", "subtype"=> "numeric", "unite" => "kWh", "filter" => "HC","calcul"=>"#value#/1000", "dashboard" =>"line", "mobile" =>"line","xpath" => "//tic#id#/HCHC"),
							"HCHP" => array("name" => "Index (heures pleines)", "type" => "info", "subtype"=> "numeric", "unite" => "kWh", "filter" => "HC","calcul"=>"#value#/1000", "dashboard" =>"line", "mobile" =>"line","xpath" => "//tic#id#/HCHP"),
							"EJPHN" => array("name" => "Index (normal EJP)", "type" => "info", "subtype"=> "numeric", "unite" => "kWh", "filter" => "EJP","calcul"=>"#value#/1000", "dashboard" =>"line", "mobile" =>"line","xpath" => "//tic#id#/EJPHN"),
							"EJPHPM" => array("name" => "Index (pointe mobile EJP)", "type" => "info", "subtype"=> "numeric", "unite" => "kWh", "filter" => "EJP","calcul"=>"#value#/1000", "dashboard" =>"line", "mobile" =>"line","xpath" => "//tic#id#/EJPHPM"),
							"BBRHCJB" => array("name" => "Index (heures creuses jours bleus Tempo)", "type" => "info", "subtype"=> "numeric", "unite" => "kWh", "filter" => "EJP","calcul"=>"#value#/1000", "dashboard" =>"line", "mobile" =>"line","xpath" => "//tic#id#/BBRHCJB"),
							"BBRHPJB" => array("name" => "Index (heures pleines jours bleus Tempo)", "type" => "info", "subtype"=> "numeric", "unite" => "kWh", "filter" => "EJP","calcul"=>"#value#/1000", "dashboard" =>"line", "mobile" =>"line","xpath" => "//tic#id#/BBRHPJB"),
							"BBRHCJW" => array("name" => "Index (heures creuses jours blancs Tempo)", "type" => "info", "subtype"=> "numeric", "unite" => "kWh", "filter" => "EJP","calcul"=>"#value#/1000", "dashboard" =>"line", "mobile" =>"line","xpath" => "//tic#id#/BBRHCJW"),
							"BBRHPJW" => array("name" => "Index (heures pleines jours blancs Tempo)", "type" => "info", "subtype"=> "numeric", "unite" => "kWh", "filter" => "EJP","calcul"=>"#value#/1000", "dashboard" =>"line", "mobile" =>"line","xpath" => "//tic#id#/BBRHPJW"),
							"BBRHCJR" => array("name" => "Index (heures creuses jours rouges Tempo)", "type" => "info", "subtype"=> "numeric", "unite" => "kWh", "filter" => "EJP","calcul"=>"#value#/1000", "dashboard" =>"line", "mobile" =>"line","xpath" => "//tic#id#/BBRHCJR"),
							"BBRHPJR" => array("name" => "Index (heures pleines jours rouges Tempo)", "type" => "info", "subtype"=> "numeric", "unite" => "kWh", "filter" => "EJP","calcul"=>"#value#/1000", "dashboard" =>"line", "mobile" =>"line","xpath" => "//tic#id#/BBRHPJR"),
							"CONSO_JOUR" => array("name" => "Consommation de la Journée", "type" => "info", "subtype"=> "numeric", "unite" => "kWh", "xpath" => "//tic#id#/CONSO_JOUR"),
							"COUT_JOUR" => array("name" => "Coût de la Journée", "type" => "info", "subtype"=> "numeric", "unite" => "€", "xpath" => "//tic#id#/COUT_JOUR"),
							"CONSO_MOIS" => array("name" => "Consommation du mois", "type" => "info", "subtype"=> "numeric", "unite" => "kWh", "xpath" => "//tic#id#/CONSO_MOIS"),
							"COUT_MOIS" => array("name" => "Coût du mois", "type" => "info", "subtype"=> "numeric", "unite" => "€", "xpath" => "//tic#id#/COUT_MOIS"),
							"CONSO_ANNEE" => array("name" => "Consommation année", "type" => "info", "subtype"=> "numeric", "unite" => "kWh", "xpath" => "//tic#id#/CONSO_ANNEE"),
							"COUT_ANNEE" => array("name" => "Coût année", "type" => "info", "subtype"=> "numeric", "unite" => "€", "xpath" => "//tic#id#/COUT_ANNEE"),
						),
						"general" => array(
							"status" => array("name"=>"Statut", "type"=>"info", "subtype" =>"binary"),
							"alarme" => array("name"=>"Alarme", "type"=>"info", "subtype" =>"binary", "xpath" =>"//info/alarme","dashboard" =>"alert", "mobile" =>"alert", "invert" =>True),
							"firmware" => array("name"=>"Firmware", "type"=>"info", "subtype" =>"string", "xpath" =>"//info/firmware"),
							"serverversion" => array("name"=>"Version Serveur", "type"=>"info", "subtype" =>"string", "xpath" =>"//info/serverversion"),
							"spaceleft" => array("name"=>"Espace libre", "type"=>"info", "subtype" =>"numeric", "unite"=>"Go", "xpath" =>"//info/spaceleft"),
							"tension" => array("name"=>"Tension", "type"=>"info", "subtype" =>"numeric", "unite"=>"V","xpath" =>"//pince/V"),
							"alarmeon" => array("name"=>"Alarme On", "type"=>"action", "subtype" =>"other"),
							"alarmeoff" => array("name"=>"Alarme Off", "type"=>"action", "subtype" =>"other"),
						),
						"compteur" => array(
							"nbimpulsion" => array("name"=>"Nombre d impulsion", "type"=>"info", "subtype" =>"numeric", "unite"=>"imp", "xpath" =>"//impulsion/PULSE#id#"),
							"index" => array("name"=>"Index", "type"=>"info", "subtype" =>"numeric", "unite"=>"l", "xpath" =>"//impulsion/INDEX#id#"),
							"debit" => array("name"=>"Débit", "type"=>"info", "subtype" =>"numeric", "unite"=>"l/min", "xpath" =>"//impulsion/DEBIT#id#"),
							"consojour" => array("name"=>"Consommation jour", "type"=>"info", "subtype" =>"numeric", "unite"=>"l", "xpath" =>"//impulsion/CONSO_JOUR#id#"),
							"consoveille" => array("name"=>"Consommation veille", "type"=>"info", "subtype" =>"numeric", "unite"=>"l", "xpath" =>"//impulsion/CONSO_VEILLE#id#"),
							"coutjour" => array("name"=>"Coût jour", "type"=>"info", "subtype" =>"numeric", "unite"=>"€", "xpath" =>"//impulsion/COUT_JOUR#id#"),
							"consomois" => array("name"=>"Consommation mois", "type"=>"info", "subtype" =>"numeric", "unite"=>"m3", "xpath" =>"//impulsion/CONSO_MOIS#id#"),
							"coutmois" => array("name"=>"Coût mois", "type"=>"info", "subtype" =>"numeric", "unite"=>"€", "xpath" =>"//impulsion/COUT_MOIS#id#"),
							"consoannee" => array("name"=>"Consommation année", "type"=>"info", "subtype" =>"numeric", "unite"=>"m3", "xpath" =>"//impulsion/CONSO_ANNEE#id#"),
							"coutannee" => array("name"=>"Coût année", "type"=>"info", "subtype" =>"numeric", "unite"=>"€", "xpath" =>"//impulsion/COUT_ANNEE#id#"),
						),
						"pince" => array(
							"intensite" => array("name"=>"Intensité", "type"=>"info", "subtype" =>"numeric", "unite"=>"A", "xpath" =>"//pince/I#id#"),
							"puissance" => array("name"=>"Puissance", "type"=>"info", "subtype" =>"numeric", "unite"=>"VA", "xpath" =>"//pince/PUISSANCE#id#"),
							"index" => array("name"=>"Index", "type"=>"info", "subtype" =>"numeric", "unite"=>"kWh", "xpath" =>"//pince/INDEX#id#"),
							"injection" => array("name"=>"Injection", "type"=>"info", "subtype" =>"numeric", "unite"=>"kWh", "xpath" =>"//pince/INJECT#id#"),
							"consojour" => array("name"=>"Consommation jour", "type"=>"info", "subtype" =>"numeric", "unite"=>"kWh", "xpath" =>"//pince/CONSO_JOUR#id#"),
							"coutjour" => array("name"=>"Coût jour", "type"=>"info", "subtype" =>"numeric", "unite"=>"€", "xpath" =>"//pince/COUT_JOUR#id#"),
							"injecjour" => array("name"=>"Injection jour", "type"=>"info", "subtype" =>"numeric", "unite"=>"kWh", "xpath" =>"//pince/INJEC_JOUR#id#"),
							"gainjour" => array("name"=>"Gain jour", "type"=>"info", "subtype" =>"numeric", "unite"=>"€","xpath" =>"//pince/GAIN_JOUR#id#"),
							"maxjour" => array("name"=>"Max jour", "type"=>"info", "subtype" =>"numeric", "unite"=>"A", "xpath" =>"//pince/MAX_JOUR#id#"),
							"consomois" => array("name"=>"Consommation mois", "type"=>"info", "subtype" =>"numeric", "unite"=>"kWh", "xpath" =>"//pince/CONSO_MOIS#id#"),
							"coutmois" => array("name"=>"Coût mois", "type"=>"info", "subtype" =>"numeric", "unite"=>"€", "xpath" =>"//pince/COUT_MOIS#id#"),
							"injecmois" => array("name"=>"Injection mois", "type"=>"info", "subtype" =>"numeric", "unite"=>"kWh","xpath" =>"//pince/INJEC_MOIS#id#"),
							"gainmois" => array("name"=>"Gain mois", "type"=>"info", "subtype" =>"numeric", "unite"=>"€", "xpath" =>"//pince/GAIN_MOIS#id#"),
							"maxmois" => array("name"=>"Max mois", "type"=>"info", "subtype" =>"numeric", "unite"=>"A", "xpath" =>"//pince/MAX_MOIS#id#"),
							"consoannee" => array("name"=>"Consommation année", "type"=>"info", "subtype" =>"numeric", "unite"=>"kWh","xpath" =>"//pince/CONSO_ANNEE#id#"),
							"coutannee" => array("name"=>"Coût année", "type"=>"info", "subtype" =>"numeric", "unite"=>"€", "xpath" =>"//pince/COUT_ANNEE#id#"),
							"injecannee" => array("name"=>"Injection année", "type"=>"info", "subtype" =>"numeric", "unite"=>"kWh", "xpath" =>"//pince/INJEC_ANNEE#id#"),
							"gainannee" => array("name"=>"Gain année", "type"=>"info", "subtype" =>"numeric", "unite"=>"€", "xpath" =>"//pince/GAIN_ANNEE#id#"),
							"maxannee" => array("name"=>"Max année", "type"=>"info", "subtype" =>"numeric", "unite"=>"A", "xpath" =>"//pince/MAX_ANNEE#id#"),
						),
						"bouton" => array(
							"state" => array("name"=>"Etat", "type"=>"info", "subtype" =>"binary","xpath" =>"//entree/ENTREE#id#"),
						),
						"relai" => array(
							"state" => array("name"=>"Etat", "type"=>"info", "subtype" =>"binary","xpath" =>"//relais/RELAIS#id#","xpathcond" => "//relais1W/RELAIS#id#","cond"=>"#id#>=10", "dashboard" =>"prise", "mobile" =>"prise"),
							"btn_on" => array("name"=>"On", "type"=>"action", "subtype" =>"other"),
							"btn_off" => array("name"=>"Off", "type"=>"action", "subtype" =>"other"),
							"commute" => array("name"=>"Toggle", "type"=>"action", "subtype" =>"other"),
						),
						"switch" => array(
							"state" => array("name"=>"Etat", "type"=>"info", "subtype" =>"binary","xpath" =>"//switch_virtuel/SWITCH#id#","dashboard" =>"prise", "mobile" =>"prise"),
							"btn_on" => array("name"=>"On", "type"=>"action", "subtype" =>"other"),
							"btn_off" => array("name"=>"Off", "type"=>"action", "subtype" =>"other"),
							"commute" => array("name"=>"Toggle", "type"=>"action", "subtype" =>"other"),
						),
						"temperature" => array(
							"reel" => array("name"=>"Température", "type"=>"info", "subtype" =>"numeric", "unite"=>"°C","xpath" =>"//temp/SONDE#id#"),
						),
						"analogique" => array(
							"reel" => array("name"=>"Réel", "type"=>"info", "subtype" =>"numeric"),
							"brut" => array("name"=>"Brut", "type"=>"info", "subtype" =>"numeric","xpath" =>"//analogique/AD#id#"),
						),
						"variable" => array(
							"value" => array("name"=>"Valeur", "type"=>"info", "subtype" =>"numeric","xpath" =>"//variables/VARIABLE#id#"),
						)
					);
		return $commands;
	}
	
	public function getTypes() {
		$types = array(
						"general" => array("name" => "Serveur Wes","ignoreCreation"=>1),
						"analogique" => array("name" => "Capteur","logical" => "_N","xpath" =>"//analogique/AD#id#","maxnumber"=>4),
						"compteur" => array("name" => "Compteur Impulsion","logical" => "_C","xpath" =>"//impulsion/INDEX#id#","maxnumber"=>6),
						"bouton" => array("name" => "Entrée","logical" => "_B","xpath" =>"//entree/ENTREE#id#","maxnumber"=>2),
						"pince" => array("name" => "Pince Ampèremétrique","logical" => "_P","xpath" =>"//pince/I#id#","maxnumber"=>4),
						"relai" => array("name" => "Relais","logical" => "_R","xpath" =>"//relais/RELAIS#id#","maxnumber"=>2),
						"switch" => array("name" => "Switch Virtuel","logical" => "_S","xpath" =>"//switch_virtuel/SWITCH#id#","maxnumber"=>24),
						"teleinfo" => array("name" => "Téléinfo","logical" => "_T","xpath" =>"//tic#id#/ADCO","maxnumber"=>3),
						"temperature" => array("name" => "Température","logical" => "_A","xpath" =>"//temp/SONDE#id#","maxnumber"=>30),
						"variable" => array("name" => "Variable","logical" => "_V","xpath" =>"//variables/VARIABLE#id#","maxnumber"=>8),
					);
		return $types;
	}

	public static function daemon() {
		$starttime = microtime (true);
		log::add('wes','debug','cron start');
		foreach (self::byType('wes') as $eqLogic) {
			if($eqLogic->getConfiguration('type') == "general"){
				$eqLogic->pull();
			}
		}
		log::add('wes','debug','cron stop');
		$endtime = microtime (true);
		if ($endtime - $starttime < config::byKey('temporisation_lecture', 'wes', 60, true)) {
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
		log::add('wes','debug','Launching Daemon');
		$cron->run();
	}

	public static function deamon_stop() {
		$cron = cron::byClassAndFunction('wes', 'daemon');
		if (!is_object($cron)) {
			throw new Exception(__('Tâche cron introuvable', __FILE__));
		}
		log::add('wes','debug','Stopping Daemon');
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
	
	public function sendFtp() {
		$ftpIp = $this->getConfiguration('ip');
		$ftpUser = $this->getConfiguration('ftpusername');
		$ftpPass = $this->getConfiguration('ftppassword');
		$local_file = dirname(__FILE__) . '/../../resources/DATA_JEEDOM.CGX';
		$connection = ftp_connect($ftpIp);
		if (@ftp_login($connection, $ftpUser, $ftpPass)){
			log::add('wes','debug','Successfully connected to ftp');
		}else{
			ftp_close($connection);
			log::add('wes','error','Error connecting to ftp');
			return false;
		}
		ftp_pasv($connection, true);
		if (ftp_put($connection, '/DATA_JEEDOM.CGX',  $local_file, FTP_BINARY)) {
			log::add('wes','debug','Successfully uploaded file to ftp');
		} else {
			log::add('wes','error','Error uploading file to ftp');
			ftp_close($connection);
			return false;
		}
		ftp_close($connection);
		return true;
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

	public function preUpdate() {
		if ($this->getConfiguration('usecustomcgx',0) == 1){
			if ($this->getConfiguration('ftpusername','') != '' && $this->getConfiguration('ftppassword','') != '') {
				$this->sendFtp();
			}
		}
		if ( $this->getIsEnable() && $this->getConfiguration('type') == "general" ){
			log::add('wes','debug','get cgx');
			$file = 'data.cgx';
			if ($this->getConfiguration('usecustomcgx',0) == 1) {
				$file = 'data_jeedom.cgx';
			}
			$this->xmlstatus = simplexml_load_string($this->getUrl($file));
			if ( $this->xmlstatus === false ){
				throw new Exception(__('Le wes ne repond pas.',__FILE__));
			}
		}
	}

	public function preInsert() {
		$this->setIsEnable(0);
		$this->setIsVisible(0);
		if ($this->getConfiguration('type','') == '') {
			$this->setConfiguration('type','general');
		}
	}
	
	public function postAjax() {
		$type = $this->getConfiguration('type');
		foreach($this->getListeCommandes()[$type] as $logicalId => $details) {
			$filter = '';
			if ($type == 'teleinfo') {
				$filter = $this->getConfiguration('tarification','');
			}
			if (isset($details['filter'])) {
				if ($filter != '' && $details['filter'] != $filter){
					$cmd = $this->getCmd(null, $logicalId);
					if (is_object($cmd) ) {
						$cmd->remove();
					}
				}
			}
		}
	}

	public function postSave() {
		$type = $this->getConfiguration('type');
		$order = 1;
		foreach($this->getListeCommandes()[$type] as $logicalId => $details) {
			$filter = '';
			if ($type == 'teleinfo') {
				$filter = $this->getConfiguration('tarification','');
			}
			if (isset($details['filter'])) {
				if ($filter != '' && $details['filter'] != $filter){
					continue;
				}
			}
			$cmd = $this->getCmd(null, $logicalId);
			if (!is_object($cmd) ) {
				$cmd = new wesCmd();
				$cmd->setName($details['name']);
				$cmd->setEqLogic_id($this->getId());
				$cmd->setType($details['type']);
				$cmd->setSubType($details['subtype']);
				$cmd->setLogicalId($logicalId);
				$cmd->setOrder($order);
				if (isset($details['visible'])){
					$cmd->setIsVisible($details['visible']);
				}
				if (isset($details['history'])){
					$cmd->setIsHistorized($details['history']);
				}
				if (isset($details['unite'])){
					$cmd->setUnite($details['unite']);
				}
				if (isset($details['calcul'])){
					$cmd->setConfiguration('calculValueOffset',$details['calcul']);
				}
				if (isset($details['dashboard'])){
					$cmd->setTemplate('dashboard',$details['dashboard']);
				}
				if (isset($details['mobile'])){
					$cmd->setTemplate('mobile',$details['mobile']);
				}
				if (isset($details['invert'])){
					$cmd->setDisplay('invertBinary',$details['invert']);
				}
				$cmd->save();
			}
			$order += 1;
		}
		if ($type == 'general') {
			self::deamon_start();
		}
	}

	public function postUpdate() {
		if ($this->getConfiguration('type') == 'general') {
			$file = 'data.cgx';
			if ($this->getConfiguration('usecustomcgx',0) == 1) {
				$file = 'data_jeedom.cgx';
			}
			$this->xmlstatus = simplexml_load_string($this->getUrl($file));
			foreach (self::getTypes() as $type => $data){
				if (!isset($data['ignoreCreation'])) {
					$id = 1;
					$xpathModele = str_replace('#id#',$id,$data['xpath']);
					$status = $this->xmlstatus->xpath($xpathModele);
					while ( count($status) != 0 ) {
						if (!is_object(self::byLogicalId($this->getId().$data['logical'].$id, 'wes')) && $this->getConfiguration($type.$id,1)==1) {
							log::add('wes','debug',count($status).'Creation ' . $data['name'] . ' : '.$this->getId().$data['logical'].$id);
							$eqLogic = new wes();
							$eqLogic->setEqType_name('wes');
							$eqLogic->setLogicalId($this->getId().$data['logical'].$id);
							$eqLogic->setName($data['name'] . ' ' . $id);
							$eqLogic->setConfiguration('type',$type);
							$eqLogic->save();
						} else if (is_object(self::byLogicalId($this->getId().$data['logical'].$id, 'wes')) && $this->getConfiguration($type.$id,1)==0) {
							self::byLogicalId($this->getId().$data['logical'].$id, 'wes')->remove();
						}
						$id ++;
						$xpathModele = str_replace('#id#',$id,$data['xpath']);
						$status = $this->xmlstatus->xpath($xpathModele);
					}
				}
			}
		}
	}

	public function preRemove() {
		foreach (self::byType('wes') as $eqLogic) {
			if ( substr($eqLogic->getLogicalId(), 0, strpos($eqLogic->getLogicalId(),"_")) == $this->getId() ) {
				log::add('wes','debug','Suppression '.$eqLogic->getConfiguration('type').' : '.$eqLogic->getName());
				$eqLogic->remove();
			}
		}
	}

	public function getLinkToConfiguration() {
			return 'index.php?v=d&p=wes&m=wes&id=' . $this->getId();
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
				$wesid = substr($eqLogic->getLogicalId(), strpos($eqLogic->getLogicalId(),"_")+2);
				if ( $eqLogic->getConfiguration('type') == 'bouton' ) {
					$cmd = $eqLogic->getCmd(null, 'state');
					log::add('wes','debug','Url program.cgi?PRG='.$compteurId.','.($wesid+30).',0,0,1,0,1,2,0,1,4,0000,0000,9,0');
					$this->getUrl('program.cgi?PRG='.$compteurId.','.($wesid+30).',0,0,1,0,1,2,0,1,4,0000,0000,9,0');
					$this->getUrl('program.cgi?RQT'.$compteurId.'='.$pathjeedom.'core/api/jeeApi.php?api='.jeedom::getApiKey('wes').'%26type=wes%26id='.$cmd->getId().'%26value=$I'.$wesid.'00');
					log::add('wes','debug','Url program.cgi?RQT'.$compteurId.'='.$pathjeedom.'core/api/jeeApi.php?api='.jeedom::getApiKey('wes').'%26type=wes%26id='.$cmd->getId().'%26value=$I'.$wesid.'00');
					$compteurId++;
					$this->getUrl('program.cgi?PRG='.$compteurId.','.($wesid+30).',0,0,0,0,1,2,0,1,4,0000,0000,9,0');
					$this->getUrl('program.cgi?RQT'.$compteurId.'='.$pathjeedom.'core/api/jeeApi.php?api='.jeedom::getApiKey('wes').'%26type=wes%26id='.$cmd->getId().'%26value=$I'.$wesid.'00');
					$compteurId++;
				}elseif ($eqLogic->getConfiguration('type') == 'relai') {
					$cmd = $eqLogic->getCmd(null, 'state');
					$this->getUrl('program.cgi?PRG='.$compteurId.','.($wesid+100).',0,0,1,0,1,2,0,1,4,0000,0000,9,0');
					$wesid = sprintf("%03d", $wesid);
					$this->getUrl('program.cgi?RQT'.$compteurId.'='.$pathjeedom.'core/api/jeeApi.php?api='.jeedom::getApiKey('wes').'%26type=wes%26id='.$cmd->getId().'%26value=$R'.$wesid);
					$compteurId++;
					$this->getUrl('program.cgi?PRG='.$compteurId.','.($wesid+100).',0,0,0,0,1,2,0,1,4,0000,0000,9,0');
					$wesid = sprintf("%03d", $wesid);
					$this->getUrl('program.cgi?RQT'.$compteurId.'='.$pathjeedom.'core/api/jeeApi.php?api='.jeedom::getApiKey('wes').'%26type=wes%26id='.$cmd->getId().'%26value=$R'.$wesid);
					$compteurId++;
				}elseif ($eqLogic->getConfiguration('type') == 'switch') {
					$cmd = $eqLogic->getCmd(null, 'state');
					$this->getUrl('program.cgi?PRG='.$compteurId.','.($wesid+500).',0,0,1,0,1,2,0,1,4,0000,0000,9,0');
					$wesid = sprintf("%03d", $wesid);
					$this->getUrl('program.cgi?RQT'.$compteurId.'='.$pathjeedom.'core/api/jeeApi.php?api='.jeedom::getApiKey('wes').'%26type=wes%26id='.$cmd->getId().'%26value=$V'.$wesid);
					$compteurId++;
					$this->getUrl('program.cgi?PRG='.$compteurId.','.($wesid+500).',0,0,0,0,1,2,0,1,4,0000,0000,9,0');
					$wesid = sprintf("%03d", $wesid);
					$this->getUrl('program.cgi?RQT'.$compteurId.'='.$pathjeedom.'core/api/jeeApi.php?api='.jeedom::getApiKey('wes').'%26type=wes%26id='.$cmd->getId().'%26value=$V'.$wesid);
					$compteurId++;
				}elseif ($eqLogic->getConfiguration('type') == 'teleinfo') {
					$url .= 'protect/settings/notif'.$wesid.'P.htm';
					for ($compteur = 0; $compteur < 6; $compteur++) {
						log::add('wes','debug','Url '.$url);
						$data = array('num' => $compteur + ($wesid -1)*6,
								'act' => $compteur+3,
								'serv' => config::byKey('internalAddr'),
								'port' => 80,
								'url' => '/jeedom/core/api/jeeApi.php?api='.jeedom::getApiKey('wes').'&type=wes&id='.substr($this->getLogicalId(), 0, strpos($this->getLogicalId(),"_")).'&message=data_change');
			//					'url' => '/jeedom/core/api/jeeApi.php?api='.jeedom::getApiKey('wes').'&type=wes_teleinfo&id='.$this->getId().'&message=data_change');

						$options = array(
							'http' => array(
								'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
								'method'  => 'POST',
								'content' => http_build_query($data),
							),
						);
						$context  = stream_context_create($options);
						$result = @file_get_contents($url, false, $context);
					}
				}
			}
		}
	}

	public function event() {
		foreach (eqLogic::byType('wes') as $eqLogic) {
			if($eqLogic->getConfiguration('type') == "general"){
				if ( $eqLogic->getId() == init('id')) {
					$eqLogic->pull();
				}
			}else {
				$cmd = wesCmd::byId(init('id'));
        if (!is_object($cmd)) {
            throw new Exception('Commande ID virtuel inconnu : ' . init('id'));
        }
				if($cmd->getConfiguration('type') == "analogique"){
					$value=init('voltage');
				}elseif ($cmd->getConfiguration('type') == "bouton") {
					$value=init('state');
				}elseif ($cmd->getConfiguration('type') == "temperature") {
					$value=init('reel');
				}else {
					$value=init('value');
				}
				if ($cmd->execCmd() != $cmd->formatValue($value)) {
					$cmd->setCollectDate('');
					$cmd->event($value);
				}
			}
		}
	}

	public function pull() {
		if ( $this->getIsEnable() && $this->getConfiguration('type') == "general" ) {
			log::add('wes','debug','pull '.$this->getName());
			$file = 'data.cgx';
			if ($this->getConfiguration('usecustomcgx',0) == 1) {
				$file = 'data_jeedom.cgx';
			}
			log::add('wes','debug','get ' . $file);
			$this->xmlstatus = simplexml_load_string($this->getUrl($file));
			$count = 0;
			while ( $this->xmlstatus === false && $count < 3 ) {
				log::add('wes','debug','reget ' . $file);
				$this->xmlstatus = simplexml_load_string($this->getUrl($file));
				$count++;
			}
			if ( $this->xmlstatus === false ) {
				$this->checkAndUpdateCmd('status', 0);
				log::add('wes','error',__('Le wes ne repond pas.',__FILE__)." ".$this->getName()." get " . $file);
				return false;
			}
			$this->checkAndUpdateCmd('status', 1);
			foreach (self::byType('wes') as $eqLogic) {
				if ($eqLogic->getIsEnable() && ($eqLogic->getId() == $this->getId() || substr($eqLogic->getLogicalId(), 0, strpos($eqLogic->getLogicalId(),"_")) == $this->getId() )) {
					$wesid = substr($eqLogic->getLogicalId(), strpos($eqLogic->getLogicalId(),"_")+2);
					foreach (self::getListeCommandes()[$eqLogic->getConfiguration('type','')] as $logical => $details) {
						if (isset($details['xpath']) && $details['xpath'] != ''){
							$xpath = $details['xpath'];
							if (isset($details['cond'])) {
								$cond = str_replace('#id#',$wesid,$details['cond']);
								$test = eval("return " . $cond .";");
								if ($test){
									$xpath = $details['xpathcond'];
								}
							}
							$xpathModele = str_replace('#id#',$wesid,$xpath);
							$status = $this->xmlstatus->xpath($xpathModele);
							$value = (string) $status[0];
							if (count($status) != 0){
								if ($eqLogic->getConfiguration('type','') == 'relai' && $logical == 'state'){
									$value = ($value == 'ON') ? 1 :0;
								}
								$eqLogic->checkAndUpdateCmd($logical, $value);
							}
						}
					}
				}
			}
			log::add('wes','debug','pull end '.$this->getName());
		}
	}
    /*     * **********************Getteur Setteur*************************** */
}

class wesCmd extends cmd {
	
	public function preSave() {
        if ( $this->getLogicalId() == 'reel' && $this->getConfiguration('type') == 'analogique') {
            $this->setValue('');
            $calcul = $this->getConfiguration('calcul');
            preg_match_all("/#([0-9]*)#/", $calcul, $matches);
            $value = '';
            foreach ($matches[1] as $cmd_id) {
                if (is_numeric($cmd_id)) {
                    $cmd = self::byId($cmd_id);
                    if (is_object($cmd) && $cmd->getType() == 'info') {
                        $value .= '#' . $cmd_id . '#';
                        break;
                    }
                }
            }
			$this->setConfiguration('calcul', $calcul);

            $this->setValue($value);
        }
    }

	public function getUrlPush() {
			if ( config::byKey('internalAddr') == "" ) {
				throw new Exception(__('L\'adresse IP du serveur Jeedom doit être renseignée.',__FILE__));
			}
			$pathjeedom = preg_replace("/plugins.*$/", "", $_SERVER['PHP_SELF']);
			if ( substr($pathjeedom, 0, 1) != "/" ) {
				$pathjeedom = "/".$pathjeedom;
			}
			if ( substr($pathjeedom, -1) != "/" ) {
				$pathjeedom = $pathjeedom."/";
			}
			$eqLogic = $this->getEqLogic();
			$wesid = substr($eqLogic->getLogicalId(), strpos($eqLogic->getLogicalId(),"_")+2);
			$url = 'http';
			if (  isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on" )
				$url .= 's';
			$url .= '://'.config::byKey('internalAddr').$pathjeedom.'core/api/jeeApi.php?api='.jeedom::getApiKey('wes').'&type=wes&id='.$this->getId().'&value=';
			if ( $this->getLogicalId() == 'reel' && $this->getConfiguration('type') == 'analogique') {
				$url .= '$E01'.$wesid;
			}
			if ( $this->getLogicalId() == 'state' && $this->getConfiguration('type') == 'bouton') {
				$url .= '$I'.$wesid.'00';
			}
			if ( $this->getLogicalId() == 'state' && $this->getConfiguration('type') == 'relai' ) {
				$url .= '$R'.$wesid.'00';
			}
			if ( $this->getLogicalId() == 'state' && $this->getConfiguration('type') == 'switch' ) {
				$url .= '$V'.$wesid.'00';
			}
			if ( $this->getLogicalId() == 'debit' ) {
				$url .= '$P'.$wesid.'00';
			}
			if ( $this->getLogicalId() == 'index' ) {
				$url .= '$P'.$wesid.'01';
			}
			if ( $this->getLogicalId() == 'puissance' ) {
				$url .= '$A'.$wesid.'00';
			}
			if ( $this->getLogicalId() == 'intensite' ) {
				$url .= '$A'.$wesid.'01';
			}
			if ( $this->getLogicalId() == 'reel' && $this->getConfiguration('type') == 'temperature' ) {
				$url .= '$W0'.$wesid;
			}
			return $url;
		}

	public function execute($_options = null) {
		log::add('wes','debug','execute '.$_options);
		$eqLogic = $this->getEqLogic();
		if (!is_object($eqLogic) || $eqLogic->getIsEnable() != 1) {
			throw new Exception(__('Equipement desactivé impossible d\éxecuter la commande : ' . $this->getHumanName(), __FILE__));
		}
		$weseqLogic = eqLogic::byId(substr ($eqLogic->getLogicalId(), 0, strpos($eqLogic->getLogicalId(),"_")));
		$wesid = substr($eqLogic->getLogicalId(), strpos($eqLogic->getLogicalId(),"_")+2);
		if($eqLogic->getConfiguration('type') == 'general'){
			if ( $this->getLogicalId() == 'alarmeon') {
				$file = 'AJAX.cgx?alarme=ON';
			} else if ( $this->getLogicalId() == 'alarmeoff' ){
				$file = 'AJAX.cgx?alarme=OFF';
			} else {
				return false;
			}
			$eqLogic->getUrl($file);
			return;
		} else if ($eqLogic->getConfiguration('type') == "relai") {
			log::add('wes','debug','execute '.$this->getLogicalId());
			if ( $this->getLogicalId() == 'btn_on' ){
				$file .= 'RL.cgi?rl'.$wesid.'=ON';
			} else if ( $this->getLogicalId() == 'btn_off' ){
				$file .= 'RL.cgi?rl'.$wesid.'=OFF';
			} else if ( $this->getLogicalId() == 'commute' ){
				$file .= 'RL.cgi?frl='.$wesid;
			} else {
				return false;
			}
			$weseqLogic->getUrl($file);
			return;
		} elseif ($eqLogic->getConfiguration('type') == "switch") {
			if ( $this->getLogicalId() == 'btn_on' ){
				$file .= 'AJAX.cgx?vs'.$wesid.'=ON';
			} else if ( $this->getLogicalId() == 'btn_off' ){
				$file .= 'AJAX.cgx?vs'.$wesid.'=OFF';
			}else if ( $this->getLogicalId() == 'commute' ){
				$file .= 'AJAX.cgx?fvs='.$wesid;
			} else {
				return false;
			}
			$weseqLogic->getUrl($file);
			return;
		} elseif ($eqLogic->getConfiguration('type') == "analogique") {
				if ($this->getLogicalId() == 'reel') {
				try {
					$calcul = $this->getConfiguration('calcul');
					if ( preg_match("/#brut#/", $calcul) ) {
						$EqLogic = $this->getEqLogic();
						$brut = $EqLogic->getCmd(null, 'brut');
						$calcul = preg_replace("/#brut#/", "#".$brut->getId()."#", $calcul);
					}
					$calcul = scenarioExpression::setTags($calcul);
					$result = jeedom::evaluateExpression($calcul);
					if (is_numeric($result)) {
						$result = number_format($result, 2);
					} else {
						$result = str_replace('"', '', $result);
					}
					if ($this->getSubType() == 'numeric') {
						if (strpos($result, '.') !== false) {
							$result = str_replace(',', '', $result);
						} else {
							$result = str_replace(',', '.', $result);
						}
					}
					return $result;
				} catch (Exception $e) {
					$EqLogic = $this->getEqLogic();
					log::add('wes', 'error', $EqLogic->getName()." error in ".$this->getConfiguration('calcul')." : ".$e->getMessage());
					return scenarioExpression::setTags(str_replace('"', '', cmd::cmdToValue($this->getConfiguration('calcul'))));
				}
			} else {
				return $this->getConfiguration('value');
			}
		}elseif ($eqLogic->getConfiguration('type') == "bouton") {
			log::add('wes','debug','execute '.$_options);
			$eqLogic = $this->getEqLogic();
	        if (!is_object($eqLogic) || $eqLogic->getIsEnable() != 1) {
	            throw new Exception(__('Equipement desactivé impossible d\éxecuter la commande : ' . $this->getHumanName(), __FILE__));
	        }
			$weseqLogic = eqLogic::byId(substr ($eqLogic->getLogicalId(), 0, strpos($eqLogic->getLogicalId(),"_")));
			$wesid = substr($eqLogic->getLogicalId(), strpos($eqLogic->getLogicalId(),"_")+2);
			$url = $weseqLogic->getUrl();
			if ( $this->getLogicalId() == 'btn_on' )
				$url .= 'leds.cgi?set='.$wesid;
			else if ( $this->getLogicalId() == 'btn_off' )
				$url .= 'leds.cgi?clear='.$wesid;
			else
				return false;

			$result = @file_get_contents($url);
			log::add('wes','debug',"get ".preg_replace("/:[^:]*@/", ":XXXX@", $url));
			$count = 0;
			while ( $result === false && $count < 3 ) {
				$result = @file_get_contents($url);
				$count++;
			}
			if ( $result === false ) {
				throw new Exception(__('Le wes ne repond pas.',__FILE__)." ".$weseqLogic->getName());
			}
	        return false;
		}
	}
}
?>
