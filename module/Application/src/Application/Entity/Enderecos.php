<?php
namespace Application\Entity;

use Components\Entity\AbstractEntity;
use Components\InputFilter\InputFilter;
use Zend\Filter\Int;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\Validator\NotEmpty;
use Zend\Validator\StringLength;
use Zend\Validator\Digits;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="tb_enderecos")
 */
class Enderecos extends AbstractEntity{
	/**
	 * @ORM\ID
	 * @ORM\Column(name="id_enderecos",type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/**
	 * @ORM\Column(name="logradouro_endereco",type="string")
	 */
	private $logradouro;
	
	/**
	 * @ORM\Column(name="numero_endereco",type="string")
	 */
	private $numero;
	
	/**
	 * @ORM\Column(name="bairro",type="string")
	 */
	private $bairro;
	
	/**
	 * @ORM\Column(name="municipio",type="string")
	 */
	private $municipio;
	/**
	 * @ORM\Column(name="uf",type="string")
	 */
	private $uf;
	
	/**
	 * @ORM\Column(name="cep",type="string")
	 */
	private $cep;
	
	/**
	 * @ORM\Column(name="complemento",type="string")
	 */
	private $complemento;
		
	/**
	 * @ORM\Column(name="id_instituicao",type="integer")
	 */
	private $id_instituicao;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Instituicao",inversedBy="enderecos")
	 * @ORM\JoinColumn(name="id_instituicao", referencedColumnName="id_instituicao")
	 */
	private $instituicao;
	
	/**
	 * 
	 * @param mixed $property
	 */
	public function __get($property){
		return $this->$property;
	}
	
	/**
	 * 
	 * @param mixed $property
	 * @param mixed $value
	 */
	public function __set($property, $value){
		$this->$property = $value;
	}
	
	public function getInputFilter(){}
	public function getArrayCopy(){}
}
?>