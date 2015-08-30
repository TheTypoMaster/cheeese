-- Ajout des informations studio
ALTER TABLE companies.company ADD studio VARCHAR(255) DEFAULT NULL;
ALTER TABLE companies.company ADD studio_address VARCHAR(255) DEFAULT NULL;
ALTER TABLE companies.company ADD studio_town BIGINT DEFAULT NULL;
CREATE INDEX IDX_F33411212D1D7C3C ON companies.company (studio_town);
ALTER TABLE companies.company ADD CONSTRAINT FK_F33411212D1D7C3C FOREIGN KEY (studio_town) REFERENCES geo.town (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
-- Ajout case cgu pour les devis 
ALTER TABLE photographers.devis ADD cgu SMALLINT DEFAULT 1 NOT NULL;
-- Ajout case cgu pour l'upload des photos du devis
ALTER TABLE photographers.devis_book ADD cgu SMALLINT DEFAULT 1 NOT NULL;