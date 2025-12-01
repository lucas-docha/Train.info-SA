/**
 * =====================================================
 * SISTEMA DE MAPA INTERATIVO - ROTAS FERROVIÁRIAS
 * =====================================================
 * Arquivo: js/mapa.js
 */

// Variáveis globais
let map;
let stations = [];
let routes = [];
let stationMarkers = [];
let routeLines = [];
let editMode = false;
let selectedStation = null;
let tempMarker = null;
let creatingRoute = false;
let currentRoute = [];
let currentRouteLine = null;

// Inicialização do mapa
function initMap() {
    // Coordenadas do centro do Brasil
    const centerLat = -14.2350;
    const centerLng = -51.9253;
    
    // Criar o mapa
    map = L.map('map').setView([centerLat, centerLng], 5);
    
    // Adicionar camada do mapa
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
    
    // Carregar dados do servidor
    loadStations();
    loadRoutes();
    
    // Evento de clique no mapa
    map.on('click', function(e) {
        if (editMode && !tempMarker && !creatingRoute) {
            createTempStation(e.latlng);
        }
    });
}

// Carregar estações do servidor
function loadStations() {
    fetch('api.php?action=get_stations')
        .then(response => {
            if (!response.ok) {
                throw new Error('Erro na resposta do servidor');
            }
            return response.json();
        })
        .then(data => {
            stations = data;
            renderStations();
            showNotification('Estações carregadas com sucesso', 'success');
        })
        .catch(error => {
            console.error('Erro ao carregar estações:', error);
            showNotification('Erro ao carregar estações', 'error');
        });
}

// Carregar rotas do servidor
function loadRoutes() {
    fetch('api.php?action=get_routes')
        .then(response => {
            if (!response.ok) {
                throw new Error('Erro na resposta do servidor');
            }
            return response.json();
        })
        .then(data => {
            routes = data;
            renderRoutes();
            showNotification('Rotas carregadas com sucesso', 'success');
        })
        .catch(error => {
            console.error('Erro ao carregar rotas:', error);
            showNotification('Erro ao carregar rotas', 'error');
        });
}

// Criar estação temporária no mapa
function createTempStation(latlng) {
    tempMarker = L.marker(latlng, {
        draggable: true,
        icon: L.divIcon({
            className: 'temp-marker',
            html: '<div style="background-color: #3498db; width: 18px; height: 18px; border-radius: 50%; border: 3px solid white; animation: pulse 1.5s infinite;"></div>',
            iconSize: [24, 24]
        })
    }).addTo(map);
    
    // Preencher coordenadas no formulário
    document.getElementById('station-lat').value = latlng.lat.toFixed(6);
    document.getElementById('station-lng').value = latlng.lng.toFixed(6);
    
    // Abrir modal para adicionar estação
    openStationModal();
}

// Renderizar estações no mapa
function renderStations() {
    // Limpar marcadores existentes
    stationMarkers.forEach(marker => map.removeLayer(marker));
    stationMarkers = [];
    
    // Adicionar cada estação
    stations.forEach(station => {
        // Criar marcador no mapa
        const marker = L.marker([station.latitude, station.longitude], {
            draggable: editMode,
            icon: L.divIcon({
                className: 'station-marker-custom',
                html: '<div style="background-color: #e74c3c; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white;"></div>',
                iconSize: [26, 26]
            })
        }).addTo(map);
        
        // Adicionar popup com informações
        marker.bindPopup(`
            <div style="color: white;">
                <h3 style="color: #41b8d5; margin-bottom: 10px;">${station.nome}</h3>
                <p style="margin: 5px 0;"><strong>Endereço:</strong> ${station.endereco || 'Não informado'}</p>
                <p style="margin: 5px 0;"><strong>Lat:</strong> ${parseFloat(station.latitude).toFixed(4)}</p>
                <p style="margin: 5px 0;"><strong>Lng:</strong> ${parseFloat(station.longitude).toFixed(4)}</p>
                <button onclick="editStation(${station.id})" class="botao botao-primario" style="margin-top: 10px; padding: 5px 15px; font-size: 12px;">
                    <i class="fas fa-edit"></i> Editar
                </button>
            </div>
        `);
        
        // Evento de arrastar (apenas no modo edição)
        if (editMode) {
            marker.on('dragend', function(e) {
                const newLat = e.target.getLatLng().lat;
                const newLng = e.target.getLatLng().lng;
                updateStationPosition(station.id, newLat, newLng);
            });
        }
        
        // Evento de clique
        marker.on('click', function() {
            if (creatingRoute) {
                addStationToRoute(station);
            } else if (!editMode) {
                map.setView([station.latitude, station.longitude], 10);
                marker.openPopup();
            }
        });
        
        stationMarkers.push(marker);
    });
}

// Renderizar rotas no mapa
function renderRoutes() {
    // Limpar rotas existentes
    routeLines.forEach(line => map.removeLayer(line));
    routeLines = [];
    
    // Adicionar cada rota
    routes.forEach(route => {
        // Obter coordenadas das estações da rota
        const coordinates = [];
        route.estacoes.forEach(estacao => {
            coordinates.push([estacao.latitude, estacao.longitude]);
        });
        
        if (coordinates.length > 1) {
            // Criar linha da rota com estilo de trilho
            const line = L.polyline(coordinates, {
                color: '#333',
                weight: 6,
                opacity: 0.8,
                dashArray: '10, 10'
            }).addTo(map);
            
            // Linha de sombra para efeito de trilho
            const shadowLine = L.polyline(coordinates, {
                color: '#e74c3c',
                weight: 8,
                opacity: 0.3
            }).addTo(map);
            
            // Adicionar popup com informações
            line.bindPopup(`
                <div style="color: white;">
                    <h3 style="color: #41b8d5; margin-bottom: 10px;">${route.nome}</h3>
                    <p style="margin: 5px 0;"><strong>Distância:</strong> ${parseFloat(route.distancia_km).toFixed(2)} km</p>
                    <p style="margin: 5px 0;"><strong>Tempo estimado:</strong> ${Math.floor(route.tempo_estimado_min / 60)}h ${route.tempo_estimado_min % 60}min</p>
                    <p style="margin: 5px 0;"><strong>Estações:</strong> ${route.estacoes.length}</p>
                    <button onclick="deleteRoute(${route.id})" class="botao botao-perigo" style="margin-top: 10px; padding: 5px 15px; font-size: 12px;">
                        <i class="fas fa-trash"></i> Excluir Rota
                    </button>
                </div>
            `);
            
            routeLines.push(line);
            routeLines.push(shadowLine);
        }
    });
}

// Alternar modo de edição
function toggleEditMode() {
    editMode = !editMode;
    
    const indicator = document.getElementById('mode-indicator');
    const btn = document.getElementById('btn-edit-mode');
    
    if (editMode) {
        indicator.textContent = 'Modo Edição';
        indicator.className = 'mode-badge mode-edit';
        btn.innerHTML = '<i class="fas fa-eye"></i> Visualizar';
        showNotification('Modo edição ativado', 'info');
    } else {
        indicator.textContent = 'Modo Visualização';
        indicator.className = 'mode-badge mode-view';
        btn.innerHTML = '<i class="fas fa-edit"></i> Modo Edição';
        showNotification('Modo visualização ativado', 'info');
    }
    
    renderStations();
}

// Iniciar criação de rota
function startRouteCreation() {
    creatingRoute = true;
    currentRoute = [];
    
    document.getElementById('route-creator').style.display = 'block';
    showNotification('Clique nas estações para criar a rota', 'info');
    map.getContainer().style.cursor = 'crosshair';
}

// Finalizar criação de rota
function finishRouteCreation() {
    const routeName = document.getElementById('route-name').value || `Rota ${routes.length + 1}`;
    
    if (currentRoute.length < 2) {
        showNotification('Uma rota precisa ter pelo menos duas estações', 'error');
        return;
    }
    
    // Preparar dados para envio
    const data = {
        nome: routeName,
        estacoes: JSON.stringify(currentRoute.map(station => station.id))
    };
    
    // Enviar para o servidor
    fetch('api.php?action=save_route', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erro na resposta do servidor');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            cancelRouteCreation();
            loadRoutes();
            showNotification(`Rota "${routeName}" criada com sucesso`, 'success');
        } else {
            showNotification('Erro ao salvar rota: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showNotification('Erro ao salvar rota: ' + error.message, 'error');
    });
}

// Cancelar criação de rota
function cancelRouteCreation() {
    creatingRoute = false;
    currentRoute = [];
    
    document.getElementById('route-creator').style.display = 'none';
    document.getElementById('route-name').value = '';
    map.getContainer().style.cursor = '';
    
    if (currentRouteLine) {
        map.removeLayer(currentRouteLine);
        currentRouteLine = null;
    }
    
    document.getElementById('route-stations-list').innerHTML = '';
    showNotification('Criação de rota cancelada', 'info');
}

// Adicionar estação à rota em criação
function addStationToRoute(station) {
    if (currentRoute.some(s => s.id === station.id)) {
        showNotification('Esta estação já está na rota', 'error');
        return;
    }
    
    currentRoute.push(station);
    updateRouteStationsList();
    updateTempRouteLine();
    showNotification(`Estação "${station.nome}" adicionada à rota`, 'success');
}

// Atualizar lista de estações na rota em criação
function updateRouteStationsList() {
    const container = document.getElementById('route-stations-list');
    container.innerHTML = '';
    
    if (currentRoute.length === 0) {
        container.innerHTML = '<p style="color: #888; text-align: center; font-size: 12px;">Nenhuma estação adicionada</p>';
        return;
    }
    
    currentRoute.forEach((station, index) => {
        const stationItem = document.createElement('div');
        stationItem.style.padding = '5px';
        stationItem.style.borderBottom = '1px solid rgba(255,255,255,0.1)';
        stationItem.style.fontSize = '12px';
        stationItem.innerHTML = `${index + 1}. ${station.nome}`;
        container.appendChild(stationItem);
    });
}

// Atualizar linha temporária da rota em criação
function updateTempRouteLine() {
    if (currentRouteLine) {
        map.removeLayer(currentRouteLine);
    }
    
    if (currentRoute.length > 1) {
        const coordinates = currentRoute.map(station => [station.latitude, station.longitude]);
        
        currentRouteLine = L.polyline(coordinates, {
            color: '#3498db',
            weight: 4,
            opacity: 0.7,
            dashArray: '5, 5'
        }).addTo(map);
    }
}

// Abrir modal de estação
function openStationModal(stationId = null) {
    const modal = document.getElementById('station-modal');
    const title = document.getElementById('modal-title');
    const form = document.getElementById('station-form');
    const deleteBtn = document.getElementById('btn-delete-station');
    
    if (stationId) {
        title.innerHTML = '<i class="fas fa-train"></i> Editar Estação';
        const station = stations.find(s => s.id == stationId);
        
        if (station) {
            document.getElementById('station-id').value = station.id;
            document.getElementById('station-name').value = station.nome;
            document.getElementById('station-address').value = station.endereco || '';
            document.getElementById('station-lat').value = station.latitude;
            document.getElementById('station-lng').value = station.longitude;
        }
        
        deleteBtn.style.display = 'inline-block';
    } else {
        title.innerHTML = '<i class="fas fa-train"></i> Adicionar Estação';
        form.reset();
        document.getElementById('station-id').value = '';
        deleteBtn.style.display = 'none';
        
        if (!document.getElementById('station-lat').value) {
            const center = map.getCenter();
            document.getElementById('station-lat').value = center.lat.toFixed(6);
            document.getElementById('station-lng').value = center.lng.toFixed(6);
        }
    }
    
    modal.style.display = 'flex';
}

// Editar estação
function editStation(stationId) {
    openStationModal(stationId);
}

// Fechar modais
function closeModals() {
    document.querySelectorAll('.modal').forEach(modal => {
        modal.style.display = 'none';
    });
    
    if (tempMarker) {
        map.removeLayer(tempMarker);
        tempMarker = null;
    }
}

// Salvar estação
function saveStation(event) {
    event.preventDefault();
    
    const data = {
        id: document.getElementById('station-id').value,
        nome: document.getElementById('station-name').value,
        endereco: document.getElementById('station-address').value,
        latitude: document.getElementById('station-lat').value,
        longitude: document.getElementById('station-lng').value
    };
    
    fetch('api.php?action=save_station', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erro na resposta do servidor');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            closeModals();
            loadStations();
            showNotification(`Estação "${document.getElementById('station-name').value}" salva com sucesso`, 'success');
        } else {
            showNotification('Erro ao salvar estação: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showNotification('Erro ao salvar estação: ' + error.message, 'error');
    });
}

// Excluir estação
function deleteStation() {
    const stationId = document.getElementById('station-id').value;
    
    if (!stationId || !confirm('Tem certeza que deseja excluir esta estação?')) {
        return;
    }
    
    const data = {
        id: stationId
    };
    
    fetch('api.php?action=delete_station', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erro na resposta do servidor');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            closeModals();
            loadStations();
            showNotification('Estação excluída com sucesso', 'success');
        } else {
            showNotification('Erro ao excluir estação: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showNotification('Erro ao excluir estação: ' + error.message, 'error');
    });
}

// Excluir rota
function deleteRoute(routeId) {
    if (!confirm('Tem certeza que deseja excluir esta rota?')) {
        return;
    }
    
    const data = {
        id: routeId
    };
    
    fetch('api.php?action=delete_route', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erro na resposta do servidor');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            loadRoutes();
            showNotification('Rota excluída com sucesso', 'success');
        } else {
            showNotification('Erro ao excluir rota: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showNotification('Erro ao excluir rota: ' + error.message, 'error');
    });
}

// Atualizar posição da estação
function updateStationPosition(stationId, lat, lng) {
    const data = {
        id: stationId,
        latitude: lat,
        longitude: lng
    };
    
    fetch('api.php?action=update_station_position', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erro na resposta do servidor');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            const station = stations.find(s => s.id == stationId);
            if (station) {
                showNotification(`Posição da estação "${station.nome}" atualizada`, 'success');
            }
        }
    })
    .catch(error => {
        console.error('Erro:', error);
    });
}

// Mostrar notificação
function showNotification(message, type = 'info') {
    // Você pode implementar um sistema de notificações mais sofisticado aqui
    console.log(`[${type.toUpperCase()}] ${message}`);
}

// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar mapa
    initMap();
    
    // Botões
    document.getElementById('btn-add-station').addEventListener('click', function() {
        openStationModal();
    });
    
    document.getElementById('btn-start-route').addEventListener('click', startRouteCreation);
    document.getElementById('btn-finish-route').addEventListener('click', finishRouteCreation);
    document.getElementById('btn-cancel-route').addEventListener('click', cancelRouteCreation);
    
    document.getElementById('btn-edit-mode').addEventListener('click', toggleEditMode);
    
    document.getElementById('btn-refresh').addEventListener('click', function() {
        loadStations();
        loadRoutes();
    });
    
    // Fechar modais
    document.querySelectorAll('.close').forEach(closeBtn => {
        closeBtn.addEventListener('click', closeModals);
    });
    
    // Formulários
    document.getElementById('station-form').addEventListener('submit', saveStation);
    document.getElementById('btn-delete-station').addEventListener('click', deleteStation);
    
    // Fechar modal ao clicar fora
    window.addEventListener('click', function(event) {
        if (event.target.id === 'station-modal') {
            closeModals();
        }
    });
});