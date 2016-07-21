
<?php
/***********************************************************************************************
 * rodzeta.seocontent4url - SEO-content for URLs
 * Copyright 2016 Semenov Roman
 * MIT License
 ************************************************************************************************/

defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();

use Bitrix\Main\Loader;
use Bitrix\Main\EventManager;
use Bitrix\Main\Config\Option;

EventManager::getInstance()->addEventHandler("main", "OnBeforeProlog", function () {
	if (CSite::InDir("/bitrix/")) {
		return;
	}

	global $APPLICATION;

	$currentUrl = !empty($_SERVER["REDIRECT_URL"])?
		$_SERVER["REDIRECT_URL"] : $APPLICATION->GetCurPage();

	// init seo content by current url
	$iblockId = Option::get("rodzeta.seocontent4url", "iblock_id", 1);
	$seoContent = \Bitrix\Iblock\ElementTable::getRow(array(
		"filter" => array(
			"IBLOCK_ID" => $iblockId,
			"IBLOCK_SECTION_ID" => Option::get("rodzeta.seocontent4url", "section_id", 21),
			"NAME" => $currentUrl
		),
	));
	if (!empty($seoContent)) {
		// get attribs
		$seoContent["ATTRIBS"] = array();
		$res = \CIBlockElement::GetProperty(
			$iblockId,
			$seoContent["ID"],
			"sort",
			"asc",
			array("CODE" => "ATTRIBS")
		);
		while ($v = $res->Fetch()) {
			$seoContent["ATTRIBS"][$v["DESCRIPTION"]] = array(
				"FIELD" => null,
				"VALUE" => $v["VALUE"],
			);
		}
		// get seo tags values
		$ipropValues = new \Bitrix\Iblock\InheritedProperty\ElementValues($iblockId, $seoContent["ID"]);
		$GLOBALS["rodzeta"]["seo_content"] = array_merge($ipropValues->getValues(), $seoContent);
	}
});


EventManager::getInstance()->addEventHandler("main", "OnEpilog", function () {
	if (CSite::InDir("/bitrix/")) {
		return;
	}

	global $APPLICATION;

	if (empty($GLOBALS["rodzeta"]["seo_content"])) {
		return;
	}

	if (!empty($GLOBALS["rodzeta"]["seo_content"]["ELEMENT_META_TITLE"])) {
		$APPLICATION->SetPageProperty("title", $GLOBALS["rodzeta"]["seo_content"]["ELEMENT_META_TITLE"]);
	}
	if (!empty($GLOBALS["rodzeta"]["seo_content"]["ELEMENT_META_KEYWORDS"])) {
		$APPLICATION->SetPageProperty("keywords", $GLOBALS["rodzeta"]["seo_content"]["ELEMENT_META_KEYWORDS"]);
	}
	if (!empty($GLOBALS["rodzeta"]["seo_content"]["ELEMENT_META_DESCRIPTION"])) {
		$APPLICATION->SetPageProperty("description", $GLOBALS["rodzeta"]["seo_content"]["ELEMENT_META_DESCRIPTION"]);
	}
	if (!empty($GLOBALS["rodzeta"]["seo_content"]["ELEMENT_PAGE_TITLE"])) {
		$APPLICATION->SetTitle($GLOBALS["rodzeta"]["seo_content"]["ELEMENT_PAGE_TITLE"]);
	}

});
