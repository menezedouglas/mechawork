drop database IF EXISTS mecha;
create database mecha;
use mecha;
set global event_scheduler=on;

create table tbl_grafico_p(
total bigint,
mes_compras int,
mes_interno int,
mes_externo int);

#criacao da tabela obra 
create table tbl_obra(
id_obra int auto_increment primary key,
descricao varchar(50),
estatus tinyint default 1);

#criacao da tabela de usuarios
create table tbl_usuario(
id_usuario int primary key auto_increment,
nome varchar(50) not null,
sobrenome varchar(50) not null,
telefone varchar(15) not null,
email varchar(100) not null unique,
estatus tinyint default 1,
nivel int not null,
hora_clique timestamp,
fulltext(nome, sobrenome)
); 

# criacao da tabela de login
create table tbl_senha(
id_senha int primary key auto_increment,
senha varchar (100) not null,
estatus tinyint default 1,
id_usuario int not null,
constraint fk_senha foreign key (id_usuario) references tbl_usuario(id_usuario)
);


#criacao de produtos para normalizacao e para o procedure comparar na tablela estoque
create table tbl_produto(
id_produto int primary key auto_increment,
descricao varchar(100) not null,
codigo char(5) unique not null,
unidade enum('AMPOLA',  'BALDE',  'BANDEJ',  'BARRA',  'BISNAG',  'BLOCO',  'BOBINA',  'BOMB',  'CAPS',  'CART',  'CENTO',  'CJ',  'CM',  'CM2',  'CX',  'CX2',  'CX3',  'CX5',  'CX10',  'CX15',  'CX20',  'CX25',  'CX50',  'CX100',  'DISP',  'DUZIA',  'EMBAL',  'FARDO',  'FOLHA',  'FRASCO',  'GALAO',  'GF',  'GRAMAS',  'JOGO',  'KG',  'KIT',  'LATA',  'LITRO',  'M',  'M2',  'M3',  'MILHEI',  'ML',  'MWH',  'PACOTE',  'PALETE',  'PARES',  'PC',  'POTE',  'K',  'RESMA',  'ROLO',  'SACO',  'SACOLA',  'TAMBOR',  'TANQUE',  'TON',  'TUBO',  'UNID',  'VASIL',  'VIDRO') not null,
estatus tinyint default 1,
fulltext(descricao,codigo));


#criacao de tabela de cadastro de veiculos e equipamentos
create table tbl_cad_frota(
id_veiculo int primary key auto_increment,
placa_numero varchar(10) not null,
fabricante varchar(50) not null,
ano char(4) not null,
descricao varchar(50) not null,
estatus tinyint default 1,
tipo enum('MÁQUINAS DE CARGA E ELEVAÇÃO', 'VEÍCULOS DE CARGA E ELEVAÇÃO', 'VEÍCULOS DE TRANSPORTES DE PASSAGEIROS', 'VEÍCULOS DE PASSEIO', 'VEÍCULOS UTILITÁRIOS', 'GERADORES') not null
);

#criacao da tabela de notificacao de manutencoes futuras
create table tbl_manutencao_futura(
id_futura int primary key auto_increment,
data_prevista date not null,
data_minima date,
quilometragem bigint default 0,
descricao varchar(200) not null,
id_veiculo int not null,
id_usuario int not null,
estatus tinyint default 1,
constraint fk_usuario_manutencao_futura foreign key (id_usuario) references tbl_usuario(id_usuario),
constraint fk_veiculo_futura foreign key (id_veiculo) references tbl_cad_frota(id_veiculo)
);

#criacao da tabela de fornecedor e prestador de servico
create table tbl_fornecedor(
cnpj bigint primary key,
nome varchar (155) not null,
estatus tinyint default 1,
telefone varchar (15) not null
);

#criacao da tabela para notas fiscal de compra
create table tbl_nota_compra(
nota_compra bigint primary key,
valor float not null,
data_compra date not null,
data_vencimento date not null,
cnpj_fornecedor bigint not null,
numero_itens int not null,
estatus tinyint default 1,
constraint fk_cnpj_compra foreign key (cnpj_fornecedor) references tbl_fornecedor(cnpj)
);

# criacao da tabela de nota fiscal de servico prestado
create table tbl_nota_servico(
nota_servico bigint primary key,
valor float not null,
data_servico date not null,
data_vencimento date not null,
cnpj_fornecedor bigint not null,
estatus tinyint default 1,
constraint fk_cnpj_servico foreign key (cnpj_fornecedor) references tbl_fornecedor(cnpj)
);

#criacao da tabela de entrada, possui triggers de controle de estoque
create table tbl_entrada_produto(
id_entrada int primary key auto_increment,
data_entrada timestamp default now(),
quantia_comprada int not null,
valor_unitario float not null,
id_produto int not null,
numero_nota bigint not null,
estatus tinyint default 1,
constraint fk_entrada_produto foreign key (id_produto) references tbl_produto(id_produto),
constraint fk_entrada_nota foreign key (numero_nota) references tbl_nota_compra(nota_compra)
);


# criacao da tabela estoque, somento o banco ira manipular ela
create table tbl_estoque_produtos(
id_estoque int primary key,
quantia int unsigned not null,
valor_unitario_medio float not null,
estatus tinyint default 1
);

  #criacao da tabela com todas manutencoes realizadas
create table tbl_manutencao_realizada(
id_manutencao int primary key auto_increment,
data_realizada timestamp default now(),
hora_iniciada datetime not null,
hora_finalizada datetime not null,
descricao varchar(255) not null,
id_usuario int not null,
id_veiculo int not null,
nota_servico bigint,
id_obra int not null,
estatus tinyint default 1,
custo_interno float,
constraint fk_obra_manutencao foreign key (id_obra) references tbl_obra(id_obra),
constraint fk_usuario_manutencao foreign key (id_usuario) references tbl_usuario(id_usuario),
constraint fk_veiculo_manutencao foreign key (id_veiculo) references tbl_cad_frota(id_veiculo),
constraint fk_servico_manutencao foreign key (nota_servico) references tbl_nota_servico(nota_servico)
);

#criacao da tabela de saida do estoque, possui trigger de controle de estoque
create table tbl_saida_produto(
id_saida int primary key auto_increment,
data_saida timestamp default now(),
quantia_usada int unsigned not null,
id_estoque int not null,
estatus tinyint default 1,
id_manutencao int not null,
constraint fk_saida_manutencao foreign key (id_manutencao) references tbl_manutencao_realizada(id_manutencao),
constraint fk_saida_produto foreign key (id_estoque) references tbl_estoque_produtos(id_estoque)
);


#criacao da tabela de ligar manutencoes agendadas com manutencoes feitas
create table tbl_controle(
id_futura int not null,
id_manutencao int not null,
constraint fk__manutencao_futura foreign key (id_futura) references tbl_manutencao_futura(id_futura),
constraint fk_manutencao_realizada foreign key (id_manutencao) references tbl_manutencao_realizada(id_manutencao)
);

create table tbl_relatorio_veiculo(
identificacao_veiculo varchar(12) not null,
manutencao int not null,
valor float not null,
data_realizada timestamp not null,
hora_gasta float not null
);


create table tbl_configuracao(
id_config int primary key auto_increment,
email_contato varchar(100) not null,
url_base varchar(100) not null,
tempo_minimo int,
margem_notifica int
);

create table tbl_notificacao(
id_notificacao int primary key auto_increment,
id_futura int not null unique ,
placa varchar(10) not null,
quilometragem int default 0,
data_cadastrada date not null,
data_notifica datetime not null default now(),
descricao varchar (200) not null,
situacao enum ('notificado','visualizada','atendida') default 'notificado',
constraint fk_noficacao foreign key (id_futura) references tbl_manutencao_futura(id_futura)
);  