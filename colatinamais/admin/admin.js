document.getElementById('cadastro-form').addEventListener('submit', async function(e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    const statusMessage = document.getElementById('status-message');

    statusMessage.innerHTML = `<div class="alert alert-info">Enviando dados...</div>`;

    try {
        const response = await fetch('../php/salvar_atracao.php', {
            method: 'POST',
            body: formData
        });

        // Verifica se a resposta do servidor foi um erro (ex: erro 500 no PHP)
        if (!response.ok) {
            throw new Error(`Erro do servidor: ${response.status} - ${response.statusText}`);
        }

        const result = await response.json();

        if (result.success) {
            statusMessage.innerHTML = `<div class="alert alert-success">${result.message}</div>`;
            form.reset(); // Limpa o formulário
        } else {
            // Mostra a mensagem de erro que veio do PHP
            statusMessage.innerHTML = `<div class="alert alert-danger">Erro ao salvar: ${result.message}</div>`;
        }

    } catch (error) {
        // Este erro acontece se não conseguir nem chegar no arquivo PHP
        console.error('Detalhes do erro:', error);
        statusMessage.innerHTML = `<div class="alert alert-danger"><b>Ocorreu um erro de conexão.</b> Verifique se o servidor local (XAMPP) está rodando e se o caminho para o arquivo PHP está correto.</div>`;
    }
});