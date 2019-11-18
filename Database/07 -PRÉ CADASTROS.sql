use mecha;

call sp_cadastra_usuario('Usuário', 'User', '11123456789', 'user@user.mw', md5('mw201902'), 2, @retorno);
call sp_cadastra_usuario('Administrador', 'Admin', '11123456789', 'admin@admin.mw', md5('mw201901'), 1, @retorno);

insert into tbl_configuracao (id_config, email_contato, url_base, tempo_minimo, margem_notifica) values (null, 'contato.iwgsoftware@gmail.com', 'http://localhost/mechawork', 2, 10);
/*
select(ifnull(valor_compras,0)+ifnull(gasto_interno,0)+ifnull(gasto_externo,0)) total, mes_compras,(ifnull(mes_interno,0))
 mes_interno,(ifnull(mes_externo,0))  mes_externo from vw_custo_mensal;
select*from vw_custo_mensal;

select mr.id_manutencao, mr.data_realizada, us.email, cf.placa_numero, ns.nota_servico, ns.valor, ns.data_servico, ns.cnpj_fornecedor, ob.descricao  from tbl_manutencao_realizada mr
inner join tbl_usuario us on mr.id_usuario = us.id_usuario
inner join tbl_cad_frota cf on mr.id_veiculo = cf.id_veiculo
inner join tbl_nota_servico ns on mr.nota_servico = ns.nota_servico
inner join tbl_obra ob on mr.id_obra = ob.id_obra;


select sp.id_saida, pr.id_produto, pr.codigo, sp.quantia_usada, mr.custo_interno, mr.id_manutencao, mr.data_realizada, us.email  from tbl_saida_produto sp
inner join tbl_estoque_produtos ep on sp.id_estoque = ep.id_estoque
inner join tbl_manutencao_realizada mr on sp.id_manutencao = mr.id_manutencao
inner join tbl_produto pr on sp.id_estoque = pr.id_produto
inner join tbl_usuario us on mr.id_usuario = us.id_usuario;

select id_entrada, data_entrada, codigo, quantia_comprada, valor_unitario, descricao, e.estatus from tbl_entrada_produto e, tbl_produto p
where e.id_produto = p.id_produto;

call popula_grafico;

select * from tbl_fornecedor;
select * from vw_notas order by emissao desc limit 10;
*/