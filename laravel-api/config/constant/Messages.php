<?php

namespace App;

class Messages
{
  /**
   * Notification message
   */
  const N0001 = 'temp';

  /**
   * Warning message
   */
  const W0001 = 'temp';

  /**
   * Errors message
   */
  const E0001 = '{attributes} invalid type numeric'; // Numeric
  const E0002 = '{attributes} invalid type string'; // String
  const E0003 = '{attributes} invalid type float'; // Float
  const E0004 = '{attributes} invalid type double'; // Double
  const E0005 = '{attributes} invalid type boolean'; // Boolean
  const E0010 = '{attributes} min is {number}'; // Min
  const E0011 = '{attributes} max is {number}'; // Max
  const E0012 = '{attributes} invalid date'; // Date
  const E0013 = '{attributes} invalid date format'; // Date format
  const E0014 = '{attributes} bigger than {attributes2}'; // Bigger date
  const E0404 = 'Method not allowed';
  const E0500 = 'Internal server error';

  /**
   * Get message
   * 
   * @param string $message
   * @param array $attributes = []
   * @return string
   */
  public static function getMessage(string $message, array $attributes = []): string
  {
    if (!empty($attributes)) {
      foreach ($attributes as $key => $value) {
        $attr = '{' . $key . '}';
        $message = str_replace($attr, $value, $message);
      }
    }

    return $message;
  }
}
