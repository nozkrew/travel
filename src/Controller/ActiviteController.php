<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Activite;

/**
* @Route("/activite")
*/
class ActiviteController extends AbstractController
{
    /**
     * @Route("/{id}-{slug}/delete", methods={"GET", "POST"}, requirements={"id"="\d+"})
     */
    public function delete(Request $request, $slug, $id){
        
        $activite = $this->getActiviteRepository()->findOneBySlugAndIdAndUser($slug, $id, $this->getUser());
        
        if($activite === null){
            $referer = $request->headers->get('referer');
            $this->get('session')->getFlashBag()->add('error', "Activité non trouvée");
            return $this->redirect($referer);
        }
        
        $form = $this->createFormBuilder()->getForm();
        
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            try{
                $activite->setSupprime(true);
                $em = $this->getDoctrine()->getManager();
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', "L'activité a été supprimée.");
                return $this->redirect($this->generateUrl("voyageapp_voyage_view", array('slug'=>$activite->getVoyage()->getSlug())));
            } catch (Exception $ex) {
                $this->get('session')->getFlashBag()->add('error', "Erreur lors de la suppression. Veuillez ré-essayer.");
            }
        }
         
        
        return $this->render('activite/delete.html.twig', [
            'activite' => $activite,
            'form' => $form->createView()
        ]);
    }
    
    private function getActiviteRepository(){
        return $this->getDoctrine()->getRepository(Activite::class);
    }
}
