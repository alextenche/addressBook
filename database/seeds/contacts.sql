-- Demo data. The first three contacts come from the original 2014 version of this app.
INSERT INTO contacts
    (first_name, last_name, email, phone, address_line1, address_line2, city, region, postal_code, contact_group, notes)
VALUES
    ('Blade', 'Daywalker', 'blade@example.com', '+1 313 555 0142', 'Underground discotheque', 'The garage', 'Detroit', 'Michigan', '48201', 'friends', 'Daywalker. Prefers night-time meetings anyway.'),
    ('Tony', 'Stark', 'tony@example.com', '+1 310 555 0199', 'Huge house on the cliff', 'Under the huge house on the cliff', 'Malibu', 'California', '90265', 'family', 'Don''t forget the oil.'),
    ('Pamela', 'Isley', 'ivy@example.com', '+1 212 555 0167', 'Creepy garden', 'Creepy biodome', 'Gotham', 'New Jersey', '07001', 'friends', 'Pretty poisonous. Bring gloves.'),
    ('Sherlock', 'Holmes', 'sherlock@example.com', '+44 20 7946 0221', '221B Baker Street', NULL, 'London', NULL, 'NW1 6XE', 'business', 'Consulting detective. Texts rather than calls.'),
    ('Ellen', 'Ripley', 'ripley@example.com', NULL, 'Nostromo, deck C', NULL, 'Hadley''s Hope', 'LV-426', NULL, 'business', 'Warrant officer. Has a cat named Jones.');
