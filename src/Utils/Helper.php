<?php

namespace FormulaTG\Utils;

use Exception;

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

    public static function validatePopulate(
        array $data,
        array $expectedValues,
        string $entity
    ): void {
        $expectedValues[] = 'id';

        foreach ($expectedValues as $expectedValue) {
            if (!array_key_exists($expectedValue, $data)) {
                throw new Exception("The key $expectedValue is expected to create a new $entity");
            }
        }
    }
}
