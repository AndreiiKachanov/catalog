<?php

namespace App\Http\Controllers\Admin\Items;

use App\Models\Admin\Item\Category;
use App\Http\Requests\Admin\Items\CreateCategoryRequest;
use App\Http\Controllers\Controller;
use App\Models\Admin\Item\Item;
use App\Services\ImageService;
use App\UseCases\Categories\CategoryService;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;

class CategoryController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        //$categories = Category::defaultOrder()->withCount(['items', 'orders'])->withDepth()->get();
        $categories = Category::defaultOrder()
            ->withCount(['items', 'orderItems' => function ($query) {
                $query->whereDoesntHave('order', function ($subQuery) {
                    $subQuery->onlyTrashed();
                });
            }])
            ->withDepth()
            ->get();
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * @return Application|Factory|View
     */
    public function create()
    {
        $categories = Arr::prepend(CategoryService::getCategoriesWithTree(), '', '');
        return view('admin.categories.create', compact('categories'));
    }

    /**
     * @param CreateCategoryRequest $request
     * @param ImageService $imageService
     * @return RedirectResponse
     */
    public function store(CreateCategoryRequest $request, ImageService $imageService)
    {
        try {
            $filePath = $request->file('img') !== null ? $request->file('img')->getRealpath() : null;
            Category::create([
                'parent_id' => $request->get('parent'),
                'title' => $request->get('title'),
                'img' => is_null($filePath)
                    ? null
                    : $imageService->saveImageWithResize($filePath, 'categories'),
            ]);
            return redirect()->route('admin.categories.index');
        } catch (Exception $exception) {
            writeErrorToFile($exception->getMessage());
            return redirect()
                ->back()
                ->withInput()
                ->withErrors('Ошибка сохранения категории. См. лог.');
        }
    }

    /**
     * @param Category $category
     * @return Application|Factory|View
     */
    public function show(Category $category)
    {
        $category->loadCount([
            'items',
            'children'
        ]);
        //$categories = $category->descendants()->pluck('id');
        //$categories[] = $category->getKey();
        //$countRelatedModels = Item::whereIn('category_id', $categories)->count();
        //return view('admin.categories.show', compact('category', 'countRelatedModels'));
        return view('admin.categories.show', compact('category'));
    }

    /**
     * @param Category $category
     * @return Application|Factory|View
     */
    public function edit(Category $category)
    {
        $categories = Arr::prepend(CategoryService::getCategoriesWithTree(), '', '');
        return view('admin.categories.edit', compact('category', 'categories'));
    }


    /**
     * @param CreateCategoryRequest $request
     * @param Category $category
     * @param ImageService $imageService
     * @return RedirectResponse
     */
    public function update(CreateCategoryRequest $request, Category $category, ImageService $imageService)
    {
        try {
            if ($request->hasFile('img')) {
                $filePath = $request->file('img')->getRealpath();
                $category->img = $imageService->saveImageWithResize($filePath, 'categories');
            }

            $category->fill([
                'slug' => null,
                'parent_id' => $request->get('parent'),
                'title' => $request->get('title'),
            ]);

            $category->save();
            return redirect()->route('admin.categories.show', compact('category'));
        } catch (Exception $exception) {
            writeErrorToFile($exception->getMessage());
            return redirect()
                ->back()
                ->withInput()
                ->withErrors('Ошибка обновления категории. См. лог.');
        }
    }

    /**
     * @param Category $category
     * @return RedirectResponse|void
     */
    public function destroy(Category $category)
    {
        $category->loadCount([
            'items',
            'children'
        ]);

        if ($category->items_count > 0 || $category->children_count > 0) {
            return redirect()->back()->withErrors('Нельзя удалить категорию имеющую подкатегории или товары');
        }
        if ($category->delete()) {
            return redirect()->route('admin.categories.index');
        }
    }

    /**
     * @param Category $category
     * @return RedirectResponse
     */
    public function first(Category $category)
    {
        if ($first = $category->siblings()->defaultOrder()->first()) {
            $category->insertBeforeNode($first);
        }
        return redirect()->route('admin.categories.index');
    }

    /**
     * @param Category $category
     * @return RedirectResponse
     */
    public function up(Category $category)
    {
        $category->up();

        return redirect()->route('admin.categories.index');
    }

    /**
     * @param Category $category
     * @return RedirectResponse
     */
    public function down(Category $category)
    {
        $category->down();

        return redirect()->route('admin.categories.index');
    }

    /**
     * @param Category $category
     * @return RedirectResponse
     */
    public function last(Category $category)
    {
        if ($last = $category->siblings()->defaultOrder('desc')->first()) {
            $category->insertAfterNode($last);
        }

        return redirect()->route('admin.categories.index');
    }

    /**
     * @param Category $category
     * @return Application|Factory|View
     */
    public function showOrders(Category $category)
    {
        $category->load([
            'orderItems' => function ($query) {
                // Загружаем только "orders", у которых не удалены "contact"
                $query->whereDoesntHave('order', function ($subQuery) {
                    $subQuery->onlyTrashed();
                })->orderByDesc('created_at');
            },
            'orderItems.order.token.invoice', // Загружаем связанные записи
            'orderItems.item' // Загружаем "orders.item"
        ]);
        //dd($category);
        return view('admin.categories.show_orders', compact('category'));
    }

    /**
     * @param Category $category
     * @return RedirectResponse
     */
    public function showItems(Category $category) {
        return redirect()->action([ItemsController::class, 'index'], [
            'radio_search_by' => 'category',
            'search_input' => $category->title,
            'find' => 'Поиск'
        ]);
    }
}
