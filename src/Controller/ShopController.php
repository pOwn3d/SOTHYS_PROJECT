<?php

namespace App\Controller;

use App\Form\CsvOrderUploaderType;
use App\Form\OrderType;
use App\Repository\OrderDraftRepository;
use App\Services\Cart\CartItem;
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
    * @Route("/{_locale}/panier", name="app_shop", requirements={
    * "_locale"="%app.locales%"
    * })
    */
    public function index(ShopServices $shopServices, CartItem $cartItem, Request $request, SluggerInterface $slugger): Response
    {
        $society = $this->getUser()->getSocietyID();
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
                            $shopServices->addToCart($society, $row[0], $row[1]);
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
            'cartItem'        => $cartItem->getItemCart($society)['0']['quantity'],
            'form'            => $form->createView(),
            'errors'          => $errors,
        ]);
    }

    /**
     *  @Route("/{_locale}/promo", name="app_promo_shop", requirements={
     * "_locale"="%app.locales%"
     * })
     */
    public function panierPromo(ShopServices $shopServices, CartItem $cartItem, PromoServices $promoServices): Response
    {
        $society = $this->getUser()->getSocietyID();
        $orders = $shopServices->getOrderDraftPromo($society);;
        $promo = $promoServices->getPromoSociety();

        return $this->render('shop/promo.html.twig', [
            'controller_name' => 'ShopController',
            'orders' => $orders,
            'cartItem' => $cartItem->getItemCart($society)['0']['quantity'],
            'promos' => $promo
        ]);
    }

    /**
     * @Route("/order-publish/{promo}", name="app_order_publish")
     */
    public function orderPublish(CartItem $cartItem, Request $request, ShopServices $shopServices): Response
    {
        $society = $this->getUser()->getSocietyID();
        $form = $this->createForm(OrderType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $society = $this->getUser()->getSocietyID();
            $order   = $shopServices->createOrder($society, $form->getData(), $request->get('promo'));
            if ($order != null) {
                $this->addFlash($order['type'], $order['msg']);
                return $this->redirectToRoute('app_shop');
            }
            return $this->redirectToRoute('app_order');
        }


        return $this->render('shop/shop.html.twig', [
            'controller_name' => 'ShopController',
            'cartItem'        => $cartItem->getItemCart($society)['0']['quantity'],
            'form'            => $form->createView(),
            'user' => $this->getUser(),
        ]);
    }


    /**
     * @Route("/order-edit/{id}", name="app_order_edit")
     */
    public function orderEdit(Request $request, OrderServices $orderServices, OrderDraftServices $orderDraftServices, ShopServices $shopServices): Response
    {
        $id = $request->get('id');
        $society = $this->getUser()->getSocietyID();
        $order = $orderServices->editOrderID($id);
        $orderLine = $orderServices->editOrderLineID($id);
        $promo = $orderDraftServices->editOrderDraft($order, $society, $orderLine);
        $shopServices->deleteOrderLine($id);


        if ($promo == true) {
            return $this->redirectToRoute('app_promo_shop');
        }
        return $this->redirectToRoute('app_shop');

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


}
