<?php

function addCI() {
	require_once( FREECMDBBASE . "/lib/ci.php");
	$sName = isset( $_POST['cmdb_itm_name']) ? $_POST['cmdb_itm_name'] : "unnamed";
	$sDesc = isset( $_POST['cmdb_itm_brief']) ? $_POST['cmdb_itm_brief'] : "";
	$pCI = new CI();
	$pCI->setWho( auth_get_current_user_id());
	$pCI->setName( $sName);
	$pCI->commit();
	$pCI->setBrief( $sDesc);
	$pCI->commit();
	return;
}

function saveCI() {
	require_once( FREECMDBBASE . "/lib/ci.php");
	$lCI = isset( $_POST['cmdbitm']) ? intval($_POST['cmdbitm']) : 0;
	$sName = isset( $_POST['cmdb_itm_name']) ? $_POST['cmdb_itm_name'] : "unnamed";
	$sDesc = isset( $_POST['cmdb_itm_brief']) ? $_POST['cmdb_itm_brief'] : "";
	$iCat = isset( $_POST['cmdb_itm_cat']) ? intval($_POST['cmdb_itm_cat']) : 0;
	$pCI = new CI();
	$pCI->find( $lCI);
	if ( $pCI->lID > 0) {
		$pCI->setType( $iCat);
		$pCI->setName( $sName);
		$pCI->setBrief( $sDesc);
		$pCI->commit();
	}
	return;
}

function genCaptureEntry( $sOrigin) {
	$sRet = sprintf( "<form method=post action=\"%s\">\n", $sOrigin);
	$sRet .= "<input type=hidden name=cmdbctx value=adm>\n";
	$sRet .= "<input type=hidden name=cmdbcmd value=cinew>\n";
	$sRet .= "<table align=center>\n";
	$sRet .= sprintf(	"<tr><td><strong>%s:<strong></td><td>%s</td></tr>\n",
							"New CI Name",
							"<textarea rows=1 cols=80 maxlength=80 id=cmdb_itm_name name=cmdb_itm_name></textarea>");
	$sRet .= sprintf(	"<tr><td valign=top><strong>%s:<strong></td><td>%s</td></tr>\n",
							"Description",
							"<textarea rows=10 cols=80 id=cmdb_itm_brief name=cmdb_itm_brief></textarea>");
	$sRet .= sprintf(	"<tr><td colspan=2 align=center>%s</td></tr>\n",
							"<input type=submit value=Add>");
	$sRet .= "</table>\n";
	$sRet .= "</form>\n";
	return( $sRet);
}

function genEditItmPage( $sOrigin) {
	require_once( FREECMDBBASE . "/lib/ci.php");
	$sRet = "";
	$lCI = 0;
	if ( !isset( $_POST['cmdbitm'])) {
		if ( isset( $_GET['cmdbitm'])) $lCI = intval( $_GET['cmdbitm']);
	} else $lCI = intval( $_POST['cmdbitm']);
	$pCI = new CI();
	$pCI->find( $lCI);
	if ( $pCI->lID > 0) {
		$sRet .= sprintf( "<form method=post action=\"%s\">\n", $sOrigin);
		$sRet .= "<input type=hidden name=cmdbctx value=adm>\n";
		$sRet .= sprintf( "<input type=hidden name=cmdbitm value=%d>\n", $lCI);
		$sRet .= $pCI->genEditView( "cmdbcmd", "cisave");
		$sRet .= "</form>\n";
	} else {
		$sRet .= "<table align=center>\n";
		$sRet .= sprintf( "<tr><td align=center>%s</td></tr>\n", "No CI");
		$sRet .= "</table>\n";
	}
	return( $sRet);
}

function genCMDBCapture( $sOrigin) {
	$sTbl = "mantis_plugin_freecmdb_itm";
	$sQry = sprintf( "select id,name from %s", $sTbl, auth_get_current_user_id());
	$pRslt = db_query_bound( $sQry, Array());
	$iRows = db_num_rows( $pRslt);
	$sRet = "<table align=center>\n";
	for ( $iRows=db_num_rows( $pRslt); $iRows > 0; $iRows--) {
		$pRow = db_fetch_array( $pRslt);
		$lCI = intval( $pRow['id']);
		$sName = $pRow['name'];
		$sURL = sprintf( "plugin.php?page=freecmdb/view&cmdbctx=adm&cmdbcmd=ciedit&cmdbitm=%d", $lCI);
		$sRet .= sprintf( "<tr><td>%s</td><td><a href=\"%s\">edit</a></td>", $sName, $sURL);
	}
	$sRet .= "</table>\n";
	return( $sRet);
}

function genCMDBCatSel( $iCat) {
	$sLT="mantis_plugin_freecmdb_lut";
	$sQry="select b.name,b.eid from ".$sLT." a join ".$sLT." b on b.ptn=a.idx where a.ptn=0 and a.tag='cicat' order by b.idx";
	$pRslt = db_query_bound( $sQry, Array());
	$iRows = db_num_rows( $pRslt);
	$sRet = "<table class=tblRadioButtonMatrix>\n";
	$sRet .= sprintf( "<tr><td><strong>%s:</strong></td>\n", "CI Type");
	$sRet .= "<td><select id=cmdb_itm_cat name=cmdb_itm_cat>\n";
	for ( $iRows=db_num_rows( $pRslt); $iRows > 0; $iRows--) {
		$pRow = db_fetch_array( $pRslt);
		$iCLV = $pRow['eid'];
		$sX = ($iCLV == $iCat) ? " selected" : "";
		$sRet .= sprintf( "<option value=%d%s>%s\n",$iCLV,$sX,$pRow['name']);
	}
	$sRet .= "</select></td>\n";
	$sRet .= "</tr>\n";
	$sRet .= "</table>\n";
	return( $sRet);
}

function genCMDBSave() {
	$sRet = "<table>\n";
//	$sRet .= "<tr><td align=center><input type=checkbox name=cmdbnsa value=nsa>Next-Step</td></tr>\n";
	$sRet .= "<tr><td align=center><input type=submit value=Save></td></tr>\n";
	$sRet .= "</table>\n";
	return( $sRet);
}

function genCMDBForm( $lCI) {
	$pCI = new CI();
	$pCI->find( $lCI);
	$sItm = $pCI->pFlds['name']['val'];
	$sBrief = $pCI->fetchBrief();
	$iCat = $pCI->getType();
	$sURL = "plugin.php?page=freecmdb/view";
	$sRet = sprintf( "<form method=post action=\"%s\">\n", $sURL);
	$sRet .= "<input type=hidden name=cmdbctx value=adm>\n";
	$sRet .= "<input type=hidden name=cmdbcmd value=cmdbsave>\n";
	$sRet .= sprintf( "<input type=hidden name=cmdbitm value=%d>\n", $lCI);
	$sRet .= sprintf( "<input type=hidden name=cmdb_itm_name value=\"%s\">\n",$sItm);
	$sRet .= "<table align=center>\n";
	$sRet .= "<tr><td colspan=2><br></td></tr>\n";
	$sRet .= sprintf( "<tr><td colspan=2><h1>%s</h1></td></tr>\n", $sItm);
	$sRet .= sprintf( "<tr><td colspan=2><textarea id=cmdb_itm_brief name=cmdb_itm_brief rows=6 cols=80>%s</textarea></td></tr>\n",$sBrief);
//	Selections
	$sRet .= "<tr>\n";
	$sRet .= sprintf( "<td align=center>\n%s</td>\n", genCMDBCatSel( $iCat));
	$sRet .= sprintf( "<td align=center>\n%s</td>\n", genCMDBSave());
	$sRet .= "</tr>\n";
//	Spacer
	$sRet .= "<tr><td colspan=2><br></td></tr>\n";
	$sRet .= "</table></form>\n";
	return( $sRet);
}

function genNewCIBtn() {
	$sURL = "plugin.php?page=freecmdb/view";
	$sRet = sprintf( "<form method=post action=\"%s\">\n", $sURL);
	$sRet .= "<input type=hidden name=cmdbctx value=adm>\n";
	$sRet .= "<input type=hidden name=cmdbcmd value=cientry>\n";
	$sRet .= sprintf( "<input type=submit value=\"%s\">\n", "Create New CI");
	$sRet .= "</form>\n";
	return( $sRet);
}

function genCMDBView( $sOp) {
	require_once( FREECMDBBASE . "/lib/ci.php");
	if ( $sOp == "cmdbsave") saveCI();
	$sRet = "<table>\n";
	$sRet .= sprintf( "<tr><td align=center>%s</td></tr>\n", genNewCIBtn());
	$sRet .= "<tr><td><br><br></td></tr>\n";
	$iIdx = 0;
	if ( !isset( $_POST['cmdb_itm_idx'])) {
		if ( isset( $_GET['cmdb_itm_idx'])) $iIdx = $_GET['cmdb_itm_idx'];
	} else $iIdx = $_POST['cmdb_itm_idx'];
	$pCI = new CI();
	$pLst = $pCI->findAll( auth_get_current_user_id());
	$iRow = 0;
	foreach ( $pLst as $lCI => $sItm) {
		if ( $iRow != $iIdx) {
			$sURL = "plugin.php?page=freecmdb/view";
			$sRet .= sprintf( "<tr><td><form method=post action=\"%s\">\n",$sURL);
			$sRet .= "<input type=hidden name=cmdbctx value=adm>\n";
			$sRet .= sprintf("<input type=hidden name=cmdb_itm_idx value=%d>\n",$iRow);
			$sRet .= "<table align=center width=100%%>";
			$sRet .= "<tr>\n";
			$sRet .= sprintf( "<td width=100%%>%s</td>\n", $sItm);
			$sRet .= sprintf( "<td><input type=submit value=\"%s\"></td>\n","...");
			$sRet .= "</tr>\n";
			$sRet .= "</table></form></td></tr>\n";
		} else $sRet .= sprintf("<tr><td>%s</td></tr>\n", genCMDBForm($lCI));
		$iRow++;
	}
	$sRet .= "</table>\n";
	return( $sRet);
}

function showCMDBCreatePage( $sOp) {
	if ( $sOp == "cinew") addCI();
	if ( $sOp == "cisave") saveCI();
//	Current view
	printf( "<table align=center>\n");
	printf( "<tr><td align=center><br></td></tr>\n");
	printf( "<tr><td align=center>\n");
	if ( $sOp == "ciedit") {
		printf( "%s", genEditItmPage( "plugin.php?page=freecmdb/view"));
	} else {
		printf( "%s", genCaptureEntry( "plugin.php?page=freecmdb/view"));
		printf( "</td></tr>\n");
		printf( "</table>\n");
	// Current capture phase
		printf( "<table align=center>\n");
		printf( "<tr><td align=center><br></td></tr>\n");
		printf( "<tr><td align=center>\n");
		printf( "%s", genCMDBCapture( "plugin.php?page=freecmdb/view"));
		printf( "</td></tr>\n");
		printf( "</table>\n");
	}
	return;
}

function showCMDBPage( $sOp) {
	require_once( FREECMDBBASE . "/lib/stdhtml.php");
	printf( "%s", genBannerCMDB( "CMDB"));
	printf( "<table align=center>\n");
	switch ( $sOp) {
		case "cinew":
		case "cisave":
		case "ciedit":
		case "cientry":
			printf( "<tr><td align=center>\n%s</td></tr>\n", showCMDBCreatePage( $sOp));
			break;

		default:
			printf( "<tr><td align=center>\n%s</td></tr>\n", genCMDBView($sOp));
			break;
	}
	printf( "</table>\n");
	printf( "%s", genFooterCMDB());
	return;
}

?>
