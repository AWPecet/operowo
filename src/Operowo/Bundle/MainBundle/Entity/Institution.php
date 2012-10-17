<?php

namespace Operowo\Bundle\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Operowo\Bundle\MainBundle\Entity\InstitutionsRepository")
 * @ORM\Table(name="institutions")
 */
class Institution
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $name;

	/**
	 * @ORM\ManyToOne(targetEntity="Operowo\Bundle\MainBundle\Entity\Province", inversedBy="institutions", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
	 */
	private $province;

	public function getId()
	{
		return $this->id;
	}

	public function setName($name)
	{
		$this->name = $name;
	}

	public function getName()
	{
		return $this->name;
	}

	public function setProvince(Province $province)
	{
		$this->province = $province;
	}

	public function getProvince()
	{
		return $this->province;
	}
}