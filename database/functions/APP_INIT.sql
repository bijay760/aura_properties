DROP FUNCTION IF EXISTS APP_INIT;
DELIMITER $$
CREATE FUNCTION `APP_INIT`(
) RETURNS TINYINT UNSIGNED
    READS SQL DATA
BEGIN

    IF @app_init IS NULL THEN
        SELECT `config` INTO @time_zone FROM `configs` WHERE `name` = 'app.timeZone';
        SET time_zone = @time_zone;
        SET @app_init = 1;
        RETURN 1;
    END IF;

    RETURN NULL;

END$$
DELIMITER ;
