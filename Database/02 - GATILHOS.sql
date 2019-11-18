use mecha;
#criacao de procedures e triggers


delimiter //
drop trigger if exists tgr_Entrada_produto//
create trigger tgr_Entrada_produto after insert on tbl_entrada_produto
for each row
begin
		declare contador int(11);
        declare total_estoque float;
		declare total_entrada float;
        declare id_entra int;
 
    select count(*) into contador from tbl_estoque_produtos where id_estoque = new.id_produto;

    if contador > 0 then
		select max(id_entrada) from tbl_entrada_produto into id_entra;
        
		select (quantia * valor_unitario_medio) into total_estoque from tbl_estoque_produtos 
        where id_estoque=new.id_produto;
        
		select (quantia_comprada * valor_unitario) into total_entrada from tbl_entrada_produto 
        where id_entrada=id_entra;

		update tbl_estoque_produtos set quantia = quantia + new.quantia_comprada
        where id_estoque = new.id_produto;
         
		update tbl_estoque_produtos set valor_unitario_medio = (total_estoque + total_entrada)/quantia where id_estoque = new.id_produto;
	else
		insert into tbl_estoque_produtos(id_estoque,quantia,valor_unitario_medio)
        values (new.id_produto,new.quantia_comprada,new.valor_unitario);
	end if;

end     //

drop trigger if exists trg_Saida_produto//
create trigger trg_Saida_produto after insert on tbl_saida_produto
for each row
begin

		declare total_estoque float;
		declare total_saida float;
        
		update tbl_estoque_produtos estoque set quantia = quantia - new.quantia_usada 
		where new.id_estoque=estoque.id_estoque;
        
		select quantia into total_estoque from tbl_estoque_produtos estoque where new.id_estoque=estoque.id_estoque; 
		
        if total_estoque=0 then
			update tbl_estoque_produtos estoque set valor_unitario_medio = 0
			where new.id_estoque=estoque.id_estoque;
        end if;
		
end //

drop trigger if exists trg_estorna_manutencao//
create trigger trg_estorna_manutencao after update on tbl_manutencao_realizada
for each row
begin
      
		if ((select estatus from tbl_manutencao_realizada where id_manutencao = new.id_manutencao) = 0) and ((select custo_interno from tbl_manutencao_realizada where id_manutencao = new.id_manutencao) != 0) then
        
			update tbl_saida_produto set estatus = 0 where id_manutencao = new.id_manutencao;			
        
        elseif ((select estatus from tbl_manutencao_realizada where id_manutencao = new.id_manutencao) = 0) and ((select nota_servico from tbl_manutencao_realizada where id_manutencao = new.id_manutencao) != 0) then
			
            select nota_servico into @nota from tbl_manutencao_realizada where id_manutencao = new.id_manutencao;
            
            update tbl_nota_servico set estatus = 0 where nota_servico = @nota;
            
        end if;
    		
end //

drop trigger if exists trg_estorna_saida//
create trigger trg_estorna_saida after update on tbl_saida_produto
for each row
begin
	
    select quantia_usada into @qty from tbl_saida_produto where id_saida = new.id_saida and estatus = 0;
	    
    update tbl_estoque_produtos set quantia = quantia + @qty where id_estoque = new.id_estoque;
    
    select sum(valor_unitario) into @vlr from tbl_entrada_produto where id_produto = new.id_estoque and estatus = 1;
    
    select count(id_produto) into @qty from tbl_entrada_produto where id_produto = new.id_estoque;
    
    update tbl_estoque_produtos set valor_unitario_medio = @vlr / @qty where id_estoque = new.id_estoque;
    
end //

drop trigger if exists trg_estorna_nota_compra//
create trigger trg_estorna_nota_compra after update on tbl_nota_compra
for each row
begin
      
		if (select estatus from tbl_nota_compra where nota_compra = new.nota_compra) = 0 then
        
			update tbl_entrada_produto set estatus = 0 where numero_nota = new.nota_compra;			
        
        end if;
    		
end //


drop trigger if exists trg_estorna_entrada//
create trigger trg_estorna_entrada after update on tbl_entrada_produto
for each row
begin
	
	declare qnt_nova int;
    
    select quantia_comprada into @qty from tbl_entrada_produto where id_entrada = new.id_entrada and estatus = 0;
	  
	select quantia into @est from tbl_estoque_produtos where id_estoque = new.id_produto;
    
    if @qty > @est then
		
        set qnt_nova = 0;
        
    else
    
		set qnt_nova = @est - @qty;
    
    end if;
      
    update tbl_estoque_produtos set quantia = qnt_nova where id_estoque = new.id_produto;
    
    select count(id_entrada) into @entradas from tbl_entrada_produto where id_produto = new.id_produto and estatus = 1;
        
	select sum(valor_unitario) into @vlr_unitario from tbl_entrada_produto where id_produto = new.id_produto and estatus = 1;
        
	update tbl_estoque_produtos set valor_unitario_medio = @vlr_unitario / @entradas where id_estoque = new.id_produto;   
    
end //

drop trigger if exists trg_relatorio_veiculo//
create trigger trg_relatorio_veiculo after insert on tbl_manutencao_realizada
for each row
begin
	#set @horas =convert(timestampdiff(minute,new.hora_iniciada,new.hora_finalizada )/60,decimal(5,2));
    select timestampdiff(minute,new.hora_iniciada,new.hora_finalizada) into @horas;
	if  new.nota_servico>0 then
			select valor into @custo from tbl_nota_servico where nota_servico=new.nota_servico;
			select placa_numero into @placa from tbl_cad_frota where id_veiculo=new.id_veiculo;
			insert into tbl_relatorio_veiculo(identificacao_veiculo,manutencao,valor,data_realizada,hora_gasta)
			values(@placa,new.id_manutencao,@custo,new.data_realizada,@horas);
        else    
			select placa_numero into @placa from tbl_cad_frota where id_veiculo=new.id_veiculo;
			insert into tbl_relatorio_veiculo(identificacao_veiculo,manutencao,valor,data_realizada,hora_gasta)
			values(@placa,new.id_manutencao,new.custo_interno,new.data_realizada,@horas);
        end if;    
end
//