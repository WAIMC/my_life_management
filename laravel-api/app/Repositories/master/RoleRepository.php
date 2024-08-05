<?php

namespace App\Repositories\master;

use DateTime;
use App\Models\master\Role;
use App\Constants\CommonVal;
use Illuminate\Database\Eloquent\Collection;

class RoleRepository
{
  /**
   * Get role list
   * 
   * @param array @payload
   * @return Collection
   */
  public function list(array $payload): Collection
  {
    $query = Role::query()
      ->select('id', 'name', 'permission', 'is_active', 'updated_at');

    if (isset($payload['name'])) {
      $query->where('name', 'like', '%' . $payload['name'] . '%');
    }

    if (isset($payload['permission'])) {
      $query->where('permission', 'like', '%' . $payload['permission'] . '%');
    }

    if (isset($payload['is_active'])) {
      $query->where('is_active', $payload['is_active']);
    }

    if (isset($payload['from_date'])) {
      $fromDate = DateTime::createFromFormat(CommonVal::DATE_FORMAT, $payload['from_date']);
      $query->whereDate('updated_at', '>=', $fromDate);
    }

    if (isset($payload['to_date'])) {
      $toDate = DateTime::createFromFormat(CommonVal::DATE_FORMAT, $payload['to_date']);
      $query->whereDate('updated_at', '<=', $toDate);
    }

    return $query->get();
  }
}
