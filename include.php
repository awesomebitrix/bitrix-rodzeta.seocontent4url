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
			"NAME" => $currentUrl,
			"ACTIVE" => "Y"
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
		$seoContent = array_merge($ipropValues->getValues(), $seoContent);

		$options = array();
		foreach (array(
					//"NAME",
					"PREVIEW_TEXT",
					"DETAIL_TEXT",
					"ELEMENT_META_TITLE",
					"ELEMENT_META_KEYWORDS",
					"ELEMENT_META_DESCRIPTION",
					"ELEMENT_PAGE_TITLE"
				) as $code) {
			$options["#SEO_" . $code . "#"] = $seoContent[$code];
		}
		foreach (array("PREVIEW_PICTURE", "DETAIL_PICTURE") as $code) {
			$img = \CFile::GetFileArray($seoContent[$code]);
			$options["#SEO_" . $code . "_ARRAY" . "#"] = $img;
			$options["#SEO_" . $code . "_SRC" . "#"] = $img["SRC"];
			$options["#SEO_" . $code . "_DESCRIPTION" . "#"] = $img["DESCRIPTION"];
			$options["#SEO_" . $code . "#"] =
				'<img src="' . $img["SRC"] . '" alt="' . htmlspecialchars($img["DESCRIPTION"]) . '">';
		}
		foreach ($seoContent["ATTRIBS"] as $code => $v) {
			$options["#SEO_" . $code . "#"] = $seoContent["ATTRIBS"][$code]["VALUE"];
		}
		$GLOBALS["RODZETA"]["SEO"] = $options;
	}
});

EventManager::getInstance()->addEventHandler("main", "OnEpilog", function () {
	if (CSite::InDir("/bitrix/")) {
		return;
	}

	global $APPLICATION;

	if (empty($GLOBALS["RODZETA"]["SEO"])) {
		return;
	}

	if (!empty($GLOBALS["RODZETA"]["SEO"]["#SEO_ELEMENT_META_TITLE#"])) {
		$APPLICATION->SetPageProperty("title", $GLOBALS["RODZETA"]["SEO"]["#SEO_ELEMENT_META_TITLE#"]);
	}
	if (!empty($GLOBALS["RODZETA"]["SEO"]["#SEO_ELEMENT_META_KEYWORDS#"])) {
		$APPLICATION->SetPageProperty("keywords", $GLOBALS["RODZETA"]["SEO"]["#SEO_ELEMENT_META_KEYWORDS#"]);
	}
	if (!empty($GLOBALS["RODZETA"]["SEO"]["#SEO_ELEMENT_META_DESCRIPTION#"])) {
		$APPLICATION->SetPageProperty("description", $GLOBALS["RODZETA"]["SEO"]["#SEO_ELEMENT_META_DESCRIPTION#"]);
	}
	if (!empty($GLOBALS["RODZETA"]["SEO"]["#SEO_ELEMENT_PAGE_TITLE#"])) {
		$APPLICATION->SetTitle($GLOBALS["RODZETA"]["SEO"]["#SEO_ELEMENT_PAGE_TITLE#"]);
	}

});
