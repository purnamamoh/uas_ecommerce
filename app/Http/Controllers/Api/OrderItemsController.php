<?php

namespace App\Http\Controllers;

use App\Models\OrderItemModel;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    /**
     * Menampilkan semua data order item.
     */
    public function index()
    {
        $orderItems = OrderItemModel::with(['order', 'product'])->get();

        return response()->json([
            'success' => true,
            'message' => 'Data order item berhasil diambil.',
            'data' => $orderItems
        ]);
    }

    /**
     * Menyimpan data order item baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price_per_item' => 'required|integer|min:0',
        ]);

        $orderItem = OrderItemModel::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Order item berhasil ditambahkan.',
            'data' => $orderItem
        ], 201);
    }

    /**
     * Menampilkan detail order item.
     */
    public function show($id)
    {
        $orderItem = OrderItemModel::with(['order', 'product'])->find($id);

        if (!$orderItem) {
            return response()->json([
                'success' => false,
                'message' => 'Order item tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $orderItem
        ]);
    }

    /**
     * Mengubah data order item.
     */
    public function update(Request $request, $id)
    {
        $orderItem = OrderItemModel::find($id);

        if (!$orderItem) {
            return response()->json([
                'success' => false,
                'message' => 'Order item tidak ditemukan.'
            ], 404);
        }

        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price_per_item' => 'required|integer|min:0',
        ]);

        $orderItem->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Order item berhasil diperbarui.',
            'data' => $orderItem
        ]);
    }

    /**
     * Menghapus data order item.
     */
    public function destroy($id)
    {
        $orderItem = OrderItemModel::find($id);

        if (!$orderItem) {
            return response()->json([
                'success' => false,
                'message' => 'Order item tidak ditemukan.'
            ], 404);
        }

        $orderItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Order item berhasil dihapus.'
        ]);
    }
}
