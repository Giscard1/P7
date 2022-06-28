<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;


class ApiProductController extends AbstractController
{
    /**
     * Cette méthode permet de récupérer l'ensemble des produits BileMo.
     *
     * @OA\Response(
     *     response=200,
     *     description="Retourne la liste des produits BileMo",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Product::class, groups={"getProducts"}))
     *     )
     * )
     * @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="La page que l'on veut récupérer",
     *     @OA\Schema(type="int")
     * )
     *
     * @OA\Parameter(
     *     name="limit",
     *     in="query",
     *     description="Le nombre d'éléments que l'on veut récupérer",
     *     @OA\Schema(type="int")
     * )
     * @OA\Tag(name="Products")
     *
     * @param ProductRepository $productRepository
     * @param SerializerInterface $serializer
     * @param Request $request
     * @return JsonResponse
     */
    #[Route("/api/products",name:"allProducts", methods: ["GET"])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits suffisants pour supprimer un utilisateur')]
    public function allProducts(Request $request,ProductRepository $productRepository, SerializerInterface $serializer)
    {

        $page = $request->get('page', 1);
        $limit = $request->get('limit', 3);

        $products = $productRepository->findAllWithPagination($page,$limit);

        $data = $serializer->serialize($products, 'json', SerializationContext::create()->setGroups(['list']));
        //$data = $serializer->serialize($products, 'json');
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }

    /**
     * Cette méthode permet de recupérer le détail d’un produit.
     *
     * @OA\Response(
     *     response=200,
     *     description="Retourne les information d’un produit",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Product::class, groups={"getProducts"}))
     *     )
     * )
     * @OA\Tag(name="Products")
     * @param $id
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    #[Route("/api/products/{id}",name:"oneProduct", methods: ["GET"])]
    public function product(int $id, ProductRepository $productRepository, SerializerInterface $serializer)
    {
        $product = $productRepository->find($id);
        if (!$product instanceof Product) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }
        $data = $serializer->serialize($product, 'json');
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
