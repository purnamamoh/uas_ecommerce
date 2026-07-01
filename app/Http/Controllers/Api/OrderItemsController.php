<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrderItemModel;
use Illuminate\Http\Request;
use App\Helpers\ApiFormatter;
use Throwable;

class OrderItemController extends Controller
{
    /**
     * Menampilkan semua data order item.
     */
    public function index()
    {
        try {
            $orderItems = OrderItemModel::with(['order', 'product'])->get();

            return ApiFormatter::createJson(
                200,
                'Data order item berhasil diambil.',
                $orderItems
            );
        } catch (Throwable $e) {
            return ApiFormatter::createJson(
                500,
                'Internal Server Error',
                ['error' => $e->getMessage()]
            );
        }
    }

    /**
     * Menyimpan data order item baru.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'order_id' => 'required|exists:orders,id',
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',
                'price_per_item' => 'required|integer|min:0',
            ]);

            $orderItem = OrderItemModel::create($validated);

            return ApiFormatter::createJson(
                201,
                'Order item berhasil ditambahkan.',
                $orderItem
            );
        } catch (Throwable $e) {
            return ApiFormatter::createJson(
                500,
                'Internal Server Error',
                ['error' => $e->getMessage()]
            );
        }
    }

    /**
     * Menampilkan detail order item.
     */
    public function show($id)
    {
        try {
            $orderItem = OrderItemModel::with(['order', 'product'])->find($id);

            if (!$orderItem) {
                return ApiFormatter::createJson(
                    404,
                    'Not Found',
                    'Order item tidak ditemukan.'
                );
            }

            return ApiFormatter::createJson(
                200,
                'Detail Order Item',
                $orderItem
            );
        } catch (Throwable $e) {
            return ApiFormatter::createJson(
                500,
                'Internal Server Error',
                ['error' => $e->getMessage()]
            );
        }
    }

    /**
     * Mengubah data order item.
     */
    public function update(Request $request, $id)
    {
        try {
            $orderItem = OrderItemModel::find($id);

            if (!$orderItem) {
                return ApiFormatter::createJson(
                    404,
                    'Not Found',
                    'Order item tidak ditemukan.'
                );
            }

            $validated = $request->validate([
                'order_id' => 'required|exists:orders,id',
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',
                'price_per_item' => 'required|integer|min:0',
            ]);

            $orderItem->update($validated);

            return ApiFormatter::createJson(
                200,
                'Order item berhasil diperbarui.',
                $orderItem
            );
        } catch (Throwable $e) {
            return ApiFormatter::createJson(
                500,
                'Internal Server Error',
                ['error' => $e->getMessage()]
            );
        }
    }

    /**
     * Menghapus data order item.
     */
    public function destroy($id)
    {
        try {
            $orderItem = OrderItemModel::find($id);

            if (!$orderItem) {
                return ApiFormatter::createJson(
                    404,
                    'Not Found',
                    'Order item tidak ditemukan.'
                );
            }

            $orderItem->delete();

            return ApiFormatter::createJson(
                200,
                'Order item berhasil dihapus.',
                null
            );
        } catch (Throwable $e) {
            return ApiFormatter::createJson(
                500,
                'Internal Server Error',
                ['error' => $e->getMessage()]
            );
        }
    }
}
