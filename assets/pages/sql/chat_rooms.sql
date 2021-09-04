CREATE TABLE chat_rooms
(
    id bigserial NOT NULL,
    order_id bigint NOT NULL,
    client_id bigint NOT NULL,
    specialist_id bigint NOT NULL,
    message text[],
    message_sender text[],
    message_time timestamp with time zone[],
    CONSTRAINT chat_rooms_id_primary PRIMARY KEY (id)
);