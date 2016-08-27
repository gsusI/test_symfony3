<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Team;
use AppBundle\Form\TeamType;

/**
* Team controller.
*
* @Route("/team")
*/
class TeamController extends Controller
{
    /**
    * Lists all Team entities.
    *
    * @Route("/", name="team_index")
    * @Method("GET")
    */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        $teams = array();
        foreach($em->getRepository('AppBundle:Team')->findAll() as $team){
            $teams[] = array('id'=>$team->getTeamID(),'name'=>$team->getName());
        }
        return $this->json($teams);
        
    }
    
    /**
    * Lists all Player entities in a Team.
    *
    * @Route("/{id}/players", name="team_players")
    * @Method("GET")
    */
    public function showPlayersAction(Request $request, Team $team)
    {
        $em = $this->getDoctrine()->getManager();
        $players = array();
        foreach($team->getPlayers() as $player){
            $players[] = array('id'=>$player->getPlayerID(),'name'=>$player->getName());
        }
        return $this->json($players);
    }

    /**
    * Lists all Player entities in a Team.
    *
    * @Route("/{id}/players/search/{keyword}", name="team_players_search")
    * @Method("GET")
    */
    public function showPlayersSearchAction(Request $request, Team $team,$keyword)
    {
        $em = $this->getDoctrine()->getManager();
        $players = array();
        foreach($team->getPlayers() as $player){
            if($player->match($keyword)){
                $players[] = array('id'=>$player->getPlayerID(),'name'=>$player->getName());
            }
        }
        return $this->json($players);
    }
    
    /**
    * Creates a new Team entity.
    *
    * @Route("/new", name="team_new")
    * @Method({"GET", "POST"})
    */
    public function newAction(Request $request)
    {
        $team = new Team();
        $form = $this->createForm('AppBundle\Form\TeamType', $team);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($team);
            $em->flush();
            
            return $this->redirectToRoute('team_show', array('id' => $team->getTeamID()));
        }
        
        return $this->render('team/new.html.twig', array(
        'team' => $team,
        'form' => $form->createView(),
        ));
    }
    
    /**
    * Finds and displays a Team entity.
    *
    * @Route("/{id}", name="team_show")
    * @Method("GET")
    */
    public function showAction(Team $team)
    {
        $deleteForm = $this->createDeleteForm($team);
        
        return $this->render('team/show.html.twig', array(
        'team' => $team,
        'delete_form' => $deleteForm->createView(),
        ));
    }
    
    /**
    * Displays a form to edit an existing Team entity.
    *
    * @Route("/{id}/edit", name="team_edit")
    * @Method({"GET", "POST"})
    */
    public function editAction(Request $request, Team $team)
    {
        $deleteForm = $this->createDeleteForm($team);
        $editForm = $this->createForm('AppBundle\Form\TeamType', $team);
        $editForm->handleRequest($request);
        
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($team);
            $em->flush();
            
            return $this->redirectToRoute('team_edit', array('id' => $team->getTeamID()));
        }
        
        return $this->render('team/edit.html.twig', array(
        'team' => $team,
        'edit_form' => $editForm->createView(),
        'delete_form' => $deleteForm->createView(),
        ));
    }
    
    /**
    * Deletes a Team entity.
    *
    * @Route("/{id}", name="team_delete")
    * @Method("DELETE")
    */
    public function deleteAction(Request $request, Team $team)
    {
        $form = $this->createDeleteForm($team);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($team);
            $em->flush();
        }
        
        return $this->redirectToRoute('team_index');
    }
    
    /**
    * Creates a form to delete a Team entity.
    *
    * @param Team $team The Team entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createDeleteForm(Team $team)
    {
        return $this->createFormBuilder()
        ->setAction($this->generateUrl('team_delete', array('id' => $team->getTeamID())))
        ->setMethod('DELETE')
        ->getForm()
        ;
    }
}