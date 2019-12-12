use mecha;

drop view if exists vw_nota;
create view vw_nota as
select sum(valor) valor_compras,month(data_compra) mes_compras,year(data_compra) ano_compras from tbl_nota_compra n
where year(data_compra)=year(now()) and estatus = 1 group by month(n.data_compra);

drop view if exists vw_custo_interno;
create view vw_custo_interno as
select sum(custo_interno) gasto_interno ,month(hora_iniciada) mes_interno, year(hora_iniciada) ano_interna from tbl_manutencao_realizada m
where year (hora_iniciada)=year(now()) and estatus = 1 group by month(m.hora_iniciada);

drop view if exists vw_custo_externo;
create view vw_custo_externo as
select sum(valor) gasto_externo,month(data_servico) mes_externo,year(data_servico) ano_externo from tbl_nota_servico n
where year(data_servico)=year(now()) and estatus = 1 group by month(n.data_servico);

drop view if exists vw_manutencao_interna;
create view vw_manutencao_interna as
select manutencao.id_manutencao, manutencao.data_realizada, manutencao.hora_iniciada, manutencao.hora_finalizada, 
frota.placa_numero, frota.tipo, manutencao.custo_interno,manutencao.descricao,
 obra.descricao armazem
from tbl_cad_frota frota,  tbl_manutencao_realizada manutencao, tbl_obra obra
where frota.estatus=1 and manutencao.estatus=1 and obra.estatus=1
and manutencao.id_obra=obra.id_obra and frota.id_veiculo=manutencao.id_veiculo and manutencao.custo_interno >0 and frota.estatus = 1 and manutencao.estatus = 1 and obra.estatus = 1;

drop view if exists vw_manutencao_externa;
create view vw_manutencao_externa as
select manutencao.id_manutencao, manutencao.data_realizada, manutencao.hora_iniciada, manutencao.hora_finalizada, 
frota.placa_numero, frota.tipo, manutencao.descricao,servico.valor, 
 obra.descricao armazem
from tbl_cad_frota frota,  tbl_manutencao_realizada manutencao, tbl_obra obra, tbl_nota_servico servico
where frota.estatus=1 and manutencao.estatus=1 and obra.estatus=1
and manutencao.id_obra=obra.id_obra and frota.id_veiculo=manutencao.id_veiculo and servico.nota_servico=manutencao.nota_servico and frota.estatus = 1 and manutencao.estatus = 1 and obra.estatus = 1 and servico.estatus = 1; 

drop view if exists vw_custo_mensal;
create view vw_custo_mensal as
select valor_compras, mes_compras, gasto_interno, mes_interno,gasto_externo, mes_externo from  
vw_custo_interno interno   
left join vw_nota compra
on (compra.mes_compras=interno.mes_interno)    left outer join vw_custo_externo
on(mes_externo = interno.mes_interno)
union 
select  valor_compras, mes_compras, gasto_interno, mes_interno,gasto_externo, mes_externo from 
vw_nota compra  
left join  vw_custo_interno interno 
on (interno.mes_interno = compra.mes_compras)
left  join vw_custo_externo 
on(mes_externo = compra.mes_compras)
union 
select  valor_compras, mes_compras, gasto_interno, mes_interno,gasto_externo, mes_externo from  
vw_custo_externo 
left join  vw_custo_interno interno 
on (interno.mes_interno = mes_externo)
left  join vw_nota compra 
on(compra.mes_compras = mes_externo);

drop view if exists vw_historico_entradas;
create view vw_historico_entradas as
select id_estoque, quantia, valor_unitario_medio, e.estatus, id_entrada, data_entrada, quantia_comprada, valor_unitario, p.id_produto, descricao, codigo, unidade, nota_compra, valor, data_compra, data_vencimento, cnpj, nome, telefone from tbl_estoque_produtos estq
inner join tbl_entrada_produto e on estq.id_estoque = e.id_produto
inner join tbl_produto p on e.id_produto = p.id_produto
inner join tbl_nota_compra n on numero_nota = nota_compra
inner join tbl_fornecedor f on cnpj_fornecedor = cnpj
where e.estatus = 1 and p.estatus = 1 and n.estatus = 1 and f.estatus = 1;

drop view if exists vw_historico_saidas;
create view vw_historico_saidas as
select sp.id_saida, sp.data_saida, pr.id_produto, pr.codigo, sp.quantia_usada, en.valor_unitario, mr.custo_interno, mr.id_manutencao, mr.data_realizada, us.email, sp.estatus  from tbl_saida_produto sp
inner join tbl_estoque_produtos ep on sp.id_estoque = ep.id_estoque
inner join tbl_manutencao_realizada mr on sp.id_manutencao = mr.id_manutencao
inner join tbl_produto pr on sp.id_estoque = pr.id_produto
inner join tbl_usuario us on mr.id_usuario = us.id_usuario
inner join tbl_entrada_produto en on pr.id_produto = en.id_produto
where ep.estatus = 1 and mr.estatus = 1 and pr.estatus = 1 and us.estatus = 1 and en.estatus = 1;

drop view if exists vw_notas;
create view vw_notas as 
select nota_compra nota, valor, data_compra emissao, data_vencimento vencimento, cnpj_fornecedor cnpj, estatus, (select table_name from information_schema.columns where table_name = 'tbl_nota_compra' group by table_name) tabela from tbl_nota_compra
union
select nota_servico nota, valor, data_servico emissao, data_vencimento vencimento, cnpj_fornecedor cnpj, estatus, (select table_name from information_schema.columns where table_name = 'tbl_nota_servico' group by table_name) tabela from tbl_nota_servico;
