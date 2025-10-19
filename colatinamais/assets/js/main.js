document.addEventListener('DOMContentLoaded', async () => {
    
    // =========================================================================
    // VARIÁVEIS GLOBAIS E FUNÇÕES DE COMUNICAÇÃO (JSON NO SERVIDOR)
    // =========================================================================
    
    // Variáveis e constantes
    const FAVORITES_STORAGE_KEY = 'favoritosColatinaMais';
    let favorites = []; // Array que armazena os IDs de favoritos, globalmente
    const FAVORITES_SERVER_HANDLER = 'php/handle_favorites_single_json.php';
    // window.USER_IS_LOGGED_IN deve ser definido em seus arquivos PHP (ex: index.php)
    
    // Função para salvar no JSON central no servidor (POST)
    const saveFavoritesToServer = (favoritesArray) => {
        // Envia a requisição apenas se o usuário estiver logado
        if (!window.USER_IS_LOGGED_IN) return; 

        fetch(FAVORITES_SERVER_HANDLER, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ favorites: favoritesArray }),
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                console.error('Erro ao salvar favoritos no JSON do servidor:', data.message);
                // Pode exibir uma mensagem de erro para o usuário aqui
            }
        })
        .catch(error => console.error('Erro na requisição POST de favoritos:', error));
    };

    // Função para carregar do JSON central no servidor (GET)
    const loadFavoritesFromServer = async () => {
        if (!window.USER_IS_LOGGED_IN) return [];

        try {
            const response = await fetch(FAVORITES_SERVER_HANDLER, { method: 'GET' });
            if (response.status === 401) {
                return []; 
            }
            const data = await response.json();
            
            if (data.success) {
                // Retorna o array de IDs de favoritos do usuário
                return data.favorites;
            } else {
                console.error('Erro ao carregar favoritos:', data.message);
                return [];
            }
        } catch (error) {
            console.error('Erro na requisição GET de favoritos:', error);
            return [];
        }
    };

    // Função unificada para SALVAR (Servidor ou LocalStorage)
    const saveFavorites = () => {
        if (window.USER_IS_LOGGED_IN) {
            saveFavoritesToServer(favorites);
        } else {
            // Comportamento para usuário não logado (salva localmente)
            localStorage.setItem(FAVORITES_STORAGE_KEY, JSON.stringify(favorites));
            // Opcional: Avisar o usuário que precisa logar para salvar permanentemente
        }
    };

    // Função unificada para CARREGAR na inicialização (Servidor ou LocalStorage)
    const loadFavorites = async () => {
        if (window.USER_IS_LOGGED_IN) {
            // Prioriza o servidor se logado
            favorites = await loadFavoritesFromServer();
        } else {
            // Usa localStorage se não logado
            const storedFavorites = localStorage.getItem(FAVORITES_STORAGE_KEY);
            // Garante que os IDs sejam tratados como strings para consistência
            favorites = storedFavorites ? JSON.parse(storedFavorites).map(String) : [];
        }
    };

    // Função genérica para adicionar/remover um favorito e salvar
    const toggleFavorite = (id) => {
        const idString = String(id); // Garante que o ID é uma string para busca consistente
        const index = favorites.indexOf(idString);
        
        if (index > -1) {
            favorites = favorites.filter(favId => favId !== idString);
        } else {
            favorites.push(idString);
        }
        
        saveFavorites(); // Salva no servidor ou localmente
        
        return favorites.includes(idString); // Retorna o novo estado
    };

    // =========================================================================
    // INICIALIZAÇÃO DA PÁGINA
    // =========================================================================
    
    // 1. CARREGA OS FAVORITOS antes de qualquer lógica visual
    await loadFavorites(); 

    // --- LÓGICA DE FAVORITOS (Para a página de detalhes) ---
    const favoritoBtnDetails = document.querySelector('.favorito-btn-details');
    if (favoritoBtnDetails) {
        // 'favorites' já está carregado
        const id = favoritoBtnDetails.getAttribute('data-id');

        function atualizarBotao(isFavorited) {
            if (isFavorited) {
                favoritoBtnDetails.innerHTML = '<i class="bi bi-heart-fill"></i> Salvo nos Favoritos';
                favoritoBtnDetails.classList.remove('btn-outline-danger');
                favoritoBtnDetails.classList.add('btn-danger');
            } else {
                favoritoBtnDetails.innerHTML = '<i class="bi bi-heart"></i> Adicionar aos Favoritos';
                favoritoBtnDetails.classList.add('btn-outline-danger');
                favoritoBtnDetails.classList.remove('btn-danger');
            }
        }
        
        favoritoBtnDetails.addEventListener('click', () => {
            // Usa a nova função unificada
            const isFavorited = toggleFavorite(id); 
            // Atualiza o visual
            atualizarBotao(isFavorited);
        });
        
        // Inicializa o estado do botão
        atualizarBotao(favorites.includes(id)); 
    }

     // --- Lógica do Botão Compartilhar (Página de Detalhes - MANTIDA) ---
    const shareButton = document.getElementById('share-button');
    if (shareButton) {
        shareButton.addEventListener('click', async () => {
            const shareData = {
                title: document.title, 
                text: 'Confira este local incrível em Colatina!', 
                url: window.location.href 
            };
            try {
                if (navigator.share) {
                    await navigator.share(shareData);
                } else {
                    await navigator.clipboard.writeText(window.location.href);
                    alert('Link copiado para a área de transferência!');
                }
            } catch (err) {
                 try { // Fallback 2
                     await navigator.clipboard.writeText(window.location.href);
                     alert('Link copiado para a área de transferência!');
                 } catch (copyErr) {
                     alert('Não foi possível compartilhar ou copiar o link.');
                 }
            }
        });
    }

    // --- LÓGICA DA PÁGINA DE FAVORITOS ---
    const favoritesContainer = document.getElementById('favorites-container');
    if (favoritesContainer) {
        // favorites já está carregado (do servidor ou localStorage)
        const emptyView = document.getElementById('empty-favorites');
        
        const atracoesFavoritas = (typeof todasAtracoes !== 'undefined' && Array.isArray(todasAtracoes)) 
            ? todasAtracoes.filter(atracao => atracao && atracao.id && favorites.includes(String(atracao.id))) 
            : [];

        if (atracoesFavoritas.length === 0) {
            emptyView.style.display = 'block'; 
        } else {
            emptyView.style.display = 'none'; 
            favoritesContainer.innerHTML = ''; 
            atracoesFavoritas.forEach(atracao => {
                if(!atracao || !atracao.id || !atracao.nome || !atracao.imagem || !atracao.tipo) return;
                const cardHTML = `
                    <div class="col-12">
                        <a href="details.php?id=${atracao.id}" class="favorite-card">
                            <img src="${atracao.imagem}" alt="${atracao.nome}">
                            <div class="favorite-card-info">
                                <h6>${atracao.nome}</h6>
                                <span class="badge bg-primary">${atracao.tipo}</span>
                            </div>
                            <i class="bi bi-chevron-right text-muted"></i>
                        </a>
                    </div>
                `;
                favoritesContainer.innerHTML += cardHTML;
            });
        }
    }

    // --- LÓGICA DA NAVEGAÇÃO INFERIOR (MANTIDA) ---
    const currentPage = window.location.pathname.split('/').pop();
    document.querySelectorAll('.bottom-nav-item').forEach(item => {
        item.classList.remove('active'); 
        const navAction = item.getAttribute('data-nav-action');
        if ( (currentPage === 'index.php' || currentPage === '') && navAction === 'home' ) {
            item.classList.add('active');
        } else if (currentPage === 'favorites.php' && navAction === 'favoritos') {
            item.classList.add('active');
        } else if (currentPage === 'list.php' && navAction === 'categorias') {
            item.classList.add('active');
        }
    });

});