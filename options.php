<?php
/***********************************************************************************************
 * rodzeta.seocontent4url - SEO-content for URLs
 * Copyright 2016 Semenov Roman
 * MIT License
 ************************************************************************************************/

defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();

use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Text\String;
use Bitrix\Main\Loader;

if (!$USER->isAdmin()) {
	$APPLICATION->authForm("ACCESS DENIED");
}

$app = Application::getInstance();
$context = $app->getContext();
$request = $context->getRequest();

Loc::loadMessages(__FILE__);

$tabControl = new CAdminTabControl("tabControl", array(
  array(
		"DIV" => "edit1",
		"TAB" => Loc::getMessage("RODZETA_SEOCONTENT4URL_MAIN_TAB_SET"),
		"TITLE" => Loc::getMessage("RODZETA_SEOCONTENT4URL_MAIN_TAB_TITLE_SET"),
  ),
));

?>

<?php

if ($request->isPost() && check_bitrix_sessid()) {
	if (!empty($save) || !empty($restore)) {
		Option::set("rodzeta.seocontent4url", "iblock_id", (int)$request->getPost("iblock_id"));
		Option::set("rodzeta.seocontent4url", "section_id", (int)$request->getPost("section_id"));
		//Option::set("rodzeta.seocontent4url", "input_params", $request->getPost("input_params"));

		CAdminMessage::showMessage(array(
	    "MESSAGE" => Loc::getMessage("RODZETA_SEOCONTENT4URL_OPTIONS_SAVED"),
	    "TYPE" => "OK",
	  ));
	}
}

$tabControl->begin();

?>

<form method="post" action="<?= sprintf('%s?mid=%s&lang=%s', $request->getRequestedPage(), urlencode($mid), LANGUAGE_ID) ?> type="get">
	<?= bitrix_sessid_post() ?>

	<?php $tabControl->beginNextTab() ?>

	<tr>
		<td class="adm-detail-content-cell-l" width="50%">
			<label>ID инфоблока SEO-контента</label>
		</td>
		<td class="adm-detail-content-cell-r" width="50%">
			<input class="input" type="text" size="4" name="iblock_id" value="<?= Option::get("rodzeta.seocontent4url", "iblock_id", 1) ?>">
		</td>
	</tr>

	<tr>
		<td class="adm-detail-content-cell-l" width="50%">
			<label>ID раздела SEO-контента</label>
		</td>
		<td class="adm-detail-content-cell-r" width="50%">
			<input name="section_id" type="text" size="4" value="<?= Option::get("rodzeta.seocontent4url", "section_id", 21) ?>">
		</td>
	</tr>

	<?php /*
	<tr>
		<td class="adm-detail-content-cell-l" width="50%">
			<label>Список входных параметров запроса</label>
		</td>
		<td class="adm-detail-content-cell-r" width="50%">
			<textarea name="input_params" rows="6"
				placeholder="например
utm_term
..."><?= Option::get("rodzeta.seocontent4url", "input_params") ?></textarea>
		</td>
	</tr>
	*/ ?>

	<?php
	 $tabControl->buttons();
  ?>

  <input class="adm-btn-save" type="submit" name="save" value="Применить настройки">

</form>

<?php

$tabControl->end();
