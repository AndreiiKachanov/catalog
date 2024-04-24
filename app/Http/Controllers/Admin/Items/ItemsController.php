<?php

namespace App\Http\Controllers\Admin\Items;

use App\Models\Admin\Cart\CartItem;
use App\Models\Admin\Item\Category;
use App\Http\Requests\Admin\Items\CreateItemRequest;
use App\Models\Admin\Item\Item;
use App\Services\ImageService;
use App\Http\Controllers\Controller;
use App\UseCases\Categories\CategoryService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Exception;

class ItemsController extends Controller
{
    /**
     * @param Request $request
     * @return Application|Factory|View|RedirectResponse
     */
    public function index(Request $request)
    {
        $radioButton = $request->get('radio_search_by', 'title');
        $searchInput = $request->get('search_input');

        // Если зашли после redirect, т.е. после неудачной валидации
        if (session('redirected_after_fail')) {
            $items = new LengthAwarePaginator([], 0, config('app.pagination_default_value'));
            return view('admin.items.index', compact('items', 'searchInput', 'radioButton'));
        }

        $query = Item::orderByDesc('created_at');

        if ($request->has('find')) {
            $validator = $this->validateSearch($request);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator)->with('redirected_after_fail', true);
            }

            if ($searchInput !== null) {
                if ($radioButton === 'title') {
                    $query->where('title', 'LIKE', "%$searchInput%");
                }

                if ($radioButton === 'article_number') {
                    $query->where('article_number', 'LIKE', "%$searchInput%");
                }

                if ($radioButton === 'category') {
                    $ids = Category::where('title', 'LIKE', "%$searchInput%")->pluck('id')->toArray();
                    $query->whereIn('category_id', $ids);
                }
            }
        }
        $items = $query->with(['category' => fn(/**@var Category $query*/ $query) => $query->withTrashed()])
            ->withCount([
                'orderItems',
                'cartItems as not_ordered_count' => fn(/**@var CartItem $query*/ $query) => $query->doesntHave('orderItem'),
            ])
            ->paginate(config('app.pagination_default_value'));

        return view('admin.items.index', compact('items', 'searchInput', 'radioButton'));
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function create(Request $request)
    {
        $categories = CategoryService::getCategoriesWithTree();
        $category = $request->has('category')
            ? Category::findOrFail($request->get('category'))
            : null;
        return view('admin.items.create', compact('categories', 'category'));
    }

    /**
     * @param CreateItemRequest $request
     * @param ImageService $imageService
     * @return RedirectResponse
     */
    public function store(CreateItemRequest $request, ImageService $imageService)
    {
        try {
            $data = $this->processData($request);
            $data['is_new'] = $request->has('is_new');
            $data['is_hit'] = $request->has('is_hit');
            $data['is_bestseller'] = $request->has('is_bestseller');
            $data['category_id'] = $request->get('category');

            if ($request->hasFile('img')) {
                $filePath = $request->file('img')->getRealpath();
                $data['img'] = $imageService->saveImageWithResize($filePath, 'items');
            }
            $item = Item::create($data);

            $item->update(['article_number' => $item->id . '.' . $item->article_number]);
            return redirect()->route('admin.items.show', $item);
        } catch (Exception $exception) {
            writeErrorToFile($exception->getMessage());
            return redirect()
            ->back()
            ->withInput()
            ->withErrors('Ошибка сохранения товара. См. лог.');
        }
    }

    /**
     * @param Item $item
     * @return Application|Factory|View
     */
    public function show(Item $item)
    {
        $item->load(['category' => fn(/**@var Category $query */ $query) => $query->withTrashed()])
            ->loadCount([
                'cartItems as not_ordered_count' => fn(/**@var CartItem $query */ $query
                ) => $query->doesntHave('orderItem')
            ]);
        return view('admin.items.show', compact('item'));
    }

    /**
     * @param Item $item
     * @return Application|Factory|View
     */
    public function edit(Item $item)
    {
        $categories = CategoryService::getCategoriesWithTree();
        return view('admin.items.edit', compact('item', 'categories'));
    }

    /**
     * @param CreateItemRequest $request
     * @param Item $item
     * @param ImageService $imageService
     * @return RedirectResponse
     */
    public function update(CreateItemRequest $request, Item $item, ImageService $imageService)
    {
        try {
            $data = $this->processData($request);
            $data['is_new'] = $request->has('is_new');
            $data['is_hit'] = $request->has('is_hit');
            $data['is_bestseller'] = $request->has('is_bestseller');
            $data['category_id'] = $request->get('category');

            if ($request->hasFile('img')) {
                $filePath = $request->file('img')->getRealpath();
                $data['img'] = $imageService->saveImageWithResize($filePath, 'items');
            }

            $item->update($data);
            return redirect()->route('admin.items.show', compact('item'));
        } catch (Exception $exception) {
            writeErrorToFile($exception->getMessage());
            return redirect()
                ->back()
                ->withInput()
                ->withErrors('Ошибка обновления товара. См. лог.');
        }
    }

    /**
     * @param Item $item
     * @return RedirectResponse
     */
    public function destroy(Item $item)
    {
        $item->delete();
        return redirect()->route('admin.items.index');
    }

    /**
     * @param Item $item
     * @return JsonResponse
     */
    public function checkBeforeRemove(Item $item)
    {
        $json = ['not_ordered_count' => $item->cartItems()->doesntHave('orderItem')->count()];
        return response()->json(($json));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Validation\Validator
     */
    private function validateSearch(Request $request)
    {
        $maxSearchLength = 70;
        return Validator::make($request->all(), [
            'radio_search_by' => 'required',
            'search_input' => "nullable|max:$maxSearchLength"
        ], [
            'radio_search_by.required' => 'Выберите категорию поиска',
            'search_input.max' => '<b>:attribute</b> не должна превышать :max ' . Lang::choice('символ|символа|символов', $maxSearchLength),
        ], [
            'search_input' => 'Строка поиска',
        ]);
    }

    /**
     * @param CreateItemRequest $request
     * @return array
     */
    private function processData(CreateItemRequest $request)
    {
        return $request->only([
            'title', 'note', 'article_number', 'price', 'min_order_amount'
        ]);
    }
}
