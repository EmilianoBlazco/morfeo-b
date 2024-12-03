<?php

namespace App\Http\Controllers\License;

use App\Http\Controllers\Controller;
use App\Http\Requests\License\LicenseStoreRequest;
use App\Models\License;
use Illuminate\Http\JsonResponse;

class LicenseController extends Controller
{
    public function index(): JsonResponse
    {
        $licenses = License::all();
        $licensesCount = License::count();
        return response()->json(['licenses' => $licenses, 'licenses_count' => $licensesCount]);
    }

    public function store(LicenseStoreRequest $request): JsonResponse
    {
        $license = License::create($request->validated());
        return response()->json($license, 201);
    }

    public function show(License $license): JsonResponse
    {
        return response()->json($license);
    }

    public function destroy(License $license): JsonResponse
    {
        $license->delete();
        return response()->json(['message' => 'License deleted successfully']);
    }
}
