<?php

require_once( FREECMDBBASE . "/lib/stored.php");

class CI extends Stored {

	function CI() {
		$this->Stored();
		$this->sTable = "mantis_plugin_freecmdb_itm";
		$this->addField( "id", "int", "ID", 1000, "cmdb_itm_id");
		$this->addField( "chg", "date", "Changed", 0, "cmdb_itm_chg");
		$this->addField( "cat", "int", "Type", 0, "cmdb_itm_cat");
		$this->addField( "who", "int", "Who", 0, "cmdb_itm_who");
		$this->addField( "name", "str", "Name", 0, "cmdb_itm_name");
		$this->addField( "alias", "str", "Name", 0, "cmdb_itm_alias");
		$this->addField( "url", "txt", "URL", 0, "cmdb_itm_url");
		$this->addField( "wiki", "txt", "Wiki", 0, "cmdb_itm_wiki");
		$this->addField( "brief", "txt", "Brief", 0, "cmdb_itm_brief");
		return;
	}

	function creationClause() {
//		return( "(who,created) values (".$this->pFlds['who']['val'].",now())");
		return( "(chg) values (now())");
	}

	function getHeaderField() {
		return( 'name');
	}

	function getDisplayedFields() {
		$pRet = array(	0 => 'id',
							1 => 'chg',
							2 => 'cat',
							3 => 'who',
							4 => 'name',
							5 => 'alias',
							6 => 'url',
							7 => 'wiki',
							8 => 'brief' );
		return( $pRet);
	}

	function getClass( $sCls) {
		$sRet = "";
		switch ( $sCls) {
			default:
				$sRet = "class=txtWhite";
				break;
		};
		return( $sRet);
	}

	function getBackLink() {
		$sRet =	"<a href=\"index.php?ctx=itm&cmd=qry&".
					"cmdb_itm_id=".$this->lID."\">Back ...</a>";
		return( $sRet);
	}

	function findAll( $sUsr) {
		$pRet = array();
		$sQry =	"select id,name from ".$this->sTable." order by cat,id";
//		echo "<br>Query:<br>".$sQry."<br><br>\n";
		$pRslt = db_query_bound( $sQry, Array());
		while ( $pRow = db_fetch_array( $pRslt)) {
			$pRet[$pRow['id']] = $pRow['name'];
		}
//		mysql_free_result( $pRslt);
		return( $pRet);
	}

	function findByType( $iUsr, $iType) {
//		require_once( "lib/dbcxn.php");
		$pRet = array();
		$sQry =	"select id,name from ".$this->sTable." where cat=".$iType."";
//		echo "<br>Query:<br>".$sQry."<br><br>\n";
		$pRslt = db_query_bound( $sQry, Array());
		while ( $pRow = db_fetch_array( $pRslt)) {
			$pRet[$pRow['id']] = $pRow['name'];
		}
//		mysql_free_result( $pRslt);
		return( $pRet);
	}

	function genEditView( $sBtn, $sCmd) {
		$sRet = "<table align=center>\n";
		$sRet .= sprintf( "<tr><td align=center colspan=2>Item %d</td></tr>", $this->lID);
		$sCtl = sprintf( "<textarea name=%s rows=1 cols=80 maxlength=80>%s</textarea>",
								"cmdb_itm_name", $this->pFlds['name']['val']);
		$sRet .= sprintf( "<tr><td>%s:</td><td>%s</td></tr>", "Name", $sCtl);
		$sCtl = sprintf( "<textarea name=%s rows=24 cols=80>%s</textarea>",
								"cmdb_itm_brief", $this->pFlds['brief']['val']);
		$sRet .= sprintf( "<tr><td>%s:</td><td>%s</td></tr>", "Brief", $sCtl);
		$sRet .= sprintf( "<tr><td align=center></td></tr>");
		$sRet .= "<tr><td align=center colspan=2>\n";
	//	Button box as sub-table
		$sRet .= "<table align=center><tr>\n";
		$sRet .= "<td align=center width=128><input type=submit value=Cancel></td>\n";
		$sRet .= sprintf( "<td align=center width=128><input type=submit name=%s value=%s></td>\n", $sBtn, $sCmd);
		$sRet .= "</tr></table>\n";
	//	Terminate view table layout
		$sRet .= "</td></tr>\n";
		$sRet .= "</table>\n";
		return( $sRet);
	}

	function fetchBrief() {
		return( $this->pFlds['brief']['val']);
	}

	function getType() {
		$iRet = $this->pFlds['cat']['val'];
		if ( $iRet == null) $iRet = 0;
		return( $iRet);
	}

	function setType( $iType) {
		$this->pFlds['cat']['val'] = $iType;
		return;
	}

	function setWho( $iUsr) {
		$this->pFlds['who']['val'] = $iUsr;
		return;
	}

	function setName( $sTxt) {
		$this->pFlds['name']['val'] = $sTxt;
		return;
	}

	function setWiki( $sWiki) {
		$this->pFlds['wiki']['val'] = $sWiki;
		return;
	}

	function setBrief( $sTxt) {
		$this->pFlds['brief']['val'] = $sTxt;
		return;
	}
}

?>
