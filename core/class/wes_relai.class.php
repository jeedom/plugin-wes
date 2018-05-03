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

class wes_relai extends eqLogic {
    /*     * *************************Attributs****************************** */

    /*     * ***********************Methode static*************************** */
	public function postInsert()
	{
        $state = $this->getCmd(null, 'state');
        if ( ! is_object($state) ) {
            $state = new wes_relaiCmd();
			$state->setName('Etat');
			$state->setEqLogic_id($this->getId());
			$state->setType('info');
			$state->setSubType('binary');
			$state->setLogicalId('state');
			$state->setEventOnly(1);
			$state->save();
		}
        $btn_on = $this->getCmd(null, 'btn_on');
        if ( ! is_object($btn_on) ) {
            $btn_on = new wes_relaiCmd();
			$btn_on->setName('On');
			$btn_on->setEqLogic_id($this->getId());
			$btn_on->setType('action');
			$btn_on->setSubType('other');
			$btn_on->setLogicalId('btn_on');
			$btn_on->setEventOnly(1);
			$btn_on->save();
		}
        $btn_off = $this->getCmd(null, 'btn_off');
        if ( ! is_object($btn_off) ) {
            $btn_off = new wes_relaiCmd();
			$btn_off->setName('Off');
			$btn_off->setEqLogic_id($this->getId());
			$btn_off->setType('action');
			$btn_off->setSubType('other');
			$btn_off->setLogicalId('btn_off');
			$btn_off->setEventOnly(1);
			$btn_off->save();
		}
        $commute = $this->getCmd(null, 'commute');
        if ( ! is_object($commute) ) {
            $commute = new wes_relaiCmd();
			$commute->setName('Commute');
			$commute->setEqLogic_id($this->getId());
			$commute->setType('action');
			$commute->setSubType('other');
			$commute->setLogicalId('commute');
			$commute->setEventOnly(1);
			$commute->save();
		}
/*        $impulsion = $this->getCmd(null, 'impulsion');
        if ( ! is_object($impulsion) ) {
            $impulsion = new wes_relaiCmd();
			$impulsion->setName('Impulsion');
			$impulsion->setEqLogic_id($this->getId());
			$impulsion->setType('action');
			$impulsion->setSubType('other');
			$impulsion->setLogicalId('impulsion');
			$impulsion->setEventOnly(1);
			$impulsion->save();
		}
*/
	}

	public function postUpdate()
	{
        $commute = $this->getCmd(null, 'commute');
        if ( ! is_object($commute) ) {
            $commute = new wes_relaiCmd();
			$commute->setName('Commute');
			$commute->setEqLogic_id($this->getId());
			$commute->setType('action');
			$commute->setSubType('other');
			$commute->setLogicalId('commute');
			$commute->setEventOnly(1);
			$commute->save();
		}

	}

	public function preInsert()
	{
		$this->setEqType_name('wes_relai');
		$this->setIsEnable(0);
		$this->setIsVisible(0);
	}

    public static function event() {
        $cmd = wes_relaiCmd::byId(init('id'));
        if (!is_object($cmd)) {
            throw new Exception('Commande ID virtuel inconnu : ' . init('id'));
        }
		if ($cmd->execCmd() != $cmd->formatValue(init('value'))) {
			$cmd->setCollectDate('');
			$cmd->event(init('value'));
		}
    }

	public function configPush($wes_eqLogic, $compteurId, $pathjeedom) {
		$wesid = substr($this->getLogicalId(), strpos($this->getLogicalId(),"_")+2);
		$cmd = $this->getCmd(null, 'state');
		$wes_eqLogic->getUrl('program.cgi?PRG='.$compteurId.','.($wesid+100).',0,0,1,0,1,2,0,1,4,0000,0000,9,0');
		$wesid = sprintf("%03d", $wesid);
		$wes_eqLogic->getUrl('program.cgi?RQT'.$compteurId.'='.$pathjeedom.'core/api/jeeApi.php?api='.config::byKey('api').'%26type=wes_relai%26id='.$cmd->getId().'%26value=$R'.$wesid);
		$compteurId++;
		$wes_eqLogic->getUrl('program.cgi?PRG='.$compteurId.','.($wesid+100).',0,0,0,0,1,2,0,1,4,0000,0000,9,0');
		$wesid = sprintf("%03d", $wesid);
		$wes_eqLogic->getUrl('program.cgi?RQT'.$compteurId.'='.$pathjeedom.'core/api/jeeApi.php?api='.config::byKey('api').'%26type=wes_relai%26id='.$cmd->getId().'%26value=$R'.$wesid);
		return $compteurId;
	}

    public function getLinkToConfiguration() {
        return 'index.php?v=d&p=wes&m=wes&id=' . $this->getId();
    }
    /*     * **********************Getteur Setteur*************************** */
}

class wes_relaiCmd extends cmd 
{
    /*     * *************************Attributs****************************** */


    /*     * ***********************Methode static*************************** */


    /*     * *********************Methode d'instance************************* */

    public function execute($_options = null) {
		log::add('wes','debug','execute '.$_options);
		$eqLogic = $this->getEqLogic();
        if (!is_object($eqLogic) || $eqLogic->getIsEnable() != 1) {
            throw new Exception(__('Equipement desactivé impossible d\éxecuter la commande : ' . $this->getHumanName(), __FILE__));
        }
		$weseqLogic = eqLogic::byId(substr ($eqLogic->getLogicalId(), 0, strpos($eqLogic->getLogicalId(),"_")));
		$wesid = substr($eqLogic->getLogicalId(), strpos($eqLogic->getLogicalId(),"_")+2);
		if ( $this->getLogicalId() == 'btn_on' )
			$file .= 'RL.cgi?rl'.($wesid).'=ON';
		else if ( $this->getLogicalId() == 'btn_off' )
			$file .= 'RL.cgi?rl'.($wesid).'=OFF';
/*		else if ( $this->getLogicalId() == 'impulsion' )
			$file .= 'preset.htm?RLY'.($wesid+1).'=1';*/
		else if ( $this->getLogicalId() == 'commute' )
			$file .= 'RL.cgi?frl='.$wesid;
		else
			return false;
		$weseqLogic->getUrl($file);
        return false;
    }
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
		$url .= '://'.config::byKey('internalAddr').$pathjeedom.'core/api/jeeApi.php?api='.config::byKey('api').'&type=wes_relai&id='.$this->getId().'&value=';
		if ( $this->getLogicalId() == 'state' ) {
			$url .= '$R'.$wesid.'00';
		}
		
		return $url;
	}
    public function imperihomeCmd() {
 		if ( $this->getLogicalId() == 'state' ) {
			return true;
		}
		elseif ( $this->getLogicalId() == 'impulsion' ) {
			return true;
		}
		elseif ( $this->getLogicalId() == 'commute' ) {
			return true;
		}
		else {
			return false;
		}
    }

	public function imperihomeGenerate($ISSStructure) {
		if ( $this->getLogicalId() == 'state' ) { // Sauf si on est entrain de traiter la commande "Mode", à ce moment là on indique un autre type
			$type = 'DevSwitch'; // Le type Imperihome qui correspond le mieux à la commande
		}
		else {
			return $info_device;
		}
		$eqLogic = $this->getEqLogic(); // Récupération de l'équipement de la commande
		$object = $eqLogic->getObject(); // Récupération de l'objet de l'équipement

		// Construction de la structure de base
		$info_device = array(
		'id' => $this->getId(), // ID de la commande, ne pas mettre autre chose!
		'name' => $eqLogic->getName()." - ".$this->getName(), // Nom de l'équipement que sera affiché par Imperihome: mettre quelque chose de parlant...
		'room' => (is_object($object)) ? $object->getId() : 99999, // Numéro de la pièce: ne pas mettre autre chose que ce code
		'type' => $type, // Type de l'équipement à retourner (cf ci-dessus)
		'params' => array(), // Le tableau des paramètres liés à ce type (qui sera complété aprés.
		);
		#$info_device['params'] = $ISSStructure[$info_device['type']]['params']; // Ici on vient copier la structure type: laisser ce code

		array_push ($info_device['params'], array("value" =>  '#' . $eqLogic->getCmd(null, 'state')->getId() . '#', "key" => "status", "type" => "infoBinary", "Description" => "Current status : 1 = On / 0 = Off"));
		$info_device['actions']["setStatus"]["item"]["0"] = $eqLogic->getCmd(null, 'btn_off')->getId();
		$info_device['actions']["setStatus"]["item"]["1"] = $eqLogic->getCmd(null, 'btn_on')->getId();
		// Ici on traite les autres commandes (hors "Mode")
		return $info_device;
	}
   /*     * **********************Getteur Setteur*************************** */
   public function imperihomeAction($_action, $_value) {
      	$eqLogic = $this->getEqLogic();
		if ($this->getLogicalId() == 'state') {
		    if ($_value == '0') {
				$eqLogic->getCmd(null, 'btn_off')->execCmd();
		    } else {
				$eqLogic->getCmd(null, 'btn_on')->execCmd();
		    }
		}
   }
}
?>