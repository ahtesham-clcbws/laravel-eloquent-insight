<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategoryModel extends Model
{
    use HasFactory;

    protected $table = 'subcategory';
    protected $fillable = ['subcategory_name', 'category_id', 'image'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // public function subjects()
    // {
    //     return $this->belongsToMany(SubjectModel::class, 'subcategory_subject');
    // }

    public function subjects()
    {
        return $this->belongsToMany(SubjectModel::class, 'subcategory_subject', 'subcategory_id', 'subject_id');
    }

    // SubjectModel.php
    public function subcategories()
    {
        return $this->belongsToMany(SubCategoryModel::class, 'subcategory_subject', 'subject_id', 'subcategory_id');
    }
}
