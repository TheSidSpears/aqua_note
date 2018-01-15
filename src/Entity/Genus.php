<?php


namespace App\Entity;

use App\Repository\GenusScientistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GenusRepository")
 * @ORM\Table(name="genus")
 */
class Genus
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="string", unique=true)
     * @Gedmo\Slug(fields={"name"})
     */
    private $slug;

    /**
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="App\Entity\SubFamily")
     * @ORM\JoinColumn(nullable=false)
     */
    private $subFamily;

    /**
     * @Assert\NotBlank()
     * @Assert\Range(min=0, minMessage="Negative species! Come on...")
     * @ORM\Column(type="integer")
     */
    private $speciesCount;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $funFact;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPublished = true;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="date")
     */
    private $firstDiscoveredAt;

    /**
     * @ORM\OneToMany(targetEntity="GenusNote",mappedBy="genus")
     * @ORM\OrderBy({"createdAt"="DESC"})
     */
    private $notes;

    /**
     * @ORM\OneToMany(
     *     targetEntity="GenusScientist",
     *     mappedBy="genus",
     *     fetch="EXTRA_LAZY",
     *     orphanRemoval=true,
     *     cascade={"persist"}
     * )
     * @Assert\Valid()
     */
    private $genusScientists;

    public function __construct()
    {
        $this->notes = new ArrayCollection();
        $this->genusScientists = new ArrayCollection();
    }

    public function __toString()
    {
        return (string)$this->getName();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getSubFamily()
    {
        return $this->subFamily;
    }

    /**
     * @param mixed $subFamily
     */
    public function setSubFamily(SubFamily $subFamily)
    {
        $this->subFamily = $subFamily;
    }

    /**
     * @return mixed
     */
    public function getSpeciesCount()
    {
        return $this->speciesCount;
    }

    /**
     * @param mixed $speciesCount
     */
    public function setSpeciesCount($speciesCount)
    {
        $this->speciesCount = $speciesCount;
    }

    /**
     * @return mixed
     */
    public function getFunFact()
    {
        return $this->funFact;
    }

    /**
     * @param mixed $funFact
     */
    public function setFunFact($funFact)
    {
        $this->funFact = $funFact;
    }

    public function getUpdatedAt()
    {
        return new \DateTime('-' . rand(0, 100) . ' days');
    }

    /**
     * @return mixed
     */
    public function getIsPublished()
    {
        return $this->isPublished;
    }

    /**
     * @param mixed $isPublished
     */
    public function setIsPublished($isPublished)
    {
        $this->isPublished = $isPublished;
    }

    /**
     * @return ArrayCollection|GenusNote[]
     */
    public function getNotes()
    {
        return $this->notes;
    }

    public function getFirstDiscoveredAt()
    {
        return $this->firstDiscoveredAt;
    }

    public function setFirstDiscoveredAt(\DateTime $firstDiscoveredAt = null)
    {
        $this->firstDiscoveredAt = $firstDiscoveredAt;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    public function addGenusScientist(GenusScientist $genusScientist)
    {
        if ($this->genusScientists->contains($genusScientist)) {
            return;
        }

        $this->genusScientists[] = $genusScientist;
        // needed to update the owning side of the relationship!
        $genusScientist->setGenus($this);
    }

    public function removeGenusScientist(GenusScientist $genusScientist)
    {
        if (!$this->genusScientists->contains($genusScientist)) {
            return;
        }

        $this->genusScientists->removeElement($genusScientist);
        // needed to update the owning side of the relationship!
        $genusScientist->setGenus(null);
    }

    /**
     * @return ArrayCollection|GenusScientist[]
     */
    public function getGenusScientists()
    {
        return $this->genusScientists;
    }

    /**
     * @return ArrayCollection|GenusScientist[]
     */
    public function getExpertScientists()
    {
        return $this->getGenusScientists()->matching(
            GenusScientistRepository::createExpertCriteria()
        );
    }

    public function feed($meal)
    {
        return $this->getName() . ' is feeded!';
    }
}
