DROP PROCEDURE IF EXISTS sp_get_categories;
DELIMITER $$
CREATE PROCEDURE sp_get_categories()
BEGIN
    SET @q=('SELECT id,name,image, sort_order FROM property_categories WHERE status=1
            ORDER BY sort_order');
    #prepare and execute statement
    PREPARE `statement` FROM @`q`;
    EXECUTE `statement`;
    SELECT FOUND_ROWS() INTO @`found_rows`;
    DEALLOCATE PREPARE `statement`;
END$$
DELIMITER ;
