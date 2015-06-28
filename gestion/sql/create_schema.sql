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
	(4, 'CANCELED')	,(5, 'CONFIRMED'),(6, 'PASSED'), (7, 'DELIVERED'),
	(8, 'CLOSED');

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

-- --User
INSERT INTO users.user (id, username, username_canonical, email, email_canonical, enabled, salt, password, last_login, locked, expired, expires_at, confirmation_token, password_requested_at, roles, credentials_expired, credentials_expire_at,note, prestations, createdAt, updatedAt)
    VALUES 
(1,'admin','admin','f_tamega@hotmail.com','f_tamega@hotmail.com',TRUE,'15x862pxtlj4w8wwggs4co8k0kcw0so','/HrsZQEEiMRK/H0QiwuBTRIVFMJ0APaSuvEHPzbrCt0MYwM7RgsR7xS7QripDb2UtLWv4bq4nWCPoDk/Rx3Org==',NOW(),FALSE,FALSE,NULL,NULL,NULL,'a:1:{i:0;s:16:"ROLE_SUPER_ADMIN";}',FALSE,NULL,0,0,NOW(),NOW());