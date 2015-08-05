-- schemas

Create SCHEMA companies;
Create SCHEMA geo;
Create SCHEMA messages;
Create SCHEMA photographers;
Create SCHEMA prestations;
Create SCHEMA status;
Create SCHEMA utils;
Create SCHEMA users;



-- Commande de droits
-- usage
grant usage on schema companies to sf2;
grant usage on schema geo to sf2;
grant usage on schema messages to sf2;
grant usage on schema photographers to sf2;
grant usage on schema prestations to sf2;
grant usage on schema status to sf2;
grant usage on schema utils to sf2;
grant usage on schema users to sf2;

-- Opérations
GRANT SELECT, INSERT, UPDATE, DELETE ON all tables in schema companies TO sf2;
GRANT SELECT, INSERT, UPDATE, DELETE ON all tables in schema geo TO sf2;
GRANT SELECT, INSERT, UPDATE, DELETE ON all tables in schema messages TO sf2;
GRANT SELECT, INSERT, UPDATE, DELETE ON all tables in schema photographers TO sf2;
GRANT SELECT, INSERT, UPDATE, DELETE ON all tables in schema prestations TO sf2;
GRANT SELECT, INSERT, UPDATE, DELETE ON all tables in schema status TO sf2;
GRANT SELECT, INSERT, UPDATE, DELETE ON all tables in schema utils TO sf2;
GRANT SELECT, INSERT, UPDATE, DELETE ON all tables in schema users TO sf2;
-- séquences
GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA companies to sf2;
GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA geo to sf2;
GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA messages to sf2;
GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA photographers to sf2;
GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA prestations to sf2;
GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA status to sf2;
GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA utils to sf2;
GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA users to sf2;

/*
GRANT connect on database sf2 to felgharb;
Connexion à distance
GRANT SELECT ON all tables in schema companies TO felgharb;
GRANT SELECT ON all tables in schema geo TO felgharb;
GRANT SELECT ON all tables in schema messages TO felgharb;
GRANT SELECT ON all tables in schema photographers TO felgharb;
GRANT SELECT ON all tables in schema prestations TO felgharb;
GRANT SELECT ON all tables in schema status TO felgharb;
GRANT SELECT ON all tables in schema utils TO felgharb;
GRANT SELECT ON all tables in schema users TO felgharb;
 */
 
