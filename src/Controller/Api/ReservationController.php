<?php

namespace App\Controller\Api;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/reservations')]
class ReservationController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private SerializerInterface $serializer,
        private ValidatorInterface $validator,
        private ReservationRepository $repo
    ) {}

    #[Route('', name: 'create_reservation', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if ($data === null) {
            return $this->json(['error' => 'Invalid JSON'], 400);
        }

        $res = new Reservation();
        $res->setCustomerName($data['customerName'] ?? '');
        $res->setCustomerEmail($data['customerEmail'] ?? '');
        $res->setCustomerPhone($data['customerPhone'] ?? '');

        try {
            $res->setDate(new \DateTime($data['date'] ?? ''));
            $res->setTime(new \DateTime($data['time'] ?? ''));
        } catch (\Exception $e) {
            return $this->json(['error' => 'Invalid date/time format'], 400);
        }

        $res->setNumberOfGuests((int)($data['numberOfGuests'] ?? 0));
        $res->setSpecialRequests($data['specialRequests'] ?? null);
        // status defaults to pending

        $errors = $this->validator->validate($res);
        if (count($errors) > 0) {
            $errorsStr = [];
            foreach ($errors as $e) {
                $errorsStr[] = $e->getPropertyPath() . ': ' . $e->getMessage();
            }
            return $this->json(['errors' => $errorsStr], 422);
        }

        $this->em->persist($res);
        $this->em->flush();

        $json = $this->serializer->serialize($res, 'json', ['groups' => ['details']]);

        return new JsonResponse($json, 201, [], true);
    }

    #[Route('', name: 'list_reservations', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        $date = $request->query->get('date');
        $status = $request->query->get('status');

        $qb = $this->repo->createQueryBuilder('r');

        if ($date) {
            try {
                $d = new \DateTime($date);
            } catch (\Exception $e) {
                return $this->json(['error' => 'Invalid date format'], 400);
            }
            $qb->andWhere('r.date = :date')->setParameter('date', $d->format('Y-m-d'));
        }

        if ($status) {
            $qb->andWhere('r.status = :status')->setParameter('status', $status);
        }

        $qb->orderBy('r.date', 'ASC')->addOrderBy('r.time','ASC');

        $items = $qb->getQuery()->getResult();

        $json = $this->serializer->serialize($items, 'json', ['groups' => ['list']]);
        return new JsonResponse($json, 200, [], true);
    }
}
