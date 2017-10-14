<?php

$g_mftk = 'false';
$g_cmdb_autouser = 'lighttpd';

class freecmdbPlugin extends MantisPlugin {

	function register() {
		$this->name = "freecmdb";
		$this->description = "FreeCMDB";
		$this->page = "config";
		$this->version = "0.1.0";
		$this->requires =	array(
									'MantisCore' => '2.1.0'
								);
		$this->author = "Matt Samudio";
		$this->contact = "matt@samudio.net";
		$this->url = "https://github.com/zenteknix/freecmdb/wiki";
		define( 'FREECMDBBASE', "plugins/freecmdb");
	}

	function config() {
		define( 'VS_CMDB_CAPTURED', 100);
		define( 'VS_CMDB_NEXTACTION', 101);
		define( 'VS_CMDB_PROJECT', 102);
		define( 'VS_CMDB_DELEGATED', 103);
		define( 'VS_CMDB_REFERENCE', 104);
		define( 'VS_CMDB_SOMEDAY', 105);
		define( 'VS_CMDB_COMPLETED', 106);
		define( 'VS_CMDB_TRASH', 107);
		return	array(
						'host' => 'localhost',
						'cmdbauth' => array( 0 => 0, 1 => 1)
					);
	}

	function cmdbCustomize( $p_event) {
		global $g_status_enum_string, $g_status_enum_workflow, $g_status_colors;
		global $g_lang_strings, $g_active_language;
		$sLang = $g_active_language;

		$g_lang_strings[$sLang]['plugin_freecmdb_configuration'] = "Configuration";
		$g_lang_strings[$sLang]['plugin_freecmdb_authlabel'] = "FreeCMDB:";
	}

	function events() {
		return	array(
						'EVENT_CORE_READY' => EVENT_TYPE_EXECUTE,
						'EVENT_LAYOUT_RESOURCES' => EVENT_TYPE_OUTPUT,
						'EVENT_MENU_MAIN' => EVENT_TYPE_DEFAULT,
						'EVENT_MENU_FILTER' => EVENT_TYPE_DEFAULT,
						'EVENT_UPDATE_BUG' => EVENT_TYPE_CHAIN,
						'EVENT_FREECMDB_VIEW_BUG' => EVENT_TYPE_CHAIN,
						'EVENT_FREECMDB_UPDATE_BUG_FORM' => EVENT_TYPE_CHAIN,
						'EVENT_FREECMDB_VER' => EVENT_TYPE_EXECUTE,
						'EVENT_FREECMDB_NFO' => EVENT_TYPE_CHAIN
					);
	}

	function hooks() {
		return	array(
						'EVENT_CORE_READY' => 'cmdbCustomize',
						'EVENT_LAYOUT_RESOURCES' => 'cmdbHtmlHead',
						'EVENT_MENU_MAIN' => 'cmdbMainMenu',
						'EVENT_MENU_FILTER' => 'cmdbFilterMenu',
						'EVENT_UPDATE_BUG' => 'cmdbUpdateBug',
						'EVENT_FREECMDB_VIEW_BUG' => 'cmdbViewBug',
						'EVENT_FREECMDB_UPDATE_BUG_FORM' => 'cmdbViewBugUpdate',
						'EVENT_FREECMDB_VER' => 'cmdbVersion',
						'EVENT_FREECMDB_NFO' => 'cmdbInfo'
					);
	}

	function cmdbVersion( $p_event) {
		printf( "0.1.0");
	}

	function cmdbInfo( $p_event, $p_chained_param) {
		return $p_chained_param;
	}

	function cmdbHtmlHead( $p_event) {
		global $g_path;
		$sCSS = $g_path . "plugins/freecmdb/files/theme.css";
		$sRet = "<link rel=stylesheet type=\"text/css\" href=\"".$sCSS."\" />\n";
		$sSel = isset( $_POST['cmdbssid']) ? $_POST['cmdbssid'] : "";
		$pTVals = array(	"test1" => "test1",
								"test2" => "test2",
								"test3" => "test3" );
		$pPVals = array(	"prod1" => "prod1",
								"prod2" => "prod2",
								"prod3" => "prod3" );
		$sRet .= "<script type=\"text/javascript\">\n";
		$sRet .= "function genAuthSel() {\n";
		$sRet .= "var pSys, pLst;\n";
		$sRet .= "var sysSel = document.all.cmdbenv;\n";
		$sRet .= "pSys = sysSel.options[sysSel.selectedIndex].text;\n";
		$sRet .= "if ( pSys) {\n";
		$sRet .= "pLst = \"<select name=cmdbssid>\";\n";
		$sRet .= "if ( pSys == \"tst\") {\n";
		foreach ( $pTVals as $sKey => $sVal) {
			$sInd = ($sSel == $sKey) ? " selected" : "";
			$sRet .= "pLst += \"<option value=".$sKey.$sInd.">".$sVal."</option>\";\n";
		}
		$sRet .= "} else if ( pSys == \"prd\") {\n";
		foreach ( $pPVals as $sKey => $sVal) {
			$sInd = ($sSel == $sKey) ? " selected" : "";
			$sRet .= "pLst += \"<option value=".$sKey.$sInd.">".$sVal."</option>\";\n";
		}
		$sRet .= "} else {\n";
		$sRet .= "pLst += \"<option value=none>None</option>\";\n";
		$sRet .= "}\n";
		$sRet .= "pLst += \"</select>\";\n";
		$sRet .= "document.getElementById( \"cmdbssid\").innerHTML = pLst;\n";
		$sRet .= "}\n";
		$sRet .= "}\n";
		$sRet .= "\n";
		$sRet .= "</script>\n";
		return $sRet;
	}

	function cmdbMainMenu( $p_event) {
//		$aRet = array( "<a href=\"plugin.php?page=freecmdb/view\">CMDB</a>");
		$aRet = array(	array(	"title" => "CMDB",
										"access_level" => VIEWER,
										"url" => "plugin.php?page=freecmdb/view",
										"icon" => "fa-random") );
		return $aRet;
	}

	function cmdbFilterMenu( $p_event) {
		$aRet = array( "<a href=\"plugin.php?page=freecmdb/filter?op=batchedit\">Item Batch</a>");
		return( $aRet);
	}

	function cmdbViewBug( $p_event, $p_chained_param) {
		printf( "<tr><td>Processing event EVENT_FREECMDB_VIEW_BUG ...</td></tr>\n");
	}

	function cmdbViewBugUpdate( $p_event, $p_chained_param) {
		printf( "<tr><td>Processing event EVENT_FREECMDB_UPDATE_BUG_FORM ...</td></tr>\n");
	}

	function cmdbUpdateBug( $p_event, $p_chained_param) {
	}

}

function freecmdbAccessAllowed( $sOperation) {
	$bRet = false;
	if ( $sOperation == "cmdb") {
		$aLst = plugin_config_get( "cmdbauth");
		if ( isset( $aLst[auth_get_current_user_id()])) {
			$bRet = true;
		}
	}
	return( $bRet);
}

?>
