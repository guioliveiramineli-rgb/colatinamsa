document.addEventListener('DOMContentLoaded', function() {
    
    // --- LÓGICA DO CRONÔMETRO DE SESSÃO ---
    const timerElement = document.getElementById('session-timer');
    if (timerElement) {
        // ✅ TEMPO INICIAL DO CRONÔMETRO AUMENTADO PARA 10 MINUTOS
        let timeLeft = parseInt(timerElement.getAttribute('data-timeleft'), 10) || 600;

        const countdown = setInterval(function() {
            if (timeLeft <= 0) {
                clearInterval(countdown);
                window.location.href = 'logout.php?reason=session_expired';
            } else {
                let minutes = Math.floor(timeLeft / 60);
                let seconds = timeLeft % 60;
                seconds = seconds < 10 ? '0' + seconds : seconds;
                timerElement.textContent = `${minutes}:${seconds}`;
            }
            timeLeft -= 1;
        }, 1000);
    }

    // --- LÓGICA PARA CADASTRO DE NOVA ATRAÇÃO ---
    const cadastroForm = document.getElementById('cadastro-form');
    if (cadastroForm) {
        cadastroForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            const form = e.target;
            const formData = new FormData(form);
            const statusMessage = document.getElementById('status-message');

            statusMessage.innerHTML = `<div class="alert alert-info">Enviando dados...</div>`;

            try {
                // ✅ CORREÇÃO CRÍTICA: O caminho para o arquivo PHP foi corrigido
                const response = await fetch('../php/salvar_atracao.php', {
                    method: 'POST',
                    body: formData
                });

                if (!response.ok) {
                    throw new Error(`Erro do servidor: ${response.status}`);
                }

                const result = await response.json();

                if (result.success) {
                    statusMessage.innerHTML = `<div class="alert alert-success">${result.message} Recarregando a lista...</div>`;
                    form.reset();
                    // Recarrega a página para mostrar o novo item na lista
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    statusMessage.innerHTML = `<div class="alert alert-danger">Erro ao salvar: ${result.message}</div>`;
                }

            } catch (error) {
                console.error('Detalhes do erro:', error);
                statusMessage.innerHTML = `<div class="alert alert-danger"><b>Ocorreu um erro de conexão.</b> Verifique se o caminho para o arquivo 'salvar_atracao.php' está correto e se não há erros no PHP.</div>`;
            }
        });
    }

    // --- LÓGICA DE EXCLUSÃO (sem alterações) ---
    const listaAtracoes = document.getElementById('lista-atracoes');
    if (listaAtracoes) {
        listaAtracoes.addEventListener('click', function(e) {
            const deleteButton = e.target.closest('.delete-btn');
            if (deleteButton) {
                e.preventDefault();
                
                const id = deleteButton.dataset.id;
                const tipo = deleteButton.dataset.tipo;

                if (confirm('Tem certeza que deseja excluir esta atração?')) {
                    const formData = new FormData();
                    formData.append('id', id);
                    formData.append('tipo', tipo);

                    fetch('delete_attraction.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Atração excluída com sucesso!');
                            window.location.reload(); 
                        } else {
                            alert('Erro ao excluir: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Erro:', error);
                        alert('Ocorreu um erro de conexão.');
                    });
                }
            }
        });
    }
});