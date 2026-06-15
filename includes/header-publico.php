<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['logado'])) {
    header("Location: login.php");
    exit();
}

require_once 'backend/conexao.php';

$notificacoes = [];
$total_nao_lidas = 0;

if (isset($_SESSION['user_nome'])) {
    try {
        // Busca os pedidos do usuário junto com o nome do pet para a notificação
        $sql_notif = "SELECT p.status_pedido, a.nome AS nome_pet 
                      FROM tb_pedidos_adocao p
                      JOIN tb_animais a ON p.id_animal = a.id
                      WHERE p.nome_adotante = :nome 
                      ORDER BY p.id DESC LIMIT 5";
                      
        $stmt_notif = $conexao->prepare($sql_notif);
        $stmt_notif->bindValue(':nome', $_SESSION['user_nome'], PDO::PARAM_STR);
        $stmt_notif->execute();
        $notificacoes = $stmt_notif->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($notificacoes as $n) {
            if ($n['status_pedido'] === 'Aprovado' || $n['status_pedido'] === 'Recusado') {
                $total_nao_lidas++;
            }
        }
    } catch (PDOException $e) {
        error_log($e->getMessage());
    }
}

// AUTOMACAÇÃO: Pega o nome do arquivo atual para saber onde colocar a linha verde
$pagina_atual = basename($_SERVER['PHP_SELF']);
?>
<header class="navbar-publica">
    <div class="nav-container">
        <a href="painel-adotante.php" class="nav-logo">🐾 CUIDA ANIMAL</a>
        
        <nav class="nav-links">
            <a href="painel-adotante.php" class="nav-item <?php echo ($pagina_atual === 'painel-adotante.php') ? 'active' : ''; ?>">🐶 Adotar um Pet</a>
            
            <a href="produtos.php" class="nav-item <?php echo ($pagina_atual === 'produtos.php') ? 'active' : ''; ?>">🧸 Brinquedos & Acessórios</a>
            
            <a href="meus-pedidos.php" class="nav-item <?php echo ($pagina_atual === 'meus-pedidos.php') ? 'active' : ''; ?>">📋 Meus Pedidos</a>
            
            <a href="sobre.php" class="nav-item <?php echo ($pagina_atual === 'sobre.php') ? 'active' : ''; ?>">🏢 Sobre a ONG</a>
        </nav>

        <div class="nav-right">
            <button id="theme-toggle" class="theme-toggle-btn" title="Alternar Tema">☀️</button>
            
            <div class="user-profile">
                
                <div class="notification-container">
                    <button class="notification-btn" id="btn-sininho" title="Notificações">
                        🔔
                        <?php if ($total_nao_lidas > 0): ?>
                            <span class="badge-number"><?php echo $total_nao_lidas; ?></span>
                        <?php endif; ?>
                    </button>
                    
                    <div class="notification-dropdown" id="dropdown-notif">
                        <div class="dropdown-header">
                            <span>Notificações</span>
                        </div>
                        <ul class="dropdown-body">
                            <?php if (empty($notificacoes)): ?>
                                <li class="empty-notification">Nenhuma atividade recente.</li>
                            <?php else: ?>
                                <?php foreach ($notificacoes as $notif): ?>
                                    <li class="notification-item">
                                        <?php if ($notif['status_pedido'] === 'Aprovado'): ?>
                                            <div class="status-icon icon-aprovado">✅</div>
                                            <div class="status-text">
                                                Sua solicitação para adotar <strong><?php echo htmlspecialchars($notif['nome_pet']); ?></strong> foi <span class="txt-aprovado">Aprovada</span>! 🎉
                                            </div>
                                        <?php elseif ($notif['status_pedido'] === 'Recusado'): ?>
                                            <div class="status-icon icon-recusado">❌</div>
                                            <div class="status-text">
                                                A solicitação para o pet <strong><?php echo htmlspecialchars($notif['nome_pet']); ?></strong> foi <span class="txt-recusado">Negada</span>. 😔
                                            </div>
                                        <?php else: ?>
                                            <div class="status-icon icon-pendente">⏳</div>
                                            <div class="status-text">
                                                Seu pedido para <strong><?php echo htmlspecialchars($notif['nome_pet']); ?></strong> está em análise.
                                            </div>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                        <div class="dropdown-footer">
                            <a href="meus-pedidos.php">Ver todos os meus pedidos</a>
                        </div>
                    </div>
                </div>

                <span>Olá, <strong><?php echo htmlspecialchars($_SESSION['user_nome'] ?? 'Visitante'); ?></strong></span>
                <a href="sair.php" class="logout-link">Sair 🚪</a>
            </div>
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const btnSininho = document.getElementById('btn-sininho');
        const dropdownNotif = document.getElementById('dropdown-notif');

        if (btnSininho && dropdownNotif) {
            btnSininho.addEventListener('click', (e) => {
                e.stopPropagation();
                dropdownNotif.classList.toggle('active');
            });

            document.addEventListener('click', (e) => {
                if (!dropdownNotif.contains(e.target) && e.target !== btnSininho) {
                    dropdownNotif.classList.remove('active');
                }
            });
        }
    });
</script>