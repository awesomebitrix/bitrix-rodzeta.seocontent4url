
# SEO-контент для произвольного URL

## Описание решения

Данный модуль позволяет привязывать контент заданный в инфоблоке (стандартные поля - описание и изображение, метатеги, атрибуты) к произвольному URL сайта.
Например модуль пригодится в случае использования ЧПУ фильтра (когда нет возможности задать данные в разделе). Так же модуль позволяет привязывать контент по UTM-меткам.

## Описание установки и настройки решения

- задайте в настройках ID инфоблока для источника SEO-контента;
- URL для привязки указывается в названии элемента инфоблока;
- UTM параметры и значения для привязки указывается в названии элемента инфоблока;
- на странице автоматически заменяются стандартные мета-теги заданные во вкладке SEO (META TITLE, META KEYWORDS, META DESCRIPTION, Заголовок элемента);
- на странице автоматически заменяются прописанные "пользовательские теги".

### Пример для переопределения значений или использования в result_modifier.php компонента "Элемент каталога"

    if (!empty($GLOBALS["RODZETA"]["SEO"]["#SEO_DETAIL_PICTURE_ARRAY#"])) {
        $arResult["DETAIL_PICTURE"] = $GLOBALS["RODZETA"]["SEO"]["#SEO_DETAIL_PICTURE_ARRAY#"];
    }
    if (!empty($GLOBALS["RODZETA"]["SEO"]["#SEO_DETAIL_TEXT#"])) {
        $arResult["DETAIL_TEXT"] = $GLOBALS["RODZETA"]["SEO"]["#SEO_DETAIL_TEXT#"];
    }

### Список "пользовательских тегов" для вставки в любое место страницы
 
    #SEO_ELEMENT_META_TITLE#
    #SEO_ELEMENT_META_KEYWORDS#
    #SEO_ELEMENT_META_DESCRIPTION#
    #SEO_ELEMENT_PAGE_TITLE#
    #SEO_PREVIEW_TEXT#
    #SEO_DETAIL_TEXT#
    #SEO_PREVIEW_PICTURE_SRC#
    #SEO_PREVIEW_PICTURE_DESCRIPTION#
    #SEO_PREVIEW_PICTURE#
    #SEO_DETAIL_PICTURE_SRC#
    #SEO_DETAIL_PICTURE_DESCRIPTION#
    #SEO_DETAIL_PICTURE#
    #SEO_SOMEATTRCODE1#
    ...

##  Описание техподдержки и контактных данных

Тех. поддержка и кастомизация оказывается на платной основе, e-mail: rivetweb@yandex.ru

Багрепорты и предложения на https://github.com/rivetweb/bitrix-rodzeta.seocontent4url/issues

Пул реквесты на https://github.com/rivetweb/bitrix-rodzeta.seocontent4url/pulls

## Ссылка на демо-версию

http://villa-mia.ru/
