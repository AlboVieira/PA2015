<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Doacao\Controller;

use Application\Entity\TesteAnexo;
use Application\Entity\Transacao;
use Components\MVC\Controller\AbstractDoctrineCrudController;
use Doacao\Form\TransacaoForm;
use Doacao\Service\DonativoService;
use Doacao\Service\PessoaService;
use Doacao\Service\TransacaoService;
use Zend\View\Model\ViewModel;

class TransacaoController extends AbstractDoctrineCrudController
{
    private $transacaoService;

    public function __construct(){
        if(!$this->transacaoService){
            $this->transacaoService = new TransacaoService();
        }
    }

    public function indexAction(){
        return new ViewModel();
    }

    public function novaTransacaoAction(){
        $this->layout()->setTemplate('layout/layout_modal');

        $post = $this->getRequest()->getPost();

        $donativoService = new DonativoService();
        $pessoaService = new PessoaService();
        $donativos = $donativoService->getDonativoById($post['idDonativo']);

        $formTransacao = new TransacaoForm();
        //seta manualmente os campos hidden
        if(!empty($post['quantidadeOferecida'])){
            $transacao = new Transacao();
            $formTransacao->setInputFilter($transacao->getInputFilter());
            $formTransacao->setData($post);
            if($formTransacao->isValid()){
                $this->transacaoService->salvar($post,$transacao);
            }
        }else{
            $data = new \DateTime('now');
            $formTransacao->get('idDonativo')->setValue($donativos->getId());
            $formTransacao->get('idInstituicao')->setValue($donativos->getInstituicao()->getId());
            $formTransacao->get('idPessoa')->setValue($pessoaService->getObjPessoa()->getId());
            $formTransacao->get('dataTransacao')->setValue($data->format('Y-m-d'));
        }

        return new ViewModel(
            array(
                'form' => $formTransacao,
                'donativo' =>  $donativos
            )
        );
    }


}
