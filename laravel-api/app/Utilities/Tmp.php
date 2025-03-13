<?php

namespace App\Utilities;

class Tmp
{
  /**
   * Compare keys and values data type of two array
   * 
   * @param array $arr1
   * @param array $arr2
   * @return bool
   */
  public static function areSameKeysAndType($arr1, $arr2): bool
  {
    // Check if both arrays have the same keys
    if (array_keys($arr1) !== array_keys($arr2)) {
      return false;
    }

    // Check if the values have the same type
    foreach ($arr1 as $key => $value) {
      if (gettype($value) !== gettype($arr2[$key])) {
        return false;
      }
    }

    return true;
  }

  /**
   * Binding by fields
   * 
   * @param array $fields
   * @param array $payload
   * @return array
   */
  public static function twoWayBindingByFields(array $fields, array $payload): array
  {
    $data = [];
    foreach ($fields as $value) {
      if (isset($payload[$value])) $data[$value] = $payload[$value];
    }

    return $data;
  }
}
