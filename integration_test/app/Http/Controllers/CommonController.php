<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\State;
use App\Models\SubCategoryModel;

class CommonController extends Controller
{

    public function categoryAll()
    {
        $categories = Category::all();
        return  $categories;
    }

    public function subcategory(Category $category)
    {
        $subcategories = $category->subcategories;
        return $subcategories;
    }

    public function subject(SubCategoryModel $subcategories)
    {
        $subjects = $subcategories->subjects;
        return $subjects;
    }

    public function districts(State $state)
    {
        $districts = $state->districts;
        return $districts;
    }

    public function subcategoryall()
    {
        $subcategories = SubCategoryModel::all();
        return $subcategories;
    }
    
}
