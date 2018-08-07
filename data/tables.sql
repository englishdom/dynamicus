DROP TABLE IF EXISTS hashes;
curl -XPOST 'rqlite:4001/db/execute?pretty' -H "Content-Type: application/json" -d '[
   "DROP TABLE IF EXISTS hashes"
]'

CREATE TABLE hashes (id integer not null primary key, hash varchar(32) unique, message text);
curl -XPOST 'rqlite:4001/db/execute?pretty' -H "Content-Type: application/json" -d '[
   "CREATE TABLE hashes (id integer not null primary key, hash varchar(32) unique, message text)"
]'

DROP TABLE IF EXISTS entities;
curl -XPOST 'rqlite:4001/db/execute?pretty' -H "Content-Type: application/json" -d '[
   "DROP TABLE IF EXISTS entities"
]'

CREATE TABLE entities (id integer not null primary key, hash_id integer not null, entity_name varchar(32), entity_id integer not null);
curl -XPOST 'rqlite:4001/db/execute?pretty' -H "Content-Type: application/json" -d '[
   "CREATE TABLE entities (id integer not null primary key, hash_id integer not null, entity_name varchar(32), entity_id integer not null)"
]'