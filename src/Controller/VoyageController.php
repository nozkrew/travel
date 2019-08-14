<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\VoyageType;
use App\Entity\Voyage;
use Symfony\Component\HttpFoundation\Request;
use App\Form\VoyageActivitiesType;

/**
* @Route("/voyage", name="voyage")
*/
class VoyageController extends AbstractController
{
    
    /**
     * @Route("/")
     */
    public function index(Request $request)
    {
        
        $voyages = $this->getVoyageRepository()->findBy(array(
            'user' => $this->getUser()
        ));
        
        return $this->render('voyage/index.html.twig', [
            'voyages' => $voyages
        ]);
    }
    
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
                $voyage->setUser($this->getUser());
                $em = $this->getDoctrine()->getManager();
                $em->persist($voyage);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', "Votre voyage a été enregistré");
                return $this->redirect($this->generateUrl('voyageapp_voyage_view', array('slug'=>$voyage->getSlug())));
                
            } catch (\Exception $ex) {
                $this->get('session')->getFlashBag()->add('error', "Une erreur est survenue lors de l'enregistrement. Veuillez ré-éssayer");
            }
            
        }
        
        return $this->render('voyage/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/{slug}")
     */
    public function view(Request $request, $slug){
        
        //Recupérer le voyage avec les heures ascendantes
        //ou créer le calendirer
        $voyage = $this->getVoyageRepository()->findOneBy(array(
            'slug' => $slug
        ));
        
        $form = $this->createForm(VoyageActivitiesType::class);
        
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            foreach($form->get('activites')->getData() as $activite){
                $activite->setVoyage($voyage);
                $em->persist($activite);
            }
            try{
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', "Evénement(s) enregistré(s)");
                return $this->redirect($this->generateUrl("voyageapp_voyage_view", array('slug'=>$voyage->getSlug())));
            } catch (\Exception $ex) {
                $this->get('session')->getFlashBag()->add('error', "Une erreur est survenue lors de l'enregistrement. Veuillez ré-éssayer");
            }
            
        }
        
        return $this->render('voyage/view.html.twig', [
            'voyage' => $voyage,
            'form' => $form->createView()
        ]);
    }
    
    private function getVoyageRepository(){
        return $this->getDoctrine()->getRepository(Voyage::class);
    }
}
