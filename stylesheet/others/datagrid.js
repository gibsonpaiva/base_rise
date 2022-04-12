// Função do botão Editar
function Edit(btnID, pagPart){
    var ID = btnID.substring(7, btnID.lenght); // Variável para Identificação do ID do Item através de extração do id do Botão clicado
    
    // Identifica a linha da tabela
    if (pagPart === undefined) {
        var trObj = $('tr#'+ID); // Variável para resumo na identificação da linha da tabela. Localiza a TR em toda à página
    } else {
        var pagPart = $(pagPart); // Restringe a localização dos objetos em uma parte específica da página
        var trObj = pagPart.find($("[name='"+ID+"']")); // Localiza a TR, linha da tabela, em uma parte específica da página através do nome
    }

    // Inverte a exibição dos campos e botões
    trObj.find(".viewOnly").hide(); // Oculta os campos de visualização
    trObj.find(".editInput").show(); // Exibe os campos para Edição
    trObj.find(".btnEdit").hide(); // Oculta o botão Editar
    trObj.find(".btnDelete").hide(); // Oculta o botão Deletar
    trObj.find(".btnSave").show(); // Exibe o botão Salvar
    trObj.find(".btnCancel").show(); // Exibe o botão Cancelar
    trObj.find(".btnModal").hide(); // Oculta o botão Modal

    // Formata os Combobox - Select2
    trObj.find('.select2').select2();

    // Formata os Campos Numéricos
    //trObj.find(".editInput.Numeric").autoNumeric('init');
}

// Função do botão Deletar
function Delete(btnID, pagPart){
    var ID = btnID.substring(9, btnID.lenght); // Variável para Identificação do ID do Item através de extração do id do Botão clicado
    
    // Identifica a linha da tabela
    if (pagPart === undefined) {
        var trObj = $('tr#'+ID); // Variável para resumo na identificação da linha da tabela. Localiza a TR em toda à página
    } else {
        var pagPart = $(pagPart); // Restringe a localização dos objetos em uma parte específica da página
        var trObj = pagPart.find($("[name='"+ID+"']")); // Localiza a TR, linha da tabela, em uma parte específica da página através do nome
    }

    // Inverte a exibição dos botões Deletar pelo Confirma
    trObj.find(".btnDownload").hide(); // Oculta o botão Download
    trObj.find(".btnModal").hide(); // Oculta o botão Modal
    trObj.find(".btnEdit").hide(); // Oculta o botão Editar
    trObj.find(".btnDelete").hide(); // Oculta o botão Deletar
    trObj.find(".btnRemove").show(); // Exibe o botão Confirma
    trObj.find(".btnCancel").show(); // Exibe o botão Cancelar
}

// Função do botão Confirma Exclusão - Remove registro selecionado
function Remove(btnID, pagPart, postURL, idArea){
    var ID = btnID.substring(9, btnID.lenght); // Variável para Identificação do ID do Item através de extração do id do Botão clicado
    // Identifica a linha da tabela
    var pagPart = $(pagPart); // Restringe os objetos em uma parte específica da página
    var trObj = pagPart.find($("[name='"+ID+"']")); // Localiza a TR, linha da tabela, em uma parte específica da página através do nome
    // Prepara os Dados para serem postados
    var postData = '&id='+ID;
    if (idArea !== undefined) { postData = '&id='+ID+'&idArea='+idArea; } // Concatena o ID da Área se informado

    // Processa página em background para exclusão do item
    $.ajax({
        url: postURL,
        type:'POST',
        dataType: "json",
        data:'action=delete'+postData,
        success:function(response){                    
            if(response.status == 'ok'){ // Se exclusão bem sucedida remove a linha da tabela
                trObj.remove();
            }else{ // Se houver alguma falha na exclusão
                // Inverte a exibição dos botões Confirma pelo Deletar
                trObj.find(".btnConfirm").hide();
                trObj.find(".btnDelete").show();
                // Exibe mensagem de resposta
                alert(response.msg);
            }
        }
    });
}

// Função do botão Cancelar
function Cancel(btnID, pagPart){
    var ID = btnID.substring(9, btnID.lenght); // Variável para Identificação do ID da linha/item através de extração do id do Botão clicado

    // Identifica a linha da tabela
    if (pagPart === undefined) {
        var trObj = $('tr#'+ID); // Localiza a TR, linha da tabela, em toda à página
    } else {
        var pagPart = $(pagPart); // Restringe os objetos em uma parte específica da página
        var trObj = pagPart.find($("[name='"+ID+"']")); // Localiza a TR, linha da tabela, em uma parte específica da página através do nome
    }

    // Verifica se trata de um novo registro ou atualização de um existente
    if (ID === "NewRecord"){ // Se cancelando um novo cadastro - remove a linha
        if (pagPart === undefined) {
            $("#btnNew").show(); // Exibe o botão Novo independente da localização deste na página
        } else {
            pagPart.find($(".btnNew")).show(); // Exibe o botão Novo localizado em uma parte específica da página
        }
        // Ajusta exibição dos componentes na página
        trObj.remove(); // Remove a linha destinada ao novo registro
    } else { // Se cancelando uma edição
        // Ajusta exibição dos componentes na página
        trObj.find(".editInput").hide(); // Oculta os campos para Edição
        trObj.find(".select2-container").hide(); // Oculta os combobox - Select2
        trObj.find(".viewOnly").show(); // Exibe os campos de visualização
        trObj.find(".btnSave").hide(); // Oculta o botão Salvar
        trObj.find(".btnCancel").hide(); // Oculta o botão Cancelar
        trObj.find(".btnRemove").hide(); // Oculta o botão Confirma
        trObj.find(".btnDownload").show(); // Exibe o botão Download
        trObj.find(".btnModal").show(); // Exibe o botão Modal
        trObj.find(".btnEdit").show(); // Exibe o botão Editar
        trObj.find(".btnDelete").show(); // Exibe o botão Deletar
    }
}

// Função do botão Salvar
function Save(btnID, pagPart, postURL){
    var ID = btnID.substring(7, btnID.lenght); // Variável para Identificação do ID da linha/item através de extração do id do Botão clicado

    // Identifica a linha da tabela
    var pagPart = $(pagPart); // Restringe os objetos em uma parte específica da página
    var trObj = pagPart.find($("[name='"+ID+"']")); // Localiza a TR, linha da tabela, em uma parte específica da página através do nome

    // Prepara os valores informados para o POST
    var inputData = trObj.find('.editInput').serialize(); // Variável para os valores informados

    // Verifica se trata de um novo registro ou atualização de um existente
    if (ID === "NewRecord"){ // Se novo - Insere
        // Processa página em background para inclusão do item
        $.ajax({
            url:postURL,
            type:'POST',
            dataType: "json",
            data:'action=insert&'+inputData,
            success:function(response){
                if(response.status == 'ok'){ // Se query executada com sucesso
                    // Atualiza o texto dos campos de visualização
                    trObj.find(".viewAlways.id").text(response.data.id);
                    trObj.find(".viewOnly.Nome").text(response.data.nome);
                    // Inverte a exibição dos campos de Edição pelos de Visualização e dos botões Salvar pelo Editar
                    trObj.find(".editInput").hide(); // Oculta os campos de Edição
                    trObj.find(".select2-container").hide(); // Oculta os combobox - Select2
                    trObj.find(".viewOnly").show(); // Exibe os campos de visualização
                    document.getElementById(btnID).remove(); // Remove o botão Salvar (novo registro)
                    trObj.find(".btnSave").hide(); // Oculta o botão Salvar
                    trObj.find(".btnCancel").hide(); // Oculta o botão Cancelar
                    trObj.find(".btnEdit").show(); // Exibe o botão Editar
                    trObj.find(".btnDelete").show(); // Exibe o botão Deletar
                    trObj.find(".btnModal").show(); // Exibe o botão Modal
                    $("#btnNew").show(); // Exibe o botão Novo
                    // Atualiza o ID da Linha e sesus objetos
                    document.getElementById("NewRecord").setAttribute("name", response.data.id); // Atualiza o Nome da TR
                    document.getElementById("NewRecord").id = response.data.id; // Atualiza o ID da TR
                    document.getElementById("btnEdit").id = "btnEdit" + response.data.id; // Atualiza o ID do botão Editar
                    document.getElementById("btnSave").id = "btnSave" + response.data.id; // Atualiza o ID do botão Savar
                    document.getElementById("btnDelete").id = "btnDelete" + response.data.id; // Atualiza o ID do botão Deletar
                    document.getElementById("btnRemove").id = "btnRemove" + response.data.id; // Atualiza o ID do botão Remover
                    // Exibe mensagem de resposta
                    //alert(response.msg);
                }
            }
        });
    } else { // Se existente - Atualiza
        // Processo página em background para atualização do item
        $.ajax({
            url:postURL,
            type:'POST',
            dataType: "json",
            data:'action=update&id='+ID+'&'+inputData,
            success:function(response){
                if(response.status == 'ok'){ // Se query executada com sucesso
                    // Atualiza o texto dos campos de visualização
                    trObj.find(".viewOnly.Nome").text(response.data.nome);
                    // Inverte a exibição dos campos de Edição pelos de Visualização e dos botões Salvar pelo Editar
                    trObj.find(".editInput").hide(); // Oculta os campos para Edição
                    trObj.find(".select2-container").hide(); // Oculta os combobox - Select2
                    trObj.find(".viewOnly").show(); // Exibe os campos de visualização
                    trObj.find(".btnSave").hide(); // Oculta o botão Salvar
                    trObj.find(".btnCancel").hide(); // Oculta o botão Cancelar
                    trObj.find(".btnEdit").show(); // Exibe o botão Editar
                    trObj.find(".btnDelete").show(); // Exibe o botão Deletar
                    // Exibe mensagem de resposta
                    //alert(response.msg);
                }
            }
        });
    }
}

// Função de Ordenação por Coluna
function Sort(thObj, pagPart, showFunction) {
    // Identifica a linha da tabela
    var pagPart = $('#'+pagPart); // Restringe os objetos em uma parte específica da página

    // Atualiza o icone do Sort e reordena o conteúdo
    if($(thObj).attr("class").includes("sorting_asc")) {
        // Reseta todos os icones do Sort
        pagPart.find($(".sorting_asc")).attr("class", "sorting datagrid");
        pagPart.find($(".sorting_desc")).attr("class", "sorting datagrid");
        // Aplica o novo icone do Sort na coluna corrente
        $(thObj).attr("class", "sorting_desc datagrid");
        // Ordena os Eventos de forma Decrescente
        window[showFunction]($(thObj).attr("col") + " DESC");
    } else {                
        // Reseta todos os icones do Sort
        pagPart.find($(".sorting_asc")).attr("class", "sorting datagrid");
        pagPart.find($(".sorting_desc")).attr("class", "sorting datagrid");
        // Aplica o novo icone do Sort na coluna corrente
        $(thObj).attr("class", "sorting_asc datagrid");
        // Ordena os Eventos de forma Crescente
        window[showFunction]($(thObj).attr("col"));
    }
}