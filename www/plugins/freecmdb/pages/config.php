<?php

function showCMDBCfgMain() {
	printf( "<table align=center>\n");
	printf( "<tr><td align=center><h1>CMDB Config</h1></td></tr>\n");
	printf( "</table>\n");
	return;
}

function showCMDBConfig() {
	require_once( 'core.php' );
	require_once( 'core/authentication_api.php' );
	require_once( 'core/compress_api.php' );
	require_once( 'core/config_api.php' );
	require_once( 'core/print_api.php' );
	if ( auth_is_user_authenticated()) {
		compress_enable();
		html_robots_noindex();
		layout_page_header_begin( "CMDB");
		$t_current_user_id = auth_get_current_user_id();
	//	Mantis redirection logic
		if ( current_user_get_pref( 'refresh_delay') > 0) {
			html_meta_redirect(	'my_view_page.php',
										current_user_get_pref( 'refresh_delay')*60);
		}
		layout_page_header_end();
	//	Mantis page heading
		layout_page_begin( __FILE__);
	//	Plugin-generated content
		$sCmd = gpc_get_string( "cmdbcmd", "menu");
		switch ( $sCmd) {
			case "menu": showCMDBCfgMain(); break;
		}
	//	Mantis page footer
		layout_page_end();
	}
	return;
}

showCMDBConfig();

?>
