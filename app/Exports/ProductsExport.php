<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Product::all('name', 'description', 'price', 'stock', 'photo');
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Nom',
            'Description',
            'Prix',
            'Stock',
            'Image_URL',
        ];
    }
}
