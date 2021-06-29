<?php


namespace App\Services;

use App\Entity\Address as EntityAddress;
use App\Entity\CustomerIncoterm;
use App\Entity\FreeRestockingRules;
use App\Entity\GammeProduct;
use App\Entity\GenericName;
use App\Entity\Incoterm;
use App\Entity\Item;
use App\Entity\ItemPrice;
use App\Entity\ItemQuantity;
use App\Entity\Order;
use App\Entity\OrderLine;
use App\Entity\PaymentMethod;
use App\Entity\Society;
use App\Entity\TransportMode;
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
        $this->em = $em;
        $this->mailer = $mailer;
        $this->resetPasswordHelper = $resetPasswordHelper;
        $this->gammeProductRepository = $gammeProductRepository;

        // See here: https://www.doctrine-project.org/projects/doctrine-orm/en/2.8/reference/batch-processing.html
        $this->em->getConnection()->getConfiguration()->setSQLLogger(null);
    }

    public function getExistingIds(string $className) : array
    {
        $items = $this->em->getRepository($className)->findAll();

        return array_map(function($item) {
            return $item->getIdX3();
        }, $items);
    }

    public function importPaymentMethod()
    {
        $csv = Reader::createFromPath('../public/csv/ConditionPaiement.csv');
        $csv->setDelimiter(';');
        $csv->fetchColumn();

        $existingIds = $this->getExistingIds(PaymentMethod::class);

        foreach ($csv as $row) {

            if(in_array($row[0], $existingIds)) {
                continue;
            }

            $paymentMethod = new PaymentMethod();
            $paymentMethod
                ->setIdX3(\utf8_encode($row[0]))
                ->setLabelFR(\utf8_encode($row[1]))
                ->setLabelEN(\utf8_encode($row[2]));

            $this->em->persist($paymentMethod);
        }

        $this->em->flush();
    }

    public function importUser(bool $shouldSendMail = true)
    {
        $csv = Reader::createFromPath('../public/csv/user.csv');
        $csv->fetchColumn();

        foreach ($csv as $row) {
            $user = $this->em->getRepository(User::class)->findOneBy(['email' => $row[0]]);
            $society = $this->em->getRepository(Society::class)->findOneBy(['idCustomer' => $row[1]]);

            $newUser = new User();
            $newUser
                ->setEmail($row[0])
                ->setAccountActivated($row[2])
                ->setIsVerified($row[3])
                ->setPassword($row[4])
                ->setSocietyID($society)
                ->setRoles([$row[5]]);
            $this->em->persist($newUser);
            $this->em->flush();

            $user = $this->em->getRepository(User::class)->findOneBy(['email' => $newUser->getEmail()]);

            if (!$shouldSendMail) {
                continue;
            }

            $resetToken = $this->resetPasswordHelper->generateResetToken($user);
            $email = (new TemplatedEmail())
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

        $existingIds = $this->getExistingIds(Order::class);

        foreach ($csv as $row) {
            if ($row[13] == 0) {
                $row[13] = null;
            }

            if ($row[0] != null) {
                $society = $this->em->getRepository(Society::class)->findOneBy(['idCustomer' => $row[2]]);
                $paymentMethod = $this->em->getRepository(PaymentMethod::class)->findOneBy(['idX3' => $row[12]]);
                $address = $this->em->getRepository(EntityAddress::class)->findOneBy(['label' => $row[6]]);

                $order = new Order();
                if(in_array($row[1], $existingIds)) {
                    $order = $this->em->getRepository(Order::class)->findOneBy([
                        'idOrderX3' => $row[1],
                    ]);
                }

                $order
                    ->setIdOrder($row[0])
                    ->setIdOrderX3($row[1])
                    ->setSocietyID($society)
                    ->setDateOrder(new \DateTime($row[4]))
                    ->setDateDelivery(new \DateTime($row[5]))
                    ->setIdStatut($row[7])
                    ->setIdDownStatut($row[8])
                    ->setReference($row[9])
                    ->setAddress($address)
//                ->setTransportMode()
                    ->setPaymentMethod($paymentMethod)
                    ->setDateLastDelivery(new \DateTime($row[13]));

                $this->em->persist($order);
            }
        }
        $this->em->flush();
    }

    public function importSociety()
    {
        $csv = Reader::createFromPath('../public/csv/Client.csv');
        $csv->setDelimiter(';');
        $csv->fetchColumn();

        $paymentMethods = $this->em->getRepository(PaymentMethod::class)->findAll();

        foreach ($csv as $row) {
            $paymentMethod = null;

            $foundPaymentMethods = array_filter($paymentMethods, function ($paymentMethod) use ($row) {
                return $paymentMethod->getIdX3() == $row[15];
            });

            if (count($foundPaymentMethods)) {
                $paymentMethod = array_shift($foundPaymentMethods);
            }

            $newSociety = new Society();
            $newSociety
                ->setIdCustomer($row[0])
                ->setName($row[5])
                ->setPaymentMethod($paymentMethod);

            $this->em->persist($newSociety);
        }
        $this->em->flush();
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

                $foundItems = array_filter($items, function ($item) use ($row) {
                    return $item->getItemID() == $row[2];
                });

                if (count($foundItems)) {
                    $item = array_shift($foundItems);
                }

                if ($item == null) {
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
        $divers = $this->em->getRepository(GammeProduct::class)->findOneBy(['refID' => 'DIVERS']);
        $genericNames = $this->em->getRepository(GenericName::class)->findAll();

        foreach ($csv as $row) {

            $product = new Item();
            $gamme = null;
            if ($row[8] != null) {
                $gammeString = $row[0];
                $foundGammes = array_filter($gammes, function ($gamme) use ($row) {
                    return $gamme->getRefID() == $row[8];
                });

                if (count($foundGammes)) {
                    $gamme = array_shift($foundGammes);
                }
            } else {
                $gammeString = 'DIVERS';
                $gamme = $divers;
            }

            $genericName = null;
            if ($row[10] != null) {
                $foundGenericNames = array_filter($genericNames, function ($genericName) use ($row) {
                    return $genericName->getItemId() == $row[10];
                });

                if (count($foundGenericNames)) {
                    $genericName = array_shift($foundGenericNames);
                }
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
                ->setIdAtTheRate($row[13])
                ->setGenericName($genericName);
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

        $i = 0;
        foreach ($csv as $row) {

            $price = new ItemPrice();

            $item = null;

            $foundItems = array_filter($items, function ($item) use ($row) {
                return $item->getItemID() == $row[1];
            });

            if (count($foundItems)) {
                $item = array_shift($foundItems);
            }

            $company = null;

            $foundCompanies = array_filter($companies, function ($company) use ($row) {
                return $company->getIdCustomer() == $row[0];
            });

            if (count($foundCompanies)) {
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
            $i++;

            if ($i % 4000 == 0) {
                $this->em->flush();
                $this->em->clear(ItemPrice::class);
            }
        }

        $this->em->flush();
    }

    public function importItemQuantity()
    {
        $csv = Reader::createFromPath('../public/csv/QuantiteGroupage.csv');
        $csv->setDelimiter(';');
        $csv->fetchColumn();

        $companies = $this->em->getRepository(Society::class)->findAll();
        $items = $this->em->getRepository(Item::class)->findAll();

        $i = 0;
        foreach ($csv as $row) {
            $itemQuantity = new ItemQuantity();

            $item = null;

            $foundItems = array_filter($items, function ($item) use ($row) {
                return $item->getItemID() == $row[1];
            });

            if (count($foundItems)) {
                $item = array_shift($foundItems);
            }

            $company = null;

            $foundCompanies = array_filter($companies, function ($company) use ($row) {
                return $company->getIdCustomer() == $row[0];
            });

            if (count($foundCompanies)) {
                $company = array_shift($foundCompanies);
            }

            if ($item != null) {
                $itemQuantity
                    ->setIdSociety($company)
                    ->setIdItem($item)
                    ->setQuantity($row[2]);
                $this->em->persist($itemQuantity);
            }

            $i++;

            if ($i % 4000 == 0) {
                $this->em->flush();
                $this->em->clear(ItemQuantity::class);
            }
        }

        $this->em->flush();
    }


    public function importModeTransport()
    {
        $csv = Reader::createFromPath('../public/csv/modeTransport.csv');
        $csv->fetchColumn();

        foreach ($csv as $row) {
            $gamme = new TransportMode();
            $gamme
                ->setIdTransport($row[0])
                ->setNameFR($row[1])
                ->setNameEN($row[2]);
            $this->em->persist($gamme);
            $this->em->flush();
        }
    }

    public function importIncoterm()
    {
        $csv = Reader::createFromPath('../public/csv/Incoterm.csv');
        $csv->setDelimiter(';');
        $csv->fetchColumn();

        foreach ($csv as $row) {
            $gamme = new Incoterm();
            $gamme
                ->setReference($row[0]);
            $this->em->persist($gamme);
            $this->em->flush();
        }
    }

    public function customerImportIncoterm()
    {
        $csv = Reader::createFromPath('../public/csv/ClientIncoterm.csv');
        $csv->setDelimiter(';');
        $csv->fetchColumn();

        foreach ($csv as $row) {

            $user = $this->em->getRepository(Society::class)->findOneBy(['idCustomer' => $row[1]]);
            $incoterm = $this->em->getRepository(Incoterm::class)->findOneBy(['reference' => $row[3]]);
            $modeTransport = $this->em->getRepository(TransportMode::class)->findOneBy(['idTransport' => $row[2]]);

            $gamme = new CustomerIncoterm();
            $gamme
                ->setSocietyCustomerIncoterm($user)
                ->setModeTransport($modeTransport)
                ->setReference($incoterm)
                ->setCity($row[4]);
            $this->em->persist($gamme);
            $this->em->flush();
        }
    }

    public function importSocietyAddress()
    {
        $csv = Reader::createFromPath('../public/csv/Adresse.csv');
        $csv->setDelimiter(';');
        $csv->fetchColumn();

        foreach ($csv as $row) {

            $society = $this->em->getRepository(Society::class)->findOneBy(['idCustomer' => $row[0]]);
            $address = new EntityAddress();
            $address
                ->setSociety($society)
                ->setLabel($row[1])
                ->setAddress1(\utf8_encode($row[3]))
                ->setAddress2(\utf8_encode($row[4]))
                ->setAddress3(\utf8_encode($row[5]))
                ->setPostalCode($row[6])
                ->setCity($row[7])
                ->setRegion($row[8])
                ->setCountry(\utf8_encode($row[9]));

            $this->em->persist($address);
            $this->em->flush();
        }
    }

    public function importGenericName()
    {
        $csv = Reader::createFromPath('../public/csv/NomGenerique.csv');
        $csv->setDelimiter(';');
        $csv->fetchColumn();

        foreach ($csv as $row) {
            $genericName = new GenericName();
            $genericName
                ->setItemId($row[0])
                ->setNameFR($row[1])
                ->setNameEN($row[2]);
            $this->em->persist($genericName);
            $this->em->flush();
        }
    }

    public function remove()
    {
        return $this->em->getRepository(Item::class)->removeOldProduct();
    }


    /**
     * @throws \League\Csv\InvalidArgument
     * @throws \League\Csv\UnavailableFeature
     * @throws \League\Csv\UnableToProcessCsv
     */
    public function freeRulesReasort(): string
    {
        $dataBdd = $this->em->getRepository(FreeRestockingRules::class)->findAll();

        foreach ($dataBdd as $data) {
            $this->em->remove($data);
            $this->em->flush();
        }

        $csv = Reader::createFromPath('../public/csv/RegleStockClient.csv');
        $csv->setOutputBOM(Reader::BOM_UTF8);
        $csv->addStreamFilter('convert.iconv.ISO-8859-15/UTF-8');
        $csv->setDelimiter(';');
        $csv->fetchColumn();


        foreach ($csv as $row) {
            if (!strpos($row[3], 'IdArticle') && $row[6] != "") {
                if (strpos($row[3], 'IN(')) {
                    $explodeCol3 = explode(')', explode('(', $row[3])[1])[0];
                } else if (strpos($row[3], '=') ) {
                    $explodeCol3 = explode('=', $row[3])[1];
                }
                if (strpos($row[4], 'IN(')) {
                    $explodeCol4 = explode(')', explode('(', $row[4])[1])[0];
                } else if (strpos($row[4], '=') ) {
                    $explodeCol4 = explode('=', $row[4])[1];
                }
                           $society = $this->em->getRepository(Society::class)->findOneBy(['idCustomer' => $row[0]]);
                $freeRestockingRules = new FreeRestockingRules();
                $freeRestockingRules
                    ->setSocietyID($society)
                    ->setTypeOfRule($explodeCol3)
                    ->setValueCondition($explodeCol4)
                    ->setObtention($row[5])
                    ->setValueRule($row[6])
                    ->setAmountStep($row[7])
                    ->setAmountQuantity($row[8])
                    ->setValidity($row[9])
                    ->setLabelFr($row[10])
                    ->setLabelEn($row[11]);
                $this->em->persist($freeRestockingRules);
                $this->em->flush();
            }
        }
        return 'Ok';
    }
}
