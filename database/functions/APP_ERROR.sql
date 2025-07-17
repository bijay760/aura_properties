DROP FUNCTION IF EXISTS APP_ERROR;
DELIMITER $$
CREATE FUNCTION `APP_ERROR`(
) RETURNS JSON
    NO SQL
    DETERMINISTIC
BEGIN

RETURN JSON_OBJECT(
    'status', false,
    'data', NULL,
    'message', 'Authentication required.',
    'code', 401
       );

END$$
DELIMITER ;
