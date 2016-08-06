<?php

$doc_root       = $_SERVER['DOCUMENT_ROOT'];
$assets_dir     = $doc_root . "/assets";
$assets_url     = "/assets";
$theme_dir      = $assets_dir . "/default";
$theme_url      = $assets_url . "/default";
$vendor_dir     = $theme_dir . "/_vendor";
$vendor_url     = $theme_url . "/_vendor";
$script_url     = $_SERVER['SCRIPT_NAME'] . "?";
$request_url    = $script_url . $_SERVER['QUERY_STRING'];
$action_url     = "/action.php?";
$generator_url  = "/generator_editable.php";

date_default_timezone_set('America/New_York');