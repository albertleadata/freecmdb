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
