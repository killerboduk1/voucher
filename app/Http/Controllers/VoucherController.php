<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Collection | JsonResponse
     */
    public function index(): Collection|JsonResponse
    {
        try {
            // Get all vouchers
            return Voucher::all();
        } catch (Exception) {
            return response()->json([
                'message' => 'Failed to retrieve vouchers'
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        // Validate request
        $request->validate([
            'voucher' => 'required|alpha_num|min:5|max:5|unique:vouchers'
        ]);

        try {
            // check if user has more than 10 vouchers
            if (Voucher::count() >= 10) {
                return response()->json([
                    'message' => 'Maximum number of vouchers reached'
                ], 400);
            }

            // create voucher
            $voucher = Voucher::create([
                'voucher' => $request->voucher
            ]);

            // return created voucher
            return response()->json($voucher);
        } catch (Exception) {
            return response()->json([
                'message' => 'Failed to store voucher'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            // Find voucher
            $voucher = Voucher::findOrFail($id);

            return response()->json($voucher);
        } catch (ModelNotFoundException) {
            return response()->json([
                'message' => 'Voucher not found'
            ], 404);
        } catch (Exception) {
            return response()->json([
                'message' => 'Failed to view voucher'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Voucher $voucher
     * @return JsonResponse
     */
    public function update(Request $request, Voucher $voucher): JsonResponse
    {
        // Validate request
        $fields = $request->validate([
            'voucher' => [
                'required',
                'alpha_num',
                'min:5',
                'max:5',
                Rule::unique('vouchers')->ignore($voucher->id),
            ]
        ]);

        try {
            // Update voucher
            $voucher->update($fields);

            return response()->json($voucher);
        } catch (Exception) {
            return response()->json([
                'message' => 'Failed to update voucher'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        try {
            // Delete voucher
            $voucher = Voucher::findOrFail($id);

            $voucher->delete();

            return response()->json([
                'message' => "Voucher ID $voucher->id deleted successfully"
            ]);
        } catch (ModelNotFoundException) {
            return response()->json([
                'message' => 'Voucher not found'
            ], 404);
        } catch (Exception) {
            return response()->json([
                'message' => 'Failed to delete voucher'
            ], 500);
        }
    }
}
