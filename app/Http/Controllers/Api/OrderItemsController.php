<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrderItemModel;
use App\Helpers\ApiFormatter;
use Throwable;

class OrderItemsController extends Controller
{
    /**
     * Menampilkan semua order item.
     * Admin dapat melihat semua.
     * Customer hanya dapat melihat order item miliknya.
     */
    public function index()
    {
        try {

            $user = auth('api')->user();

            if ($user->role == 'admin') {

                $orderItems = OrderItemModel::with(['order', 'product'])
                    ->orderBy('created_at', 'DESC')
                    ->get();

            } else {

                $orderItems = OrderItemModel::with(['order', 'product'])
                    ->whereHas('order', function ($query) use ($user) {
                        $query->where('user_id', $user->id);
                    })
                    ->orderBy('created_at', 'DESC')
                    ->get();

            }

            return ApiFormatter::createJson(
                200,
                'Get Order Items Success',
                $orderItems
            );

        } catch (Throwable $e) {

            return ApiFormatter::createJson(
                500,
                'Internal Server Error',
                [
                    'error' => $e->getMessage()
                ]
            );

        }
    }

    /**
     * Detail satu order item.
     */
    public function show($id)
    {
        try {

            $user = auth('api')->user();

            $orderItem = OrderItemModel::with(['order', 'product'])
                ->find($id);

            if (!$orderItem) {

                return ApiFormatter::createJson(
                    404,
                    'Not Found',
                    'Order Item tidak ditemukan.'
                );

            }

            // Customer hanya boleh melihat order item miliknya
            if (
                $user->role != 'admin' &&
                $orderItem->order->user_id != $user->id
            ) {

                return ApiFormatter::createJson(
                    403,
                    'Forbidden',
                    'Anda tidak memiliki akses ke order item ini.'
                );

            }

            return ApiFormatter::createJson(
                200,
                'Get Detail Order Item Success',
                $orderItem
            );

        } catch (Throwable $e) {

            return ApiFormatter::createJson(
                500,
                'Internal Server Error',
                [
                    'error' => $e->getMessage()
                ]
            );

        }
    }

        public function byOrder($orderId)
    {
        try {

            $user = auth('api')->user();

            $items = OrderItemModel::with('product')
                ->where('order_id', $orderId)
                ->whereHas('order', function ($query) use ($user) {

                    if ($user->role != 'admin') {
                        $query->where('user_id', $user->id);
                    }

                })
                ->get();

            return ApiFormatter::createJson(
                200,
                'Get Order Items Success',
                $items
            );

        } catch (Throwable $e) {

            return ApiFormatter::createJson(
                500,
                'Internal Server Error',
                [
                    'error' => $e->getMessage()
                ]
            );

        }
    }
}
