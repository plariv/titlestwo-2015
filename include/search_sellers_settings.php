<?php
$tdatasearch_sellers=array();
	$tdatasearch_sellers[".NumberOfChars"]=80; 
	$tdatasearch_sellers[".ShortName"]="search_sellers";
	$tdatasearch_sellers[".OwnerID"]="";
	$tdatasearch_sellers[".OriginalTable"]="search_sellers";


	
//	field labels
$fieldLabelssearch_sellers = array();
if(mlang_getcurrentlang()=="English")
{
	$fieldLabelssearch_sellers["English"]=array();
	$fieldToolTipssearch_sellers["English"]=array();
	$fieldLabelssearch_sellers["English"]["id"] = "Id";
	$fieldToolTipssearch_sellers["English"]["id"] = "";
	$fieldLabelssearch_sellers["English"]["seller_name"] = "Seller Name";
	$fieldToolTipssearch_sellers["English"]["seller_name"] = "";
	$fieldLabelssearch_sellers["English"]["ttl_actions"] = "Ttl Actions";
	$fieldToolTipssearch_sellers["English"]["ttl_actions"] = "";
	if (count($fieldToolTipssearch_sellers["English"])){
		$tdatasearch_sellers[".isUseToolTips"]=true;
	}
}


	
	$tdatasearch_sellers[".NCSearch"]=true;

	

$tdatasearch_sellers[".shortTableName"] = "search_sellers";
$tdatasearch_sellers[".nSecOptions"] = 0;
$tdatasearch_sellers[".recsPerRowList"] = 1;	
$tdatasearch_sellers[".tableGroupBy"] = "0";
$tdatasearch_sellers[".mainTableOwnerID"] = "";
$tdatasearch_sellers[".moveNext"] = 1;




$tdatasearch_sellers[".showAddInPopup"] = false;

$tdatasearch_sellers[".showEditInPopup"] = false;

$tdatasearch_sellers[".showViewInPopup"] = false;


$tdatasearch_sellers[".fieldsForRegister"] = array();

$tdatasearch_sellers[".listAjax"] = false;

	$tdatasearch_sellers[".audit"] = false;

	$tdatasearch_sellers[".locking"] = false;
	
$tdatasearch_sellers[".listIcons"] = true;
$tdatasearch_sellers[".edit"] = true;
$tdatasearch_sellers[".inlineEdit"] = true;
$tdatasearch_sellers[".view"] = true;

$tdatasearch_sellers[".exportTo"] = true;

$tdatasearch_sellers[".printFriendly"] = true;

$tdatasearch_sellers[".delete"] = true;

$tdatasearch_sellers[".showSimpleSearchOptions"] = false;

$tdatasearch_sellers[".showSearchPanel"] = true;


if (isMobile()){
$tdatasearch_sellers[".isUseAjaxSuggest"] = false;
}else {
$tdatasearch_sellers[".isUseAjaxSuggest"] = true;
}

$tdatasearch_sellers[".rowHighlite"] = true;


// button handlers file names

$tdatasearch_sellers[".addPageEvents"] = false;

$tdatasearch_sellers[".arrKeyFields"][] = "id";

// use datepicker for search panel
$tdatasearch_sellers[".isUseCalendarForSearch"] = false;

// use timepicker for search panel
$tdatasearch_sellers[".isUseTimeForSearch"] = false;

$tdatasearch_sellers[".isUseiBox"] = false;



	


$tdatasearch_sellers[".isUseInlineAdd"] = true;

$tdatasearch_sellers[".isUseInlineEdit"] = true;
$tdatasearch_sellers[".isUseInlineJs"] = $tdatasearch_sellers[".isUseInlineAdd"] || $tdatasearch_sellers[".isUseInlineEdit"];

$tdatasearch_sellers[".allSearchFields"] = array();

$tdatasearch_sellers[".globSearchFields"][] = "id";
// do in this way, because combine functions array_unique and array_merge returns array with keys like 1,2, 4 etc
if (!in_array("id", $tdatasearch_sellers[".allSearchFields"]))
{
	$tdatasearch_sellers[".allSearchFields"][] = "id";	
}
$tdatasearch_sellers[".globSearchFields"][] = "seller_name";
// do in this way, because combine functions array_unique and array_merge returns array with keys like 1,2, 4 etc
if (!in_array("seller_name", $tdatasearch_sellers[".allSearchFields"]))
{
	$tdatasearch_sellers[".allSearchFields"][] = "seller_name";	
}
$tdatasearch_sellers[".globSearchFields"][] = "ttl_actions";
// do in this way, because combine functions array_unique and array_merge returns array with keys like 1,2, 4 etc
if (!in_array("ttl_actions", $tdatasearch_sellers[".allSearchFields"]))
{
	$tdatasearch_sellers[".allSearchFields"][] = "ttl_actions";	
}


$tdatasearch_sellers[".googleLikeFields"][] = "id";
$tdatasearch_sellers[".googleLikeFields"][] = "seller_name";
$tdatasearch_sellers[".googleLikeFields"][] = "ttl_actions";



$tdatasearch_sellers[".advSearchFields"][] = "id";
// do in this way, because combine functions array_unique and array_merge returns array with keys like 1,2, 4 etc
if (!in_array("id", $tdatasearch_sellers[".allSearchFields"])) 
{
	$tdatasearch_sellers[".allSearchFields"][] = "id";	
}
$tdatasearch_sellers[".advSearchFields"][] = "seller_name";
// do in this way, because combine functions array_unique and array_merge returns array with keys like 1,2, 4 etc
if (!in_array("seller_name", $tdatasearch_sellers[".allSearchFields"])) 
{
	$tdatasearch_sellers[".allSearchFields"][] = "seller_name";	
}
$tdatasearch_sellers[".advSearchFields"][] = "ttl_actions";
// do in this way, because combine functions array_unique and array_merge returns array with keys like 1,2, 4 etc
if (!in_array("ttl_actions", $tdatasearch_sellers[".allSearchFields"])) 
{
	$tdatasearch_sellers[".allSearchFields"][] = "ttl_actions";	
}

$tdatasearch_sellers[".isTableType"] = "list";


	



// Access doesn't support subqueries from the same table as main
$tdatasearch_sellers[".subQueriesSupAccess"] = true;





$tdatasearch_sellers[".pageSize"] = 20;

$gstrOrderBy = "";
if(strlen($gstrOrderBy) && strtolower(substr($gstrOrderBy,0,8))!="order by")
	$gstrOrderBy = "order by ".$gstrOrderBy;
$tdatasearch_sellers[".strOrderBy"] = $gstrOrderBy;
	
$tdatasearch_sellers[".orderindexes"] = array();

$tdatasearch_sellers[".sqlHead"] = "SELECT id,   seller_name,   ttl_actions";
$tdatasearch_sellers[".sqlFrom"] = "FROM search_sellers";
$tdatasearch_sellers[".sqlWhereExpr"] = "";
$tdatasearch_sellers[".sqlTail"] = "";




//fill array of records per page for list and report without group fields
$arrRPP = array();
$arrRPP[] = 10;
$arrRPP[] = 20;
$arrRPP[] = 30;
$arrRPP[] = 50;
$arrRPP[] = 100;
$arrRPP[] = 500;
$arrRPP[] = -1;
$tdatasearch_sellers[".arrRecsPerPage"] = $arrRPP;

//fill array of groups per page for report with group fields
$arrGPP = array();
$arrGPP[] = 1;
$arrGPP[] = 3;
$arrGPP[] = 5;
$arrGPP[] = 10;
$arrGPP[] = 50;
$arrGPP[] = 100;
$arrGPP[] = -1;
$tdatasearch_sellers[".arrGroupsPerPage"] = $arrGPP;

	$tableKeys = array();
	$tableKeys[] = "id";
	$tdatasearch_sellers[".Keys"] = $tableKeys;

$tdatasearch_sellers[".listFields"] = array();
$tdatasearch_sellers[".listFields"][] = "id";
$tdatasearch_sellers[".listFields"][] = "seller_name";
$tdatasearch_sellers[".listFields"][] = "ttl_actions";

$tdatasearch_sellers[".addFields"] = array();
$tdatasearch_sellers[".addFields"][] = "seller_name";
$tdatasearch_sellers[".addFields"][] = "ttl_actions";

$tdatasearch_sellers[".inlineAddFields"] = array();
$tdatasearch_sellers[".inlineAddFields"][] = "seller_name";
$tdatasearch_sellers[".inlineAddFields"][] = "ttl_actions";

$tdatasearch_sellers[".editFields"] = array();
$tdatasearch_sellers[".editFields"][] = "seller_name";
$tdatasearch_sellers[".editFields"][] = "ttl_actions";

$tdatasearch_sellers[".inlineEditFields"] = array();
$tdatasearch_sellers[".inlineEditFields"][] = "seller_name";
$tdatasearch_sellers[".inlineEditFields"][] = "ttl_actions";

	
//	id
	$fdata = array();
	$fdata["strName"] = "id";
	$fdata["ownerTable"] = "search_sellers";
	$fdata["Label"]="Id"; 
	
		
		
	$fdata["FieldType"]= 3;
	
		$fdata["AutoInc"]=true;
	
			$fdata["UseiBox"] = false;
	
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
		
		
		
		
		$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "id";
	
		$fdata["FullName"]= "id";
	
		$fdata["IsRequired"]=true; 
	
		
		
		
		
				$fdata["Index"]= 1;
				$fdata["EditParams"]="";
			
		$fdata["bListPage"]=true; 
	
		
		
		
		
		$fdata["bViewPage"]=true; 
	
		$fdata["bAdvancedSearch"]=true; 
	
		$fdata["bPrinterPage"]=true; 
	
		$fdata["bExportPage"]=true; 
	
	//Begin validation
	$fdata["validateAs"] = array();
				$fdata["validateAs"]["basicValidate"][] = getJsValidatorName("Number");	
						$fdata["validateAs"]["basicValidate"][] = "IsRequired";
	
		//End validation
	
				$fdata["FieldPermissions"]=true;
	
		
				
		
		
		
			$tdatasearch_sellers["id"]=$fdata;
//	seller_name
	$fdata = array();
	$fdata["strName"] = "seller_name";
	$fdata["ownerTable"] = "search_sellers";
	$fdata["Label"]="Seller Name"; 
	
		
		
	$fdata["FieldType"]= 200;
	
		
			$fdata["UseiBox"] = false;
	
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
		
		
		
		
		$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "seller_name";
	
		$fdata["FullName"]= "seller_name";
	
		
		
		
		
		
				$fdata["Index"]= 2;
				$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=50";
		
		$fdata["bListPage"]=true; 
	
		$fdata["bAddPage"]=true; 
	
		$fdata["bInlineAdd"]=true; 
	
		$fdata["bEditPage"]=true; 
	
		$fdata["bInlineEdit"]=true; 
	
		$fdata["bViewPage"]=true; 
	
		$fdata["bAdvancedSearch"]=true; 
	
		$fdata["bPrinterPage"]=true; 
	
		$fdata["bExportPage"]=true; 
	
	//Begin validation
	$fdata["validateAs"] = array();
		
		//End validation
	
				$fdata["FieldPermissions"]=true;
	
		
				
		
		
		
			$tdatasearch_sellers["seller_name"]=$fdata;
//	ttl_actions
	$fdata = array();
	$fdata["strName"] = "ttl_actions";
	$fdata["ownerTable"] = "search_sellers";
	$fdata["Label"]="Ttl Actions"; 
	
		
		
	$fdata["FieldType"]= 3;
	
		
			$fdata["UseiBox"] = false;
	
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
		
		
		
		
		$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "ttl_actions";
	
		$fdata["FullName"]= "ttl_actions";
	
		
		
		
		
		
				$fdata["Index"]= 3;
				$fdata["EditParams"]="";
			
		$fdata["bListPage"]=true; 
	
		$fdata["bAddPage"]=true; 
	
		$fdata["bInlineAdd"]=true; 
	
		$fdata["bEditPage"]=true; 
	
		$fdata["bInlineEdit"]=true; 
	
		$fdata["bViewPage"]=true; 
	
		$fdata["bAdvancedSearch"]=true; 
	
		$fdata["bPrinterPage"]=true; 
	
		$fdata["bExportPage"]=true; 
	
	//Begin validation
	$fdata["validateAs"] = array();
				$fdata["validateAs"]["basicValidate"][] = getJsValidatorName("Number");	
						
		//End validation
	
				$fdata["FieldPermissions"]=true;
	
		
				
		
		
		
			$tdatasearch_sellers["ttl_actions"]=$fdata;


	
$tables_data["search_sellers"]=&$tdatasearch_sellers;
$field_labels["search_sellers"] = &$fieldLabelssearch_sellers;
$fieldToolTips["search_sellers"] = &$fieldToolTipssearch_sellers;

// -----------------start  prepare master-details data arrays ------------------------------//
// tables which are detail tables for current table (master)
$detailsTablesData["search_sellers"] = array();

	
// tables which are master tables for current table (detail)
$masterTablesData["search_sellers"] = array();

// -----------------end  prepare master-details data arrays ------------------------------//

require_once(getabspath("classes/sql.php"));










function createSqlQuery_search_sellers()
{
$proto0=array();
$proto0["m_strHead"] = "SELECT";
$proto0["m_strFieldList"] = "id,   seller_name,   ttl_actions";
$proto0["m_strFrom"] = "FROM search_sellers";
$proto0["m_strWhere"] = "";
$proto0["m_strOrderBy"] = "";
$proto0["m_strTail"] = "";
$proto1=array();
$proto1["m_sql"] = "";
$proto1["m_uniontype"] = "SQLL_UNKNOWN";
	$obj = new SQLNonParsed(array(
	"m_sql" => ""
));

$proto1["m_column"]=$obj;
$proto1["m_contained"] = array();
$proto1["m_strCase"] = "";
$proto1["m_havingmode"] = "0";
$proto1["m_inBrackets"] = "0";
$proto1["m_useAlias"] = "0";
$obj = new SQLLogicalExpr($proto1);

$proto0["m_where"] = $obj;
$proto3=array();
$proto3["m_sql"] = "";
$proto3["m_uniontype"] = "SQLL_UNKNOWN";
	$obj = new SQLNonParsed(array(
	"m_sql" => ""
));

$proto3["m_column"]=$obj;
$proto3["m_contained"] = array();
$proto3["m_strCase"] = "";
$proto3["m_havingmode"] = "0";
$proto3["m_inBrackets"] = "0";
$proto3["m_useAlias"] = "0";
$obj = new SQLLogicalExpr($proto3);

$proto0["m_having"] = $obj;
$proto0["m_fieldlist"] = array();
						$proto5=array();
			$obj = new SQLField(array(
	"m_strName" => "id",
	"m_strTable" => "search_sellers"
));

$proto5["m_expr"]=$obj;
$proto5["m_alias"] = "";
$obj = new SQLFieldListItem($proto5);

$proto0["m_fieldlist"][]=$obj;
						$proto7=array();
			$obj = new SQLField(array(
	"m_strName" => "seller_name",
	"m_strTable" => "search_sellers"
));

$proto7["m_expr"]=$obj;
$proto7["m_alias"] = "";
$obj = new SQLFieldListItem($proto7);

$proto0["m_fieldlist"][]=$obj;
						$proto9=array();
			$obj = new SQLField(array(
	"m_strName" => "ttl_actions",
	"m_strTable" => "search_sellers"
));

$proto9["m_expr"]=$obj;
$proto9["m_alias"] = "";
$obj = new SQLFieldListItem($proto9);

$proto0["m_fieldlist"][]=$obj;
$proto0["m_fromlist"] = array();
												$proto11=array();
$proto11["m_link"] = "SQLL_MAIN";
			$proto12=array();
$proto12["m_strName"] = "search_sellers";
$proto12["m_columns"] = array();
$proto12["m_columns"][] = "id";
$proto12["m_columns"][] = "seller_name";
$proto12["m_columns"][] = "ttl_actions";
$obj = new SQLTable($proto12);

$proto11["m_table"] = $obj;
$proto11["m_alias"] = "";
$proto13=array();
$proto13["m_sql"] = "";
$proto13["m_uniontype"] = "SQLL_UNKNOWN";
	$obj = new SQLNonParsed(array(
	"m_sql" => ""
));

$proto13["m_column"]=$obj;
$proto13["m_contained"] = array();
$proto13["m_strCase"] = "";
$proto13["m_havingmode"] = "0";
$proto13["m_inBrackets"] = "0";
$proto13["m_useAlias"] = "0";
$obj = new SQLLogicalExpr($proto13);

$proto11["m_joinon"] = $obj;
$obj = new SQLFromListItem($proto11);

$proto0["m_fromlist"][]=$obj;
$proto0["m_groupby"] = array();
$proto0["m_orderby"] = array();
$obj = new SQLQuery($proto0);

return $obj;
}
$queryData_search_sellers = createSqlQuery_search_sellers();
$tdatasearch_sellers[".sqlquery"] = $queryData_search_sellers;



$tableEvents["search_sellers"] = new eventsBase;
$tdatasearch_sellers[".hasEvents"] = false;

?>
