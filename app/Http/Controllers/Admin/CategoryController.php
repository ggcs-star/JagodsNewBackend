<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Status;
use App\Enums\UserRole;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Enums\CategoryStatus;
use Yajra\Datatables\Datatables;
use App\Http\Requests\CategoryRequest;
use App\Http\Controllers\BackendController;

class CategoryController extends BackendController
{


    public function __construct()
    {
        parent::__construct();
        $this->data['siteTitle'] = 'Categories';

        $this->middleware(['permission:category'])->only('index');
        $this->middleware(['permission:category_create'])->only('create', 'store');
        $this->middleware(['permission:category_edit'])->only('edit', 'update');
        $this->middleware(['permission:category_delete'])->only('destroy');

    }

    public function index(Request $request)
    {
        return $this->getCategory($request);
    }


    public function create()
    {
        return view('admin.category.create');
    }


    public function store(CategoryRequest $request)
    {
        $category              = new Category;
        $category->name        = $request->name;
        $category->description = $request->description;
        $category->parent_id   = 0;
        $category->depth       = 0;
        $category->left        = 0;
        $category->right       = 0;
        $category->status      = $request->status ? $request->status : Status::INACTIVE;
        $category->save();

        //Store Image Media Libraty Spati
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $category->addMediaFromRequest('image')->toMediaCollection('categories');
        }

        return redirect(route('admin.category.index'))->withSuccess('The data inserted successfully.');
    }


    public function edit($id)
    {
        $this->data['category'] = Category::owner()->findOrFail($id);
        return view('admin.category.edit', $this->data);
    }


    public function update(CategoryRequest $request, $id)
    {
        $category              = Category::owner()->findOrFail($id);
        $category->name        = $request->name;
        $category->description = $request->description;
        $category->parent_id   = 0;
        $category->depth       = 0;
        $category->left        = 0;
        $category->right       = 0;
        $category->save();

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $category->media()->delete($id);
            $category->addMediaFromRequest('image')->toMediaCollection('categories');
        }

        return redirect(route('admin.category.index'))->withSuccess('The data updated successfully.');
    }


    public function destroy($id)
    {
        Category::owner()->findOrFail($id)->delete();
        return redirect(route('admin.category.index'))->withSuccess('The data deleted successfully.');
    }

    private function getCategory($request)
    {
        if (request()->ajax()) {
            $queryArray = [];
            
            if(!auth()->user()->myrole == UserRole::ADMIN ){
                $queryArray['status'] = Status::ACTIVE;
            }

            if ((int) $request->status) {
                $queryArray['status'] = $request->status;
            }
            if ((int) $request->requested) {
                $queryArray['requested'] = $request->requested;
            }

            $categories = Category::where($queryArray)->descending()->get();
            return Datatables::of($categories)
                ->addColumn('action', function ($category) {
                    $button_array           = [];
                    $button_array['edit']   = ['route' => route('admin.category.edit', $category),'permission' => 'category_edit'];
                    $button_array['delete'] = ['route' => route('admin.category.destroy', $category),'permission' => 'category_delete'];
                    
                    return action_button($button_array);
                })
                ->editColumn('status', function ($category) {
                    return $category->statusName;
                })
                ->editColumn('created_by', function ($category) {
                    return optional($category->creator)->name;
                })
                ->rawColumns(['action'])

                ->escapeColumns([])
                ->make(true);

        }
        return view('admin.category.index');
    }
}
