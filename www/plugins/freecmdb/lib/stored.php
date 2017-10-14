<?php class Stored {

	var $lID;
	var $sPrd;
	var $sTable;
	var $pFlds;
	var $sLog;
	var $sURL;

	function Stored() {
		$this->sPrd = "ripstock";
		$this->sURL = "index.php";
		$this->sTable = "";
		$this->lID = 0;
//		$this->bPopulated = false;
		$this->sLog = null;
		$this->pFlds = null;
		$this->addField( 'id', 'int', "ID", 0, "id");
		return;
	}

	function addField( $sTag, $sType, $sLabel, $iMode, $sPost) {
		$this->pFlds[$sTag]['type'] = $sType;
		$this->pFlds[$sTag]['label'] = $sLabel;
		$this->pFlds[$sTag]['mode'] = $iMode;
		$this->pFlds[$sTag]['post'] = $sPost;
		$this->pFlds[$sTag]['old'] = null;
		$this->pFlds[$sTag]['val'] = null;
		return;
	}

	function getTxtFldClass( $sCls) {
		$sRet = "";
		return( $sRet);
	}

	function getClass( $sCls) {
		$sRet = "";
		return( $sRet);
	}

	function getQry() {
		return "";
	}

	function getUpd() {
		$sRet = $this->updateClause();
		$bFirst = ($sRet == "") ? true : false;
//		$pLst = array_keys( $this->pFlds);
//		$iLst = count( $pLst);
//		for ( $iFld=0; $iFld < $iLst; $iFld++) {
		foreach ( $this->pFlds as $sKey => $pFld) {
			$sUpd = "";
//			$sKey = $pLst[$iFld];
//			$pFld = $this->pFlds[$sKey];
			$iType = ($sKey != "id") ?  $pFld['type'] : -1;
			$sV1 = isset( $pFld['old']) ? $pFld['old'] : null;
			$sV2 = isset( $pFld['val']) ? $pFld['val'] : null;
//			printf( "DEBUG - comparing %s: %s -> %s<br>\n", $sKey, $sV1, $sV2);
			if ( $sV1 != $sV2) {
				switch ( $iType) {
					case 'str':	// String
					case 'txt':	// Long text
						if ( !$bFirst) $sUpd .= ",";
						if ( ($sV2 == null) || ($sV2 == "")) {
							$sUpd .= $sKey."=null";
						} else $sUpd .= $sKey."='".str_replace("'","''",$sV2)."'";
						$bFirst = false;
						break;

					case 'int':	// Integer
					case 'float':	// Floating-point
						if ( !$bFirst) $sUpd .= ",";
						if ( ($sV2 == null) || ($sV2 == "")) {
							$sUpd .= $sKey."=null";
						} else $sUpd .= $sKey."=".$sV2;
						$bFirst = false;
						break;

					case 'date':	// Date
						if ( !$bFirst) $sUpd .= ",";
						if ( ($sV2 == null) || ($sV2 == "")) {
							$sUpd .= $sKey."=null";
						} else $sUpd .= $sKey."='".str_replace("'","''",$sV2)."'";
						$bFirst = false;
						break;

					default:
						break;
				};
			}
			$sRet .= $sUpd;
		}
		return( $sRet);
	}

	function creationClause() {
		return( "");
	}

	function updateClause() {
		return( "");
	}

	function create() {
//		require_once( "lib/dbcxn.php");
		$lRet = 0;
//		printf( "DEBUG - create() invoked<br>\n");
		$sCrt = $this->creationClause();
		$sQry =	"insert into ".$this->sTable." ".$sCrt;
//		echo "DEBUG - Insertion query:<br>".$sQry."<br><br>";
//		if ( mysql_query( $sQry)) {
		if ( db_query_bound( $sQry, Array())) {
			$this->lID = db_insert_id( $this->sTable);
//			echo "DEBUG - New ID:<br>".$this->lID."<br><br>";
			$this->pFlds['id']['old'] = $this->lID;
			$this->pFlds['id']['val'] = $this->lID;
			$lRet = $this->lID;
//			if ( !$bPopulated) $this->find( $this->lID);
//			$this->find( $this->lID);
//			$lRet = $this->lID;
		}
		return( $lRet);
	}

	function set( $sTag, $sVal) {
//		$this->pFlds[$sTag]['old'] = $sVal;
		$this->pFlds[$sTag]['val'] = $sVal;
		return;
	}

	function load( $pNewFlds, $bForce) {
		$pKeys = array_keys( $pNewFlds);
		$iKeys = count( $pKeys);
		for ( $iKey=0; $iKey < $iKeys; $iKey++) {
			$sKey = $pKeys[$iKey];
			$this->pFlds[$sKey]['val'] = $pNewFlds[$sKey];
			if ( $bForce) $this->pFlds[$sKey]['old'] = $pNewFlds[$sKey];
		}
		return;
	}

	function find( $lOID) {
//		require_once( "lib/dbcxn.php");
//		printf( "DEBUG - find( lOID) invoked<br>\n");
		$bRet = false;
		$sQry =	"select * from ".$this->sTable." where id=".$lOID;
//		echo "<br>Query:<br>".$sQry."<br><br>\n";
//		$pRslt = mysql_query( $sQry);
		$pRslt = db_query_bound( $sQry, Array());
//		$pRow = ($pRslt != null) ? mysql_fetch_assoc( $pRslt) : null;
		$pRow = ($pRslt != null) ? db_fetch_array( $pRslt) : null;
		if ( $pRow != null) {
			$this->load( $pRow, true);
			$this->lID = $this->pFlds['id']['old'];
			$bRet = true;
		}
//		if ( $pRslt != null) mysql_free_result( $pRslt);
		return( $bRet);
	}

	function commit() {
//		require_once( "lib/dbcxn.php");
		$bRet = false;
//		printf( "DEBUG - commit() invoked<br>\n");
		if (	isset( $this->pFlds['id']['old']) &&
				($this->pFlds['id']['old'] != null)) {
			$this->lID = $this->pFlds['id']['old'];
		}
		if ( ($this->lID == null) || ($this->lID <= 0)) {
//			$bPopulated = true;
			$this->lID = $this->create();
//			$this->pFlds['id']['old'] = $this->lID;
//			$this->pFlds['id']['val'] = $this->lID;
		}
		if ( $this->lID > 0) {
			$this->lID = $this->pFlds['id']['old'];
			$sUpd = $this->getUpd();
			$sQry =	"update ".$this->sTable." set ".$sUpd." where id=".$this->lID;
//			echo "DEBUG - update query:<br>".$sQry."<br><br>\n";
//			$bRet = mysql_query( $sQry);
			$bRet = db_query_bound( $sQry, Array());
			if ( !$bRet) {
				echo "Update for ".$this->sTable." ".$this->lID." failed<br>\n";
				echo "Query:<br>".$sQry."<br><br>\n";
			} else {
				if ( $this->sLog != null) {
					$this->logUpdate( 'UPDEVT', $sQry);
				}
			}
		} else echo "ERROR - init failed on missing ID<br>";
		return( $bRet);
	}

	function remove() {
//		require_once( "lib/dbcxn.php");
		$bRet = false;
		if ( $this->lID > 0) {
			$lDel = -$this->lID;
			$sQry =	"update ".$this->sTable." set id=".$lDel." where id=".$this->lID;
//			echo "Update query:<br>".$sQry."<br><br>\n";
//			$bRet = mysql_query( $sQry);
			$bRet = db_query_bound( $sQry, Array());
			if ( !$bRet) {
				echo "Update for ".$this->sTable." ".$this->lID." failed<br>\n";
				echo "Query:<br>".$sQry."<br><br>\n";
			} else {
				if ( $this->sLog != null) {
					$this->logUpdate( 'UPDEVT', $sQry);
				}
			}
			$this->lID = $lDel;
			$this->pFlds['id']['val'] = $lDel;
		} else echo "delete from "+$this->sTable+" failed on missing ID";
		return( $bRet);
	}

	function logUpdate( $sTag, $sQry) {
		$pNow = getdate();
		$sNow = sprintf(	"%02d-%02d-%04d %02d:%02d:%02d",
								$pNow['mon'], $pNow['mday'], $pNow['year'],
								$pNow['hours'], $pNow['minutes'],
								$pNow['seconds']);
		$sOut =	$sNow." | ".$_SESSION['sid_uid']." | ".$sTag." | ".$this->lID.
					" | ".$sQry;
		$pIn = fopen( $this->sLog, "a");
		fwrite( $pIn, "".$sOut."\n");
		fclose( $pIn);
		return( $_SESSION['sid_uid']." (".$sNow.")");
	}

	function loadFromPost() {
	// Pre-load from DB
//		printf( "DEBUG - loadFromPost() invoked<br>\n");
		$sKey = 'id';
		$sVal = $this->pFlds[$sKey]['post'];
		if ( $this->lID < 1) {
//			printf( "DEBUG - checking %s in session: ...<br>\n", $sVal);
			if ( isset( $_POST[$sVal])) {
//				printf( "DEBUG - found %s in session: %s<br>\n", $sVal, $_POST[$sVal]);
				$this->lID = $_POST[$sVal];
				$this->pFlds[$sKey]['old']	= $this->lID;
				$this->pFlds[$sKey]['val']	= $this->lID;
			}
//			if ( $this->lID > 0) $this->find( $this->lID);
		}

	//	Set values from POST variables...
		$pKeys = array_keys( $this->pFlds);
		$iLst = count( $pKeys);
		for ( $iIdx=0; $iIdx < $iLst; $iIdx++) {
			$sFld = $pKeys[$iIdx];
			if ( $sFld != 'id') {
				$sPV = $this->pFlds[$sFld]['post'];
				if ( !$this->customPostLoad( $sPV)) {
//					printf( "DEBUG - checking %s (%s) in session: ...<br>\n", $sFld, $sPV);
					$sNewVal = isset( $_POST[$sPV]) ? $_POST[$sPV] : null;
					if ( $sNewVal != null) {
//						printf( "DEBUG - found %s in session: %s<br>\n", $sFld, $sNewVal);
						$this->pFlds[$sFld]['val'] = $sNewVal;
						unset( $_POST[$sPV]);
					}
				} else unset( $_POST[$sPV]);
			}
		}
		return;
	}

	function loadFromSession() {
	// Pre-load from DB
//		printf( "DEBUG - loadFromSession() invoked<br>\n");
		$sKey = 'id';
		$sVal = $this->pFlds[$sKey]['post'];
		if ( $this->lID < 1) {
//			printf( "DEBUG - checking %s in session: ...<br>\n", $sVal);
			if ( isset( $_SESSION[$sVal])) {
//				printf( "DEBUG - found %s in session: %s<br>\n", $sVal, $_SESSION[$sVal]);
				$this->lID = $_SESSION[$sVal];
				$this->pFlds[$sKey]['old']	= $this->lID;
				$this->pFlds[$sKey]['val']	= $this->lID;
				unset( $_SESSION[$sVal]);
			}
//			if ( $this->lID > 0) $this->find( $this->lID);
		}

	//	Set values from POST variables...
		$pKeys = array_keys( $this->pFlds);
		$iLst = count( $pKeys);
		for ( $iIdx=0; $iIdx < $iLst; $iIdx++) {
			$sFld = $pKeys[$iIdx];
			if ( $sFld != 'id') {
				$sPV = $this->pFlds[$sFld]['post'];
				if ( !$this->customPostLoad( $sPV)) {
//					printf( "DEBUG - checking %s (%s) in session: ...<br>\n", $sFld, $sPV);
					$sNewVal = isset( $_SESSION[$sPV]) ? $_SESSION[$sPV] : null;
					if ( $sNewVal != null) {
//						printf( "DEBUG - found %s in session: %s<br>\n", $sFld, $sNewVal);
						$this->pFlds[$sFld]['val'] = $sNewVal;
						unset( $_SESSION[$sPV]);
					}
				} else unset( $_SESSION[$sPV]);
			}
		}
		return;
	}

	function getHeaderField() {
		return( 'id');
	}

	function getDisplayedFields() {
		$pRet = array(	0 => 'id' );
		return( $pRet);
	}

	function getEditLink() {
		return( "<input type=submit name=submit value=\"Edit\">");
	}

	function getSaveLink() {
		return( "<input type=submit name=submit value=\"Save\">");
	}

	function getBackLink() {
		$sRet = "<a href=\"view.php\">SID Home...</a>";
		return( $sRet);
	}

	function customPostLoad( $sFld) {
		return( FALSE);
	}

	function showCustom( $iMode) {
		return;
	}

	function showField( $sFld, $iMode) {
		$bRet = false;
		$pFld = $this->pFlds[$sFld];
		$sTFC = $this->getTxtFldClass( "");
		if ( $pFld['mode'] < $iMode) {
			echo "<td><strong>".$pFld['label'].":</strong></td>";
			echo "<td>";
			switch ( $pFld['type']) {
				case 'txt':
					echo "<textarea name=\"".$pFld['post']."\" cols=30 rows=6>";
					echo "".$pFld['val'];
					echo "</textarea>\n";
					break;

				case 'bin':
					echo "<italic>binary format not showable</italic>";
					break;

				default:
					echo "<input type=text ".$sTFC." name=\"".$pFld['post']."\" ".
							"value=\"".$pFld['val']."\">";
					break;
			};
			echo "</td>\n";
			$bRet = true;
		} else {
			echo "<td><strong>".$pFld['label'].":</strong></td>";
			if ( $sFld == 'id') {
				echo	"<td><input type=hidden name=\"".$pFld['post']."\" ".
						"value=".$pFld['val'].">".$pFld['val']."</td>\n";
			} else {
				echo "<td>";
				switch ( $pFld['type']) {
					case 'bin':
						echo "<italic>binary format not showable</italic>";
						break;

					default:
						echo $pFld['val'];
						break;
				};
				echo "</td>\n";
			}
		}
		return( $bRet);
	}

	function show( $iW, $iMode) {
		$bEdit = false;
		$bTmp = false;
		$sW = ($iW > 0) ? ("width=".$iW) : "";
		$sIDF = $this->pFlds['id']['post'];
		echo "<table ".$this->getClass( "")." ".$sW.">\n";
		printf( "<form method=post action=\"%s\">\n", $this->sURL);
		printf( "<input type=hidden name=ctx value=%s>\n", $this->sTable);
		printf( "<input type=hidden name=cmd value=save>\n");
		printf( "<input type=hidden name=%s value=%ld>\n", $sIDF, $this->lID);
		if ( $iW > 0) {
			$sKey = $this->getHeaderField();
			echo "<tr bgcolor=\"#A8A8A8\">\n";
			echo "<th colspan=4>".$this->pFlds[$sKey]['val']."</th>";
			echo "</tr>\n";
			$pLst = $this->getDisplayedFields();
			$iLst = count( $pLst);
			for ( $iIdx=0; $iIdx < $iLst; $iIdx++) {
				$sKey = $pLst[$iIdx];
				echo "<tr>\n";
				$bTmp = $this->showField( $sKey, $iMode);
				if ( $bTmp) $bEdit = true;
				echo "</tr>\n";
			}
		} else $bEdit = $this->showCustom( $iMode);

		echo "<tr>\n";
		if ( $bEdit) {
			echo "<td>".$this->getSaveLink()."</td>\n";
		} else echo "<td>".$this->getEditLink()."</td>\n";
		printf( "<td>%s</td>\n", "" /* $this->getBackLink() */ );
		echo "</tr>\n";
		echo "</form>\n";
		echo "</table>\n";
		return;
	}

//	var $bPopulated;
} ?>
