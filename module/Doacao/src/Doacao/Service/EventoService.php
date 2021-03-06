<?php

namespace Doacao\Service;

use Application\Entity\Evento;
use Application\Service\AbstractService;
use Doacao\Dao\EventoDao;
use Doacao\Dao\PessoaDao;

//TODO retirar metodos de evento e instituicoes e passar para services especificos

class EventoService extends AbstractService{

    const QTDREGISTRO = 0;
    private $eventoDAO;

    public function __construct(){
        $this->eventoDAO = new EventoDao();
    }

    /**
     * @return array
     */
    public function getEventosInstituicoes(){
        $evento = null;
        if($this->getObjPessoa()){
            $objEvento = $this->eventoDAO->selectEventosInstituicao($this->getObjPessoa()->getId());
            $evento = $this->bindEvento($objEvento);
        }
        return $evento;
    }

    /**
     * temporario
     * @return array
     */
    public function getObjPessoa(){
        $pessoaDao = new PessoaDao();
        $usuario = $this->getUserLogado();
        return $pessoaDao->selectPorUsuario($usuario);
    }

    /**
     * @param $objEvento
     * @return array
     */
    public function bindEvento($objEvento){
        $arrEvento = [];
        foreach($objEvento as $key=>$evento){
            // necessario pois o retorno esta trazendo as instituicoes tbm
            if($evento instanceof Evento){
                $arrEvento[$key]['id'] = $evento->getId();
                $arrEvento[$key]['idInstituicao'] = $evento->getIdInstituicao()->getId();
                $arrEvento[$key]['nomeFantasia'] = $evento->getIdInstituicao()->getNomeFantasia();
                $arrEvento[$key]['fotoInstituicao'] = $evento->getIdInstituicao()->getFoto();
                $arrEvento[$key]['descEvento'] = $evento->getDescEvento();
                $arrEvento[$key]['siteEvento'] = $evento->getSiteEvento();
                $arrEvento[$key]['objetivos'] = $evento->getObjetivos();
                $arrEvento[$key]['tituloEvento'] = $evento->getTituloEvento();
                $arrEvento[$key]['dataInicio'] =  $this->dateToString($evento->getDataInicio());
                $arrEvento[$key]['dataFim'] = $this->dateToString($evento->getDataFim());
                $arrEvento[$key]['imagem1'] = $evento->getImagem1();
                $arrEvento[$key]['imagem2'] = $evento->getImagem2();
                $arrEvento[$key]['imagem3'] = $evento->getImagem3();
            }
        }

        return $arrEvento;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function dateToString($data){
        return $data->format('d/m/Y');
    }

    public function getEventoPorID($id){
        //$objEvento = $this->eventoDAO->findById($id, $this->eventoDAO->getEntity());
        $objEvento = $this->eventoDAO->findEventoByID($id);
        return $this->bindEvento($objEvento);
    }

    /**
     * @return array
     */
    public function getEventosInstituicoesRecentes(){
        $evento = null;
        if($this->getObjPessoa()){
            $objEvento = $this->eventoDAO->selectEventosInstituicaoRecente($this->getObjPessoa()->getId());
            $evento = $this->bindEvento($objEvento);
        }
        return $evento;
    }

    /**
     * @param $termo
     * @return array
     */
    public function getEventosComFiltro($termo){
        $objEvento = $this->eventoDAO->selectEventosInstituicaoComFiltro($termo);
        return $this->bindEvento($objEvento);
    }
}