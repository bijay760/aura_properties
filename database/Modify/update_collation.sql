SELECT CONCAT(
           'ALTER TABLE `', TABLE_NAME, '` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;'
       )
FROM information_schema.TABLES
WHERE TABLE_SCHEMA = 'aura_properties';
