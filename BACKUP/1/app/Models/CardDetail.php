<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'picha_ya_kitambulisho',
        'jina_kamili',
        'barua_pepe',
        'namba_ya_simu',
        'jinsia',
        'hali_ndoa',
        'nimeoa_or_olewa',
        'tarehe_ya_kuzaliwa',
        'pf_no',
        'cheo',
        'wilaya_ya_kipolisi', // Hii sasa itahifadhi jina, sio ID
        'mkoa_wa_kipolisi',   // Hii sasa itahifadhi jina, sio ID
        'kituo_cha_kazi',
        'check_namba',
        'mkataba_wa_ajira',
        'eneo_unaloishi',
        'picha_ya_sahihi_yako',
        'status',
        'trackingPIN',
        'comment',
        'registered_date',
        'api_id'
    ];

    // Ondoa functions za region() na district() hapa,
    // kwa sababu sasa wilaya_ya_kipolisi na mkoa_wa_kipolisi zitakuwa zimehifadhi majina direct.

    // Add casts for dates if not already present
    protected $casts = [
        'tarehe_ya_kuzaliwa' => 'date',
         'registered_date' => 'date',
    ];

     public function member()
    {
        return $this->belongsTo(Member::class, 'check_namba', 'checkNo');
    }
}