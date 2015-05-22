<?php
namespace Doacao\Service;

use Application\Entity\Instituicao;
use Application\Entity\Donativos;
use Application\Service\AbstractService;
use Doacao\Dao\InstituicaoDao;
use Zend\Console\Charset\Utf8;
use Zend\Mime\Decode;
use Zend\View\Helper\ViewModel;

class ServiceInstituicao extends AbstractService{
	private static $em;
	private static $instituicao;
	private $instituicaoDao;

	/** PASSAR ESSES METODOS QUE ACESSAM A ENTIDADE INSTITUICAO PARA A INSTITUICAO DAO
	 O QUE FOR GERAL PASSAR PARA A CLASSE SERVICE
	 *
	 */

	public function __construct(){
		if(!$this->instituicaoDao){
			$this->instituicaoDao = new InstituicaoDao();
		}
	}

	public function getObjInstituicao(){
		$usuario = $this->getUserLogado();
		return $this->instituicaoDao->selectPorUsuario($usuario);
	}

	public function buscaUmaInstituicao($id){
		$instituicao = $this->instituicaoDao->findById($id,'Application\Entity\Instituicao');
		return $instituicao;
	}
		
	public function save($data){
		
	}
	
	/**
	 * Monta o Perfil de uma instituição e retorna respectivo HTML
	 * @param Instituicao $instituicao
	 * @return string
	 */
	public function montaPerfilHtml(Instituicao $instituicao){
		$id = $instituicao->__get('id');
		$razaoSocial = $instituicao->__get('razaoSocial');
		$nomeFantasia = $instituicao->__get('nomeFantasia');
		$descricao = $instituicao->__get('descricao');
		$email = $instituicao->__get('email');
		$site = $instituicao->__get('site');
		$foto = $instituicao->__get('foto');
		$dataCadastro = $instituicao->__get('dataCadastro');
		//$endereco = $instituicao->listaEnderecos($instituicao);
		$perfilHtml = ""
		."
        <div class='sidebar-flat-perfil panel label-primary'>
            <br>
            <div class='emulate-img label-default center-block img-circle'></div>
            <br>
            <li class='list-group-item-special' data-toggle='collapse' href='#perfilcollapse' aria-expanded='false' aria-controls='perfilcollapse'>
				<b>Perfil <i class='caret'></i></b>
			</li>
            <ul class='list-group list-flat in' id='perfilcollapse' data-toggle='collapse'>
                <li class='list-group-item'><span class='glyphicon glyphicon-user'></span> {$razaoSocial}</li>
                <li class='list-group-item'><span class='glyphicon glyphicon-credit-card'></span> {$nomeFantasia}</li>
                <li class='list-group-item'><span class='glyphicon glyphicon-envelope'></span> {$email}</li>
                <li class='list-group-item'><span class='glyphicon glyphicon-link'></span> {$site}</li>
                <li class='list-group-item'><span class='glyphicon glyphicon-calendar'></span> Membro desde {$dataCadastro->format('d/m/Y')}</li>
                <li class='list-group-item'><span class='glyphicon glyphicon-book'></span> {$descricao}</li>
            </ul>
        </div>"
		;

		return $perfilHtml;

	}
	
	/**
	 *
	 * @param Instituicao $object
	 */
	public function listaDonativos(Instituicao $object){
		$donativos = $this->decompoeObjeto($object,'donativos');
		$listHtml = null;
		if($donativos) {
			foreach ($donativos as $item):
				$id = $item->ID();
				$titulo = $item->Titulo();
				$descricao = $item->Descricao();
				$quantidade = $item->Quantidade();
				$dataInclu = $item->DataInclusao()->format('d/m/Y');
				$dataDesa = $item->DataDesativacao()->format('d/m/Y');
				$listHtml = "" .
					"<li class='list-group-item list-group-item-special' data-toggle='collapse' href='#donativo-{$id}' aria-expanded='false' aria-controls='perfilcollapse'>
				{$titulo}
				<br>
				<sub>Incluído em: {$dataInclu}</sub>
				<span class='badge pull-rigth'>Pedidos: {$quantidade}</span><span class='badge pull-rigth label-info'>Doados: 36</span>
			</li>
			<ul class='list-flat list-group in' data-toggle='collapse' id='donativo-{$id}'>
				<li class='list-group-item panel-body'>
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

	public function decompoeObjeto($objectArray, $association){
		$associationArray = array();
		foreach($objectArray->__get($association) as $decomposte):
		array_push($associationArray,$decomposte);
		endforeach;
		return $associationArray;
	}

}