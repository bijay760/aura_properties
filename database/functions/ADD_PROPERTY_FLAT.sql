DROP FUNCTION IF EXISTS ADD_PROPERTY_FLAT;
DELIMITER $$
CREATE FUNCTION ADD_PROPERTY_FLAT(p_json_data JSON) RETURNS JSON
BEGIN
    DECLARE v_user_id BIGINT;
    DECLARE v_listing_type VARCHAR(10);
    DECLARE v_property_category_id BIGINT;
    DECLARE v_amenities JSON;
    DECLARE v_gallery_images JSON;
    DECLARE v_status VARCHAR(10) DEFAULT 'active';
    DECLARE v_result JSON;
    DECLARE v_json_valid BOOLEAN;

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
        BEGIN
            GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
                @errno = MYSQL_ERRNO,
                @text = MESSAGE_TEXT;
            SET v_result = JSON_OBJECT(
                'code', 500,
                'status', false,
                'message', CONCAT('Error: ', @text),
                'data', JSON_OBJECT()
                           );
            RETURN v_result;
        END;

    -- First validate the JSON structure
    SET v_json_valid = JSON_VALID(p_json_data);
    IF NOT v_json_valid THEN
        SET v_result = JSON_OBJECT(
            'code', 400,
            'status', false,
            'message', 'Invalid JSON input',
            'data', JSON_OBJECT()
                       );
        RETURN v_result;
    END IF;

    -- Check if we have the basic required fields
    IF JSON_CONTAINS_PATH(p_json_data, 'one', '$.user_id') = 0 THEN
        SET v_result = JSON_OBJECT(
            'code', 400,
            'status', false,
            'message', 'Missing required field: user_id',
            'data', JSON_OBJECT()
                       );
        RETURN v_result;
    END IF;

    -- Extract values from JSON input with proper type conversion
    SET v_user_id = CAST(JSON_UNQUOTE(JSON_EXTRACT(p_json_data, '$.user_id')) AS UNSIGNED);

    -- If user_id is still not valid (0 or NULL)
    IF v_user_id IS NULL OR v_user_id = 0 THEN
        SET v_result = JSON_OBJECT(
            'code', 400,
            'status', false,
            'message', 'Invalid user_id',
            'data', JSON_OBJECT()
                       );
        RETURN v_result;
    END IF;

    -- Continue with other fields
    SET v_listing_type = JSON_UNQUOTE(JSON_EXTRACT(p_json_data, '$.listing_type'));
    SET v_property_category_id = CAST(JSON_UNQUOTE(JSON_EXTRACT(p_json_data, '$.property_category_id')) AS UNSIGNED);

    -- Handle JSON fields with validation
    IF JSON_EXTRACT(p_json_data, '$.amenities') IS NOT NULL AND JSON_VALID(JSON_EXTRACT(p_json_data, '$.amenities')) THEN
        SET v_amenities = JSON_EXTRACT(p_json_data, '$.amenities');
    ELSE
        SET v_amenities = JSON_ARRAY();
    END IF;

    IF JSON_EXTRACT(p_json_data, '$.gallery_images') IS NOT NULL AND JSON_VALID(JSON_EXTRACT(p_json_data, '$.gallery_images')) THEN
        SET v_gallery_images = JSON_EXTRACT(p_json_data, '$.gallery_images');
    ELSE
        SET v_gallery_images = JSON_ARRAY();
    END IF;

    -- Insert the property
    INSERT INTO properties (
        user_id,
        listing_type,
        property_category_id,
        covered_area,
        carpet_area,
        total_price,
        is_price_negotiable,
        city,
        locality,
        address,
        total_numbers,
        bedrooms_count,
        bathroom_count,
        balcony_count,
        is_furnishing,
        floor_count,
        total_floors,
        transaction_type,
        availability_status,
        possession,
        approved_by_bank,
        amenities,
        gallery_images,
        flooring_type,
        landmark,
        status,
        created_at
    ) VALUES (
                 v_user_id,
                 v_listing_type,
                 v_property_category_id,
                 JSON_UNQUOTE(JSON_EXTRACT(p_json_data, '$.covered_area')),
                 JSON_UNQUOTE(JSON_EXTRACT(p_json_data, '$.carpet_area')),
                 JSON_UNQUOTE(JSON_EXTRACT(p_json_data, '$.total_price')),
                 JSON_UNQUOTE(JSON_EXTRACT(p_json_data, '$.is_price_negotiable')),
                 JSON_UNQUOTE(JSON_EXTRACT(p_json_data, '$.city')),
                 JSON_UNQUOTE(JSON_EXTRACT(p_json_data, '$.locality')),
                 JSON_UNQUOTE(JSON_EXTRACT(p_json_data, '$.address')),
                 JSON_UNQUOTE(JSON_EXTRACT(p_json_data, '$.total_numbers')),
                 JSON_UNQUOTE(JSON_EXTRACT(p_json_data, '$.bedrooms_count')),
                 JSON_UNQUOTE(JSON_EXTRACT(p_json_data, '$.bathroom_count')),
                 JSON_UNQUOTE(JSON_EXTRACT(p_json_data, '$.balcony_count')),
                 JSON_UNQUOTE(JSON_EXTRACT(p_json_data, '$.is_furnishing')),
                 JSON_UNQUOTE(JSON_EXTRACT(p_json_data, '$.floor_count')),
                 JSON_UNQUOTE(JSON_EXTRACT(p_json_data, '$.total_floors')),
                 JSON_UNQUOTE(JSON_EXTRACT(p_json_data, '$.transaction_type')),
                 JSON_UNQUOTE(JSON_EXTRACT(p_json_data, '$.availability_status')),
                 JSON_UNQUOTE(JSON_EXTRACT(p_json_data, '$.possession')),
                 JSON_UNQUOTE(JSON_EXTRACT(p_json_data, '$.approved_by_bank')),
                 v_amenities,
                 v_gallery_images,
                 JSON_UNQUOTE(JSON_EXTRACT(p_json_data, '$.flooring_type')),
                 JSON_UNQUOTE(JSON_EXTRACT(p_json_data, '$.landmark')),
                 v_status,
                 NOW()
             );

    -- Return success response
    SET v_result = JSON_OBJECT(
        'code', 200,
        'status', true,
        'message', 'Property created successfully',
        'data', JSON_OBJECT('id', LAST_INSERT_ID())
                   );
    RETURN v_result;
END $$

DELIMITER ;
