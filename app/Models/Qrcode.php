<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Qrcode extends Model
{
    public $table = 'qrcodes';

    public $fillable = [
        'user_id',
        'qr_code',
        'website',
        'product_name',
        'product_url',
        'company_name',
        'callback_url',
        'qrcode_path',
        'amount',
        'status'
    ];

    protected $casts = [
        'qr_code' => 'string',
        'website' => 'string',
        'product_name' => 'string',
        'product_url' => 'string',
        'company_name' => 'string',
        'callback_url' => 'string',
        'qrcode_path' => 'string',
        'amount' => 'float',
        'status' => 'boolean'
    ];

    public static array $rules = [
        'user_id' => 'required',
        'qr_code' => 'required|string|max:255',
        'website' => 'nullable|string|max:255',
        'product_name' => 'required|string|max:255',
        'product_url' => 'nullable|string|max:255',
        'company_name' => 'required|string|max:255',
        'callback_url' => 'nullable|string|max:255',
        'qrcode_path' => 'nullable|string|max:255',
        'amount' => 'required|numeric',
        'status' => 'required|boolean',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
