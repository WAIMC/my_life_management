<?php

namespace App\Http\Controllers\Management;

use App\Http\Requests\Management\EntryDescriptionMgmt\ListEntryDescriptionMgmtRequest;
use App\Http\Requests\Management\EntryDescriptionMgmt\StoreEntryDescriptionMgmtRequest;
use App\Http\Requests\Management\EntryDescriptionMgmt\UpdateEntryDescriptionMgmtRequest;
use App\Http\Requests\Management\EntryDescriptionMgmt\DeleteEntryDescriptionMgmtRequest;
use App\Services\Management\EntryDescriptionMgmtService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class EntryDescriptionMgmtController extends Controller
{
    public function __construct(
        protected EntryDescriptionMgmtService $entryDescriptionMgmt
    )
    {
    }
    
    /**
     * EntryDescriptionMgmt list
     *
     * @param ListEntryDescriptionMgmtRequest $request
     * @return JsonResource
     */
    public function list(ListEntryDescriptionMgmtRequest $request): JsonResource
    {
        return $this->entryDescriptionMgmt->list($request->all());
    }

    /**
     * Store entry description mgmt
     *
     * @param StoreEntryDescriptionMgmtRequest $request
     * @return int
     */
    public function store(StoreEntryDescriptionMgmtRequest $request): int
    {
        return $this->entryDescriptionMgmt->store($request->all());
    }

    /**
     * Update entry description mgmt
     *
     * @param UpdateEntryDescriptionMgmtRequest $request
     * @param string $id
     * @return int
     */
    public function update(UpdateEntryDescriptionMgmtRequest $request, string $id): int
    {
        $payload = $request->all();
        $payload['id'] = $id;

        return $this->entryDescriptionMgmt->update($payload);
    }

    /**
     * Delete entry description mgmt
     *
     * @param DeleteEntryDescriptionMgmtRequest $request
     * @return void
     */
    public function delete(DeleteEntryDescriptionMgmtRequest $request): void
    {
        $this->entryDescriptionMgmt->delete($request->all());
    }
}
