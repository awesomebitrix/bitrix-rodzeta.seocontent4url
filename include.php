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

	$GLOBALS["RODZETA"]["SEO"] = array();

	// set seo-content by request params
	if (Option::get("rodzeta.seocontent4url", "use_request_params") == "Y") {
		$fields = parse_ini_string(Option::get("rodzeta.seocontent4url", "input_params"));
		foreach ($fields as $dest => $src) {
			if (!empty($_REQUEST[$src])) {
				$GLOBALS["RODZETA"]["SEO"]["#SEO_" . $dest . "#"] = $_REQUEST[$src];
			}
		}
	}

	// set seo-content by URL
	$currentUrl = !empty($_SERVER["REDIRECT_URL"])?
		$_SERVER["REDIRECT_URL"] : $APPLICATION->GetCurPage();
	\Rodzeta\Seocontent4url\Utils::getSeoContent(
		$currentUrl,
		Option::get("rodzeta.seocontent4url", "iblock_id", 1),
		Option::get("rodzeta.seocontent4url", "section_id", 21),
		$GLOBALS["RODZETA"]["SEO"]
	);

	// set seo-content by UTM
	if (Option::get("rodzeta.seocontent4url", "utm_iblock_id") && Option::get("rodzeta.seocontent4url", "utm_section_id")) {
		$fields = array_filter(array_map("trim", explode("\n", Option::get("rodzeta.seocontent4url", "utm_input_params"))));
		$currentParams = array();
		foreach ($fields as $code) {
			if (isset($_REQUEST[$code])) {
				$currentParams[] = $code . "=" . $_REQUEST[$code];
				//$currentParams[$code] = filter_var($_REQUEST[$code], FILTER_SANITIZE_STRING);
			}
		}
		\Rodzeta\Seocontent4url\Utils::getSeoContent(
			implode("&", $currentParams),
			Option::get("rodzeta.seocontent4url", "utm_iblock_id"),
			Option::get("rodzeta.seocontent4url", "utm_section_id"),
			$GLOBALS["RODZETA"]["SEO"],
			Option::get("rodzeta.seocontent4url", "utm_element_id")
		);
	}

});

EventManager::getInstance()->addEventHandler("main", "OnEpilog", function () {
	if (CSite::InDir("/bitrix/") || empty($GLOBALS["RODZETA"]["SEO"])) {
		return;
	}

	global $APPLICATION;
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

EventManager::getInstance()->addEventHandler("main", "OnEndBufferContent", function (&$content) {
	if (CSite::InDir("/bitrix/") || empty($GLOBALS["RODZETA"]["SEO"])) {
		return;
	}

	global $APPLICATION;
	if ($APPLICATION->GetPublicShowMode() != "view") {
		return;
	}

	$content = str_replace(array_keys($GLOBALS["RODZETA"]["SEO"]), array_values($GLOBALS["RODZETA"]["SEO"]), $content);
});