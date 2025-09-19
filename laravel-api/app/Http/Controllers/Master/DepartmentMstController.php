<?php

namespace App\Http\Controllers\Master;

use App\Models\Master\ApiMst;
use App\Constants\Messages;
use App\Constants\CommonVal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Master\DepartmentMstService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

class DepartmentMstController extends Controller
{
  /**
   * DepartmentMst list
   *
   * @param Request $request
   * @return Response
   */
  public function list(Request $request): Response
  {
    return $this->handleRequest(function () use ($request) {
      // Check valid method
      if ($request->method() !== ApiMst::TYPE_OF_METHOD[0]) {
        throw new MethodNotAllowedException(
          [ApiMst::TYPE_OF_METHOD[0]],
          Messages::E0405,
          CommonVal::HTTP_METHOD_NOT_ALLOWED
        );
      }
      $payload = $request->all();

      return DepartmentMstService::getInstance()->list($payload);
    });
  }

  /**
   * Store department
   *
   * @param Request $request
   * @return Response
   */
  public function store(Request $request): Response
  {
    return $this->handleRequest(function () use ($request) {
      // Check valid method
      if ($request->method() !== ApiMst::TYPE_OF_METHOD[1]) {
        throw new MethodNotAllowedException(
          [ApiMst::TYPE_OF_METHOD[1]],
          Messages::E0405,
          CommonVal::HTTP_METHOD_NOT_ALLOWED
        );
      }
      $payload = $request->all();

      return DepartmentMstService::getInstance()->store($payload);
    });
  }

  /**
   * Update department
   *
   * @param Request $request
   * @param string $id
   * @return Response
   */
  public function update(Request $request, string $id): Response
  {
    return $this->handleRequest(function () use ($request, $id) {
      // Check valid method
      if ($request->method() !== ApiMst::TYPE_OF_METHOD[2]) {
        throw new MethodNotAllowedException(
          [ApiMst::TYPE_OF_METHOD[2]],
          Messages::E0405,
          CommonVal::HTTP_METHOD_NOT_ALLOWED
        );
      }
      $payload = $request->all();
      $payload['id'] = $id;

      return DepartmentMstService::getInstance()->update($payload);
    });
  }

  /**
   * Delete department
   *
   * @param Request $request
   * @param string $id
   * @return Response
   */
  public function delete(Request $request, string $id): Response
  {
    return $this->handleRequest(function () use ($request, $id) {
      // Check valid method
      if ($request->method() !== ApiMst::TYPE_OF_METHOD[4]) {
        throw new MethodNotAllowedException(
          [ApiMst::TYPE_OF_METHOD[4]],
          Messages::E0405,
          CommonVal::HTTP_METHOD_NOT_ALLOWED
        );
      }

      return DepartmentMstService::getInstance()->delete($id);
    });
  }
}
