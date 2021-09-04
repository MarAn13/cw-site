create or replace function on_update_specialist_reduce_num_order()
returns trigger as $$
declare
current_records bigint;
begin
select current_orders_num from specialists where id = OLD.specialist_id into current_records;
update specialists set current_orders_num = (current_records - 1) where id = OLD.specialist_id;
return NEW;
end $$
language plpgsql;
create trigger specialist_current_orders_num_reduce
before update on orders
for each row
when (OLD.status != NEW.status and (NEW.status = 'completed' or 
(OLD.status = 'processing' and NEW.status = 'waiting') or 
(OLD.status = 'processing' and NEW.status = 'canceled')))
execute function on_update_specialist_reduce_num_order();