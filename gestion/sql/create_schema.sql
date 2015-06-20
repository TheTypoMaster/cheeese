Create SCHEMA companies;
Create SCHEMA geo;
Create SCHEMA messages;
Create SCHEMA photographers;
Create SCHEMA prestations;
Create SCHEMA status;
Create SCHEMA utils;
Create SCHEMA users;
Create SCHEMA notations;

#GEO TOWN
insert into geo.country (id, name, icon) VALUES (1, 'France', 'France');

-- ------ STATUS
-- --Photographer
insert into status.photographerstatus (id, libelle) 
	VALUES
	(1, 'TO_VERIFY'), (2, 'VERIFICATION_OK'), (3, 'VERIFICATION_KO');
-- --Prestation	
insert into status.prestationstatus (id, libelle) 
	VALUES
	(1, 'CREATED'), (2, 'PRE_CONFIRMED'), (3, 'REFUSED'),
	(4, 'CANCELED')	,(5, 'CONFIRMED'),(6, 'PASSED'), (7, 'CLOSED');

-- ----utils
-- --Category
insert into utils.category (id, country, type, name, icon, active)
	VALUES
	(1, 1, 1, 'Anniversaire', '', 1),(2, 1, 1, 'Bapteme', '', 1),(3, 1, 1, 'Bar-Mitsva', '', 1),
	(4, 1, 1, 'Book', '', 1),(5, 1, 1, 'Brit-Mila', '', 1),(6, 1, 1, 'Communion', '', 1),
	(7, 1, 1, 'EVJF/EVJH', '', 1),(8, 1, 1, 'Fiançailles', '', 1),(9, 1, 1, 'Mariage', '', 1),
	(10, 1, 1, 'Naissance', '', 1),(11, 1, 1, 'Femme enceinte', '', 1),(12, 1, 1, 'Shooting Studio', '', 1),
	(13, 1, 1, 'Shooting Extérieur', '', 1);
-- --Currency
insert into utils.currency (id, libelle)
	VALUES
	(1, 'Euros');
-- --Duration
insert into utils.duration (id, libelle)
	VALUES
	(1, '1h'), (2, '2h'), (3, '3h'),(4, '4h'), (5, '6h'), (6, '8h'), (7, '10h');
