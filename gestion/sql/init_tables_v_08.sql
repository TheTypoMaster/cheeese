------ STATUS
-- --Photographer
insert into status.photographerstatus (id, libelle) 
	VALUES
	(4, 'SUSPENDED');
-- Prestation status
insert into status.prestationstatus (id, libelle) 
	VALUES
	(9, 'CANCELED_PHOTOGRAPHER'), (10, 'CANCELED_CLIENT'), (11, 'LITIGE_CLIENT'),
	(12, 'LITIGE_PHOTOGRAPHER');