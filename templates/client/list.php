<?php include __DIR__ . '/../layouts/header.php' ?>

<div id="content" style="display:none;">
    <h1>Meus Clientes</h1>
    <button type="button">Novo</button>
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
<div id="client-dialog" title="Criar novo cliente">
	    <form>
	        <input type="hidden" name="id">
	        <div class="form-group">
	            <label for="name">Nome</label>
	            <input type="text" class="form-control" id="name" name="name" placeholder="Nome" required>
	        </div>
	        <div class="form-group">
	            <label for="name">CPF</label>
	            <input type="text" class="form-control" id="cpf" name="cpf" placeholder="CPF" required>
	        </div>
	        <div class="form-group">
	            <label for="email">E-mail</label>
	            <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
	        </div>
	        <div class="form-group">
	            <label for="phone">Telefone</label>
	            <input type="text" class="form-control" id="phone" name="phone" required>
	        </div>
	        <div class="form-group">
	            <label for="birthday">Aniversário</label>
	            <input type="text" class="form-control" id="birthday" name="birthday" required>
	        </div>
	        <div class="form-group">
	            <label for="marital_status">Estado Civil</label>
	            <select class="form-control" id="marital_status" name="marital_status" required>
	                <option value="1">Solteiro</option>
	                <option value="2">Casado</option>
	                <option value="3">Divorciado</option>
	            </select>
	        </div>
	
	        <input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
	    </form>
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

            var btnEdit = $('<button/>').attr('type', 'button').html('Editar');

            for( let key in data){
                let tr = $('<tr/>'),
                    row = data[key],
                    nome = $('<td/>').html(row.name),
                    cpf = $('<td/>').html($.formatCpf(row.cpf)), 
                    email = $('<td/>').html(row.email), 
                    tel = $('<td/>').html($.formatPhone(row.phone)), 
                    niver = $('<td/>').html($.formatDate(row.birthday)),
                    ecivil = $('<td/>').html($.formatMaritalStatus(row.marital_status))

                    let actions = $('<td/>');
                    actions.append(btnEdit.clone());

                    tr.data('client-id',row.id);
                    

                    tr.append(nome)
                       .append(cpf)
                       .append(email)
                       .append(tel)
                       .append(niver)
                       .append(ecivil)
                       .append(actions);

                    tbody.append(tr);
            }
                tbody.find('button:contains(Editar)').button({
                    icon:'ui.icon-pencil'
                })
                     .click(function (){
                         var button = $(this),
                             tr = button.closest('tr'),
                             id = tr.data('client-id');
                             loadEditForm(id);
                     })
        })
    }

    function loadEditForm(id){
        $.get('/api/clients?id='+id, function( data){
            var client = data[0];
            $('input[name=id]').val(client.id);
            $('input[name=name]').val(client.name);
            $('input[name=email]').val(client.email);
            $('input[name=cpf]').val(client.cpf);
            $('input[name=phone]').val(client.phone);
            $('input[name=birthday]').val(client.birthday);
            $('input[name=birthday]').val(client.birthday);
            $('select[name=marital_status]').val(client.marital_status);
            dialogSave.dialog('open');
        });
    }
    function saveClient(){
        let data  = $('#client-dialog>form').serializeObject();
        let id = $('input[name=id]').val();
        let url;
            if( id == ""){
                url = '/api/clients/store';
                delete data.id;
            }else{
                url = '/api/clients/update';
            }
        $.post(url,data)
            .done( function(){
                dialogSave.dialog('close');
                listClients();
            });

    }
    let dialogSave;
    function init(){
        dialogSave = $('#client-dialog')
                                .dialog({
                                    autoOpen: false,
                                    height: 400,
                                    width : 400,
                                    modal: true,
                                    buttons:{
                                        "Criar Cliente": saveClient,
                                        "Cancelar": function(){
                                                //$(this).dialog('close');
                                                dialogSave.dialog('close');
                                        }
                                    },
                                    close: function(){
                                           $('#client-dialog>form')[0].reset();  
                                           $('#client-dialog>form').find('input[type=hidden]').val("");
                                    }
                                });
        $('#client-dialog>form').on('submit', function(event){
            event.preventDefault();
            addClient();
        })
        $('#content>button').button({
            icon: 'ui-icon-plus'
        }).click(function (){
            dialogSave.dialog('open');
        });
        $('#content').show('slide');
        listClients();
    }
    $(document).ready( function (){
        init();
        
    })
    
</script>

<?php include __DIR__ . '/../layouts/end.php' ?>
