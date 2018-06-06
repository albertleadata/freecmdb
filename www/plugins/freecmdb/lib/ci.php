<?php

require_once( FREECMDBBASE . "/lib/stored.php");

class CI extends Stored {

	function CI() {
		$this->Stored();
		$this->sTable = "cmdbci";
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
		$sQry =	"select id,name from ".$this->sTable." where cat=".$iType." order by id";
//		echo "<br>Query:<br>".$sQry."<br><br>\n";
		$pRslt = db_query_bound( $sQry, Array());
		while ( $pRow = db_fetch_array( $pRslt)) {
			$pRet[$pRow['id']] = $pRow['name'];
		}
//		mysql_free_result( $pRslt);
		return( $pRet);
	}

	function genTypeSel() {
		$iTmp = $this->pFlds['cat']['val'];
		$iType = ($iTmp != null) ? $iTmp : 0;
		$sLT="cmdblut";
		$sQry="select b.name,b.eid from ".$sLT." a join ".$sLT." b on b.ptn=a.idx where a.ptn=0 and a.tag='cicat' order by b.idx";
		$pRslt = db_query_bound( $sQry, Array());
		$iRows = db_num_rows( $pRslt);
		$sRet = "<table>\n";
		$sRet .= sprintf( "<tr><td><strong>%s:</strong></td>\n", "CI Type");
		$sRet .= "<td><select id=cmdb_itm_cat name=cmdb_itm_cat>\n";
		for ( $iRows=db_num_rows( $pRslt); $iRows > 0; $iRows--) {
			$pRow = db_fetch_array( $pRslt);
			$iCLV = $pRow['eid'];
			$sX = ($iCLV == $iType) ? " selected" : "";
			$sRet .= sprintf( "<option value=%d%s>%s\n",
									$iCLV,$sX,$pRow['name']);
		}
		$sRet .= "</select></td>\n";
		$sRet .= "</tr></table>\n";
		return( $sRet);
	}

//	Basic name and description capture/create form
	function genCaptureForm( $sOrigin, $sBtn, $sCmd) {
		$lCI = $this->lID;
//		$sOrigin = "plugin.php?page=freecmdb/view";
//		$sBtn = "cmdbcmd";
//		$sCmd = "cisave";
		$sRet = sprintf( "<form method=post action=\"%s\">\n", $sOrigin);
		$sRet .= "<input type=hidden name=cmdbctx value=adm>\n";
		$sRet .= "<input type=hidden name=cmdbcmd value=".$sCmd.">\n";
		$sRet .= "<table align=center>\n";
		$sCID = "cmdb_itm_name";
		$sCtl = sprintf( "<input type=text name=%s id=%s size=61 maxlength=%d value=\"%s\">", $sCID, $sCID, 80, "");
		$sRet .= sprintf(	"<tr><td><strong>%s:<strong></td><td>%s</td></tr>\n",
								"New CI Name", $sCtl);
		$sCtl = sprintf( "<textarea name=%s rows=8 cols=60>%s</textarea>",
								"cmdb_itm_brief", "");
		$sRet .= sprintf( "<tr><td valign=top>%s:</td><td>%s</td></tr>",
								"Description", $sCtl);
	//	Add button
		$sRet .= sprintf(	"<tr><td colspan=2 align=center>%s</td></tr>\n",
								"<input type=submit value=Add>");
	//	Terminate form table layout
		$sRet .= "</table>\n";
		$sRet .= "</form>\n";
		return( $sRet);
	}

//	Full edit form
	function genEditForm( $sOrigin, $sBtn, $sCmd) {
		$lCI = $this->lID;
		$sRet = sprintf( "<form method=post action=\"%s\">\n", $sOrigin);
		$sRet .= "<input type=hidden name=cmdbctx value=adm>\n";
		$sRet .= "<input type=hidden name=cmdbcmd value=".$sCmd.">\n";
		$sRet .= "<input type=hidden name=cmdbitm value=".$lCI.">\n";
		$sRet .= "<table align=center>\n";
		$sRet .= sprintf( "<tr><td align=center colspan=2>Item %d</td></tr>", $this->lID);
		$sCtl = sprintf( "<input type=text name=%s size=61 maxlength=80 value=\"%s\">",
								"cmdb_itm_name", $this->pFlds['name']['val']);
		$sRet .= sprintf( "<tr><td>%s:</td><td>%s</td></tr>", "Name", $sCtl);
		$sCtl = sprintf( "<textarea name=%s rows=8 cols=60>%s</textarea>",
								"cmdb_itm_brief", $this->pFlds['brief']['val']);
		$sRet .= sprintf( "<tr><td valign=top>%s:</td><td>%s</td></tr>", "Description", $sCtl);
		$sRet .= sprintf( "<tr><td align=center></td></tr>");
	//	Last form row ...
		$sRet .= "<tr>\n";
	//	CI Type selector
		$sRet .= sprintf("<td align=center>%s</td>\n", $this->genTypeSel());
		$sRet .= "<td align=center>\n";
	//	Button box as sub-table
		$sRet .= "<table align=center><tr>\n";
		$sRet .= "<td align=center width=128><input type=submit value=Cancel></td>\n";
		$sRet .= sprintf( "<td align=center width=128><input type=submit value=%s></td>\n", $sBtn);
		$sRet .= "</tr></table>\n";
		$sRet .= "</td>\n";
	//	Close last form row ...
		$sRet .= "</tr>\n";
	//	Terminate view table layout
		$sRet .= "</table>\n";
		$sRet .= "</form>\n";
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
