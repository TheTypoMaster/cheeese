CREATE TABLE notations.client (client INT NOT NULL, prestation BIGINT NOT NULL, prestation_notation INT DEFAULT NULL, prestation_comment TEXT DEFAULT NULL, photographer_notation INT DEFAULT NULL, photographer_comment TEXT DEFAULT NULL, createdAt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updatedAt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(client, prestation));
CREATE INDEX IDX_B02BEF3BC7440455 ON notations.client (client);
CREATE INDEX IDX_B02BEF3B51C88FAD ON notations.client (prestation);
CREATE TABLE notations.photographer (photographer INT NOT NULL, prestation BIGINT NOT NULL, client_notation INT DEFAULT NULL, client_comment TEXT DEFAULT NULL, createdAt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updatedAt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(photographer, prestation));
CREATE INDEX IDX_49327B1B16337A7F ON notations.photographer (photographer);
CREATE INDEX IDX_49327B1B51C88FAD ON notations.photographer (prestation);
CREATE TABLE users."user" (id INT NOT NULL, username VARCHAR(255) NOT NULL, username_canonical VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, email_canonical VARCHAR(255) NOT NULL, enabled BOOLEAN NOT NULL, salt VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, last_login TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, locked BOOLEAN NOT NULL, expired BOOLEAN NOT NULL, expires_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, confirmation_token VARCHAR(255) DEFAULT NULL, password_requested_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, roles TEXT NOT NULL, credentials_expired BOOLEAN NOT NULL, credentials_expire_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, presentation TEXT DEFAULT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, telephone VARCHAR(255) DEFAULT NULL, photo VARCHAR(255) DEFAULT NULL, note FLOAT, prestations INT NOT NULL, premium_end TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, birthdate TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, createdAt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updatedAt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id));
CREATE UNIQUE INDEX UNIQ_86A8513892FC23A8 ON users."user" (username_canonical);
CREATE UNIQUE INDEX UNIQ_86A85138A0D96FBF ON users."user" (email_canonical);
COMMENT ON COLUMN users."user".roles IS '(DC2Type:array)';
CREATE TABLE photographers.moves (company INT NOT NULL, town BIGINT NOT NULL, createdAt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(company, town));
CREATE INDEX IDX_DD3DCAD14FBF094F ON photographers.moves (company);
CREATE INDEX IDX_DD3DCAD14CE6C7A4 ON photographers.moves (town);
CREATE TABLE photographers.devis_prices (devis BIGINT NOT NULL, duration BIGINT NOT NULL, price FLOAT, active SMALLINT DEFAULT 1 NOT NULL, createdAt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updatedAt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(devis, duration));
CREATE INDEX IDX_B482338B8B27C52B ON photographers.devis_prices (devis);
CREATE INDEX IDX_B482338B865F80C0 ON photographers.devis_prices (duration);
CREATE TABLE photographers.devis (id BIGSERIAL NOT NULL, company INT DEFAULT NULL, category BIGINT DEFAULT NULL, currency BIGINT DEFAULT NULL, title VARCHAR(255) NOT NULL, presentation TEXT NOT NULL, directpay SMALLINT DEFAULT 0 NOT NULL, note FLOAT, prestations INT NOT NULL, active SMALLINT DEFAULT 1 NOT NULL, createdAt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updatedAt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id));
CREATE INDEX IDX_132507C84FBF094F ON photographers.devis (company);
CREATE INDEX IDX_132507C864C19C1 ON photographers.devis (category);
CREATE INDEX IDX_132507C86956883F ON photographers.devis (currency);
CREATE TABLE photographers.devis_book (id BIGSERIAL NOT NULL, PRIMARY KEY(id));
CREATE TABLE photographers.availability (company INT NOT NULL, day DATE NOT NULL, createdAt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(company, day));
CREATE INDEX IDX_53EB3BA14FBF094F ON photographers.availability (company);
CREATE TABLE photographers.moves_radius (company INT NOT NULL, radius INT NOT NULL, createdAt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updatedAt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(company));
CREATE TABLE prestations.evaluation (scorer INT NOT NULL, prestation BIGINT NOT NULL, prestation_notation INT DEFAULT NULL, prestation_comment TEXT DEFAULT NULL, user_notation INT DEFAULT NULL, user_comment TEXT DEFAULT NULL, createdAt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updatedAt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(scorer, prestation));
CREATE INDEX IDX_439376705707C8 ON prestations.evaluation (scorer);
CREATE INDEX IDX_43937651C88FAD ON prestations.evaluation (prestation);
CREATE TABLE prestations.prestation (id BIGSERIAL NOT NULL, devis BIGINT DEFAULT NULL, client INT DEFAULT NULL, town BIGINT DEFAULT NULL, duration BIGINT DEFAULT NULL, status BIGINT DEFAULT NULL, reference VARCHAR(50) NOT NULL, price FLOAT, address VARCHAR(255) NOT NULL, start_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, createdAt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updatedAt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id));
CREATE UNIQUE INDEX UNIQ_42A8B9AEAEA34913 ON prestations.prestation (reference);
CREATE INDEX IDX_42A8B9AE8B27C52B ON prestations.prestation (devis);
CREATE INDEX IDX_42A8B9AEC7440455 ON prestations.prestation (client);
CREATE INDEX IDX_42A8B9AE4CE6C7A4 ON prestations.prestation (town);
CREATE INDEX IDX_42A8B9AE865F80C0 ON prestations.prestation (duration);
CREATE INDEX IDX_42A8B9AE7B00651C ON prestations.prestation (status);
CREATE TABLE utils.duration (id BIGSERIAL NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id));
CREATE TABLE utils.currency (id BIGSERIAL NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id));
CREATE TABLE utils.language (id BIGSERIAL NOT NULL, PRIMARY KEY(id));
CREATE TABLE utils.category (id BIGSERIAL NOT NULL, country BIGINT DEFAULT NULL, type SMALLINT NOT NULL, name VARCHAR(255) NOT NULL, icon VARCHAR(255) NOT NULL, active SMALLINT DEFAULT 1 NOT NULL, PRIMARY KEY(id));
CREATE INDEX IDX_1E2C41995373C966 ON utils.category (country);
CREATE TABLE geo.town (id BIGSERIAL NOT NULL, country BIGINT DEFAULT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, latitude NUMERIC(9, 7) NOT NULL, longitude NUMERIC(9, 7) NOT NULL, PRIMARY KEY(id));
CREATE INDEX IDX_8C7321DB5373C966 ON geo.town (country);
CREATE TABLE geo.country (id BIGSERIAL NOT NULL, name VARCHAR(255) NOT NULL, icon VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id));
CREATE TABLE status.prestationstatus (id BIGSERIAL NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id));
CREATE TABLE status.photographerstatus (id BIGSERIAL NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id));
CREATE TABLE companies.transactions (id BIGSERIAL NOT NULL, PRIMARY KEY(id));
CREATE TABLE companies.company (photographer INT NOT NULL, town BIGINT DEFAULT NULL, country BIGINT DEFAULT NULL, status BIGINT DEFAULT NULL, title VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, identification VARCHAR(50) NOT NULL, createdAt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updatedAt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(photographer));
CREATE UNIQUE INDEX UNIQ_F334112149E7720D ON companies.company (identification);
CREATE INDEX IDX_F33411214CE6C7A4 ON companies.company (town);
CREATE INDEX IDX_F33411215373C966 ON companies.company (country);
CREATE INDEX IDX_F33411217B00651C ON companies.company (status);
CREATE TABLE companies.iban (id BIGSERIAL NOT NULL, PRIMARY KEY(id));
CREATE TABLE messages.message (id BIGSERIAL NOT NULL, sender INT DEFAULT NULL, receiver INT DEFAULT NULL, prestation BIGINT DEFAULT NULL, type SMALLINT NOT NULL, read SMALLINT DEFAULT 0 NOT NULL, content TEXT NOT NULL, createdAt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updatedAt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id));
CREATE INDEX IDX_CFECF5AA5F004ACF ON messages.message (sender);
CREATE INDEX IDX_CFECF5AA3DB88C96 ON messages.message (receiver);
CREATE INDEX IDX_CFECF5AA51C88FAD ON messages.message (prestation);
CREATE SEQUENCE users.user_id_seq INCREMENT BY 1 MINVALUE 1 START 1;
ALTER TABLE notations.client ADD CONSTRAINT FK_B02BEF3BC7440455 FOREIGN KEY (client) REFERENCES users."user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE notations.client ADD CONSTRAINT FK_B02BEF3B51C88FAD FOREIGN KEY (prestation) REFERENCES prestations.prestation (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE notations.photographer ADD CONSTRAINT FK_49327B1B16337A7F FOREIGN KEY (photographer) REFERENCES users."user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE notations.photographer ADD CONSTRAINT FK_49327B1B51C88FAD FOREIGN KEY (prestation) REFERENCES prestations.prestation (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE photographers.moves ADD CONSTRAINT FK_DD3DCAD14FBF094F FOREIGN KEY (company) REFERENCES companies.company (photographer) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE photographers.moves ADD CONSTRAINT FK_DD3DCAD14CE6C7A4 FOREIGN KEY (town) REFERENCES geo.town (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE photographers.devis_prices ADD CONSTRAINT FK_B482338B8B27C52B FOREIGN KEY (devis) REFERENCES photographers.devis (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE photographers.devis_prices ADD CONSTRAINT FK_B482338B865F80C0 FOREIGN KEY (duration) REFERENCES utils.duration (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE photographers.devis ADD CONSTRAINT FK_132507C84FBF094F FOREIGN KEY (company) REFERENCES companies.company (photographer) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE photographers.devis ADD CONSTRAINT FK_132507C864C19C1 FOREIGN KEY (category) REFERENCES utils.category (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE photographers.devis ADD CONSTRAINT FK_132507C86956883F FOREIGN KEY (currency) REFERENCES utils.currency (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE photographers.availability ADD CONSTRAINT FK_53EB3BA14FBF094F FOREIGN KEY (company) REFERENCES companies.company (photographer) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE photographers.moves_radius ADD CONSTRAINT FK_F207A1894FBF094F FOREIGN KEY (company) REFERENCES companies.company (photographer) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE prestations.evaluation ADD CONSTRAINT FK_439376705707C8 FOREIGN KEY (scorer) REFERENCES users."user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE prestations.evaluation ADD CONSTRAINT FK_43937651C88FAD FOREIGN KEY (prestation) REFERENCES prestations.prestation (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE prestations.prestation ADD CONSTRAINT FK_42A8B9AE8B27C52B FOREIGN KEY (devis) REFERENCES photographers.devis (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE prestations.prestation ADD CONSTRAINT FK_42A8B9AEC7440455 FOREIGN KEY (client) REFERENCES users."user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE prestations.prestation ADD CONSTRAINT FK_42A8B9AE4CE6C7A4 FOREIGN KEY (town) REFERENCES geo.town (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE prestations.prestation ADD CONSTRAINT FK_42A8B9AE865F80C0 FOREIGN KEY (duration) REFERENCES utils.duration (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE prestations.prestation ADD CONSTRAINT FK_42A8B9AE7B00651C FOREIGN KEY (status) REFERENCES status.prestationstatus (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE utils.category ADD CONSTRAINT FK_1E2C41995373C966 FOREIGN KEY (country) REFERENCES geo.country (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE geo.town ADD CONSTRAINT FK_8C7321DB5373C966 FOREIGN KEY (country) REFERENCES geo.country (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE companies.company ADD CONSTRAINT FK_F334112116337A7F FOREIGN KEY (photographer) REFERENCES users."user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE companies.company ADD CONSTRAINT FK_F33411214CE6C7A4 FOREIGN KEY (town) REFERENCES geo.town (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE companies.company ADD CONSTRAINT FK_F33411215373C966 FOREIGN KEY (country) REFERENCES geo.country (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE companies.company ADD CONSTRAINT FK_F33411217B00651C FOREIGN KEY (status) REFERENCES status.photographerstatus (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE messages.message ADD CONSTRAINT FK_CFECF5AA5F004ACF FOREIGN KEY (sender) REFERENCES users."user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE messages.message ADD CONSTRAINT FK_CFECF5AA3DB88C96 FOREIGN KEY (receiver) REFERENCES users."user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE messages.message ADD CONSTRAINT FK_CFECF5AA51C88FAD FOREIGN KEY (prestation) REFERENCES prestations.prestation (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
