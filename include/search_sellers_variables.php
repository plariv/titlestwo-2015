<?php
$strTableName="search_sellers";
$_SESSION["OwnerID"] = $_SESSION["_".$strTableName."_OwnerID"];

$strOriginalTableName="search_sellers";

$gstrOrderBy="";
if(strlen($gstrOrderBy) && strtolower(substr($gstrOrderBy,0,8))!="order by")
	$gstrOrderBy="order by ".$gstrOrderBy;

$g_orderindexes=array();
$gsqlHead="SELECT id,   seller_name,   ttl_actions";
$gsqlFrom="FROM search_sellers";
$gsqlWhereExpr="";
$gsqlTail="";

include_once(getabspath("include/search_sellers_settings.php"));

// alias for 'SQLQuery' object
$gQuery = &$queryData_search_sellers;
$eventObj = &$tableEvents["search_sellers"];

$reportCaseSensitiveGroupFields = false;

$gstrSQL = gSQLWhere("");


?>