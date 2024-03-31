<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = ['comment','user_id','feedback_id','parent_id'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function feedback()
    {
        return $this->belongsTo(Feedback::class);
    }
    // public function replies()
    // {
    //     return $this->hasMany(Comment::class,'parent_id');
    // }
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->with('user:id,name', 'replies');
    }
}
