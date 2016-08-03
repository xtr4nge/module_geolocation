<style>
	.block {
		width: 200px;
		display: inline-block;
	}
</style>
<b>GeoLocation</b> A tool for changing GeoLocation (WiFi only). Monitor mode is required (<b>mon0</b>)
<br><br>
<b>Author</b>: xtr4nge [_AT_] gmail.com - @xtr4nge

<br><br>

<br><b>[TABS]</b>
<br><b>Commands</b>: Exec or Edit individual templates.
<br><b>Options</b>: Set Path options. GeoLocation Path is the path to be executed when the module is started.
<br><b>Templates</b>: Edit templates. The templates are a list of WiFi networks [BSSID (space) SSID].
<br><b>Path</b>: Edit path. The path is a list of templates. The templates are executed in order to create a path. The wait on each spot is defined on Options tab [wait value].

<br><br>

<br><b>[OPTIONS]</b>
<br>
<br><b>Path</b>: This path is executed when GeoLocation module is started.
<br><b>Wait</b>: Wait on spot (current template) in seconds (default 120).
<br><b>Threads</b>: Number of concurrent instances per template.
