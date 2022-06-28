<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\CustomerRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Validator\ValidatorInterface;
//use Symfony\Component\Security\Core\Security;


class ApiUserController extends AbstractController
{
    /**
     * Cette méthode permet de consulter le détail d’un utilisateur inscrit lié à un client.
     *
     * @OA\Response(
     *     response=200,
     *     description="Retourne les information d’un utilisateur",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=User::class, groups={"getUser"}))
     *     )
     * )
     * @OA\Tag(name="Users")
     * @param $id
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    #[Route("/api/clients/{id}/users/{userId}", name:"OneProduct", methods: ["GET"])]
    public function user(int $id,int $userId,SerializerInterface $serializer, UserRepository $userRepository)
    {
        $connectedUser = $this->getUser()->getId();

        //Vérification si  l'id de la personne connectée correspondant bien au {id}
        if($id == $connectedUser ) {

            $user = $userRepository->find($userId);
            //Vérifiaction de l'existance de l'utilisateur recherché
            if ($user){
                //Vérifiaction de l'existance de l'utilisateur recherché
                if ($user->getCustomer()->getId() == $id ){
                    $data = $serializer->serialize($user, 'json', SerializationContext::create()->setGroups(['detailUser']));
                    $response = new Response($data);
                    $response->headers->set('Content-Type', 'application/json');

                    return $response;
                }
                return new Response('Vous avez pas la permission',Response::HTTP_FORBIDDEN);
            }else{
                return new Response('Le client n\'a pas d\'utilisateur',Response::HTTP_NO_CONTENT);
            }
        }
        return new Response('Vous avez pas la permission',Response::HTTP_FORBIDDEN);
    }

    /**
     * Cette méthode permet de créer un utilisateur.
     *
     * @OA\Response(
     *     response=200,
     *     description="Créer un utilisateur",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=User::class, groups={"setUser"}))
     *     )
     * )
     * @OA\Tag(name="Users")
     * @param Request $request
     * @param int $id
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $em
     * @param \Doctrine\Persistence\ManagerRegistry $doctrine
     * @param ValidatorInterface $validator
     * @return JsonResponse
     */
    #[Route('/api/clients/{id}/users', name: 'app_api_create_user', methods: ["POST"])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits suffisants pour créer un utilisateur')]
    public function createUser(Request $request,int $id,SerializerInterface $serializer, EntityManagerInterface $em, \Doctrine\Persistence\ManagerRegistry $doctrine, ValidatorInterface $validator){

       $connectedUser = $this->getUser()->getId();

       //A vérifier en premier sur le controller -> que l'id de la personne connectée correspondant bien au {id}
       if($id == $connectedUser ){
            var_dump($id);
            var_dump($connectedUser);
           $data = $request->getContent();
           $user = $serializer->deserialize($data, User::class, 'json');
           $errors = $validator->validate($user);

           //Vérification des erreurs (entitys)
           if (count($errors) > 0) {
               $errorsString = (string) $errors;
               return new Response($errorsString, Response::HTTP_BAD_REQUEST);
           }else{
               if ($connectedUser == $user->getCustomer()->getId()){
                   $validator->validate($user);
                   $em = $doctrine->getManager();
                   $em->persist($user);
                   $em->flush();
                   return new Response('Succès création utilisateur', Response::HTTP_CREATED);
               } else{
                   return new Response('Vous avez pas la permission',Response::HTTP_FORBIDDEN);
               }
           }

       }else{
           return new Response('Vous avez pas la permission',Response::HTTP_FORBIDDEN);
       }
    }

    /**
     * Cette méthode permet de supprimer un utilisateur.
     *
     * @OA\Response(
     *     response=204,
     *     description="Supprimer un utilisateur",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=User::class, groups={"removeUser"}))
     *     )
     * )
     * @OA\Tag(name="Users")
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    #[Route('/api/clients/{id}/users/{idUser}', name: 'app_api_delete_user', methods: ["DELETE"])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits suffisants pour supprimer un utilisateur')]
    public function deleteUser(Request $request,int $id,int $idUser, SerializerInterface $serializer, EntityManagerInterface $em, UserRepository $userRepository){

        $connectedUser = $this->getUser()->getId();
        if ($id !== $connectedUser) {
            return new JsonResponse(
                ['message' => ' Vous avez pas la permission'],
                Response::HTTP_FORBIDDEN
            );
        }
        $user = $userRepository->find($idUser);
        if (!$user instanceof User){
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }
        if ($connectedUser !== $user->getCustomer()->getId()) {
            return new JsonResponse(
                ['message' => ' Vous avez pas la permission'],
                Response::HTTP_FORBIDDEN
            );
        }
        $em->remove($user);
        $em->flush();
        return new JsonResponse(null,Response::HTTP_NO_CONTENT);
    }

    /**
     * Cette méthode permet de récupérer l'ensemble des utilisateurs d'un client.
     *
     * @OA\Response(
     *     response=200,
     *     description="Liste des clients",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=User::class, groups={"getUsers"}))
     *     )
     * )
     * @OA\Tag(name="Users")
     * @param int $id
     * @param CustomerRepository $customerRepository
     * @param UserRepository $userRepository
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    #[Route("/api/clients/{id}/users",name:"allClientsOfACustomer", methods: ["GET"])]
    public function allClients(int $id, Request $request, CustomerRepository $customerRepository,UserRepository $userRepository, SerializerInterface $serializer)
    {
        $connectedUser = $this->getUser()->getId();

        if ($id !== $connectedUser) {
            return new JsonResponse(
                ['message' => ' Vous avez pas la permission'],
                Response::HTTP_FORBIDDEN
            );
        }
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 3);
        $customerId = $this->getUser()->getId();
        $users = $userRepository->findAllWithPagination($page,$limit,$customerId);
        $data = $serializer->serialize($users, 'json');
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }
}
