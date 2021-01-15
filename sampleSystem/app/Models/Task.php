<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    // ホワイトリストです。$fillableに指定したカラムのみ、create()やfill()、update()で値が代入されます。
    protected $fillable = [
        'title',
        'content',
        'person_in_charge',
    ];
}
