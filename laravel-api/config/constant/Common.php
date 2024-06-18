<?php

namespace App;

class Common
{
  /**
   * Http status code: Information responses 100 - 199
   */
  const HTTP_CONTINUE = 100;
  const HTTP_SWITCHING_PROTOCOLS = 101;
  const HTTP_PROCESSING = 102;
  const HTTP_EARLY_HINTS = 103;

  /**
   * Http status code: Successful responses 200 - 299
   */
  const HTTP_OK = 200;
  const HTTP_CREATED = 201;
  const HTTP_ACCEPTED = 202;
  const HTTP_NON_AUTHORITATIVE_INFO = 203;
  const HTTP_NO_CONTENT = 204;
  const HTTP_RESET_CONTENT = 205;
  const HTTP_PARTIAL_CONTENT = 206;
  const HTTP_MULTI_STATUS = 207;
  const HTTP_ALREADY_REPORT = 208;
  const HTTP_IM_USED = 226;

  /**
   * Http status code: Redirection messages 300 - 399
   */
  const HTTP_MULTIPLE_CHOICES = 300;
  const HTTP_FOUND = 302;
  const HTTP_SEE_OTHER = 303;
  const HTTP_NOT_MODIFIED = 304;
  const HTTP_TEMPORARY_REDIRECT = 307;
  const HTTP_PERMANENT_REDIRECT = 308;

  /**
   * Http status code: Client error responses 400 - 499
   */
  const HTTP_BAD_REQUEST = 400;
  const HTTP_UNAUTHORIZED = 401;
  const HTTP_PAYMENT_REQUIRED = 402;
  const HTTP_FORBIDDEN = 403;
  const HTTP_NOT_FOUND = 404;
  const HTTP_METHOD_NOT_ALLOWED = 405;
  const HTTP_NOT_ACCEPTABLE = 406;
  const HTTP_PROXY_AUTHENTICATION_REQUIRED = 407;
  const HTTP_REQUEST_TIMEOUT = 408;
  const HTTP_CONFLICT = 409;
  const HTTP_GONE = 410;
  const HTTP_UNPROCESSABLE_CONTENT = 422;

  /**
   * Http status code: Server error responses 500 - 599
   */
  const HTTP_INTERNAL_SERVER_ERROR = 500;
  const HTTP_NOT_IMPLEMENTED = 501;
  const HTTP_BAD_GATEWAY = 502;
  const HTTP_SERVICE_UNAVAILABLE = 503;

  const DATE_FORMAT = 'd/m/Y';
}
