<?php include __DIR__ . '/../layouts/header.php' ?>

<div id="content" style="display:none;">
    <h1>Meus Clientes</h1>
    <table id="table" class="table">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Cpf</th>
                <th>Email</th>
                <th>Tel</th>
                <th>Niver</th>
                <th>E. Civil</th>
                <th>Acoes</th>
            </tr>
        </thead>
        <tbody>
        
        </tbody>
    
    </table>
</div>

<?php include __DIR__ . '/../layouts/footer.php' ?>
<script>
    function listClients(){
        let loading ='<tr><td colspan="6">Carregando ... </td></tr>';
        let empty ='<tr><td colspan="6">Nenhum registro encontrado ... </td></tr>';
        let tbody = $('#table>tbody');
        tbody.html(loading);
        $.get('/api/clients', function( data){
            data.length ? tbody.empty():tbody.html(empty);
            for( let key in data){
                let tr = $('<tr/>'),
                    row = data[key],
                    nome = $('<td/>').html(row.name),
                    cpf = $('<td/>').html(row.cpf), 
                    email = $('<td/>').html(row.email), 
                    tel = $('<td/>').html(row.phone), 
                    niver = $('<td/>').html(row.birthday),
                    ecivil = $('<td/>').html(row.marital_status)
                    tr.append(nome)
                       .append(cpf)
                       .append(email)
                       .append(tel)
                       .append(niver)
                       .append(ecivil);
                    tbody.append(tr);
            }

        })
    }
    function init(){
        $('#content').show('slide');
    }
    $(document).ready( function (){
        init();
        listClients();
    })
    
</script>

<?php include __DIR__ . '/../layouts/end.php' ?>
