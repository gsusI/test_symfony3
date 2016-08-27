<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\ManyToMany as ManyToMany;

/**
* Competition
*
* @ORM\Table(name="competition")
* @ORM\Entity(repositoryClass="AppBundle\Repository\CompetitionRepository")
*/
class Competition
{
    /**
    * @var int
    *
    * @ORM\Column(name="competitionID", type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $competitionID;
    
    /**
    * @var string
    *
    * @ORM\Column(name="name", type="string", length=255)
    */
    private $name;
    
    
    /**
    * @var \Doctrine\Common\Collections\Collection|UserGroup[]
    *
    * @ORM\ManyToMany(targetEntity="Team", inversedBy="competitions")
    * @ORM\JoinTable(
    *  name="competition_team",
    *  joinColumns={
    *      @ORM\JoinColumn(name="competitionID", referencedColumnName="competitionID")
    *  },
    *  inverseJoinColumns={
    *      @ORM\JoinColumn(name="teamID", referencedColumnName="teamID")
    *  }
    * )
    */
    private $teams;
    
    
    public function __construct() {
        $this->teams = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
    * Get competitionID
    *
    * @return int
    */
    public function getCompetitionID()
    {
        return $this->competitionID;
    }
    
    /**
    * Set competitionID
    *
    * @param integer $competitionID
    *
    * @return Competition
    */
    public function setCompetitionID($competitionID)
    {
        $this->competitionID = $competitionID;
        
        return $this;
    }
    
    /**
    * Set name
    *
    * @param string $name
    *
    * @return Competition
    */
    public function setName($name)
    {
        $this->name = $name;
        
        return $this;
    }
    
    /**
    * Get name
    *
    * @return string
    */
    public function getName()
    {
        return $this->name;
    }
    
    /**
    * Add team
    *
    * @param \AppBundle\Entity\Team $team
    *
    * @return Competition
    */
    public function addTeam(\AppBundle\Entity\Team $team)
    {
        $this->teams[] = $team;
        
        return $this;
    }
    
    /**
    * Remove team
    *
    * @param \AppBundle\Entity\Team $team
    */
    public function removeTeam(\AppBundle\Entity\Team $team)
    {
        $this->teams->removeElement($team);
    }
    
    /**
    * Get teams
    *
    * @return \Doctrine\Common\Collections\Collection
    */
    public function getTeams()
    {
        return $this->teams;
    }
    
    public function __toString() {
        return $this->name;
    }
}