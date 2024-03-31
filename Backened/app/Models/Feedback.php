<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;
    protected $table = 'feedback';
    protected $fillable = ['title','user_id','category_id','description'];
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // public function comments()
    // {
    //     return $this->hasMany(Comment::class)->whereNull('parent_id');
    // }
    public function comments()
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id')->with('user:id,name', 'replies');
    }
}
