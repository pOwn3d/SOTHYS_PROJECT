<?php


namespace App\Services;


use App\Entity\GammeProduct;
use App\Entity\Item;
use App\Entity\Order;
use App\Entity\OrderLine;
use App\Entity\Society;
use App\Entity\User;
use App\Repository\GammeProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

class CsvImporter
{
    use ResetPasswordControllerTrait;

    private ResetPasswordHelperInterface $resetPasswordHelper;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    /**
     * @var MailerInterface
     */
    private MailerInterface $mailer;
    /**
     * @var GammeProductRepository
     */
    private GammeProductRepository $gammeProductRepository;

    public function __construct(EntityManagerInterface $em, MailerInterface $mailer, ResetPasswordHelperInterface $resetPasswordHelper, GammeProductRepository $gammeProductRepository)
    {
        $this->em                     = $em;
        $this->mailer                 = $mailer;
        $this->resetPasswordHelper    = $resetPasswordHelper;
        $this->gammeProductRepository = $gammeProductRepository;
    }

    public function importUser()
    {
        $csv = Reader::createFromPath('../public/csv/user.csv');
        $csv->fetchColumn();
        foreach ($csv as $row) {
            $user    = $this->em->getRepository(User::class)->findOneBy([ 'email' => $row[0] ]);
            $society = $this->em->getRepository(Society::class)->findOneBy([ 'idCustomer' => $row[1] ]);

            $newUser = new User();
            $newUser
                ->setEmail($row[0])
                ->setAccountActivated($row[1])
                ->setIsVerified($row[2])
                ->setPassword('$argon2id$v=19$m=65536,t=4,p=1$bnM5WjlnVFFkZURDNlQuYw$mRc2Rxng96ERn2SxcwyyWuBuL5WF/2tWzyAK5xe2iTE')
                ->setSocietyID($society)
                ->setRoles([ "ROLE_USER" ]);
            $this->em->persist($newUser);
            $this->em->flush();

            $user = $this->em->getRepository(User::class)->findOneBy([ 'email' => $newUser->getEmail() ]);

            $resetToken = $this->resetPasswordHelper->generateResetToken($user);
            $email      = (new TemplatedEmail())
                ->from(new Address('clopez@nexton-group.com', 'Acme Mail Bot'))
                ->to($newUser->getEmail())
                ->subject('Demande de nouveau mot de passe')
                ->htmlTemplate('emails/account/reset.html.twig')
                ->context([
                    'resetToken' => $resetToken,
                ]);

            $this->mailer->send($email);

        }
    }

    public function importOrder()
    {
        $csv = Reader::createFromPath('../public/csv/order.csv');
        $csv->fetchColumn();

        foreach ($csv as $row) {
            if ($row[13] == 0) {
                $row[13] = null;
            }

            $society  = $this->em->getRepository(Society::class)->findOneBy([ 'idCustomer' => $row[2] ]);
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

            $this->em->persist($newOrder);
            $this->em->flush();
        }

        echo $csv->toString();

    }

    public function importSociety()
    {
        $csv = Reader::createFromPath('../public/csv/society.csv');
        $csv->fetchColumn();

        foreach ($csv as $row) {
            $newSociety = new Society();
            $newSociety
                ->setIdCustomer($row[0])
                ->setName($row[1]);

            $this->em->persist($newSociety);
            $this->em->flush();
        }
    }

    public function importGamme()
    {
        $csv = Reader::createFromPath('../public/csv/gamme.csv');
        $csv->fetchColumn();

        foreach ($csv as $row) {
            $gamme = new GammeProduct();
            $gamme
                ->setRefID($row[0])
                ->setLabelFR($row[1])
                ->setLabelEN($row[2]);
            $this->em->persist($gamme);
            $this->em->flush();
        }
    }

    public function importOrderLine()
    {
        $csv = Reader::createFromPath('../public/csv/orderLine.csv');
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

            $this->em->persist($orderLine);
            $this->em->flush();
        }
    }

    public function importItem()
    {
        $csv = Reader::createFromPath('../public/csv/item.csv');
        $csv->fetchColumn();

        foreach ($csv as $row) {

            $product     = new Item();
            $gammeString = 'DIVERS';

            if (is_null($row[8]) || $row[8] == null) {

                $divers  = $this->em->getRepository(GammeProduct::class)->findOneBy([ 'refID' => 'DIVERS' ]);
                $gammeID = $divers;

            } else {

                $gammeID     = $this->em->getRepository(GammeProduct::class)->findOneBy([ 'refID' => $row[8] ]);
                $gammeString = $gammeID->getRefID();

            }

            $product
                ->setItemID($row[0])
                ->setGamme($gammeID)
                ->setLabelFR($row[1])
                ->setLabelEN($row[2])
                ->setCapacityFR($row[3])
                ->setCapacityEN($row[4])
                ->setIdPresentation($row[5])
                ->setSector($row[6])
                ->setUsageString($row[7])
                ->setGammeString($gammeString)
                ->setAmountBulking($row[11])
                ->setCodeEAN($row[12])
                ->setIdAtTheRate($row[13]);

            $this->em->persist($product);

        }

        $this->em->flush();
        return 'je suis ici';
    }
}
