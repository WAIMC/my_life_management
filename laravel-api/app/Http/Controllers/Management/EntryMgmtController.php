<?php

namespace App\Http\Controllers\Management;

use App\Http\Requests\Management\EntryMgmt\ListEntryMgmtRequest;
use App\Http\Requests\Management\EntryMgmt\StoreEntryMgmtRequest;
use App\Http\Requests\Management\EntryMgmt\UpdateEntryMgmtRequest;
use App\Http\Requests\Management\EntryMgmt\DeleteEntryMgmtRequest;
use App\Services\Management\EntryMgmtService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class EntryMgmtController extends Controller
{
    public function __construct(
        protected EntryMgmtService $entryMgmt
    )
    {
    }
    
    /**
     * EntryMgmt list
     *
     * @param ListEntryMgmtRequest $request
     * @return JsonResource
     */
    public function list(ListEntryMgmtRequest $request): JsonResource
    {
        return $this->entryMgmt->list($request->all());
    }

    /**
     * Store entry mgmt
     *
     * @param StoreEntryMgmtRequest $request
     * @return int
     */
    public function store(StoreEntryMgmtRequest $request): int
    {
        return $this->entryMgmt->store($request->all());
    }

    /**
     * Update entry mgmt
     *
     * @param UpdateEntryMgmtRequest $request
     * @param string $id
     * @return int
     */
    public function update(UpdateEntryMgmtRequest $request, string $id): int
    {
        $payload = $request->all();
        $payload['id'] = $id;

        return $this->entryMgmt->update($payload);
    }

    /**
     * Delete entry mgmt
     *
     * @param DeleteEntryMgmtRequest $request
     * @return void
     */
    public function delete(DeleteEntryMgmtRequest $request): void
    {
        $this->entryMgmt->delete($request->all());
    }
}
