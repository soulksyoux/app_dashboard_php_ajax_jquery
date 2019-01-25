$(document).ready(() => {

    $('#dashboard').on('click', () => {
        $.get('dashboard.html', e => {
            $('#pagina').html(e);
        })
    })
    
    /*
    
    $('#documentacao').on('click',() => {
        $('#pagina').load('documentacao.html');
    })

    */

    $('#documentacao').on('click', () => {
        $.get('documentacao.html', e => {
            $('#pagina').html(e);
        })
    })


    /*
    $('#suporte').on('click',() => {
        $('#pagina').load('suporte.html');
    })
    */

   $('#suporte').on('click', () => {
        $.post('suporte.html', e => {
            $('#pagina').html(e);
        })
    })


    //ajax
    $('#competencia').on('change', e => {

        let competencia = $(e.target).val();

        //executar request assincrono
        $.ajax({
            //metodo
            type: 'GET',
            //url
            url: 'app.php',
            //reencaminhar dados
            data: `competencia=${competencia}`, //x-www-form-urlencoded
            //caso sucesso
            dataType: 'json',
            success: dados => {

                console.log("sucesso");
                $('#numeroVendas').html(dados.numeroVendas);
                $('#totalVendas').html(dados.totalVendas);
                $('#clientesAtivos').html(dados.clientesAtivos);
                $('#clientesInativos').html(dados.clientesInativos);
                $('#totalReclamacoes').html(dados.totalReclamacoes);
                $('#totalElogios').html(dados.totalElogios);
                $('#totalSugestoes').html(dados.totalSugestoes);
                $('#totalDespesas').html(dados.totalDespesas);
          
            },
            //caso fail
            error: erro => {
                console.log('Erro: ' + erro);
            }
        })
    })

})