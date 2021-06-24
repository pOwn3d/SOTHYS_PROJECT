<?php

namespace App\Controller;

use App\Entity\OrderDraft;
use App\Form\CsvOrderUploaderType;
use App\Form\OrderType;
use App\Repository\OrderDraftRepository;
use App\Repository\OrderLineRepository;
use App\Services\Cart\CartItem;
use App\Services\ItemServices;
use App\Services\OrderDraftServices;
use App\Services\OrderServices;
use App\Services\PromoServices;
use App\Services\ShopServices;
use League\Csv\Reader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ShopController extends AbstractController
{
    /**
     * @Route("/{_locale}/panier", name="app_shop", requirements={"_locale"="%app.locales%"})
     */
    public function index(ShopServices $shopServices, CartItem $cartItem, Request $request, SluggerInterface $slugger, ItemServices $itemService): Response
    {
        $society = $this->getUser()->getSocietyID();
       $freeRestockingRules = $shopServices->getFreeRestockingRules($society);
        $errors = [];

        $form = $this->createForm(CsvOrderUploaderType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $file = $form->get('update_csv_order')->getData();

            if ($file) {
                if($file->getClientOriginalExtension() !== 'csv'){
                    $errors[] = "You have entered an invalid file, try a file with '.csv' extension";
                } else {
                    $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    // this is needed to safely include the file name as part of the URL
                    $safeFilename = $slugger->slug($originalFilename);

                    $newFilename = $safeFilename.'-'.uniqid(). '.' . $file->getClientOriginalExtension();

                    try {
                        $file->move(
                            $this->getParameter('upload_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        $errors[] = "Something went wrong while uploading file, try again !";
                    }

                    $csv = Reader::createFromPath($this->getParameter('upload_directory') . '/' . $newFilename, 'r');
                    if(str_contains($csv->toString(),';')){
                        $csv->setDelimiter(';');
                    }
                    $records = $csv->getRecords();
                    try {
                        foreach($records as $row){
                            $item = $itemService->getItemByX3Id($row[0]);
                            $shopServices->addToCart($society, $item->getId(), $row[1], 0);
                        }
                    } catch(\Exception $e) {
                        $errors[] = "We have no item with this id : " . $row[0];
                    }
                }
            }
        }

        $orders  = $shopServices->getOrderDraft($society);
        return $this->render('shop/index.html.twig', [
            'controller_name' => 'ShopController',
            'orders'          => $orders,
            'cartItem'        => $cartItem->getItemCart($society->getId()),
            'form'            => $form->createView(),
            'errors'          => $errors,
            'freeRestockingRules' => $freeRestockingRules
        ]);
    }

    /**
     *  @Route("/{_locale}/promo", name="app_promo_shop", requirements={"_locale"="%app.locales%"})
     */
    public function panierPromo(ShopServices $shopServices, CartItem $cartItem, PromoServices $promoServices): Response
    {
        $society = $this->getUser()->getSocietyID();
        $orders = $shopServices->getOrderDraftPromo($society);
        $promo = $promoServices->getPromoSociety();

        return $this->render('shop/promo.html.twig', [
            'controller_name' => 'ShopController',
            'orders' => $orders,
            'cartItem' => $cartItem->getItemCart($society->getId()),
            'promos' => $promo
        ]);
    }

    /**
     * @Route("/{_locale}/order-publish/{promo}", name="app_order_publish")
     */
    public function orderPublish(CartItem $cartItem, Request $request, ShopServices $shopServices): Response
    {
        $society = $this->getUser()->getSocietyID();
        $form = $this->createForm(OrderType::class, null, [
            'societyId' => $society->getId(),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $society = $this->getUser()->getSocietyID();

            $formData = $form->getData();
            $formData->setIdStatut(2);
            if(!empty($form->getExtraData()) && array_keys($form->getExtraData())[0] === 'saveDraft') {
                $formData->setIdStatut(1);
            }
            $order   = $shopServices->createOrder($this->getUser(), $society, $formData, $request->get('promo'));
            if ($order != null) {
                $this->addFlash($order['type'], $order['msg']);
                return $this->redirectToRoute('app_shop');
            }
            return $this->redirectToRoute('app_order');
        }

        $cartItem = $cartItem->getItemCart($society->getId());
        if ($cartItem == 0){
            $this->addFlash('error', 'Panier vide');
            return $this->redirectToRoute('app_shop');
        }

        return $this->render('shop/publish.html.twig', [
            'controller_name' => 'ShopController',
            'cartItem'        => $cartItem,
            'form'            => $form->createView(),
            'user' => $this->getUser(),
        ]);
    }

    /**
     * @Route("/{_locale}/order-edit/{id}", name="app_order_edit")
     */
    public function orderEdit(Request $request, OrderServices $orderServices, CartItem $cartItem): Response
    {
        $id = $request->get('id');
        $order = $orderServices->getOrderByID($id);
        $orderLines = $orderServices->getOrderLinesByID($id);

        $cartItemCount = 0;
        if($request->get('token') !== 'token') {
            $cartItemCount = $cartItem->getItemCart($this->getUser()->getSocietyID()->getId());
        }

        if($request->get('token') !== 'token' && ($order->getIdStatut() !== 1 || in_array('ROLE_ADMIN', $this->getUser()->getRoles()))) {
            return $this->redirectToRoute('app_order');
        }

        // TODO : handle promo edition ?
        // if ($promo == true) {
        //     return $this->redirectToRoute('app_promo_shop');
        // }

        return $this->render('shop/edit.html.twig', [
            'controller_name' => 'ShopController',
            'order'           => $order,
            'orders'          => $orderLines,
            'cartItem'        => $cartItemCount,
            'form'            => null,
            'errors'          => [],
        ]);
    }

    /**
     * @Route("/{_locale}/order-publish-edit/{id}", name="app_order_edit_publish")
     */
    public function orderEditPublish(Request $request, ShopServices $shopServices, OrderServices $orderServices): Response
    {

        $id = $request->get('id');
        $order = $orderServices->getOrderByID($id);

        $society = $this->getUser()->getSocietyID();
        $form = $this->createForm(OrderType::class, null, [
            'societyId' => $society->getId(),
        ]);
        $form->handleRequest($request);

        if(!$form->isSubmitted()) {
            $form->setData($order);
        }

        if ($form->isSubmitted() && $form->isValid()) {

            $formData = $form->getData();
            $formData->setIdStatut(2);
            if(!empty($form->getExtraData()) && array_keys($form->getExtraData())[0] === 'saveDraft') {
                $formData->setIdStatut(1);
            }

            $order = $shopServices->updateOrder($order, $form->getData());
            if ($order != null) {
                return $this->redirectToRoute('app_order');
            }
            return $this->redirectToRoute('app_order_edit', [
                'id' => $id,
            ]);
        }

        return $this->render('shop/publish.html.twig', [
            'controller_name' => 'ShopController',
            'form'            => $form->createView(),
            'user' => $this->getUser(),
            'order' => $order,
        ]);
    }

    /**
     * @Route("/order-delete-item/{id}", name="app_order_product_delete")
     */
    public function orderDeleteItem(Request $request, ShopServices $shopServices, OrderDraftRepository $orderDraftRepository): Response
    {
        // TODO : Checker si la société est bien celle de la personne qui supprime le produit.
        $id = $request->get('id');
        $order = $orderDraftRepository->find($id);
        $shopServices->deleteItemOrderDraft($id);

        if ($order->getPromo() == true) {
            return $this->redirectToRoute('app_promo_shop');
        }

        return $this->redirectToRoute('app_shop');
    }

/**
     * @Route("/add-to-order/{orderId}/{orderLineId}/{qty}", name="app_order_edit_quantity_item")
     */
    public function orderEditQuantityItem(Request $request, ShopServices $shopServices): Response
    {
        $orderId = $request->get('orderId');
        $orderLineId = $request->get('orderLineId');
        $quantity = $request->get('qty');

        $shopServices->updateQuantityOrderLineById($orderLineId, $quantity);

        return $this->redirectToRoute('app_order_edit', [
            'id' => $orderId,
        ]);
    }

    /**
     * @Route("/order-delete-item/order/{orderId}/item/{orderLineId}", name="app_order_product_edit_delete")
     */
    public function orderEditDeleteItem(Request $request, ShopServices $shopServices): Response
    {
        $orderId = $request->get('orderId');
        $orderLineId = $request->get('orderLineId');

        $shopServices->deleteOrderLineById($orderLineId);

        return $this->redirectToRoute('app_order_edit', [
            'id' => $orderId,
        ]);
    }

    /**
     * @Route("/{_locale}/empty-cart", name="app_empty_cart")
     */
    public function emptyCart(ShopServices $shopServices)
    {

        $societyId = $this->getUser()->getSocietyID()->getId();

        $shopServices->emptyCart($societyId);
        return $this->redirectToRoute("app_shop");
    }
}
