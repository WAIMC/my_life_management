<?php

namespace App\Http\Controllers\master;

use App\Models\master\api;
use App\Constants\Messages;
use App\constants\CommonVal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\master\RoleService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

class RoleController extends Controller
{
  /**
   * Display a listing of the resource.
   * 
   * @param Request $request
   * @return Response
   */
  public function index(Request $request): Response
  {
    return $this->handleRequest(function () use ($request) {
      // Check valid method
      if ($request->method() !== api::TYPE_OF_METHOD[0]) {
        throw new MethodNotAllowedException(
          [api::TYPE_OF_METHOD[0]],
          Messages::E0405,
          CommonVal::HTTP_METHOD_NOT_ALLOWED
        );
      }
      $payload = $request->all();

      return RoleService::getInstance()->list($payload);
    });
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    //
  }

  /**
   * Display the specified resource.
   */
  public function show(string $id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(string $id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    //
  }
}
