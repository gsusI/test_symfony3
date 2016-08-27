<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Player;
use AppBundle\Form\PlayerType;

/**
* Player controller.
*
* @Route("/player")
*/
class PlayerController extends Controller
{
    /**
    * Lists all Player entities.
    *
    * @Route("/", name="player_index")
    * @Method("GET")
    */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        $players = array();
        foreach($em->getRepository('AppBundle:Player')->findAll() as $player){
            $players[] = array('id'=>$player->getPlayerID(),'name'=>$player->getName());
        }
        return $this->json($players);
    }
    
    /**
    * Creates a new Player entity.
    *
    * @Route("/new", name="player_new")
    * @Method({"GET", "POST"})
    */
    public function newAction(Request $request)
    {
        $player = new Player();
        $form = $this->createForm('AppBundle\Form\PlayerType', $player);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($player);
            $em->flush();
            
            return $this->redirectToRoute('player_show', array('id' => $player->getPlayerID()));
        }
        
        // Is not creating a player, instead of giving error it will show a form to create it
        return $this->render('player/new.html.twig', array(
        'player' => $player,
        'form' => $form->createView(),
        ));
    }
    
    /**
    * Finds and displays a Player entity.
    *
    * @Route("/{id}", name="player_show")
    * @Method("GET")
    */
    public function showAction(Request $request, Player $player)
    {
        return $this->json(array('id'=>$player->getPlayerID(),'name'=>$player->getName(),'team'=>$player->getTeam()->getName()));
    }
    
    /**
    * Finds and displays a Player entity.
    *
    * @Route("/{id}/competitions", name="player_show_competitions")
    * @Method("GET")
    */
    public function showCompetitionsAction(Player $player)
    {
        $competitions = array();
        foreach($player->getTeam()->getCompetitions() as $competition){
            $competitions[] = array('id'=>$competition->getCompetitionID(),'name'=>$competition->getName());
        }
        return $this->json($competitions);
    }
    
    /**
    * Displays a form to edit an existing Player entity.
    *
    * @Route("/{id}/edit", name="player_edit")
    * @Method({"GET", "POST"})
    */
    public function editAction(Request $request, Player $player)
    {
        $deleteForm = $this->createDeleteForm($player);
        $editForm = $this->createForm('AppBundle\Form\PlayerType', $player);
        $editForm->handleRequest($request);
        
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($player);
            $em->flush();
            
            return $this->redirectToRoute('player_show', array('id' => $player->getPlayerID()));
        }
        
        return $this->render('player/edit.html.twig', array(
        'player' => $player,
        'edit_form' => $editForm->createView(),
        'delete_form' => $deleteForm->createView(),
        ));
    }
    
    /**
    * Displays a form to edit an existing Player entity.
    *
    * @Route("/{id}/editteam/{teamID}", name="player_edit_team")
    * @Method({"GET", "POST"})
    */
    public function editTeamAction(Request $request, Player $player, $teamID)
    {
        $em = $this->getDoctrine()->getManager();
        $team = $em->getRepository('AppBundle:Team')->findOneByTeamID($teamID);
        if($team !== NULL){
            $player->setTeam($team);
            $em->persist($player);
            $em->flush();
        }
        return $this->redirectToRoute('player_show', array('id' => $player->getPlayerID()));
    }
    /**
    * Displays a form to edit an existing Player entity.
    *
    * @Route("/search/{keyword}", name="player_search")
    * @Method({"GET", "POST"})
    */
    public function searchAction(Request $request,$keyword)
    {
        $em = $this->getDoctrine()->getManager();
        $players = array();
        foreach($em->getRepository('AppBundle:Player')->findAll() as $player){
            if($player->match($keyword)){
                $players[] = array('id'=>$player->getPlayerID(),'name'=>$player->getName());
            }
        }
        return $this->json($players);
    }
    
    /**
    * Deletes a Player entity.
    *
    * @Route("/{id}", name="player_delete")
    * @Method("DELETE")
    */
    public function deleteAction(Request $request, Player $player)
    {
        $form = $this->createDeleteForm($player);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($player);
            $em->flush();
        }
        
        return $this->redirectToRoute('player_index');
    }
    
    /**
    * Creates a form to delete a Player entity.
    *
    * @param Player $player The Player entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createDeleteForm(Player $player)
    {
        return $this->createFormBuilder()
        ->setAction($this->generateUrl('player_delete', array('id' => $player->getPlayerID())))
        ->setMethod('DELETE')
        ->getForm()
        ;
    }
}