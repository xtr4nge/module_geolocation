<? 
/*
    Copyright (C) 2013-2016 xtr4nge [_AT_] gmail.com

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/ 
?>
<?
include "../../../login_check.php";
include "../../../config/config.php";
include "../_info_.php";
include "../../../functions.php";

include "options_config.php";

// Checking POST & GET variables...
if ($regex == 1) {
    regex_standard($_GET["service"], "../msg.php", $regex_extra);
    regex_standard($_GET["action"], "../msg.php", $regex_extra);
    regex_standard($_GET["page"], "../msg.php", $regex_extra);
    regex_standard($_GET["install"], "../msg.php", $regex_extra);
    regex_standard($_GET["hopping_conf"], "../msg.php", $regex_extra);
    regex_standard($_GET["geolocation_conf"], "../msg.php", $regex_extra);
}

$service = $_GET['service'];
$action = $_GET['action'];
$page = $_GET['page'];
$install = $_GET['install'];
$geolocation_conf = $_GET['geolocation_conf'];

function killRegex($regex){
	
	$exec = "ps aux|grep -E '$regex' | grep -v grep | awk '{print $2}'";
	exec($exec,$output);
	
	if (count($output) > 0) {
		$exec = "kill " . $output[0];
		exec_fruitywifi($exec);
	}	
}

if($service == "geolocation") {
    
    if ($action == "run") {
        
        $exec = "$bin_killall mdk3";
        exec_fruitywifi($exec);
        sleep(0.5);
        for ($i=0; $i < 5; $i++) {
            $exec = "$bin_screen -md $bin_mdk3 mon0 b -v templates/$geolocation_conf -g -t  > /dev/null 2 &";
            echo "$exec <br>";
            exec_fruitywifi($exec);
        }
        
    } else if ($action == "end") {
        
        $exec = "$bin_killall mdk3";
        exec_fruitywifi($exec);
        
    } else if ($action == "start") {

        $exec = "python geolocation-path.py -d templates/ -w $mod_geolocation_wait -f path/$mod_geolocation_onstart -t $mod_geolocation_threads  > /dev/null 2 &";
        exec_fruitywifi($exec);
        
        // COPY LOG
        if ( 0 < filesize( $mod_logs ) ) {
            $exec = "$bin_cp $mod_logs $mod_logs_history/".gmdate("Ymd-H-i-s").".log";
            //exec_fruitywifi($exec);
            
            $exec = "$bin_echo '' > $mod_logs";
            //exec_fruitywifi($exec);
        }
    
    
    } else if ($action == "stop") {
        
        killRegex("geolocation-path.py");
        sleep(0.5);
        $exec = "$bin_killall mdk3";
        exec_fruitywifi($exec);

    }

}

if ($install == "install_autostart") {

    $exec = "chmod 755 install.sh";
    exec_fruitywifi($exec);

    $exec = "$bin_sudo ./install.sh > $log_path/install.txt &";
    exec_fruitywifi($exec);

    header('Location: ../../install.php?module=autostart');
    exit;
}

if ($page == "status") {
    header('Location: ../../../action.php');
} else {
    header('Location: ../../action.php?page='.$mod_name);
}

?>