<?php

//	# Create statement for table used in this code ...
//	create table oncall (
//		id bigint(11) primary key auto_increment,
//		created datetime,
//		top datetime,
//		btm datetime,
//		chg datetime,
//		ptn bigint(20),
//		gid bigint(20),
//		name varchar(40),
//		cell varchar(20),
//		alt varchar(40),
//		phone varchar(20),
//		brief text
//	);

function genRotationMembers() {
	$pRet = array(	"John Doe" => "555-123-4567", "Daffy Duck" => "555-666-3142",
						"Jane Doe" => "555-987-4321", "Bugs Bunny" => "555-192-2837");
	return( $pRet);
}

function applyRotation( $dFirst, $iWks, $sPrev) {
	$dPrev = $dFirst;
	$pRoster = genRotationMembers();
	$pReg = array();
	$iIdx=-1;
	$iTkn = 0;
	foreach ( $pRoster as $sWho => $sCell) {
		$pReg[$iTkn] = $sWho;
		$iTkn++;
		if ( $sWho == $sPrev) $iIdx=$iTkn;
	}
	if ( ($iIdx < 0) || ($iIdx == $iTkn)) $iIdx=0;
	$pRet = array();
	for ( $i=0; $i < $iWks; $i++) {
		$dNext = strtotime( "+1 week", $dPrev);
		$sNext = sprintf( "%s", date( "Y-m-d", $dNext));
		$pRow = array();
		$pRow['name'] = $pReg[$iIdx];
		$iIdx = ($iIdx < ($iTkn-1)) ? $iIdx+1 : 0;
		$dPrev = $dNext;
		$pRow['alt'] = $pReg[$iIdx];
		$pRet[$sNext] = $pRow;
	}
	return( $pRet);
}

function genRotation( $iNew) {
	$sQry = "select top,name,alt from oncall where ptn=".db_param()." order by top desc limit 0,1";
	$pRslt = db_query_bound( $sQry, Array( '1'));
	$iRslt = db_num_rows( $pRslt);
	if ( $iRslt > 0) {
		$pRow = db_fetch_array( $pRslt);
		$dPrev = strtotime( $pRow['top']);
		$pNew = applyRotation( $dPrev, $iNew, $pRow['name']);
		$pRoster = genRotationMembers();
		foreach ( $pNew as $sWk => $pWho) {
			$sWho = $pWho['name'];
			$sAlt = $pWho['alt'];
			$sCell = $pRoster[$sWho];
			$sPhone = $pRoster[$sAlt];
			$sQry = "insert into oncall (ptn,created,top,name,cell,alt,phone) values (1,now(),".db_param().",".db_param().",".db_param().",".db_param().",".db_param().")";
			db_query_bound( $sQry, Array( $sWk, $sWho, $sCell, $sAlt, $sPhone));
			$lNID = db_insert_id( "oncall");
		}
	}
	return;
}

function showRotation( $dFirst) {
	$sFirst = date( "Y-m-d", $dFirst);
	$sQry = "select * from oncall where ptn=".db_param()." and top >= '".$sFirst."' order by top";
//	printf(	"<tr><td colspan=5>DEBUG: %s</td><tr>", $sQry);
	$pRslt = db_query_bound( $sQry, Array( '1'));
	$iRslt = db_num_rows( $pRslt);
	for ( $iRow=0; $iRow < $iRslt; $iRow++) {
		$pRow = db_fetch_array( $pRslt);
		$dWhen = strtotime( $pRow['top']);
		printf(	"<tr>");
		printf(	"<td align=center>%s:</td>", date( "Y-m-d", $dWhen));
		printf(	"<td align=center>%s</td>", $pRow['name']);
		printf(	"<td align=center>(%s)</td>", $pRow['cell']);
		printf(	"<td align=center>%s</td>", $pRow['alt']);
		printf(	"<td align=center>(%s)</td>", $pRow['phone']);
		printf(	"</tr>\n");
	}
	return;
}

function showOnCallRotationView() {
	$sLib = FREECMDBBASE . "/lib";
	require_once( $sLib."/oncall.php");
	printf( "<table align=center>\n");
	printf( "<tr><td align=center><h1>On-Call</h1></td></tr>\n");
	printf( "</table>\n");
	printf( "<table align=center>\n");
	$dPrev = strtotime( "last Sunday");
	showRotation( $dFirst);
	printf( "</table>\n");
	return;
}

?>
