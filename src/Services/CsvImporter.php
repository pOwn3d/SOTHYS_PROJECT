<?php


namespace App\Services;


use App\Entity\GammeProduct;
use App\Entity\Item;
use App\Entity\ItemPrice;
use App\Entity\ItemQuantity;
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

        // See here: https://www.doctrine-project.org/projects/doctrine-orm/en/2.8/reference/batch-processing.html
        $this->em->getConnection()->getConfiguration()->setSQLLogger(null);
    }

    public function importUser(bool $shouldSendMail = true)
    {
        $csv = Reader::createFromPath('../public/csv/user.csv');
        $csv->fetchColumn();

        foreach ($csv as $row) {
            $user    = $this->em->getRepository(User::class)->findOneBy([ 'email' => $row[0] ]);
            $society = $this->em->getRepository(Society::class)->findOneBy([ 'idCustomer' => $row[1] ]);

            $newUser = new User();
            $newUser
                ->setEmail($row[0])
                ->setAccountActivated($row[2])
                ->setIsVerified($row[3])
                ->setPassword($row[4])
                ->setSocietyID($society)
                ->setRoles([ $row[5] ]);
            $this->em->persist($newUser);
            $this->em->flush();

            $user = $this->em->getRepository(User::class)->findOneBy([ 'email' => $newUser->getEmail() ]);

            if (!$shouldSendMail) {
                continue;
            }

            $resetToken = $this->resetPasswordHelper->generateResetToken($user);
            $email      = (new TemplatedEmail())
                ->from(new Address('service.client@sothys.net', 'Service Client Sothys')) # TODO: translate me
                ->to($newUser->getEmail())
                ->subject('Demande de nouveau mot de passe') # TODO: translate me
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

            if ($row[0] != null) {
                $society = $this->em->getRepository(Society::class)->findOneBy([ 'idCustomer' => $row[2] ]);
//            $idOrder = $this->em->getRepository(Order)
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
        }

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

        $items = $this->em->getRepository(Item::class)->findAll();

        foreach ($csv as $row) {

            $orderLine = new OrderLine();
            if ($row[0] != "" && $row[1] != "") {
                $item = null;

                $foundItems = array_filter($items, function($item) use ($row) {
                    return $item->getItemID() == $row[2];
                });

                if(count($foundItems)) {
                    $item = array_shift($foundItems);
                }

                if($item == null) {
                    continue;
                }

                $orderLine
                    ->setIdOrder($row[0])
                    ->setIdOrderLine($row[1])
                    ->setQuantity($row[3])
                    ->setItemID($item)
                    ->setPrice($row[4])
                    ->setPriceUnit($row[5])
                    ->setIdOrderX3($row[7])
                    ->setRemainingQtyOrder($row[9]);

                $this->em->persist($orderLine);
            }
        }
        $this->em->flush();
    }

    public function importItem()
    {
        $csv = Reader::createFromPath('../public/csv/item.csv');
        $csv->fetchColumn();

        $gammes = $this->em->getRepository(GammeProduct::class)->findAll();
        $divers = $this->em->getRepository(GammeProduct::class)->findOneBy([ 'refID' => 'DIVERS' ]);


        foreach ($csv as $row) {

            $product = new Item();
            $gamme = null;
            if ($row[8] != null) {
                $gammeString = $row[0];
                $foundGammes = array_filter($gammes, function($gamme) use ($row) {
                    return $gamme->getRefID() == $row[8];
                });

                if(count($foundGammes)) {
                    $gamme = array_shift($foundGammes);
                }
            } else {
                $gammeString = 'DIVERS';
                $gamme       = $divers;
            }

            $product
                ->setItemID($row[0])
                ->setGamme($gamme)
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
    }

    public function importItemPrice()
    {
        $csv = Reader::createFromPath('../public/csv/price.csv');
        $csv->fetchColumn();

        $companies = $this->em->getRepository(Society::class)->findAll();
        $items = $this->em->getRepository(Item::class)->findAll();

        foreach ($csv as $row) {

            $price   = new ItemPrice();

            $item = null;

            $foundItems = array_filter($items, function($item) use ($row) {
                return $item->getItemID() == $row[1];
            });

            if(count($foundItems)) {
                $item = array_shift($foundItems);
            }

            $company = null;

            $foundCompanies = array_filter($companies, function($company) use ($row) {
                return $company->getIdCustomer() == $row[0];
            });

            if(count($foundCompanies)) {
                $company = array_shift($foundCompanies);
            }

            if ($item != null) {
                $price
                    ->setIdSociety($company)
                    ->setIdItem($item)
                    ->setPrice($row[2])
                    ->setDateStartValidity(new \DateTime($row[3]))
                    ->setDateEndValidity(new \DateTime($row[4]))
                    ->setPricePublic($row[5])
                    ->setPriceAesthetic($row[6]);
                $this->em->persist($price);
            }
        }
        $this->em->flush();
    }

    public function importItemQuantity()
    {
        $csv = Reader::createFromPath('../public/csv/itemQuantity.csv');
        $csv->fetchColumn();

        $companies = $this->em->getRepository(Society::class)->findAll();
        $items = $this->em->getRepository(Item::class)->findAll();

        foreach ($csv as $row) {
            $itemQuantity = new ItemQuantity();

            $item = null;

            $foundItems = array_filter($items, function($item) use ($row) {
                return $item->getItemID() == $row[1];
            });

            if(count($foundItems)) {
                $item = array_shift($foundItems);
            }

            $company = null;

            $foundCompanies = array_filter($companies, function($company) use ($row) {
                return $company->getIdCustomer() == $row[0];
            });

            if(count($foundCompanies)) {
                $company = array_shift($foundCompanies);
            }

            if ($item != null) {
                $itemQuantity
                    ->setIdSociety($company)
                    ->setIdItem($item)
                    ->setQuantity($row[2]);
                $this->em->persist($itemQuantity);
            }
        }
        $this->em->flush();
    }
}
