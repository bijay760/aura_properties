DROP FUNCTION IF EXISTS `fn_user_detail`;
DELIMITER $$
CREATE FUNCTION `fn_user_detail`()
    RETURNS JSON
BEGIN
    DECLARE `v_email` VARCHAR(64);
    DECLARE `v_phone` varchar(32);

    DECLARE V_USERID BIGINT UNSIGNED;
    DECLARE V_SESSION JSON;

    #return variable
    DECLARE `v_payload` JSON;

    SET V_SESSION = APP_SESSION();
    IF V_SESSION IS NULL THEN
        RETURN APP_RETURN(false, 1032, NULL);
    END IF;

    DO APP_INIT();

    SET V_USERID = JSON_VALUE(V_SESSION, '$.user_id');

    SELECT email,
           phone
    INTO v_email, v_phone
    FROM users
    WHERE id = V_USERID;

#set payload
    SET `v_payload` = JSON_OBJECT(
        'user_id', V_USERID,
        'email', `v_email`,
        'phone', v_phone
                      );
    RETURN APP_RETURN(true, 1037, `v_payload`);
END$$
DELIMITER ;
