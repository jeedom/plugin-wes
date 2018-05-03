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

class wes_temperature extends eqLogic {
    /*     * *************************Attributs****************************** */

    /*     * ***********************Methode static*************************** */
	public function postUpdate()
	{
        $reel = $this->getCmd(null, 'reel');
        if ( ! is_object($reel) ) {
            $reel = new wes_temperatureCmd();
			$reel->setName('Réel');
			$reel->setEqLogic_id($this->getId());
			$reel->setType('info');
			$reel->setSubType('numeric');
			$reel->setLogicalId('reel');
			$reel->setEventOnly(1);
			$reel->save();
		}
	}

	public function postInsert()
	{
        $reel = $this->getCmd(null, 'reel');
        if ( ! is_object($reel) ) {
            $reel = new wes_temperatureCmd();
			$reel->setName('Réel');
			$reel->setEqLogic_id($this->getId());
			$reel->setType('info');
			$reel->setSubType('numeric');
			$reel->setLogicalId('reel');
			$reel->setEventOnly(1);
			$reel->save();
		}
	}

	public function preInsert()
	{
		$this->setEqType_name('wes_temperature');
		$this->setIsEnable(0);
		$this->setIsVisible(0);
	}

    public static function event() {
        $cmd = wes_temperatureCmd::byId(init('id'));
        if (!is_object($cmd)) {
            throw new Exception('Commande ID virtuel inconnu : ' . init('id'));
        }
		if ($cmd->execCmd() != $cmd->formatValue(init('reel'))) {
			$cmd->setCollectDate('');
			$cmd->event(init('reel'));
		}
    }

    public function getLinkToConfiguration() {
        return 'index.php?v=d&p=wes&m=wes&id=' . $this->getId();
    }
    /*     * **********************Getteur Setteur*************************** */
}

class wes_temperatureCmd extends cmd 
{
    /*     * *************************Attributs****************************** */


    /*     * ***********************Methode static*************************** */

    /*     * *********************Methode d'instance************************* */

    /*     * **********************Getteur Setteur*************************** */
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
		$url .= '://'.config::byKey('internalAddr').$pathjeedom.'core/api/jeeApi.php?api='.config::byKey('api').'&type=wes_temperature&id='.$this->getId().'&value=';
		if ( $this->getLogicalId() == 'reel' ) {
			$url .= '$W0'.$wesid;
		}
		
		return $url;
	}
}
?>
