<?php

namespace App\Imports;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Téléchargez l'image à partir de l'URL
        $imageContents = file_get_contents($row['photo']);

        // Générer un nom de fichier unique
        $imageName = uniqid() . '.jpg';

        // Enregistrez l'image sur votre serveur
        Storage::disk('public')->put('products/' . $imageName, $imageContents);

        // Enregistrez le produit dans votre base de données
        return new Product([
            'name' => $row['nom'],
            'description' => $row['description'],
            'price' => $row['prix'],
            'stock' => $row['stock'],
            'photo' => 'products/' . $imageName,
        ]);

    }
}
