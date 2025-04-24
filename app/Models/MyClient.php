<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyClient extends Model
{
    use HasFactory;

    protected $table = 'my_client';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'slug',
        'is_project',
        'self_capture',
        'client_prefix',
        'client_logo',
        'address',
        'phone_number',
        'city',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $dates = ['deleted_at'];

    public function saveToRedis()
    {
        $clientData = $this->toArray();
        $redisKey = $this->slug;
        \Illuminate\Support\Facades\Redis::set($redisKey, json_encode($clientData));
    }

    public function deleteFromRedis()
    {
        $redisKey = $this->slug;
        \Illuminate\Support\Facades\Redis::del($redisKey);
    }
}
