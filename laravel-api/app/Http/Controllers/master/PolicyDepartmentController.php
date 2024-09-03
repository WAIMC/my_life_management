<?php

namespace App\Http\Controllers\master;

use App\Models\master\Api;
use App\Constants\Messages;
use App\constants\CommonVal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Services\master\PolicyDepartmentService;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

class PolicyDepartmentController extends Controller
{
  /**
   * Policy department list
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

      return PolicyDepartmentService::getInstance()->list($payload);
    });
  }

  /**
   * Store policy department
   * 
   * @param Request $request
   * @return Response
   */
  public function store(Request $request): Response
  {
    return $this->handleRequest(function () use ($request) {
      // Check valid method
      if ($request->method() !== Api::TYPE_OF_METHOD[1]) {
        throw new MethodNotAllowedException(
          [Api::TYPE_OF_METHOD[1]],
          Messages::E0405,
          CommonVal::HTTP_METHOD_NOT_ALLOWED
        );
      }
      $payload = $request->all();

      return PolicyDepartmentService::getInstance()->store($payload);
    });
  }

  /**
   * Update policy department
   * 
   * @param Request $request
   * @param string $id
   * @return Response
   */
  public function update(Request $request, string $id): Response
  {
    return $this->handleRequest(function () use ($request, $id) {
      // Check valid method
      if ($request->method() !== Api::TYPE_OF_METHOD[2]) {
        throw new MethodNotAllowedException(
          [Api::TYPE_OF_METHOD[2]],
          Messages::E0405,
          CommonVal::HTTP_METHOD_NOT_ALLOWED
        );
      }
      $payload = $request->all();
      $payload['id'] = $id;

      return PolicyDepartmentService::getInstance()->update($payload);
    });
  }

  /**
   * Delete policy department
   * 
   * @param Request $request
   * @param string $id
   * @return Response
   */
  public function delete(Request $request, string $id): Response
  {
    return $this->handleRequest(function () use ($request, $id) {
      // Check valid method
      if ($request->method() !== Api::TYPE_OF_METHOD[4]) {
        throw new MethodNotAllowedException(
          [Api::TYPE_OF_METHOD[4]],
          Messages::E0405,
          CommonVal::HTTP_METHOD_NOT_ALLOWED
        );
      }

      return PolicyDepartmentService::getInstance()->delete($id);
    });
  }
}
