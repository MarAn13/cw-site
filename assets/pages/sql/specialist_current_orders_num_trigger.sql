create or replace function on_insert_specialist_accept_order()
returns trigger as $$
declare
current_records bigint;
begin
select current_orders_num from specialists where id = NEW.specialist_id into current_records;
update specialists set current_orders_num = (current_records + 1) where id = NEW.specialist_id;
return NEW;
end $$
language plpgsql;
create trigger specialist_current_orders_num
before update on orders
for each row
when (OLD.status != NEW.status and NEW.status = 'processing')
execute function on_insert_specialist_accept_order();