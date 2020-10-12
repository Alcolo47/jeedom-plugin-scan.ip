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

if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}

$ipsReseau = (array) scan_ip::getJsonTampon();
if(empty($ipsReseau)){
    scan_ip::syncScanIp();
    $ipsReseau = (array) scan_ip::getJsonTampon();
}

$savingMac = scan_ip::getAlleqLogics();

?>

<style>
    .scanTd{
        padding : 3px 20px !important;
    }
    .macPresentActif{
        color: green;
    }
    .macPresentInactif{
        color: red;
    }
    .macAbsent{
        color: grey;
    }
    .spanScanIp{
        display: block;
        width: 78x !important;
        padding : 2px 5px;
        color : white;
        text-align: center;
    }
    .EnableScanIp{
        background-color: green;
    }
    .DisableScanIp{
        background-color: red;
    }
    .NoneScanIp{
        background-color: #A9A9A9;
    }
</style>

<div class="col-md-8">
    <div class="panel panel-primary" id="div_functionalityPanel">
        <div class="panel-heading">
            <h3 class="panel-title">Les plages ip et adresses MAC du réseau (<?php echo $ipsReseau["timestamp"]->date ?>)</h3>
        </div>
        <div class="panel-body">
            <table style="width: 100%; margin: -5px -5px 10px 5px;">
                <thead>
                    <tr style="background-color: grey !important; color: white !important;">
                        <th class="scanTd">{{Suivi}}</th>
                        <th class="scanTd">{{Adresse MAC}}</th>
                        <th class="scanTd">{{ip}}</th>
                        <th class="scanTd">{{Nom}}</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($ipsReseau["all"] as $value) {
                        
                        if(isset($savingMac[$value->mac]["name"])){
                            $name = $savingMac[$value->mac]["name"];
                        } else {
                            $name = $value->name;
                        }
                        
                        if(isset($savingMac[$value->mac]["enable"])){
                            if($savingMac[$value->mac]["enable"] == 1){
                                $classPresent = "macPresentActif";
                                $textPresent = "Activé";
                                $classSuivi = "spanScanIp EnableScanIp";
                            } else {
                                $classPresent = "macPresentInactif";
                                $textPresent = "Désactivé";
                                $classSuivi = "spanScanIp DisableScanIp";
                            }
                        } else {
                            $classPresent = "macAbsent";
                            $textPresent = "Inconnue";
                            $classSuivi = "spanScanIp NoneScanIp";
                        }
                        
                        echo '<tr>'
                        . '<td class="'.$classPresent.'"><span class="'.$classSuivi.'">' . $textPresent . '</span></td>'
                        . '<td class="scanTd '.$classPresent.'">' . $value->mac . '</td>'
                        . '<td class="scanTd '.$classPresent.'">' . $value->ip_v4 . '</td>'
                        . '<td class="scanTd '.$classPresent.'">' . $name . '</td>'
                        . '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="col-md-4">
    <div class="panel panel-primary" id="div_functionalityPanel">
        <div class="panel-heading">
            <h3 class="panel-title">Votre Jeedom</h3>
        </div>
        <div class="panel-body">
            <div>
                <label class="col-sm-5 control-label">Nom : </label>
                <div><?php echo $ipsReseau["jeedom"]->name ?></div> 
            </div>
            <div>
                <label class="col-sm-5 control-label">ip : </label>
                <div><?php echo $ipsReseau["jeedom"]->ip_v4 ?></div> 
            </div>
            <div>
                <label class="col-sm-5 control-label">Adresse MAC : </label>
                <div><?php echo $ipsReseau["jeedom"]->mac?></div>
            </div>
        </div>
        <br />
    </div>
</div>

<div class="col-md-4">
    <div class="panel panel-primary" id="div_functionalityPanel">
        <div class="panel-heading">
            <h3 class="panel-title">Votre routeur</h3>
        </div>
        <div class="panel-body">
            <div>
                <label class="col-sm-5 control-label">ip : </label>
                <div><?php echo $ipsReseau["routeur"]->ip_v4 ?></div>
            </div>
            <div>
                <label class="col-sm-5 control-label">Adresse MAC : </label>
                <div><?php echo $ipsReseau["routeur"]->mac ?></div>
            </div>
        </div>
        <br />
    </div>
</div>

<?php include_file('core', 'plugin.template', 'js'); ?>