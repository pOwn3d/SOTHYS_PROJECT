<?php

namespace App\Controller;

use App\Entity\GammeProduct;
use App\Entity\Order;
use App\Entity\OrderLine;
use App\Entity\Society;
use App\Entity\User;
use App\Repository\SocietyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use League\Csv\Reader;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

class CsvImporterController extends AbstractController
{

    use ResetPasswordControllerTrait;

    private ResetPasswordHelperInterface $resetPasswordHelper;

    public function __construct(ResetPasswordHelperInterface $resetPasswordHelper)
    {
        $this->resetPasswordHelper = $resetPasswordHelper;
    }

    /**
     * @Route("/import-csv-user", name="import_csv_user")
     * @param EntityManagerInterface $em
     * @param MailerInterface        $mailer
     *
     * @return Response
     * @throws ResetPasswordExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function index(EntityManagerInterface $em, MailerInterface $mailer, SocietyRepository $societyRepository): Response
    {
        $csv = Reader::createFromPath('../public/csv/customer.csv');
        $csv->fetchColumn();
        foreach ($csv as $row) {
            $user = $this->getDoctrine()->getRepository(User::class)->findOneBy([ 'email' => $row[0] ]);

            $society = $this->getDoctrine()->getRepository(Society::class)->findOneBy([ 'idCustomer' => $row[1] ]);

            $newUser = new User();
            $newUser
                ->setEmail($row[0])
                ->setAccountActivated($row[1])
                ->setIsVerified($row[2])
                ->setPassword('TOTOaChanger')
                ->setSocietyID($society)
                ->setRoles([ "ROLE_USER" ]);
            $em->persist($newUser);
            $em->flush();

            $user = $this->getDoctrine()->getRepository(User::class)->findOneBy([ 'email' => $newUser->getEmail() ]);

            $resetToken = $this->resetPasswordHelper->generateResetToken($user);
            $email      = (new TemplatedEmail())
                ->from(new Address('clopez@nexton-group.com', 'Acme Mail Bot'))
                ->to($newUser->getEmail())
                ->subject('Demande de nouveau mot de passe')
                ->htmlTemplate('emails/account/reset.html.twig')
                ->context([
                    'resetToken' => $resetToken,
                ]);

            $mailer->send($email);
            $this->setTokenObjectInSession($resetToken);

        }

        dd();
    }

    /**
     * @Route("/import-order", name="import_csv_order")
     * @param EntityManagerInterface $em
     *
     * @return Response
     * @throws Exception
     */
    public function importBooking(EntityManagerInterface $em): Response
    {
        $csv = Reader::createFromPath('../public/csv/order.csv');
        $csv->fetchColumn();


        foreach ($csv as $row) {
            if ($row[13] == 0) {
                $row[13] = null;
            }

            $society = $this->getDoctrine()->getRepository(Society::class)->findOneBy([ 'idCustomer' => $row[2] ]);


            $newOrder = new Order();
            $newOrder
                ->setIdOrder($row[0])
                ->setIdOrderX3($row[1])
                ->setSocietyID($society)
//                ->setIdLogin()
                ->setDateOrder(new \DateTime($row[4]))
                ->setDateDelivery(new \DateTime($row[5]))
//                ->setIdAdress()
                ->setIdStatut($row[7])
                ->setIdDownStatut($row[8])
                ->setReference($row[9])
//                ->setIdTransport()
//                ->setIdIntercom()
//                ->setIdCondition()
                ->setDateLastDelivery(new \DateTime($row[13]));


            $em->persist($newOrder);
            $em->flush();
        }

        dd();
    }

    /**
     * @Route("/import-society", name="import_csv_society")
     * @param EntityManagerInterface $em
     *
     * @return Response
     * @throws Exception
     */
    public function importSociety(EntityManagerInterface $em): Response
    {
        $csv = Reader::createFromPath('../public/csv/society.csv');
        $csv->fetchColumn();

        foreach ($csv as $row) {
            $newSociety = new Society();
            $newSociety
                ->setIdCustomer($row[0])
                ->setName($row[1]);

            $em->persist($newSociety);
            $em->flush();
        }
        dd();
    }

    /**
     * @Route("/import-gamme", name="import_csv_gamme")
     * @param EntityManagerInterface $em
     *
     * @return Response
     * @throws Exception
     */
    public function importGamme(EntityManagerInterface $em): Response
    {
        $csv = Reader::createFromPath('../public/csv/gamme.csv');
        $csv->fetchColumn();

        foreach ($csv as $row) {
            $gamme = new GammeProduct();
            $gamme
                ->setIdCoedi($row[0])
                ->setName($row[1])
                ->setName2($row[2]);

            $em->persist($gamme);
            $em->flush();
        }
        dd();
    }

    /**
     * @Route("/import-order-item", name="import_csv_order_item")
     * @param EntityManagerInterface $em
     *
     * @return Response
     * @throws Exception
     */
    public function importOrderItem(EntityManagerInterface $em): Response
    {
        $csv = Reader::createFromPath('../public/csv/orderItem.csv');
        $csv->fetchColumn();

        foreach ($csv as $row) {

            if ($row[0] == "") {
                $row[0] = null;
            }
            if ($row[1] == "") {
                $row[1] = null;
            }

            $orderLine = new OrderLine();
            $orderLine
                ->setIdOrder($row[0])
                ->setIdOrderLine($row[1])

                ->setQuantity($row[3])
                ->setPrice($row[4])
                ->setPriceUnit($row[5])
                ->setIdOrderX3($row[7])
                ->setRemainingQtyOrder($row[9]);

            $em->persist($orderLine);
            $em->flush();
        }
        dd();
    }
}
