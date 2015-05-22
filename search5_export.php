<?php 
@ini_set("display_errors","1");
@ini_set("display_startup_errors","1");
include("include/dbcommon.php");
include("classes/searchclause.php");
session_cache_limiter("none");

include("include/search5_variables.php");

if(!@$_SESSION["UserID"])
{ 
	$_SESSION["MyURL"]=$_SERVER["SCRIPT_NAME"]."?".$_SERVER["QUERY_STRING"];
	header("Location: login.php?message=expired"); 
	return;
}
if(!CheckSecurity(@$_SESSION["_".$strTableName."_OwnerID"],"Export"))
{
	echo "<p>"."You don't have permissions to access this table"."<a href=\"login.php\">"."Back to login page"."</a></p>";
	return;
}

$layout = new TLayout("export","Extravaganza1Orange","MobileOrange");
$layout->blocks["top"] = array();
$layout->containers["export"] = array();

$layout->containers["export"][] = array("name"=>"exportheader","block"=>"","substyle"=>2);


$layout->containers["export"][] = array("name"=>"exprange_header","block"=>"rangeheader_block","substyle"=>3);


$layout->containers["export"][] = array("name"=>"exprange","block"=>"range_block","substyle"=>1);


$layout->containers["export"][] = array("name"=>"expoutput_header","block"=>"","substyle"=>3);


$layout->containers["export"][] = array("name"=>"expoutput","block"=>"","substyle"=>1);


$layout->containers["export"][] = array("name"=>"expbuttons","block"=>"","substyle"=>2);


$layout->skins["export"] = "fields";
$layout->blocks["top"][] = "export";$page_layouts["search5_export"] = $layout;


// Modify query: remove blob fields from fieldlist.
// Blob fields on an export page are shown using imager.php (for example).
// They don't need to be selected from DB in export.php itself.
//$gQuery->ReplaceFieldsWithDummies(GetBinaryFieldsIndices());

//	Before Process event
if($eventObj->exists("BeforeProcessExport"))
	$eventObj->BeforeProcessExport($conn);

$strWhereClause = "";
$strHavingClause = "";
$strSearchCriteria = "and";
$selected_recs = array();
$options = "1";

header("Expires: Thu, 01 Jan 1970 00:00:01 GMT"); 
include('include/xtempl.php');
include('classes/runnerpage.php');
$xt = new Xtempl();
$id = postvalue("id") != "" ? postvalue("id") : 1;

$phpVersion = (int)substr(phpversion(), 0, 1); 
if($phpVersion > 4)
{
	include("include/export_functions.php");
	$xt->assign("groupExcel", true);
}
else
	$xt->assign("excel", true);

//array of params for classes
$params = array("pageType" => PAGE_EXPORT, "id" => $id, "tName" => $strTableName);
$params["xt"] = &$xt;
if(!$eventObj->exists("ListGetRowCount") && !$eventObj->exists("ListQuery"))
	$params["needSearchClauseObj"] = false;
$pageObject = new RunnerPage($params);

if (@$_REQUEST["a"]!="")
{
	$options = "";
	$sWhere = "1=0";	

//	process selection
	$selected_recs = array();
	if (@$_REQUEST["mdelete"])
	{
		foreach(@$_REQUEST["mdelete"] as $ind)
		{
			$keys=array();
			$keys["idttl_actions"] = refine($_REQUEST["mdelete1"][mdeleteIndex($ind)]);
			$selected_recs[] = $keys;
		}
	}
	elseif(@$_REQUEST["selection"])
	{
		foreach(@$_REQUEST["selection"] as $keyblock)
		{
			$arr=explode("&",refine($keyblock));
			if(count($arr)<1)
				continue;
			$keys = array();
			$keys["idttl_actions"] = urldecode($arr[0]);
			$selected_recs[] = $keys;
		}
	}

	foreach($selected_recs as $keys)
	{
		$sWhere = $sWhere . " or ";
		$sWhere.=KeyWhere($keys);
	}


	$strSQL = gSQLWhere($sWhere);
	$strWhereClause=$sWhere;
	
	$_SESSION[$strTableName."_SelectedSQL"] = $strSQL;
	$_SESSION[$strTableName."_SelectedWhere"] = $sWhere;
	$_SESSION[$strTableName."_SelectedRecords"] = $selected_recs;
}

if ($_SESSION[$strTableName."_SelectedSQL"]!="" && @$_REQUEST["records"]=="") 
{
	$strSQL = $_SESSION[$strTableName."_SelectedSQL"];
	$strWhereClause = @$_SESSION[$strTableName."_SelectedWhere"];
	$selected_recs = $_SESSION[$strTableName."_SelectedRecords"];
}
else
{
	$strWhereClause = @$_SESSION[$strTableName."_where"];
	$strHavingClause = @$_SESSION[$strTableName."_having"];
	$strSearchCriteria = @$_SESSION[$strTableName."_criteria"];
	$strSQL=gSQLWhere($strWhereClause, $strHavingClause, $strSearchCriteria);
}

$mypage = 1;
if(@$_REQUEST["type"])
{
//	order by
	$strOrderBy = $_SESSION[$strTableName."_order"];
	if(!$strOrderBy)
		$strOrderBy = $gstrOrderBy;
	$strSQL.=" ".trim($strOrderBy);

	$strSQLbak = $strSQL;
	if($eventObj->exists("BeforeQueryExport"))
		$eventObj->BeforeQueryExport($strSQL,$strWhereClause,$strOrderBy);
//	Rebuild SQL if needed
	if($strSQL!=$strSQLbak)
	{
//	changed $strSQL - old style	
		$numrows=GetRowCount($strSQL);
	}
	else
	{
		$strSQL = gSQLWhere($strWhereClause,$strHavingClause, $strSearchCriteria);
		$strSQL.=" ".trim($strOrderBy);
		$rowcount=false;
		if($eventObj->exists("ListGetRowCount"))
		{
			$masterKeysReq=array();
			for($i = 0; $i < count($pageObject->detailKeysByM); $i ++)
				$masterKeysReq[]=$_SESSION[$strTableName."_masterkey".($i + 1)];
			$rowcount=$eventObj->ListGetRowCount($pageObject->searchClauseObj,$_SESSION[$strTableName."_mastertable"],$masterKeysReq,$selected_recs);
		}
		if($rowcount!==false)
			$numrows=$rowcount;
		else
			$numrows=gSQLRowCount($strWhereClause,$strHavingClause,$strSearchCriteria);
	}
	LogInfo($strSQL);

//	 Pagination:

	$nPageSize = 0;
	if(@$_REQUEST["records"]=="page" && $numrows)
	{
		$mypage = (integer)@$_SESSION[$strTableName."_pagenumber"];
		$nPageSize = (integer)@$_SESSION[$strTableName."_pagesize"];
		
		if(!$nPageSize)
			$nPageSize = GetTableData($strTableName,".pageSize",0);
				
		if($nPageSize<0)
			$nPageSize = 0;
			
		if($nPageSize>0)
		{
			if($numrows<=($mypage-1)*$nPageSize)
				$mypage = ceil($numrows/$nPageSize);
		
			if(!$mypage)
				$mypage = 1;
			
					$strSQL.=" limit ".(($mypage-1)*$nPageSize).",".$nPageSize;
		}
	}
	$listarray = false;
	if($eventObj->exists("ListQuery"))
		$listarray = $eventObj->ListQuery($pageObject->searchClauseObj,$_SESSION[$strTableName."_arrFieldForSort"],$_SESSION[$strTableName."_arrHowFieldSort"],$_SESSION[$strTableName."_mastertable"],$masterKeysReq,$selected_recs,$nPageSize,$mypage);
	if($listarray!==false)
		$rs = $listarray;
	elseif($nPageSize>0)
	{
					$rs = db_query($strSQL,$conn);
	}
	else
		$rs = db_query($strSQL,$conn);

	if(!ini_get("safe_mode"))
		set_time_limit(300);
	
	if(substr(@$_REQUEST["type"],0,5)=="excel")
	{
//	remove grouping
		$locale_info["LOCALE_SGROUPING"]="0";
		$locale_info["LOCALE_SMONGROUPING"]="0";
				if($phpVersion > 4)
			ExportToExcel();
		else
			ExportToExcel_old();
	}
	else if(@$_REQUEST["type"]=="word")
	{
		ExportToWord();
	}
	else if(@$_REQUEST["type"]=="xml")
	{
		ExportToXML();
	}
	else if(@$_REQUEST["type"]=="csv")
	{
		$locale_info["LOCALE_SGROUPING"]="0";
		$locale_info["LOCALE_SDECIMAL"]=".";
		$locale_info["LOCALE_SMONGROUPING"]="0";
		$locale_info["LOCALE_SMONDECIMALSEP"]=".";
		ExportToCSV();
	}
	db_close($conn);
	return;
}

// add button events if exist
$pageObject->addButtonHandlers();

if($options)
{
	$xt->assign("rangeheader_block",true);
	$xt->assign("range_block",true);
}

$xt->assign("exportlink_attrs", 'id="saveButton'.$pageObject->id.'"');

$pageObject->body["begin"] .="<script type=\"text/javascript\" src=\"include/loadfirst.js\"></script>\r\n";
$pageObject->body["begin"] .= "<script type=\"text/javascript\" src=\"include/lang/".getLangFileName(mlang_getcurrentlang()).".js\"></script>";

$pageObject->fillSetCntrlMaps();
$pageObject->body['end'] .= '<script>';
$pageObject->body['end'] .= "window.controlsMap = ".my_json_encode($pageObject->controlsHTMLMap).";";
$pageObject->body['end'] .= "window.settings = ".my_json_encode($pageObject->jsSettings).";";
$pageObject->body['end'] .= '</script>';
$pageObject->body["end"] .= "<script language=\"JavaScript\" src=\"include/runnerJS/RunnerAll.js\"></script>\r\n";
$pageObject->addCommonJs();

$pageObject->body["end"] .= "<script>".$pageObject->PrepareJS()."</script>";
$xt->assignbyref("body",$pageObject->body);

$xt->display("search5_export.htm");

function ExportToExcel_old()
{
	global $cCharset;
	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment;Filename=search5.xls");

	echo "<html>";
	echo "<html xmlns:o=\"urn:schemas-microsoft-com:office:office\" xmlns:x=\"urn:schemas-microsoft-com:office:excel\" xmlns=\"http://www.w3.org/TR/REC-html40\">";
	
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=".$cCharset."\">";
	echo "<body>";
	echo "<table border=1>";

	WriteTableData();

	echo "</table>";
	echo "</body>";
	echo "</html>";
}

function ExportToWord()
{
	global $cCharset;
	header("Content-Type: application/vnd.ms-word");
	header("Content-Disposition: attachment;Filename=search5.doc");

	echo "<html>";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=".$cCharset."\">";
	echo "<body>";
	echo "<table border=1>";

	WriteTableData();

	echo "</table>";
	echo "</body>";
	echo "</html>";
}

function ExportToXML()
{
	global $nPageSize,$rs,$strTableName,$conn,$eventObj;
	header("Content-Type: text/xml");
	header("Content-Disposition: attachment;Filename=search5.xml");
	if($eventObj->exists("ListFetchArray"))
		$row = $eventObj->ListFetchArray($rs);
	else
		$row = db_fetch_array($rs);	
	//if(!$row)
	//	return;
		
	global $cCharset;
	
	echo "<?xml version=\"1.0\" encoding=\"".$cCharset."\" standalone=\"yes\"?>\r\n";
	echo "<table>\r\n";
	$i=0;
	
	
	while((!$nPageSize || $i<$nPageSize) && $row)
	{
		
		$values = array();
			$values["idttl_actions"] = GetData($row,"idttl_actions","");
			$values["FileNumber"] = GetData($row,"FileNumber","");
			$values["NameOfSeller"] = GetData($row,"NameOfSeller","");
			$values["NameOfBuyer"] = GetData($row,"NameOfBuyer","");
			$values["NameOfBroker"] = GetData($row,"NameOfBroker","");
			$values["BrokerCo"] = GetData($row,"BrokerCo","");
			$values["PropAddress"] = GetData($row,"PropAddress","");
			$values["City"] = GetData($row,"City","");
			$values["MortgageBy"] = GetData($row,"MortgageBy","");
			$values["Client A"] = GetData($row,"Client A","");
			$values["Client B"] = GetData($row,"Client B","");
			$values["Tenant"] = GetData($row,"Tenant","");
			$values["Matter"] = GetData($row,"Matter","");
		
		
		$eventRes = true;
		if ($eventObj->exists('BeforeOut'))
		{			
			$eventRes = $eventObj->BeforeOut($row, $values);
		}
		if ($eventRes)
		{
			$i++;
			echo "<row>\r\n";
			foreach ($values as $fName => $val)
			{
				$field = htmlspecialchars(XMLNameEncode($fName));
				echo "<".$field.">";
				echo htmlspecialchars($values[$fName]);
				echo "</".$field.">\r\n";
			}
			echo "</row>\r\n";
		}
		
		
		if($eventObj->exists("ListFetchArray"))
			$row = $eventObj->ListFetchArray($rs);
		else
			$row = db_fetch_array($rs);	
	}
	echo "</table>\r\n";
}

function ExportToCSV()
{
	global $rs,$nPageSize,$strTableName,$conn,$eventObj;
	header("Content-Type: application/csv");
	header("Content-Disposition: attachment;Filename=search5.csv");
	
	if($eventObj->exists("ListFetchArray"))
		$row = $eventObj->ListFetchArray($rs);
	else
		$row = db_fetch_array($rs);	

// write header
	$outstr = "";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"idttl_actions\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"FileNumber\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"NameOfSeller\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"NameOfBuyer\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"NameOfBroker\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"BrokerCo\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"PropAddress\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"City\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"MortgageBy\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"Client A\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"Client B\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"Tenant\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"Matter\"";
	echo $outstr;
	echo "\r\n";

// write data rows
	$iNumberOfRows = 0;
	while((!$nPageSize || $iNumberOfRows < $nPageSize) && $row)
	{
		$values = array();
			$format = "";
			$values["idttl_actions"] = GetData($row,"idttl_actions",$format);
			$format = "";
			$values["FileNumber"] = GetData($row,"FileNumber",$format);
			$format = "";
			$values["NameOfSeller"] = GetData($row,"NameOfSeller",$format);
			$format = "";
			$values["NameOfBuyer"] = GetData($row,"NameOfBuyer",$format);
			$format = "";
			$values["NameOfBroker"] = GetData($row,"NameOfBroker",$format);
			$format = "";
			$values["BrokerCo"] = GetData($row,"BrokerCo",$format);
			$format = "";
			$values["PropAddress"] = GetData($row,"PropAddress",$format);
			$format = "";
			$values["City"] = GetData($row,"City",$format);
			$format = "";
			$values["MortgageBy"] = GetData($row,"MortgageBy",$format);
			$format = "";
			$values["Client A"] = GetData($row,"Client A",$format);
			$format = "";
			$values["Client B"] = GetData($row,"Client B",$format);
			$format = "";
			$values["Tenant"] = GetData($row,"Tenant",$format);
			$format = "";
			$values["Matter"] = GetData($row,"Matter",$format);

		$eventRes = true;
		if ($eventObj->exists('BeforeOut'))
		{
			$eventRes = $eventObj->BeforeOut($row,$values);
		}
		if ($eventRes)
		{
			$outstr="";
			if($outstr!="")
				$outstr.=",";
			$outstr.='"'.str_replace('"', '""', $values["idttl_actions"]).'"';
			if($outstr!="")
				$outstr.=",";
			$outstr.='"'.str_replace('"', '""', $values["FileNumber"]).'"';
			if($outstr!="")
				$outstr.=",";
			$outstr.='"'.str_replace('"', '""', $values["NameOfSeller"]).'"';
			if($outstr!="")
				$outstr.=",";
			$outstr.='"'.str_replace('"', '""', $values["NameOfBuyer"]).'"';
			if($outstr!="")
				$outstr.=",";
			$outstr.='"'.str_replace('"', '""', $values["NameOfBroker"]).'"';
			if($outstr!="")
				$outstr.=",";
			$outstr.='"'.str_replace('"', '""', $values["BrokerCo"]).'"';
			if($outstr!="")
				$outstr.=",";
			$outstr.='"'.str_replace('"', '""', $values["PropAddress"]).'"';
			if($outstr!="")
				$outstr.=",";
			$outstr.='"'.str_replace('"', '""', $values["City"]).'"';
			if($outstr!="")
				$outstr.=",";
			$outstr.='"'.str_replace('"', '""', $values["MortgageBy"]).'"';
			if($outstr!="")
				$outstr.=",";
			$outstr.='"'.str_replace('"', '""', $values["Client A"]).'"';
			if($outstr!="")
				$outstr.=",";
			$outstr.='"'.str_replace('"', '""', $values["Client B"]).'"';
			if($outstr!="")
				$outstr.=",";
			$outstr.='"'.str_replace('"', '""', $values["Tenant"]).'"';
			if($outstr!="")
				$outstr.=",";
			$outstr.='"'.str_replace('"', '""', $values["Matter"]).'"';
			echo $outstr;
		}
		
		$iNumberOfRows++;
		if($eventObj->exists("ListFetchArray"))
			$row = $eventObj->ListFetchArray($rs);
		else
			$row = db_fetch_array($rs);	
			
		if(((!$nPageSize || $iNumberOfRows<$nPageSize) && $row) && $eventRes)
			echo "\r\n";
	}
}


function WriteTableData()
{
	global $rs,$nPageSize,$strTableName,$conn,$eventObj;
	
	if($eventObj->exists("ListFetchArray"))
		$row = $eventObj->ListFetchArray($rs);
	else
		$row = db_fetch_array($rs);	
//	if(!$row)
//		return;
// write header
	echo "<tr>";
	if($_REQUEST["type"]=="excel")
	{
		echo '<td style="width: 100" x:str>'.PrepareForExcel("Idttl Actions").'</td>';	
		echo '<td style="width: 100" x:str>'.PrepareForExcel("File Number").'</td>';	
		echo '<td style="width: 100" x:str>'.PrepareForExcel("Name Of Seller").'</td>';	
		echo '<td style="width: 100" x:str>'.PrepareForExcel("Name Of Buyer").'</td>';	
		echo '<td style="width: 100" x:str>'.PrepareForExcel("Name Of Broker").'</td>';	
		echo '<td style="width: 100" x:str>'.PrepareForExcel("Broker Co").'</td>';	
		echo '<td style="width: 100" x:str>'.PrepareForExcel("Prop Address").'</td>';	
		echo '<td style="width: 100" x:str>'.PrepareForExcel("City").'</td>';	
		echo '<td style="width: 100" x:str>'.PrepareForExcel("Mortgage By").'</td>';	
		echo '<td style="width: 100" x:str>'.PrepareForExcel("Client A").'</td>';	
		echo '<td style="width: 100" x:str>'.PrepareForExcel("Client B").'</td>';	
		echo '<td style="width: 100" x:str>'.PrepareForExcel("Tenant").'</td>';	
		echo '<td style="width: 100" x:str>'.PrepareForExcel("Matter").'</td>';	
	}
	else
	{
		echo "<td>"."Idttl Actions"."</td>";
		echo "<td>"."File Number"."</td>";
		echo "<td>"."Name Of Seller"."</td>";
		echo "<td>"."Name Of Buyer"."</td>";
		echo "<td>"."Name Of Broker"."</td>";
		echo "<td>"."Broker Co"."</td>";
		echo "<td>"."Prop Address"."</td>";
		echo "<td>"."City"."</td>";
		echo "<td>"."Mortgage By"."</td>";
		echo "<td>"."Client A"."</td>";
		echo "<td>"."Client B"."</td>";
		echo "<td>"."Tenant"."</td>";
		echo "<td>"."Matter"."</td>";
	}
	echo "</tr>";
			$totals = array();
		$totals["idttl_actions"] = array("value" => 0, "numRows" => 0);
		$totalsFields[] = array('fName'=>"idttl_actions", 'totalsType'=>'', 'viewFormat'=>"");
			$totals["FileNumber"] = array("value" => 0, "numRows" => 0);
		$totalsFields[] = array('fName'=>"FileNumber", 'totalsType'=>'', 'viewFormat'=>"");
			$totals["NameOfSeller"] = array("value" => 0, "numRows" => 0);
		$totalsFields[] = array('fName'=>"NameOfSeller", 'totalsType'=>'', 'viewFormat'=>"");
			$totals["NameOfBuyer"] = array("value" => 0, "numRows" => 0);
		$totalsFields[] = array('fName'=>"NameOfBuyer", 'totalsType'=>'', 'viewFormat'=>"");
			$totals["NameOfBroker"] = array("value" => 0, "numRows" => 0);
		$totalsFields[] = array('fName'=>"NameOfBroker", 'totalsType'=>'', 'viewFormat'=>"");
			$totals["BrokerCo"] = array("value" => 0, "numRows" => 0);
		$totalsFields[] = array('fName'=>"BrokerCo", 'totalsType'=>'', 'viewFormat'=>"");
			$totals["PropAddress"] = array("value" => 0, "numRows" => 0);
		$totalsFields[] = array('fName'=>"PropAddress", 'totalsType'=>'', 'viewFormat'=>"");
			$totals["City"] = array("value" => 0, "numRows" => 0);
		$totalsFields[] = array('fName'=>"City", 'totalsType'=>'', 'viewFormat'=>"");
			$totals["MortgageBy"] = array("value" => 0, "numRows" => 0);
		$totalsFields[] = array('fName'=>"MortgageBy", 'totalsType'=>'', 'viewFormat'=>"");
			$totals["Client A"] = array("value" => 0, "numRows" => 0);
		$totalsFields[] = array('fName'=>"Client A", 'totalsType'=>'', 'viewFormat'=>"");
			$totals["Client B"] = array("value" => 0, "numRows" => 0);
		$totalsFields[] = array('fName'=>"Client B", 'totalsType'=>'', 'viewFormat'=>"");
			$totals["Tenant"] = array("value" => 0, "numRows" => 0);
		$totalsFields[] = array('fName'=>"Tenant", 'totalsType'=>'', 'viewFormat'=>"");
			$totals["Matter"] = array("value" => 0, "numRows" => 0);
		$totalsFields[] = array('fName'=>"Matter", 'totalsType'=>'', 'viewFormat'=>"");
	
// write data rows
	$iNumberOfRows = 0;
	while((!$nPageSize || $iNumberOfRows<$nPageSize) && $row)
	{
		countTotals($totals, $totalsFields, $row);
		
		$values = array();
	
					
							$format = "";
			
			$values["idttl_actions"] = GetData($row,"idttl_actions",$format);
					
							$format = "";
			
			$values["FileNumber"] = GetData($row,"FileNumber",$format);
					
							$format = "";
			
			$values["NameOfSeller"] = GetData($row,"NameOfSeller",$format);
					
							$format = "";
			
			$values["NameOfBuyer"] = GetData($row,"NameOfBuyer",$format);
					
							$format = "";
			
			$values["NameOfBroker"] = GetData($row,"NameOfBroker",$format);
					
							$format = "";
			
			$values["BrokerCo"] = GetData($row,"BrokerCo",$format);
					
							$format = "";
			
			$values["PropAddress"] = GetData($row,"PropAddress",$format);
					
							$format = "";
			
			$values["City"] = GetData($row,"City",$format);
					
							$format = "";
			
			$values["MortgageBy"] = GetData($row,"MortgageBy",$format);
					
							$format = "";
			
			$values["Client A"] = GetData($row,"Client A",$format);
					
							$format = "";
			
			$values["Client B"] = GetData($row,"Client B",$format);
					
							$format = "";
			
			$values["Tenant"] = GetData($row,"Tenant",$format);
					
							$format = "";
			
			$values["Matter"] = GetData($row,"Matter",$format);
		
		$eventRes = true;
		if ($eventObj->exists('BeforeOut'))
		{
			$eventRes = $eventObj->BeforeOut($row, $values);
		}
		if ($eventRes)
		{
			$iNumberOfRows++;
			echo "<tr>";
		
							echo '<td>';
			
			
									$format="";
									echo htmlspecialchars($values["idttl_actions"]);
			echo '</td>';
							if($_REQUEST["type"]=="excel")
					echo '<td x:str>';
				else
					echo '<td>';
			
			
									$format="";
									if($_REQUEST["type"]=="excel")
						echo PrepareForExcel($values["FileNumber"]);
					else
						echo htmlspecialchars($values["FileNumber"]);
			echo '</td>';
							if($_REQUEST["type"]=="excel")
					echo '<td x:str>';
				else
					echo '<td>';
			
			
									$format="";
									if($_REQUEST["type"]=="excel")
						echo PrepareForExcel($values["NameOfSeller"]);
					else
						echo htmlspecialchars($values["NameOfSeller"]);
			echo '</td>';
							if($_REQUEST["type"]=="excel")
					echo '<td x:str>';
				else
					echo '<td>';
			
			
									$format="";
									if($_REQUEST["type"]=="excel")
						echo PrepareForExcel($values["NameOfBuyer"]);
					else
						echo htmlspecialchars($values["NameOfBuyer"]);
			echo '</td>';
							if($_REQUEST["type"]=="excel")
					echo '<td x:str>';
				else
					echo '<td>';
			
			
									$format="";
									if($_REQUEST["type"]=="excel")
						echo PrepareForExcel($values["NameOfBroker"]);
					else
						echo htmlspecialchars($values["NameOfBroker"]);
			echo '</td>';
							if($_REQUEST["type"]=="excel")
					echo '<td x:str>';
				else
					echo '<td>';
			
			
									$format="";
									if($_REQUEST["type"]=="excel")
						echo PrepareForExcel($values["BrokerCo"]);
					else
						echo htmlspecialchars($values["BrokerCo"]);
			echo '</td>';
							if($_REQUEST["type"]=="excel")
					echo '<td x:str>';
				else
					echo '<td>';
			
			
									$format="";
									if($_REQUEST["type"]=="excel")
						echo PrepareForExcel($values["PropAddress"]);
					else
						echo htmlspecialchars($values["PropAddress"]);
			echo '</td>';
							if($_REQUEST["type"]=="excel")
					echo '<td x:str>';
				else
					echo '<td>';
			
			
									$format="";
									if($_REQUEST["type"]=="excel")
						echo PrepareForExcel($values["City"]);
					else
						echo htmlspecialchars($values["City"]);
			echo '</td>';
							if($_REQUEST["type"]=="excel")
					echo '<td x:str>';
				else
					echo '<td>';
			
			
									$format="";
									if($_REQUEST["type"]=="excel")
						echo PrepareForExcel($values["MortgageBy"]);
					else
						echo htmlspecialchars($values["MortgageBy"]);
			echo '</td>';
							if($_REQUEST["type"]=="excel")
					echo '<td x:str>';
				else
					echo '<td>';
			
			
									$format="";
									if($_REQUEST["type"]=="excel")
						echo PrepareForExcel($values["Client A"]);
					else
						echo htmlspecialchars($values["Client A"]);
			echo '</td>';
							if($_REQUEST["type"]=="excel")
					echo '<td x:str>';
				else
					echo '<td>';
			
			
									$format="";
									if($_REQUEST["type"]=="excel")
						echo PrepareForExcel($values["Client B"]);
					else
						echo htmlspecialchars($values["Client B"]);
			echo '</td>';
							if($_REQUEST["type"]=="excel")
					echo '<td x:str>';
				else
					echo '<td>';
			
			
									$format="";
									if($_REQUEST["type"]=="excel")
						echo PrepareForExcel($values["Tenant"]);
					else
						echo htmlspecialchars($values["Tenant"]);
			echo '</td>';
							if($_REQUEST["type"]=="excel")
					echo '<td x:str>';
				else
					echo '<td>';
			
			
									$format="";
									if($_REQUEST["type"]=="excel")
						echo PrepareForExcel($values["Matter"]);
					else
						echo htmlspecialchars($values["Matter"]);
			echo '</td>';
			echo "</tr>";
		}
		
		
		if($eventObj->exists("ListFetchArray"))
			$row = $eventObj->ListFetchArray($rs);
		else
			$row = db_fetch_array($rs);	
	}
	
}

function XMLNameEncode($strValue)
{
	$search = array(" ","#","'","/","\\","(",")",",","[");
	$ret = str_replace($search,"",$strValue);
	$search = array("]","+","\"","-","_","|","}","{","=");
	$ret = str_replace($search,"",$ret);
	return $ret;
}

function PrepareForExcel($str)
{
	$ret = htmlspecialchars($str);
	if (substr($ret,0,1)== "=") 
		$ret = "&#61;".substr($ret,1);
	return $ret;

}

function countTotals(&$totals, $totalsFields, $data)
{
	for($i = 0; $i < count($totalsFields); $i ++) 
	{
		if($totalsFields[$i]['totalsType'] == 'COUNT') 
			$totals[$totalsFields[$i]['fName']]["value"] += ($data[$totalsFields[$i]['fName']]!= "");
		else if($totalsFields[$i]['viewFormat'] == "Time") 
		{
			$time = GetTotalsForTime($data[$totalsFields[$i]['fName']]);
			$totals[$totalsFields[$i]['fName']]["value"] += $time[2]+$time[1]*60 + $time[0]*3600;
		} 
		else 
			$totals[$totalsFields[$i]['fName']]["value"] += ($data[$totalsFields[$i]['fName']]+ 0);
		
		if($totalsFields[$i]['totalsType'] == 'AVERAGE')
		{
			if(!is_null($data[$totalsFields[$i]['fName']]) && $data[$totalsFields[$i]['fName']]!=="")
				$totals[$totalsFields[$i]['fName']]['numRows']++;
		}
	}
}
?>
