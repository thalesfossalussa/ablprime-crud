<?php
include "connection/connect.php";
$id_endereco = "";
$nome = "";
$cep = "";
$logradouro = "";
$numero_endereco = "";
$bairro = "";
$cidade = "";
$uf = "";
$id_cliente = $_GET['id_cliente'];

$erro = [
    "codigo" => -1,
    "descricao" => "",
];
$btn = "btn_inserir";
if (isset($_GET['id_endereco']) && $_GET['id_endereco'] != "") {
    $id_endereco     = $_GET['id_endereco'];
    $id_cliente      = $_GET['id_cliente'];

    $sql = "SELECT e.id as 'id_endereco', e.logradouro as 'logradouro', e.numero as 'numero_endereco', 
    e.bairro as 'bairro', e.cidade as 'cidade', e.uf as 'uf', e.cep as 'cep' 
    FROM endereco e, cliente c 
    WHERE c.id = e.id_cliente AND e.id_cliente = '{$_GET['id_cliente']}' AND e.id = '{$_GET['id_endereco']}';";
    $query = $conn->query($sql);

    while ($row = $query->fetch_array()) {
        $id_cliente = $_GET['id_cliente'];
        $id_endereco = $row['id_endereco'];
        $logradouro = $row['logradouro'];
        $numero_endereco = $row['numero_endereco'];
        $bairro = $row['bairro'];
        $cidade = $row['cidade'];
        $uf = $row['uf'];
        $cep = $row['cep'];
    }
    $btn = "btn_alterar";

}

$sql = "SELECT nome as 'nome' FROM cliente WHERE id = '{$_GET['id_cliente']}';";
$query = $conn->query($sql);
while ($row = $query->fetch_array()) $nome = $row['nome'];

if (isset($_POST["btn_inserir"])) {
    $id_cliente      = $_GET['id_cliente'];
    $cep             = $_POST['cep'];
    $logradouro      = $_POST['logradouro'];
    $numero_endereco = $_POST['numero_endereco'];
    $bairro          = $_POST['bairro'];
    $cidade          = $_POST['cidade'];
    $uf              = $_POST['uf'];

    $sql = "INSERT INTO endereco(id_cliente, logradouro, numero, bairro, cidade, uf, cep)
            VALUES('{$id_cliente}', '{$logradouro}', '{$numero_endereco}', '{$bairro}', '{$cidade}', '{$uf}', '{$cep}');";

    if (!mysqli_query($conn, $sql)) {
        $erro['codigo'] = 1;
        $erro['descricao'] = mysqli_error($conn);
    }
    else {
        $erro['codigo'] = 0;
        $cep = "";
        $logradouro = "";
        $numero_endereco = "";
        $bairro = "";
        $cidade = "";
        $uf = "";
        $id_cliente = $_GET['id_cliente'];
    };
     
}

if (isset($_POST["btn_alterar"])) {
    $id_endereco     = $_GET['id_endereco'];
    $id_cliente      = $_GET['id_cliente'];
    $cep             = $_POST['cep'];
    $logradouro      = $_POST['logradouro'];
    $numero_endereco = $_POST['numero_endereco'];
    $bairro          = $_POST['bairro'];
    $cidade          = $_POST['cidade'];
    $uf              = $_POST['uf'];

    $sql = "UPDATE endereco SET logradouro = '{$logradouro}', numero = '{$numero_endereco}', 
            bairro = '{$bairro}', cidade = '{$cidade}', uf = '{$uf}', cep = '{$cep}' 
            WHERE id = '{$id_endereco}';";

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
                            <h2 class="card-title text-white">
                            <?php 
                            if($btn == "btn_inserir") echo "Inserir";
                            else if($btn == "btn_alterar") echo "Alterar";
                            ?>
                            endereço de <?=$nome;?></h2>
                        </div>
                        <form method="POST">
                            <div class="card-body">

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
                                        <input type="text" name="numero_endereco" class="form-control" value="<?= $numero_endereco?>">
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
                                        <input type="text" name="uf" id="uf" class="form-control" value="<?= $uf?>" readonly>
                                    </div>
                                </div>

                            </div>
                            <div class="card-footer">
                                <input type="text" name="id_cliente" value="<?=$id_cliente?>" hidden>
                                <input type="text" name="id_endereco" value="<?=$id_endereco?>" hidden>
                                <button type="submit" class="btn btn-primary my-2" name="<?=$btn;?>">
                                    <i class="fa fa-pencil-alt" aria-hidden="true"></i> 
                                    <?php 
                                    if($btn == "btn_inserir") echo "Inserir";
                                    else if($btn == "btn_alterar") echo "Alterar";
                                    ?>

                                </button>
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
    $(document).ready(function(){
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