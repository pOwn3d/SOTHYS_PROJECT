<?php

namespace App\Controller;

use App\Form\OrderType;
use App\Form\CsvOrderUploaderType;
use League\Csv\Reader;
use App\Services\Cart\CartItem;
use App\Services\OrderDraftServices;
use App\Services\OrderServices;
use App\Services\ShopServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
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


        $form = $this->createForm(CsvOrderUploaderType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $file = $form->get('update_csv_order')->getData();

            if ($file) {
                if($file->getClientOriginalExtension() !== 'csv'){
                    $invalidExtention = "You have entered an invalid file, Try a file with '.csv' extension";
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
                    $ErrorFile = "Somthing went wrong while uploading file, try Again !";
                }
                $csv = Reader::createFromPath($this->getParameter('upload_directory') . '/' . $newFilename, 'r');
                if(str_contains($csv->toString(),';')){
                    $csv->setDelimiter(';');
                }
                $records = $csv->getRecords();
                try{
                    foreach($records as $row){
                        $shopServices->cartSociety($society, $row[0], $row[1]);  // $row[0] is product id // $row[1] is product quantity
                    }
                } catch(\Exception $e) {
                    $IdError = "we have no item with this id : " . $row[0];
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
            'invalidExtention'=> $invalidExtention ?? null,
            'ErrorFile'       => $ErrorFile ?? null,
            'IdError'         => $IdError ?? null,
            ]);
    }

    /**
     * @Route("/order-publish", name="app_order_publish")
     */
    public function orderPublish(CartItem $cartItem, Request $request, ShopServices $shopServices): Response
    {
        $society = $this->getUser()->getSocietyID();

        $form = $this->createForm(OrderType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $society = $this->getUser()->getSocietyID();
            $order   = $shopServices->createOrder($society, $form->getData());

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

//    /**
//     * @Route("/order-publish", name="app_order_publish")
//     */
//    public function orderSave(ShopServices $shopServices): Response
//    {
//        $society = $this->getUser()->getSocietyID();
//        $order   = $shopServices->createOrder($society);
//
//        if ($order != null) {
//            $this->addFlash($order['type'], $order['msg']);
//            return $this->redirectToRoute('app_shop');
//        }
//
//        return $this->redirectToRoute('app_order');
//    }

    /**
     * @Route("/order-edit/{id}", name="app_order_edit")
     */
    public function orderEdit(Request $request, OrderServices $orderServices, OrderDraftServices $orderDraftServices, ShopServices $shopServices): Response
    {
        $id        = $request->get('id');
        $society   = $this->getUser()->getSocietyID();
        $order     = $orderServices->editOrderID($id);
        $orderLine = $orderServices->editOrderLineID($id);
        $orderDraftServices->editOrderDraft($order, $society, $orderLine);
        $shopServices->deleteOrderLine($id);

        return $this->redirect('/panier');
    }

    /**
     * @Route("/order-delete-item/{id}", name="app_order_product_delete")
     */
    public function orderDeleteItem(Request $request, ShopServices $shopServices): Response
    {
        // TODO : Checker si la société est bien celle de la personne qui supprime le produit.
        $id = $request->get('id');
        $shopServices->deleteItemOrderDraft($id);
        return $this->redirectToRoute('app_shop');
    }


}
