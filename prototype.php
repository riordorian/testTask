<?
namespace SIte\Iblock;
if (!\Bitrix\Main\Loader::includeModule('iblock')) {
    throw new \Main\Exception("Infoblock module is't installed.");
}

/**
 * Класс для работы с инфоблоками
 */
class Prototype
{
    /**
     * Стандартный GetList с кешированием
     *
     * @param $arOrder
     * @param $arFilter
     * @param $arSelect
     * @return array
     */
    public static function GetList($arOrder, $arFilter, $arSelect)
    {
        $arSelect = array_merge(array('ID'), $arSelect);

        $arFilter = array_merge(array('ACTIVE' => 'Y'), $arFilter);
        
        $arOrder = array_merge(array('SORT' => 'DESC'), $arOrder);

        $arResult = array();
        $сache = \Bitrix\Main\Data\Cache::createInstance();

        if( $сache->initCache(86400, __METHOD__, __CLASS__) ){
            $arResult = $сache->getVars();
        }
        elseif( $сache->startDataCache() ) {
            $rsRes = \CIBlockElement::GetList(
                $arOrder,
                $arFilter,
                false,
                false,
                $arSelect
            );

            if ( $rsRes->SelectedRowsCount() == 0 )
            {
//                $cache->abortDataCache();
            }
            else{

                while($arItem = $rsRes->GetNext()){
                    $arResult[] = $arItem;
                }
                $сache->endDataCache($arResult);
            }
        }

        return $arResult;
    }
}