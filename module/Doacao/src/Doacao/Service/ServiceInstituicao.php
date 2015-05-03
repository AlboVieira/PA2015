<?php
namespace Doacao\Service;

use Application\Entity\Instituicao;
use Application\Entity\Donativos;

class ServiceInstituicao{
	private static $em;
	private static $instituicao;
	
	public function __construct(){
		if(!isset(self::$instituicao)){
			self::$instituicao = new Instituicao();
		}
		return self::$instituicao;
	}
	
	public function buscaUmaInstituicao($id){
		$em = self::getServiceLocator();
		$instituicao = $em->find('\Application\Entity\Instituicao',$id);
		return $instituicao;
	}

	public static function getServiceLocator(){
		if(!isset(self::$em)){
			self::$em = $GLOBALS['entityManager'];
		}
		return self::$em;
	}
	
	public function buscaInstituicoes(){
		$em = self::getServiceLocator();
		$instituicoes = $em->getRepository('Application\Entity\Instituicao')->findAll();

		$arrInstituicao = [];
		foreach($instituicoes as $key=>$instituicao){
			 $arrInstituicao[$key]['id'] = $instituicao->__get('id');
			 $arrInstituicao[$key]['nomeFantasia'] = $instituicao->__get('nomeFantasia');
			 $arrInstituicao[$key]['razaoSocial'] = $instituicao->__get('razaoSocial');
			 $arrInstituicao[$key]['foto'] = $instituicao->__get('foto');
			 $arrInstituicao[$key]['descricao'] = $instituicao->__get('descricao');
			 $arrInstituicao[$key]['email'] = $instituicao->__get('email');
			 $arrInstituicao[$key]['cnpj'] = $instituicao->__get('cnpj');
			 $arrInstituicao[$key]['site'] = $instituicao->__get('site');
			 $arrInstituicao[$key]['site'] = $instituicao->__get('site');
		}

		return $arrInstituicao;
	}
	
	/**
	 * 
	 * @param Instituicao $object
	 */
	public function listaDonativos(Instituicao $object){
		$donativos = $object->listaDoacao($object, 'donativos');
		$listHtml = null;
		if($donativos) {
			foreach ($donativos as $item):
				$id = $item->__get('id');
				$titulo = $item->__get('titulo');
				$descricao = $item->__get('descricao');
				$quantidade = $item->__get('quantidade');
				$dataInclu = $item->__get('dataInclu')->format('d/m/Y');
				$dataDesa = $item->__get('dataDesa')->format('d/m/Y');
				$listHtml = "" .
					"<li class='list-group-item' data-toggle='collapse' href='#donativo-{$id}' aria-expanded='false' aria-controls='perfilcollapse'>
				{$titulo}
				<br>
				<sub>Incluído em: {$dataInclu}</sub>
				<span class='badge pull-rigth'>Pedidos: {$quantidade}</span><span class='badge pull-rigth label-info'>Doados: 36</span> 
			</li>
			<ul class='list-flat list-group in' data-toggle='collapse' id='donativo-{$id}'>
				<li class='list-group-item'>
					{$descricao}
					<br>
					<sub>Expira em: {$dataDesa}</sub>
					<div class='pull-right btn-group btn-group-sm'>						
						<a class='btn btn-warning'>Gerenciar</a>
						<a class='btn btn-danger'>Desativar</a>
					</div>
				</li>
			</ul>
			";
			endforeach;
		}
		return $listHtml;
	}
}