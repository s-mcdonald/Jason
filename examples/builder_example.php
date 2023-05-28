<?php

declare(strict_types=1);

require_once '../vendor/autoload.php';

use SamMcDonald\Jason\Json;

//{
//    "id": 11,
//    "title": "Perfume Oil",
//    "rating": 4.26,
//    "stock": 65,
//    "thumbnail": {
//    "url": "https://i.dummyjson.com/data/products/11/thumbnail.jpg",
//          "title": "thumbnail.jpg"
//    },
//    "images": [
//          "https://i.dummyjson.com/data/products/11/1.jpg",
//          "https://i.dummyjson.com/data/products/11/2.jpg"
//     ]
//}

echo Json::createJsonBuilder()
        ->addNumericProperty('id', 11)
        ->addStringProperty('title', "Perfume Oil")
        ->addNumericProperty('rating', 4.26)
        ->addNumericProperty('stock', 65)
        ->addObjectProperty(
            'thumbnail',
            Json::createJsonBuilder()
                ->addStringProperty("url", "https://i.dummyjson.com/data/products/11/thumbnail.jpg")
                ->addStringProperty("title", "thumbnail.jpg")
        )
        ->addArrayProperty("images", [
            "https://i.dummyjson.com/data/products/11/1.jpg",
            "https://i.dummyjson.com/data/products/11/2.jpg"
        ])
;

echo "\n";
echo "\n";
