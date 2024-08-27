<?php

namespace App\Http\Controllers\master;

use App\Models\master\Api;
use App\Constants\Messages;
use App\constants\CommonVal;
use Illuminate\Http\Request;
use InvalidArgumentException;
use App\Http\Controllers\Controller;
use App\Services\master\AdminRoleService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

class AdminRoleController extends Controller
{
  /**
   * Admin role list
   * 
   * @param Request $request
   * @return Response
   */
  public function list(Request $request): Response
  {
    return $this->handleRequest(function () use ($request) {
      // Check valid method
      if ($request->method() !== Api::TYPE_OF_METHOD[0]) {
        throw new MethodNotAllowedException(
          [Api::TYPE_OF_METHOD[0]],
          Messages::E0405,
          CommonVal::HTTP_METHOD_NOT_ALLOWED
        );
      }
      $payload = $request->all();

      return AdminRoleService::getInstance()->list($payload);
    });
  }

  /**
   * Update admin role
   * 
   * @param Request $request
   * @return Response
   */
  public function update(Request $request): Response
  {
    return $this->handleRequest(function () use ($request) {
      // Check valid method
      if ($request->method() !== Api::TYPE_OF_METHOD[2]) {
        throw new MethodNotAllowedException(
          [Api::TYPE_OF_METHOD[2]],
          Messages::E0405,
          CommonVal::HTTP_METHOD_NOT_ALLOWED
        );
      }

      $payload = $request->all();
      if (!$payload) {
        throw new InvalidArgumentException(Messages::E0422, CommonVal::HTTP_UNPROCESSABLE_CONTENT);
      }
      $payload['admin_id'] = $request->attributes->get('admin_id');

      return AdminRoleService::getInstance()->update($payload);
    });
  }
}
