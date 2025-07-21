DROP PROCEDURE IF EXISTS GET_PROPERTIES_MINE;
DELIMITER $$
CREATE PROCEDURE GET_PROPERTIES_MINE(IN DATA JSON)
BEGIN
    DECLARE V_SESSION JSON;
    DECLARE V_USER_ID BIGINT;
    DECLARE V_LISTING_TYPE VARCHAR(32);
    DECLARE V_PROPERTY_CATEGORY_ID BIGINT;
    DECLARE V_STATUS VARCHAR(21);

    SET V_SESSION = APP_SESSION();
    IF V_SESSION IS NULL THEN
        SELECT APP_ERROR();
    END IF;

    SET V_USER_ID = JSON_VALUE(V_SESSION, '$.user_id');
    SET V_LISTING_TYPE = JSON_VALUE(DATA, '$.listing_type');
    SET V_PROPERTY_CATEGORY_ID = JSON_VALUE(DATA, '$.property_category_id');
    SET V_STATUS = JSON_VALUE(DATA, '$.status');

    -- Start building the base query
    SET @q = CONCAT('SELECT * FROM properties WHERE user_id = ', V_USER_ID);

    -- Add conditions only if parameters are not NULL
    IF V_LISTING_TYPE !='' THEN
        SET @q = CONCAT(@q, ' AND listing_type = "', V_LISTING_TYPE, '"');
    END IF;

    IF V_PROPERTY_CATEGORY_ID >0 THEN
        SET @q = CONCAT(@q, ' AND property_category_id = ', V_PROPERTY_CATEGORY_ID);
    END IF;

    IF V_STATUS!='' THEN
        SET @q = CONCAT(@q, ' AND status = "', V_STATUS, '"');
    END IF;

    -- Add ordering
    SET @q = CONCAT(@q, ' ORDER BY created_at DESC');

    PREPARE stmt FROM @q;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END $$
DELIMITER ;
