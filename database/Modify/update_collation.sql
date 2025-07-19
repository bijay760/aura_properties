SELECT CONCAT(
           'ALTER TABLE `', TABLE_NAME, '` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;'
       )
FROM information_schema.TABLES
WHERE TABLE_SCHEMA = 'aura_properties';




alter table properties
    modify listing_type enum ('sale', 'rent') not null;




-- 1. First, modify the columns to have proper JSON defaults
ALTER TABLE `properties`
    MODIFY COLUMN `additional_rooms` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT '[]' CHECK (json_valid(`additional_rooms`)),
    MODIFY COLUMN `overlooking` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT '[]' CHECK (json_valid(`overlooking`)),
    MODIFY COLUMN `amenities` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT '[]' CHECK (json_valid(`amenities`)),
    MODIFY COLUMN `gallery_images` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT '[]' CHECK (json_valid(`gallery_images`));

