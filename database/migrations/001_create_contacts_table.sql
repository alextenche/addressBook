CREATE TABLE contacts (
    id            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    first_name    VARCHAR(100)    NOT NULL,
    last_name     VARCHAR(100)    NOT NULL,
    email         VARCHAR(254)    NULL,
    phone         VARCHAR(50)     NULL,
    address_line1 VARCHAR(150)    NULL,
    address_line2 VARCHAR(150)    NULL,
    city          VARCHAR(100)    NULL,
    region        VARCHAR(100)    NULL,
    postal_code   VARCHAR(20)     NULL,
    contact_group ENUM('family', 'friends', 'business') NOT NULL DEFAULT 'friends',
    notes         TEXT            NULL,
    created_at    TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_contacts_name (last_name, first_name),
    KEY idx_contacts_group (contact_group)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
