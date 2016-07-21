
# SEO-контент для произвольного URL

## Описание решения

Данный модуль позволяет привязывать контент заданный в инфоблоке (стандартные поля - описание и изображение, метатеги, атрибуты) к произвольному URL сайта.
Например модуль пригодится в случае использования ЧПУ фильтра (когда нет возможности задать данные в разделе).

## Описание установки и настройки решения

- задайте в настройках ID инфоблока и раздела для источника SEO-контента;
- URL для привязки указывается в названии элемента инфоблока;
- на странице автоматически заменяются стандартные теги заданные во вкладке SEO (META TITLE, META KEYWORDS, META DESCRIPTION, Заголовок элемента).

### Пример для переопределения значений или использования в result_modifier.php компонента "Элемент каталога"

    if (!empty($GLOBALS["rodzeta"]["seo_content"]["DETAIL_PICTURE"])) {
        $arResult["DETAIL_PICTURE"] = CFile::GetFileArray($GLOBALS["rodzeta"]["seo_content"]["DETAIL_PICTURE"]);
    }
    if (!empty($GLOBALS["rodzeta"]["seo_content"]["DETAIL_TEXT"])) {
        $arResult["DETAIL_TEXT"] = $GLOBALS["rodzeta"]["seo_content"]["DETAIL_TEXT"];
    }

##  Описание техподдержки и контактных данных

Тех. поддержка и кастомизация оказывается на платной основе, e-mail: rivetweb@yandex.ru

Багрепорты и предложения на https://github.com/rivetweb/bitrix-rodzeta.seocontent4url/issues

Пул реквесты на https://github.com/rivetweb/bitrix-rodzeta.seocontent4url/pulls

## Ссылка на демо-версию

http://villa-mia.ru/
