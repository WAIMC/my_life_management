<?php

namespace App\Constants;

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
   * Errors validation messages
   */
  const E0001 = '{attributes} invalid type numeric';      // Numeric
  const E0002 = '{attributes} invalid type string';       // String
  const E0003 = '{attributes} invalid type float';        // Float
  const E0004 = '{attributes} invalid type double';       // Double
  const E0005 = '{attributes} invalid type boolean';      // Boolean
  const E0006 = '{attributes} invalid type email';        // email
  const E0007 = '{attributes} is required';               // Required
  const E0008 = '{attributes} is unique';                 // Unique
  const E0009 = '{attributes} invalid date format';       // Date
  const E0010 = '{attributes} min is {number}';           // Min
  const E0011 = '{attributes} max is {number}';           // Max
  const E0012 = '{attributes} invalid date';              // Date
  const E0013 = '{attributes} invalid date format';       // Date format
  const E0014 = '{attributes} bigger than {attributes2}'; // Bigger date

  /**
   * Errors http messages
   */
  const E0401 = 'Unauthorized Access';
  const E0403 = 'Access is forbidden';
  const E0404 = 'Not Found';
  const E0500 = 'Internal server error';

  /**
   * Errors JWT messages
   */
  const E0600 = 'Wrong number of segments';
  const E0601 = 'Invalid header encoding';
  const E0602 = 'Invalid data header';
  const E0603 = 'Invalid claims encoding';
  const E0604 = 'Invalid data claims';
  const E0605 = 'Invalid signature encoding';
  const E0606 = 'Signature verification failed';
  const E0607 = 'Invalid expiration time';
  const E0608 = 'Invalid type member';

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
