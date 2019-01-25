<?php

    //classe dashboard
    class Dashboard {

        public $data_inicio;
        public $data_fim;
        public $numeroVendas;
        public $totalVendas;
        public $clientesAtivos;
        public $clientesInativos;
        public $totalReclamacoes;
        public $totalElogios;
        public $totalSugestoes;
        public $totalDespesas;


        public function __get($atributo){
            return $this->$atributo;
        }


        public function __set($atributo, $valor){
            $this->$atributo = $valor;
            return $this;
        }

    }


    class Conexao {

        private $host = 'localhost';
        private $dbname = 'dashboard';
        private $user = 'root';
        private $pass = '';

        public function conectar(){
            try{
                $conexao = new PDO(
                    "mysql:host=$this->host;dbname=$this->dbname",
                    "$this->user",
                    "$this->pass"
                );

                //
                $conexao->exec('set charset set utf8');

                return $conexao;


            } catch (PDOException $e) {
                echo '<p>Problema na conexao com a base de dados: ' . $e->getMessage() . '</p>';
            }
        }
    }

    class Bd {
        private $conexao;
        private $dashboard;

        public function __construct(Conexao $conexao, Dashboard $dashboard){
            $this->conexao = $conexao->conectar();
            $this->dashboard = $dashboard;
        }

        public function getNumeroVendas() {

            $query = '
                select 
                    count(*) as numero_vendas 
                from 
                    tb_vendas 
                where 
                    data_venda BETWEEN :data_inicio AND :data_fim';

            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
            $stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_OBJ)->numero_vendas;
        }

        public function getTotalVendas() {

            $query = '
                select 
                    sum(total) as total_vendas 
                from 
                    tb_vendas 
                where 
                    data_venda BETWEEN :data_inicio AND :data_fim';

            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
            $stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_OBJ)->total_vendas;
        }

        public function getClientesAtivos() {

            $query = '
                SELECT 
                    COUNT(*) as clientes_ativos 
                FROM 
                    `tb_clientes` 
                WHERE cliente_ativo = 1
            ';

            $stmt = $this->conexao->prepare($query);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_OBJ)->clientes_ativos;
        }

        public function getClientesInativos() {

            $query = '
                SELECT 
                    COUNT(*) as clientes_inativos 
                FROM 
                    `tb_clientes` 
                WHERE cliente_ativo = 0
            ';

            $stmt = $this->conexao->prepare($query);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_OBJ)->clientes_inativos;
        }

        public function getTotalReclamacoes() {

            $query = '
                SELECT 
                    COUNT(*) AS total_reclamacoes
                FROM 
                    `tb_contatos` 
                WHERE tipo_contato = 3
            ';

            $stmt = $this->conexao->prepare($query);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_OBJ)->total_reclamacoes;
        }

        
        public function getTotalElogios() {

            $query = '
                SELECT 
                    COUNT(*) AS total_elogios
                FROM 
                    `tb_contatos` 
                WHERE tipo_contato = 1
            ';

            $stmt = $this->conexao->prepare($query);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_OBJ)->total_elogios;
        }

        
        public function getTotalSugestoes() {

            $query = '
                SELECT 
                    COUNT(*) AS total_sugestoes
                FROM 
                    `tb_contatos` 
                WHERE tipo_contato = 1
            ';

            $stmt = $this->conexao->prepare($query);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_OBJ)->total_sugestoes;
        }


        public function getTotalDespesas() {

            $query = '
                SELECT 
                    SUM(total) AS total_despesas
                FROM 
                    `tb_despesas` 
                WHERE data_despesa BETWEEN :data_inicio AND :data_fim
            ';

            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
            $stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_OBJ)->total_despesas;
        }


        

    }



    $dashboard = new Dashboard();
    $conexao = new Conexao();

    $competencia = explode('-', $_GET['competencia']);
    $ano = $competencia[0];
    $mes = $competencia[1];
    $dias_do_mes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);


    $dashboard->__set('data_inicio', $ano.'-'.$mes.'-01');
    $dashboard->__set('data_fim', $ano.'-'.$mes.'-'.$dias_do_mes);

    //como é que ele consegue ir buscar a info da data sendo que o atributo está private???
    

    //echo '<br>';
    //echo $dashboard->data_inicio;
    //echo '</br>';
    


    $bd = new Bd($conexao, $dashboard);

    $dashboard->__set('numeroVendas', $bd->getNumeroVendas());
    $dashboard->__set('totalVendas', $bd->getTotalVendas());
    $dashboard->__set('clientesAtivos', $bd->getClientesAtivos());
    $dashboard->__set('clientesInativos', $bd->getClientesInativos());
    $dashboard->__set('totalReclamacoes', $bd->getTotalReclamacoes());
    $dashboard->__set('totalElogios', $bd->getTotalElogios());
    $dashboard->__set('totalSugestoes', $bd->getTotalSugestoes());
    $dashboard->__set('totalDespesas', $bd->getTotalDespesas());

    echo json_encode($dashboard);


?>