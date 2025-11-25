<div class="tarefas-container">
    <h2>Minhas Tarefas</h2>

    <form id="novaTarefa" onsubmit="return false;" method="post">
        <input type="text" name="titulo" placeholder="Título da tarefa" required>
        <textarea name="descricao" placeholder="Descrição"></textarea>
        <input type="datetime-local" name="prazo" id="prazo">
        <select name="prioridade">
            <option value="baixa">Baixa</option>
            <option value="media">Média</option>
            <option value="alta">Alta</option>
        </select>
        <input type="number" name="meta" placeholder="Meta (minutos, km, valor, etc)">

        <!-- IMPORTANTE: type="button" pra não disparar submit nativo -->
        <button type="button" onclick="criarTarefa()">Adicionar</button>
    </form>

    <div id="listaTarefas"></div>
</div>

<script>
    const idUsuario = <?php echo $_SESSION['usuarioId']; ?>;

    document.addEventListener('DOMContentLoaded', () => {
        const input = document.getElementById('prazo');

        const agora = new Date();
        const ano = agora.getFullYear();
        const mes = String(agora.getMonth() + 1).padStart(2, '0');
        const dia = String(agora.getDate()).padStart(2, '0');
        const hora = String(agora.getHours()).padStart(2, '0');
        const min = String(agora.getMinutes()).padStart(2, '0');

        input.value = `${ano}-${mes}-${dia}T${hora}:${min}`;
    });


    window.carregarTarefas = function() {
        fetch(`/api/api_tarefas.php?action=listar&id_usuario=${idUsuario}`)
            .then(r => r.json())
            .then(dados => {
                if (!dados || !dados.tarefas) {
                    document.getElementById('listaTarefas').innerHTML = '<p>Nenhuma tarefa encontrada.</p>';
                    return;
                }

                let html = "";

                dados.tarefas.forEach(t => {
                    html += `
                        <div class="tarefa ${t.status}">
                            <h3>${t.titulo}</h3>
                            <p>${t.descricao ?? ""}</p>
                            <p><b>Prazo:</b> ${t.prazo ?? "—"}</p>
                            <p><b>Prioridade:</b> ${t.prioridade}</p>
                            <p><b>Meta:</b> ${t.meta ?? "—"}</p>

                            <select onchange="alterarStatus(${t.id}, this.value)">
                                <option value="pendente" ${t.status === "pendente" ? "selected" : ""}>Pendente</option>
                                <option value="progresso" ${t.status === "progresso" ? "selected" : ""}>Em Progresso</option>
                                <option value="concluida" ${t.status === "concluida" ? "selected" : ""}>Concluída</option>
                            </select>

                            <button type="button" onclick="excluirTarefa(${t.id})">Excluir</button>
                        </div>
                    `;
                });

                document.getElementById('listaTarefas').innerHTML = html;
            })
            .catch(err => {
                console.error('Erro ao carregar tarefas:', err);
            });
    }

    window.criarTarefa = function() {
        const form = document.getElementById('novaTarefa');
        const formData = new FormData(form);
        formData.append('id_usuario', idUsuario);

        fetch('/api/api_tarefas.php?action=criar', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(dados => {
                form.reset();
                carregarTarefas();
            })
            .catch(err => {
                console.error('Erro ao criar tarefa:', err);
            });
    }

    window.alterarStatus = function(id, status) {
        const fd = new FormData();
        fd.append('id', id);
        fd.append('status', status);

        fetch('/api/api_tarefas.php?action=status', {
                method: 'POST',
                body: fd
            })
            .then(() => carregarTarefas())
            .catch(err => console.error('Erro ao alterar status:', err));
    }

    window.excluirTarefa = function(id) {
        const fd = new FormData();
        fd.append('id', id);

        fetch('/api/api_tarefas.php?action=excluir', {
                method: 'POST',
                body: fd
            })
            .then(() => carregarTarefas())
            .catch(err => console.error('Erro ao excluir tarefa:', err));
    }

    document.addEventListener('DOMContentLoaded', carregarTarefas);
</script>