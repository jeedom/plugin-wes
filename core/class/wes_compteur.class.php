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

class wes_compteur extends eqLogic {
    /*     * *************************Attributs****************************** */

    /*     * ***********************Methode static*************************** */
	public function postInsert()
	{
        $nbimpulsion = $this->getCmd(null, 'nbimpulsion');
        if ( ! is_object($nbimpulsion) ) {
            $nbimpulsion = new wes_compteurCmd();
			$nbimpulsion->setName('Nombre d impulsion');
			$nbimpulsion->setEqLogic_id($this->getId());
			$nbimpulsion->setType('info');
			$nbimpulsion->setSubType('numeric');
			$nbimpulsion->setLogicalId('nbimpulsion');
			$nbimpulsion->setEventOnly(1);
			$nbimpulsion->save();
		}
        $nbimpulsionminute = $this->getCmd(null, 'nbimpulsionminute');
        if ( ! is_object($nbimpulsionminute) ) {
            $nbimpulsionminute = new wes_compteurCmd();
			$nbimpulsionminute->setName('Nombre d impulsion par minute');
			$nbimpulsionminute->setEqLogic_id($this->getId());
			$nbimpulsionminute->setType('info');
			$nbimpulsionminute->setSubType('numeric');
			$nbimpulsionminute->setLogicalId('nbimpulsionminute');
			$nbimpulsionminute->setUnite("Imp/min");
			$nbimpulsionminute->setEventOnly(1);
			$nbimpulsionminute->setConfiguration('calcul', '#brut#');
			$nbimpulsionminute->save();
		}
	}

	public function postUpdate()
	{
        $nbimpulsion = $this->getCmd(null, 'nbimpulsion');
        if ( ! is_object($nbimpulsion) ) {
            $nbimpulsion = new wes_compteurCmd();
			$nbimpulsion->setName('Nombre d impulsion');
			$nbimpulsion->setEqLogic_id($this->getId());
			$nbimpulsion->setType('info');
			$nbimpulsion->setSubType('numeric');
			$nbimpulsion->setLogicalId('nbimpulsion');
			$nbimpulsion->setEventOnly(1);
			$nbimpulsion->save();
		}
        $nbimpulsionminute = $this->getCmd(null, 'nbimpulsionminute');
        if ( ! is_object($nbimpulsionminute) ) {
            $nbimpulsionminute = new wes_compteurCmd();
			$nbimpulsionminute->setName('Nombre d impulsion par minute');
			$nbimpulsionminute->setEqLogic_id($this->getId());
			$nbimpulsionminute->setType('info');
			$nbimpulsionminute->setSubType('numeric');
			$nbimpulsionminute->setLogicalId('nbimpulsionminute');
			$nbimpulsionminute->setUnite("Imp/min");
			$nbimpulsionminute->setConfiguration('calcul', '#brut#');
			$nbimpulsionminute->setEventOnly(1);
			$nbimpulsionminute->save();
		}
	}

	public function preInsert()
	{
		$this->setEqType_name('wes_compteur');
		$this->setIsEnable(0);
		$this->setIsVisible(0);
	}

    public static function event() {
        $cmd = wes_compteurCmd::byId(init('id'));
        if (!is_object($cmd)) {
            throw new Exception('Commande ID virtuel inconnu : ' . init('id'));
        }
		if ($cmd->execCmd() != $cmd->formatValue(init('value'))) {
			$cmd->setCollectDate('');
			$cmd->event(init('value'));
		}
    }

    public function getLinkToConfiguration() {
        return 'index.php?v=d&p=wes&m=wes&id=' . $this->getId();
    }
    /*     * **********************Getteur Setteur*************************** */
}

class wes_compteurCmd extends cmd 
{

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
		$url .= '://'.config::byKey('internalAddr').$pathjeedom.'core/api/jeeApi.php?api='.config::byKey('api').'&type=wes_compteur&id='.$this->getId.'&value=';
		if ( $this->getLogicalId() == 'debit' ) {
			$url .= '$P'.$wesid.'00';
		}
		if ( $this->getLogicalId() == 'index' ) {
			$url .= '$P'.$wesid.'01';
		}
		
		return $url;
	}
}
?>
