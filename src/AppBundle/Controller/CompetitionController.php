<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Competition;
use AppBundle\Form\CompetitionType;

/**
 * Competition controller.
 *
 * @Route("/competition")
 */
class CompetitionController extends Controller
{
    /**
     * Lists all Competition entities.
     *
     * @Route("/", name="competition_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $competitions = array();
        foreach($em->getRepository('AppBundle:Competition')->findAll() as $competition){
            $competitions[] = array('id'=>$competition->getCompetitionID(),'name'=>$competition->getName());
        }
        return $this->json($competitions);
    }

    /**
     * Creates a new Competition entity.
     *
     * @Route("/new", name="competition_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $competition = new Competition();
        $form = $this->createForm('AppBundle\Form\CompetitionType', $competition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($competition);
            $em->flush();

            return $this->redirectToRoute('competition_show', array('id' => $competition->getCompetitionID()));
        }

        return $this->render('competition/new.html.twig', array(
            'competition' => $competition,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Competition entity.
     *
     * @Route("/{id}", name="competition_show")
     * @Method("GET")
     */
    public function showAction(Competition $competition)
    {
        $deleteForm = $this->createDeleteForm($competition);

        return $this->render('competition/show.html.twig', array(
            'competition' => $competition,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Finds and displays a Competition entity.
     *
     * @Route("/{id}/players", name="competition_show_players")
     * @Method("GET")
     */
    public function showPlayersAction(Competition $competition)
    {
        $players = array();
        foreach($competition->getTeams() as $team){
            foreach($team->getPlayers() as $player){
                $players[] = array('id'=>$player->getPlayerID(),'name'=>$player->getName(),'team'=>array('id'=>$team->getTeamID(),'name'=>$team->getName()));
            }
        }
        return $this->json($players);
    }

    /**
     * Finds and displays a Competition entity.
     *
     * @Route("/{id}/teams", name="competition_show_teams")
     * @Method("GET")
     */
    public function showTeamsAction(Competition $competition)
    {
        $teams = array();
        foreach($competition->getTeams() as $team){
                $teams[] = array('id'=>$team->getTeamID(),'name'=>$team->getName());
        }
        return $this->json($teams);
    }

    /**
     * Displays a form to edit an existing Competition entity.
     *
     * @Route("/{id}/edit", name="competition_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Competition $competition)
    {
        $deleteForm = $this->createDeleteForm($competition);
        $editForm = $this->createForm('AppBundle\Form\CompetitionType', $competition);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($competition);
            $em->flush();

            return $this->redirectToRoute('competition_edit', array('id' => $competition->getCompetitionID()));
        }

        return $this->render('competition/edit.html.twig', array(
            'competition' => $competition,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Competition entity.
     *
     * @Route("/{id}", name="competition_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Competition $competition)
    {
        $form = $this->createDeleteForm($competition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($competition);
            $em->flush();
        }

        return $this->redirectToRoute('competition_index');
    }

    /**
     * Creates a form to delete a Competition entity.
     *
     * @param Competition $competition The Competition entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Competition $competition)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('competition_delete', array('id' => $competition->getCompetitionID())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
