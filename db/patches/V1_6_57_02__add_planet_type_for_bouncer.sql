INSERT INTO planet_type (planet_type_id, planet_type_name, planet_type_description, planet_image_link, planet_max_attackers, planet_max_landed) VALUES
(3, 
'Dwarf Planet', 
'A smaller than usual planet, with no native life present.', 
'images/planet3.png', 
'5', 
'0');

INSERT INTO planet_type_has_section (planet_type_id, planet_section) VALUES
('3', 'CONSTRUCTION'),
('3', 'DEFENSE'),
('3', 'FINANCE'),
('3', 'STOCKPILE'),
('3', 'OWNERSHIP');

INSERT INTO planet_can_build (planet_type_id, construction_id, max_amount, cost_time, cost_credit, exp_gain) VALUES 
('3', '1', '10', '10800', '100000', '90'),
('3', '2', '85', '21600', '100000', '180'),
('3', '3', '5', '64800', '1000000', '540'),
('3', '4', '0', '10000', '50000', '90');
