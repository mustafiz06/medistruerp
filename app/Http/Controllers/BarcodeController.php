<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Picqer\Barcode\BarcodeGeneratorSVG;

class BarcodeController extends Controller
{
    public function generate(Product $product)
    {
        $generator = new BarcodeGeneratorSVG();
        $barcodeSVG = $generator->getBarcode(
            $product->sku,
            $generator::TYPE_CODE_128,
            2, 
            45 
        );
        $formattedSku = implode(' ', str_split($product->sku));

        return view('productSetting.productBarcodePrint', compact('product','barcodeSVG','formattedSku'));
    }
}
