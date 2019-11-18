use mecha;
delimiter //
create procedure sp_cadastra_usuario (nome varchar(50),sobrenome varchar(50),telefone varchar(15) ,email varchar(100),senha varchar(100) , nivel int, out retorno varchar(100))
begin
insert into tbl_usuario(nome,sobrenome,telefone,email,nivel)
values (nome,sobrenome,telefone,email,nivel);

insert into tbl_senha(senha,id_usuario)
values (senha,(select max(id_usuario) from tbl_usuario));

set retorno = 'cadastrado com sucesso';
end				
//

create procedure sp_alterar_senha(email varchar(100),senha varchar (100))
begin
declare contador int(11);

select id_usuario into @id from tbl_usuario usuario
where usuario.email=email;

 select count(*) into contador from tbl_senha where id_usuario = @id and tbl_senha.senha=senha;

if contador = 0 then
	update tbl_senha set estatus=0
	where id_usuario=@id;
	insert into tbl_senha(senha,id_usuario)
	values(senha,@id);	
end if;
end//

drop procedure if exists sp_entrada_estoque //
create procedure sp_entrada_estoque(cnpj bigint,nome varchar(155),telefone varchar(15), valor_total float,valor_unitario text,
 nota bigint, data_emissao date, data_vencimento date,codigo_prod text,quantia text,itens int)
begin

declare contador int;
declare contProd int;
declare aumento int;

set aumento =1;
set contProd = 0;
set contador = 0;

while contador = 0 do #loop para cadastrar a nota fiscal e fazer entrada dos produtos em estoque
	select count(*) into contador from tbl_nota_compra where nota_compra = nota; #select q verifica se nota fiscal ja existe
		if contador > 0 then #caso nota fiscal exista entrara no if para cadastrar os itens
			while contProd < itens do # loop para cadastrar cada, contProd é a quantia de itens cadastrados e itens é a quantia de itens diferentes a serem cadastradas
        
				select substring_index(substring_index(codigo_prod,'|',aumento),'|',-1) into @codProd; #funcao para pegar o codigo de kd produto e atribuir numa variavel
				select substring_index(substring_index(quantia,'|',aumento),'|',-1) into @quantiaProd; #funcao para pegar a quantidade de kd produto e atribuir numa variavel
				set @quantia = convert (@quantiaProd,unsigned); # convertendo quantidade string para int
				select substring_index(substring_index(valor_unitario,'|',aumento),'|',-1) into @valor; #funcao para pegar o valor de kd produto e atribuir numa variavel
				set @valor=replace(@valor,',','.'); #funcao para substituir as virgulas por ponto
				set @valor= convert (@valor, decimal(10, 2)); #funcao para converte o valor dos produtos de string para float
				select id_produto into @id from tbl_produto where codigo=@codProd; #select para pegar o id do produto se baseando no codigo dele
		
				insert into tbl_entrada_produto(data_entrada,quantia_comprada,valor_unitario,id_produto,numero_nota)
				values(now(),@quantiaProd,@valor,@id,nota); #insercao dos dados do produto
			
				select count(*) into contProd from tbl_entrada_produto where numero_nota=nota; #armazenado quandos produtos ja foram cadastrados
				set aumento=aumento+1; #variavel para pegar o proximo produto a ser cadastrado
			end while;   
		else #caso n tenha a nota cadastrada ira cadastrar automaticamente
			select count(*) into @fornecedor from tbl_fornecedor f where f.cnpj=cnpj;
			if @fornecedor = 0 then
				call sp_cadastra_fornecedor(cnpj , nome,telefone);
			else
				insert into tbl_nota_compra(nota_compra,valor,data_compra,data_vencimento,cnpj_fornecedor,numero_itens)
				values(nota,valor_total,data_emissao,data_vencimento,cnpj,itens);
			end if;
	end if;
end while;
end//

create procedure sp_cadastra_fornecedor(cnpj bigint,nome varchar(30),telefone varchar(15))
 begin
	insert into tbl_fornecedor(cnpj,nome,telefone)
    values(cnpj,nome,telefone);
end//
drop procedure  if exists sp_login//
create procedure sp_login(email varchar(100), senha varchar(100))
begin 
	
		select * from tbl_usuario u, tbl_senha s 
		where u.id_usuario = s.id_usuario and u.email = email and s.senha = senha and u.estatus != 0 and s.estatus = 1;
        
        if(select estatus from tbl_usuario u , tbl_senha s where u.email = email and s.senha=senha) = 1 then
			update tbl_usuario u set estatus = 2, u.hora_clique = now() where u.email = email;
		end if;
          
end //	

drop procedure if exists sp_manutencao_futura//
create procedure sp_manutencao_futura(data_prev date,descricao varchar(200),placa varchar (15),usuario int,data_min date,quilometro int)
begin
select id_veiculo into @id from tbl_cad_frota where placa_numero=placa;

insert into tbl_manutencao_futura(data_prevista,data_minima,quilometragem,descricao,id_veiculo,id_usuario)
values (data_prev,data_min,quilometro,descricao,@id,usuario);

end
// 

drop procedure if exists sp_manutencao_realizada_interna//
create procedure sp_manutencao_realizada_interna (hora_inicio datetime, hora_fim datetime,usuario int, descricao varchar(255), placa varchar(10),obra varchar(20),custo float)
begin
	select id_veiculo into @id from tbl_cad_frota where placa_numero=placa;

	insert into tbl_manutencao_realizada(hora_iniciada,hora_finalizada,descricao,id_usuario,id_veiculo,id_obra,custo_interno)
    values(hora_inicio,hora_fim,descricao,usuario,@id,obra,custo);
end//

drop procedure if exists sp_manutencao_realizada_externa//
create procedure sp_manutencao_realizada_externa (hora_inicio datetime, hora_fim datetime, descricao varchar(255),usuario int, placa varchar(10),nota bigint,obra int, valor float, data_nota date, data_vencimento date, fornecedor bigint, razao_social varchar(155), telefone varchar(15))
begin
	select id_veiculo into @id from tbl_cad_frota where placa_numero=placa;
        
    if (select count(*) from tbl_fornecedor where cnpj = fornecedor) = 0 then
		
		call sp_cadastra_fornecedor(fornecedor, razao_social, telefone);
        
    end if;
        
    if(select count(*) from tbl_nota_servico where nota_servico = nota) = 0 then
		
        insert into tbl_nota_servico (nota_servico, valor, data_servico, data_vencimento, cnpj_fornecedor, estatus)
        values(nota, valor, data_nota, data_vencimento, fornecedor, 1);
        
        select nota_servico into @id_nota from tbl_nota_servico where nota_servico = nota;
        
    else 
    
		select nota_servico into @id_nota from tbl_nota_servico where nota_servico = nota;
    
	end if;
    
	insert into tbl_manutencao_realizada(hora_iniciada,hora_finalizada,descricao,id_usuario,id_veiculo,nota_servico,id_obra,custo_interno)
    values(hora_inicio,hora_fim,descricao,usuario,@id,@id_nota,obra,null);
		
end//

drop procedure if exists sp_consulta // 
create procedure sp_consulta(tabela varchar(150),coluna varchar(150),parametro varchar(150))
begin

    set @consulta = concat('select * from ', tabela, ' where ', coluna, ' like ' ,' "%',parametro,'%"',' and estatus=1' );
	prepare stmt from @consulta;
	execute stmt;
    deallocate prepare  stmt;
    
end
//

drop procedure if exists  sp_desloga_usuario_auto//
create procedure sp_desloga_usuario_auto()
begin

set @contador=1;
select max(id_usuario) into @numero from tbl_usuario ;
	while  @numero >= @contador do
		select timestampdiff(minute,hora_clique,now()) into @horas from tbl_usuario where id_usuario=@contador and estatus=2;
        select tempo_minimo into @relativo from tbl_configuracao;
			if @horas >= @relativo then
				update tbl_usuario set estatus=1 where id_usuario=@contador;
				set @contador = @contador +1;
			else
				set @contador = @contador +1;
			end if;
	end while;
 end   
//


create procedure sp_usuario_ativo(id int)
begin
	update tbl_usuario set hora_clique=now(), estatus = 2 where id_usuario=id;
end
//


create procedure sp_exclui(tabela varchar(150),id varchar(20),parametro varchar(150))
begin

    set @consulta = concat('update ', tabela,' set estatus = 0  where id_usuario = ', id );
	prepare stmt from @consulta;
	execute stmt;
    deallocate prepare  stmt;
end//

drop procedure if exists sp_estorno_nota_compra//
create procedure sp_estorno_nota_compra(nota bigint(20))
begin        

	if (select estatus from tbl_nota_compra where nota_compra = nota) = 1 then #Se o estatus da nota for 1
    
		update tbl_nota_compra set estatus = 0 where nota_compra = nota and estatus = 1;
        
	else
		
		insert into tbl_nota_compra (nota_compra) values (nota);
        
    end if;
    
end//

drop procedure if exists sp_estorno_manutencao_interna//
create procedure sp_estorno_manutencao_interna(id int)
begin        

	if((select estatus from tbl_manutencao_realizada where id_manutencao = id) = 1) and ((select custo_interno from tbl_manutencao_realizada where id_manutencao = id) != 0) then #Se o estatus da entrada for 1
    
		update tbl_manutencao_realizada set estatus = 0 where id_manutencao = id and estatus = 1;
        
	else
		
		insert into tbl_manutencao_realizada (id_manutencao) values (id);
        
    end if;
    
end//

create procedure sp_desloga_usuario(id int)
begin
	update tbl_usuario set estatus=1 where id_usuario=id and estatus=2;
end//

drop procedure if exists  sp_notificacao//
create procedure sp_notificacao()
begin
declare saida boolean default false;
declare data_min date;
declare data_prev date;
declare km bigint;
declare descr varchar(200);
declare id int;
declare veiculo int;

declare acabou boolean default false;

declare verificacao cursor for
select id_futura,data_prevista, data_minima,quilometragem, descricao,id_veiculo from tbl_manutencao_futura where estatus=1;

declare continue handler for not found set acabou=true;

open verificacao;
	verifica: loop
		fetch verificacao into id,data_prev,data_min,km,descr,veiculo;
        select placa_numero into @placa from tbl_cad_frota where id_veiculo = veiculo;
        select margem_notifica into @tempo from tbl_configuracao where id_config = 1;
        if acabou then
			leave verifica;
         elseif data_min = 0 then
			if (select timestampdiff(day,now(),data_prev)) <= @tempo then
				if(select count(*) from tbl_notificacao where id_futura=id)=0 then
					insert into tbl_notificacao(id_futura,placa,data_cadastrada,descricao)
					values(id,@placa,data_prev,descr);
                 end if;   
             end if;
         else
			if (select timestampdiff(day,now(),data_min)) <= @tempo then
				if(select count(*) from tbl_notificacao where id_futura=id)=0 then
					insert into tbl_notificacao(id_futura,placa,quilometragem,data_cadastrada,descricao)
					values(id,@placa,km,data_min,descr);
                 end if;   
			end if;
         end if;
	end loop verifica;
close verificacao;    
end//

drop procedure if exists popula_grafico//
create procedure popula_grafico()
begin
declare quatro time default '17:00:00';
declare tres time default '12:00:00';
if now() >= tres and now()  <= quatro  then
	drop table if exists tbl_grafico_p;
	create table tbl_grafico_p(
		total bigint,
		mes_compras int,
		mes_interno int,
		mes_externo int);
	insert into tbl_grafico_p 
		select(ifnull(valor_compras,0)+ifnull(gasto_interno,0)+ifnull(gasto_externo,0)) total, (ifnull(mes_compras,0))
		mes_compras,(ifnull(mes_interno,0)) mes_interno,(ifnull(mes_externo,0))  mes_externo from vw_custo_mensal;
end if;
end
//

drop procedure if exists grafico_area//
create procedure grafico_area()
begin
	set @externo = 0, @interno = 0, @compras = 0;
	select ifnull(sum(gasto_externo),0) into @externo from vw_custo_externo where ano_externo = year(now());
	select ifnull(sum(gasto_interno),0) into @interno from vw_custo_interno where ano_interna = year(now());
	select ifnull(sum(valor_compras),0) into @compras from vw_nota where ano_compras = year(now());
	select sum(@externo + @interno + @compras) into @total;

	select fn_calc_porcentagem(@total, @externo) externo, fn_calc_porcentagem(@total, @interno) interno, fn_calc_porcentagem(@total, @compras) compras;
end //