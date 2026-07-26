<?php

namespace App\Http\Controllers\Api;

use App\Filters\ContractFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Contract\ContractIndexRequest;
use App\Http\Requests\Contract\StoreContractRequest;
use App\Http\Requests\Contract\UpdateContractRequest;
use App\Http\Resources\ContractResource;
use App\Models\Contract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;


class ContractController extends Controller
{
    public function index(ContractIndexRequest $request): AnonymousResourceCollection
    {
        $filter = new ContractFilter($request);

        $contracts = Contract::query();

        $contracts = $filter->apply($contracts);

        $contracts->with([
            'counterparty',
            'createdBy',
        ]);

        return ContractResource::collection(
            $contracts->paginate()
        );
    }

    public function show(Contract $contract): ContractResource
    {
        $contract->load([
            'counterparty',
            'createdBy',
        ]);

        return new ContractResource($contract);
    }

    public function store(StoreContractRequest $request): JsonResponse
    {
        $data = $request->validated();

        $data['created_by'] = Auth::id();

        $contract = Contract::create($data);

        $contract->load([
            'counterparty',
            'createdBy',
        ]);

        return (new ContractResource($contract))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateContractRequest $request, Contract $contract): ContractResource
    {
        $data = $request->validated();

        $contract->update($data);

        $contract->load([
            'counterparty',
            'createdBy',
        ]);

        return new ContractResource($contract);
    }

    public function destroy(Contract $contract): JsonResponse
    {
        $contract->delete();

        return response()->json(null, 204);
    }
}
