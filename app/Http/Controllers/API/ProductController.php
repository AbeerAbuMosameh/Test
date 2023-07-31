<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ApiResponseTrait;
use App\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class ProductController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $validated_data = Validator::make($request->all(), [
            'limit' => 'integer|min:1',
            'page' => 'integer|min:1',
            'status' => 'in:active,inactive,published,scheduled',
        ]);

        if ($validated_data->fails()) {
            return $this->errorResponse([], $validated_data->errors()->first(), 422);
        }

        $limit = $request->input('limit', 10);
        $page = $request->input('page', 1);
        $status = $request->input('status', '');
        $query = Product::query();

        if ($status) {
            $query->where('status', $status);
        }

        $products = $query->paginate($limit, ['*'], 'page', $page);
        return $this->successResponse($products, trans('product.all'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated_data = Validator::make($request->all(), [
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg',
            'name' => 'array',
            'name.*' => 'string|min:3',
            'model' => 'required|string',
            'sku' => 'required|string|unique:products',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'rate' => 'required|numeric|between:1,5',
            'status' => 'required|in:active,inactive,published,scheduled',
            'description' => 'required|array',
            'description.*' => 'required|string|min:3',
            'discount_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'slug' => 'unique:products',
            'keyword' => 'nullable|string',
            'meta_title' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'product_tag' => 'nullable|string',
            'in_stock' => 'required|in:yes,no',
            'limited_inStock' => 'required|in:yes,no',
            'width' => 'required|numeric|min:0',
            'height' => 'required|numeric|min:0',
            'weight' => 'required|numeric|min:0',
            'length' => 'required|numeric|min:0',
        ]);

        if ($validated_data->fails()) {
            return $this->errorResponse([], $validated_data->errors()->first(), 422);
        }

        $product = new Product;
        $product->fill($request->all());

        if ($request->has('name')) {
            $product->setTranslations('name', $request->input('name'));
        }

        if ($request->has('description')) {
            $product->setTranslations('description', $request->input('description'));
        }

        if ($request->hasFile('image')) {
            $product_image = $request->file('image');
            $product_image_path = $this->storeImage($product_image);
            $product->image = $product_image_path;
        }

        $product->save();
        return $this->successResponse($product, trans('product.add_success'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $product = Product::findOrFail($id);

            if (!$product) {
                return $this->errorResponse([], trans('product.show_error'), 404);
            }
            return $this->successResponse($product, trans('product.show_success'));


        } catch (ModelNotFoundException $e) {
            return $this->errorResponse([], trans('product.not_found'), 404);
        }

    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg',
            'name' => 'array',
            'name.*' => 'string|min:3',
            'model' => 'string',
            'sku' => [
                'string',
                Rule::unique('products')->ignore($id),
            ],
            'quantity' => 'integer|min:1',
            'price' => 'numeric|min:0',
            'rate' => 'numeric|between:1,5',
            'status' => 'in:active,inactive,published,scheduled',
            'description' => 'array',
            'description.*' => 'string|min:3',
            'discount_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'slug' => [
                Rule::unique('products')->ignore($id),
            ],
            'keyword' => 'nullable|string',
            'meta_title' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'product_tag' => 'nullable|string',
            'in_stock' => 'in:yes,no',
            'limited_inStock' => 'in:yes,no',
            'width' => 'numeric|min:0',
            'height' => 'numeric|min:0',
            'weight' => 'numeric|min:0',
            'length' => 'numeric|min:0',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse([], $validator->errors()->first(), 422);
        }

        try {
            $product = Product::findOrFail($id);

            if ($request->has('name')) {
                $product->setTranslations('name', $request->input('name'));
            }

            if ($request->has('description')) {
                $product->setTranslations('description', $request->input('description'));
            }

            $product->update($request->all());

            return $this->successResponse($product, trans('product.update_success'), 200);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse([], trans('product.not_found'), 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);

            $product->delete();
            return $this->successResponse([], trans('product.delete'));

        } catch (ModelNotFoundException $e) {
            return $this->errorResponse([], trans('product.not_found'), 404);
        }

    }
}
