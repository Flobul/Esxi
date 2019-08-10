<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; witfhout even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *f
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';

class Esxi extends eqLogic {

	public static function pull() {
		foreach (eqLogic::byType('Esxi') as $Esxi) {
			$Esxi->getInformations();
			$mc = cache::byKey('EsxiWidgetmobile' . $Esxi->getId());
			$mc->remove();
			$mc = cache::byKey('EsxiWidgetdashboard' . $Esxi->getId());
			$mc->remove();
			$Esxi->toHtml('mobile');
			$Esxi->toHtml('dashboard');
			$Esxi->refreshWidget();
		}
	}

	public static function dependancy_info() {
		$return = array();
		$return['log'] = 'Esxi_update';
		$return['progress_file'] = '/tmp/dependancy_esxi_in_progress';
		if (file_exists('/tmp/dependancy_esxi_in_progress')) {
			$return['state'] = 'in_progress';
		} else {
			if (exec('php -m | grep ssh2 | wc -l') != 0) {
				$return['state'] = 'ok';
			} else {
				$return['state'] = 'nok';
			}
		}
		return $return;
	}

	public static function dependancy_install() {
		if (file_exists('/tmp/compilation_esxi_in_progress')) {
			return;
		}
		log::remove('Esxi_update');
		$cmd = 'sudo /bin/bash ' . dirname(__FILE__) . '/../../ressources/install.sh';
		$cmd .= ' >> ' . log::getPathToLog('Esxi_update') . ' 2>&1 &';
		exec($cmd);
	}

	public function postSave() {

		$EsxiCmd = $this->getCmd(null, 'namedistri');
		if (!is_object($EsxiCmd)) {
			$EsxiCmd = new EsxiCmd();

		}
		$EsxiCmd->setName(__('Distribution', __FILE__));
		$EsxiCmd->setEqLogic_id($this->getId());
		$EsxiCmd->setLogicalId('namedistri');
		$EsxiCmd->setType('info');
		$EsxiCmd->setSubType('string');
		$EsxiCmd->setIsVisible(1);
		$EsxiCmd->save();


		$EsxiCmd = $this->getCmd(null, 'uptime');
		if (!is_object($EsxiCmd)) {
			$EsxiCmd = new EsxiCmd();
		}
		$EsxiCmd->setName(__('Démarré depuis',  __FILE__));
		$EsxiCmd->setEqLogic_id($this->getId());
		$EsxiCmd->setLogicalId('uptime');
		$EsxiCmd->setType('info');
		$EsxiCmd->setSubType('string');
		$EsxiCmd->save();

		$EsxiCmd = $this->getCmd(null, 'loadavg1mn');
		if (!is_object($EsxiCmd)) {
			$EsxiCmd = new EsxiCmd();
		}
		$EsxiCmd->setName(__('Charge système 1min', __FILE__));
		$EsxiCmd->setEqLogic_id($this->getId());
		$EsxiCmd->setLogicalId('loadavg1mn');
		$EsxiCmd->setType('info');
		$EsxiCmd->setSubType('numeric');
		$EsxiCmd->save();

		$EsxiCmd = $this->getCmd(null, 'loadavg5mn');
		if (!is_object($EsxiCmd)) {
			$EsxiCmd = new EsxiCmd();
		}
		$EsxiCmd->setName(__('Charge système 5min', __FILE__));
		$EsxiCmd->setEqLogic_id($this->getId());
		$EsxiCmd->setLogicalId('loadavg5mn');
		$EsxiCmd->setType('info');
		$EsxiCmd->setSubType('numeric');
		$EsxiCmd->save();

		$EsxiCmd = $this->getCmd(null, 'loadavg15mn');
		if (!is_object($EsxiCmd)) {
			$EsxiCmd = new EsxiCmd();
		}
		$EsxiCmd->setName(__('Charge système 15min', __FILE__));
		$EsxiCmd->setEqLogic_id($this->getId());
		$EsxiCmd->setLogicalId('loadavg15mn');
		$EsxiCmd->setType('info');
		$EsxiCmd->setSubType('numeric');
		$EsxiCmd->save();

		$EsxiCmd = $this->getCmd(null, 'ethernet0');
		if (!is_object($EsxiCmd)) {
			$EsxiCmd = new EsxiCmd();
		}
		$EsxiCmd->setName(__('Réseau (MiB)', __FILE__));
		$EsxiCmd->setEqLogic_id($this->getId());
		$EsxiCmd->setLogicalId('ethernet0');
		$EsxiCmd->setType('info');
		$EsxiCmd->setSubType('string');
		$EsxiCmd->save();

		$EsxiCmd = $this->getCmd(null, 'cpu');
		if (!is_object($EsxiCmd)) {
			$EsxiCmd = new EsxiCmd();
		}
		$EsxiCmd->setName(__('CPU(s)', __FILE__));
		$EsxiCmd->setEqLogic_id($this->getId());
		$EsxiCmd->setLogicalId('cpu');
		$EsxiCmd->setType('info');
		$EsxiCmd->setSubType('string');
		$EsxiCmd->save();

		$EsxiCmd = $this->getCmd(null, 'cpu_temp');
		if (!is_object($EsxiCmd)) {
			$EsxiCmd = new EsxiCmd();
		}
		$EsxiCmd->setName(__('Température CPU', __FILE__));
		$EsxiCmd->setEqLogic_id($this->getId());
		$EsxiCmd->setLogicalId('cpu_temp');
		$EsxiCmd->setType('info');
		$EsxiCmd->setSubType('numeric');
		$EsxiCmd->save();

		$EsxiCmd = $this->getCmd(null, 'cnx_ssh');
		if (!is_object($EsxiCmd)) {
			$EsxiCmd = new EsxiCmd();
		}
		$EsxiCmd->setName(__('Statut cnx SSH Scénario', __FILE__));
		$EsxiCmd->setEqLogic_id($this->getId());
		$EsxiCmd->setLogicalId('cnx_ssh');
		$EsxiCmd->setType('info');
		$EsxiCmd->setSubType('string');
		$EsxiCmd->save();

        for ($i = 0; $i < 6; $i++) {
            $EsxiCmd = $this->getCmd(null, 'DISK'.$i);
            if (!is_object($EsxiCmd)) {
                $EsxiCmd = new EsxiCmd();
            }
            $EsxiCmd->setName(__('Nom disque '.$i, __FILE__));
            $EsxiCmd->setEqLogic_id($this->getId());
            $EsxiCmd->setLogicalId('DISK'.$i);
            $EsxiCmd->setType('info');
            $EsxiCmd->setSubType('string');
            $EsxiCmd->save();

            $EsxiCmd = $this->getCmd(null, 'HEALTH'.$i);
            if (!is_object($EsxiCmd)) {
                $EsxiCmd = new EsxiCmd();
            }
            $EsxiCmd->setName(__('Santé disque '.$i, __FILE__));
            $EsxiCmd->setEqLogic_id($this->getId());
            $EsxiCmd->setLogicalId('HEALTH'.$i);
            $EsxiCmd->setType('info');
            $EsxiCmd->setSubType('string');
            $EsxiCmd->save();

            $EsxiCmd = $this->getCmd(null, 'HOURS'.$i);
            if (!is_object($EsxiCmd)) {
                $EsxiCmd = new EsxiCmd();
            }
            $EsxiCmd->setName(__('Durée disque '.$i, __FILE__));
            $EsxiCmd->setEqLogic_id($this->getId());
            $EsxiCmd->setLogicalId('HOURS'.$i);
            $EsxiCmd->setType('info');
            $EsxiCmd->setSubType('string');
            $EsxiCmd->save();

            $EsxiCmd = $this->getCmd(null, 'TEMP'.$i);
            if (!is_object($EsxiCmd)) {
                $EsxiCmd = new EsxiCmd();
            }
            $EsxiCmd->setName(__('Température disque '.$i, __FILE__));
            $EsxiCmd->setEqLogic_id($this->getId());
            $EsxiCmd->setLogicalId('TEMP'.$i);
            $EsxiCmd->setType('info');
            $EsxiCmd->setSubType('numeric');
            $EsxiCmd->save();
        }

		$EsxiCmd = $this->getCmd(null, 'perso1');
		if (!is_object($EsxiCmd)) {
			$EsxiCmd = new EsxiCmd();
		}
		$EsxiCmd->setName(__('perso1', __FILE__));
		$EsxiCmd->setEqLogic_id($this->getId());
		$EsxiCmd->setLogicalId('perso1');
		$EsxiCmd->setType('info');
		$EsxiCmd->setSubType('string');
		$EsxiCmd->save();

		$EsxiCmd = $this->getCmd(null, 'reboot');
		if (!is_object($EsxiCmd)) {
			$EsxiCmd = new EsxiCmd();
		}
		$EsxiCmd->setName(__('reboot', __FILE__));
		$EsxiCmd->setEqLogic_id($this->getId());
		$EsxiCmd->setLogicalId('reboot');
		$EsxiCmd->setType('action');
		$EsxiCmd->setSubType('other');
		$EsxiCmd->save();

		$EsxiCmd = $this->getCmd(null, 'poweroff');
		if (!is_object($EsxiCmd)) {
			$EsxiCmd = new EsxiCmd();
		}
		$EsxiCmd->setName(__('poweroff', __FILE__));
		$EsxiCmd->setEqLogic_id($this->getId());
		$EsxiCmd->setLogicalId('poweroff');
		$EsxiCmd->setType('action');
		$EsxiCmd->setSubType('other');
		$EsxiCmd->save();

		foreach (eqLogic::byType('Esxi') as $Esxi) {
			$Esxi->getInformations();
		}
	}

	public static $_widgetPossibility = array('custom' => array(
		'visibility' => true,
		'displayName' => true,
		'displayObjectName' => true,
		'optionalParameters' => false,
		'background-color' => true,
		'text-color' => true,
		'border' => true,
		'border-radius' => true,
		'background-opacity' => true,
	));

	public function toHtml($_version = 'dashboard')	{
		$replace = $this->preToHtml($_version);
		if (!is_array($replace)) {
			return $replace;
		}
		$_version = jeedom::versionAlias($_version);
		log::add('Esxi', 'error', 'Diskque '.$nbdisks);

		for ($i = 0; $i < 6; $i++) {
			$replace ['#TEMP'.$i.'vertinfa#'] = $this->getConfiguration('TEMP'.$i.'vertinfa');
			$replace ['#TEMP'.$i.'orangede#'] = $this->getConfiguration('TEMP'.$i.'orangede');
			$replace ['#TEMP'.$i.'orangea#'] = $this->getConfiguration('TEMP'.$i.'orangea');
			$replace ['#TEMP'.$i.'rougesupa#'] = $this->getConfiguration('TEMP'.$i.'rougesupa');
		}
		$replace ['#loadavg1mnvertinfa#'] = $this->getConfiguration('loadavg1mnvertinfa');
		$replace ['#loadavg5mnvertinfa#'] = $this->getConfiguration('loadavg5mnvertinfa');
		$replace ['#loadavg15mnvertinfa#'] = $this->getConfiguration('loadavg15mnvertinfa');
		$replace ['#loadavg1mnorangede#'] = $this->getConfiguration('loadavg1mnorangede');
		$replace ['#loadavg5mnorangede#'] = $this->getConfiguration('loadavg5mnorangede');
		$replace ['#loadavg15mnorangede#'] = $this->getConfiguration('loadavg15mnorangede');
		$replace ['#loadavg1mnorangea#'] = $this->getConfiguration('loadavg1mnorangea');
		$replace ['#loadavg5mnorangea#'] = $this->getConfiguration('loadavg5mnorangea');
		$replace ['#loadavg15mnorangea#'] = $this->getConfiguration('loadavg15mnorangea');
		$replace ['#loadavg1mnrougesupa#'] = $this->getConfiguration('loadavg1mnrougesupa');
		$replace ['#loadavg5mnrougesupa#'] = $this->getConfiguration('loadavg5mnrougesupa');
		$replace ['#loadavg15mnrougesupa#'] = $this->getConfiguration('loadavg15mnrougesupa');
		$replace ['#cpu_tempvertinfa#'] = $this->getConfiguration('cpu_tempvertinfa');
		$replace ['#cpu_temporangede#'] = $this->getConfiguration('cpu_temporangede');
		$replace ['#cpu_temporangea#'] = $this->getConfiguration('cpu_temporangea');
		$replace ['#cpu_temprougesupa#'] = $this->getConfiguration('cpu_temprougesupa');

		$namedistri = $this->getCmd(null,'namedistri');
		$replace['#namedistri#'] = (is_object($namedistri)) ? $namedistri->execCmd() : '';
		$replace['#namedistriid#'] = is_object($namedistri) ? $namedistri->getId() : '';
		$replace['#namedistri_display#'] = (is_object($namedistri) && $namedistri->getIsVisible()) ? "#namedistri_display#" : "none";

		$loadavg1mn = $this->getCmd(null,'loadavg1mn');
		$replace['#loadavg1mn#'] = (is_object($loadavg1mn)) ? $loadavg1mn->execCmd() : '';
		$replace['#loadavg1mnid#'] = is_object($loadavg1mn) ? $loadavg1mn->getId() : '';
		$replace['#loadavg_display#'] = (is_object($loadavg1mn) && $loadavg1mn->getIsVisible()) ? "#loadavg_display#" : "none";

		$loadavg5mn = $this->getCmd(null,'loadavg5mn');
		$replace['#loadavg5mn#'] = (is_object($loadavg5mn)) ? $loadavg5mn->execCmd() : '';
		$replace['#loadavg5mnid#'] = is_object($loadavg5mn) ? $loadavg5mn->getId() : '';

		$loadavg15mn = $this->getCmd(null,'loadavg15mn');
		$replace['#loadavg15mn#'] = (is_object($loadavg15mn)) ? $loadavg15mn->execCmd() : '';
		$replace['#loadavg15mnid#'] = is_object($loadavg15mn) ? $loadavg15mn->getId() : '';

		$uptime = $this->getCmd(null,'uptime');
		$replace['#uptime#'] = (is_object($uptime)) ? $uptime->execCmd() : '';
		$replace['#uptimeid#'] = is_object($uptime) ? $uptime->getId() : '';
		$replace['#uptime_display#'] = (is_object($uptime) && $uptime->getIsVisible()) ? "#uptime_display#" : "none";

		$ethernet0 = $this->getCmd(null,'ethernet0');
		$replace['#ethernet0#'] = (is_object($ethernet0)) ? $ethernet0->execCmd() : '';
		$replace['#ethernet0id#'] = is_object($ethernet0) ? $ethernet0->getId() : '';
		$replace['#ethernet0_display#'] = (is_object($ethernet0) && $ethernet0->getIsVisible()) ? "#ethernet0_display#" : "none";

		$cpu = $this->getCmd(null,'cpu');
		$replace['#cpu#'] = (is_object($cpu)) ? $cpu->execCmd() : '';
		$replace['#cpuid#'] = is_object($cpu) ? $cpu->getId() : '';
		$replace['#cpu_display#'] = (is_object($cpu) && $cpu->getIsVisible()) ? "#cpu_display#" : "none";

		$cnx_ssh = $this->getCmd(null,'cnx_ssh');
		$replace['#cnx_ssh#'] = (is_object($cnx_ssh)) ? $cnx_ssh->execCmd() : '';
		$replace['#cnx_sshid#'] = is_object($cnx_ssh) ? $cnx_ssh->getId() : '';

		$cpu_temp = $this->getCmd(null,'cpu_temp');
		$replace['#cpu_temp#'] = (is_object($cpu_temp)) ? $cpu_temp->execCmd() : '';
		$replace['#cpu_tempid#'] = is_object($cpu_temp) ? $cpu_temp->getId() : '';

		for ($i = 0; $i < 6; $i++) {
			${"DISK$i"} = $this->getCmd(null,'DISK'.$i);
			$replace['#DISK'.$i.'#'] = (is_object(${"DISK$i"})) ? ${"DISK$i"}->execCmd() : '';
			$replace['#DISK'.$i.'id#'] = is_object(${"DISK$i"}) ? ${"DISK$i"}->getId() : '';
		
			${"HEALTH$i"} = $this->getCmd(null,'HEALTH'.$i);
			$replace['#HEALTH'.$i.'#'] = (is_object(${"HEALTH$i"})) ? ${"HEALTH$i"}->execCmd() : '';
			$replace['#HEALTH'.$i.'#id#'] = is_object(${"HEALTH$i"}) ? ${"HEALTH$i"}->getId() : '';
			
			${"HOURS$i"} = $this->getCmd(null,'HOURS'.$i);
			$replace['#HOURS'.$i.'#'] = (is_object(${"HOURS$i"})) ? ${"HOURS$i"}->execCmd() : '';
			$replace['#HOURS'.$i.'id#'] = is_object(${"HOURS$i"}) ? ${"HOURS$i"}->getId() : '';
			
			${"TEMP$i"} = $this->getCmd(null,'TEMP'.$i);
			$replace['#TEMP'.$i.'#'] = (is_object(${"TEMP$i"})) ? ${"TEMP$i"}->execCmd() : '';
			$replace['#TEMP'.$i.'id#'] = is_object(${"TEMP$i"}) ? ${"TEMP$i"}->getId() : '';
		}
		
		$perso1 = $this->getCmd(null,'perso1');
		$replace['#perso1#'] = (is_object($perso1)) ? $perso1->execCmd() : '';
		$replace['#perso1id#'] = is_object($perso1) ? $perso1->getId() : '';
		$replace['#perso1_display#'] = (is_object($perso1) && $perso1->getIsVisible()) ? "#perso1_display#" : "none";
		$nameperso_1 = (is_object($perso1)) ? $this->getCmd(null,'perso1')->getName() : '';
		$iconeperso_1 = (is_object($perso1)) ? $this->getCmd(null,'perso1')->getdisplay('icon') : '';
		$replace['#nameperso1#'] = (is_object($perso1)) ? $nameperso_1 : "";
		$replace['#iconeperso1#'] = (is_object($perso1)) ? $iconeperso_1 : "";
		$perso_1unite = $this->getConfiguration('perso1_unite');
		$replace['#uniteperso1#'] = (is_object($perso1)) ? $perso_1unite : "";

		foreach ($this->getCmd('action') as $cmd) {
			$replace['#cmd_' . $cmd->getLogicalId() . '_id#'] = $cmd->getId();
		}

		$html = template_replace($replace, getTemplate('core', $_version, 'Esxi','Esxi'));
		cache::set('EsxiWidget' . $_version . $this->getId(), $html, 0);
		return $html;
	}

	public function getInformations() {

		$uname = "Inconnu";

		if ($this->getConfiguration('cartereseau') == 'netautre'){
			$cartereseau = $this->getConfiguration('cartereseauautre');
		}else{
			$cartereseau = $this->getConfiguration('cartereseau');
		}
		if ($this->getConfiguration('maitreesclave') == 'deporte' && $this->getIsEnable()){
			$ip = $this->getConfiguration('addressip');
			$user = $this->getConfiguration('user');
			$pass = $this->getConfiguration('password');
			$port = $this->getConfiguration('portssh');
			$equipement = $this->getName();

			if (!$connection = ssh2_connect($ip,$port)) {
				log::add('Esxi', 'error', 'connexion SSH KO pour '.$equipement);
				$cnx_ssh = 'KO';
			}else{
				if (!ssh2_auth_password($connection,$user,$pass)){
					log::add('Esxi', 'error', 'Authentification SSH KO pour '.$equipement);
					$cnx_ssh = 'KO';
				}else{
					$cnx_ssh = 'OK';
					$ARMvcmd = "uname -a | awk '{print \$7}'";
					$uptimecmd = "uptime";
					$VersionIDcmd = "uname -p | awk -F _ '{printf $2}'";
					$namedistricmd = "uname -rso";
					$bitdistricmd = "uname -p";
					$loadavgcmd = "uptime | awk -F 'average: ' '{printf $2}'";
	          		$nbcpu = "vim-cmd hostsvc/hostsummary | grep numCpuCores | awk '{printf $3}' | sed -e 's/,//g'";
					$disk = "/usr/lib/vmware/vm-support/bin/smartinfo";
					$perso_1cmd = $this->getConfiguration('perso1');

					$ARMvoutput = ssh2_exec($connection, $ARMvcmd);
					stream_set_blocking($ARMvoutput, true);
					$ARMv = stream_get_contents($ARMvoutput);
					$ARMv = trim($ARMv);

					$uptimeoutput = ssh2_exec($connection, $uptimecmd);
					stream_set_blocking($uptimeoutput, true);
					$uptime = stream_get_contents($uptimeoutput);

					$VersionIDoutput = ssh2_exec($connection, $VersionIDcmd);
					stream_set_blocking($VersionIDoutput, true);
					$VersionID = stream_get_contents($VersionIDoutput);

					$namedistrioutput = ssh2_exec($connection, $namedistricmd);
					stream_set_blocking($namedistrioutput, true);
					$namedistri = stream_get_contents($namedistrioutput);

					$bitdistrioutput = ssh2_exec($connection, $bitdistricmd);
					stream_set_blocking($bitdistrioutput, true);
					$bitdistri = stream_get_contents($bitdistrioutput);

					$loadavgoutput = ssh2_exec($connection, $loadavgcmd);
					stream_set_blocking($loadavgoutput, true);
					$loadav = stream_get_contents($loadavgoutput);

					$closesession = ssh2_exec($connection, 'exit');
					stream_set_blocking($closesession, true);
					stream_get_contents($closesession);
					//close ssh ($connection);

					$connection = ssh2_connect($ip,$port);
					ssh2_auth_password($connection,$user,$pass);

					$perso1output = ssh2_exec($connection, $perso_1cmd);
					stream_set_blocking($perso1output, true);
					$perso_1 = stream_get_contents($perso1output);

					$diskoutput = ssh2_exec($connection, $disk);
					stream_set_blocking($diskoutput, true);
					$disk = stream_get_contents($diskoutput);

              		$unamecmd = "vim-cmd hostsvc/hostsummary | grep licenseProductName | awk -F '\"' '{printf $2}'";
              		$unamedata = ssh2_exec($connection, $unamecmd);
              		stream_set_blocking($unamedata, true);
              		$uname = stream_get_contents($unamedata);
				
					$nbcpuARMcmd = "vim-cmd hostsvc/hostsummary | grep numCpuCores | awk '{printf $3}' | sed -e 's/,//g'";
           			$nbcpuoutput = ssh2_exec($connection, $nbcpuARMcmd);
           			stream_set_blocking($nbcpuoutput, true);
           			$nbcpu = stream_get_contents($nbcpuoutput);
           			$nbcpu = trim($nbcpu);

           			$cpufreq0ARMcmd = "vim-cmd hostsvc/hostsummary | grep cpuMhz | awk '{printf $3}' | sed -e 's/,//g'";
             		$cpufreq0output = ssh2_exec($connection, $cpufreq0ARMcmd);
              		stream_set_blocking($cpufreq0output, true);
              		$cpufreq = stream_get_contents($cpufreq0output);              			
              	}
              }
          }
          if (isset($cnx_ssh)) {
          	if($this->getConfiguration('maitreesclave') == 'local' || $cnx_ssh == 'OK'){
          		if (isset($namedistri)) {
          			$namedistrifin = str_ireplace('PRETTY_NAME="', '', $namedistri);
          			$namedistrifin = str_ireplace('"', '', $namedistrifin);
          			if (isset($namedistri) && isset($namedistrifin) && isset($bitdistri) && isset($ARMv)) {
          				$namedistri = $namedistrifin.' '.$bitdistri.'bits ('.$ARMv.')';
          			}
          		}

          		if (isset($uptime)) {
          			$datauptime = explode(' up ', $uptime);
          			if (isset($datauptime[0]) && isset($datauptime[1])) {
          				$datauptime = explode(', ', $datauptime[1]);
          				$datauptime = str_replace("days", "jour(s)", $datauptime);
          				$datauptime = str_replace("day", "jour", $datauptime);
						$datauptime = preg_replace('/:/', 'h', $datauptime, 1);
						$datauptime = preg_replace('/:/', 'min', $datauptime, 1);
						$datauptime = preg_replace('/:/', 's', $datauptime, 1);
						if (strpos($datauptime[0], 'jour(s)') === false){
          					$uptime = $datauptime[0];
          				}else{
          					if (isset($datauptime[0]) && isset($datauptime[1])) {
          						$uptime = $datauptime[0].' '.$datauptime[1];
          					}
          				}
          			}
          		}

          		if (isset($loadav)) {
          			$loadavg = explode(" ", $loadav);
          			if (isset($loadavg[0]) && isset($loadavg[1]) && isset($loadavg[2])) {
          				$loadavg1mn = $loadavg[0];
          				$loadavg5mn = $loadavg[1];
          				$loadavg15mn = $loadavg[2];
          			}
          		}

          		if (isset($ARMv)) {
          				if (($cpufreq / 1000) > 1) {
          					$cpufreq = round($cpufreq / 1000, 1, PHP_ROUND_HALF_UP) . " GHz";
          				}else{
          					$cpufreq = $cpufreq . " MHz";
          				}
          				if ($this->getCmd(null,'cpu_temp')->getIsVisible() == 1) {
          					if ($cputemp0 != 0 & $cputemp0 > 200){
          						$cputemp0 = $cputemp0 / 1000;
          						$cputemp0 = round($cputemp0, 1);
          					}
          				}
          				$cpu = $nbcpu.' - '.$cpufreq;
          		}

          		if (isset($disk)) {
					$nbdisks = substr_count($disk, "t10");
					$smartdisk = explode("_____", $disk);

					preg_match_all("/t10.ATA_____(.*?)______________/",$disk,$linedisk);
					preg_match_all("/Health Status(.*)/",$disk,$healthdisk);
					preg_match_all("/Power-on Hours(.*)/",$disk,$powerhours);
					preg_match_all("/Drive Temperature(.*)/",$disk,$drivetemp);

					for ($i = 0; $i < $nbdisks; $i++) {
						$diskname[$i] = substr($linedisk[1][$i], 0, 12);
						$diskname = str_replace("_", "",$diskname);
						
						$healthdisk[1][$i] = trim($healthdisk[1][$i]); 
						$healthdisk[1][$i] = substr($healthdisk[1][$i], 0, strpos($healthdisk[1][$i],' '));

						$powerhours[1][$i] = trim($powerhours[1][$i]); 
						$powerhours[1][$i] = substr($powerhours[1][$i], 0, strpos($powerhours[1][$i],' '));

						$drivetemp[1][$i] = trim($drivetemp[1][$i]); 
						$drivetemp[1][$i] = substr($drivetemp[1][$i], 0, strpos($drivetemp[1][$i],' '));
					
					${"DISK$i"} = $diskname[$i];
					${"HEALTH$i"} = $healthdisk[1][$i];
					${"HOURS$i"} = $powerhours[1][$i];
					${"TEMP$i"} = $drivetemp[1][$i];
					}
          		}
				
          		if (empty($cputemp0)) {$cputemp0 = '';}
          		if (empty($perso_1)) {$perso_1 = '';}
          		if (empty($cnx_ssh)) {$cnx_ssh = '';}
          		if (empty($uname)) {$uname = 'Inconnu';}

          		$dataresult = array(
          			'namedistri' => $namedistri,
          			'uptime' => $uptime,
          			'loadavg1mn' => $loadavg1mn,
          			'loadavg5mn' => $loadavg5mn,
          			'loadavg15mn' => $loadavg15mn,
          			'ethernet0' => $ethernet0,
          			'cpu' => $cpu,
          			'cpu_temp' => $cputemp0,
          			'cnx_ssh' => $cnx_ssh,
          			'perso1' => $perso_1
				);
				for ($i = 0; $i < $nbdisks; $i++){
					$dataresult += array(
						'DISK'.$i => ${"DISK$i"},
						'HEALTH'.$i => ${"HEALTH$i"},
						'HOURS'.$i => ${"HOURS$i"},
						'TEMP'.$i => ${"TEMP$i"}
					);
				}

          		$namedistri = $this->getCmd(null,'namedistri');
          		if(is_object($namedistri)){
          			$namedistri->event($dataresult['namedistri']);
          		}
          		$uptime = $this->getCmd(null,'uptime');
          		if(is_object($uptime)){
          			$uptime->event($dataresult['uptime']);
          		}
          		$loadavg1mn = $this->getCmd(null,'loadavg1mn');
          		if(is_object($loadavg1mn)){
          			$loadavg1mn->event($dataresult['loadavg1mn']);
          		}
          		$loadavg5mn = $this->getCmd(null,'loadavg5mn');
          		if(is_object($loadavg5mn)){
          			$loadavg5mn->event($dataresult['loadavg5mn']);
          		}
          		$loadavg15mn = $this->getCmd(null,'loadavg15mn');
          		if(is_object($loadavg15mn)){
          			$loadavg15mn->event($dataresult['loadavg15mn']);
          		}
          		$ethernet0 = $this->getCmd(null,'ethernet0');
          		if(is_object($ethernet0)){
          			$ethernet0->event($dataresult['ethernet0']);
          		}
          		$cpu = $this->getCmd(null,'cpu');
          		if(is_object($cpu)){
          			$cpu->event($dataresult['cpu']);
          		}
          		$cpu_temp = $this->getCmd(null,'cpu_temp');
          		if(is_object($cpu_temp)){
          			$cpu_temp->event($dataresult['cpu_temp']);
          		}
          		$cnx_ssh = $this->getCmd(null,'cnx_ssh');
          		if(is_object($cnx_ssh)){
          			$cnx_ssh->event($dataresult['cnx_ssh']);
          		}
          		$perso1 = $this->getCmd(null,'perso1');
          		if(is_object($perso1)){
          			$perso1->event($dataresult['perso1']);
          		}
				for ($i = 0; $i < $nbdisks; $i++){
					${"DISK$i"} = $this->getCmd(null,'DISK'.$i);
					if(is_object(${"DISK$i"})){
						${"DISK$i"}->event($dataresult['DISK'.$i]);
					}
					${"HEALTH$i"} = $this->getCmd(null,'HEALTH'.$i);
					if(is_object(${"HEALTH$i"})){
						${"HEALTH$i"}->event($dataresult['HEALTH'.$i]);
					}
					${"HOURS$i"} = $this->getCmd(null,'HOURS'.$i);
					if(is_object(${"HOURS$i"})){
						${"HOURS$i"}->event($dataresult['HOURS'.$i]);
					}
					${"TEMP$i"} = $this->getCmd(null,'TEMP'.$i);
					if(is_object(${"TEMP$i"})){
						${"TEMP$i"}->event($dataresult['TEMP'.$i]);
					}	
				}
          	}
          }
          if (isset($cnx_ssh)) {
          	if($cnx_ssh == 'KO'){
          		$dataresult = array(
          			'namedistri' => 'Connexion SSH KO',
          			'cnx_ssh' => $cnx_ssh
          		);
          		$namedistri = $this->getCmd(null,'namedistri');
          		if(is_object($namedistri)){
          			$namedistri->event($dataresult['namedistri']);
          		}
          		$cnx_ssh = $this->getCmd(null,'cnx_ssh');
          		if(is_object($cnx_ssh)){
          			$cnx_ssh->event($dataresult['cnx_ssh']);
          		}
          	}
          }
      }
      function getCaseAction($paramaction) {
      	if ($this->getConfiguration('maitreesclave') == 'deporte' && $this->getIsEnable()){

      		$ip = $this->getConfiguration('addressip');
      		$user = $this->getConfiguration('user');
      		$pass = $this->getConfiguration('password');
      		$port = $this->getConfiguration('portssh');
      		$equipement = $this->getName();

      		if (!$connection = ssh2_connect($ip,$port)) {
      			log::add('Esxi', 'error', 'connexion SSH KO pour '.$equipement);
      			$cnx_ssh = 'KO';
      		}else{
      			if (!ssh2_auth_password($connection,$user,$pass)){
      				log::add('Esxi', 'error', 'Authentification SSH KO pour '.$equipement);
      				$cnx_ssh = 'KO';
      			}else{
      				switch ($paramaction) {
      					case "reboot":
      					$paramaction =
//								$Rebootcmd = "sudo shutdown -r now >/dev/null & shutdown -r now >/dev/null";
      					$Rebootcmd = "sudo reboot >/dev/null & reboot >/dev/null";
      					$Rebootoutput = ssh2_exec($connection, $Rebootcmd);
      					stream_set_blocking($Rebootoutput, false);
      					$Reboot = stream_get_contents($Rebootoutput);
      					log::add('Esxi','debug','lancement commande deporte reboot ' . $this->getHumanName());
      					break;
      					case "poweroff":
      					$paramaction =
//								$poweroffcmd = "sudo shutdown -P now >/dev/null & shutdown -P now >/dev/null";
      					$poweroffcmd = "sudo poweroff >/dev/null & poweroff  >/dev/null";
      					$poweroffoutput = ssh2_exec($connection, $poweroffcmd);
      					stream_set_blocking($poweroffoutput, false);
      					$poweroff = stream_get_contents($poweroffoutput);
      					log::add('Esxi','debug','lancement commande deporte poweroff' . $this->getHumanName());
      					break;
      				}
      			}
      		}
      	}elseif($this->getConfiguration('maitreesclave') == 'local' && $this->getIsEnable()){
      			switch ($paramaction) {
      				case "reboot":
      				$paramaction =
      				$cmdreboot = "sudo shutdown -r now >/dev/null & shutdown -r now >/dev/null";
      				exec($cmdreboot);
      				log::add('Esxi','debug','lancement commande local reboot ' . $this->getHumanName());
      				break;
      				case "poweroff":
      				$paramaction =
      				exec('sudo shutdown -P now >/dev/null & shutdown -P now >/dev/null');
      				log::add('Esxi','debug','lancement commande local poweroff ' . $this->getHumanName());
      				break;
      			}
      	}
      }
  }

  class EsxiCmd extends cmd {


  	/*     * *************************Attributs****************************** */
  	public static $_widgetPossibility = array('custom' => false);

  	/*     * *********************Methode d'instance************************* */
  	public function execute($_options = null) {
  		$eqLogic = $this->getEqLogic();
  		$paramaction = $this->getLogicalId();

  		if ( $this->GetType = "action" ) {
  			$eqLogic->getCmd();
  			$contentCmd = $eqLogic->getCaseAction($paramaction);
  		} else {
  			throw new Exception(__('Commande non implémentée actuellement', __FILE__));
  		}
  		return true;
  	}
  }

  ?>

