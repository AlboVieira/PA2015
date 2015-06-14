<?php
namespace Doacao\Service;

use Application\Entity\Mensagem;
use Application\Entity\Transacao;
use Application\Service\AbstractService;
use Doacao\Dao\TransacaoDAO;

class TransacaoService extends AbstractService{

	const PESSOA = '2';
	const INSTITUICAO = '3';

	private $transacaoDao;
	
	public function __construct(){
		$this->transacaoDao = new TransacaoDAO();
	}
	
	public function criarTransacao(){

	}

	public function totalPorDonativo($id){
		$quant = $this->transacaoDao->total($id);
		return $quant;
	}

	public function salvar($post, $transacao){

		$arrDependencias = $this->getDependencias($post);

		//preenche transacao
		$transacao->exchangeArray($post);
		$transacao->setInstituicao($arrDependencias['instituicao']);
		$transacao->setDonativo($arrDependencias['donativo']);
		$transacao->setPessoa($arrDependencias['pessoa']);

		if($transacao->getId()){
			$this->transacaoDao->updateEntity($transacao);
		}else{
			//var_dump($transacao);die;
			$this->transacaoDao->salvar($transacao);
		}
		if(!empty($post['mensagem'])){
			$this->salvarMensagem($arrDependencias,$post);
		}
	}

	public function getDependencias($post){

		$arrObjsDependencia = [];
		$arrObjsDependencia['instituicao'] = $this->transacaoDao->findById($post['idInstituicao'], 'Application\Entity\Instituicao');
		$arrObjsDependencia['pessoa'] = $this->transacaoDao->findById($post['idPessoa'], 'Application\Entity\Pessoa');
		$arrObjsDependencia['donativo'] = $this->transacaoDao->findById($post['idDonativo'], 'Application\Entity\Donativos');

		return $arrObjsDependencia;
	}

	public function salvarMensagem($arrDependencias, $post){

		$mensagem = new Mensagem();
		$arrDependencias['idMensagem'] = $post['idMensagem'];
		$arrDependencias['mensagem'] = $post['mensagem'];
		$arrDependencias['transacao'] = $this->transacaoDao->findById($post['idTransacao'], $this->transacaoDao->getEntity());

		//verifica o perfil do remetente para persistir
		$perfil = $this->transacaoDao->validaPerfil($this->getUserLogado());
		if($perfil == self::PESSOA){
			$arrDependencias['idRemetente'] = $arrDependencias['pessoa']->getId();
		}else{
			$arrDependencias['idRemetente'] = $arrDependencias['instituicao']->getId();
		}

		//data e hora da mensagem
		$data = new \DateTime('now');
		$arrDependencias['dataEnvioMensagem'] = $data->format('Y-m-d h:m:s');
		$mensagem->exchangeArray($arrDependencias);

		$this->transacaoDao->salvar($mensagem);
	}

	public function getTransacaoPorPessoaeDonativo($idpessoa, $iddonativo){
		return $this->transacaoDao->findTransacaoPorDonativosePessoa($idpessoa, $iddonativo);
	}

	public function getTransacaoPorPessoa($idpessoa){
		//$objTransacao = $this->transacaoDao->findTransacaoPorPessoa($idpessoa);
		return $this->transacaoDao->findTransacaoPorPessoa($idpessoa);
		//return $this->bindTransacao($objTransacao);
	}

	// n usado por enquanto
	public function bindTransacao($objTransacao){
		$arrTransacao = [];
		foreach($objTransacao as $key=>$transacao){

			if($transacao instanceof Transacao) {
				if ($transacao != null) {

					$arrTransacao[$key]['id'] = $transacao->getId();
					$arrTransacao[$key]['idInstituicao'] = $transacao->getInstituicao()->getId();
					$arrTransacao[$key]['nomeFantasia'] = $transacao->getInstituicao()->getNomeFantasia();
					$arrTransacao[$key]['fotoInstituicao'] = $transacao->getInstituicao()->getFoto();
					$arrTransacao[$key]['dataTransacao'] = $transacao->getDataTransacao();
					$arrTransacao[$key]['titulo'] = $transacao->getDonativo()->getTitulo();
					$arrTransacao[$key]['descricao'] = $transacao->getDonativo()->getDescricao();
					$arrTransacao[$key]['quantidadeOfertada'] = $transacao->getDonativo()->getQuantidade();

				}
			}
		}
		return $arrTransacao;
	}

	public function getMensagensTransacao($transacaoID){
		return $this->transacaoDao->findMensagensTransacao($transacaoID);
	}


}