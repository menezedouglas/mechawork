delimiter //

drop event if exists deslogar_usuario//
create event deslogar_usuario
on schedule every 10 second do 
begin 
	call sp_desloga_usuario_auto();
end
//

drop event if exists notificacao//
create event notificacao
on schedule every 1 minute do 
begin 
	call sp_notificacao();
end//

create event popula_grafico
on schedule every 1 hour  do 
begin
	call popula_grafico();
end
//
SET GLOBAL event_scheduler = on;

