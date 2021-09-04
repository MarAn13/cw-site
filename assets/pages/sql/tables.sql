CREATE TABLE users
(
    id bigserial NOT NULL,
    username text NOT NULL,
    email text NOT NULL,
    password text NOT NULL,
    user_type text[] NOT NULL,
    CONSTRAINT users_id_primary PRIMARY KEY (id),
    CONSTRAINT users_username_unique UNIQUE (username),
    CONSTRAINT users_email_unique UNIQUE (email)
);

CREATE TABLE clients
(
    id bigserial NOT NULL,
    user_id bigint NOT NULL,
    order_id bigint[],
    total_pages bigint NOT NULL,
    CONSTRAINT clients_id_primary PRIMARY KEY (id),
    CONSTRAINT clients_order_id_unique UNIQUE (order_id),
    CONSTRAINT clients_user_id_unique UNIQUE (user_id),
    CONSTRAINT clients_user_id_foreign FOREIGN KEY (user_id)
        REFERENCES users (id)
);

CREATE TABLE specialists
(
    id bigserial NOT NULL,
    user_id bigint NOT NULL,
    place_of_operation polygon[],
    current_orders_num bigint DEFAULT 0,
    CONSTRAINT specialists_id_primary PRIMARY KEY (id),
    CONSTRAINT specialists_user_id_foreign FOREIGN KEY (user_id)
        REFERENCES users (id)
);

CREATE TABLE admins
(
    id bigserial NOT NULL,
    user_id bigint NOT NULL,
    CONSTRAINT admins_id_primary PRIMARY KEY (id),
    CONSTRAINT admins_user_id_foreign FOREIGN KEY (user_id)
        REFERENCES users (id)
);

CREATE TABLE orders
(
    id bigserial NOT NULL,
    client_id bigint NOT NULL,
    specialist_id bigint,
    place_of_operation polygon[] NOT NULL,
    video_type text NOT NULL,
    video_source text,
    rating bigint,
    report_message text,
    date_of_creation timestamp with time zone NOT NULL,
    expire_date timestamp with time zone NOT NULL,
    date_of_completion timestamp with time zone,
    status text NOT NULL,
    CONSTRAINT orders_id_primary PRIMARY KEY (id),
    CONSTRAINT orders_client_id_foreign FOREIGN KEY (client_id)
        REFERENCES clients (id),
    CONSTRAINT orders_specialist_id_foreign FOREIGN KEY (specialist_id)
        REFERENCES specialists (id)
);

CREATE TABLE chat_rooms
(
    id bigserial NOT NULL,
    order_id bigint NOT NULL,
    client_id bigint NOT NULL,
    specialist_id bigint NOT NULL,
    message text[],
    message_sender text[],
    message_time timestamp with time zone,
    CONSTRAINT chat_rooms_id_primary PRIMARY KEY (id),
    CONSTRAINT chat_rooms_order_id_foreign FOREIGN KEY (order_id)
        REFERENCES orders (id),
    CONSTRAINT chat_rooms_order_id_unique UNIQUE (order_id),
    CONSTRAINT chat_rooms_client_id_foreign FOREIGN KEY (client_id)
        REFERENCES clients (id),
    CONSTRAINT chat_rooms_specialist_id_foreign FOREIGN KEY (specialist_id)
        REFERENCES specialists (id)
);