<?php
include "connection/connect.php";

function banco_datepicker($data) {
    $ano = substr($data, 0, 4);
    $mes = substr($data, 5, 2);
    $dia = substr($data, 8);
    $data = $dia . "/" . $mes . "/" . $ano;
    return $data;
}

$erro = [
    "codigo" => -1,
    "descricao" => "",
];

if(isset($_POST['btn_excluir'])) {
    $sql = "DELETE FROM cliente WHERE id = '{$_POST['id_excluir']}';";
    if(mysqli_query($conn, $sql)) $erro['codigo'] = 0;
    else {
        $erro['codigo'] = 1;
        $erro['descricao'] = mysqli_error($conn);
    }
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
    
    <!--DataTables-->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.24/datatables.min.css"/>

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
                                <h4>Endereço excluído com sucesso!</h4>
                            </div>
                        <?php }
                        if ($erro['codigo'] > 0) {
                            ?>
                            <div class="alert alert-danger" role="alert">
                                <h4>Erro ao excluir!</h4>
                                <p>Erro: <?=$erro['descricao']?></p>
                            </div>
                        <?php }
                    } ?>
                    <div class="card">
                    
                        <div class="card-header bg-primary">
                            <h3 class="card-title text-white">Clientes</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive ">
                                <table id="table" class="cell-border hover stripe">
                                    <thead>
                                        <tr>
                                            <th scope="col">Nome</th>
                                            <th scope="col">CPF</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Nascimento</th>
                                            <th scope="col">Endereços</th>
                                            <th scope="col">Excluir</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "SELECT id as 'id', nome as 'nome', cpf as 'cpf', email as 'email', data_nascimento as 'data_nascimento' FROM cliente;";
                                        $query = $conn->query($sql);

                                        while ($row = $query->fetch_array()) {
                                            $id = $row['id'];
                                            $nome = $row['nome'];
                                            $cpf = $row['cpf'];
                                            $email = $row['email'];
                                            $data_nascimento = banco_datepicker($row['data_nascimento']);

                                            $select_endereco = "SELECT COUNT(*) as 'numero_endereco' FROM endereco WHERE id_cliente = '{$id}'";
                                            $query_endereco = $conn->query($select_endereco);
                                            while ($row_endereco = $query_endereco->fetch_array()) $numero_endereco = $row_endereco['numero_endereco'];
                                            ?>
                                        <tr>
                                            <td>
                                                <a href="inserir_cliente.php?id=<?=$id?>"><?=$nome;?></a>
                                            </td>
                                            <td>
                                                <a href="inserir_cliente.php?id=<?=$id?>"><?=$cpf;?></a>
                                            </td>
                                            <td>
                                                <a href="inserir_cliente.php?id=<?=$id?>"><?=$email;?></a>
                                            </td>
                                            <td>
                                                <a href="inserir_cliente.php?id=<?=$id?>"><?=$data_nascimento;?></a>
                                            </td>

                                            <td>
                                                <a href="visualizar_endereco.php?id_cliente=<?=$id;?>">
                                                    <button class="btn btn-primary">
                                                        <i class="far fa-eye" aria-hidden="true"></i> Visualizar <?=$numero_endereco;?> endereços
                                                    </button>
                                                </a>
                                            </td>
                                            
                                            <td>
                                                <form method="POST">
                                                    <input type="text" name="id_excluir" value="<?=$id;?>" hidden>
                                                    <button class="btn" type="submit" name="btn_excluir"><i class="fa fa-times fa-2x text-danger" aria-hidden="true"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                            <?php
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.24/datatables.min.js"></script>
    <script>
    $(document).ready( function () {
        $('#table').DataTable({
            hover: true,
            "language": {
                "sEmptyTable": "Nenhum registro encontrado",
                "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
                "sInfoFiltered": "(Filtrados de _MAX_ registros)",
                "sInfoPostFix": "",
                "sInfoThousands": ".",
                "sLengthMenu": "Mostrar _MENU_ resultados por página",
                "sLoadingRecords": "Carregando...",
                "sProcessing": "Processando...",
                "sZeroRecords": "Nenhum registro encontrado",
                "sSearch": "Pesquisar: ",
                "oPaginate": {
                    "sNext": "Próximo",
                    "sPrevious": "Anterior",
                    "sFirst": "Primeiro",
                    "sLast": "Último"
                },
                "oAria": {
                    "sSortAscending": ": Ordenar colunas de forma ascendente",
                    "sSortDescending": ": Ordenar colunas de forma descendente"
                }
            }
        });
    } );
    </script>
    <script src="https://kit.fontawesome.com/bdca009afd.js" crossorigin="anonymous"></script>
</body>

</html>