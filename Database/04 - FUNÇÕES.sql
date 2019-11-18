use mecha;

drop function if exists fn_calc_porcentagem;

delimiter &&
create function fn_calc_porcentagem (total float, valor float)
returns float
deterministic
begin 
	
    return round((valor * 100) / total, 2);
    
end &&
delimiter ;