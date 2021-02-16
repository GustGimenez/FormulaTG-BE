<?php

namespace FormulaTG\Utils;

class Helper
{
    public static function formParams(array $params): array
    {
        $formedParams = [];
        foreach ($params as $param) {
            $paramInfo = explode('=', $param);
            $formedParams[$paramInfo[0]] = $paramInfo[1];
        }

        return $formedParams;
    }

    public static function containsParam(array $params, string $expectedParam): bool
    {
        foreach ($params as $paramName => $paramValue) {
            if ($paramName === $expectedParam) {
                return true;
            }
        }

        return false;
    }

    public static function removeEntityFromParams(array $params): array
    {
        $entityIndex = array_search('car', $params);
        unset($params[$entityIndex]);
        return array_values($params);
    }
}