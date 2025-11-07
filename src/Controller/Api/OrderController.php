<?php

namespace App\Controller\Api;

use App\Entity\CustomerOrder;
use App\Repository\CustomerOrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/orders')]
class OrderController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private SerializerInterface $serializer,
        private ValidatorInterface $validator,
        private CustomerOrderRepository $repo
    ) {}

    #[Route('', name: 'create_order', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if ($data === null) {
            return $this->json(['error' => 'Invalid JSON'], 400);
        }

        $order = new CustomerOrder();
        $order->setTableNumber((int)($data['tableNumber'] ?? 0));
        $items = $data['items'] ?? [];
        if (!is_array($items) || count($items) === 0) {
            return $this->json(['error' => 'Items must be a non-empty array'], 422);
        }

        // validation
        $total = 0.0;
        foreach ($items as $it) {
            if (!isset($it['name'], $it['quantity'], $it['price'])) {
                return $this->json(['error' => 'Each item must have name, quantity and price'], 422);
            }
            $q = (int)$it['quantity'];
            $p = (float)$it['price'];
            if ($q <= 0 || $p < 0) {
                return $this->json(['error' => 'Invalid item quantity or price'], 422);
            }
            $total += $q * $p;
        }

        // Round to 2 decimals and format as string to store in decimal column
        $order->setItems($items);
        $order->setTotalAmount(number_format($total, 2, '.', ''));
        $order->setCustomerNote($data['customerNote'] ?? null);

        $errors = $this->validator->validate($order);
        if (count($errors) > 0) {
            $errorsStr = [];
            foreach ($errors as $e) {
                $errorsStr[] = $e->getPropertyPath() . ': ' . $e->getMessage();
            }
            return $this->json(['errors' => $errorsStr], 422);
        }

        $this->em->persist($order);
        $this->em->flush();

        $json = $this->serializer->serialize($order, 'json', ['groups' => ['details']]);
        return new JsonResponse($json, 201, [], true);
    }

    #[Route('', name: 'list_orders', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        $status = $request->query->get('status');
        $tableNumber = $request->query->get('tableNumber');

        $qb = $this->repo->createQueryBuilder('o');

        if ($status) {
            $qb->andWhere('o.status = :status')->setParameter('status', $status);
        }
        if ($tableNumber) {
            $qb->andWhere('o.tableNumber = :tn')->setParameter('tn', (int)$tableNumber);
        }
        $qb->orderBy('o.createdAt', 'DESC');

        $items = $qb->getQuery()->getResult();

        $json = $this->serializer->serialize($items, 'json', ['groups' => ['list']]);
        return new JsonResponse($json, 200, [], true);
    }
}
