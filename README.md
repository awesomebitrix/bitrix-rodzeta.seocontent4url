
# Модуль SEO-контент для произвольного URL

## Описание

Данный модуль позволяет привязывать контент заданный в инфоблоке (стандартные поля - описание и изображение, метатеги, атрибуты) к произвольному URL сайта.
Например модуль пригодится в случае использования ЧПУ фильтра (когда нет возможности задать данные в разделе).

## Как работает

- задайте в настройках ID инфоблока и раздела для источника SEO-контента;
- URL для привязки указывается в названии элемента инфоблока;
- на странице автоматически заменяются стандартные теги заданные во вкладке SEO (META TITLE, META KEYWORDS, META DESCRIPTION, Заголовок элемента).

### Пример для переопределения значений или использования в result_modifier.php компонента "Элемент каталога"

    global $_APP;
    if (!empty($_APP["rodzeta"]["seo_content"]["DETAIL_PICTURE"])) {
        $arResult["DETAIL_PICTURE"] = CFile::GetFileArray($_APP["rodzeta"]["seo_content"]["DETAIL_PICTURE"]);
    }
    if (!empty($_APP["rodzeta"]["seo_content"]["DETAIL_TEXT"])) {
        $arResult["DETAIL_TEXT"] = $_APP["rodzeta"]["seo_content"]["DETAIL_TEXT"];
    }

## Демо сайт

http://villa-mia.ru/

## Тех. поддержка и кастомизация

Оказывается на платной основе, e-mail: rivetweb@yandex.ru

Багрепорты и предложения на https://github.com/rivetweb/bitrix-rodzeta.seocontent4url/issues

Пул реквесты на https://github.com/rivetweb/bitrix-rodzeta.seocontent4url/pulls
