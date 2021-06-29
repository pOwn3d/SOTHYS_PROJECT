<?php

namespace App\Controller;

use App\Entity\Item;
use App\Services\ItemServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController {
    /**
     * @Route("/{_locale}/search", name="app_search")
     */
    public function search(ItemServices $itemServices, Request $request) {
        $term = $request->request->get('term', '');

        $societyId = $this->getUser()->getSocietyID()->getId();

        $items = $itemServices->getItemsByTerm($societyId, $term, $request->getLocale());

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

    /**
     * @Route("/{_locale}/search-promo", name="app_search_promo")
     */
    public function searchPromo(ItemServices $itemServices, Request $request) {
        $term = $request->request->get('term', '');
        $freeRules = $request->request->get('freeRules');

        $societyId = $this->getUser()->getSocietyID()->getId();

//        $items = $itemServices->getItemsByTerm($societyId, $term, $request->getLocale());
        $items = $itemServices->getItemsByFreeRestocking($societyId, $term, $request->getLocale(), $freeRules);

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
