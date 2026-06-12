-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           10.4.32-MariaDB - mariadb.org binary distribution
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              12.14.0.7165
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Copiando estrutura do banco de dados para db_ong_animais
CREATE DATABASE IF NOT EXISTS `db_ong_animais` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_estonian_ci */;
USE `db_ong_animais`;

-- Copiando estrutura para tabela db_ong_animais.tb_animais
CREATE TABLE IF NOT EXISTS `tb_animais` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `especie` varchar(50) NOT NULL,
  `raca` varchar(50) NOT NULL,
  `idade` int(11) NOT NULL DEFAULT 0,
  `cor` varchar(30) NOT NULL,
  `sexo` enum('Macho','Fêmea') NOT NULL,
  `descricao` text NOT NULL,
  `status` bit(1) NOT NULL DEFAULT b'1',
  `data` datetime NOT NULL DEFAULT current_timestamp(),
  `imagem` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_estonian_ci;

-- Copiando dados para a tabela db_ong_animais.tb_animais: ~4 rows (aproximadamente)
INSERT INTO `tb_animais` (`id`, `nome`, `especie`, `raca`, `idade`, `cor`, `sexo`, `descricao`, `status`, `data`, `imagem`) VALUES
	(1, 'Max', 'Cachorro', 'Pastor Alemão Capa Preta', 2, 'Preto E meio Amarelado', 'Macho', 'Esse é o max um dos Nosso Filhotes mais brincalhão Que Tem aqui. Ele ama agua, e tambem é um otimo nadador. Ele Foi abandonado pelo antigo dono, Ele Precisa de Um lar pois atualmente esta triste. PF adotem ele.', b'1', '2026-04-30 14:34:23', 'Pator Alemão Capa Preta Filhote.jpg'),
	(3, 'Fifi', 'Cachorro', 'Cockapoo', 17, 'Cinza e Preta', 'Fêmea', 'Cão da raça Cockapoo (mistura de Cocker Spaniel com Poodle). É extremamente dócil, inteligente, brincalhão e de porte médio, ideal tanto para casas quanto para apartamentos. Possui uma pelagem linda e costuma se dar muito bem com crianças e outros animais. Está saudável, vacinado e cheio de energia esperando por um lar amoroso!', b'1', '2026-06-09 15:06:39', '10-poodle-de-pelo-cinza.jpg'),
	(4, 'Thor', 'Cachorro', 'Husky Siberiano', 2, 'Branco e Preto claro', 'Macho', '"Thor é um filhote de Husky Siberiano muito alegre, brincalhão e divertido. Infelizmente, foi abandonado pelo antigo dono sem motivo aparente. Hoje, procura uma família que lhe dê o amor e os cuidados que ele merece." ????❤️', b'1', '2026-06-12 13:43:15', '8b7e74d347f3dcc14256f6499563c9f1.jpg'),
	(5, 'Panda', 'Gato', 'Frajola', 1, 'Preto e Branco', 'Macho', '"Panda é um gato macho de 1 ano, muito dócil, brincalhão e carinhoso. Sua pelagem preta e branca lhe dá um charme especial. Adora brincar, receber atenção e está à procura de um lar cheio de amor e cuidado." ❤️????', b'1', '2026-06-12 13:50:17', '5e5cc3f8dbd09bdef40fa9dfaffc12c5.jpg');

-- Copiando estrutura para tabela db_ong_animais.tb_estoque
CREATE TABLE IF NOT EXISTS `tb_estoque` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_nome` varchar(100) NOT NULL,
  `categoria` varchar(50) NOT NULL,
  `quantidade` int(11) NOT NULL DEFAULT 0,
  `status_necessidade` enum('OK','Atenção','Crítico') DEFAULT 'OK',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_estonian_ci;

-- Copiando dados para a tabela db_ong_animais.tb_estoque: ~2 rows (aproximadamente)
INSERT INTO `tb_estoque` (`id`, `item_nome`, `categoria`, `quantidade`, `status_necessidade`) VALUES
	(1, 'Ração Cão Adulto', 'Ração', 2, 'Crítico'),
	(2, 'Bolinhas de Borracha', 'Brinquedo', 15, 'OK'),
	(4, 'Ração para Filhote', 'Ração', 200, 'Atenção');

-- Copiando estrutura para tabela db_ong_animais.tb_parcerias
CREATE TABLE IF NOT EXISTS `tb_parcerias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome_empresa` varchar(150) NOT NULL,
  `responsavel` varchar(100) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `tipo_parceria` varchar(50) DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `status_parceria` varchar(20) DEFAULT 'Ativa',
  `data_cadastro` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_estonian_ci;

-- Copiando dados para a tabela db_ong_animais.tb_parcerias: ~2 rows (aproximadamente)
INSERT INTO `tb_parcerias` (`id`, `nome_empresa`, `responsavel`, `telefone`, `email`, `tipo_parceria`, `observacoes`, `status_parceria`, `data_cadastro`) VALUES
	(1, 'PremieRpet', 'Phelipe', '(83) 2837-5838', 'premierpet@gmail.com', 'Patrocinador', '', 'Ativa', '2026-06-10 14:56:12'),
	(4, 'Phelipe Pets', 'Phelipe', '(19) 99287-1033', 'phelipepets@gmail.com', 'Patrocinador', '', 'Ativa', '2026-06-10 15:02:21');

-- Copiando estrutura para tabela db_ong_animais.tb_pedidos_adocao
CREATE TABLE IF NOT EXISTS `tb_pedidos_adocao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_animal` int(11) NOT NULL,
  `nome_adotante` varchar(255) NOT NULL,
  `telefone_adotante` varchar(50) NOT NULL,
  `mensagem` text NOT NULL,
  `status_pedido` varchar(50) DEFAULT 'Pendente',
  `data_pedido` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_animal` (`id_animal`),
  CONSTRAINT `tb_pedidos_adocao_ibfk_1` FOREIGN KEY (`id_animal`) REFERENCES `tb_animais` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela db_ong_animais.tb_pedidos_adocao: ~2 rows (aproximadamente)
INSERT INTO `tb_pedidos_adocao` (`id`, `id_animal`, `nome_adotante`, `telefone_adotante`, `mensagem`, `status_pedido`, `data_pedido`) VALUES
	(1, 1, 'Carlos Silva', '(11) 98888-7777', 'Gostaria muito de adotar este pet! Tenho um quintal grande e espaço para ele correr.', 'Aprovado', '2026-06-10 19:42:56'),
	(2, 3, 'Leo silva', '(21) 99589-1044', 'Quero adotar a Fifi Pois sempre quis ter um cachorinho desde de criança mas como meu pai tinha alergia ele não deixou eu ter na infancia então eu quero ter um agora, tenho espaço e quero aproveitar os ultimos aninhos da fifi.', 'Aprovado', '2026-06-10 20:13:00');

-- Copiando estrutura para tabela db_ong_animais.tb_produtos
CREATE TABLE IF NOT EXISTS `tb_produtos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `categoria` varchar(100) NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `imagem` varchar(255) NOT NULL,
  `estoque` int(11) NOT NULL DEFAULT 0,
  `descricao` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela db_ong_animais.tb_produtos: ~8 rows (aproximadamente)
INSERT INTO `tb_produtos` (`id`, `nome`, `categoria`, `preco`, `imagem`, `estoque`, `descricao`) VALUES
	(1, 'Bolinha Interativa Funcional', 'Brinquedo', 24.90, 'bolinha-interativa.jpg', 20, 'Bolinha de borracha macia com compartimento interno para petiscos. Estimula o foco e gasta energia do seu pet.'),
	(2, 'Mordedor de Corda Nó Triplo', 'Brinquedo', 19.90, 'mordedor-corda.jpg', 15, 'Corda de algodão natural de alta resistência. Perfeito para brincadeiras de cabo de guerra e auxílio na higiene bucal.'),
	(3, 'Frisbee Flexível Aerodinâmico', 'Brinquedo', 32.50, 'frisbee-silicone.jpg', 10, 'Frisbee de silicone macio que não machuca a boca do animal. Ideal para treinos de corrida e saltos ao ar livre.'),
	(4, 'Arranhador Torre com Playground', 'Brinquedo', 129.90, 'arranhador-torre.jpg', 5, 'Torre completa com postes em sisal, brinquedo suspenso e plataforma elevada para o descanso e diversão dos felinos.'),
	(5, 'Guia Retrátil Confort de 5 Metros', 'Acessorio', 59.90, 'guia-retratil.jpg', 12, 'Guia com fita de nylon de alta durabilidade, sistema de trava rápida automática e manopla ergonômica antiderrapante.'),
	(6, 'Peitoral Anti-Puxão Comfort', 'Acessorio', 64.90, 'peitoral-antipuxao.jpg', 18, 'Peitoral com engate frontal que reduz drasticamente o impacto dos puxões. Tecido respirável e faixas reflexivas.'),
	(7, 'Comedouro Ergonômico de Cerâmica', 'Acessorio', 48.00, 'comedouro-ceramica.jpg', 8, 'Pote pesado que evita o deslocamento no chão, com altura ideal para proteger a coluna e melhorar a digestão.'),
	(8, 'Cama Nuvem Ultra Macia (G)', 'Acessorio', 98.90, 'cama-nuvem.jpg', 6, 'Cama redonda com estofamento premium e bordas elevadas que diminuem a ansiedade, garantindo um sono profundo e relaxante.');

-- Copiando estrutura para tabela db_ong_animais.tb_usuarios
CREATE TABLE IF NOT EXISTS `tb_usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `nivel_acesso` enum('user','admin') DEFAULT 'user',
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_estonian_ci;

-- Copiando dados para a tabela db_ong_animais.tb_usuarios: ~2 rows (aproximadamente)
INSERT INTO `tb_usuarios` (`id`, `nome`, `email`, `senha`, `nivel_acesso`, `data_cadastro`) VALUES
	(1, 'Admin ONG', 'admin@ong.com', 'admin123', 'admin', '2026-06-09 17:45:01'),
	(2, 'Adotante Comum', 'usuario@email.com', 'user123', 'user', '2026-06-09 17:45:01'),
	(3, 'Leo silva', 'leosilva2020@gmail.com', 'Leoleo', 'user', '2026-06-09 18:54:00');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
