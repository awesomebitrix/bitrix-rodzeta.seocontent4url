<?php
/***********************************************************************************************
 * rodzeta.seocontent4url - SEO-content for URLs
 * Copyright 2016 Semenov Roman
 * MIT License
 ************************************************************************************************/

namespace Rodzeta\Seocontent4url;

use \Bitrix\Main\Config\Option;

final class Utils {

	static function getSeoContent($name, $iblockId, $sectionId, &$options) {
		$seoContent = \Bitrix\Iblock\ElementTable::getRow(array(
			"filter" => array(
				"IBLOCK_ID" => $iblockId,
				"IBLOCK_SECTION_ID" => $sectionId,
				"NAME" => $name,
				"ACTIVE" => "Y"
			),
		));
		if (empty($seoContent)) {
			return;
		}

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
	}

}