<?php

namespace App\Http\Controllers\History\Management;

use App\Http\Requests\History\Management\EntryMgmtHist\ListEntryMgmtHistRequest;
use App\Http\Requests\History\Management\EntryMgmtHist\StoreEntryMgmtHistRequest;
use App\Http\Requests\History\Management\EntryMgmtHist\UpdateEntryMgmtHistRequest;
use App\Http\Requests\History\Management\EntryMgmtHist\DeleteEntryMgmtHistRequest;
use App\Services\History\Management\EntryMgmtHistService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class EntryMgmtHistController extends Controller
{
    public function __construct(
        protected EntryMgmtHistService $entryMgmtHist
    )
    {
    }
    
    /**
     * EntryMgmtHist list
     *
     * @param ListEntryMgmtHistRequest $request
     * @return JsonResource
     */
    public function list(ListEntryMgmtHistRequest $request): JsonResource
    {
        return $this->entryMgmtHist->list($request->all());
    }

    /**
     * Store entry mgmt hist
     *
     * @param StoreEntryMgmtHistRequest $request
     * @return int
     */
    public function store(StoreEntryMgmtHistRequest $request): int
    {
        return $this->entryMgmtHist->store($request->all());
    }

    /**
     * Update entry mgmt hist
     *
     * @param UpdateEntryMgmtHistRequest $request
     * @param string $id
     * @return int
     */
    public function update(UpdateEntryMgmtHistRequest $request, string $id): int
    {
        $payload = $request->all();
        $payload['id'] = $id;

        return $this->entryMgmtHist->update($payload);
    }

    /**
     * Delete entry mgmt hist
     *
     * @param DeleteEntryMgmtHistRequest $request
     * @return void
     */
    public function delete(DeleteEntryMgmtHistRequest $request): void
    {
        $this->entryMgmtHist->delete($request->all());
    }
}
