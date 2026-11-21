-- A separate database for the feature tests, so `composer test` never touches your data.
CREATE DATABASE IF NOT EXISTS addressbook_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
GRANT ALL PRIVILEGES ON addressbook_test.* TO 'addressbook'@'%';
