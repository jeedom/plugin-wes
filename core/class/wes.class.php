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
				"ADCO" => array("name"=>__("Numéro compteur", __FILE__), "type" => "info", "subtype"=> "numeric", "dashboard" =>"line", "mobile" =>"line", "xpath" => "//tic#id#/ADCO", "order"=>1),
				"OPTARIF" => array("name"=>__("Option tarif", __FILE__), "type" => "info", "subtype"=> "string", "xpath" => "//tic#id#/OPTARIF", "order"=>2),
				"ISOUSC" => array("name"=>__("Intensité souscrite", __FILE__), "type" => "info", "subtype"=> "numeric", "unite" => "A", "dashboard" =>"line", "mobile" =>"line", "xpath" => "//tic#id#/ISOUSC", "order"=>3),
				"PTEC" => array("name"=>__("Tarif en cours", __FILE__), "type" => "info", "subtype"=> "string", "xpath" => "//tic#id#/PTEC", "order"=>4),
				"PAP" => array("name"=>__("Puissance Apparente", __FILE__), "type" => "info", "subtype"=> "numeric", "unite" => "VA", "xpath" => "//tic#id#/PAP", "dashboard"=>"tile", "mobile"=>"tile", "order"=>5),
				"IINST" => array("name"=>__("Intensité instantanée", __FILE__), "type" => "info", "subtype"=> "numeric", "unite" => "A", "xpath" => "//tic#id#/IINST", "dashboard"=>"tile", "mobile"=>"tile", "order"=>6),
				"IINST1" => array("name"=>__("Intensité instantanée 1", __FILE__), "type" => "info", "subtype"=> "numeric", "unite" => "A", "visible" => 0, "xpath" => "//tic#id#/IINST1", "dashboard"=>"tile", "mobile"=>"tile", "order"=>7),
				"IINST2" => array("name"=>__("Intensité instantanée 2", __FILE__), "type" => "info", "subtype"=> "numeric", "unite" => "A", "visible" => 0, "xpath" => "//tic#id#/IINST2", "dashboard"=>"tile", "mobile"=>"tile", "order"=>8),
				"IINST3" => array("name"=>__("Intensité instantanée 3", __FILE__), "type" => "info", "subtype"=> "numeric", "unite" => "A", "visible" => 0, "xpath" => "//tic#id#/IINST3", "dashboard"=>"tile", "mobile"=>"tile", "order"=>9),
				"IMAX" => array("name"=>__("Intensité maximum", __FILE__), "type" => "info", "subtype"=> "numeric", "unite" => "A", "xpath" => "//tic#id#/IMAX", "dashboard"=>"tile", "mobile"=>"tile", "order"=>10),
				"IMAX1" => array("name"=>__("Intensité maximum 1", __FILE__), "type" => "info", "subtype"=> "numeric", "unite" => "A", "visible" => 0, "xpath" => "//tic#id#/IMAX1", "dashboard"=>"tile", "mobile"=>"tile", "order"=>11),
				"IMAX2" => array("name"=>__("Intensité maximum 2", __FILE__), "type" => "info", "subtype"=> "numeric", "unite" => "A", "visible" => 0, "xpath" => "//tic#id#/IMAX2", "dashboard"=>"tile", "mobile"=>"tile", "order"=>12),
				"IMAX3" => array("name"=>__("Intensité maximum 3", __FILE__), "type" => "info", "subtype"=> "numeric", "unite" => "A", "visible" => 0, "xpath" => "//tic#id#/IMAX3", "dashboard"=>"tile", "mobile"=>"tile", "order"=>13),
				"TENS1" => array("name"=>__("Tension 1", __FILE__), "type" => "info", "subtype"=> "numeric", "unite" => "V", "xpath" => "//tic#id#/TENSION1", "dashboard"=>"tile", "mobile"=>"tile", "order"=>14),
				"TENS2" => array("name"=>__("Tension 2", __FILE__), "type" => "info", "subtype"=> "numeric", "unite" => "V", "visible" => 0, "xpath" => "//tic#id#/TENSION2", "dashboard"=>"tile", "mobile"=>"tile", "order"=>15),
				"TENS3" => array("name"=>__("Tension 3", __FILE__), "type" => "info", "subtype"=> "numeric", "unite" => "V", "visible" => 0, "xpath" => "//tic#id#/TENSION3", "dashboard"=>"tile", "mobile"=>"tile", "order"=>16),
				"PEJP" => array("name"=>__("Préavis EJP", __FILE__), "type" => "info", "subtype"=> "binary", "filter" => ["tarification"=>"EJP"], "xpath" => "//tic#id#/PEJP", "order"=>17),
				"DEMAIN" => array("name"=>__("Couleur demain", __FILE__), "type" => "info", "subtype"=> "string", "filter" => ["tarification"=>"BBRH"], "xpath" => "//tic#id#/DEMAIN", "order"=>18),
				"BASE" => array("name"=>__("Index (base)", __FILE__), "type" => "info", "subtype"=> "numeric", "unite" => "kWh", "filter" => ["tarification"=>"BASE"],"calcul"=>"#value#/1000", "dashboard" =>"tile", "mobile" =>"tile", "xpath" => "//tic#id#/BASE", "order"=>19),
				"HCHC" => array("name"=>__("Index (heures creuses)", __FILE__), "type" => "info", "subtype"=> "numeric", "unite" => "kWh", "filter" => ["tarification"=>"HC"],"calcul"=>"#value#/1000", "dashboard" =>"tile", "mobile" =>"tile","xpath" => "//tic#id#/HCHC", "order"=>20),
				"HCHP" => array("name"=>__("Index (heures pleines)", __FILE__), "type" => "info", "subtype"=> "numeric", "unite" => "kWh", "filter" => ["tarification"=>"HC"],"calcul"=>"#value#/1000", "dashboard" =>"tile", "mobile" =>"tile","xpath" => "//tic#id#/HCHP", "order"=>21),
				"EJPHN" => array("name"=>__("Index (normal EJP)", __FILE__), "type" => "info", "subtype"=> "numeric", "unite" => "kWh", "filter" => ["tarification"=>"EJP"],"calcul"=>"#value#/1000", "dashboard" =>"tile", "mobile" =>"tile","xpath" => "//tic#id#/EJPHN", "order"=>22),
				"EJPHPM" => array("name"=>__("Index (pointe mobile EJP)", __FILE__), "type" => "info", "subtype"=> "numeric", "unite" => "kWh", "filter" => ["tarification"=>"EJP"],"calcul"=>"#value#/1000", "dashboard" =>"tile", "mobile" =>"tile","xpath" => "//tic#id#/EJPHPM", "order"=>23),
				"BBRHCJB" => array("name"=>__("Index (heures creuses jours bleus Tempo)", __FILE__), "type" => "info", "subtype"=> "numeric", "unite" => "kWh", "filter"=>["tarification"=>"EJP"], "calcul"=>"#value#/1000", "dashboard"=>"tile", "mobile" =>"tile","xpath" => "//tic#id#/BBRHCJB", "order"=>24),
				"BBRHPJB" => array("name"=>__("Index (heures pleines jours bleus Tempo)", __FILE__), "type" => "info", "subtype"=> "numeric", "unite" => "kWh", "filter"=>["tarification"=>"EJP"], "calcul"=>"#value#/1000", "dashboard"=>"tile", "mobile" =>"tile","xpath" => "//tic#id#/BBRHPJB", "order"=>25),
				"BBRHCJW" => array("name"=>__("Index (heures creuses jours blancs Tempo)", __FILE__), "type" => "info", "subtype"=> "numeric", "unite" => "kWh", "filter"=>["tarification"=>"EJP"], "calcul"=>"#value#/1000", "dashboard"=>"tile", "mobile" =>"tile","xpath" => "//tic#id#/BBRHCJW", "order"=>26),
				"BBRHPJW" => array("name"=>__("Index (heures pleines jours blancs Tempo)", __FILE__), "type" => "info", "subtype"=> "numeric", "unite" => "kWh", "filter"=>["tarification"=>"EJP"], "calcul"=>"#value#/1000", "dashboard"=>"tile", "mobile" =>"tile","xpath" => "//tic#id#/BBRHPJW", "order"=>27),
				"BBRHCJR" => array("name"=>__("Index (heures creuses jours rouges Tempo)", __FILE__), "type" => "info", "subtype"=> "numeric", "unite" => "kWh", "filter"=>["tarification"=>"EJP"], "calcul"=>"#value#/1000", "dashboard"=>"tile", "mobile" =>"tile","xpath" => "//tic#id#/BBRHCJR", "order"=>28),
				"BBRHPJR" => array("name"=>__("Index (heures pleines jours rouges Tempo)", __FILE__), "type" => "info", "subtype"=> "numeric", "unite" => "kWh", "filter"=>["tarification"=>"EJP"], "calcul"=>"#value#/1000", "dashboard"=>"tile", "mobile" =>"tile","xpath" => "//tic#id#/BBRHPJR", "order"=>29),
				"CONSO_JOUR" => array("name"=>__("Consommation jour", __FILE__), "type" => "info", "subtype"=> "numeric", "unite" => "kWh", "xpath" => "//tic#id#/CONSO_JOUR", "filter"=>["usecustomcgx"=>1], "dashboard"=>"tile", "mobile"=>"tile", "order"=>30),
				"COUT_JOUR" => array("name"=>__("Coût jour", __FILE__), "type" => "info", "subtype"=> "numeric", "unite" => "€", "xpath" => "//tic#id#/COUT_JOUR", "filter"=>["usecustomcgx"=>1], "dashboard"=>"tile", "mobile"=>"tile", "order"=>31),
				"CONSO_MOIS" => array("name"=>__("Consommation mois", __FILE__), "type" => "info", "subtype"=> "numeric", "unite" => "kWh", "xpath" => "//tic#id#/CONSO_MOIS", "filter"=>["usecustomcgx"=>1], "dashboard"=>"tile", "mobile"=>"tile", "order"=>32),
				"COUT_MOIS" => array("name"=>__("Coût mois", __FILE__), "type" => "info", "subtype"=> "numeric", "unite" => "€", "xpath" => "//tic#id#/COUT_MOIS", "filter"=>["usecustomcgx"=>1], "dashboard"=>"tile", "mobile"=>"tile", "order"=>33),
				"CONSO_ANNEE" => array("name"=>__("Consommation année", __FILE__), "type" => "info", "subtype"=> "numeric", "unite" => "kWh", "xpath" => "//tic#id#/CONSO_ANNEE", "filter"=>["usecustomcgx"=>1], "dashboard"=>"tile", "mobile"=>"tile", "order"=>34),
				"COUT_ANNEE" => array("name"=>__("Coût année", __FILE__), "type" => "info", "subtype"=> "numeric", "unite" => "€", "xpath" => "//tic#id#/COUT_ANNEE", "filter"=>["usecustomcgx"=>1], "dashboard"=>"tile", "mobile"=>"tile", "order"=>35)
			),
			"general" => array(
				"firmware" => array("name"=>__("Firmware", __FILE__), "type"=>"info", "subtype"=>"string", "xpath"=>"//info/firmware", "order"=>1),
				"serverversion" => array("name"=>__("Version Serveur", __FILE__), "type"=>"info", "subtype"=>"string", "xpath"=>"//info/serverversion", "filter"=>["usecustomcgx"=>1], "order"=>2),
				"status" => array("name"=>__("Statut", __FILE__), "type"=>"info", "subtype"=>"binary", "order"=>3),
				"alarme" => array("name"=>__("Alarme", __FILE__), "type"=>"info", "subtype"=>"binary", "visible"=> 0, "xpath"=>"//info/alarme","dashboard"=>"alert", "mobile"=>"alert", "filter"=>["usecustomcgx"=>1], "order"=>4),
				"alarmeon" => array("name"=>__("Alarme On", __FILE__), "type"=>"action", "subtype"=>"other", "value"=>"alarme","dashboard"=>"alert", "mobile"=>"alert", "filter"=>["usecustomcgx"=>1], "order"=>5),
				"alarmeoff" => array("name"=>__("Alarme Off", __FILE__), "type"=>"action", "subtype"=>"other", "value"=>"alarme","dashboard"=>"alert", "mobile"=>"alert", "filter"=>["usecustomcgx"=>1], "order"=>6),
				"spaceleft" => array("name"=>__("Espace libre", __FILE__), "type"=>"info", "subtype"=>"numeric", "unite"=>"Go", "xpath"=>"//info/spaceleft", "filter"=>["usecustomcgx"=>1], "order"=>7),
				"tension" => array("name"=>__("Tension", __FILE__), "type"=>"info", "subtype"=>"numeric", "unite"=>"V", "minValue"=>200, "maxValue"=>260, "xpath"=>"//pince/V", "order"=>8)
			),
			"compteur" => array(
				"nbimpulsion" => array("name"=>__("Impulsions", __FILE__), "type"=>"info", "subtype"=>"numeric", "unite"=>"imp", "xpath"=>"//impulsion/PULSE#id#", "dashboard"=>"tile", "mobile"=>"tile", "order"=>1),
				"index" => array("name"=>__("Index", __FILE__), "type"=>"info", "subtype"=>"numeric", "unite"=>"l", "xpath"=>"//impulsion/INDEX#id#", "dashboard"=>"tile", "mobile"=>"tile", "order"=>2),
				"debit" => array("name"=>__("Débit", __FILE__), "type"=>"info", "subtype"=>"numeric", "unite"=>"l/min", "xpath"=>"//impulsion/DEBIT#id#", "dashboard"=>"tile", "mobile"=>"tile", "filter"=>["usecustomcgx"=>1], "order"=>3),
				"consoveille" => array("name"=>__("Consommation J-1", __FILE__), "type"=>"info", "subtype"=>"numeric", "unite"=>"l", "xpath"=>"//impulsion/CONSO_VEILLE#id#", "dashboard"=>"tile", "mobile"=>"tile", "filter"=>["usecustomcgx"=>1], "order"=>4),
				"consojour" => array("name"=>__("Consommation jour", __FILE__), "type"=>"info", "subtype"=>"numeric", "unite"=>"l", "xpath"=>"//impulsion/CONSO_JOUR#id#", "dashboard"=>"tile", "mobile"=>"tile", "filter"=>["usecustomcgx"=>1], "order"=>5),
				"coutjour" => array("name"=>__("Coût jour", __FILE__), "type"=>"info", "subtype"=>"numeric", "unite"=>"€", "xpath"=>"//impulsion/COUT_JOUR#id#", "dashboard"=>"tile", "mobile"=>"tile", "filter"=>["usecustomcgx"=>1], "order"=>6),
				"consomois" => array("name"=>__("Consommation mois", __FILE__), "type"=>"info", "subtype"=>"numeric", "unite"=>"m3", "xpath"=>"//impulsion/CONSO_MOIS#id#", "dashboard"=>"tile", "mobile"=>"tile", "filter"=>["usecustomcgx"=>1], "order"=>7),
				"coutmois" => array("name"=>__("Coût mois", __FILE__), "type"=>"info", "subtype"=>"numeric", "unite"=>"€", "xpath"=>"//impulsion/COUT_MOIS#id#", "dashboard"=>"tile", "mobile"=>"tile", "filter"=>["usecustomcgx"=>1], "order"=>8),
				"consoannee" => array("name"=>__("Consommation année", __FILE__), "type"=>"info", "subtype"=>"numeric", "unite"=>"m3", "xpath"=>"//impulsion/CONSO_ANNEE#id#", "dashboard"=>"tile", "mobile"=>"tile", "filter"=>["usecustomcgx"=>1], "order"=>9),
				"coutannee" => array("name"=>__("Coût année", __FILE__), "type"=>"info", "subtype"=>"numeric", "unite"=>"€", "xpath"=>"//impulsion/COUT_ANNEE#id#", "dashboard"=>"tile", "mobile"=>"tile", "filter"=>["usecustomcgx"=>1], "order"=>10)
			),
			"pince" => array(
				"index" => array("name"=>__("Index consommation", __FILE__), "type"=>"info", "subtype"=>"numeric", "unite"=>"kWh", "xpath"=>"//pince/INDEX#id#", "dashboard"=>"tile", "mobile"=>"tile", "consumption"=>1, "order"=>1),
				"injection" => array("name"=>__("Index injection", __FILE__), "type"=>"info", "subtype"=>"numeric", "unite"=>"kWh", "xpath"=>"//pince/INJECT#id#", "dashboard"=>"tile", "mobile"=>"tile", "production"=>1, "order"=>1),
				"intensite" => array("name"=>__("Intensité", __FILE__), "type"=>"info", "subtype"=>"numeric", "unite"=>"A", "xpath"=>"//pince/I#id#", "dashboard"=>"tile", "mobile"=>"tile", "consumption"=>1, "production"=>1, "order"=>2),
				"puissance" => array("name"=>__("Puissance", __FILE__), "type"=>"info", "subtype"=>"numeric", "unite"=>"VA", "xpath"=>"//pince/PUISSANCE#id#", "dashboard"=>"tile", "mobile"=>"tile", "filter"=>["usecustomcgx"=>1], "consumption"=>1, "production"=>1, "order"=>3),
				"consojour" => array("name"=>__("Consommation jour", __FILE__), "type"=>"info", "subtype"=>"numeric", "unite"=>"kWh", "xpath"=>"//pince/CONSO_JOUR#id#", "dashboard"=>"tile", "mobile"=>"tile", "filter"=>["usecustomcgx"=>1], "consumption"=>1, "order"=>4),
				"coutjour" => array("name"=>__("Coût jour", __FILE__), "type"=>"info", "subtype"=>"numeric", "unite"=>"€", "xpath"=>"//pince/COUT_JOUR#id#", "dashboard"=>"tile", "mobile"=>"tile", "filter"=>["usecustomcgx"=>1], "consumption"=>1, "order"=>5),
				"consomois" => array("name"=>__("Consommation mois", __FILE__), "type"=>"info", "subtype"=>"numeric", "unite"=>"kWh", "xpath"=>"//pince/CONSO_MOIS#id#", "dashboard"=>"tile", "mobile"=>"tile", "filter"=>["usecustomcgx"=>1], "consumption"=>1, "order"=>6),
				"coutmois" => array("name"=>__("Coût mois", __FILE__), "type"=>"info", "subtype"=>"numeric", "unite"=>"€", "xpath"=>"//pince/COUT_MOIS#id#", "dashboard"=>"tile", "mobile"=>"tile", "filter"=>["usecustomcgx"=>1], "consumption"=>1, "order"=>7),
				"consoannee" => array("name"=>__("Consommation année", __FILE__), "type"=>"info", "subtype"=>"numeric", "unite"=>"kWh","xpath"=>"//pince/CONSO_ANNEE#id#", "dashboard"=>"tile", "mobile"=>"tile", "filter"=>["usecustomcgx"=>1], "consumption"=>1, "order"=>8),
				"coutannee" => array("name"=>__("Coût année", __FILE__), "type"=>"info", "subtype"=>"numeric", "unite"=>"€", "xpath"=>"//pince/COUT_ANNEE#id#", "dashboard"=>"tile", "mobile"=>"tile", "filter"=>["usecustomcgx"=>1], "consumption"=>1, "order"=>9),
				"injecjour" => array("name"=>__("Injection jour", __FILE__), "type"=>"info", "subtype"=>"numeric", "unite"=>"kWh", "xpath"=>"//pince/INJEC_JOUR#id#", "dashboard"=>"tile", "mobile"=>"tile", "filter"=>["usecustomcgx"=>1], "production"=>1, "order"=>4),
				"gainjour" => array("name"=>__("Gain jour", __FILE__), "type"=>"info", "subtype"=>"numeric", "unite"=>"€","xpath"=>"//pince/GAIN_JOUR#id#", "dashboard"=>"tile", "mobile"=>"tile", "filter"=>["usecustomcgx"=>1], "production"=>1, "order"=>5),
				"injecmois" => array("name"=>__("Injection mois", __FILE__), "type"=>"info", "subtype"=>"numeric", "unite"=>"kWh","xpath"=>"//pince/INJEC_MOIS#id#", "dashboard"=>"tile", "mobile"=>"tile", "filter"=>["usecustomcgx"=>1], "production"=>1, "order"=>6),
				"gainmois" => array("name"=>__("Gain mois", __FILE__), "type"=>"info", "subtype"=>"numeric", "unite"=>"€", "xpath"=>"//pince/GAIN_MOIS#id#", "dashboard"=>"tile", "mobile"=>"tile", "filter"=>["usecustomcgx"=>1], "production"=>1, "order"=>7),
				"injecannee" => array("name"=>__("Injection année", __FILE__), "type"=>"info", "subtype"=>"numeric", "unite"=>"kWh", "xpath"=>"//pince/INJEC_ANNEE#id#", "dashboard"=>"tile", "mobile"=>"tile", "filter"=>["usecustomcgx"=>1], "production"=>1, "order"=>8),
				"gainannee" => array("name"=>__("Gain année", __FILE__), "type"=>"info", "subtype"=>"numeric", "unite"=>"€", "xpath"=>"//pince/GAIN_ANNEE#id#", "dashboard"=>"tile", "mobile"=>"tile", "filter"=>["usecustomcgx"=>1], "production"=>1, "order"=>9),
				"maxjour" => array("name"=>__("Puissance max jour", __FILE__), "type"=>"info", "subtype"=>"numeric", "unite"=>"A", "xpath"=>"//pince/MAX_JOUR#id#", "dashboard"=>"tile", "mobile"=>"tile", "filter"=>["usecustomcgx"=>1], "consumption"=>1, "production"=>1, "order"=>10),
				"maxmois" => array("name"=>__("Puissance max mois", __FILE__), "type"=>"info", "subtype"=>"numeric", "unite"=>"A", "xpath"=>"//pince/MAX_MOIS#id#", "dashboard"=>"tile", "mobile"=>"tile", "filter"=>["usecustomcgx"=>1], "consumption"=>1, "production"=>1, "order"=>11),
				"maxannee" => array("name"=>__("Puissance max année", __FILE__), "type"=>"info", "subtype"=>"numeric", "unite"=>"A", "xpath"=>"//pince/MAX_ANNEE#id#", "dashboard"=>"tile", "mobile"=>"tile", "filter"=>["usecustomcgx"=>1], "consumption"=>1, "production"=>1, "order"=>12)
			),
			"bouton" => array(
				"state" => array("name"=>__("Etat", __FILE__), "type"=>"info", "subtype"=>"binary", "xpath"=>"//entree/ENTREE#id#")
			),
			"relai" => array(
				"state" => array("name"=>__("Etat", __FILE__), "type"=>"info", "subtype"=>"binary", "visible"=>0, "xpath"=>"//relais/RELAIS#id#", "xpathcond"=>"//relais1W/RELAIS#id#", "cond"=>"#id#>=10", "dashboard"=>"prise", "mobile"=>"prise", "order"=>1),
				"btn_on" => array("name"=>"On", "type"=>"action", "subtype"=>"other", "value"=>"state", "dashboard"=>"prise", "mobile"=>"prise", "order"=>2),
				"btn_off" => array("name"=>"Off", "type"=>"action", "subtype"=>"other", "value"=>"state", "dashboard"=>"prise", "mobile"=>"prise", "order"=>3),
				"commute" => array("name"=>"Toggle", "type"=>"action", "subtype"=>"other", "order"=>4)
			),
			"switch" => array(
				"state" => array("name"=>__("Etat", __FILE__), "type"=>"info", "subtype"=>"binary", "visible"=>0, "xpath"=>"//switch_virtuel/SWITCH#id#","dashboard" =>"circle", "mobile" =>"circle", "order"=>1),
				"btn_on" => array("name"=>"On", "type"=>"action", "subtype"=>"other", "value"=>"state", "dashboard"=>"circle", "mobile"=>"circle", "order"=>2),
				"btn_off" => array("name"=>"Off", "type"=>"action", "subtype"=>"other", "value"=>"state", "dashboard"=>"circle", "mobile"=>"circle", "order"=>3),
				"commute" => array("name"=>"Toggle", "type"=>"action", "subtype"=>"other", "order"=>4)
			),
			"temperature" => array(
				"reel" => array("name"=>__("Température",__FILE__), "type"=>"info", "subtype"=>"numeric", "unite"=>"°C", "xpath"=>"//temp/SONDE#id#", "dashboard"=>"tile", "mobile"=>"tile")
			),
			"analogique" => array(
				"reel" => array("name"=>__("Réel",__FILE__), "type"=>"info", "subtype"=>"numeric", "dashboard"=>"tile", "mobile"=>"tile"),
				"brut" => array("name"=>__("Brut",__FILE__), "type"=>"info", "subtype"=>"numeric", "xpath"=>"//analogique/AD#id#", "dashboard"=>"tile", "mobile"=>"tile")
			),
			"variable" => array(
				"value" => array("name"=>__("Valeur",__FILE__), "type"=>"info", "subtype"=>"numeric", "xpath"=>"//variables/VARIABLE#id#", "dashboard"=>"tile", "mobile"=>"tile")
			)
		);
		return $commands;
	}

	public function getTypes() {
		$types = array(
			"general" => array("name"=>__("Serveur Wes", __FILE__), "category"=>"energy", "width"=>"192px", "height"=>"212px", "ignoreCreation"=>1),
			"analogique" => array("name" => __("Capteurs", __FILE__), "logical"=>"_N", "category"=>"automatism", "width"=>"112px", "height"=>"152px", "xpath"=>"//analogique/AD#id#", "maxnumber"=>4, "type"=>__("Tension", __FILE__)),
			"compteur" => array("name" => __("Compteurs impulsions", __FILE__), "logical"=>"_C", "width"=>"272px", "height"=>"332px", "category"=>"energy", "xpath"=>"//impulsion/INDEX#id#", "maxnumber"=>6, "type"=>__("Compteur", __FILE__)),
			"bouton" => array("name" => __("Entrées", __FILE__), "logical"=>"_B", "category"=>"automatism", "width"=>"112px", "height"=>"152px", "xpath"=>"//entree/ENTREE#id#", "maxnumber"=>2, "type"=>__("Entrée", __FILE__)),
			"pince" => array("name" => __("Pinces Ampèremétriques", __FILE__), "logical"=>"_P", "width"=>"392px", "height"=>"272px", "category"=>"energy", "xpath"=>"//pince/I#id#","maxnumber"=>4, "type"=>__("Pince", __FILE__)),
			"relai" => array("name" => __("Relais", __FILE__), "logical"=>"_R", "category"=>"automatism", "width"=>"112px", "height"=>"152px", "xpath"=>"//relais/RELAIS#id#","maxnumber"=>2, "type"=>__("Relais", __FILE__)),
			"switch" => array("name" => __("Switchs virtuels", __FILE__), "logical"=>"_S", "category"=>"automatism", "width"=>"112px", "height"=>"152px", "xpath"=>"//switch_virtuel/SWITCH#id#", "maxnumber"=>24, "type"=>__("Switch", __FILE__)),
			"teleinfo" => array("name" => __("Téléinfo", __FILE__), "logical"=>"_T", "width"=>"272px", "height"=>"492px", "category"=>"energy", "xpath"=>"//tic#id#/ADCO", "maxnumber"=>3, "type"=>__("TIC", __FILE__)),
			"temperature" => array("name" => __("Températures", __FILE__), "logical"=>"_A", "category"=>"heating", "width"=>"112px", "height"=>"152px", "xpath"=>"//temp/SONDE#id#", "maxnumber"=>30, "type"=>__("Sonde", __FILE__)),
			"variable" => array("name" => __("Variables", __FILE__), "logical"=>"_V", "category"=>"automatism", "width"=>"112px", "height"=>"152px", "xpath"=>"//variables/VARIABLE#id#", "maxnumber"=>8, "type"=>__("Variable", __FILE__)),
		);
		return $types;
	}

	public static function daemon() {
		$starttime = microtime (true);
		log::add(__CLASS__,'debug','cron start');
		foreach (self::byType('wes', true) as $eqLogic) {
			if($eqLogic->getConfiguration('type') == "general"){
				$eqLogic->pull();
			}
		}
		log::add(__CLASS__,'debug','cron stop');
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
		log::add(__CLASS__,'debug','Launching Daemon');
		$cron->run();
	}

	public static function deamon_stop() {
		$cron = cron::byClassAndFunction('wes', 'daemon');
		if (!is_object($cron)) {
			throw new Exception(__('Tâche cron introuvable', __FILE__));
		}
		log::add(__CLASS__,'debug','Stopping Daemon');
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
		log::add(__CLASS__,'debug', $this->getHumanName() . __(' Envoi du fichier CGX personnalisé au serveur Wes', __FILE__));
		$ftpIp = $this->getConfiguration('ip');
		$ftpUser = $this->getConfiguration('ftpusername');
		$ftpPass = $this->getConfiguration('ftppassword');
		$local_file = dirname(__FILE__) . '/../../resources/DATA_JEEDOM.CGX';
		$connection = ftp_connect($ftpIp);
		if (@ftp_login($connection, $ftpUser, $ftpPass)){
			log::add(__CLASS__,'debug', $this->getHumanName() . __(' Connecté au serveur Wes en FTP', __FILE__));
		}
		else{
			ftp_close($connection);
			log::add(__CLASS__,'error', $this->getHumanName() . __(' Erreur lors de la connexion au serveur Wes en FTP', __FILE__));
			return false;
		}
		ftp_pasv($connection, true);
		if (ftp_put($connection, '/DATA_JEEDOM.CGX',  $local_file, FTP_BINARY)) {
			log::add(__CLASS__,'debug', $this->getHumanName() . __(' Fichier CGX correctement transmis au serveur Wes', __FILE__));
		}
		else {
			log::add(__CLASS__,'error', $this->getHumanName() . __(' Erreur lors de la transmission du fichier CGX au serveur Wes', __FILE__));
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
		log::add(__CLASS__,'debug', $this->getHumanName() . __(' Appel de l\'url : ', __FILE__).$url.'/'.$file);
		if ( $postarg != "" ) {
			log::add(__CLASS__,'debug','Post '.$postarg);
			curl_setopt($process, CURLOPT_POST, 1);
			curl_setopt($process, CURLOPT_POSTFIELDS, $postarg);
		}
		$return = curl_exec($process);
		curl_close($process);
		if ( $return === false )
		throw new Exception(__('Le serveur Wes n\'est pas joignable.',__FILE__));
		usleep (50);
		return $return;
	}

	public function preUpdate() {
		if ($this->getConfiguration('type') == "general" && $this->getConfiguration('usecustomcgx', 0) == 1) {
			if ($this->getConfiguration('ip','') != '' && $this->getConfiguration('ftpusername','') != '' && $this->getConfiguration('ftppassword','') != '') {
				$this->sendFtp();
			}
			else {
				throw new Exception(__('Veuillez renseigner les informations de connexion FTP pour utiliser le fichier CGX personnalisé.',__FILE__));
			}
		}
		if ($this->getIsEnable() && $this->getConfiguration('type') == "general" && $this->getConfiguration('ip') != '' && $this->getConfiguration('username') != '' && $this->getConfiguration('password') != '') {
			log::add(__CLASS__,'debug', $this->getHumanName() . __(' Vérification de la communication avec le serveur Wes', __FILE__));
			$file = 'data.cgx';
			if ($this->getConfiguration('usecustomcgx',0) == 1) {
				$file = 'data_jeedom.cgx';
			}
			$this->xmlstatus = simplexml_load_string($this->getUrl($file));
			if ($this->xmlstatus === false){
				throw new Exception(__('Le serveur Wes n\'est pas joignable.',__FILE__));
			}
		}
	}

	public function preInsert() {
		$this->setIsEnable(0);
		$this->setIsVisible(0);
		if ($this->getConfiguration('type', '') == '') {
			$this->setConfiguration('type', 'general');
		}
	}

	public function postSave() {
		$type = $this->getConfiguration('type');

		foreach($this->getListeCommandes()[$type] as $logicalId => $details) {
			if ($type == 'pince' && (!isset($details[$this->getConfiguration('pinceMeasure')]) || $details[$this->getConfiguration('pinceMeasure')] != 1)) {
				continue;
			}
			if (isset($details['filter'])) {
				foreach ($details['filter'] as $param => $value) {
					$continue = false;
					if ($param == 'usecustomcgx' && $type != 'general') {
						$generalId = explode('_', $this->getLogicalId());
						if (eqLogic::byId($generalId[0])->getConfiguration($param) != $value) {
							$continue = true;
						}
					}
					else if ($this->getConfiguration($param) != $value) {
						$continue = true;
					}
				}
				if ($continue) {
					continue;
				}
			}
			$cmd = $this->getCmd(null, $logicalId);
			if (!is_object($cmd)) {
				log::add(__CLASS__,'debug', $this->getHumanName() . __(' Création de la commande ', __FILE__) . $details['name'] . ' : ' .$logicalId);
				$cmd = new wesCmd();
				$cmd->setName($details['name']);
				$cmd->setEqLogic_id($this->getId());
				$cmd->setType($details['type']);
				$cmd->setSubType($details['subtype']);
				$cmd->setLogicalId($logicalId);
				if (isset($details['visible'])){
					$cmd->setIsVisible($details['visible']);
				}
				if (isset($details['order'])){
					$cmd->setOrder($details['order']);
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
				if (isset($details['value'])){
					$cmd->setValue($this->getCmd('info', $details['value'])->getId());
				}
				if (isset($details['dashboard'])){
					$cmd->setTemplate('dashboard',$details['dashboard']);
				}
				if (isset($details['mobile'])){
					$cmd->setTemplate('mobile',$details['mobile']);
				}
				if (isset($details['minValue'])){
					$cmd->setConfiguration('minValue',$details['minValue']);
				}
				if (isset($details['maxValue'])){
					$cmd->setConfiguration('maxValue',$details['maxValue']);
				}
				$cmd->save();
			}
		}

		if ($this->getIsEnable() && $this->getConfiguration('type') == "general" && $this->getConfiguration('ip','') != '' && $this->getConfiguration('username','') != '' && $this->getConfiguration('password','') != '') {
			log::add(__CLASS__, 'debug', $this->getHumanName() . __(' Démarrage du démon', __FILE__));
			self::deamon_start();
		}
	}

	public function postUpdate() {
		if ($this->getIsEnable() && $this->getConfiguration('type') == "general" && $this->getConfiguration('ip','') != '' && $this->getConfiguration('username','') != '' && $this->getConfiguration('password','') != '') {
			$file = 'data.cgx';
			if ($this->getConfiguration('usecustomcgx', 0) == 1) {
				$file = 'data_jeedom.cgx';
			}
			$this->xmlstatus = simplexml_load_string($this->getUrl($file));
			foreach (self::getTypes() as $type => $data){
				if (!isset($data['ignoreCreation'])) {
					$id = 1;
					$xpathModele = str_replace('#id#',$id,$data['xpath']);
					$status = $this->xmlstatus->xpath($xpathModele);
					while (count($status) != 0) {
						if (!is_object(self::byLogicalId($this->getId().$data['logical'].$id, 'wes')) && $this->getConfiguration($type.$id,1)==1) {
							log::add(__CLASS__,'debug', $this->getHumanName() . __(' Création de l\'équipement ', __FILE__) . $data['type'] . ' ' . $id . ' : ' . $this->getId() . $data['logical'] . $id);
							$eqLogic = new wes();
							$eqLogic->setEqType_name('wes');
							$eqLogic->setLogicalId($this->getId().$data['logical'].$id);
							$eqLogic->setName($data['type'] . ' ' . $id . ' (' . $this->getName() . ')');
							$eqLogic->setConfiguration('type', $type);
							$eqLogic->setCategory($data['category'], 1);
							$eqLogic->setDisplay('width', $data['width']);
							$eqLogic->setDisplay('height', $data['height']);
							$eqLogic->save();
						} else if (is_object(self::byLogicalId($this->getId().$data['logical'].$id, 'wes')) && $this->getConfiguration($type.$id,1)==0) {
							$toRemove = self::byLogicalId($this->getId().$data['logical'].$id, 'wes');
							log::add(__CLASS__,'debug', $this->getHumanName() . __(' Suppression de l\'équipement ', __FILE__) . $toRemove->getName() . ' : ' . $toRemove->getLogicalId());
							$toRemove->remove();
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
			if (substr($eqLogic->getLogicalId(), 0, strpos($eqLogic->getLogicalId(),"_")) == $this->getId() ) {
				log::add(__CLASS__,'debug', $this->getHumanName() . __(' Suppression des équipements liés ', __FILE__) . $eqLogic->getConfiguration('type') . ' : ' . $eqLogic->getName());
				$eqLogic->remove();
			}
		}
	}

	public function getLinkToConfiguration() {
		return 'index.php?v=d&p=wes&m=wes&id=' . $this->getId();
	}

	public function configPush() {
		if (config::byKey("internalAddr") == "" || config::byKey("internalPort") == "")	{
			throw new Exception(__('L\'adresse IP ou le port local de jeedom ne sont pas définis (Administration => Configuration réseaux => Accès interne).', __FILE__));
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
					log::add(__CLASS__,'debug','Url program.cgi?PRG='.$compteurId.','.($wesid+30).',0,0,1,0,1,2,0,1,4,0000,0000,9,0');
					$this->getUrl('program.cgi?PRG='.$compteurId.','.($wesid+30).',0,0,1,0,1,2,0,1,4,0000,0000,9,0');
					$this->getUrl('program.cgi?RQT'.$compteurId.'='.$pathjeedom.'core/api/jeeApi.php?api='.jeedom::getApiKey('wes').'%26type=wes%26id='.$cmd->getId().'%26value=$I'.$wesid.'00');
					log::add(__CLASS__,'debug','Url program.cgi?RQT'.$compteurId.'='.$pathjeedom.'core/api/jeeApi.php?api='.jeedom::getApiKey('wes').'%26type=wes%26id='.$cmd->getId().'%26value=$I'.$wesid.'00');
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
						log::add(__CLASS__,'debug','Url '.$url);
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
		if ( $this->getIsEnable() && $this->getConfiguration('type') == "general") {
			$file = 'data.cgx';
			if ($this->getConfiguration('usecustomcgx',0) == 1) {
				$file = 'data_jeedom.cgx';
			}
			log::add(__CLASS__,'debug', $this->getHumanName() . __(' Interrogation du serveur Wes', __FILE__));
			$this->xmlstatus = simplexml_load_string($this->getUrl($file));
			$count = 0;
			while ( $this->xmlstatus === false && $count < 3) {
				log::add(__CLASS__,'debug', $this->getHumanName() . __(' Tentative échouée, nouvelle interrogation du serveur Wes', __FILE__));
				$this->xmlstatus = simplexml_load_string($this->getUrl($file));
				$count++;
			}
			if ( $this->xmlstatus === false ) {
				$this->checkAndUpdateCmd('status', 0);
				log::add(__CLASS__,'error', $this->getHumanName() . __('Le serveur Wes n\'est pas joignable sur ',__FILE__) . $file);
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
									$value = ($value == 'ON') ? 1 : 0;
								}
								$eqLogic->checkAndUpdateCmd($logical, $value);
							}
						}
					}
				}
			}
			log::add(__CLASS__,'debug', $this->getHumanName() . __(' Fin d\'interrogation du serveur Wes', __FILE__));
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
		if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") {
			$url .= 's';
		}
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
		log::add(__CLASS__,'debug','execute '.$_options);
		$eqLogic = $this->getEqLogic();
		if (!is_object($eqLogic) || $eqLogic->getIsEnable() != 1) {
			throw new Exception(__('Équipement desactivé impossible d\'exécuter la commande : ' . $this->getHumanName(), __FILE__));
		}
		$weseqLogic = eqLogic::byId(substr ($eqLogic->getLogicalId(), 0, strpos($eqLogic->getLogicalId(),"_")));
		$wesid = substr($eqLogic->getLogicalId(), strpos($eqLogic->getLogicalId(),"_")+2);
		if($eqLogic->getConfiguration('type') == 'general'){
			if ( $this->getLogicalId() == 'alarmeon') {
				$file = 'AJAX.cgx?alarme=ON';
				$alarm = 1;
			} else if ( $this->getLogicalId() == 'alarmeoff' ){
				$file = 'AJAX.cgx?alarme=OFF';
				$alarm = 0;
			} else {
				return false;
			}
			$eqLogic->getUrl($file);
			$eqLogic->checkAndUpdateCmd('alarme', $alarm);
			return;
		} else if ($eqLogic->getConfiguration('type') == "relai") {
			if ( $this->getLogicalId() == 'btn_on' ){
				$file .= 'RL.cgi?rl'.$wesid.'=ON';
				$state = 1;
			} else if ( $this->getLogicalId() == 'btn_off' ){
				$file .= 'RL.cgi?rl'.$wesid.'=OFF';
				$state = 0;
			} else if ( $this->getLogicalId() == 'commute' ){
				$file .= 'RL.cgi?frl='.$wesid;
				$state = ($eqLogic->getCmd('info', 'state')->execCmd() == 1) ? 0 : 1;
			} else {
				return false;
			}
			$weseqLogic->getUrl($file);
			$eqLogic->checkAndUpdateCmd('state', $state);
			return;
		} elseif ($eqLogic->getConfiguration('type') == "switch") {
			if ( $this->getLogicalId() == 'btn_on' ){
				$file .= 'AJAX.cgx?vs'.$wesid.'=ON';
				$state = 1;
			} else if ( $this->getLogicalId() == 'btn_off' ){
				$file .= 'AJAX.cgx?vs'.$wesid.'=OFF';
				$state = 0;
			}else if ( $this->getLogicalId() == 'commute' ){
				$file .= 'AJAX.cgx?fvs='.$wesid;
				$state = ($eqLogic->getCmd('info', 'state')->execCmd() == 1) ? 0 : 1;
			} else {
				return false;
			}
			$weseqLogic->getUrl($file);
			$eqLogic->checkAndUpdateCmd('state', $state);
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
					log::add(__CLASS__, 'error', $EqLogic->getName()." error in ".$this->getConfiguration('calcul')." : ".$e->getMessage());
					return scenarioExpression::setTags(str_replace('"', '', cmd::cmdToValue($this->getConfiguration('calcul'))));
				}
			} else {
				return $this->getConfiguration('value');
			}
		}elseif ($eqLogic->getConfiguration('type') == "bouton") {
			log::add(__CLASS__,'debug','execute '.$_options);
			$eqLogic = $this->getEqLogic();
			if (!is_object($eqLogic) || $eqLogic->getIsEnable() != 1) {
				throw new Exception(__('Equipement désactivé impossible d\éxecuter la commande : ' . $this->getHumanName(), __FILE__));
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
			log::add(__CLASS__,'debug',"get ".preg_replace("/:[^:]*@/", ":XXXX@", $url));
			$count = 0;
			while ( $result === false && $count < 3 ) {
				$result = @file_get_contents($url);
				$count++;
			}
			if ( $result === false ) {
				throw new Exception(__('Le serveur Wes n\'est pas joignable.',__FILE__)." ".$weseqLogic->getName());
			}
			return false;
		}
	}
}
?>
