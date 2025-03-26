<?php
namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->input('page', 1);
        $perPage = 2;

        $offset = ($page - 1) * $perPage;
        
        $all = Category::orderBy('id', 'desc')
        ->skip($offset)
        ->take($perPage)
        ->get();

        $total = Category::count();

        $totalPages = ceil($total / $perPage);

        return view('admin.category', ['categories' => $all, 'current_page' => $page,'total_pages' => $totalPages]);

    }

    public function store(CategoryRequest $request)
    {
        $category = new Category();
        $category->name = $request->name;
        $category->is_active = $request->is_active ?? 0;
        $category->save();

        return redirect()->back();
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $category->name = $request->name;
        $category->is_active = $request->is_active ?? 0;
        $category->save();

        return redirect()->back()->with('success');
    }

    public function delete(Category $category)
    {
        $category->delete();

        return redirect()->back()->with('success');
    }
}
