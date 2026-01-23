<?php

namespace App\Controller\ShippingMethod;

use App\Entity\ShippingMethod;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SoftDeleteShippingMethodController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function __invoke(Request $request): Response
    {
        $id = $request->attributes->get('id');
        $shippingMethod = $this->entityManager->getRepository(ShippingMethod::class)->find($id);
        $shippingMethod->setDeletedAt(new \DateTimeImmutable());
        $this->entityManager->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
