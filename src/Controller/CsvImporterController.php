<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
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

    private $resetPasswordHelper;

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
    public function index(EntityManagerInterface $em, MailerInterface $mailer): Response
    {
        $csv = Reader::createFromPath('../public/csv/customer.csv');
        $csv->fetchColumn();
        foreach ($csv as $row) {
            $user    = $this->getDoctrine()->getRepository(User::class)->findOneBy([ 'email' => $row[0] ]);
            $newUser = new User();
            $newUser
                ->setEmail($row[0])
                ->setAccountActivated($row[1])
                ->setIsVerified($row[2])
                ->setPassword('TOTOaChanger')
                ->setRoles([ "ROLE_USER" ])
            ;
            $em->persist($newUser);
            $em->flush();

            $user = $this->getDoctrine()->getRepository(User::class)->findOneBy([ 'email' => $newUser->getEmail()]);

            $resetToken = $this->resetPasswordHelper->generateResetToken($user);
            $email = (new TemplatedEmail())
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

        return $this->render('csv_importer/index.html.twig', [
            'controller_name' => 'CsvImporterController',
        ]);
    }

//    /**
//     * @Route("/import-csv-user", name="import_csv_user")
//     * @param EntityManagerInterface $em
//     *
//     * @return Response
//     * @throws ResetPasswordExceptionInterface
//     */
//    public function importBooking(EntityManagerInterface $em): Response
//    {
//        $csv = Reader::createFromPath('../public/csv/booking.csv');
//        $csv->fetchColumn();
//        
//        foreach ($csv as $row) {
//    
////            $newUser = new //
//            $newUser
//                ->setEmail($row[0])
//                ->setPassword('TOTOaChanger')
//                ->setRoles([ "ROLE_USER" ])
//            ;
//            $em->persist($newUser);
//            $em->flush();
//
//            $user = $this->getDoctrine()->getRepository(User::class)->findOneBy([ 'email' => $newUser->getEmail()]);
//
//            $resetToken = $this->resetPasswordHelper->generateResetToken($user);
//            $email = (new TemplatedEmail())
//                ->from(new Address('clopez@nexton-group.com', 'Acme Mail Bot'))
//                ->to($newUser->getEmail())
//                ->subject('Demande de nouveau mot de passe')
//                ->htmlTemplate('emails/account/reset.html.twig')
//                ->context([
//                    'resetToken' => $resetToken,
//                ]);
//
//            $mailer->send($email);
//            $this->setTokenObjectInSession($resetToken);
//
//        }
//
//        return $this->render('csv_importer/index.html.twig', [
//            'controller_name' => 'CsvImporterController',
//        ]);
//    }
}
