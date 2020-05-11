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

class wes_teleinfo extends eqLogic {
    /*     * *************************Attributs****************************** */

    /*     * ***********************Methode static*************************** */
	private function getListeDefaultCommandes()
	{
		return array("ADCO" => array('Numero compteur', 'numeric', '', 0, ""),
		"OPTARIF" => array('Option tarif', 'string', '', 1, ""),
		"ISOUSC" => array('Intensité souscrite', 'numeric', 'A', 0, ""),
		"PTEC" => array('Tarif en cours', 'string', '', 1, ""),
		"PAP" => array('Puissance Apparente', 'numeric', 'W', 1, ""),
		"IINST" => array('Intensité instantanée', 'numeric', 'A', 1, ""),
		"IINST1" => array('Intensité instantanée 1', 'numeric', 'A', 0, ""),
		"IINST2" => array('Intensité instantanée 2', 'numeric', 'A', 0, ""),
		"IINST3" => array('Intensité instantanée 3', 'numeric', 'A', 0, ""),
		"IMAX" => array('Intensité maximum', 'numeric', 'A', 1, ""),
		"IMAX1" => array('Intensité maximum 1', 'numeric', 'A', 0, ""),
		"IMAX2" => array('Intensité maximum 2', 'numeric', 'A', 0, ""),
		"IMAX3" => array('Intensité maximum 3', 'numeric', 'A', 0, ""),
		"PEJP" => array('Préavis EJP', 'binary', '', 0, "EJP"),
		"DEMAIN" => array('Couleur demain', 'string', '', 0, "BBRH"),
		"BASE" => array('Index (base)', 'numeric', 'W', 1, "BASE"),
		"HCHC" => array('Index (heures creuses)', 'numeric', 'W', 1, "HC"),
		"HCHP" => array('Index (heures pleines)', 'numeric', 'W', 1, "HC"),
		"EJPHN" => array('Index (normal EJP)', 'numeric', 'W', 0, "EJP"),
		"EJPHPM" => array('Index (pointe mobile EJP)', 'numeric', 'W', 0, "EJP"),
		"BBRHCJB" => array('Index (heures creuses jours bleus Tempo)', 'numeric', 'W', 0, "BBRH"),
		"BBRHPJB" => array('Index (heures pleines jours bleus Tempo)', 'numeric', 'W', 0, "BBRH"),
		"BBRHCJW" => array('Index (heures creuses jours blancs Tempo)', 'numeric', 'W', 0, "BBRH"),
		"BBRHPJW" => array('Index (heures pleines jours blancs Tempo)', 'numeric', 'W', 0, "BBRH"),
		"BBRHCJR" => array('Index (heures creuses jours rouges Tempo)', 'numeric', 'W', 0, "BBRH"),
		"BBRHPJR" => array('Index (heures pleines jours rouges Tempo)', 'numeric', 'W', 0, "BBRH"),
		"BASE_evolution" => array('Evolution index (base)', 'numeric', 'W/min', 1, "BASE"),
		"HCHC_evolution" => array('Evolution index (heures creuses)', 'numeric', 'W/min', 1, "HC"),
		"HCHP_evolution" => array('Evolution index (heures pleines)', 'numeric', 'W/min', 1, "HC"),
		"BBRHCJB_evolution" => array('Evolution index (heures creuses jours bleus Tempo)', 'numeric', 'W/min', 0, "BBRH"),
		"BBRHPJB_evolution" => array('Evolution index (heures pleines jours bleus Tempo)', 'numeric', 'W/min', 0, "BBRH"),
		"BBRHCJW_evolution" => array('Evolution index (heures creuses jours blancs Tempo)', 'numeric', 'W/min', 0, "BBRH"),
		"BBRHPJW_evolution" => array('Evolution index (heures pleines jours blancs Tempo)', 'numeric', 'W/min', 0, "BBRH"),
		"BBRHCJR_evolution" => array('Evolution index (heures creuses jours rouges Tempo)', 'numeric', 'W/min', 0, "BBRH"),
		"BBRHPJR_evolution" => array('Evolution index (heures pleines jours rouges Tempo)', 'numeric', 'W/min', 0, "BBRH"),
		"EJPHN_evolution" => array('Evolution index (normal EJP)', 'numeric', 'W', 0, "EJP"),
		"EJPHPM_evolution" => array('Evolution index (pointe mobile EJP)', 'numeric', 'W', 0, "EJP"));
	}

	public function preInsert()
	{
		$this->setEqType_name('wes_teleinfo');
		$this->setIsEnable(0);
		$this->setIsVisible(0);
	}

	public function postInsert()
	{
		foreach( $this->getListeDefaultCommandes() as $label => $data)
		{
			if ( $this->getConfiguration('tarification') == $data[4] || $data[4] == "" ) {
				$cmd = $this->getCmd(null, $label);
				if ( ! is_object($cmd) ) {
					$cmd = new wes_teleinfoCmd();
					$cmd->setName($data[0]);
					$cmd->setEqLogic_id($this->getId());
					$cmd->setType('info');
					$cmd->setSubType($data[1]);
					$cmd->setLogicalId($label);
					$cmd->setUnite($data[2]);
					$cmd->setIsVisible($data[3]);
					$cmd->setEventOnly(1);
					$cmd->save();
				}
			} else {
				$cmd = $this->getCmd(null, $label);
				if ( is_object($cmd) ) {
					$cmd->remove();
				}
			}
		}
	}

	public function postUpdate() {
		foreach( $this->getListeDefaultCommandes() as $label => $data)
		{
			if ( $this->getConfiguration('tarification') == "" || $this->getConfiguration('tarification') == $data[4] || $data[4] == "" ) {
				$cmd = $this->getCmd(null, $label);
				if ( ! is_object($cmd) ) {
					$cmd = new wes_teleinfoCmd();
					$cmd->setName($data[0]);
					$cmd->setEqLogic_id($this->getId());
					$cmd->setType('info');
					$cmd->setSubType($data[1]);
					$cmd->setLogicalId($label);
					$cmd->setUnite($data[2]);
					$cmd->setIsVisible($data[3]);
					$cmd->setEventOnly(1);
					$cmd->save();
				}
			} else {
				$cmd = $this->getCmd(null, $label);
				if ( is_object($cmd) ) {
					$cmd->remove();
				}
			}
		}
	}

    public static function event() {
        $cmd = wes_teleinfoCmd::byId(init('id'));
        if (!is_object($cmd)) {
            throw new Exception('Commande ID virtuel inconnu : ' . init('id'));
        }
		$cmd->event(init('value'));
    }

	public function configPush($url) {
		$wesid = substr($this->getLogicalId(), strpos($this->getLogicalId(),"_")+2, 1);
		$url .= 'protect/settings/notif'.$wesid.'P.htm';
		for ($compteur = 0; $compteur < 6; $compteur++) {
			log::add('wes','debug','Url '.$url);
			$data = array('num' => $compteur + ($wesid -1)*6,
					'act' => $compteur+3,
					'serv' => config::byKey('internalAddr'),
					'port' => 80,
					'url' => '/jeedom/core/api/jeeApi.php?api='.config::byKey('api').'&type=wes&id='.substr($this->getLogicalId(), 0, strpos($this->getLogicalId(),"_")).'&message=data_change');
//					'url' => '/jeedom/core/api/jeeApi.php?api='.config::byKey('api').'&type=wes_teleinfo&id='.$this->getId().'&message=data_change');
			
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

    public function getLinkToConfiguration() {
        return 'index.php?v=d&p=wes&m=wes&id=' . $this->getId();
    }
    /*     * **********************Getteur Setteur*************************** */
}

class wes_teleinfoCmd extends cmd 
{
    /*     * *************************Attributs****************************** */


    /*     * ***********************Methode static*************************** */

    /*     * *********************Methode d'instance************************* */

    /*     * **********************Getteur Setteur*************************** */
}
?>
