<?php
include "connection/connect.php";
$id = "";
$nome = "";
$cpf = "";
$data_nascimento = "";
$email = "";

/* codigo erro
* 0 = correto
* 1 = erro no cliente
* 2 = erro no endereço
*/
$erro = [
    "codigo" => -1,
    "descricao" => "",
];

function datepicker_banco($data) {
    $dia = substr($data, 0, 2);
    $mes = substr($data, 3, 2);
    $ano = substr($data, 6);
    $data = $ano . "-" . $mes . "-" . $dia;
    return $data;
}

function banco_datepicker($data) {
    $ano = substr($data, 0, 4);
    $mes = substr($data, 5, 2);
    $dia = substr($data, 8);
    $data = $dia . "-" . $mes . "-" . $ano;
    return $data;
}

if(isset($_GET['id'])) {
    $btn = [
        "tipo" => "btn_alterar",
        "descricao" => "Alterar cliente",
    ];

    $id = $_GET['id'];
    $sql = "SELECT nome as 'nome', email as 'email', cpf as 'cpf', data_nascimento as 'data_nascimento' 
            FROM cliente WHERE id = '{$id}'";

    $query = $conn->query($sql);
    while($row = $query->fetch_array()) {
        $nome = $row['nome'];
        $email = $row['email'];
        $cpf = $row['cpf'];
        $data_nascimento = banco_datepicker($row['data_nascimento']);

    }

} else {
    $btn = [
        "tipo" => "btn_inserir",
        "descricao" => "Inserir cliente",
    ];
    $cep = "";
    $logradouro = "";
    $numero = "";
    $bairro = "";
    $cidade = "";
    $uf = "";
 
}

if (isset($_POST["btn_inserir"])) {
    $id              = NULL;
    $nome            = $_POST['nome'];
    $cpf             = $_POST['cpf'];
    $data_nascimento_convertido = datepicker_banco($_POST['data_nascimento']);
    $data_nascimento = $_POST['data_nascimento'];
    $email           = $_POST['email'];
    $cep             = $_POST['cep'];
    $logradouro      = $_POST['logradouro'];
    $numero          = $_POST['numero'];
    $bairro          = $_POST['bairro'];
    $cidade          = $_POST['cidade'];
    $uf              = $_POST['uf'];

    $select = "SELECT COUNT(*) as 'existe_cliente' FROM cliente WHERE cpf = '{$cpf}';";
    $query = $conn->query($select);

    while ($row = $query->fetch_array()) $existe_cliente = $row['existe_cliente'];

    if ($existe_cliente > 0) {
        $erro['codigo'] = 1;
        $erro['descricao'] = "O cliente ja existe no banco de dados";
    }
    else {
        $sql = "INSERT INTO cliente(nome, cpf, data_nascimento, email)
				VALUES('{$nome}', '{$cpf}', '{$data_nascimento_convertido}', '{$email}');";

		if (!mysqli_query($conn, $sql)) {
            $erro['codigo'] = 1;
            $erro['descricao'] = mysqli_error($conn);
        }
		else {
            $erro['descricao'] = "Usuário inserido";

            // Pegando id do cliente
            $select = "SELECT id as 'id' FROM cliente WHERE cpf = '{$cpf}';";
            $query = $conn->query($select);

            while ($row = $query->fetch_array()) $id = $row['id'];

            // Inserindo endereço
            $sql = "INSERT INTO endereco(id_cliente, logradouro, numero, bairro, cidade, uf, cep)
				VALUES('{$id}', '{$logradouro}', '{$numero}', '{$bairro}', '{$cidade}', '{$uf}', '{$cep}');";

            if (!mysqli_query($conn, $sql)) {
                $erro['codigo'] = 2;
                $erro['descricao'] = $erro['descricao']  . "</br>Erro no endereço: ". mysqli_error($conn);
            } else {
                $erro['codigo'] = 0;
                $nome            = "";
                $cpf             = "";
                $data_nascimento_convertido = "";
                $data_nascimento = "";
                $email           = "";
                $cep             = "";
                $logradouro      = "";
                $numero          = "";
                $bairro          = "";
                $cidade          = "";
                $uf              = "";
            };
        }
    } 
    
}



if(isset($_POST["btn_alterar"])) {
    $id_cliente = $_GET['id'];
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $data_nascimento = datepicker_banco( $_POST['data_nascimento']);
    $email = $_POST['email'];

    $sql = "UPDATE cliente SET nome = '{$nome}', cpf = '{$cpf}', 
            email = '{$email}', data_nascimento = '{$data_nascimento}'
            WHERE id = '{$id_cliente}';";

    if (!mysqli_query($conn, $sql)) {
        $erro['codigo'] = 1;
        $erro['descricao'] = mysqli_error($conn);
    } 
    else $erro['codigo'] = 0;
        

}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD | ABL Prime</title>

    <link rel="icon" href="img/icon.png">

    <!--Bootstrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">

    <link href="css/style.css" rel="stylesheet">

    <!--Jquery UI-->
    <link href="js/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet">

</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col">

                <div class="container mt-3">
                    <?php
                    if($erro['codigo'] > -1){
                        if ($erro['codigo'] == 0) {?>
                            <div class="alert alert-success" role="alert">
                                <h4>Sucesso!</h4>
                                <?php if (isset($_POST["btn_alterar"])) { ?>
                                    <p>Alterado com sucesso!</p>
                                <?php } else { ?>
                                    <p>Cadastrado com sucesso!</p>
                                <?php } ?>
                            </div>
                        <?php }
                        if ($erro['codigo'] > 0) {
                            ?>
                            <div class="alert alert-danger" role="alert">
                                <h4>Erro!</h4>
                                <p>Erro: <?=$erro['descricao']?></p>
                            </div>
                        <?php }
                    } ?>
                    <div class="card">
                    
                        <div class="card-header bg-primary">
                            <h2 class="card-title text-white"><?=$btn['descricao']?></h2>
                        </div>
                        <form method="POST">
                            <div class="card-body">
                        
                                <div class="form-group">
                                    <label>Nome</label>
                                    <input type="text" name="nome" maxlength="200" class="form-control" value="<?= $nome?>">
                                </div>
                                <div class="row">
                                    <div class="form-group col-12 col-md-6">
                                        <label>CPF</label>
                                        <input type="text" name="cpf" id="cpf" maxlength="14" class="form-control" value="<?= $cpf?>" required>
                                    </div>
                                    <div class="form-group col-12 col-md-6">
                                        <label>Data de nascimento</label>
                                        <input type="text" name="data_nascimento" maxlength="10" id="data" class="form-control" value="<?= $data_nascimento?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" name="email" maxlength="200" class="form-control" value="<?= $email?>">
                                </div>
                                <?php
                                if ($btn['tipo'] == "btn_inserir"){ ?>
                                    <div class="row">
                                        <div class="form-group col-12 col-md-3">
                                            <label>CEP</label>
                                            <input type="text" name="cep" id="cep" class="form-control" value="<?= $cep?>">
                                        </div>
                                        <div class="form-group col-12 col-md-9">
                                            <label>Logradouro</label>
                                            <input type="text" name="logradouro" id="logradouro" class="form-control" value="<?= $logradouro?>" readonly>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-12 col-md-2 col-sm-4">
                                        <label>Número</label>
                                        <input type="text" name="numero" class="form-control" value="<?= $numero?>">
                                        </div>
                                        <div class="form-group col-12 col-md-4 col-sm-8">
                                        <label>Bairro</label>
                                        <input type="text" name="bairro" id="bairro" class="form-control" value="<?= $bairro?>" readonly>
                                        </div>
                                        <div class="form-group col-12 col-md-4 col-sm-8">
                                        <label>Cidade</label>
                                        <input type="text" name="cidade" id="cidade" class="form-control" value="<?= $cidade?>" readonly>
                                        </div>
                                        <div class="form-group col-12 col-md-2 col-sm-4">
                                        <label>Estado</label>
                                        <input type="text" name="uf" id="uf" class="form-control" placeholder="" aria-describedby="helpId" value="<?= $uf?>" readonly>
                                        </div>
                                    </div>
                                <?php } else { ?>
                                    <input name="id" value="<?=$id?>" hidden>
                                <?php } ?>
                                
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary my-2" name="<?=$btn['tipo']?>"><i class="fa fa-pencil-alt" aria-hidden="true"></i> <?=$btn['descricao']?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Jquery-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="js/jquery-ui-1.12.1/jquery-ui.min.js"></script>

    <!--Input Mask-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.4-beta.33/bindings/inputmask.binding.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.4-beta.33/inputmask.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.4-beta.33/jquery.inputmask.min.js"></script>

    <!--bootstrap-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>

    <!--fontAwesome-->
    <script src="https://kit.fontawesome.com/bdca009afd.js" crossorigin="anonymous"></script>

    <!-- Scripts -->
    <script>
    var anoAtual = new Date().getFullYear();
    $(document).ready(function(){
       
        $('#data').datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd/mm/yy',
            dayNames: [ "Domingo", "Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sábado" ],
            dayNamesMin: [ "Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sab" ],
            dayNamesShort: [ "Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sab" ],
            monthNames: [ "Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro" ],
            monthNamesShort: [ "Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez" ],
            showMonthAfterYear: true,
            yearRange: "1910:" + anoAtual,
        });
        
        $('#data').inputmask('99/99/9999', { 'placeholder': '01/01/2000'});
        $('#cpf').inputmask('999.999.999-99', { 'placeholder': '000.000.000-00'} );
        $('#cep').inputmask('99999-999', { 'placeholder': '00000-000'} );

        function limpa_formulário_cep() {
            $("#logradouro").val("");
            $("#bairro").val("");
            $("#cidade").val("");
            $("#uf").val("");
        }
            
        $("#cep").blur(function() {

            var cep = $(this).val().replace(/\D/g, '');

            if (cep != "") {

                var validacep = /^[0-9]{8}$/;

                if(validacep.test(cep)) {

                    $("#logradouro").val("Carregando...");
                    $("#bairro").val("Carregando...");
                    $("#cidade").val("Carregando...");
                    $("#uf").val("Carregando...");

                    $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {

                        if (!("erro" in dados)) {
                            $("#logradouro").val(dados.logradouro);
                            $("#bairro").val(dados.bairro);
                            $("#cidade").val(dados.localidade);
                            $("#uf").val(dados.uf);
                            $("#ibge").val(dados.ibge);
                        } 
                        else {
                            limpa_formulário_cep();
                            alert("CEP não encontrado.");
                        }
                    });
                }
                else {
                    limpa_formulário_cep();
                    alert("Formato de CEP inválido.");
                }
            }
            else {
                limpa_formulário_cep();
            }
        });
    });
    </script>
</body>

</html>