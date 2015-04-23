<?php
namespace Doacao\Model;

use Components\Entity\AbstractEntity;
use Components\InputFilter\InputFilter;
use Zend\Filter\Int;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\Validator\StringLength;
use Zend\Validator\Digits;
use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\Mapping as ORM;


/**
 * Doacao\Model\Instituicao
 * @ORM\Entity
 * @ORM\Table(name="tb_instbenef")
 *
 */
class Instituicao extends AbstractEntity{
	
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer", name="id_instituicao")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 **/
	protected $id;
	
	/**
	 * @ORM\Column(type="string")
	 */
	
	protected $cnpj;
	
	/**
	 * @ORM\Column(type="string", name="razao_social")
	 */
	protected $razaoSocial;
	
	/**
	 * @ORM\Column(type="string", name="nome_fantasia")
	 */
	protected $nomeFantasia;
	
	/**
	 * @ORM\Column(type="string", length=500)
	 */
	protected $descricao;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected $email;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected $site;
	
	/**
	 * @ORM\Column(type="blob")
	 */
	protected $foto;
	
	/**
	 * @ORM\Column(type="datetime", name="data_cadastro")
	 */
	protected $dataCadastro;
	
	/**
	 * @ORM\OneToMany(targetEntity="Donativo", mappedBy="instituicao")
	 */
	protected $donativos;
	
	/**
	 * @ORM\OneToMany(targetEntity="Endereco", mappedBy="instituicao")
	 */
	private $enderecos;
	
	/**
	 * @ORM\OneToMany(targetEntity="Transacao", mappedBy="instituicao")
	 */
	private $transacoes;
	
	protected $inputFilter;
	
	/**
	 * Metodo construtor, inicializa o array donativos com todos os
	 * donativos solicitados pela instituição.
	 */
	public function __construct(){
		$this->donativos = new ArrayCollection();
		$this->enderecos = new ArrayCollection();
		$this->transacoes = new ArrayCollection();
	}
	
	/**
	 * @return mixed
	 */
	public function __get($property){
		return $this->$property;
	}
	
	/**
	 * @param mixed
	 * @param mixed
	 */
	public function __set($property,$value){ $this->$property = $value;}
	
	public function getArrayCopy() {
		return "";
	}
	
	public function getInputFilter(){
		
		if(!$this->inputFilter){
			$inputFilter = new InputFilter();
			
			$inputFilter->add(array(
				'name'=>'razaoSocial',
				'required'=>true,
				'filters'
			));
		}
		
		return "";
	}
	
	/**
	 * Retorna o endereço principal passado por um array de objetos
	 * @param array $enderecos
	 * @return string
	 */
	public function getEnderecoPrincipal($enderecos){
		$endereco = "";
		foreach($this->enderecos as $end){
			$principal = $end->__get('principal');
			if($principal === 'S'){
				$endereco .= $end->__get('logradouro').", "
						  .$end->__get('numero').", "
						  .$end->__get('bairro').", "
						  .$end->__get('municipio').", "
						  .$end->__get('uf')." - "
						  .$end->__get('cep');
			}
		}
		
		return $endereco;
	}
	
	/**
	 * Parametro retorna o array de objetos donativos
	 * @param array $objectArray
	 * @return multitype:
	 */
	public function donativos($objectArray){
		$donativos = array();
		foreach($objectArray as $donativo):
			array_push($donativos,$donativo);	
		endforeach;
		
		return $donativos;
	}
	
	/**
	 * Parametro retorna o array de objetos transacoes
	 * @param array $objectArray
	 * @return multitype:
	 */
	public function transacoes($objectArray){
		$transacoes = array();
		
		foreach($objectArray as $transacao):
			array_push($transacoes, $transacao->__get('transacao'));
		endforeach;
		
		return $transacoes;
	}
	
	/**
	 * Parametro retorna o array de objetos transaçoes efetivas
	 * @param array $objectArray
	 * @return multitype:
	 */
	public function tEfetiva($objectArray){
		$tEfetivas = array();
		foreach($objectArray as $tEfetiva):
			array_push($tEfetivas, $tEfetiva->__get('transacaoEfetiva'));
		endforeach;
		
		return $tEfetivas;
	}
	
	/**
	 * Parametro retorna o array de objetos dos doadores
	 * @param array $objectArray
	 * @return multitype:
	 */
	public function doadores($objectArray){
		$doadores = array();
		
		foreach($objectArray as $doador):
			array_push($objectArray, $doador->__get('pessoa'));
		endforeach;
		
		return $doadores;
	}
}