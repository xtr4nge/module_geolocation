<?php
$mod_name="geolocation";
$mod_version="1.0";
$mod_path="/usr/share/fruitywifi/www/modules/$mod_name";
$mod_logs="$log_path/$mod_name.log"; 
$mod_logs_history="$mod_path/includes/logs/";
$mod_logs_panel="disabled";
$mod_panel="show";
$mod_alias="GeoLocation";

# OPTIONS
$mod_geolocation_onstart="path-disney.conf";
$mod_geolocation_wait="120";
$mod_geolocation_threads="5";

# EXEC
$bin_sudo = "/usr/bin/sudo";
$bin_screen = "/usr/bin/screen";
$bin_mdk3 = "/usr/bin/mdk3";
$bin_sh = "/bin/sh";
$bin_echo = "/bin/echo";
$bin_grep = "/usr/bin/ngrep";
$bin_killall = "/usr/bin/killall";
$bin_cp = "/bin/cp";
$bin_chmod = "/bin/chmod";
$bin_sed = "/bin/sed";
$bin_rm = "/bin/rm";
$bin_route = "/sbin/route";
$bin_dos2unix = "/usr/bin/dos2unix";
$bin_touch = "/usr/bin/touch";
$bin_mv = "/bin/mv";

# ISUP
$mod_isup="ps aux|grep -iEe 'geolocation.+$mod_geolocation_onstart'|grep -v grep";
?>
