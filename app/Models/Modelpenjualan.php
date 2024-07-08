<?php

namespace App\Models;

use CodeIgniter\Model;

class Modelpenjualan extends Model
{
    protected $table      = 'penjualan';
    protected $primaryKey = 'jual_faktur';

    protected $allowedFields = [];
}
