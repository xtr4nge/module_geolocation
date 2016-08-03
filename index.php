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
include "../../login_check.php";
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>FruityWiFi</title>
<script src="../js/jquery.js"></script>
<script src="../js/jquery-ui.js"></script>
<link rel="stylesheet" href="../css/jquery-ui.css" />
<link rel="stylesheet" href="../css/style.css" />
<link rel="stylesheet" href="../../../style.css" />

<script src="includes/scripts.js"></script>

<script>
$(function() {
    $( "#action" ).tabs();
    $( "#result" ).tabs();
});

</script>

</head>
<body>

<? include "../menu.php"; ?>

<br>

<?

include "../../config/config.php";
include "../../login_check.php";
include "_info_.php";
include "../../functions.php";

// Checking POST & GET variables...
if ($regex == 1) {
	regex_standard($_POST["newdata"], "msg.php", $regex_extra);
    regex_standard($_GET["logfile"], "msg.php", $regex_extra);
    regex_standard($_GET["action"], "msg.php", $regex_extra);
    regex_standard($_POST["service"], "msg.php", $regex_extra);
    regex_standard($_GET["tempname"], "msg.php", $regex_extra);
}

$newdata = $_POST['newdata'];
$logfile = $_GET["logfile"];
$action = $_GET["action"];
$tempname = $_GET["tempname"];
$service = $_POST["service"];

// DELETE LOG
if ($logfile != "" and $action == "delete") {
    $exec = "$bin_rm ".$mod_logs_history.$logfile.".log";
    exec_fruitywifi($exec);
}

include "includes/options_config.php";

?>

<div class="rounded-top" align="left">&nbsp; <b><?=$mod_alias?></b> </div>
<div class="rounded-bottom">

    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;version <?=$mod_version?><br>
    <?
    /*
    $isinstalled = exec("dpkg-query -s python-requests|grep -iEe '^status.+installed'");
    if ($isinstalled != "") {
        echo "&nbsp; $mod_alias <font style='color:lime'>installed</font><br>";
    } else {
        echo "&nbsp; $mod_alias <a href='includes/module_action.php?install=install_autostart' style='color:red'>install</a><br>";
    }
    */
    ?>
    
    <?
    $ismoduleup = exec($mod_isup);
    if ($ismoduleup != "") {
        echo "&nbsp; $mod_alias  <font color='lime'><b>enabled</b></font>.&nbsp; | <a href='includes/module_action.php?service=$mod_name&action=stop&page=module'><b>stop</b></a>";
    } else { 
        echo "&nbsp; $mod_alias  <font color='red'><b>disabled</b></font>. | <a href='includes/module_action.php?service=$mod_name&action=start&page=module'><b>start</b></a>"; 
    }
    ?>
    
    <br>
    
    <?
    $iface_mon0 = exec("/sbin/ifconfig | grep mon0 ");
    if ($iface_mon0 != "") {
        echo "&nbsp;&nbsp;&nbsp;&nbsp;Interface <font color='lime'><b>enabled</b></font>.&nbsp; [mon0]</a>";
    } else {
        echo "&nbsp;&nbsp;&nbsp;&nbsp;Interface <font color='red'><b>disabled</b></font>. [mon0]</a>";
    }
    ?>
</div>

<br>


<div id="msg" style="font-size: larger;">
Loading, please wait...
</div>

<div id="body" style="display:none;">


    <div id="result" class="module">
        <ul>
            <li><a href="#tab-run">Commands</a></li>
            <li><a href="#tab-options">Options</a></li>
            <li><a href="#tab-templates">Templates</a></li>
			<li><a href="#tab-path">Path</a></li>
            <li><a href="#tab-history">History</a></li>
            <li><a href="#tab-about">About</a></li>
        </ul>

        <!-- RUN -->

        <div id="tab-run" class="history">
            <?
                $template_path = "$mod_path/includes/templates/";
                $templates = glob($template_path.'*');
                //print_r($templates);
                
                for ($i = 0; $i < count($templates); $i++) {
                    $filename = str_replace($template_path,"",$templates[$i]);
                    $exec = "ps aux|grep -iEe 'mdk3.+$filename'|grep -v grep";
                    $ison = exec($exec);
                    if ($ison != "") {
                        $run_color = "green";
                        $run_text = "On";
                        $run_action = "end";
                    } else {
                        $run_color = "";
                        $run_text = "Run";
                        $run_action = "run";
                    }
                    
                    echo "<div style='padding:2px'>";
                    echo "<a href='?tab=3&tempname=$filename'>";
                    echo "<input class='btn btn-success-outline btn-xs' type='button' value='Edit' style='width: 40px;'>";
                    echo "</a> ";
                    echo "<a href='includes/module_action.php?service=$mod_name&action=$run_action&page=module&geolocation_conf=$filename'>";
                    echo "<input class='btn btn-success-outline btn-xs' type='button' value='$run_text' style='width: 40px; color: $run_color'>";
                    echo "</a> ";
                    echo "<a href='includes/module_action.php?service=$mod_name&action=$run_action&page=module&geolocation_conf=$filename'>$filename</a>";
                    echo "</div>";
                }
            ?>
            
        </div>

        <!-- OPTIONS -->

        <div id="tab-options" class="history">
            <script>
                function getValue(id) {
                    console.log(id)
                    var e = document.getElementById(id);
                    var output = e.options[e.selectedIndex].text;
                    console.log(output)
                }
                
            </script>
            <h4>
                ACTIONS
            </h4>
            <h5>
                <div style="color:black; w-idth: 50px; display: inline-block;">GeoLocation Path</div><br>
                <select class="btn btn-default btn-sm" id="action_onstart" name="action_onstart" onchange="setOptionSelect(this, 'mod_geolocation_onstart')">
                    <option value="0">-</option>
                    <?
                    $template_path = "$mod_path/includes/path/";
                    $templates = glob($template_path.'*');
        
                    for ($i = 0; $i < count($templates); $i++) {
                        $filename = str_replace($template_path,"",$templates[$i]);
                        if ($filename == $mod_geolocation_onstart) echo "<option selected>"; else echo "<option>"; 
                        echo "$filename";
                        echo "</option>";
                    }
                    ?>
                </select>
                
                <br><br>
                
                Wait (seconds)
                <br>
                <input id="geolocation_wait" class="form-control input-sm" placeholder="Wait" value="<?=$mod_geolocation_wait?>" style="width: 180px; display: inline-block; " type="text" />
                <input class="btn btn-default btn-sm" type="button" value="save" onclick="setOption('geolocation_wait', 'mod_geolocation_wait');">
                
                <br><br>
                
                Threads (default 5)
                <br>
                <input id="geolocation_threads" class="form-control input-sm" placeholder="Wait" value="<?=$mod_geolocation_threads?>" style="width: 180px; display: inline-block; " type="text" />
                <input class="btn btn-default btn-sm" type="button" value="save" onclick="setOption('geolocation_threads', 'mod_geolocation_threads');">
            </h5>
        </div>

        <!-- TEMPLATES -->
    
        <div id="tab-templates" class="history">
            <form id="formTemplates" name="formTemplates" method="POST" autocomplete="off" action="includes/save.php">
                <input class="btn btn-default btn-sm" type="submit" value="save">       
                
                <br><br>
                <?
                    if ($tempname != "") {
                        $filename = "$mod_path/includes/templates/".$tempname;
                        
                        /*
                        if ( 0 < filesize( $filename ) ) {
                            $fh = fopen($filename, "r"); // or die("Could not open file.");
                            $data = fread($fh, filesize($filename)); // or die("Could not read file.");
                            fclose($fh);
                        }
                        */
                        
                        $data = open_file($filename);
                        
                    } else {
                        $data = "";
                    }
                    
                ?>
                <textarea id="inject" name="newdata" class="btn btn-default btn-sm" c-lass="module-content" style="width: 100%; height: 160px; text-align: left; font-family: courier;"><?=htmlspecialchars($data)?></textarea>
                <input type="hidden" name="type" value="templates">
                <input type="hidden" name="action" value="save">
                <input type="hidden" name="tempname" value="<?=$tempname?>">
            </form>
            
            <br>
                
            <table border=0 cellspacing=0 cellpadding=0>
                <tr>
                    <td class="general">
                        Template
                    </td>
                    <td>
                        <form id="formTempname" name="formTempname" method="POST" autocomplete="off" action="includes/save.php">
                            <select name="tempname" onchange='this.form.submit()' class="btn btn-default btn-sm">
                            <option value="0">-</option>
                            <?
                            $template_path = "$mod_path/includes/templates/";
                            $templates = glob($template_path.'*');
                            //print_r($templates);
                
                            for ($i = 0; $i < count($templates); $i++) {
                                $filename = str_replace($template_path,"",$templates[$i]);
                                if ($filename == $tempname) echo "<option selected>"; else echo "<option>"; 
                                echo "$filename";
                                echo "</option>";
                            }
                            ?>
                            </select>
                            <input type="hidden" name="type" value="templates">
                            <input type="hidden" name="action" value="select">
                        </form>
                    </td>
                <tr>
                    <td class="general" style="padding-right: 6px">
                        Add/Rename
                    </td>
                    <td>
                        <form id="formTempname" name="formTempname" method="POST" autocomplete="off" action="includes/save.php">
                            <select name="new_rename" class="btn btn-default btn-sm">
                            <option value="0">- add template -</option>
                            <?
                            $template_path = "$mod_path/includes/templates/";
                            $templates = glob($template_path.'*');
                            //print_r($templates);
                
                            for ($i = 0; $i < count($templates); $i++) {
                                $filename = str_replace($template_path,"",$templates[$i]);
                                echo "<option>"; 
                                //if ($filename == $tempname) echo "<option selected>"; else echo "<option>";
                                echo "$filename";
                                echo "</option>";
                            }
                            ?>
                            
                            </select>
                            <input c-lass="ui-widget" class="btn btn-default btn-sm" type="text" name="new_rename_file" value="" style="width:150px; text-align: left;">
                            <input class="btn btn-default btn-sm" type="submit" value="add/rename">
                            
                            <input type="hidden" name="type" value="templates">
                            <input type="hidden" name="action" value="add_rename">
                            
                        </form>
                    </td>
                </tr>
                
                <tr><td><br></td></tr>
                
                <tr>
                    <td>
                        
                    </td>
                    <td>
                        <form id="formTempDelete" name="formTempDelete" method="POST" autocomplete="off" action="includes/save.php">
                            <select class="btn btn-default btn-sm" name="new_rename">
                            <option value="0">-</option>
                            <?
                            $template_path = "$mod_path/includes/templates/";
                            $templates = glob($template_path.'*');
                            //print_r($templates);
                
                            for ($i = 0; $i < count($templates); $i++) {
                                //$filename = $templates[$i];
                                $filename = str_replace($template_path,"",$templates[$i]);
                                echo "<option>"; 
                                echo "$filename";
                                echo "</option>";
                            }
                            ?>
                            
                            </select>
                
                            <input class="btn btn-default btn-sm" type="submit" value="delete">
                            
                            <input type="hidden" name="type" value="templates">
                            <input type="hidden" name="action" value="delete">
                            
                        </form>
                    </td>
                </tr>
            </table>
        </div>

		<!-- PATH -->
    
        <div id="tab-path" class="history">
            <form id="formPath" name="formPath" method="POST" autocomplete="off" action="includes/save.php">
                <input class="btn btn-default btn-sm" type="submit" value="save">       
                
                <br><br>
                <?
                    if ($tempname != "") {
                        $filename = "$mod_path/includes/path/".$tempname;
                        
                        /*
                        if ( 0 < filesize( $filename ) ) {
                            $fh = fopen($filename, "r"); // or die("Could not open file.");
                            $data = fread($fh, filesize($filename)); // or die("Could not read file.");
                            fclose($fh);
                        }
                        */
                        
                        $data = open_file($filename);
                        
                    } else {
                        $data = "";
                    }
                    
                ?>
                <textarea id="inject" name="newdata" class="btn btn-default btn-sm" c-lass="module-content" style="width: 100%; height: 160px; text-align: left; font-family: courier;"><?=htmlspecialchars($data)?></textarea>
                <input type="hidden" name="type" value="path">
                <input type="hidden" name="action" value="save">
                <input type="hidden" name="tempname" value="<?=$tempname?>">
            </form>
            
            <br>
                
            <table border=0 cellspacing=0 cellpadding=0>
                <tr>
                    <td class="general">
                        Template
                    </td>
                    <td>
                        <form id="formPath" name="formPath" method="POST" autocomplete="off" action="includes/save.php">
                            <select name="tempname" onchange='this.form.submit()' class="btn btn-default btn-sm">
                            <option value="0">-</option>
                            <?
                            $template_path = "$mod_path/includes/path/";
                            $templates = glob($template_path.'*');
                            //print_r($templates);
                
                            for ($i = 0; $i < count($templates); $i++) {
                                $filename = str_replace($template_path,"",$templates[$i]);
                                if ($filename == $tempname) echo "<option selected>"; else echo "<option>"; 
                                echo "$filename";
                                echo "</option>";
                            }
                            ?>
                            </select>
                            <input type="hidden" name="type" value="path">
                            <input type="hidden" name="action" value="select">
                        </form>
                    </td>
                <tr>
                    <td class="general" style="padding-right: 6px">
                        Add/Rename
                    </td>
                    <td>
                        <form id="formPath" name="formPath" method="POST" autocomplete="off" action="includes/save.php">
                            <select name="new_rename" class="btn btn-default btn-sm">
                            <option value="0">- add template -</option>
                            <?
                            $template_path = "$mod_path/includes/path/";
                            $templates = glob($template_path.'*');
                            //print_r($templates);
                
                            for ($i = 0; $i < count($templates); $i++) {
                                $filename = str_replace($template_path,"",$templates[$i]);
                                echo "<option>"; 
                                //if ($filename == $tempname) echo "<option selected>"; else echo "<option>";
                                echo "$filename";
                                echo "</option>";
                            }
                            ?>
                            
                            </select>
                            <input c-lass="ui-widget" class="btn btn-default btn-sm" type="text" name="new_rename_file" value="" style="width:150px; text-align: left;">
                            <input class="btn btn-default btn-sm" type="submit" value="add/rename">
                            
                            <input type="hidden" name="type" value="path">
                            <input type="hidden" name="action" value="add_rename">
                            
                        </form>
                    </td>
                </tr>
                
                <tr><td><br></td></tr>
                
                <tr>
                    <td>
                        
                    </td>
                    <td>
                        <form id="formTempDelete" name="formTempDelete" method="POST" autocomplete="off" action="includes/save.php">
                            <select class="btn btn-default btn-sm" name="new_rename">
                            <option value="0">-</option>
                            <?
                            $template_path = "$mod_path/includes/path/";
                            $templates = glob($template_path.'*');
                            //print_r($templates);
                
                            for ($i = 0; $i < count($templates); $i++) {
                                //$filename = $templates[$i];
                                $filename = str_replace($template_path,"",$templates[$i]);
                                echo "<option>"; 
                                echo "$filename";
                                echo "</option>";
                            }
                            ?>
                            
                            </select>
                
                            <input class="btn btn-default btn-sm" type="submit" value="delete">
                            
                            <input type="hidden" name="type" value="path">
                            <input type="hidden" name="action" value="delete">
                            
                        </form>
                    </td>
                </tr>
            </table>
        </div>

        <!-- HISTORY -->

        <div id="tab-history" class="history">
            <a href="?tab=4"><input class="btn btn-default btn-sm" type="submit" value="refresh"></a>
            <br><br>
            
            <?
            $logs = glob($mod_logs_history.'*.log');
            print_r($a);

            for ($i = 0; $i < count($logs); $i++) {
                $filename = str_replace(".log","",str_replace($mod_logs_history,"",$logs[$i]));
                echo "<a href='?logfile=".str_replace(".log","",str_replace($mod_logs_history,"",$logs[$i]))."&action=delete&tab=2'><b>x</b></a> ";
                echo $filename . " | ";
                echo "<a href='?logfile=".str_replace(".log","",str_replace($mod_logs_history,"",$logs[$i]))."&action=view'><b>view</b></a>";
                echo "<br>";
            }
            ?>
            
        </div>
        
        <!-- ABOUT -->

        <div id="tab-about" class="history">
            <? include "includes/about.php"; ?>
        </div>

        <!-- END ABOUT -->
        
    </div>

    <div id="loading" class="ui-widget" style="width:100%;background-color:#000; padding-top:4px; padding-bottom:4px;color:#FFF">
        Loading...
    </div>

    <?
    if ($_GET["tab"] == 1) {
        echo "<script>";
        echo "$( '#result' ).tabs({ active: 0 });";
        echo "</script>";
    } else if ($_GET["tab"] == 2) {
        echo "<script>";
        echo "$( '#result' ).tabs({ active: 1 });";
        echo "</script>";
    } else if ($_GET["tab"] == 3) {
        echo "<script>";
        echo "$( '#result' ).tabs({ active: 2 });";
        echo "</script>";
    } else if ($_GET["tab"] == 4) {
        echo "<script>";
        echo "$( '#result' ).tabs({ active: 3 });";
        echo "</script>";
    } else if ($_GET["tab"] == 5) {
        echo "<script>";
        echo "$( '#result' ).tabs({ active: 4 });";
        echo "</script>";
    }  
    ?>

</div>

<script type="text/javascript">
    $('#loading').hide();
    
    $(document).ready(function() {
        $('#body').show();
        $('#msg').hide();
    });
</script>

</body>
</html>
