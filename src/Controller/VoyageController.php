<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\VoyageType;
use App\Entity\Voyage;
use Symfony\Component\HttpFoundation\Request;

/**
* @Route("/voyage", name="voyage")
*/
class VoyageController extends AbstractController
{
    
    /**
     * @Route("/add")
     */
    public function add(Request $request)
    {
        
        $voyage = new Voyage();
        
        $form = $this->createForm(VoyageType::class, $voyage, array());
        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            
            try{
                $em = $this->getDoctrine()->getManager();
                $em->persist($voyage);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', "Votre voyage a été enregistré");
                
            } catch (\Exception $ex) {
                $this->get('session')->getFlashBag()->add('error', "Une erreur est survenue lors de l'enregistrement. Veuillez ré-éssayer");
            }
            
        }
        
        return $this->render('voyage/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
