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
//        [
//            'Nom',
//            'Description',
//            'Categorie',
//            'Prix',
//            'Stock',
//            'Image_URL',
//        ] avec le nom de la catégorie et non son id
        return Product::query()
            ->with('category')
            ->get()
            ->map(function ($product) {
                return [
                    'name' => $product->name,
                    'description' => $product->description,
                    'category' => $product->category->name,
                    'price' => $product->price,
                    'stock' => $product->stock,
                    'photo' => $product->photo,
                ];
            });

    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Nom',
            'Description',
            'Categorie',
            'Prix',
            'Stock',
            'Image_URL',
        ];
    }
}
