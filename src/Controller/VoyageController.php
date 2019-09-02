<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\VoyageType;
use App\Entity\Voyage;
use App\Entity\Categorie;
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

        $voyage = $this->getVoyageRepository()->findOneBy(array(
            'slug' => $slug,
            'user' => $this->getUser()
        ));
        
        if($voyage === null){
            $this->get('session')->getFlashBag()->add('error', "Aucun voyage trouvé");
            return $this->redirect($this->generateUrl("app_main_index"));
        }
        
        $events = $this->getFullCalendarEvents($voyage->getActivites()->filter(
            //tri sur les activité non supprimées
            function($activite) {
                return !$activite->getSupprime();
            }
        ));
        
        $categories = $this->getCategorieRepository()->findAll();
        
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
            'events' => $events,
            'form' => $form->createView(),
            'categories' => $categories
        ]);
    }
    
    private function getFullCalendarEvents($activites){
        $events = array();
        foreach($activites as $activite){
            $tmp = array();
            $tmp['id'] = $activite->getId();
            $tmp['title'] = $activite->getNom();
            $tmp['start'] = $activite->getDateDeb()->format('Y-m-d H:i');
            $tmp['end'] = $activite->getDateFin()->format('Y-m-d H:i');
            $tmp['description'] = $activite->getDescription();
            $tmp['urlDelete'] = $this->generateUrl("app_activite_delete", array('id'=>$activite->getId(), 'slug'=>$activite->getSlug()));
            $tmp['urlUpdate'] = $this->generateUrl("app_activite_editdate", array('id'=>$activite->getId(), 'slug'=>$activite->getSlug()));
            $tmp['backgroundColor'] = $activite->getCategorie()->getCouleur();
            $tmp['borderColor'] = $activite->getCategorie()->getCouleur();
            $events[] = $tmp;
        }        
        return json_encode($events);
    }
    
    private function getVoyageRepository(){
        return $this->getDoctrine()->getRepository(Voyage::class);
    }
    
    private function getCategorieRepository(){
        return $this->getDoctrine()->getRepository(Categorie::class);
    }
}
