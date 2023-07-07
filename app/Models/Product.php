<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'tblproductdata'; // Set the table name

    protected $primaryKey = 'intProductDataId'; // Set the primary key field

    public $timestamps = false; // Disable the default timestamps behavior

    // Define the column name for the timestamp
    const CREATED_AT = 'stmTimestamp';
    const UPDATED_AT = 'stmTimestamp';


    protected $fillable = [
        'strProductName',
        'strProductDesc',
        'strProductCode',
        'stock',
        'price',
        'dtmAdded',
        'dtmDiscontinued',
    ];

    protected $dates = [
        'dtmAdded',
        'dtmDiscontinued',
    ];

}
