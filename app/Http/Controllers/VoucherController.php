<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVoucherRequest;
use App\Http\Requests\UpdateVoucherRequest;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class VoucherController extends Controller implements HasMiddleware
{
    /**
     * @return Middleware[]
     */
    public static function middleware(): array
    {
        return [
            new Middleware('auth:sanctum'),
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Collection|JsonResponse
     */
    public function index(Request $request): Collection|JsonResponse
    {
        try {
            // Get all vouchers
            return $request->user()->vouchers()->get();
        } catch (Exception) {
            return response()->json([
                'message' => 'Failed to retrieve vouchers',
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreVoucherRequest $request
     * @return JsonResponse
     */
    public function store(StoreVoucherRequest $request): JsonResponse
    {
        try {
            // Get authenticated user
            $user = $request->user();

            // Check if user has more than 10 vouchers
            if ($user->vouchers()->count() >= 10) {
                return response()->json([
                    'message' => 'Maximum number of vouchers reached',
                ], 400);
            }

            // Create voucher
            $voucher = $user->vouchers()->create([
                'voucher' => $request->voucher,
            ]);

            // Return created voucher
            return response()->json($voucher, 201);
        } catch (Exception) {
            return response()->json([
                'message' => 'Failed to store voucher',
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function show(int $id, Request $request): JsonResponse
    {
        try {
            // Find voucher
            $voucher = $request->user()->vouchers()->findOrFail($id);

            return response()->json($voucher);
        } catch (Exception) {
            return response()->json([
                'message' => 'Failed to view voucher',
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @param UpdateVoucherRequest $request
     * @return JsonResponse
     */
    public function update(UpdateVoucherRequest $request, int $id): JsonResponse
    {
        try {
            // Find voucher
            $voucher = $request->user()->vouchers()->findOrFail($id);

            // Update voucher
            $voucher->update($request->validated());

            // Return the updated voucher
            return response()->json($voucher);
        } catch (Exception) {
            return response()->json([
                'message' => 'Failed to update voucher',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(int $id, Request $request): JsonResponse
    {
        try {
            // Find voucher
            $voucher = $request->user()->vouchers()->findOrFail($id);

            // Delete voucher
            $voucher->delete();

            // Return success message
            return response()->json([
                'message' => "Voucher ID $id deleted successfully",
            ]);
        } catch (Exception) {
            return response()->json([
                'message' => 'Failed to delete voucher',
            ], 500);
        }
    }
}
