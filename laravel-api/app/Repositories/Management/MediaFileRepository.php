<?php

namespace App\Repositories\Management;

use App\Interfaces\Management\MediaFileInterface;
use App\Repositories\BaseRepository;

class MediaFileRepository extends BaseRepository implements MediaFileInterface
{
  /**
   * @var string
   */
  protected $model;

  public function __construct(\App\Models\Management\MediaMgmt $model)
  {
    parent::__construct($model);
  }
}
