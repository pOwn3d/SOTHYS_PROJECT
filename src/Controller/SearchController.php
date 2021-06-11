<?php

namespace App\Controller;

use App\Entity\Item;
use App\Services\ItemServices;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SearchController {
    /**
     * @Route("/search", name="app_search")
     */
    public function search(ItemServices $itemServices, Request $request) {
        $term = $request->request->get('term', '');

        $items = $itemServices->getItemsByTerm($term, $request->getLocale());

        if(empty($items)) {
            return new JsonResponse([]);
        }

        return new JsonResponse(array_map(function(Item $item) use ($request) {
            return [
                'id' => $item->getId(),
                'label' => $item->getLabel($request->getLocale()),
                'gamme' => $item->getGamme()->getLabel($request->getLocale()),
                'reference' => $item->getItemID(),
            ];
        }, $items));
    }
}
