create or replace function on_insert_update_client_order()
returns trigger as $$
declare
record_num bigint;
total_page_num bigint;
records_per_page bigint := 3;
begin
if TG_OP = 'INSERT' then
select count(*) + 1 from orders where client_id = NEW.client_id and "status" != 'canceled' into record_num;
select ceil(record_num::float / records_per_page) into total_page_num;
update clients set total_pages = total_page_num where id = NEW.client_id;
end if;
if TG_OP = 'UPDATE' and NEW.status = 'canceled' then
select count(*) - 1 from orders where client_id = OLD.client_id and "status" != 'canceled' into record_num;
select ceil(record_num::float / records_per_page) into total_page_num;
update clients set total_pages = total_page_num where id = OLD.client_id;
end if;
return NEW;
end $$
language plpgsql;
create trigger client_page_update
before insert or update on orders
for each row
execute function on_insert_update_client_order();