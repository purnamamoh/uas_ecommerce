<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductModel;
use Illuminate\Http\Request;
use App\Helpers\ApiFormatter;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    // Menampilkan semua data
    public function index()
    {
        try {

            $products = ProductModel::all();

            return ApiFormatter::createJson(
                200,
                'Daftar Produk',
                $products
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

    // Menyimpan data baru
    public function store(Request $request)
    {
        try {

            // Cek hak akses
            $user = auth('api')->user();

            if (!$user || $user->role !== 'admin') {
                return ApiFormatter::createJson(
                    403,
                    'Forbidden',
                    'Hanya admin yang dapat menambah produk.'
                );
            }

            // Validasi
            $validator = Validator::make($request->all(), [
                'name'        => 'required|string|max:255',
                'description' => 'required|string',
                'price'       => 'required|integer|min:0',
                'stock'       => 'required|integer|min:0',
            ], [
                'name.required'        => 'Nama produk wajib diisi.',
                'description.required' => 'Deskripsi wajib diisi.',
                'price.required'       => 'Harga wajib diisi.',
                'price.integer'        => 'Harga harus berupa angka.',
                'price.min'            => 'Harga tidak boleh kurang dari 0.',
                'stock.required'       => 'Stok wajib diisi.',
                'stock.integer'        => 'Stok harus berupa angka.',
                'stock.min'            => 'Stok tidak boleh kurang dari 0.',
            ]);

            if ($validator->fails()) {
                return ApiFormatter::createJson(
                    422,
                    'Validation Error',
                    $validator->errors()
                );
            }

            // Simpan data
            $product = ProductModel::create([
                'name'        => $request->name,
                'description' => $request->description,
                'price'       => $request->price,
                'stock'       => $request->stock,
            ]);

            return ApiFormatter::createJson(
                201,
                'Produk berhasil ditambahkan.',
                $product
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

    // Menampilkan detail produk
    public function show($id)
    {
        try {

            $product = ProductModel::find($id);

            if (!$product) {
                return ApiFormatter::createJson(
                    404,
                    'Not Found',
                    'Produk tidak ditemukan.'
                );
            }

            return ApiFormatter::createJson(
                200,
                'Detail Produk',
                $product
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

    // Mengubah data produk
    public function update(Request $request, $id)
    {
        try {

            $user = auth('api')->user();

            if (!$user || $user->role !== 'admin') {
                return ApiFormatter::createJson(
                    403,
                    'Forbidden',
                    'Hanya admin yang dapat mengubah produk.'
                );
            }

            $product = ProductModel::find($id);

            if (!$product) {
                return ApiFormatter::createJson(
                    404,
                    'Not Found',
                    'Produk tidak ditemukan.'
                );
            }

            $validator = Validator::make($request->all(), [
                'name'        => 'required|string|max:255',
                'description' => 'required|string',
                'price'       => 'required|integer|min:0',
                'stock'       => 'required|integer|min:0',
            ]);

            if ($validator->fails()) {
                return ApiFormatter::createJson(
                    422,
                    'Validation Error',
                    $validator->errors()
                );
            }

            $product->update($request->only([
                'name',
                'description',
                'price',
                'stock'
            ]));

            return ApiFormatter::createJson(
                200,
                'Produk berhasil diperbarui.',
                $product
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

    // Menghapus produk
    public function destroy($id)
    {
        try {

            $user = auth('api')->user();

            if (!$user || $user->role !== 'admin') {
                return ApiFormatter::createJson(
                    403,
                    'Forbidden',
                    'Hanya admin yang dapat menghapus produk.'
                );
            }

            $product = ProductModel::find($id);

            if (!$product) {
                return ApiFormatter::createJson(
                    404,
                    'Not Found',
                    'Produk tidak ditemukan.'
                );
            }

            $product->delete();

            return ApiFormatter::createJson(
                200,
                'Produk berhasil dihapus.',
                null
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
