<?php

function genStdInitHTML() {
	$sW3C = "-//W3C//DTD HTML 4.01 Transitional//EN";
	$sRet = sprintf( "<!DOCTYPE html PUBLIC \"%s\">\r\n", $sW3C);
	$sRet .= "<html>\r\n";
	$sRet .= "<head>\r\n";
//	$sRet .= sprintf( "<link href=\"%s\" rel=stylesheet type=text/css>\r\n", FREECMDBBASE."/files/theme.css");
	$sRet .= "</head>\r\n";
	$sRet .= "<body>\r\n";
	return( $sRet);
}

function genStdCloseHTML() {
	$sRet = "</body>\r\n";
	$sRet .= "</html>\r\n";
	return( $sRet);
}

function genBannerCMDB( $sHzn) {
	$sLGC = "#CCFFCC";
	$sIDL = FREECMDBBASE . "/files";
	$sRet = "<table align=center>\n";
	$sRet .= "<tr><td align=center><h1>CMDB</h1></td></tr>\n";
	$sRet .= "</table>\n";
// Toolbar
	$sRet .= "<table align=center>\n";
	$sRet .= "<tr>\n";
//	Capture ...
	$sURL = "plugin.php?page=freecmdb/view";
	$sImg = $sIDL . "/cmdb-logo.png";
	$sBGC = ($sHzn == "capture") ? " bgcolor=\"".$sLGC."\"" : "";
	$sRet .= sprintf( "<td align=center%s><a href=\"%s\"><img src=\"%s\" style=\"%s\"></a></td>\n", $sBGC, $sURL, $sImg, "height:128px;border:0;");
	$sRet .= "<td width=64> </td>\n";
//	Clarify & Organize ...
	$sURL = "plugin.php?page=freecmdb/view&cmdbctx=clarify";
	$sImg = $sIDL . "/cmdb-logo.png";
	$sBGC = ($sHzn == "clarify") ? " bgcolor=\"".$sLGC."\"" : "";
	$sRet .= sprintf( "<td align=center%s><a href=\"%s\"><img src=\"%s\" style=\"%s\"></a></td>\n", $sBGC, $sURL, $sImg, "height:128px;border:0;");
	$sRet .= "<td width=64> </td>\n";
//	Reflect ...
	$sURL = "plugin.php?page=freecmdb/view&cmdbctx=reflect";
	$sImg = $sIDL . "/cmdb-logo.png";
	$sBGC = ($sHzn == "reflect") ? " bgcolor=\"".$sLGC."\"" : "";
	$sRet .= sprintf( "<td align=center%s><a href=\"%s\"><img src=\"%s\" style=\"%s\"></a></td>\n", $sBGC, $sURL, $sImg, "height:128px;border:0;");
	$sRet .= "<td width=64> </td>\n";
// Engage ...
	$sURL = "plugin.php?page=freecmdb/view&cmdbctx=engage";
	$sImg = $sIDL . "/cmdb-logo.png";
	$sBGC = ($sHzn == "engage") ? " bgcolor=\"".$sLGC."\"" : "";
	$sRet .= sprintf( "<td align=center%s><a href=\"%s\"><img src=\"%s\" style=\"%s\"></a></td>\n", $sBGC, $sURL, $sImg, "height:128px;border:0;");
//	Wrap up the table
	$sRet .= "</tr>\n";
	$sRet .= "</table>\n";
	return( $sRet);
}

function genFooterCMDB() {
	$sRet = "<table align=center>\n";
	$sRet .=  "<tr><td align=center><br></td></tr>\n";
	$sRet .= sprintf(	"<tr><td align=center><a href=\"%s\">%s</a></td></tr>\n",
							 "my_view_page.php", "Mantis: My View");
	$sRet .= "</table>\n";
	return( $sRet);
}

?>
